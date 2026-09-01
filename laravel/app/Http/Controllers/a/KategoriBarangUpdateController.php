<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;


class KategoriBarangUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Kategori Barang';
        return view('a.kategoribarang.main',$d);
    }

    public function get_kategoribarangupdate(Request $request)
    {
        $f_kategori=$request->x;
        if ($request->ajax()) {
            $data = KategoriBarang::orderBy('id','DESC')->get();
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


                    ->rawColumns(['action'])
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
        $d['title']='Tambah Kategori Barang';
        return view('a.kategoribarang.create',$d);
    }

    public function get_data(Request $request)
    {
        $id = Formatter::decrypt0($request->id);
        $q = KategoriBarang::where('id', $id)->first();

        return response()->json([
            'id' => Formatter::encrypt0($q->id),
            'kategori_barang' => $q->kategori_barang,
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

            $kategoribarang = new KategoriBarang;
            $kategoribarang->inserted_by =  $request->session()->get('id');
            $text = 'Data sukses disimpan';
        } else {
            $kategoribarang = KategoriBarang::find(Formatter::decrypt0($request->id));
            $kategoribarang->updated_by =  $request->session()->get('id');
            $text = 'Data sukses diupdate';
        }

        $kategoribarang->kategori_barang = $request->kategori_barang;

        $kategoribarang->save();

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
        $kategoribarang = KategoriBarang::find(Formatter::decrypt0($request->id));
        $kategoribarang->delete();
    }
}
