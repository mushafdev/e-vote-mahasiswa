<?php

namespace App\Http\Controllers\u;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SessionModel;
use App\Models\Slider;
use App\Models\Pemilih;
use App\Models\VerificationOtp;
use Nullix\CryptoJsAes\CryptoJsAes;
use Formatter;
use RememberMe;
use App\Mail\VerifikasiOtpMail;
use App\Mail\VerifikasiEmailMail;
use App\Mail\SendEmailNotifikasi;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Jobs\SendEmailNotifikasiJob;
use App\Rules\CheckPemilih;
use App\Rules\CheckPemilihTerdaftar;
use App\Services\FaceRecognitionService;


class LoginController extends Controller
{

    public function index(Request $request)
    {
        // if (RememberMe::is_user_logged_in()) {
        //     return redirect()->intended('verifikasi-login');
        // }
        $d['sliders']=Slider::get();
        return view('u.login',$d);
    }
    
    public function close_page(Request $request)
    {
        
        return view('u.close_page');

    }

    public function verifikasi_login(Request $request)
    {
        $id=Formatter::decrypt0($request->session()->get('id_enc_otp'));
        $pemilih=Pemilih::whereId($id)->where('email_verifikasi','Y')->where('aktif','Y')->count();
        if($pemilih>0){
            return view('u.verifikasi_login');
        }
        return redirect()->intended('/');
    }

    public function registrasi()
    {
        return view('u.registrasi');
    }

    public function lupa_akun()
    {
        return view('u.lupa_akun');
    }

    public function form_update_akun(Request $request)
    {
        $time=base64_decode(Formatter::decrypt0($request->x));
        $start_date=Carbon::create($time);
        $end_date=Carbon::create(date('Y-m-d H:i:s'));
        $hour=(int)$end_date->diffInHours($start_date);
        $id=Formatter::decrypt0($request->a);
        $data=Pemilih::whereId($id);
        $pemilih=$data->first();
        if($data->count()>0){
            if($hour>=1){
                $status='expired';

            }else{
                $status='';
            }
            return view('u.form_update_akun',compact('pemilih','time','status'));
        }else{
            return redirect()->intended('/');
        }
    }

    public function respone(Request $request)
    {
        $page=$request->page;
        $id=Formatter::decrypt0($request->session()->get('id_enc_response'));
        $data=Pemilih::whereId($id);
        $respone=$request->session()->get('page_response');
        $pemilih=$data->first();
        if($data->count()>0){
            return view('u.reg_response',compact('pemilih','respone'));
        }else{
            return redirect()->intended('/');
        }



    }
   
    public function verify_email(Request $request)
    {
        $id1=Formatter::decrypt0($request->segment(3));
        $id2=base64_decode($request->segment(4));
        $error='';
        if (Hash::check($id1, $id2)) {
            $error='';
            $data=Pemilih::where('email',$id1);
            if($data->count()>0){

                $pemilih=$data->first();
                if($pemilih->email_verifikasi=='N'){
                    $respone='respone_sukses_verifikasi_email';
                    $data->update([
                        'email_verified_at'=>Carbon::now(),
                        'email_verifikasi'=>'Y',
                    ]);
                    // Broadcast Notifikasi
                    $admins=DB::table('users')->select('email','nama')->get();
                    foreach($admins as $ad){
                        $metaEmail = [
                            'judul' => date('d/m/Y H:i:s').' - '.'Notifikasi Pendaftar '.Formatter::identity()['title'],
                            'nama_penerima' => $ad->nama,
                            'nama' => $pemilih->nama,
                            'no_anggota' => $pemilih->no_anggota,
                        ];
                        // Mail::to($ad->email)->send(new SendEmailNotifikasi($metaEmail));
                        dispatch(new SendEmailNotifikasiJob($ad->email,$metaEmail));
                    }
                    // Broadcast Notifikasi
                    return view('u.reg_response',compact('pemilih','respone'));
                }else{
                    return redirect()->intended('/');
                }
            }
        }

        $respone='respone_error';
        return view('u.reg_response',compact('respone','error'));
    }

    public function verifikasi_wajah_login_page(Request $request)
    {
        $id_enc = $request->session()->get('id_enc_face_login');
        if (!$id_enc) return redirect('login')->with('error', 'Sesi tidak valid, silahkan login ulang.');
        $id = Formatter::decrypt0($id_enc);
        $user = Pemilih::find($id);
        if (!$user) return redirect('login')->with('error', 'Pengguna tidak ditemukan.');
        return view('u.verifikasi_wajah_login', compact('user'));
    }

