<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Formatter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\BarangStok;
use App\Models\Barang;
use App\Models\UnitKerja;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get_stok(Request $request)
    {
            $barang_id=Formatter::decrypt0(addslashes($request->xb));
            $barang=Barang::
            join('kategori_barangs','kategori_barangs.id','=','barangs.kategori_barang_id')
            ->select('barangs.*','kategori_barangs.kategori_barang')->where('barangs.habis_pakai','N')->where('barangs.id',$barang_id);
            if($barang->count()>0){
                $unit_kerja=UnitKerja::select('id','kode','unit_kerja','alamat')->get();
                $unit_kerja=$unit_kerja->map(function($row) use ($barang_id) {
                    $stok_barang=BarangStok::where('barang_id',$barang_id)->where('unit_kerja_id',$row->id)->sum('stok');
                    $row->stok=$stok_barang;
                    $row->id='xxx';
                    return $row;
                })->reject(function ($row) {
                    return $row->stok==0;
                });
                $d['unit_kerja']=$unit_kerja;
                $d['barang']=$barang->first();
                return view('api.stok',$d);
            }else{
                return '';
            }
           

    }





}
