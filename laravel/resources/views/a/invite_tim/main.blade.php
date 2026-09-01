@extends('a.main')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{!! $title !!}
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a href="{{route('invite_tim.create')}}"   class="btn-shadow btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-plus-circle fa-w-20"></i>
                    </span>
                    Tambah
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 p-4 mb-3 card">
        <div class="table-action">
          <div class="text-right">
          </div>
        </div>
        <div class="table-place-full">
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_invite_tim" data-list="{{route('invite_tim.get_invite_tim')}}" data-delete="{{route('invite_tim.destroy')}}" data-token="{{csrf_token()}}">
                <thead class="thead-light">
                    <tr>
                    <th scope="col" class="text-center">No</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Kelompok TIM</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NIK</th>
                    <th scope="col">No. Telp</th>
                    <th scope="col">Level</th>
                    <th scope="col" class="text-center span2 datatable-nosort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade mt-3" id="modal-account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
	  <div class="modal-dialog modal-lg mt-5" role="document">
		<div class="modal-content">
			<div class="modal-header tit-up">
				<h4 class="modal-title">Update Akun</h4>
			</div>
			<div class="modal-body customer-box">
                <div class="buttonload modal-load">
                        <i class="fa fa-spinner fa-spin modal-load-spinner"></i>
                </div>
				<!-- Tab panes -->
				<form role="form" name="form_account" id="form_account" class="form-horizontal">
                {{csrf_field()}}
                <input type="hidden" id="id_user_a" name="id_user_a" readonly />
                    <div class="form-group">
						<div class="col-sm-12">
						  <label>Username</label>
							<input type="text" class="form-control" name="username_a" id="username_a" Placeholder="Username" required readonly>
						</div>
                    </div>
                    <div class="form-group">
						<div class="col-sm-12">
						  <label>New Username</label>
							<input type="text" class="form-control" name="username" id="username_a2" Placeholder="New Username">
						</div>
                    </div>
                    <div class="form-group">
						<div class="col-sm-12">
						  <label>Password</label>
							<input type="text" autocomplete="off" class="form-control" name="password1" id="password1" Placeholder="Password" required
                            data-parsley-minlength="8"
                            data-parsley-uppercase="1"
                            data-parsley-lowercase="1"
                            data-parsley-number="1"
                            data-parsley-special="1"
                            >
						</div>
                    </div>
                    <div class="form-group">
						<div class="col-sm-12">
						  <label>Confirm Password</label>
							<input type="text" class="form-control" autocomplete="off" name="password2" id="password2" Placeholder="Password" required data-parsley-equalto="#password1">
						</div>
                    </div>
				</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md" name="save_account" id="save_account">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-danger btn-md" name="cancel_account" id="cancel_account" data-dismiss="modal">
                <i class="fa fa-ban"></i> Batal</button>
            </div>
		</div>
	  </div>
	</div>
</div>

<!-- Menampilkan Data -->

<script src="{{asset('assets/js/page/invite_tim/invite_tim_list.js')}}"></script>
@endsection

