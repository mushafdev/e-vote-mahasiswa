<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Formatter;

class AdminLoginController extends Controller
{

    public function index()
    {
        $d['sliders']=Slider::get();
        return view('a.admin_login',$d);
    }

    public function reload_captcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }

    public function authenticate(Request $request){

            $rules = [
                'username'=>'required|min:5|max:50',
                'password'=>'required|max:50',
                'captcha' => 'required|captcha'
            ];

            $messages = [
                'username.required' => 'Username kosong',
                'username.min' => 'Username tidak boleh lebih kecil dari 5 karakter',
                'password.required' => 'Password kosong',
                'password.max' => 'Password tidak boleh lebih dari 50 karakter',
                'captcha.required' => 'Captcha kosong',
                'captcha.captcha' => 'Periksa captcha',
            ];

            request()->validate($rules,$messages);

            $password = $request->password;
            if (Auth::guard('user')->attempt(['username' => addslashes($request->username), 'password' => $password, 'active' => 'Y', 'level' => 'a'],false)) {
                $user=Auth::guard('user')->user();
                $login = DB::table('sessions')->where('user_id', Auth::id())->count();
                if ($login > 0)
                {
                    Auth::logout();    
                    return redirect()->intended('admin-login')->with('error','You are Logged in on other devices');
                }else{
                    $request->session()->put('nama',$user->nama);
                    $request->session()->put('npp',$user->npp);
                    $request->session()->put('id',$user->id);
                    $request->session()->put('unit_kerja_id',$user->unit_kerja_id);
                    $request->session()->put('level',$user->level);
                    $request->session()->put('role',$user->role);
                    $request->session()->put('photo',$user->photo);
                    
                    if($user->role=='a'){
                        return redirect()->intended('pemilih/registration-list');
                    }else if($user->role=='kp'){
                        return redirect()->intended('hasil-kandidat');
                    }else if($user->role=='p'){
                        return redirect()->intended('pemilih/registration-list');
                    }else if($user->role=='su'){
                        return redirect()->intended('users');
                    }else if($user->role=='v'){
                        return redirect()->intended('pemilih/vote-list-notifikasi');
                    }
                    return redirect()->intended('/');
                }
                
                
            }

            return redirect()->intended('admin-login')->with('error','Username and Password is not valid');
    }

    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect('admin-login');
    }
}