    public function verifikasi_wajah_login_proses(Request $request)
    {
        $id_enc = $request->session()->get('id_enc_face_login');
        if (!$id_enc) {
            return response()->json(['message' => 'Sesi tidak valid'], 401);
        }
        $id   = Formatter::decrypt0($id_enc);
        $user = Pemilih::find($id);
        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        $imageBase64 = $request->input('face_image');
        if (empty($imageBase64)) {
            return response()->json(['message' => 'Foto wajah tidak ditemukan'], 422);
        }

        try {
            $faceService = new FaceRecognitionService();
            $result = $faceService->verify($user->id, $imageBase64);

            // Log verifikasi
            \App\Models\FaceVerifyLog::create([
                'pemilih_id'      => $user->id,
                'aksi'            => 'verify_login',
                'match'           => $result['match'] ?? false,
                'confidence'      => $result['confidence'] ?? 0,
                'threshold'       => $result['threshold'] ?? 60,
                'is_real'         => $result['is_real'] ?? true,
                'spoof_score'     => $result['spoof_score'] ?? 1.0,
                'antispoof_label' => $result['antispoof_label'] ?? 'real',
                'ip_address'      => $request->ip(),
            ]);

            if (!($result['match'] ?? false)) {
                $reason = !($result['is_real'] ?? true) ? 'Terdeteksi spoofing, gunakan wajah asli.' : 'Wajah tidak cocok, coba lagi.';
                return response()->json(['message' => $reason, 'match' => false], 422);
            }

            Auth::guard('pemilih')->login($user);
            $request->session()->forget('id_enc_face_login');

            return response()->json(['message' => 'Verifikasi berhasil', 'url' => url('account/home')], 200);

        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal menghubungi Face API: ' . $e->getMessage()], 500);
        }
    }

    public function authenticate(Request $request){
        
        $rules = [
            'username'=>'required|min:5|max:50',
            'password'=>'required|max:50',
        ];

        $messages = [
            'username.required' => 'Username kosong',
            'username.min' => 'Username tidak boleh lebih kecil dari 5 karakter',
            'password.required' => 'Password kosong',
        ];

        request()->validate($rules,$messages);

        $password = $request->password;
        $pemilih=Pemilih::where('username',addslashes($request->username))->where('aktif','Y');
        if($pemilih->count()>0){

            $user=$pemilih->first();
            if (Hash::check($password, $user->password)) {
                // Akun tanpa embedding wajah tidak akan bisa lolos verifikasi wajah.
                // Beri pesan yang jelas, jangan diteruskan ke step verifikasi (yang
                // hanya akan menghasilkan "wajah tidak cocok" yang menyesatkan).
                if (!$user->face_registered) {
                    return response()->json(['message' => 'Wajah Anda belum terdaftar di sistem. Silakan registrasi ulang wajah atau hubungi panitia.'], 422);
                }
                $login = DB::table('sessions')->where('pemilih_id', $user->id)->count();
                if ($login > 0)
                {
                    Auth::guard('pemilih')->logout();
                    return response()->json(['message' => 'Anda sudah login di perangkat lain.'], 422);
                }else{
                    $request->session()->put('id_enc_face_login', Formatter::encrypt0($user->id));
                    return response()->json(['step' => 'face', 'nama' => $user->nama], 200);
                }
            }
            
        
        }
        return response()->json(['message' => 'Username atau password tidak valid.'], 422);
    }
    public function verifikasi_otp(Request $request){
        $rules = [
            'kode_otp'=>'required|min:6|max:6',
        ];

        $messages = [
        ];

        request()->validate($rules,$messages);
        $id=Formatter::decrypt0($request->session()->get('id_enc_otp'));
        #Validation Logic
        $verificationCode   = VerificationOtp::where('pemilih_id', $id)->where('otp', $request->kode_otp)->first();

        $now = Carbon::now();
        if (!$verificationCode) {
            return response()->json([
                'message' =>'The given data was invalid.',
                'errors' =>[
                    'kode_otp'=>[
                        'Kode OTP not correct'
                    ]
                ],
            ],422);
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            return response()->json([
                'message' =>'The given data was invalid.',
                'errors' =>[
                    'kode_otp'=>[
                        'Kode OTP is has been expired'
                    ]
                ],
            ],422);
        }

        $user = Pemilih::whereId($id)->first();

        $login = DB::table('sessions')->where('pemilih_id', $user->id)->count();
        if ($login > 0)
        {
            return response()->json([
                'message' =>'The given data was invalid.',
                'errors' =>[
                    'kode_otp'=>[
                        'You are Logged in on other devices'
                    ]
                ],
            ],422);
        }else{
            if($user){
                // Expire The OTP
                // DB::transaction(function() use ($request,&$user,&$verificationCode,&$error) {
                    $verificationCode->update([
                        'expire_at' => Carbon::now()
                    ]);
        
                    Auth::guard('pemilih')->login($user);

                    // RememberMe::log_user_in($user);
                    // RememberMe::remember_me($user->id);

                    return response()->json([
                        'message' =>'Kode OTP berhasil diverifikasi',
                        'url'=>url('account/home'),
                    ],200);
                // });
            }
        }

        
    }


