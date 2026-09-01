<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelompokTim;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;


class KelompokTimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-albums';
        $d['title']='Kelompok Tim';
        return view('a.kelompok_tim.main',$d);
    }

    public function get_kelompok_tim(Request $request)
    {
        $kategori=$request->kategori;
        if ($request->ajax()) {
            $data = KelompokTim::orderBy('id','DESC')->where('kategori',$kategori)->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn='<div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                        $btn .= '<a href="#" class="btn-action edit" title="Edit"  data-id="' . Formatter::encrypt0($row->id) . '" data-toggle="modal" data-target="#modal"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                        <span class="btn-action btn-action-delete delete" title="Hapus" data-id="'.Formatter::encrypt0($row->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</span>';
                        $btn.='</div>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('tot_kordinator', function($row){
                        return 0;
                    })
                    ->addColumn('tot_relawan', function($row){
                        return 0;
                    })
                    ->addColumn('tot_pendukung', function($row){
                        return 0;
                    })
                    ->addColumn('aktif', function($row){
                        if($row->aktif=='N'){
                            $status='<span class="badge badge-danger" title="Off">Off</span>';
                        }else{
                            $status='<span class="badge badge-success" title="Aktif">Aktif</span>';
                        }
                        return $status;
                    })

                    ->rawColumns(['action','aktif'])
                    ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d['icon']='pe-7s-plus';
        $d['title']='Tambah Kelompok Tim';
        return view('a.kelompok_tim.create',$d);
    }

    public function get_data(Request $request)
    {
        $id = Formatter::decrypt0($request->id);
        $q = KelompokTim::where('id', $id)->first();

        return response()->json([
            'id' => Formatter::encrypt0($q->id),
            'q' => $q,
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

        if ($request->param == 'add') {

            $kelompok_tim = new KelompokTim;

            $kelompok_tim->inserted_by =  $request->session()->get('id');
            $text = 'Data sukses disimpan';
        } else {
            $kelompok_tim = KelompokTim::find(Formatter::decrypt0($request->id));
            $kelompok_tim->updated_by =  $request->session()->get('id');
            $text = 'Data sukses diupdate';
        }

        $kelompok_tim->kelompok_tim = $request->nama;
        $kelompok_tim->kategori = $request->kategori;
        $kelompok_tim->aktif = $request->aktif;
        $kelompok_tim->deskripsi = $request->deskripsi;

        $kelompok_tim->save();

        return response()->json([
            'text' => $text
        ]);
    }

    /**
     * Display the specified resource.
     *
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
        $kelompok_tim = KelompokTim::find(Formatter::decrypt0($request->id));
        $kelompok_tim->delete();
    }
}
