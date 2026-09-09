#!/usr/bin/env python3
"""
Evaluasi akurasi face recognition untuk penelitian E-Voting.

Mereplikasi pipeline sistem produksi:
  - Detektor : MTCNN
  - Model    : Facenet512 (embedding 512-dim, pra-terlatih)
  - Enroll   : rata-rata beberapa embedding (template per subjek)
  - Cocok    : cosine similarity, terima bila >= threshold (default 0.60)

Menghasilkan (untuk mengisi Tabel 3, Gambar 7, dan pengganti Gambar 8 di artikel):
  - metrics.json / metrics.csv  → Akurasi, Presisi, Recall, F1, FAR, FRR, EER, AUC
  - roc_curve.png               → Gambar 7 (Kurva ROC)
  - similarity_distribution.png → pengganti Gambar 8 (distribusi genuine vs impostor)
  - threshold_sweep.csv         → FAR/FRR/akurasi per threshold (lampiran)

Struktur dataset yang diharapkan:
  dataset/
    subjek_01/
      enroll/   f1.jpg f2.jpg f3.jpg f4.jpg f5.jpg   (5 foto: depan/kiri/kanan/menunduk/mendongak)
      test/     t1.jpg t2.jpg ... tN.jpg             (foto uji sesi berbeda, variasi kondisi)
    subjek_02/
      ...

Pemakaian:
  python evaluate_accuracy.py --dataset ./eval_dataset --threshold 0.60 --out ./eval_output
"""

import argparse
import json
import os
import sys

import numpy as np


# ── Ekstraksi embedding (samakan dengan sistem produksi) ─────────────────────
FACE_MODEL = os.getenv("FACE_MODEL", "Facenet512")
DETECTOR_BACKEND = os.getenv("DETECTOR_BACKEND", "mtcnn")

# Batas sisi terpanjang gambar sebelum deteksi (0 = tanpa batas). Diisi dari
# argumen --max-dim di main(). Set ke 640 untuk MENIRU produksi (yang men-
# downscale gambar besar), atau 0 untuk memakai resolusi penuh. Menjalankan
# eval dua kali (0 vs 640) → bukti empiris pengaruh downscaling ke akurasi.
_MAX_DIM = int(os.getenv("FACE_MAX_IMAGE_DIM", "0"))


def get_embedding(img_path, deepface):
    """Ekstrak 1 embedding dari sebuah gambar. Return None bila wajah gagal terdeteksi."""
    try:
        img_input = img_path
        if _MAX_DIM and _MAX_DIM > 0:
            import cv2
            img = cv2.imread(img_path)
            if img is not None:
                h, w = img.shape[:2]
                longest = max(h, w)
                if longest > _MAX_DIM:
                    scale = _MAX_DIM / float(longest)
                    img = cv2.resize(img, (max(1, int(w * scale)), max(1, int(h * scale))),
                                     interpolation=cv2.INTER_AREA)
                img_input = img  # samakan preprocessing dengan produksi (BGR ndarray)
        res = deepface.represent(
            img_path=img_input,
            model_name=FACE_MODEL,
            detector_backend=DETECTOR_BACKEND,
            enforce_detection=True,
        )
        return np.array(res[0]["embedding"], dtype=np.float64)
    except Exception as e:  # noqa: BLE001
        print(f"  [SKIP] wajah tidak terdeteksi: {img_path} ({e})")
        return None


def average_embeddings(embeddings):
    """Rata-rata beberapa embedding lalu L2-normalisasi (sama seperti face_service)."""
    arr = np.array(embeddings)
    avg = np.mean(arr, axis=0)
    return avg / np.linalg.norm(avg)


def cosine_similarity(a, b):
    return float(np.dot(a, b) / (np.linalg.norm(a) * np.linalg.norm(b)))


# ── Pipeline evaluasi ────────────────────────────────────────────────────────
def build_dataset(dataset_dir, deepface):
    """Bangun template enroll per subjek + kumpulan embedding uji per subjek."""
    subjects = sorted(
        d for d in os.listdir(dataset_dir)
        if os.path.isdir(os.path.join(dataset_dir, d))
    )
    if not subjects:
        sys.exit(f"Tidak ada folder subjek di {dataset_dir}")

    templates = {}   # subjek -> template embedding (enroll)
    test_embs = {}   # subjek -> list embedding uji
    n_enroll_fail = n_test_fail = 0

    for subj in subjects:
        subj_dir = os.path.join(dataset_dir, subj)
        enroll_dir = os.path.join(subj_dir, "enroll")
        test_dir = os.path.join(subj_dir, "test")

        # Enrollment (template)
        embs = []
        for f in sorted(_list_images(enroll_dir)):
            e = get_embedding(f, deepface)
            if e is not None:
                embs.append(e)
            else:
                n_enroll_fail += 1
        if not embs:
            print(f"  [WARN] subjek {subj} tidak punya enroll valid — dilewati")
            continue
        templates[subj] = average_embeddings(embs)

        # Test
        tembs = []
        for f in sorted(_list_images(test_dir)):
            e = get_embedding(f, deepface)
            if e is not None:
                tembs.append(e)
            else:
                n_test_fail += 1
        test_embs[subj] = tembs
        print(f"  {subj}: {len(embs)} enroll, {len(tembs)} uji")

    return templates, test_embs, n_enroll_fail, n_test_fail


