<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserTheme;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Nullix\CryptoJsAes\CryptoJsAes;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users view', ['only' => ['index','get_users','get_account','update_account','update_theme']]);
        $this->middleware('permission:users create', ['only' => ['create','store','update_theme']]);
        $this->middleware('permission:users update', ['only' => ['update','edit','update_theme']]);
        $this->middleware('permission:users delete', ['only' => ['destroy','update_theme']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-users';
        $d['title']='Users';
        $d['data']=Users::all();
        return view('a.users.main',$d);
    }

    public function get_users(Request $request)
    {
        if ($request->ajax()) {
            $data = Users::leftjoin('kelompok_tims','kelompok_tims.id','=','users.unit_kerja_id')
            ->select('users.*','kelompok_tims.kelompok_tim')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                        $btn .= '<a href="'.route('users.edit',Formatter::encrypt0($row->id)).'" title="Edit" class="btn-action edit"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                        <a href="#" class="btn-action btn-action-warning account" title="Update Akun" data-id="'.Formatter::encrypt0($row->id).'" data-toggle="modal" data-target="#modal-account"><i class="fa fa-key" aria-hidden="true"></i> Update Akun</a>
                        <span class="btn-action btn-action-delete delete" title="Hapus" title="Hapus" data-id="'.Formatter::encrypt0($row->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</span>';
                        $btn.='</div>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('level', function($row){
                        $d_level=array('su' =>'Super Admin','sa'=>'Sub Admin','a'=>'Admin','p'=>'Panlih','kp'=>'Ketua Panlih','v'=>'Verifikator Suara');
                        $d_color=array('su' =>'warning' ,'sa'=>'dark','a'=>'info','p'=>'success','kp'=>'primary','v'=>'secondary');
                        $label = $d_level[$row->role] ?? ucfirst($row->role);
                        $color = $d_color[$row->role] ?? 'secondary';
                        if($row->role=='a'){
                           $result= '<span class="badge badge-'.$color.'">'.$label.'</span>';
                        }else{
                            $result='<span class="badge badge-'.$color.'">'.$label.'</span>'.'<span class="badge badge-secondary ml-1">'.$row->kelompok_tim.'</span>';
                        }
                        return $result;
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
                            $photo= '<img src="'.url('storage/images/users/'.$row->photo).'" class="photo-table"></img>';
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
        $d['title']='Tambah User';
        $d['group_tims']=array('utama'=>'Utama','sayap'=>'Sayap');
        return view('a.users.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::transaction(function() use ($request,&$no_transaksi,&$error) {
        $key = "1;/>u+/|l35W10+Gc6<WWvw72@xc1a_'4S==-^3g?VgQ7fZOTX~XsmQSp<TK_qBup09789zsNZm}|/p+5[";
        $password = CryptoJsAes::decrypt($request->password, $key);
        $request->merge([
            'password' => $password,
        ]);
        $rules = [
            'username'=>'required|unique:users|max:50',
            'photo'=>'max:2048|mimes:jpeg,png',
            'password' => [
                'required',
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            ]
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $users = new Users;
        if(!empty($request->photo)){
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('storage/images/users'), $imageName);
            $users->photo = $imageName;
        }

        $users->unit_kerja_id = $request->kelompok_tim_id;
        $users->nama = $request->name;
        $users->email = $request->email;
        $users->alamat = $request->address;
        $users->kontak = $request->contact;
        $users->active = $request->active;
        $users->role = $request->level;
        $users->level = 'a';
        $users->username = $request->username;
        $users->remember_token=Str::random(14);
        $users->password = bcrypt($request->password);
        $users->inserted_by =  $request->session()->get('id');


        $users->syncRoles($request->level);

        $users->save();

        });
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
        $d['title']='Edit User';
        $d['group_tims']=array('utama'=>'Utama','sayap'=>'Sayap');
        $d['q'] = Users::where('id', $id)->first();
        return view('a.users.edit',$d);
    }

    public function get_account(Request $request)
    {
        $id=Formatter::decrypt0($request->id);
        $q = Users::where('id', $id)->first();
        return response()->json([
            'id_user' => Formatter::encrypt0($q->id),
            'username' => $q->username
        ]);
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

        DB::transaction(function() use ($request,&$no_transaksi,&$error) {
        $id=Formatter::decrypt0($request->id);
        $users = Users::find($id);
        $rules = [
            'photo'=>'max:2048|mimes:jpeg,png'
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        if(!empty($request->photo)){
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('storage/images/users'), $imageName);
            $users->photo = $imageName;
        }

        $users->unit_kerja_id = $request->kelompok_tim_id;
        $users->nama = $request->name;
        $users->email = $request->email;
        $users->alamat = $request->address;
        $users->kontak = $request->contact;
        $users->role = $request->level;
        $users->level = 'a';
        $users->active = $request->active;
        $users->updated_by =  $request->session()->get('id');

        $users->syncRoles($request->level);
        $users->save();
    });
    }

    public function update_account(Request $request)
    {


        $id=Formatter::decrypt0($request->id_user_a);
        $users = Users::find($id);
        if(!empty($request->username)){
            $rules = [
                'username'=>'required|unique:users|max:50',
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];

            request()->validate($rules,$messages);

            $users->username = $request->username;
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
            $users->password = bcrypt($request->password2);
        }
        $users->updated_by =  $request->session()->get('id');
        $users->save();
    }
    
    public function update_theme(Request $request)
    {

        $user_id=$request->session()->get('id');
        UserTheme::where('user_id',$user_id)->delete();

        $count=count($request->theme);
            for($i=0;$i<$count;$i++){
                if($request->theme[$i]==''){
                    continue;
                }
                if (str_contains($request->theme[$i], 'sidebar')) { 
                    $type="sidebar";
                }elseif(str_contains($request->theme[$i], 'header')){
                    $type='header';
                }else{
                    $type="page";
                }

                $usertheme=new UserTheme;
                $usertheme->user_id=$user_id;
                $usertheme->type=$type;
                $usertheme->class=$request->theme[$i];
                $usertheme->save();
                $error='';


            }

        
        return response()->json([
            'text' => 'Success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $users = Users::find(Formatter::decrypt0($request->id));
        $users->delete();
    }
}
