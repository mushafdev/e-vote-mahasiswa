<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Formatter;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminController extends Controller
{
    public function index(Request $request){
        $kelompok_tim_id=$request->session()->get('unit_kerja_id');
        $where='';
        if(!empty($kelompok_tim_id)){
            $where.=" AND tims.kelompok_tim_id='$kelompok_tim_id'";
        }
        $d['icon']='pe-7s-display1';
        $d['title']='Dashboard';
        $d['tot_dpt']=0;
        $d['tot_kec']=0;
        $d['tot_kel']=0;
        $d['tot_tps']=0;
        $d['tot_pendukung']=0;
        $d['tot_pendukung_l']=0;
        $d['tot_pendukung_p']=0;
        return view('a.dashboard',$d);
    }

    public function line_chart(Request $request)
    {
        $startDate=Carbon::createFromFormat('Y-m-d',$request->start_date);
        $endDate=Carbon::createFromFormat('Y-m-d',$request->end_date);
        $check=Carbon::now()->between($startDate,$endDate);

        $period = CarbonPeriod::create($request->start_date, $request->end_date);
        $kelompok_tim_id=$request->session()->get('unit_kerja_id');
      
        $data=array();
        foreach ($period as $date) {
            $tgl=$date->format('Y-m-d');
            $where='';
            if(!empty($kelompok_tim_id)){
                $where.=" AND tims.kelompok_tim_id='$kelompok_tim_id'";
            }
            $pendukung=DB::table('pemilihs')
            ->leftJoin('tims','tims.id','=','pemilihs.tim_id')
            ->whereRaw("pemilihs.pendukung='Y' AND DATE(pemilihs.time_invite)='$tgl' $where ")
            ->count();
            $data[]=array(
                'date'=>$date->format('Y-m-d'),
                'pendukung'=>$pendukung,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }
   
    public function pie_chart(Request $request)
    {
        $kategories=array('S'=>'Strong','W'=>'Weak');
        $kelompok_tim_id=$request->session()->get('unit_kerja_id');
      
        foreach($kategories as $k=> $v){
            $where='';
            if(!empty($kelompok_tim_id)){
                $where.=" AND tims.kelompok_tim_id='$kelompok_tim_id'";
            }
            $pendukung=DB::table('pemilihs')
            ->leftJoin('tims','tims.id','=','pemilihs.tim_id')
            ->whereRaw("pemilihs.pendukung='Y' AND pemilihs.kategori_pendukung='$k' $where")->count();
            $data[]=array(
                'kategori'=>$v,
                'jumlah'=>$pendukung,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }
}
