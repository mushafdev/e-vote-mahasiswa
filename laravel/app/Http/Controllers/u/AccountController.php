<?php

namespace App\Http\Controllers\u;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilih;
use App\Models\Kandidat;
use App\Mail\SendEmailBuktiBalon;
use Formatter;
use Illuminate\Support\Facades\DB;
use Image;
use File;
use Exception;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Services\FaceRecognitionService;

class AccountController extends Controller
{
    public function index(Request $request){
        $request->session()->put('id_enc_otp','');
        $d['icon']='pe-7s-user';
        $d['title']=auth()->guard('pemilih')->user()->nama;
        return view('u.dashboard',$d);
    }
    public function pilih_calon_kandidat(Request $request){
        $d['icon']='pe-7s-user';
        $d['title']='Pemilihan Calon Kandidat';
        $id=auth()->guard('pemilih')->user()->id;
        $d['pemilih']=Pemilih::find($id);
        return view('u.pilih_calon_kandidat',$d);
    }
    
    public function pilih_calon_kandidat_2(Request $request){

        $now = Carbon::now();

        $start = Carbon::parse('2024-07-16 00:00:00');
        $end = Carbon::parse('2024-07-20 16:00:00');
        $check=$now->between($start, $end);
        $d['check']=$check;
        $d['icon']='pe-7s-user';
        $d['title']='Pemilihan Calon Kandidat Tahap 2';
        $id=auth()->guard('pemilih')->user()->id;
        $d['pemilih']=Pemilih::find($id);
        $d['calon_kandidats']=Kandidat::where('calon_kandidat_status','Y')->orderBy('no_urut','asc')->get();
        return view('u.pilih_calon_kandidat_2',$d);
    }
    
    public function pilih_calon_kandidat_3(Request $request){

        $now = Carbon::now();

        $start = Carbon::parse('2024-08-15 10:30:00');
        $end = Carbon::parse('2027-08-16 14:00:00');
        $check=$now->between($start, $end);
        $d['check']=$check;
        $d['icon']='pe-7s-user';
        $d['title']='Pemilihan Ketua '.Formatter::identity()['organization_name'];
        $id=auth()->guard('pemilih')->user()->id;
        $d['pemilih']=Pemilih::find($id);
        $d['kandidats']=Kandidat::where('kandidat_status','Y')->orderBy('no_urut','asc')->get();
        return view('u.pilih_calon_kandidat_3',$d);
    }
    public function pilih_calon_kandidat_3_detail($kandidat){

        $d['icon']='pe-7s-user';
        $d['title']='Detail Kandidat';
        $id=auth()->guard('pemilih')->user()->id;
        $d['pemilih']=Pemilih::find($id);
        $d['kandidats']=Kandidat::find(Formatter::decrypt0($kandidat));
        return view('u.pilih_calon_kandidat_3_detail',$d);
    }

    public function search_kandidat(Request $request)
    {
        $rules = [
            'keyword'=>'required|min:3|max:50',
        ];

        $messages = [
        ];

        request()->validate($rules,$messages);

        $keyword=addslashes($request->keyword);
        $q = DB::table('calon_kandidats')->whereRaw("(nama like '%".$keyword."%') OR (no_anggota like '%".$keyword."%') ")
        // ->where('status_aktif','Aktif')
        ->select('id','nama','no_anggota')->orderBy('nama')->get();
        $total=$q->count();
        if($total>0){
            $total_show=$total.' data ditemukan';
        }else{
            $total_show='<span class="text-danger">data tidak ditemukan</span>';
        }

        $table='';
        $table.='<div class="table-responsive">';
        $table.='<table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_kandidat">';
        $table.='<thead class="thead-light">';
        $table.='<tr>';
        $table.='<th scope="col" class="text-center">No</th>';
        $table.='<th scope="col">Nama</th>';
        $table.='<th scope="col">No. Anggota</th>';
        $table.='<th scope="col" class="text-center span2 datatable-nosort">Aksi</th>';
        $table.='</tr>';
        $table.='</thead>';
        $table.='<tbody>';
        $i=1;
        if($q->count()>0){
            foreach($q as $dt){

                $table.='<tr data-id="' . Formatter::encrypt0($dt->id) . '">';
                $table.='<td scope="col" class="text-center">'.$i++.'</td>';
                $table.='<td scope="col">'.$dt->nama.'</td>';
                $table.='<td scope="col">'.substr($dt->no_anggota, 0, 5).'</td>';
                $table.='<td scope="col" class="text-center"><a href="#" class=" btn-action btn-action-info detail btn-sm" title="Update" data-toggle="modal" data-target="#modal-update"><i class="fa fa-check" aria-hidden="true"></i></a></td>';
                $table.='</tr>';
    
            }
        }else{
            $table.='<tr>';
            $table.='<td scope="col" class="text-center text-danger" colspan="12">Data tidak ditemukan</td>';
            $table.='</tr>';
        }
        
        $table.='</tbody>';
        $table.='</table>';
        $table.='</div>';
        return response()->json([
            'q' => $q,
            'total' => $total,
            'total_show' => $total_show,
            'table' => $table,
        ]);
    }
    
    public function get_detail_kandidat(Request $request)
    {
        $id=Formatter::decrypt0($request->id);
        $q = DB::table('calon_kandidats')->whereRaw("id='$id'")
        ->select('id','nama','no_anggota')
        ->first();
       
        return response()->json([
            'id' => Formatter::encrypt0($id),
            'q' => $q,
        ]);
    }
    
