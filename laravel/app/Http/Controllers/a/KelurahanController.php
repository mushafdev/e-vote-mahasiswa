<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelurahan;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Image;
use File;


class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kec_id=Formatter::decrypt0($request->kecamatan);
        $kecamatan=DB::table('kecamatans')->where('id',$kec_id)->first();
        $d['icon']='pe-7s-server';
        $d['title']='Kecamatan '.$kecamatan->kecamatan;
        $d['sub_title']='Data <span class="text-danger font-weight-bold">Kelurahan/Desa</span>';
        $d['data']=$kecamatan;

        return view('a.daerah.kelurahan',$d);
    }

    public function get_kelurahan(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('kelurahans')->where('kec_id',$request->kec_id)->orderBy('id','DESC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('kelurahan', function($row){
                        $result = '<a href="#">'.$row->kelurahan.' </a>';
                        return $result;
                    })
                    ->addColumn('action', function($row){
                        $btn='<div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                        $btn .= '
                        <a href="#" class="btn-action edit" title="Edit"  data-id="' . Formatter::encrypt0($row->id) . '" data-toggle="modal" data-target="#modal"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                        <span class="btn-action btn-action-delete delete" title="Hapus" data-id="'.Formatter::encrypt0($row->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Hapus </span>';
                        $btn.='</div>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->rawColumns(['action','user_image','kelurahan'])
                    ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get_data(Request $request)
    {
        $id = Formatter::decrypt0($request->id);
        $q = Kelurahan::where('id', $id)->first();
        return response()->json([
            'id' => Formatter::encrypt0($q->id),
            'kelurahan' => $q->kelurahan,
            'target' => $q->target,
            'lat' => $q->lat,
            'lon' => $q->lon,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

   

    public function store(Request $request)
    {

        $kec_id= $request->kec_id;
        if ($request->param == 'add') {

            $kelurahan = new Kelurahan;
            $text = 'Data sukses disimpan';
        } else {

            $kelurahan = Kelurahan::find(Formatter::decrypt0($request->id));
            $text = 'Data sukses diupdate';
        }
        $kelurahan->kec_id = $kec_id;
        $kelurahan->kelurahan = $request->kelurahan;
        $kelurahan->target = $request->target;
        $kelurahan->lat = $request->lat;
        $kelurahan->lon = $request->lon;
        $kelurahan->save();

        return response()->json([
            'text' => $text
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $kelurahan = Kelurahan::find(Formatter::decrypt0($request->id));
        $kelurahan->delete();
    }
}
