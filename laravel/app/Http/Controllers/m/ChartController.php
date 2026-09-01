<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ChartController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:chart bar-hasil-kandidat', ['only' => ['bar_chart_hasil_kandidat']]);
    }
    
    public function bar_chart_calon_kandidat_2(Request $request)
    {
        $kategori=$request->kategori;
        $kandidats= DB::table('kandidats')->where('calon_kandidat_status','Y')->get();
        $where="";
        $kandidats=$kandidats->map(function($row) use ($where) {
            $pendukung=DB::table('pemilihs')->where('calon_kandidat_2_id',$row->id)->count();
            $row->total_suara=$pendukung;
            return $row;
        });

        $data=array();
        foreach ($kandidats as $dt) {
            $data[]=array(
                'kandidat'=>$dt->nama,
                'jumlah'=>$dt->total_suara,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }
    
    public function bar_chart_hasil_kandidat(Request $request)
    {
        $kategori=$request->kategori;
        $kandidats= DB::table('kandidats')->where('kandidat_status','Y')->get();

        // Rekap suara dari kolom terenkripsi vote_enc (didekripsi di aplikasi)
        $tallySuara = [];
        $ciphers = DB::table('pemilihs')->whereNotNull('vote_enc')->where('verifikasi_vote','Y')->pluck('vote_enc');
        foreach ($ciphers as $cipher) {
            $kandidatId = \App\Models\Pemilih::decryptVote($cipher);
            if ($kandidatId !== null) {
                $tallySuara[$kandidatId] = ($tallySuara[$kandidatId] ?? 0) + 1;
            }
        }

        $kandidats=$kandidats->map(function($row) use ($tallySuara) {
            $row->total_suara = $tallySuara[$row->id] ?? 0;
            return $row;
        });

        $data=array();
        foreach ($kandidats as $dt) {
            $data[]=array(
                'kandidat'=>$dt->nama,
                'jumlah'=>$dt->total_suara,
            );
        }


        return response()->json([
            'x' =>$data
        ]);
    }

     



}
