<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function search_kabupaten(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kabupatens')->whereRaw("(kabupaten like '%".$searchTerm."%')")->orderBy('kabupaten')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->kabupaten,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_kecamatan(Request $request)
     {
        
         $kab_id =$request->x;
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kecamatans')->whereRaw("(kecamatan like '%".$searchTerm."%') AND kab_id='$kab_id'")->orderBy('kecamatan')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->kecamatan,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_kelurahan(Request $request)
     {

         $kec_id =$request->x;
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kelurahans')->whereRaw("(kelurahan like '%".$searchTerm."%') AND kec_id='$kec_id'")->orderBy('kelurahan')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->kelurahan,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_tim(Request $request)
     {

         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kelompok_tims')->whereRaw("(kelompok_tim like '%".$searchTerm."%')")->orderBy('kelompok_tim')->get();
         $kategori=array('utama'=>'Utama','sayap'=>'Sayap');
         $response = array();
         foreach($kategori as $k=>$v){
            $children = array();
            if($data->where('kategori',$k)->count()==0){
                continue;
            }
            foreach($data->where('kategori',$k) as $dt){
                $children[]=array(
                    "id"=>$dt->id,
                    "text"=>$dt->kelompok_tim,
                    
                );
             }
             $response[] = array(
                "text"=>$v,
                "children"=>$children,
                
            );
         }
         
 
         echo json_encode($response);
 
     }
     
     public function search_referensi(Request $request)
     {

         $kab_id = $request->kab_id;
         $kec_id = $request->kec_id;
         $kel_id = $request->kel_id;
         $role = $request->session()->get('role');
         $where="";
         if($role!='a'){
            $kelompok_tim_id = $request->session()->get('unit_kerja_id');
            $where.=" AND tims.kelompok_tim_id='$kelompok_tim_id'";
        }
         $searchTerm = addslashes($request->searchTerm);
        
         
         $data = DB::table('tims')
         ->leftjoin('kelompok_tims','kelompok_tims.id','=','tims.kelompok_tim_id')
         ->whereRaw("((nama like '%".$searchTerm."%') OR (nik like '%".$searchTerm."%')) AND 
            IF(tims.kel_id IS NOT NULL, tims.kel_id='$kel_id',
                IF(tims.kec_id IS NOT NULL, tims.kec_id='$kec_id',
                    IF(tims.kab_id IS NOT NULL,tims.kab_id='$kab_id','')
                )
            ) $where")
         
         ->select('tims.id','tims.nik','tims.nama','kelompok_tims.kelompok_tim','kelompok_tims.kategori','tims.kab_id','tims.kec_id','tims.kel_id')
         ->orderBy('tims.nama')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>Formatter::encrypt0($dt->id),
             "text"=>$dt->nama,
             "nik"=>$dt->nik,
             "kelompok_tim"=>$dt->kelompok_tim,
             "kategori"=>$dt->kategori,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_referensi_all(Request $request)
     {

       
         $role = $request->session()->get('role');
         $where="";
         if($role!='a'){
            $kelompok_tim_id = $request->session()->get('unit_kerja_id');
            $where.=" AND tims.kelompok_tim_id='$kelompok_tim_id'";
        }
         $searchTerm = addslashes($request->searchTerm);
        
         
         $data = DB::table('tims')
         ->leftjoin('kelompok_tims','kelompok_tims.id','=','tims.kelompok_tim_id')
         ->whereRaw("((nama like '%".$searchTerm."%') OR (nik like '%".$searchTerm."%')) $where")
         
         ->select('tims.id','tims.nik','tims.nama','kelompok_tims.kelompok_tim','kelompok_tims.kategori','tims.kab_id','tims.kec_id','tims.kel_id')
         ->orderBy('tims.nama')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>Formatter::encrypt0($dt->id),
             "text"=>$dt->nama,
             "nik"=>$dt->nik,
             "kelompok_tim"=>$dt->kelompok_tim,
             "kategori"=>$dt->kategori,
            );
         }
 
         echo json_encode($response);
 
     }

     public function data_kelurahan(Request $request)
     {

         $data = DB::table('kelurahans')->orderBy('kelurahan')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->kelurahan,
            );
         }
 
         echo json_encode($response);
 
     }
     

     



}