    public function review_kandidat(Request $request)
    {
        $id=Formatter::decrypt0($request->id);
        $q = DB::table('kandidats')->whereRaw("id='$id'")
        ->select('id','nama','no_anggota','file_photo','no_urut')
        ->first();
       
        return response()->json([
            'id' => Formatter::encrypt0($id),
            'photo' => url('images/kandidat/'.$q->file_photo),
            'q' => $q,
        ]);
    }

    public function pilih_kandidat_update(Request $request)
    {

        $rules = [
            'id'=>'required',
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $id_kandidat=Formatter::decrypt0($request->id);
        $id=auth()->guard('pemilih')->user()->id;
        $pemilih = Pemilih::find($id);

        // Verifikasi wajah sebelum voting jika pemilih sudah terdaftar
        if ($pemilih->face_registered) {
            if (empty($request->face_image)) {
                return response()->json(['message' => 'Verifikasi wajah diperlukan untuk melanjutkan voting'], 422);
            }
            $faceService = new FaceRecognitionService();
            $faceResult  = $faceService->verify($pemilih->id, $request->face_image);
            if (empty($faceResult['match'])) {
                $confidence = $faceResult['confidence'] ?? 0;
                return response()->json([
                    'message' => "Verifikasi wajah gagal (confidence: {$confidence}%). Pastikan wajah Anda terlihat jelas.",
                ], 422);
            }
        }

        if(empty($pemilih->calon_kandidat_id)){
            try {

                $balon=DB::table('calon_kandidats')->where('id',$id_kandidat)->first();
                $metaEmail = [
                    'judul' => 'Tanda Telah Memilih Balon Ketua IAI '.Formatter::identity()['title'],
                    'nama' => $pemilih->nama,
                    'no_anggota' => $pemilih->no_anggota,
                    'waktu' => date('d/m/Y H:i:s'),
                    'balon' => $balon->nama,
                    'no_anggota_balon' => $balon->no_anggota,
                ];
                Mail::to($pemilih->email)->send(new SendEmailBuktiBalon($metaEmail));

                $pemilih->calon_kandidat_id = $id_kandidat;
                $pemilih->save();
            }catch (Exception $e) {
                return response()->json([
                    'message' =>$e->getMessage()
                ],500);
            }
        }else{
            return response()->json([
                'status' => 500,
                'message' => 'Data sudah dipilih',
            ]);
        }

        
    }
    
    public function pilih_kandidat_2_update(Request $request)
    {

        $rules = [
            'id'=>'required',
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $id_kandidat=Formatter::decrypt0($request->id);
        $id=auth()->guard('pemilih')->user()->id;
        $pemilih = Pemilih::find($id);
        if(empty($pemilih->calon_kandidat_2_id)){
            try {
                
                $balon=Kandidat::where('id',$id_kandidat)->first();
                $metaEmail = [
                    'judul' => 'Tanda Telah Memilih Balon Ketua IAI Tahap 2',
                    'nama' => $pemilih->nama,
                    'no_anggota' => $pemilih->no_anggota,
                    'waktu' => date('d/m/Y H:i:s'),
                    'no_urut_balon' => $balon->no_urut,
                    'balon' => $balon->nama,
                    'no_anggota_balon' => $balon->no_anggota,
                ];
                Mail::to($pemilih->email)->send(new SendEmailBuktiBalon($metaEmail));

                $pemilih->time_vote_2 = date('Y-m-d H:i:s');
                $pemilih->calon_kandidat_2_id = $id_kandidat;
                $pemilih->save();
            }catch (Exception $e) {
                return response()->json([
                    'message' =>$e->getMessage()
                ],500);
            }
        }else{
            return response()->json([
                'status' => 500,
                'message' => 'Data sudah dipilih',
            ]);
        }

        
    }
    
    public function pilih_kandidat_3_update(Request $request)
    {

        request()->validate(['id' => 'required']);

        $id_kandidat = Formatter::decrypt0($request->id);
        $id = auth()->guard('pemilih')->user()->id;
        $pemilih = Pemilih::find($id);

        if ($pemilih->face_registered) {
            if (empty($request->face_image)) {
                return response()->json(['message' => 'Verifikasi wajah diperlukan untuk melanjutkan voting'], 422);
            }
            $faceService = new FaceRecognitionService();
            $faceResult  = $faceService->verify($pemilih->id, $request->face_image);
            if (empty($faceResult['match'])) {
                $confidence = $faceResult['confidence'] ?? 0;
                return response()->json(['message' => "Verifikasi wajah gagal (confidence: {$confidence}%). Pastikan wajah Anda terlihat jelas."], 422);
            }
        }

        if (empty($pemilih->vote_enc)) {
            try {
                $pemilih->time_vote_final = date('Y-m-d H:i:s');
                // Pilihan suara disimpan terenkripsi AES-256 (at rest);
                // kandidat_id sengaja dibiarkan NULL agar pilihan tidak terbaca
                // langsung dari database. Kehadiran vote_enc = penanda sudah memilih.
                $pemilih->vote_enc = Pemilih::encryptVote($id_kandidat);
                $pemilih->verifikasi_vote = 'Y';
                $pemilih->save();
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['status' => 500, 'message' => 'Data sudah dipilih']);
        }

        
    }

    public function view(Request $request)
    {
        $id=$request->session()->get('id');
        $d['icon']='pe-7s-user';
        $d['title']='Account';
        $d['q'] = Pemilih::where('id', $id)->first();
        return view('u.account.edit',$d);
    }

}