def _list_images(d):
    if not os.path.isdir(d):
        return []
    exts = (".jpg", ".jpeg", ".png", ".bmp", ".webp")
    return [os.path.join(d, f) for f in os.listdir(d) if f.lower().endswith(exts)]


def score_pairs(templates, test_embs):
    """Hasilkan skor cosine untuk semua pasangan genuine & impostor."""
    genuine, impostor = [], []
    for subj, tembs in test_embs.items():
        for te in tembs:
            for tmpl_subj, tmpl in templates.items():
                s = cosine_similarity(tmpl, te)
                if tmpl_subj == subj:
                    genuine.append(s)
                else:
                    impostor.append(s)
    return np.array(genuine), np.array(impostor)


def metrics_at(threshold, genuine, impostor):
    """Hitung metrik verifikasi pada satu threshold cosine."""
    tp = int(np.sum(genuine >= threshold))   # genuine diterima
    fn = int(np.sum(genuine < threshold))    # genuine ditolak
    tn = int(np.sum(impostor < threshold))   # impostor ditolak
    fp = int(np.sum(impostor >= threshold))  # impostor diterima

    total = tp + fn + tn + fp
    accuracy = (tp + tn) / total if total else 0.0
    precision = tp / (tp + fp) if (tp + fp) else 0.0
    recall = tp / (tp + fn) if (tp + fn) else 0.0          # = TAR = 1 - FRR
    f1 = (2 * precision * recall / (precision + recall)) if (precision + recall) else 0.0
    far = fp / (fp + tn) if (fp + tn) else 0.0             # impostor diterima
    frr = fn / (fn + tp) if (fn + tp) else 0.0             # genuine ditolak

    return {
        "threshold": round(threshold, 4),
        "accuracy": round(accuracy * 100, 2),
        "precision": round(precision * 100, 2),
        "recall": round(recall * 100, 2),
        "f1_score": round(f1 * 100, 2),
        "FAR": round(far * 100, 2),
        "FRR": round(frr * 100, 2),
        "TP": tp, "FN": fn, "TN": tn, "FP": fp,
    }


def compute_roc_eer(genuine, impostor):
    """ROC (FAR vs TAR), AUC, dan Equal Error Rate memakai sklearn."""
    from sklearn.metrics import roc_curve, auc

    y = np.concatenate([np.ones_like(genuine), np.zeros_like(impostor)])
    scores = np.concatenate([genuine, impostor])
    fpr, tpr, thr = roc_curve(y, scores)   # fpr = FAR, tpr = TAR = 1 - FRR
    roc_auc = auc(fpr, tpr)

    fnr = 1 - tpr
    eer_idx = int(np.nanargmin(np.abs(fpr - fnr)))
    eer = (fpr[eer_idx] + fnr[eer_idx]) / 2
    eer_threshold = thr[eer_idx]
    return fpr, tpr, thr, roc_auc, eer, eer_threshold


# ── Grafik ───────────────────────────────────────────────────────────────────
def plot_roc(fpr, tpr, roc_auc, eer, op_far, op_tar, out_path):
    import matplotlib.pyplot as plt

    plt.figure(figsize=(6, 5))
    plt.plot(fpr, tpr, label=f"ROC (AUC = {roc_auc:.4f})", linewidth=2)
    plt.plot([0, 1], [0, 1], "k--", linewidth=0.8, label="Acak")
    plt.scatter([op_far], [op_tar], color="red", zorder=5,
                label=f"Operating point (thr=0.60)")
    plt.xlabel("False Positive Rate (FAR)")
    plt.ylabel("True Positive Rate (TAR = 1 - FRR)")
    plt.title(f"Kurva ROC Model Face Recognition (EER = {eer*100:.2f}%)")
    plt.legend(loc="lower right")
    plt.grid(alpha=0.3)
    plt.tight_layout()
    plt.savefig(out_path, dpi=300)
    plt.close()
    print(f"  ✓ {out_path}")


def plot_distribution(genuine, impostor, threshold, out_path):
    import matplotlib.pyplot as plt

    plt.figure(figsize=(7, 5))
    bins = np.linspace(0, 1, 50)
    plt.hist(impostor, bins=bins, alpha=0.6, label=f"Impostor (n={len(impostor)})", density=True)
    plt.hist(genuine, bins=bins, alpha=0.6, label=f"Genuine (n={len(genuine)})", density=True)
    plt.axvline(threshold, color="red", linestyle="--", label=f"Threshold = {threshold}")
    plt.xlabel("Cosine Similarity")
    plt.ylabel("Densitas")
    plt.title("Distribusi Similarity: Genuine vs Impostor")
    plt.legend()
    plt.grid(alpha=0.3)
    plt.tight_layout()
    plt.savefig(out_path, dpi=300)
    plt.close()
    print(f"  ✓ {out_path}")


