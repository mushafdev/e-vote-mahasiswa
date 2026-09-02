#!/usr/bin/env python3
"""
Load test Face API — simulasi registrasi/verifikasi WAJAH SERENTAK.

Tujuan: menguji apakah server sanggup menangani "burst" (mis. 40 mahasiswa
menekan Registrasi bersamaan) TANPA harus uji langsung di kelas. Ini menembak
endpoint yang kemarin jebol (koneksi di-reset: cURL 52/55/56) dan melaporkan
berapa yang sukses, latensi, serta jenis error.

Butuh SATU foto wajah yang jelas (ada wajahnya) — endpoint register memakai
deteksi wajah (MTCNN), jadi gambar kosong akan ditolak. Foto ini dipakai ulang
untuk semua "mahasiswa" simulasi (kita menguji kapasitas, bukan akurasi).

Contoh pemakaian (jalankan di server, setelah container Face API baru aktif):

    # 40 request register, semua bersamaan, tiap request 5 foto
    python3 scripts/loadtest.py \
        --url http://localhost:5055 \
        --key evote_face_secret_2024 \
        --image /path/ke/foto_wajah.jpg \
        --concurrency 40 --total 40 --photos 5

    # Bersihkan embedding hasil tes setelah selesai
    python3 scripts/loadtest.py --url http://localhost:5055 --key ... --cleanup --total 40

Verdict di akhir: kalau success rate ~100% dan tak ada ConnectionError/Timeout,
berarti perbaikan gunicorn sudah bekerja dan sistem siap untuk kelas.
"""
import argparse
import base64
import concurrent.futures as cf
import statistics
import sys
import time

try:
    import requests
except ImportError:
    sys.exit("Modul 'requests' belum terpasang. Jalankan: pip install requests")


PREFIX = "loadtest_"   # pemilih_id simulasi: loadtest_0, loadtest_1, ...


def load_image_b64(path: str) -> str:
    with open(path, "rb") as f:
        return base64.b64encode(f.read()).decode("ascii")


def do_register(url, key, pemilih_id, images_b64, photos, timeout):
    payload = {"pemilih_id": pemilih_id, "images_base64": [images_b64] * photos}
    t0 = time.perf_counter()
    try:
        r = requests.post(
            f"{url}/api/face/register",
            json=payload,
            headers={"X-API-Key": key},
            timeout=timeout,
        )
        dt = time.perf_counter() - t0
        ok = r.status_code == 200 and bool((r.json() or {}).get("success"))
        return {"id": pemilih_id, "ok": ok, "status": r.status_code, "dt": dt,
                "err": None if ok else _short(r.text)}
    except requests.exceptions.RequestException as e:
        dt = time.perf_counter() - t0
        # ConnectionError / Timeout / dsb = gejala server jebol (yang kita cari)
        return {"id": pemilih_id, "ok": False, "status": None, "dt": dt,
                "err": type(e).__name__}


def do_verify(url, key, pemilih_id, images_b64, timeout):
    payload = {"pemilih_id": pemilih_id, "image_base64": images_b64}
    t0 = time.perf_counter()
    try:
        r = requests.post(
            f"{url}/api/face/verify",
            json=payload,
            headers={"X-API-Key": key},
            timeout=timeout,
        )
        dt = time.perf_counter() - t0
        body = r.json() or {}
        ok = r.status_code == 200 and bool(body.get("success"))
        return {"id": pemilih_id, "ok": ok, "status": r.status_code, "dt": dt,
                "match": body.get("match"), "err": None if ok else _short(r.text)}
    except requests.exceptions.RequestException as e:
        dt = time.perf_counter() - t0
        return {"id": pemilih_id, "ok": False, "status": None, "dt": dt,
                "match": None, "err": type(e).__name__}


def do_delete(url, key, pemilih_id, timeout):
    try:
        requests.delete(f"{url}/api/face/delete/{pemilih_id}",
                        headers={"X-API-Key": key}, timeout=timeout)
        return True
    except requests.exceptions.RequestException:
        return False


def _short(text, n=80):
    text = (text or "").replace("\n", " ").strip()
    return text[:n]


