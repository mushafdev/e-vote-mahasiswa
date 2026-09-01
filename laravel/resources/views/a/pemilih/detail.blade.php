@extends('a.main')

@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{$icon}} text-white"></i>
            </div>
            <div>{!!$title!!}</div>
        </div>
        <div class="page-title-actions">
            <a href="{{route('pemilih.main')}}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<form role="form" id="form-pemilih"
    data-update-verifikasi="{{route('pemilih.update_verifikasi')}}"
    data-registration-list="{{route('pemilih.registration_list')}}"
    data-main="{{route('pemilih.main')}}">
{{ csrf_field() }}
<input type="hidden" id="id" name="id" value="{{Formatter::encrypt0($q->id)}}">

<div class="row">
    {{-- Kolom Kiri: Info Pemilih --}}
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 font-weight-bold">Informasi Pemilih</h6></div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th width="180" class="bg-light">Nama Lengkap</th>
                            <td>{{$q->nama}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">NIM/No. Identitas</th>
                            <td>{{$q->no_anggota ?? '-'}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Username</th>
                            <td>{{$q->username ?? '-'}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Email</th>
                            <td>{{$q->email ?? '-'}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">No. WA / Telp</th>
                            <td>{{$q->telp ?? '-'}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Alamat</th>
                            <td>{{$q->alamat ?? '-'}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status Akun</th>
                            <td>
                                {!! $q->aktif == 'Y'
                                    ? '<span class="badge badge-success">Aktif</span>'
                                    : '<span class="badge badge-secondary">Tidak Aktif</span>' !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Email Verifikasi</th>
                            <td>
                                {!! $q->email_verifikasi == 'Y'
                                    ? '<span class="badge badge-success">Terverifikasi</span>'
                                    : '<span class="badge badge-warning">Belum</span>' !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Verifikasi Admin</th>
                            <td>
                                {!! $q->verifikasi == 'Y'
                                    ? '<span class="badge badge-success">Diterima</span>'
                                    : '<span class="badge badge-danger">Belum/Ditolak</span>' !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Face Registered</th>
                            <td>
                                {!! $q->face_registered
                                    ? '<span class="badge badge-success">Terdaftar</span>'
                                    : '<span class="badge badge-secondary">Belum</span>' !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Terdaftar Pada</th>
                            <td>{{$q->created_at ? date('d/m/Y H:i', strtotime($q->created_at)) : '-'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Dokumen KTP --}}
        @if (!empty($q->ktp))
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 font-weight-bold">Dokumen KTP / KTA</h6></div>
            <div class="card-body">
                <a href="{{asset('files/ktp/'.$q->ktp)}}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-file mr-1"></i> Lihat Dokumen
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- Kolom Kanan: Status Verifikasi --}}
    <div class="col-md-4">
        @if(Session::get('role') == 'a')
        <div class="card">
            <div class="card-header"><h6 class="mb-0 font-weight-bold">Update Status Verifikasi</h6></div>
            <div class="card-body">
                @if (!empty($q->catatan_verifikasi) || !empty($q->verify_by))
                    <div class="mb-3">
                        <label class="text-muted small">Status</label>
                        <div>{!! $q->verifikasi == 'Y' ? '<span class="badge badge-success">Diterima</span>' : '<span class="badge badge-danger">Ditolak</span>' !!}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Catatan</label>
                        <div>{{$q->catatan_verifikasi ?? '-'}}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Diverifikasi Oleh</label>
                        <div>{{$q->diverifikasi_oleh ?? '-'}}</div>
                    </div>
                @else
                    <div class="form-group">
                        <label>Status Verifikasi</label>
                        <select class="form-control" id="verifikasi" name="verifikasi" required>
                            <option value="">Pilih Status</option>
                            <option value="Y">Diterima</option>
                            <option value="N">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan_verifikasi" id="catatan_verifikasi" class="form-control" rows="3" required></textarea>
                    </div>
                    @can('pemilih verifikasi')
                    <button type="button" class="btn btn-primary btn-block" id="update-verifikasi">
                        <i class="fa fa-save mr-1"></i> Simpan
                    </button>
                    @endcan
                @endif
            </div>
        </div>
        @endif

        @if (!empty($q->catatan_panlih))
        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0 font-weight-bold">Catatan Panlih</h6></div>
            <div class="card-body">
                <div class="alert alert-info mb-0">{{$q->catatan_panlih}}</div>
            </div>
        </div>
        @endif
    </div>
</div>
</form>

<script src="{{asset('assets/js/page/pemilih/pemilih.js')}}?v=2212"></script>
@endsection
