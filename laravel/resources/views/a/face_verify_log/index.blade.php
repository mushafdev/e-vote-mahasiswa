@extends('a.main')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white"></i>
            </div>
            <div>{!! $title !!}</div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('face_log.export_csv') }}" id="btn-export" class="btn btn-success btn-sm">
                <i class="fa fa-download"></i> Export CSV
            </a>
        </div>
    </div>
</div>

{{-- Kartu Statistik --}}
<div class="row mb-3">
    <div class="col-md-2 col-6 mb-2">
        <div class="card text-center p-3">
            <div class="text-muted" style="font-size:11px;">Pemilih Terdaftar Wajah</div>
            <div class="font-weight-bold text-primary" style="font-size:28px;">{{ $total_register }}</div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card text-center p-3">
            <div class="text-muted" style="font-size:11px;">Total Log</div>
            <div class="font-weight-bold text-secondary" style="font-size:28px;">{{ $total_log }}</div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card text-center p-3">
            <div class="text-muted" style="font-size:11px;">Verifikasi Berhasil</div>
            <div class="font-weight-bold text-success" style="font-size:28px;">{{ $total_match }}</div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card text-center p-3">
            <div class="text-muted" style="font-size:11px;">Verifikasi Gagal</div>
            <div class="font-weight-bold text-danger" style="font-size:28px;">{{ $total_gagal }}</div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card text-center p-3">
            <div class="text-muted" style="font-size:11px;">Rata-rata Confidence</div>
            <div class="font-weight-bold text-info" style="font-size:28px;">{{ number_format($rata_confidence, 1) }}%</div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card text-center p-3">
            <div class="text-muted" style="font-size:11px;">Akurasi Sistem</div>
            <div class="font-weight-bold text-warning" style="font-size:28px;">
                @if($total_log > 0) {{ number_format(($total_match / $total_log) * 100, 1) }}%
                @else - @endif
            </div>
        </div>
    </div>
</div>

<div class="text-muted small mb-3">
    Rata-rata waktu verifikasi:
    <strong>{{ $n_durasi > 0 ? number_format($rata_durasi_ms / 1000, 2) . ' detik' : '-' }}</strong>
    (n = {{ $n_durasi }} verifikasi; diukur dari server Laravel ke Face API)
</div>

{{-- Grafik --}}
<div class="row mb-3">
    <div class="col-md-6 mb-3">
        <div class="card p-3">
            <h6 class="font-weight-bold mb-3">Distribusi Confidence Score</h6>
            <canvas id="chart-distribusi" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card p-3">
            <h6 class="font-weight-bold mb-3">Match vs Gagal per Bulan</h6>
            <canvas id="chart-bulanan" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Filter & Tabel --}}
<div class="card p-3">
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="small text-muted">Jenis Aksi</label>
            <select class="form-control form-control-sm filter-field" id="filter-aksi">
                <option value="">Semua</option>
                <option value="register">Registrasi</option>
                <option value="verify_login">Login</option>
                <option value="verify_vote">Voting</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="small text-muted">Hasil</label>
            <select class="form-control form-control-sm filter-field" id="filter-match">
                <option value="">Semua</option>
                <option value="1">Match</option>
                <option value="0">Gagal</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="small text-muted">Tanggal Dari</label>
            <input type="date" class="form-control form-control-sm filter-field" id="filter-dari">
        </div>
        <div class="col-md-2">
            <label class="small text-muted">Tanggal Sampai</label>
            <input type="date" class="form-control form-control-sm filter-field" id="filter-sampai">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary btn-sm mr-1" id="btn-filter"><i class="fa fa-search"></i> Filter</button>
            <button class="btn btn-secondary btn-sm" id="btn-reset">Reset</button>
        </div>
    </div>

    <div id="loading-table" class="text-center py-3" style="display:none;">
        <i class="fa fa-spinner fa-spin"></i> Memuat data...
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="40">No</th>
                    <th>Pemilih</th>
                    <th class="text-center" width="100">Aksi</th>
                    <th class="text-center" width="80">Hasil</th>
                    <th width="160">Confidence</th>
                    <th class="text-center" width="80">Threshold</th>
                    <th class="text-center" width="80">Durasi</th>
                    <th class="text-center" width="110">IP Address</th>
                    <th class="text-center" width="130">Waktu</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <tr><td colspan="9" class="text-center text-muted">Klik Filter untuk memuat data</td></tr>
            </tbody>
        </table>
    </div>

    <div id="pagination-wrap" class="mt-2"></div>
    <div id="total-info" class="text-muted small mt-1"></div>
</div>

<div id="face-log-config" style="display:none;"
    data-get-data="{{ route('face_log.get_data') }}"
    data-stats="{{ route('face_log.stats') }}"
    data-export="{{ route('face_log.export_csv') }}">
</div>
<script src="{{ asset('assets/vendor/chartjs/chart.min.js') }}"></script>
<script src="{{ asset('assets/js/page/face_verify_log/index.js') }}?v=1"></script>
@endsection
