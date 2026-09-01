<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\KategoriBarangRekening;
use App\Models\SubKategoriBarangRekening;
use DataTables;
use Formatter;
use BarangHelper;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-settings';
        $d['title']='Pengaturan';
        $role=auth()->user()->role;
        if($role=='a'){
            $d['setting']=Setting::first();
            $d['persediaan_kp']=array('N'=>'Hide','Y'=>'Show');
            return view('a.setting.main',$d);
        }else{
            $kategories=DB::table('kategori_barangs')->get();
            $kategories=$kategories->map(function($row) {
                $unit_kerja_id=auth()->user()->unit_kerja_id;
                $norek='';
                // New Update
                $norek_debet='';
                $list_norek=DB::table('kategori_barang_rekenings')->whereRaw("unit_kerja_id='$unit_kerja_id' AND kategori_barang_id='$row->id'");
                if($list_norek->count()>0){
                    $norek=$list_norek->first()->kode;
                    $norek_debet=$list_norek->first()->kode_debet;
                }
                $row->norek=$norek;
                $row->norek_debet=$norek_debet;
                // New Update
                return $row;
            });
            $subkategories=DB::table('sub_kategori_barangs')->get();
            $subkategories=$subkategories->map(function($row) {
                $unit_kerja_id=auth()->user()->unit_kerja_id;
                $norek='';
                // New Update
                $norek_debet='';
                $list_norek=DB::table('sub_kategori_barang_rekenings')->whereRaw("unit_kerja_id='$unit_kerja_id' AND sub_kategori_barang_id='$row->id'");
                if($list_norek->count()>0){
                    $norek=$list_norek->first()->kode;
                    $norek_debet=$list_norek->first()->kode_debet;
                }
                $row->norek=$norek;
                $row->norek_debet=$norek_debet;
                // New Update
                return $row;
            });
            $d['kategories']=$kategories;
            $d['subkategories']=$subkategories;
            return view('a.setting.mainuk',$d);
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $role=auth()->user()->role;
        if($role=='a'){
            $setting = Setting::find(1);
            $setting->pajak=$request->pajak;
            $setting->persediaan_kp=$request->persediaan_kp;
            $setting->tgl_ro_start=$request->tgl_ro_start;
            $setting->tgl_ro_end=$request->tgl_ro_end;
            $setting->save();
        }else{

            $unit_kerja_id=auth()->user()->unit_kerja_id;
            KategoriBarangRekening::where('unit_kerja_id',$unit_kerja_id)->delete();
            SubKategoriBarangRekening::where('unit_kerja_id',$unit_kerja_id)->delete();
            foreach($request->kategori_norek as $k=>$v){
                // if($v==''){continue;}
                // $request->validate([
                //     'kategori_norek.'.$k.'' => [
                //         'required',
                //     ],
                // ]);
                $kategori=new KategoriBarangRekening;
                $kategori->kode=$v;
                // New Update
                $kategori->kode_debet=$request->kategori_norek_debet[$k];
                // New Update
                $kategori->kategori_barang_id=$k;
                $kategori->unit_kerja_id=$unit_kerja_id;
                $kategori->inserted_by=$request->session()->get('id');
                $kategori->save();
            }
            foreach($request->sub_kategori_norek as $k=>$v){
                // if($v==''){continue;}
                // $request->validate([
                //     'sub_kategori_norek.'.$k.'' => [
                //         'required',
                //     ],
                // ]);
                $subkategori=new SubKategoriBarangRekening;
                $subkategori->kode=$v;// New Update
                $subkategori->kode_debet=$request->sub_kategori_norek_debet[$k];
                // New Update
                $subkategori->sub_kategori_barang_id=$k;
                $subkategori->unit_kerja_id=$unit_kerja_id;
                $subkategori->inserted_by=$request->session()->get('id');
                $subkategori->save();
            }
        }
        
    }
}
