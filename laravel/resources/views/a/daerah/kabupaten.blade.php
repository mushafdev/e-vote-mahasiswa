@extends('a.main')

@section('content')
<style>
    .dataTables_scrollHeadInner{
        width: 100% !important;
    }
</style>
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
                  <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_daerah">
                      <thead class="thead-light">
                          <tr>
                          <th scope="col">No</th>
                          <th scope="col">Nama</th>
                          <th scope="col">Target (Pendukung)</th>
                          <th scope="col">Lat</th>
                          <th scope="col">Lon</th>
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
    <div class="modal-dialog modal-md mt-5" role="document">
      <div class="modal-content">
          <div class="modal-header tit-up">
            <h4 class="modal-title" id="form-title">Tambah Kabupaten</h4>
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
                              <label>Kabupaten</label>
                                <input type="text" class="form-control" name="kabupaten" id="kabupaten" Placeholder="Kabupaten" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                              <label>Target (Pendukung)</label>
                                <input type="number" class="form-control" name="target" id="target" min="1" Placeholder="Target" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                              <label>Latitude (Lat)</label>
                                <input type="text"  class="form-control" name="lat" id="lat" Placeholder="Latitude">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                              <label>Longitude (Lon)</label>
                                <input type="text"  class="form-control" name="lon" id="lon" Placeholder="Longitude">
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


<!-- Menampilkan Data -->

<script src="{{asset('assets/vendor/ckeditor5/ckeditor.js')}}"></script>
<script>
    $('document').ready(function(){
        $('.modal-load').hide();

        $('#add').click(function(e){
            e.preventDefault();
            $('#form').parsley().reset();
            $("#param").val('add');
            $('#form-title').text('Tambah Kabupaten');
            process_done1('#simpan','<i class="fa fa-save"></i> Simpan');
            $('#form')[0].reset();
            $('#id').val('');
            $('.modal-load').hide();
            $("#modal").modal('show');
        });

        var ax_save;
        var ax_save_account;
        var table;
        table = $('#t_daerah').DataTable({
            scrollCollapse: true,
            autoWidth: false,
            responsive: false,
            "scrollX": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "info": "_START_-_END_ of _TOTAL_ entries",
                searchPlaceholder: "Search"
            },
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            ajax: {
                    "type": "GET",
                    "url": "{{route('admin.kabupaten.get_kabupaten')}}",
                    "beforeSend" :function(){
                        process1($("#filter"));
                    },
                    "complete" : function(){
                        process_done1($("#filter"), '<i class="fa fa-filter"></i> Filter');
                    },
                    "data": function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.x = $("#f_kategori").val();
                    }
                },
            columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kabupaten', name: 'kabupaten'},
            {data: 'target', name: 'target'},
            {data: 'lat', name: 'lat'},
            {data: 'lon', name: 'lon'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "columnDefs": [
            {
                "targets": "datatable-nosort",
                "orderable": false,
            },
            ],

        });

        $("#filter").click(function(e) {
            table.ajax.reload(null, true);
        });

        $("#f_kategori").change(function(e) {
            table.ajax.reload(null, true);
        });

        $("#save").click(function(e) {
                e.preventDefault();
                if ($('#form').parsley().validate()) {
                    var param = this;
                    process1(param);
                    var form = $('form')[0];
                    var form_data = new FormData(form);
                    ax_save = $.ajax({
                        type: 'POST',
                        data: form_data,
                        contentType : false,
                        processData : false,
                        cache: false,
                        async:true,
                        url: "{{ route('admin.kabupaten.store') }}",
                        DataType: 'json',
                        success: function(data) {
                            process_done1(param, '<i class="fa fa-save"></i> Simpan');
                            Swal.fire('Success!', data.text, 'success');
                            table.ajax.reload(null, false);
                            $('#modal').modal('toggle');
                        },
                        error: function(reject) {
                            process_done1(param, '<i class="fa fa-save"></i> Simpan');
                            $('#id').parsley().removeError('uniqueerror', {
                                updateClass: true
                            });
                            if (reject.status === 422) {
                                var data = reject.responseText;
                                var jsonResponse = JSON.parse(data);
                                var kodeError = jsonResponse.errors.kode;
                                $('#id').parsley().addError('uniqueerror', {
                                    message: kodeError,
                                    updateClass: true
                                });
                            } else {
                                Swal.fire("Error", "Maaf, terjadi kesalahan", "error");
                            }
                        }
                    });
                } else {
                    return false();
                }

            });

        $(document).on( 'click', '.edit', function () {
                $('.modal-load').show();
                $('#form-title').text('Edit Kabupaten');
                $('#form').parsley().reset();
                var id = $(this).data("id");
                $.ajax({
                type    : 'GET',
                data    : {id:id},
                url     : "{{route('admin.kabupaten.get_data')}}",
                dataType: 'json',
                success : function(data) {
                    $('#id').val(data.id);
                    $('#param').val('edit');
                    $("#kabupaten").val(data.kabupaten);
                    $("#target").val(data.target);
                    $("#lat").val(data.lat);
                    $("#lon").val(data.lon);
                    $('.modal-load').hide();
                }
                });

            });


            $(document).on( 'click', '.delete', function () {
                var id = $(this).data("id");
                Swal.fire({
                title: 'Are you sure?',
                text: "This record and it`s details will be permanantly deleted!",
                type: 'warning',
                allowOutsideClick: false,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type    : 'POST',
                        data    : {id:id,_token:"{{csrf_token()}}"},
                        url     : "{{route('admin.kabupaten.destroy')}}",
                        success : function(data) {
                            Swal.fire('Success!','Deleted successfully.','success');
                            table.ajax.reload( null, false );
                        },
                        error : function(){
                            Swal.fire("Error", "There is an error", "error");
                        }
                    });
                }
                })
            });



    });
</script>
@endsection

