<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
<title>Persediaan</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<meta name="description" content="">
<meta name="msapplication-tap-highlight" content="no">
<link href="{{asset('assets/vendor/architectui/css/main.css')}}" rel="stylesheet">
<!-- Include Editor style. -->
<style>
</style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h4 class="font-weight-bold mb-0">Bank Sulselbar</h4>
            </div>
            <div class="col-md-12">
                <h5>Persediaan Barang</h5>
                <hr>
            </div>
            <div class="col-md-12">
                <table class="">
                    <tr>
                        <td>Kode Barang</td>
                        <td width="10%"></td>
                        <th>{{$barang->kode}}</th>
                    </tr>
                    <tr>
                        <td>Nama Barang</td>
                        <td width="10%"></td>
                        <th>{{$barang->barang}}</th>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td width="10%"></td>
                        <th>{{$barang->kategori_barang}}</th>
                    </tr>
                    <tr>
                        <td>Sub Kategori</td>
                        <td width="10%"></td>
                        <th>{{$barang->sub_kategori_barang}}</th>
                    </tr>
                    <tr>
                        <td>Satuan</td>
                        <td width="10%"></td>
                        <th>{{$barang->satuan}}</th>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <table class="table table-stripped table-hover table-bordered">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>Kode</th>
                            <th>Unit Kerja</th>
                            <th class="text-center">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($unit_kerja->count()>0)
                            @foreach ($unit_kerja as $dt )
                            <tr>
                                <td>{{$dt->kode}}</td>
                                <td>{{$dt->unit_kerja}}</td>
                                <td class="text-center">{{$dt->stok}}</td>
                            </tr>   
                            @endforeach
                        @else
                            <tr><td class="text-center text-danger" colspan="3">Stok Kosong</td></tr>
                        @endif
                        
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
