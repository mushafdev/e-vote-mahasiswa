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
                <a href="{{route('profilupdate.create')}}"   class="btn-shadow btn btn-primary">
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
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_profilupdate">
                <thead class="thead-light">
                    <tr>
                    <th scope="col" class="text-center">No</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Urut</th>
                    <th scope="col">Judul</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center span2 datatable-nosort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Menampilkan Data -->
<script>
    $('document').ready(function(){
        $('.modal-load').hide();
        var ax_save;
        var ax_save_account;
        var table;
        table = $('#t_profilupdate').DataTable({
            scrollCollapse: true,
            autoWidth: false,
            responsive: false,
            "scrollX": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "profil": "_START_-_END_ of _TOTAL_ entries",
                searchPlaceholder: "Search"
            },
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            ajax: "{{route('profilupdate.get_profilupdate')}}",
            columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'user_photo', name: 'photo'},
            {data: 'urut', name: 'urut'},
            {data: 'title', name: 'title'},
            {data: 'active', name: 'active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "columnDefs": [
            {
                "targets": "datatable-nosort",
                "orderable": false,
            },
            ],

        });


            $('#t_profilupdate').on( 'click', 'tr .delete', function () {
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
                        url     : "{{route('profilupdate.destroy')}}",
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

