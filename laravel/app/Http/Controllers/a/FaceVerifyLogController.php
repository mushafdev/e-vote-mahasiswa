<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaceVerifyLog;
use App\Models\Pemilih;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FaceVerifyLogController extends Controller
{
    public function index(Request $request)
    {
        $d['icon']  = 'pe-7s-camera';
        $d['title'] = 'Log Verifikasi Wajah';

        // Statistik ringkas untuk kartu atas
        $d['total_log']       = FaceVerifyLog::count();
        $d['total_match']     = FaceVerifyLog::where('match', true)->count();
        $d['total_gagal']     = FaceVerifyLog::where('match', false)->count();
        $d['rata_confidence'] = FaceVerifyLog::where('match', true)->avg('confidence') ?? 0;
        $d['total_register']  = \App\Models\Pemilih::where('face_registered', true)->count();
        $d['rata_durasi_ms']  = FaceVerifyLog::whereNotNull('durasi_ms')->avg('durasi_ms');
        $d['n_durasi']        = FaceVerifyLog::whereNotNull('durasi_ms')->count();

        return view('a.face_verify_log.index', $d);
    }

    public function get_data(Request $request)
    {
        $query = FaceVerifyLog::with('pemilih:id,nama,no_anggota')
            ->select('face_verify_logs.*');

        // Filter
        if ($request->aksi)  $query->where('aksi', $request->aksi);
        if ($request->match !== null && $request->match !== '') {
            $query->where('match', (bool) $request->match);
        }
        if ($request->tanggal_dari) $query->whereDate('created_at', '>=', $request->tanggal_dari);
        if ($request->tanggal_sampai) $query->whereDate('created_at', '<=', $request->tanggal_sampai);

        $data  = $query->orderByDesc('created_at')->paginate(20);
        $table = '';

        foreach ($data as $i => $dt) {
            $nama       = $dt->pemilih->nama ?? '-';
            $no_anggota = $dt->pemilih->no_anggota ?? '-';
            $aksiLabel  = ['register' => 'Registrasi', 'verify_login' => 'Login', 'verify_vote' => 'Voting'][$dt->aksi] ?? $dt->aksi;

            $matchBadge = $dt->match
                ? '<span class="badge badge-success">Match</span>'
                : '<span class="badge badge-danger">Gagal</span>';

            $confidenceBar = $this->confidenceBar($dt->confidence);
            $durasi        = $dt->durasi_ms !== null ? number_format($dt->durasi_ms / 1000, 2) . ' dtk' : '-';

            $table .= "<tr>
                <td class='text-center'>" . ($data->firstItem() + $i) . "</td>
                <td>{$nama}<br><small class='text-muted'>{$no_anggota}</small></td>
                <td><span class='badge badge-secondary'>{$aksiLabel}</span></td>
                <td class='text-center'>{$matchBadge}</td>
                <td>{$confidenceBar}</td>
                <td class='text-center'>{$dt->threshold}%</td>
                <td class='text-center'><small>{$durasi}</small></td>
                <td class='text-center'><small>{$dt->ip_address}</small></td>
                <td class='text-center'><small>" . \Carbon\Carbon::parse($dt->created_at)->format('d/m/Y H:i:s') . "</small></td>
            </tr>";
        }

        return response()->json([
            'table'      => $table,
            'pagination' => (string) $data->links(),
            'total'      => $data->total(),
        ]);
    }

    public function stats(Request $request)
    {
        // Data untuk grafik distribusi confidence
        $distribusi = FaceVerifyLog::where('match', true)
            ->selectRaw('FLOOR(confidence / 10) * 10 as range_mulai, COUNT(*) as total')
            ->groupBy('range_mulai')
            ->orderBy('range_mulai')
            ->get()
            ->mapWithKeys(fn($r) => [$r->range_mulai . '-' . ($r->range_mulai + 9) . '%' => $r->total]);

        // FAR & FRR per bulan
        $perBulan = FaceVerifyLog::where('aksi', '!=', 'register')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as bulan,
                SUM(`match` = 1) as match_count,
                SUM(`match` = 0) as gagal_count,
                COUNT(*) as total,
                AVG(confidence) as avg_confidence")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return response()->json([
            'distribusi' => $distribusi,
            'per_bulan'  => $perBulan,
            'summary'    => [
                'total'           => FaceVerifyLog::count(),
                'match'           => FaceVerifyLog::where('match', true)->count(),
                'gagal'           => FaceVerifyLog::where('match', false)->count(),
                'avg_confidence'  => round(FaceVerifyLog::where('match', true)->avg('confidence') ?? 0, 2),
                'avg_durasi_ms'   => round(FaceVerifyLog::whereNotNull('durasi_ms')->avg('durasi_ms') ?? 0),
            ],
        ]);
    }

    public function export_csv(Request $request)
    {
        $query = FaceVerifyLog::with('pemilih:id,nama,no_anggota')->orderByDesc('created_at');
        if ($request->aksi) $query->where('aksi', $request->aksi);
        if ($request->tanggal_dari) $query->whereDate('created_at', '>=', $request->tanggal_dari);
        if ($request->tanggal_sampai) $query->whereDate('created_at', '<=', $request->tanggal_sampai);

        $rows = $query->get();

        $response = new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama', 'No Anggota', 'Aksi', 'Match', 'Confidence (%)', 'Threshold (%)', 'Is Real', 'Spoof Score', 'Antispoof Label', 'Durasi (ms)', 'Keterangan', 'IP Address', 'Waktu']);
            foreach ($rows as $i => $row) {
                fputcsv($handle, [
                    $i + 1,
                    $row->pemilih->nama ?? '-',
                    $row->pemilih->no_anggota ?? '-',
                    $row->aksi,
                    $row->match ? 'Match' : 'Gagal',
                    $row->confidence,
                    $row->threshold,
                    $row->is_real ? 1 : 0,
                    $row->spoof_score,
                    $row->antispoof_label,
                    $row->durasi_ms,
                    $row->keterangan,
                    $row->ip_address,
                    $row->created_at,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="face_verify_log_' . date('Ymd_His') . '.csv"');
        return $response;
    }

    private function confidenceBar(float $confidence): string
    {
        $color = $confidence >= 80 ? 'success' : ($confidence >= 60 ? 'warning' : 'danger');
        return "<div class='d-flex align-items-center gap-1'>
            <div class='progress flex-grow-1' style='height:8px;min-width:80px'>
                <div class='progress-bar bg-{$color}' style='width:{$confidence}%'></div>
            </div>
            <small class='text-{$color} font-weight-bold ml-1'>{$confidence}%</small>
        </div>";
    }
}
