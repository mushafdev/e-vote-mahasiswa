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
            <a href="{{route('pemilih.vote_list')}}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<form role="form" id="form-pemilih"
    data-update-verifikasi="{{route('pemilih.vote_approve')}}"
    data-registration-list="{{route('pemilih.vote_list')}}">
{{ csrf_field() }}
<input type="hidden" id="id" name="id" value="{{Formatter::encrypt0($q->id)}}">

<div class="row">
    {{-- Info Pemilih --}}
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
                            <th class="bg-light">Email</th>
                            <td>{{$q->email ?? '-'}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">No. WA / Telp</th>
                            <td>{{$q->telp ?? '-'}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Waktu Memilih</th>
                            <td>{{$q->time_vote_final ? date('d/m/Y H:i:s', strtotime($q->time_vote_final)) : '-'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kandidat yang dipilih --}}
        @if (!empty($q->nama_kandidat))
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 font-weight-bold">Kandidat yang Dipilih</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <div style="width:60px;height:60px;background:#c0392b;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <span class="text-white font-weight-bold" style="font-size:24px;">{{$q->no_urut}}</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-0">{{$q->nama_kandidat}}</h5>
                        <small class="text-muted">NIM/No. Identitas: {{$q->no_anggota_kandidat ?? '-'}}</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Status Suara --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 font-weight-bold">Status Suara</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block">Status</label>
                    @if ($q->verifikasi_vote == 'Y')
                        <span class="badge badge-success">Suara Sah</span>
                    @elseif ($q->verifikasi_vote == 'N')
                        <span class="badge badge-danger">Suara Tidak Sah</span>
                    @else
                        <span class="badge badge-secondary">Belum Diverifikasi</span>
                    @endif
                </div>

                @if (!empty($q->diverifikasi_oleh))
                <div class="mb-3">
                    <label class="text-muted small d-block">Diverifikasi Oleh</label>
                    <span>{{$q->diverifikasi_oleh}}</span>
                </div>
                @endif

                @if (!empty($q->verify_vote_time))
                <div class="mb-3">
                    <label class="text-muted small d-block">Waktu Verifikasi</label>
                    <span>{{date('d/m/Y H:i', strtotime($q->verify_vote_time))}}</span>
                </div>
                @endif

                @if(Session::get('role') == 'v')
                <hr>
                <div class="form-group">
                    <label>Update Status</label>
                    <select class="form-control" id="verifikasi_vote" name="verifikasi_vote" required>
                        <option value="">Pilih Status</option>
                        <option value="Y">Sah</option>
                        <option value="N">Tidak Sah</option>
                    </select>
                </div>
                @can('pemilih vote-approve')
                <button type="button" class="btn btn-primary btn-block" id="update-verifikasi">
                    <i class="fa fa-save mr-1"></i> Simpan
                </button>
                @endcan
                @endif
            </div>
        </div>
    </div>
</div>
</form>

<script src="{{asset('assets/js/page/pemilih/pemilih.js')}}?v=2212"></script>
@endsection
