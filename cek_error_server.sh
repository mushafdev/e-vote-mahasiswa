#!/usr/bin/env bash
# Kumpulkan log error E-Vote (Laravel + Face API + nginx + DB) ke satu file.
#
# Pakai (di server):
#   sudo bash cek_error_server.sh                     # 24 jam terakhir
#   sudo SINCE=6h bash cek_error_server.sh            # 6 jam terakhir
#   sudo LARAVEL_DIR=/var/www/e_vote bash cek_error_server.sh
#
# Hasil: /tmp/evote_diagnosa_<tanggal>.txt — kirim file itu.
# Script ini hanya MEMBACA, tidak mengubah apa pun. Password tidak dicetak.

SINCE="${SINCE:-24h}"
OUT="/tmp/evote_diagnosa_$(date +%Y%m%d_%H%M).txt"
FACE_CONTAINER="${FACE_CONTAINER:-evote_face_api}"

sec() { printf '\n\n==================== %s ====================\n' "$1"; }

{
sec "INFO SERVER"
date; uptime; hostname
echo "--- CPU:"; nproc
echo "--- Memori:"; free -h
echo "--- Disk:"; df -h / /var 2>/dev/null

sec "OOM KILLER (proses dibunuh karena RAM habis)"
(dmesg -T 2>/dev/null || journalctl -k --since "-$SINCE" 2>/dev/null) \
  | grep -iE "out of memory|oom-kill|killed process" | tail -30 || echo "(tidak ada)"

sec "DOCKER"
if command -v docker >/dev/null; then
  docker ps -a --format 'table {{.Names}}\t{{.Status}}\t{{.Image}}'
  echo; docker stats --no-stream --format 'table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}' 2>/dev/null
  echo; echo "--- Restart count & OOMKilled $FACE_CONTAINER:"
  docker inspect -f 'RestartCount={{.RestartCount}} OOMKilled={{.State.OOMKilled}} StartedAt={{.State.StartedAt}}' "$FACE_CONTAINER" 2>/dev/null

  sec "FACE API — ERROR/TIMEOUT/WORKER ($SINCE terakhir)"
  docker logs --since "$SINCE" "$FACE_CONTAINER" 2>&1 \
    | grep -iE "error|exception|traceback|timeout|worker|killed|signal|memory|booting|exit" \
    | tail -150

  sec "FACE API — Ringkasan status HTTP ($SINCE terakhir)"
  docker logs --since "$SINCE" "$FACE_CONTAINER" 2>&1 \
    | grep -oE '"(POST|GET) [^ ]+ HTTP/[0-9.]+" [0-9]{3}' | sort | uniq -c | sort -rn

  sec "FACE API — 60 baris log terakhir"
  docker logs --tail 60 "$FACE_CONTAINER" 2>&1
else
  echo "docker tidak ada"
fi

sec "LARAVEL"
if [ -z "$LARAVEL_DIR" ]; then
  LARAVEL_DIR=$(find /var/www /home /opt /srv /root -maxdepth 4 -name artisan 2>/dev/null \
    | xargs -r grep -l "Laravel" 2>/dev/null | head -1 | xargs -r dirname)
fi
echo "LARAVEL_DIR=$LARAVEL_DIR"
if [ -n "$LARAVEL_DIR" ] && [ -d "$LARAVEL_DIR/storage/logs" ]; then
  ls -lt "$LARAVEL_DIR/storage/logs" | head -8
  LOGF=$(ls -t "$LARAVEL_DIR"/storage/logs/laravel*.log 2>/dev/null | head -1)
  echo "File log: $LOGF"

  sec "LARAVEL — Jumlah error per jenis (hari ini)"
  grep -E "^\[$(date +%Y-%m-%d)" "$LOGF" | grep -E "\.(ERROR|CRITICAL|WARNING)" \
    | sed -E 's/^\[[^]]+\] //' | cut -c1-160 | sort | uniq -c | sort -rn | head -40

  sec "LARAVEL — 80 baris ERROR terakhir (tanpa stack trace)"
  grep -E "^\[[0-9]{4}-" "$LOGF" | grep -E "\.(ERROR|CRITICAL|WARNING)" | tail -80 | cut -c1-400

  sec "LARAVEL — Stack trace error terakhir"
  awk '/^\[[0-9]{4}-.*\.ERROR/{buf=""; f=1} f{buf=buf $0 "\n"} END{printf "%s", buf}' "$LOGF" | head -60

  sec "DATABASE — face_verify_logs"
  ENVF="$LARAVEL_DIR/.env"
  getenv() { grep -E "^$1=" "$ENVF" | head -1 | cut -d= -f2- | tr -d '"'"'"; }
  DBH=$(getenv DB_HOST); DBP=$(getenv DB_PORT); DBN=$(getenv DB_DATABASE)
  DBU=$(getenv DB_USERNAME); export MYSQL_PWD=$(getenv DB_PASSWORD)
  Q="SELECT DATE(created_at) tgl, aksi, \`match\`, LEFT(keterangan,70) keterangan, COUNT(*) n
       FROM face_verify_logs WHERE created_at >= NOW() - INTERVAL 2 DAY
       GROUP BY tgl, aksi, \`match\`, keterangan ORDER BY tgl DESC, n DESC;
     SELECT id, pemilih_id, aksi, \`match\`, is_real, spoof_score, confidence, threshold,
            LEFT(keterangan,70) keterangan, created_at
       FROM face_verify_logs WHERE \`match\`=0 ORDER BY id DESC LIMIT 40;
     SELECT COUNT(*) total_pemilih, SUM(kandidat_id IS NOT NULL) sudah_vote FROM pemilihs;"
  if command -v mysql >/dev/null && [ -n "$DBN" ]; then
    mysql -h "${DBH:-127.0.0.1}" -P "${DBP:-3306}" -u "$DBU" "$DBN" -t -e "$Q" 2>&1
  else
    MC=$(docker ps --format '{{.Names}}' 2>/dev/null | grep -iE "mysql|maria|db" | head -1)
    if [ -n "$MC" ]; then
      docker exec -e MYSQL_PWD="$MYSQL_PWD" "$MC" mysql -u "$DBU" "$DBN" -t -e "$Q" 2>&1
    else
      echo "mysql client tidak ditemukan"
    fi
  fi
  unset MYSQL_PWD

  sec "LARAVEL — Config penting (tanpa password)"
  grep -E "^(APP_ENV|APP_DEBUG|APP_URL|SESSION_DRIVER|QUEUE_CONNECTION|FACE_API_URL)=" "$ENVF"
else
  echo "Folder Laravel tidak ditemukan. Jalankan ulang dengan LARAVEL_DIR=/path/ke/laravel"
fi

sec "NGINX — error log ($SINCE terakhir, 80 baris)"
for f in /var/log/nginx/error.log /var/log/nginx/*error*.log; do
  [ -f "$f" ] && { echo "--- $f"; tail -80 "$f"; }
done | awk '!seen[$0]++'

sec "NGINX — status 4xx/5xx di access log (hari ini)"
TODAY=$(date +%d/%b/%Y)
for f in /var/log/nginx/access.log /var/log/nginx/*access*.log; do
  [ -f "$f" ] && grep "$TODAY" "$f"
done | awk '$9 ~ /^(4|5)[0-9][0-9]$/ {split($7,u,"?"); print $9, $6, u[1]}' \
  | tr -d '"' | sort | uniq -c | sort -rn | head -30
grep -rhE "client_max_body_size|fastcgi_read_timeout|proxy_read_timeout" /etc/nginx 2>/dev/null | sort -u

sec "PHP-FPM — error/timeout"
ls /var/log/php*fpm*.log 2>/dev/null | while read f; do echo "--- $f"; tail -40 "$f"; done
php -i 2>/dev/null | grep -E "^(upload_max_filesize|post_max_size|max_execution_time|memory_limit)"

} > "$OUT" 2>&1

echo "Selesai. Hasil: $OUT ($(wc -l < "$OUT") baris)"
