#!/usr/bin/env python3
"""
Evaluasi anti-spoofing (Presentation Attack Detection / PAD) untuk sistem E-Voting.

Mereplikasi mekanisme produksi (face_service.check_spoof): DeepFace.extract_faces
dengan anti_spoofing=True — sebuah presentasi dinilai `is_real` (bonafide) atau spoof.

Mengukur seberapa baik sistem menolak serangan foto cetak / video replay, memakai
metrik standar ISO/IEC 30107-3:
  - APCER (Attack Presentation Classification Error Rate)
           = proporsi SERANGAN yang keliru dianggap asli   (makin kecil makin baik)
  - BPCER (Bonafide Presentation Classification Error Rate)
           = proporsi WAJAH ASLI yang keliru dianggap spoof (makin kecil makin baik)
  - ACER  = (APCER + BPCER) / 2
  - Attack Detection Rate = 1 - APCER  (persentase serangan yang berhasil ditolak)

Struktur dataset:
  pad_dataset/
    bonafide/            wajah asli/live: a1.jpg a2.jpg ...
    attack/
      print/             foto cetak: p1.jpg ...
      replay/            video replay di layar (screenshot/foto layar): r1.jpg ...
      <tipe_lain>/       opsional (mis. mask)

Pemakaian:
  python evaluate_antispoof.py --dataset ./pad_dataset --out ./pad_output
"""

import argparse
import json
import os
import sys

import numpy as np


DETECTOR_BACKEND = os.getenv("DETECTOR_BACKEND", "mtcnn")


def check_spoof(img_path, deepface):
    """
    Kembalikan (is_real: bool|None, score: float). None bila wajah gagal terdeteksi.
    Meniru face_service.check_spoof pada sistem produksi.
    """
    try:
        faces = deepface.extract_faces(
            img_path=img_path,
            detector_backend=DETECTOR_BACKEND,
            enforce_detection=True,
            anti_spoofing=True,
        )
        if not faces:
            return None, 0.0
        face = faces[0]
        return bool(face.get("is_real", False)), float(face.get("antispoof_score", 0.0))
    except Exception as e:  # noqa: BLE001
        print(f"  [SKIP] wajah tidak terdeteksi: {img_path} ({e})")
        return None, None


def _list_images(d):
    if not os.path.isdir(d):
        return []
    exts = (".jpg", ".jpeg", ".png", ".bmp", ".webp")
    return [os.path.join(d, f) for f in sorted(os.listdir(d)) if f.lower().endswith(exts)]


def classify_folder(folder, deepface):
    """Kembalikan list (is_real, score) untuk semua gambar valid di folder (rekursif 1 level)."""
    results = []
    n_fail = 0
    for f in _list_images(folder):
        is_real, score = check_spoof(f, deepface)
        if is_real is None:
            n_fail += 1
            continue
        results.append((is_real, score))
    return results, n_fail


def main():
    ap = argparse.ArgumentParser(description="Evaluasi anti-spoofing (PAD) E-Voting")
    ap.add_argument("--dataset", required=True, help="Folder dataset PAD (lihat docstring)")
    ap.add_argument("--out", default="./pad_output", help="Folder output")
    args = ap.parse_args()
    os.makedirs(args.out, exist_ok=True)

    bonafide_dir = os.path.join(args.dataset, "bonafide")
    attack_dir = os.path.join(args.dataset, "attack")
    if not os.path.isdir(bonafide_dir) or not os.path.isdir(attack_dir):
        sys.exit(f"Butuh folder '{bonafide_dir}' dan '{attack_dir}'.")

    print("Memuat DeepFace (anti-spoofing)...")
    from deepface import DeepFace

    # ── Bonafide (wajah asli) ────────────────────────────────────────────────
    print("\n[1/2] Menilai presentasi bonafide (wajah asli)...")
    bona, bona_fail = classify_folder(bonafide_dir, DeepFace)
    n_bona = len(bona)
    if n_bona == 0:
        sys.exit("Tidak ada bonafide valid.")
    bona_real = sum(1 for r, _ in bona if r)          # benar: dianggap asli
    bpcer = (n_bona - bona_real) / n_bona              # asli tapi ditolak
    print(f"  bonafide valid: {n_bona} | diterima asli: {bona_real} | gagal deteksi: {bona_fail}")

    # ── Attack (per tipe) ─────────────────────────────────────────────────────
    print("\n[2/2] Menilai presentasi serangan (spoof) per tipe...")
    attack_types = sorted(
        d for d in os.listdir(attack_dir)
        if os.path.isdir(os.path.join(attack_dir, d))
    )
    if not attack_types:  # gambar langsung di attack/ tanpa subfolder tipe
        attack_types = ["_all"]

    per_type = {}
    all_attack = []
    for t in attack_types:
        tdir = attack_dir if t == "_all" else os.path.join(attack_dir, t)
        res, fail = classify_folder(tdir, DeepFace)
        n = len(res)
        if n == 0:
            print(f"  [WARN] tipe '{t}' kosong")
            continue
        accepted_as_real = sum(1 for r, _ in res if r)   # salah: spoof lolos
        apcer_t = accepted_as_real / n
        per_type[t] = {
            "n": n,
            "lolos_sebagai_asli": accepted_as_real,
            "APCER_persen": round(apcer_t * 100, 2),
            "detection_rate_persen": round((1 - apcer_t) * 100, 2),
            "gagal_deteksi": fail,
        }
        all_attack.extend(res)
        print(f"  {t}: {n} serangan | lolos: {accepted_as_real} | "
              f"APCER: {apcer_t*100:.2f}% | ditolak: {(1-apcer_t)*100:.2f}%")

    n_attack = len(all_attack)
    if n_attack == 0:
        sys.exit("Tidak ada serangan valid.")
    attack_accepted = sum(1 for r, _ in all_attack if r)
    apcer = attack_accepted / n_attack
    acer = (apcer + bpcer) / 2
    accuracy = ((bona_real) + (n_attack - attack_accepted)) / (n_bona + n_attack)

    summary = {
        "config": {"detector": DETECTOR_BACKEND, "anti_spoofing": True},
        "dataset": {"bonafide": n_bona, "attack": n_attack,
                    "bonafide_gagal_deteksi": bona_fail},
        "APCER_persen": round(apcer * 100, 2),
        "BPCER_persen": round(bpcer * 100, 2),
        "ACER_persen": round(acer * 100, 2),
        "attack_detection_rate_persen": round((1 - apcer) * 100, 2),
        "accuracy_persen": round(accuracy * 100, 2),
        "per_tipe_serangan": per_type,
    }
    with open(os.path.join(args.out, "pad_metrics.json"), "w") as f:
        json.dump(summary, f, indent=2)

    print("\n" + "=" * 52)
    print("  HASIL ANTI-SPOOFING (untuk pembahasan keamanan)")
    print("=" * 52)
    print(f"  APCER (serangan lolos)      : {summary['APCER_persen']}%")
    print(f"  BPCER (asli ditolak)        : {summary['BPCER_persen']}%")
    print(f"  ACER                        : {summary['ACER_persen']}%")
    print(f"  Attack detection rate       : {summary['attack_detection_rate_persen']}%")
    print(f"  Akurasi keseluruhan         : {summary['accuracy_persen']}%")
    print("=" * 52)
    print(f"\nOutput tersimpan di: {args.out}/pad_metrics.json")


if __name__ == "__main__":
    main()
