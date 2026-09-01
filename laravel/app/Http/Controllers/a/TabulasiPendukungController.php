<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pemilih;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Nullix\CryptoJsAes\CryptoJsAes;


class TabulasiPendukungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function kabupaten()
    {
        $d['icon']='pe-7s-graph';
        $d['title']='Tabulasi <span class="text-danger font-weight-bold">Pendukung</span>';
        $d['sub_title']='Kabupaten';
        $kabupatens=DB::table('kabupatens')->get();
        $kab_id=Formatter::identity()['regency_id'];
        $where="";
        $kabupatens=$kabupatens->map(function($row) use ($where,$kab_id) {
            $pendukung=DB::table('pemilihs')->whereRaw("pendukung='Y' AND kab_id='$kab_id'")->count();
            $persentase=($row->target<=0)?0:($pendukung/$row->target)*100;
            $persentase=round($persentase,2);
            $row->tercapai=$pendukung;
            if($pendukung<$row->target){
                $row->tercapai_show='<span class="text-danger font-weight-bold">'.$pendukung.'</span>';
                $row->persentase_show='<span class="badge badge-lg badge-danger">'.$persentase.'%</span>';
            }else{
                $row->tercapai_show='<span class="text-success font-weight-bold">'.$pendukung.'</span>';
                $row->persentase_show='<span class="badge badge-lg badge-success">'.$persentase.'%</span>';
            }
            $row->persentase=$persentase;
            return $row;
        });
        $d['kabupatens']=$kabupatens;
        
        return view('a.tabulasi.kabupaten',$d);
    }
    
    public function kecamatan(Request $request)
    {
        $kab_id=Formatter::decrypt0($request->kabupaten);
        $kabupaten=DB::table('kabupatens')->where('id',$kab_id)->first();
        $d['icon']='pe-7s-graph';
        $d['title']='Tabulasi Pendukung <span class="text-danger font-weight-bold">'.$kabupaten->kabupaten.'</span>';
        $d['sub_title']='Data Kecamatan';
        $kecamatans= DB::table('kecamatans')->where('kab_id',$kab_id)->orderBy('id','DESC')->get();
        $where="";
        $kecamatans=$kecamatans->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->whereRaw("pendukung='Y' AND kec_id='$row->id'")->count();
            $persentase=($row->target<=0)?0:($pendukung/$row->target)*100;
            $persentase=round($persentase,2);
            $row->tercapai=$pendukung;
            if($pendukung<$row->target){
                $row->tercapai_show='<span class="text-danger font-weight-bold">'.$pendukung.'</span>';
                $row->persentase_show='<span class="badge badge-lg badge-danger">'.$persentase.'%</span>';
            }else{
                $row->tercapai_show='<span class="text-success font-weight-bold">'.$pendukung.'</span>';
                $row->persentase_show='<span class="badge badge-lg badge-success">'.$persentase.'%</span>';
            }
            $row->persentase=$persentase;
            return $row;
        });
        $d['kabupaten']=$kabupaten;
        $d['kecamatans']=$kecamatans;
        
        return view('a.tabulasi.kecamatan',$d);
    }

    public function kecamatan_bar_chart(Request $request)
    {
        $kab_id=$request->kab_id;
        $kecamatans= DB::table('kecamatans')->where('kab_id',$kab_id)->orderBy('id','DESC')->get();
        $where="";
        $kecamatans=$kecamatans->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->whereRaw("pendukung='Y' AND kec_id='$row->id'")->count();
            $persentase=($row->target<=0)?0:($pendukung/$row->target)*100;
            $persentase=round($persentase,2);
            $row->persentase=$persentase;
            $row->tercapai=$pendukung;
            return $row;
        });

        $data=array();
        foreach ($kecamatans as $dt) {
            $data[]=array(
                'kecamatan'=>$dt->kecamatan,
                'jumlah'=>$dt->tercapai,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }
   
    public function kelurahan(Request $request)
    {
        $kec_id=Formatter::decrypt0($request->kecamatan);
        $kecamatan=DB::table('kecamatans')->where('id',$kec_id)->first();
        $d['icon']='pe-7s-graph';
        $d['title']='Tabulasi Pendukung <span class="text-danger font-weight-bold">'.$kecamatan->kecamatan.'</span>';
        $d['sub_title']='Data Kelurahan/Desa';
        $kelurahans= DB::table('kelurahans')->where('kec_id',$kec_id)->orderBy('id','DESC')->get();
        $where="";
        $kelurahans=$kelurahans->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->whereRaw("pendukung='Y' AND kel_id='$row->id'")->count();
            $persentase=($row->target<=0)?0:($pendukung/$row->target)*100;
            $persentase=round($persentase,2);
            $row->tercapai=$pendukung;
            if($pendukung<$row->target){
                $row->tercapai_show='<span class="text-danger font-weight-bold">'.$pendukung.'</span>';
                $row->persentase_show='<span class="badge badge-lg badge-danger">'.$persentase.'%</span>';
            }else{
                $row->tercapai_show='<span class="text-success font-weight-bold">'.$pendukung.'</span>';
                $row->persentase_show='<span class="badge badge-lg badge-success">'.$persentase.'%</span>';
            }
            $row->persentase=$persentase;
            return $row;
        });
        $d['kecamatan']=$kecamatan;
        $d['kelurahans']=$kelurahans;
        
        return view('a.tabulasi.kelurahan',$d);
    }

    public function kelurahan_bar_chart(Request $request)
    {
        $kec_id=$request->kec_id;
        $kelurahans= DB::table('kelurahans')->where('kec_id',$kec_id)->orderBy('id','DESC')->get();
        $where="";
        $kelurahans=$kelurahans->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->whereRaw("pendukung='Y' AND kel_id='$row->id'")->count();
            $persentase=($row->target<=0)?0:($pendukung/$row->target)*100;
            $persentase=round($persentase,2);
            $row->persentase=$persentase;
            $row->tercapai=$pendukung;
            return $row;
        });

        $data=array();
        foreach ($kelurahans as $dt) {
            $data[]=array(
                'kelurahan'=>$dt->kelurahan,
                'jumlah'=>$dt->tercapai,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }

    public function tps(Request $request)
    {
        $kel_id=Formatter::decrypt0($request->kelurahan);
        $kelurahan=DB::table('kelurahans')->where('id',$kel_id)->first();
        $d['icon']='pe-7s-graph';
        $d['title']='Tabulasi Pendukung <span class="text-danger font-weight-bold">'.$kelurahan->kelurahan.'</span>';
        $d['sub_title']='Data TPS';
        $tps= DB::table('pemilihs')->groupBy('tps')->where('kel_id',$kel_id)->orderBy('tps','ASC')->get();
        $where="";
        $tps=$tps->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->whereRaw("pendukung='Y' AND kel_id='$row->kel_id' AND tps='$row->tps'")->count();
            $row->tercapai=$pendukung;
            $row->tercapai_show='<span class="text-danger font-weight-bold">'.$pendukung.'</span>';
            return $row;
        });
        $d['tps']=$tps;
        $d['kelurahan']=$kelurahan;
        
        return view('a.tabulasi.tps',$d);
    }

}
