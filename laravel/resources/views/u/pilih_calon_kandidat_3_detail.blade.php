@extends('u.main')

@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{!! $title!!}
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>

@if (!empty($pemilih->vote_enc))
<div class="row card align-items-center pb-5 pt-5">
    <div class="col-md-12 text-center">
        <div class="alert alert-success">
            <i class="pe-7s-check" style="font-size: 3rem;"></i>
            <h4 class="font-weight-bold mt-2">Anda Sudah Memilih</h4>
            <h5 class="mb-0">Terima kasih, suara Anda telah tercatat.</h5>
            <p class="mt-2 mb-0 text-muted">Setiap pemilih hanya dapat memilih satu kali.</p>
        </div>
    </div>
</div>
@else

<div class="row justify-content-center" style="min-height: 500px;">
    <div class="col-md-4 col-lg-3 py-4">

        <form data-save="{{route('account.pilih_kandidat_3_update')}}" method="POST" id="form-pilih" autocomplete="off">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{Request::segment(3)}}">

        {{-- STEP 1: Info Kandidat + Tombol Pilih --}}
        <div id="step-pilih">

            {{-- Card Kandidat --}}
            <div class="card border-0 mb-4" style="box-shadow:0 4px 20px rgba(0,0,0,0.12);border-radius:12px;overflow:hidden;">
                <img src="{{asset('images/kandidat/'.$kandidats->file_photo)}}" alt="{{$kandidats->nama}}"
                     onerror="this.src='{{asset('assets/images/no-image.png')}}';this.onerror=null;"
                     style="width:100%;height:220px;object-fit:cover;object-position:top;background:#eee;">
                <div class="card-body bg-danger py-3 px-4">
                    <div class="d-flex align-items-center">
                        <span class="display-4 font-weight-bold text-white mr-3">{{$kandidats->no_urut}}</span>
                        <div>
                            <h5 class="text-white font-weight-bold mb-0">{{$kandidats->nama}}</h5>
                            <small class="text-white-50">{{$kandidats->no_anggota}}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Peringatan --}}
            <div class="alert alert-warning text-center py-2 mb-3">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                <strong>Perhatikan!</strong> Pilihan tidak dapat diubah setelah dikonfirmasi.
            </div>

            <div class="d-flex gap-2">
                <a href="{{route('account','home')}}" class="btn btn-outline-secondary btn-lg flex-fill">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="button" id="btn-pilih" class="btn btn-danger btn-lg flex-fill">
                    <i class="fas fa-check mr-1"></i> Pilih Kandidat Ini
                </button>
            </div>
        </div>

        {{-- STEP 2: Verifikasi Wajah --}}
        <div id="step-face" style="display:none;">

            <div class="text-center mb-3">
                <i class="fas fa-camera fa-2x text-danger mb-2"></i>
                <h6 class="font-weight-bold mb-0">Verifikasi Wajah</h6>
                <small class="text-muted">Arahkan wajah ke kamera lalu klik konfirmasi</small>
            </div>

            <div id="alert-face" class="alert py-2 mb-3" style="display:none;"></div>

            <div class="position-relative mb-3" style="background:#000;border-radius:10px;overflow:hidden;">
                <video id="video-vote" autoplay playsinline
                       style="width:100%;display:block;border-radius:10px;height:260px;object-fit:cover;"></video>
                <canvas id="canvas-vote" style="display:none;"></canvas>
                <div id="cam-placeholder" class="d-flex flex-column align-items-center justify-content-center text-white"
                     style="position:absolute;top:0;left:0;width:100%;height:100%;background:#1a1a2e;">
                    <i class="fas fa-camera fa-3x mb-2 text-secondary"></i>
                    <small>Kamera belum aktif</small>
                </div>
            </div>

            <button type="button" id="btn-verify-face" class="btn btn-danger btn-lg w-100 mb-2" disabled>
                <i class="fas fa-check-circle mr-1"></i> Konfirmasi Pilihan
            </button>
            <div class="text-center">
                <a href="#" id="btn-batal-face" class="text-muted small">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
            </div>
        </div>

        </form>
    </div>
</div>
@endif




<script src="{{asset('assets/js/page/kandidat/pilih_calon_kandidat_3.js')}}?v=87213"></script>


<!-- Menampilkan Data -->

@endsection

