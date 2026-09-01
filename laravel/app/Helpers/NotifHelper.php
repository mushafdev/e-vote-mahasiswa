<?php
namespace App\Helpers;
use DateTime;
use Illuminate\Support\Facades\DB;

class NotifHelper {
    public static function requestBarang($style) {
        $role=auth()->user()->role;
        $notif='';
        $where_count_rb='';
        if($role=='c'){
            $unit_kerja_id=auth()->user()->unit_kerja_id;
            $where_count_rb.=" AND (notif_c='Y' OR jurnal_terima='N') AND unit_kerja_id='$unit_kerja_id'";
        }else{
            $where_count_rb.=" AND (notif_kp='Y' OR jurnal_kirim='N')";
        }
        $data=DB::table('request_barangs')->whereRaw("cancel='N' $where_count_rb");
        $count_notif=$data->count();
        if ($count_notif>0){
            $notif.='<span class="badge badge-danger">'.$count_notif.'</span>';
        }

        if($style=='icon'){
            $result=$notif;
        }else{
            $result['count']=$count_notif;
            $result['list']=$data->get();
        }
       
        return $result;
    }
    
    public static function requestBarangUnitKerja($style) {
        $role=auth()->user()->role;
        $notif='';
        $where_count_rb='';
        if($role=='sc'){
            $user_id=auth()->user()->id;
            $where_count_rb.=" AND (notif_c='Y') AND inserted_by='$user_id'";
        }else{
            $where_count_rb.=" AND (notif_kp='Y' OR jurnal_kirim='N')";
        }
        $data=DB::table('suk_request_barangs')->whereRaw("cancel='N' $where_count_rb");
        $count_notif=$data->count();
        if ($count_notif>0){
            $notif.='<span class="badge badge-danger">'.$count_notif.'</span>';
        }

        if($style=='icon'){
            $result=$notif;
        }else{
            $result['count']=$count_notif;
            $result['list']=$data->get();
        }
       
        return $result;
    }
    
    public static function barangKeluar($style) {
        $role=auth()->user()->role;
        $unit_kerja_id=auth()->user()->unit_kerja_id;
        $notif='';
        $where_count_rb='';
        if($role=='c'){
            $where_count_rb.=" AND notif_c='Y' AND unit_kerja_id='$unit_kerja_id'";
        }else{
            $where_count_rb.=" AND notif_sp='Y' AND unit_kerja_id='$unit_kerja_id'";
        }
        $data=DB::table('barang_masuk_keluars')->whereRaw("cancel='N' $where_count_rb");
        $count_notif=$data->count();
        if ($count_notif>0){
            $notif.='<span class="badge badge-danger">'.$count_notif.'</span>';
        }

        if($style=='icon'){
            $result=$notif;
        }else{
            $result['count']=$count_notif;
            $result['list']=$data->get();
        }
       
        return $result;
    }

    public static function stokKritis($style) {
        $role=auth()->user()->role;
        $unit_kerja_id=auth()->user()->unit_kerja_id;
        $notif='';
        
        $count_notif=DB::table('barang_stoks')
        ->join('barangs','barangs.id','=','barang_stoks.barang_id')
        ->whereRaw("barang_stoks.stok<=barangs.stok_kritis AND barangs.stok_kritis_type='a' AND barangs.habis_pakai='N' AND barang_stoks.unit_kerja_id='$unit_kerja_id'")->count();
        if ($count_notif>0){
                $notif.='<span class="badge badge-danger">'.$count_notif.'</span>';
        }
        if($style=='icon'){
            $result=$notif;
        }else{
            $result=$count_notif;
        }
       
        return $result;
    }

    public static function stokAwal($style) {
        $role=auth()->user()->role;
        $notif='';
        if($role== "a"){
            $count_notif=DB::table('stok_awals')->whereRaw("notif_a='Y'")->count();
            if ($count_notif>0){
                $notif.='<span class="badge badge-danger">'.$count_notif.'</span>';
            }
        }else{
            $unit_kerja_id=auth()->user()->unit_kerja_id;
            $count_notif=DB::table('stok_awals')->whereRaw("notif_uk='Y' AND unit_kerja_id='$unit_kerja_id'")->count();
            
            if ($count_notif>0){
                $notif.='<span class="badge badge-danger">'.$count_notif.'</span>';
            }
        }
        if($style=='icon'){
            $result=$notif;
        }else{
            $result=$count_notif;
        }
       
        return $result;
    }
    
    public static function transferBarang($style,$jenis) {
        
        $unit_kerja_id=auth()->user()->unit_kerja_id;
        $notif='';
        if($jenis=='total'){
            $count_notif=DB::table('transfer_barangs')->whereRaw("status_terima='N' AND cancel='N' AND (unit_kerja_id='$unit_kerja_id' OR tujuan_id='$unit_kerja_id')")->count();
        }elseif($jenis=='kirim'){
            $count_notif=DB::table('transfer_barangs')->whereRaw("status_terima='N' AND cancel='N' AND unit_kerja_id='$unit_kerja_id'")->count();
        }else{
            $count_notif=DB::table('transfer_barangs')->whereRaw("status_terima='N' AND cancel='N' AND tujuan_id='$unit_kerja_id'")->count();
        }
        if ($count_notif>0){
            $notif.='<span class="badge badge-danger">'.$count_notif.'</span>';
        }
        if($style=='icon'){
            $result=$notif;
        }else{
            $result=$count_notif;
        }
       
        return $result;
    }
    

 }