def summarize(results, title):
    n = len(results)
    ok = [r for r in results if r["ok"]]
    fail = [r for r in results if not r["ok"]]
    lat = [r["dt"] for r in results]

    print(f"\n=== {title} ===")
    print(f"Total request     : {n}")
    print(f"Sukses            : {len(ok)}  ({100*len(ok)/n:.0f}%)")
    print(f"Gagal             : {len(fail)}")
    if lat:
        lat_sorted = sorted(lat)
        p95 = lat_sorted[min(len(lat_sorted) - 1, int(0.95 * len(lat_sorted)))]
        print(f"Latensi (detik)   : min {min(lat):.1f} | median {statistics.median(lat):.1f} "
              f"| p95 {p95:.1f} | max {max(lat):.1f}")
    if fail:
        # Kelompokkan jenis error
        by_err = {}
        for r in fail:
            k = r["err"] or f"HTTP {r['status']}"
            by_err[k] = by_err.get(k, 0) + 1
        print("Rincian kegagalan :")
        for k, v in sorted(by_err.items(), key=lambda x: -x[1]):
            print(f"   - {v}x  {k}")

    # Verdict
    drop_signals = [r for r in fail if r["err"] in
                    ("ConnectionError", "ReadTimeout", "ConnectTimeout", "Timeout", "ChunkedEncodingError")]
    print("\nVERDICT:")
    if len(ok) == n:
        print("  ✅ Semua request sukses — server sanggup menangani beban ini.")
    elif drop_signals:
        print(f"  ❌ {len(drop_signals)} request putus koneksi/timeout — server masih kewalahan "
              f"pada beban ini (turunkan concurrency, atau kurangi worker/foto).")
    else:
        print("  ⚠️  Ada kegagalan non-koneksi (lihat rincian di atas).")
    return len(ok), n


def main():
    ap = argparse.ArgumentParser(description="Load test Face API (simulasi burst).")
    ap.add_argument("--url", default="http://localhost:5055")
    ap.add_argument("--key", required=True, help="API key (X-API-Key)")
    ap.add_argument("--image", help="Path foto wajah untuk simulasi (wajib kecuali --cleanup)")
    ap.add_argument("--concurrency", type=int, default=40, help="Berapa request BERSAMAAN")
    ap.add_argument("--total", type=int, default=40, help="Total request")
    ap.add_argument("--photos", type=int, default=5, help="Jumlah foto per register")
    ap.add_argument("--timeout", type=int, default=180, help="Timeout per request (detik)")
    ap.add_argument("--verify", action="store_true",
                    help="Setelah register, uji verifikasi serentak juga")
    ap.add_argument("--cleanup", action="store_true",
                    help="Hapus embedding hasil tes (loadtest_*) lalu keluar")
    args = ap.parse_args()

    if args.cleanup:
        print(f"Menghapus {args.total} embedding hasil tes (loadtest_0..{args.total-1})...")
        done = sum(do_delete(args.url, args.key, f"{PREFIX}{i}", 30) for i in range(args.total))
        print(f"Selesai. {done} permintaan hapus terkirim.")
        return

    if not args.image:
        sys.exit("--image wajib diisi (foto wajah jelas). Contoh: --image foto.jpg")

    img_b64 = load_image_b64(args.image)
    ids = [f"{PREFIX}{i}" for i in range(args.total)]

    print(f"Target   : {args.url}")
    print(f"Skenario : {args.total} register, {args.concurrency} bersamaan, {args.photos} foto/request")
    print("Menembak... (ini mensimulasikan burst registrasi kelas)")

    t0 = time.perf_counter()
    reg_results = []
    with cf.ThreadPoolExecutor(max_workers=args.concurrency) as ex:
        futs = [ex.submit(do_register, args.url, args.key, pid, img_b64, args.photos, args.timeout)
                for pid in ids]
        for fut in cf.as_completed(futs):
            reg_results.append(fut.result())
    wall = time.perf_counter() - t0

    ok, n = summarize(reg_results, "REGISTER (burst)")
    print(f"\nTotal waktu dinding (semua selesai): {wall:.1f} detik")

    if args.verify:
        print("\nMenguji VERIFIKASI serentak (memakai foto yang sama)...")
        ver_results = []
        with cf.ThreadPoolExecutor(max_workers=args.concurrency) as ex:
            futs = [ex.submit(do_verify, args.url, args.key, pid, img_b64, args.timeout)
                    for pid in ids]
            for fut in cf.as_completed(futs):
                ver_results.append(fut.result())
        summarize(ver_results, "VERIFY (burst)")
        matched = sum(1 for r in ver_results if r.get("match"))
        print(f"Cocok (match=true): {matched}/{len(ver_results)} "
              f"(wajar tinggi karena foto register = foto verify)")

    print(f"\nBersihkan embedding tes dengan:\n"
          f"  python3 scripts/loadtest.py --url {args.url} --key <KEY> --cleanup --total {args.total}")


if __name__ == "__main__":
    main()
