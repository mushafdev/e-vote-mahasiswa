@extends('a.main')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{{ $title }}
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <button type="button" class="btn-shadow btn btn-primary" id="add">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-plus-circle fa-w-20"></i>
                    </span>
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
        <div class="card p-3">
            <div class="table-action">
                <div class="text-right">
                </div>
              </div>
              <div class="table-place-full">
                  <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_permissions" data-list="{{route('permissions.get_permissions')}}" data-delete="{{route('permissions.destroy')}}" data-save="{{route('permissions.store')}}" data-token="{{csrf_token()}}" data-get-data="{{route('permissions.get_data')}}">
                      <thead class="thead-light">
                          <tr>
                          <th scope="col">No</th>
                          <th scope="col">Nama</th>
                          <th scope="col" class="text-center span2 datatable-nosort">Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
              </div>
        </div>

    </div>
</div>

<div class="modal fade mt-3" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-sm mt-5" role="document">
      <div class="modal-content">
          <div class="modal-header tit-up">
            <h4 class="modal-title" id="form-title">Tambah Permission</h4>
          </div>
          <div class="modal-body customer-box">
              <div class="buttonload modal-load">
                      <i class="fa fa-spinner fa-spin modal-load-spinner"></i>
              </div>
              <!-- Tab panes -->
              <form role="form" name="form" id="form" class="form-horizontal">
              {{csrf_field()}}
              <input type="hidden" id="id" name="id" readonly />
              <input type="hidden" id="param" name="param" readonly />
                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-12">
                              <label>Nama</label>
                                <input type="text" class="form-control" name="name" id="name" Placeholder="Permission" required >
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 form_surat_keluar hide">

                        <div class="form-group">
                            <div class="col-sm-12">
                              <label>Redaksi Surat</label>
                              <div id="toolbar-container"></div>
                            <div class="" id="editor"></div>
                            </div>
                        </div>
                    </div>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-md" name="cancel" id="cancel" data-dismiss="modal">
              <i class="fa fa-ban"></i> Batal</button>
              <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                  <i class="fa fa-save"></i> Simpan
              </button>
          </div>
      </div>
    </div>
  </div>
</div>


<script src="{{asset('assets/vendor/ckeditor5Document/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/page/permissions/permissions.js')}}"></script>

@endsection

