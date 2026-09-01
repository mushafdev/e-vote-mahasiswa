<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Nullix\CryptoJsAes\CryptoJsAes;


class AccountUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id=$request->session()->get('id');
        $d['icon']='pe-7s-users';
        $d['title']='Account';
        // New Update
        $d['q'] = Users::
        leftJoin('unit_kerjas','unit_kerjas.id','=','users.unit_kerja_id')
        ->select('users.*','unit_kerjas.unit_kerja')->where('users.id', $id)->first();
        // New Update
        return view('a.account.edit',$d);
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
        return view('a.account.create',$d);
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
            'username'=>'required|unique:users|max:50',
            'photo'=>'max:2048|mimes:jpeg,png',
            'password2' => [
                // 'required',
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

        $users->nama = $request->name;
        $users->email = $request->email;
        $users->alamat = $request->address;
        $users->kontak = $request->contact;
        $users->username = $request->username;
        $users->remember_token=Str::random(14);
        $users->password = bcrypt($request->username);
        $users->inserted_by =  $request->session()->get('id');

        $users->save();
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

        $id=Formatter::decrypt0($request->id);
        $users = Users::find($id);
        $rules = [
            'photo'=>'max:2048|mimes:jpeg,png',
            
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        if(!empty($request->username)){
            $rules = [
                'username'=>'required|unique:users|max:50'
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];


            request()->validate($rules,$messages);
            $users->username = $request->username;
        }

        
            $key = "1;/>u+/|l35W10+Gc6<WWvw72@xc1a_'4S==-^3g?VgQ7fZOTX~XsmQSp<TK_qBup09789zsNZm}|/p+5[";
            $password = CryptoJsAes::decrypt($request->password, $key);
            if(!empty($password)){
            $request->merge([
                'password' => $password,
            ]);
            $rules = [
                'password' => [
                    // 'required',
                    'min:8',
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
                ]
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];
            request()->validate($rules,$messages);
            $users->password = bcrypt($request->password);
        }



        if(!empty($request->photo)){
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('storage/images/users'), $imageName);
            $users->photo = $imageName;
        }




        $users->nama = $request->name;
        $users->email = $request->email;
        $users->alamat = $request->address;
        $users->kontak = $request->contact;
        $users->updated_by =  $request->session()->get('id');

        $users->save();
    }

    public function update_account(Request $request)
    {


        $id=Formatter::decrypt0($request->id_user_a);
        $users = Users::find($id);
        if(!empty($request->username)){
            $rules = [
                'username'=>'required|unique:users|max:50'
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];

            request()->validate($rules,$messages);

            $users->username = $request->username;
        }
        $users->password = bcrypt($request->password2);
        $users->updated_by =  $request->session()->get('id');
        $users->save();
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