    public function kirimUlangOtp(Request $request){
        $id=Formatter::decrypt0($request->session()->get('id_enc_otp'));
        $user = Pemilih::whereId($id)->where('email_verifikasi','Y')->where('aktif','Y');
        if($user->count()>0){
            $user=$user->first();
            $otp_code=$this->generateOtp($id);
            $metaEmail = [
                'judul' => $otp_code->otp.' - Kode OTP '.Formatter::identity()['title'],
                'nama' => $user->nama,
                'otp_code' => $otp_code->otp,
                'id' => Formatter::encrypt0($user->id),
                'time' => Formatter::encrypt0(base64_encode($user->time_update_akun))
            ];
            
            try {
                Mail::to($user->email)->send(new VerifikasiOtpMail($metaEmail));
                return response()->json([
                    'message' =>'Success',
                ],200);
            } catch (Exception $e) {
                return response()->json([
                    'message' =>'error',
                ],500);
            }
        }else{
            return response()->json([
                'message' =>'error',
            ],500);
        }
        
        
    }
    public function generateOtp($id)
    {
        $user = Pemilih::where('id', $id)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationOtp::where('pemilih_id', $user->id)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationOtp::create([
            'pemilih_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(5)
        ]);
    }
    public function registrasi_store(Request $request){

            $rules = [
                'nama'       => 'required|min:3|max:50',
                'no_anggota' => [
                    'required',
                    'max:20',
                    new CheckPemilihTerdaftar($request->no_anggota),
                ],
                'telp'       => 'required|unique:pemilihs|min:5|max:20',
                'ktp'        => 'nullable|max:2048|mimes:jpeg,png,pdf',
                'email'      => 'required|email|unique:pemilihs|max:50',
                'file_photo' => 'required|max:2048|mimes:jpeg,png',
                'username'   => 'required|min:5|max:50|unique:pemilihs',
                'password'   => 'required|min:8|max:50',
            ];

            $messages = [
                'telp.required'      => 'No. WA is required',
                'username.required'  => 'Username wajib diisi',
                'username.min'       => 'Username minimal 5 karakter',
                'username.unique'    => 'Username sudah digunakan',
                'password.required'  => 'Password wajib diisi',
                'password.min'       => 'Password minimal 8 karakter',
            ];

            request()->validate($rules,$messages);


            $pemilih = new Pemilih;

            if(!empty($request->ktp)){
                $fileName = time().'.'.$request->ktp->extension();
                Storage::disk('ktp')->put($fileName, file_get_contents($request->ktp));
                $pemilih->ktp = $fileName;
            }
            
            if(!empty($request->file_photo)){
                $fileName = time().'.'.$request->file_photo->extension();
                Storage::disk('file_photo')->put($fileName, file_get_contents($request->file_photo));
                $pemilih->file_photo = $fileName;
            }

            $pemilih->nama = $request->nama;
            $pemilih->no_anggota = $request->no_anggota;
            $pemilih->email = $request->email;
            $pemilih->telp = $request->telp;
            $pemilih->username = $request->username;
            $pemilih->password = Hash::make($request->password);
            $pemilih->catatan_verifikasi = NULL;
            $pemilih->verifikasi = 'Y';

            $skipEmailVerif = !Formatter::identity()['email_verif'];
            if ($skipEmailVerif) {
                $pemilih->email_verifikasi = 'Y';
                $pemilih->aktif = 'Y';
            }

            try {
                if (!$skipEmailVerif) {
                    $metaEmail = [
                        'judul' => 'Verifikasi Email '.Formatter::identity()['title'],
                        'nama' => $request->nama,
                        'link_verifikasi' => url('email/verify/'.(Formatter::encrypt0($request->email)).'/'.base64_encode(Hash::make($request->email))),
                        'time' => Formatter::encrypt0(base64_encode(Carbon::now()))
                    ];
                    Mail::to($request->email)->send(new VerifikasiEmailMail($metaEmail));
                }

                $pemilih->save();
                if($pemilih){
                    // Daftarkan wajah ke Face Recognition API (5 foto → average embedding)
                    $faceImages = array_filter($request->input('face_images', []));
                    if (empty($faceImages) && !empty($request->face_image)) {
                        $faceImages = [$request->face_image]; // backward-compat
                    }
                    if (!empty($faceImages)) {
                        $faceOk = false;
                        try {
                            $faceService = new FaceRecognitionService();
                            $faceResult  = $faceService->register($pemilih->id, array_values($faceImages));
                            $faceOk = !empty($faceResult['success']);
                        } catch (Exception $e) {
                            Log::warning('[FaceAPI] Gagal register wajah pemilih '.$pemilih->id.': '.$e->getMessage());
                            $faceOk = false;
                        }

                        if (!$faceOk) {
                            // Batalkan registrasi supaya TIDAK ada akun tanpa wajah
                            // (yang nanti tak bisa login). Hapus file yang telanjur
                            // terupload + baris pemilih, agar user bisa mengulang.
                            if (!empty($pemilih->ktp)) { Storage::disk('ktp')->delete($pemilih->ktp); }
                            if (!empty($pemilih->file_photo)) { Storage::disk('file_photo')->delete($pemilih->file_photo); }
                            $pemilih->delete();

                            return response()->json([
                                'message' => 'Pendaftaran wajah gagal karena server sedang sibuk. Mohon ulangi registrasi beberapa saat lagi.',
                            ], 503);
                        }

                        $pemilih->face_registered    = true;
                        $pemilih->face_registered_at = Carbon::now();
                        $pemilih->save();
                    }

                    $request->session()->flash('success', 'Registrasi berhasil! Silahkan login menggunakan username dan password Anda.');

                    return response()->json([
                        'url' => url('login'),
                    ],200);
                }
            }catch (Exception $e) {
                return response()->json([
                    'message' =>$e->getMessage()
                ],500);
            }

    }