# ── Main ─────────────────────────────────────────────────────────────────────
def main():
    ap = argparse.ArgumentParser(description="Evaluasi akurasi face recognition E-Voting")
    ap.add_argument("--dataset", required=True, help="Folder dataset (lihat struktur di docstring)")
    ap.add_argument("--threshold", type=float, default=0.60, help="Operating threshold cosine (default 0.60)")
    ap.add_argument("--out", default="./eval_output", help="Folder output")
    ap.add_argument("--max-dim", type=int, default=0,
                    help="Batasi sisi terpanjang gambar (px) sebelum deteksi, meniru produksi. "
                         "0 = resolusi penuh. Bandingkan 0 vs 640 untuk uji dampak downscaling.")
    args = ap.parse_args()

    global _MAX_DIM
    _MAX_DIM = args.max_dim
    print(f"Downscale sisi terpanjang: {'OFF (resolusi penuh)' if _MAX_DIM <= 0 else str(_MAX_DIM) + 'px (meniru produksi)'}")

    os.makedirs(args.out, exist_ok=True)

    print("Memuat DeepFace (Facenet512 + MTCNN)...")
    from deepface import DeepFace  # import di sini agar --help cepat

    print("\n[1/4] Ekstraksi embedding enroll & uji...")
    templates, test_embs, ef, tf = build_dataset(args.dataset, DeepFace)
    n_test = sum(len(v) for v in test_embs.values())
    print(f"\nSubjek valid: {len(templates)} | total foto uji: {n_test}"
          f" | gagal deteksi (enroll/uji): {ef}/{tf}")

    print("\n[2/4] Menghitung skor pasangan genuine & impostor...")
    genuine, impostor = score_pairs(templates, test_embs)
    if len(genuine) == 0 or len(impostor) == 0:
        sys.exit("Butuh minimal 1 pasangan genuine & impostor. Cek dataset (>=2 subjek, ada foto uji).")
    print(f"  Pasangan genuine: {len(genuine)} | impostor: {len(impostor)}")

    print("\n[3/4] Metrik pada operating threshold & ROC/EER...")
    op = metrics_at(args.threshold, genuine, impostor)
    fpr, tpr, thr, roc_auc, eer, eer_thr = compute_roc_eer(genuine, impostor)

    summary = {
        "config": {"model": FACE_MODEL, "detector": DETECTOR_BACKEND,
                   "operating_threshold": args.threshold},
        "dataset": {"subjek": len(templates), "foto_uji": n_test,
                    "pasangan_genuine": len(genuine), "pasangan_impostor": len(impostor),
                    "gagal_deteksi_enroll": ef, "gagal_deteksi_uji": tf},
        "operating_point": op,
        "roc_auc": round(float(roc_auc), 4),
        "eer_percent": round(float(eer) * 100, 2),
        "eer_threshold": round(float(eer_thr), 4),
    }

    # Threshold sweep (lampiran)
    sweep = [metrics_at(t, genuine, impostor) for t in np.round(np.arange(0.30, 0.91, 0.05), 2)]

    print("\n[4/4] Menulis output & grafik...")
    with open(os.path.join(args.out, "metrics.json"), "w") as f:
        json.dump(summary, f, indent=2)
    _write_csv(os.path.join(args.out, "metrics.csv"), [op])
    _write_csv(os.path.join(args.out, "threshold_sweep.csv"), sweep)
    plot_roc(fpr, tpr, roc_auc, eer, op["FAR"] / 100, op["recall"] / 100,
             os.path.join(args.out, "roc_curve.png"))
    plot_distribution(genuine, impostor, args.threshold,
                      os.path.join(args.out, "similarity_distribution.png"))

    # Ringkasan ke layar (langsung untuk Tabel 3)
    print("\n" + "=" * 52)
    print("  HASIL EVALUASI (untuk Tabel 3 artikel)")
    print("=" * 52)
    print(f"  Akurasi   : {op['accuracy']}%")
    print(f"  Presisi   : {op['precision']}%")
    print(f"  Recall    : {op['recall']}%")
    print(f"  F1-Score  : {op['f1_score']}%")
    print(f"  FAR       : {op['FAR']}%")
    print(f"  FRR       : {op['FRR']}%")
    print(f"  EER       : {summary['eer_percent']}%")
    print(f"  ROC-AUC   : {summary['roc_auc']}")
    print("=" * 52)
    print(f"\nSemua output tersimpan di: {args.out}")


def _write_csv(path, rows):
    import csv
    if not rows:
        return
    with open(path, "w", newline="") as f:
        w = csv.DictWriter(f, fieldnames=list(rows[0].keys()))
        w.writeheader()
        w.writerows(rows)


if __name__ == "__main__":
    main()
