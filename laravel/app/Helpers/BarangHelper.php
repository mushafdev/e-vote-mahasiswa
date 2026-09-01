<?php
namespace App\Helpers;
use DateTime;
use App\Models\BarangHistory;
use App\Models\BarangHistoryUnitKerja;
use Illuminate\Support\Facades\DB;

class BarangHelper {
    public static function createHistory($no_referensi,$transaksi_id,$jenis_transaksi,$barang_id,$qty,$harga,$in_out,$unit_kerja_id,$lokasi_id,$waktu,$no_seri_start,$no_seri_end) {
        $stok=DB::table('barang_stoks')->whereRaw("barang_id='$barang_id' AND unit_kerja_id='$unit_kerja_id' AND lokasi_id='1'")->first()->stok;
        $data = [];
        $data['waktu'] = $waktu;
        $data['no_referensi'] = $no_referensi;
        $data['transaksi_id'] = $transaksi_id;
        $data['jenis_transaksi'] = $jenis_transaksi;
        $data['barang_id'] = $barang_id;
        $data['qty'] = $qty;
        $data['no_seri_start'] = $no_seri_start;
        $data['no_seri_end'] = $no_seri_end;
        $data['stok'] = $stok;
        $data['harga'] = $harga;
        $data['in_out'] = $in_out;
        $data['unit_kerja_id'] = $unit_kerja_id;
        $data['lokasi_id'] = $lokasi_id;
        $data['user_id'] = auth()->check() ? auth()->user()->id : NULL;
        return BarangHistory::create($data);
    }
    
    public static function createHistoryUnitKerja($no_referensi,$transaksi_id,$jenis_transaksi,$barang_id,$qty,$harga,$in_out,$unit_kerja_id,$lokasi_id,$waktu,$no_seri_start,$no_seri_end) {
        $stok=DB::table('barang_stok_unit_kerjas')->whereRaw("barang_id='$barang_id' AND unit_kerja_id='$unit_kerja_id' AND lokasi_id='1'")->first()->stok;
        $data = [];
        $data['waktu'] = $waktu;
        $data['no_referensi'] = $no_referensi;
        $data['transaksi_id'] = $transaksi_id;
        $data['jenis_transaksi'] = $jenis_transaksi;
        $data['barang_id'] = $barang_id;
        $data['qty'] = $qty;
        $data['no_seri_start'] = $no_seri_start;
        $data['no_seri_end'] = $no_seri_end;
        $data['stok'] = $stok;
        $data['harga'] = $harga;
        $data['in_out'] = $in_out;
        $data['unit_kerja_id'] = $unit_kerja_id;
        $data['lokasi_id'] = $lokasi_id;
        $data['user_id'] = auth()->check() ? auth()->user()->id : NULL;
        return BarangHistoryUnitKerja::create($data);
    }

 }