    public function update_akun(Request $request){

            $rules = [
                'password'=>'required|confirmed|min:6',
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];

            request()->validate($rules,$messages);

            $id=base64_decode(Formatter::decrypt0($request->param));
            $pemilih = Pemilih::find($id);

            $pemilih->password = bcrypt($request->password);
            $pemilih->save();

            return redirect()->intended('registrasi/respone?page=respone_update_akun&id='.Formatter::encrypt0($id));

    }

    public function kirim_link(Request $request){

            $rules = [
                'email'=>'required|email|exists:pemilihs|max:50',
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];

            request()->validate($rules,$messages);

            Pemilih::where('email',$request->email)->update([
                'time_update_akun'=> date('Y-m-d H:i:s')
            ]);

            $pemilih=Pemilih::where('email',$request->email)->first();

            $metaEmail = [
                'judul' => 'Update Akun '.Formatter::identity()['title'],
                'nama' => $pemilih->nama,
                'id' => Formatter::encrypt0($pemilih->id),
                'time' => Formatter::encrypt0(base64_encode($pemilih->time_update_akun))
            ];
            Mail::to($request->email)->send(new VerifikasiOtpMail($metaEmail));

            return redirect()->intended('registrasi/respone?page=lupa_password&id='.Formatter::encrypt0($pemilih->id).'&x='.Formatter::encrypt0(base64_encode($pemilih->time_update_akun)).'');

    }

    public function aktivasi($id){

             $id_user=Formatter::decrypt0($id);
            $pemilih=Pemilih::find($id_user);
            $pemilih->active = 'Y';
            $pemilih->save();

            return redirect()->intended('registrasi/respone?page=respone_aktivasi&id='.$id);

    }

    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect('login');
    }
}
