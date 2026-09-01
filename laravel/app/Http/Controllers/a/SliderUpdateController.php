<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;


class SliderUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-photo';
        $d['title']='Slider';
        return view('a.slider.main',$d);
    }

    public function get_slider(Request $request)
    {
        $f_kategori=$request->x;
        if ($request->ajax()) {
            $data = Slider::orderBy('id','DESC')->get();
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

                    ->addColumn('file', function($row){
                        if (!empty($row->file)){
                            $file= '<img src="'.url('storage/images/slider/'.$row->file).'" class="image-table"></img>';
                        }else{
                            $file= '<div class="image-table"><i class="fa fa-user"></i></div>';
                        }
                        return $file;
                    })


                    ->rawColumns(['action','file'])
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
        $d['title']='Tambah Slider';
        return view('a.slider.create',$d);
    }

    public function get_data(Request $request)
    {
        $id = Formatter::decrypt0($request->id);
        $q = Slider::where('id', $id)->first();

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

        $rules = [
            'file'=>'required|mimes:jpeg,png',
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        


        if ($request->param == 'add') {

            $slider = new Slider;
            $slider->inserted_by =  $request->session()->get('id');
            $text = 'Data sukses disimpan';
        } else {
            $slider = Slider::find(Formatter::decrypt0($request->id));

            if(!empty($request->file)){
            Formatter::deleteImageFile('slider',$slider->file);
            }
            $slider->updated_by =  $request->session()->get('id');
            $text = 'Data sukses diupdate';
        }

        if(!empty($request->file)){
            $imageName = time().'.'.$request->file->extension();
            $request->file->move(public_path('storage/images/slider'), $imageName);
            $slider->file = $imageName;
        }

        $slider->save();

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
        $slider = Slider::find(Formatter::decrypt0($request->id));
        Formatter::deleteImageFile('slider',$slider->file);
        $slider->delete();
    }
}
