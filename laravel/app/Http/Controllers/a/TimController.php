<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tim;
use App\Models\KelompokTim;
use Illuminate\Support\Facades\DB;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Nullix\CryptoJsAes\CryptoJsAes;


class TimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($kategori)
    {
        $d['icon']='pe-7s-download';
        $d['title']='TIM';
        $d['group_tims']=array('utama'=>'Utama','sayap'=>'Sayap');
        $d['kelompok_tims']=KelompokTim::where('kategori',$kategori)->get();
        return view('a.tim.main',$d);
    }

    public function get_tim(Request $request)
    {
        $role=$request->session()->get('role');
        $kategori=$request->kategori;
        $kabupaten=$request->kabupaten;
        $kecamatan=$request->kecamatan;
        $kelurahan=$request->kelurahan;
        $level=$request->level;
        if ($request->ajax()) {
            $where="";
            

            if($role=='a'){
                $kelompok_tim=$request->kelompok_tim;
                if(!empty($kelompok_tim)){
                    $where.=" AND tims.kelompok_tim_id='$kelompok_tim'";
                }
            }else{
                $kelompok_tim=$request->session()->get('unit_kerja_id');
                $where.=" AND tims.kelompok_tim_id='$kelompok_tim'";
            }
            if(!empty($kabupaten)){
                $where.=" AND tims.kab_id='$kabupaten'";
            }
            if(!empty($kecamatan)){
                $where.=" AND tims.kec_id='$kecamatan'";
            }
            if(!empty($kelurahan)){
                $where.=" AND tims.kel_id='$kelurahan'";
            }
            if(!empty($level)){
                $where.=" AND tims.level='$level'";
            }
            $data = DB::table('tims')
            ->leftjoin('kelompok_tims','kelompok_tims.id','=','tims.kelompok_tim_id')
            ->leftjoin('kabupatens','kabupatens.id','=','tims.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','tims.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','tims.kel_id')
            ->select('tims.*','kelompok_tims.kelompok_tim','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','kelompok_tims.kategori')
            ->whereRaw("tims.id<>'' AND kelompok_tims.kategori='$kategori' $where")->orderBy('kelompok_tims.kelompok_tim','asc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                        $btn .= '<a href="'.route('tim.edit',Formatter::encrypt0($row->id)).'" title="Edit" class="btn-action edit"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                        <span class="btn-action btn-action-delete delete" title="Hapus" title="Hapus" data-id="'.Formatter::encrypt0($row->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</span>';
                        $btn.='</div>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('level', function($row){
                        $d_level=array('K' =>'Kordinator','R'=>'Relawan');
                        $d_color=array('K' =>'warning' ,'R'=>'info');
                        return '<span class="badge badge-'.$d_color[$row->level].'">'.$d_level[$row->level].'</span>';
                    })
                    ->addColumn('area', function($row){
                        if(!empty($row->kel_id)){
                            $area = 'KEL./DESA '.$row->kelurahan;
                        }else if(!empty($row->kec_id)){
                            $area = 'KEC. '.$row->kecamatan;
                        }else{
                            $area = $row->kabupaten;
                        }
                        return $area;
                    })
                    ->addColumn('tot_pendukung', function($row){
                        return 0;
                    })
                    ->addColumn('status', function($row){
                        if($row->active=='Y'){
                            $status='<span class="badge badge-success">Aktif</span>';
                        }else{
                            $status='<span class="badge badge-danger">Tidak Aktif</span>';
                        }
                        return $status;
                    })
                    ->addColumn('user_photo', function($row){
                        if (!empty($row->photo)){
                            $photo= '<img src="'.url('storage/images/tims/'.$row->photo).'" class="photo-table"></img>';
                        }else{
                            $photo= '<div class="photo-table"><i class="fa fa-user"></i></div>';
                        }
                        return $photo;
                    })

                    ->rawColumns(['action','status','user_photo','level'])
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
        $d['title']='Invite Tim';
        $d['group_tims']=array('utama'=>'Utama','sayap'=>'Sayap');
        $d['kelompok_tims']=KelompokTim::all();
        $d['kabupatens']=DB::table('kabupatens')->get();
        return view('a.tim.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $key = "1;/>u+/|l35W10+Gc6<WWvw72@xc1a_'4S==-^3g?VgQ7fZOTX~XsmQSp<TK_qBup09789zsNZm}|/p+5[";
        // $password = CryptoJsAes::decrypt($request->password, $key);
        // $request->merge([
        //     'password' => $password,
        // ]);
        $rules = [
            // 'username'=>'required|unique:users|max:50',

            'nik'=>'required|unique:tims|max:20',
            'photo'=>'max:2048|mimes:jpeg,png',
            // 'password' => [
            //     'required',
            //     'min:8',
            //     'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            // ]
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $tims = new Tim;
        if(!empty($request->photo)){
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('storage/images/tims'), $imageName);
            $tims->photo = $imageName;
        }

        $tims->nama = $request->nama;
        $tims->nik = $request->nik;
        $tims->telp = $request->telp;
        $tims->email = $request->email;
        $tims->alamat = $request->address;
        $tims->jenis_kelamin = $request->jenis_kelamin;
        $tims->level = $request->level;
        if($request->level=='K'){
            if(!empty($request->kel_id)){
                $tims->role = 'KKL';
            }else if(!empty($request->kec_id)){
                $tims->role = 'KKC';
            }else{
                $tims->role = 'KKB';
            }
        }else{
            $tims->role = 'R';
        }
        
        $tims->kelompok_tim_id = $request->kelompok_tim_id;
        $tims->kab_id = $request->kab_id;
        $tims->kel_id = $request->kel_id;
        $tims->kec_id = $request->kec_id;
        // $tims->username = $request->username;
        // $tims->remember_token=Str::random(14);
        // $tims->password = bcrypt($request->password);
        $tims->inserted_by =  $request->session()->get('id');

        $tims->save();
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
        $d['title']='Edit Tim';
        $d['group_tims']=array('utama'=>'Utama','sayap'=>'Sayap');
        $d['kelompok_tims']=KelompokTim::all();
        $d['kabupatens']=DB::table('kabupatens')->get();
        $d['jenis_kelamin']=array('L'=>'Laki-laki','P'=>'Perempuan');
        $d['level']=array('K'=>'Kordinator','R'=>'Relawan');
        $d['q'] = Tim::where('tims.id', $id)
        ->leftJoin('kelompok_tims','kelompok_tims.id','=','tims.kelompok_tim_id')
        ->leftJoin('kecamatans','kecamatans.id','=','tims.kec_id')
        ->leftJoin('kelurahans','kelurahans.id','=','tims.kel_id')
        ->select('tims.*','kecamatans.kecamatan','kelurahans.kelurahan','kelompok_tims.kategori')
        ->first();
        return view('a.tim.edit',$d);
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
        $tims = Tim::find($id);
        $rules = [
            
            'photo'=>'max:2048|mimes:jpeg,png',
            'nik'=>'required|max:20|unique:tims,nik,'.$tims->id,
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        if(!empty($request->photo)){
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('storage/images/tims'), $imageName);
            $tims->photo = $imageName;
        }

        $tims->nama = $request->nama;
        $tims->nik = $request->nik;
        $tims->telp = $request->telp;
        $tims->email = $request->email;
        $tims->alamat = $request->address;
        $tims->jenis_kelamin = $request->jenis_kelamin;
        $tims->level = $request->level;
        if($request->level=='K'){
            if(!empty($request->kel_id)){
                $tims->role = 'KKL';
            }else if(!empty($request->kec_id)){
                $tims->role = 'KKC';
            }else{
                $tims->role = 'KKB';
            }
        }else{
            $tims->role = 'R';
        }
        
        $tims->kelompok_tim_id = $request->kelompok_tim_id;
        $tims->kab_id = $request->kab_id;
        $tims->kel_id = $request->kel_id;
        $tims->kec_id = $request->kec_id;
        
        $tims->updated_by =  $request->session()->get('id');

        $tims->save();
    }

    public function update_account(Request $request)
    {


        $id=Formatter::decrypt0($request->id_user_a);
        $tims = Tim::find($id);
        if(!empty($request->username)){
            $rules = [
                'username'=>'required|unique:users|max:50',
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];

            request()->validate($rules,$messages);

            $tims->username = $request->username;
        }
        if(!empty($request->password2)){
            $key = "1;/>u+/|l35W10+Gc6<WWvw72@xc1a_'4S==-^3g?VgQ7fZOTX~XsmQSp<TK_qBup09789zsNZm}|/p+5[";
            $password = CryptoJsAes::decrypt($request->password2, $key);
            $request->merge([
                'password2' => $password,
            ]);
           
            $rules = [
                'password2' => [
                    'required',
                    'min:8',
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
                ]
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];

            request()->validate($rules,$messages);
            $tims->password = bcrypt($request->password2);
        }
        $tims->updated_by =  $request->session()->get('id');
        $tims->save();
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $tims = Tim::find(Formatter::decrypt0($request->id));
        $tims->delete();
    }
}
