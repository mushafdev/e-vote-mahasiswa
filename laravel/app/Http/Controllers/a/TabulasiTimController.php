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
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TabulasiTimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function tim($kategori)
    {
        $d['icon']='pe-7s-graph';
        $d['title']='Tabulasi <span class="text-danger font-weight-bold">TIM</span>';
        $d['sub_title']='Data TIM';
        $kelompok_tims=DB::table('kelompok_tims')->where('kategori',$kategori)->get();
        $where="";
        $kelompok_tims=$kelompok_tims->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->join('tims','tims.id','=','pemilihs.tim_id')->whereRaw("pemilihs.pendukung='Y' AND tims.kelompok_tim_id='$row->id' ")->count();
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
        $d['kelompok_tims']=$kelompok_tims;
        
        return view('a.tabulasi.tim',$d);
    }
    
    public function detail(Request $request)
    {
        $kelompok_tim_id=Formatter::decrypt0($request->kelompok_tim);
        $d['icon']='pe-7s-graph';
        $d['title']='Tabulasi <span class="text-danger font-weight-bold">TIM</span>';
        $d['sub_title']='Data TIM';
        $kelompok_tims=DB::table('kelompok_tims')->where('id',$kelompok_tim_id)->first();
        $tims=DB::table('tims')->where('kelompok_tim_id',$kelompok_tim_id)->get();
        $where="";
        $tims=$tims->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->whereRaw("pemilihs.pendukung='Y' AND pemilihs.tim_id='$row->id' ")->count();
            $row->tercapai=$pendukung;
            $row->tercapai_show='<span class="text-success font-weight-bold">'.$pendukung.'</span>';
            return $row;
        });
        $d['tims']=$tims;
        $d['kelompok_tims']=$kelompok_tims;
        
        return view('a.tabulasi.tim_detail',$d);
    }
    
    public function tim_bar_chart(Request $request)
    {
        $kategori=$request->kategori;
        $kelompok_tims= DB::table('kelompok_tims')->where('kategori',$kategori)->get();
        $where="";
        $kelompok_tims=$kelompok_tims->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->join('tims','tims.id','=','pemilihs.tim_id')->whereRaw("pemilihs.pendukung='Y' AND tims.kelompok_tim_id='$row->id' ")->count();
            $persentase=($row->target<=0)?0:($pendukung/$row->target)*100;
            $persentase=round($persentase,2);
            $row->persentase=$persentase;
            $row->tercapai=$pendukung;
            return $row;
        });

        $data=array();
        foreach ($kelompok_tims as $dt) {
            $data[]=array(
                'kelompok_tim'=>$dt->kelompok_tim,
                'jumlah'=>$dt->tercapai,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }
    
    public function detail_bar_chart(Request $request)
    {
        $kelompok_tim_id=$request->kelompok_tim_id;
        $kelompok_tims=DB::table('kelompok_tims')->where('id',$kelompok_tim_id)->first();
        $tims=DB::table('tims')->where('kelompok_tim_id',$kelompok_tim_id)->get();
        $where="";
        $tims=$tims->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->whereRaw("pemilihs.pendukung='Y' AND pemilihs.tim_id='$row->id' ")->count();
            $row->tercapai=$pendukung;
            return $row;
        });

        $data=array();
        foreach ($tims as $dt) {
            $data[]=array(
                'nama'=>$dt->nama,
                'jumlah'=>$dt->tercapai,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }

    public function tim_line_chart(Request $request)
    {
        $kategori=$request->kategori;
        $startDate=Carbon::createFromFormat('Y-m-d',$request->start_date);
        $endDate=Carbon::createFromFormat('Y-m-d',$request->end_date);

        $period = CarbonPeriod::create($startDate, $endDate);
        $kelompok_tims=DB::table('kelompok_tims')->select('id','kelompok_tim')->where('kategori',$kategori)->get();
        $where="";
        
        $data=array();
        foreach ($period as $date) {
            $kelompokArray=array();
            $kelompokArray['date']=$date->format('Y-m-d');
            foreach($kelompok_tims as $kt){
                $tgl=$date->format('Y-m-d');
                $pendukung=DB::table('pemilihs')->join('tims','tims.id','=','pemilihs.tim_id')->whereRaw("pemilihs.pendukung='Y' AND tims.kelompok_tim_id='$kt->id' AND DATE(pemilihs.time_invite)='$tgl' ")->count();
                $kelompokArray[$kt->id]=$pendukung;
            }
            $data[]=$kelompokArray;
            
        }


        return response()->json([
            'x' =>$data,
            'kelompok_tims' =>$kelompok_tims
        ]);
    }

}
