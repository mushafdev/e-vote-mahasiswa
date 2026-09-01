<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kecamatan;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Image;
use File;


class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kabupaten_id=Formatter::decrypt0($request->kabupaten);
        $kabupaten=DB::table('kabupatens')->where('id',$kabupaten_id)->first();
        
        $d['title']=$kabupaten->kabupaten;
        $d['sub_title']='Data <span class="text-danger font-weight-bold">Kecamatan</span>';
        $d['icon']='pe-7s-angle-right-circle';
        $d['data']=$kabupaten;

        return view('a.daerah.kecamatan',$d);
    }

    public function get_kecamatan(Request $request)
    {

        $kab_id=$request->kab_id;
        if ($request->ajax()) {
            $data = DB::table('kecamatans')->where('kab_id',$kab_id)->orderBy('id','DESC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('kecamatan', function($row){
                        $result = '<a href="'.route('admin.kelurahan.main').'?kecamatan='.Formatter::encrypt0($row->id).'">'.$row->kecamatan.' </a>';
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

                    ->rawColumns(['action','user_image','kecamatan'])
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
        $q = Kecamatan::where('id', $id)->first();
        return response()->json([
            'id' => Formatter::encrypt0($q->id),
            'kecamatan' => $q->kecamatan,
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

    public function create_id($kab_id){

        $row=DB::table('kecamatans')->where('kab_id',$kab_id)->count();
        if($row>0){
            $max_id=DB::table('kecamatans')->selectRaw('MAX(id) max_id')->where('kab_id',$kab_id)->first();
            $id=(int)$max_id->max_id+1;
        }else {
            $id='0001';
        }
        return $id;
    }

    public function store(Request $request)
    {

        $kab_id=$request->kab_id;

        if ($request->param == 'add') {

            $kecamatan = new Kecamatan;
            $text = 'Data sukses disimpan';
        } else {

            $kecamatan = Kecamatan::find(Formatter::decrypt0($request->id));
            $text = 'Data sukses diupdate';
        }
        $kecamatan->kab_id = $kab_id;
        $kecamatan->kecamatan = $request->kecamatan;
        $kecamatan->target = $request->target;
        $kecamatan->lat = $request->lat;
        $kecamatan->lon = $request->lon;
        $kecamatan->save();

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
        $kecamatan = Kecamatan::find(Formatter::decrypt0($request->id));
        $kecamatan->delete();
    }
}
