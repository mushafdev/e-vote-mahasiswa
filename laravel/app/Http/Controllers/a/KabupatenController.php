<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kabupaten;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Image;
use File;


class KabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Kabupaten/Kota';

        return view('a.daerah.kabupaten',$d);
    }

    public function get_kabupaten(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('kabupatens')->orderBy('id','DESC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('kabupaten', function($row){
                        $result = '<a href="'.route('admin.kecamatan.main').'?kabupaten='.Formatter::encrypt0($row->id).'">'.$row->kabupaten.' </a>';
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

                    ->rawColumns(['action','user_image','kabupaten'])
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
        $q = Kabupaten::where('id', $id)->first();
        return response()->json([
            'id' => Formatter::encrypt0($q->id),
            'kabupaten' => $q->kabupaten,
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

    public function create_id(){

        $row=DB::table('kabupatens')->count();
        if($row>0){
            $max_id=DB::table('kabupatens')->selectRaw('MAX(RIGHT(id,3)) max_id')->first();
            $id=str_pad(((int)$max_id->max_id+1), 3, '0', STR_PAD_LEFT);
        }else {
            $id='001';
        }
        return $id;
    }

    public function store(Request $request)
    {


        if ($request->param == 'add') {

            $kabupaten = new Kabupaten;
            $text = 'Data sukses disimpan';
        } else {

            $kabupaten = Kabupaten::find(Formatter::decrypt0($request->id));
            $text = 'Data sukses diupdate';
        }
        $kabupaten->kabupaten = $request->kabupaten;
        $kabupaten->target = $request->target;
        $kabupaten->lat = $request->lat;
        $kabupaten->lon = $request->lon;
        $kabupaten->save();

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
        $kabupaten = Kabupaten::find(Formatter::decrypt0($request->id));
        $kabupaten->delete();
    }
}
