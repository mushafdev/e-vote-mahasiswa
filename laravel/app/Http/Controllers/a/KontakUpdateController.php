<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kontak;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;


class KontakUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-call';
        $d['title']='Kontak';
        $d['data']=Kontak::all();
        return view('a.kontak.main',$d);
    }

    public function get_kontakupdate(Request $request)
    {
        if ($request->ajax()) {
            $data = Kontak::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<center>
                        <a href="'.route('kontakupdate.edit',Formatter::encrypt0($row->id)).'" class="btn-action edit"><i class="fa fa-edit" aria-hidden="true"></i> Edit </a>
                                                    <span class="btn-action btn-action-delete delete" title="Hapus" data-id="'.Formatter::encrypt0($row->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Hapus </span>
                                                    </center>';
                        return $btn;
                    })
                    ->addColumn('user_photo', function($row){
                        if (!empty($row->photo)){
                            $photo= '<img src="'.url('images/artikel/'.$row->photo).'" class="image-table"></img>';
                        }else{
                            $photo= '<div class="image-table"><i class="fa fa-image"></i></div>';
                        }
                        return $photo;
                    })

                    ->addColumn('active', function($row){
                        if($row->active=='Y'){
                            $active='<span class="badge badge-success">Aktif</span>';
                        }else{
                            $active='<span class="badge badge-danger">Tidak Aktif</span>';
                        }
                        return $active;
                    })

                    ->rawColumns(['action','status','user_photo', 'active'])
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
        $d['title']='Tambah Kontak';
        return view('a.kontak.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $kontak = new Kontak;
        $kontak->title = $request->name;
        $kontak->content = $request->content;
        $kontak->active = $request->active;
        $kontak->by_user =  $request->session()->get('id');

        $kontak->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=Formatter::decrypt0($id);
        $d['icon']='pe-7s-note';
        $d['title']='Update Kontak';
        $d['q'] = Kontak::where('id', $id)->first();
        return view('a.kontak.main',$d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id=Formatter::decrypt0($request->id);
        $kontak = Kontak::find($id);

        $kontak->alamat = $request->alamat;
        $kontak->phone = $request->phone;
        $kontak->facebook = $request->facebook;
        $kontak->facebook_link = $request->facebook_link;
        $kontak->instagram = $request->instagram;
        $kontak->instagram_link = $request->instagram_link;
        $kontak->whatsapp = $request->whatsapp;
        $kontak->whatsapp_link = $request->whatsapp_link;
        $kontak->map = $request->map;
        $kontak->tax = $request->tax;
        $kontak->email = $request->email;
        $kontak->by_user =  $request->session()->get('id');

        $kontak->save();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $kontak = Kontak::find(Formatter::decrypt0($request->id));
        $kontak->delete();
    }
}
