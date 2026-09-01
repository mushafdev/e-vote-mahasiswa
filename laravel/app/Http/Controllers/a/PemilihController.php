<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pemilih;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Nullix\CryptoJsAes\CryptoJsAes;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiRegistrationMail;
use TechTailor\RPG\Facade\RPG;
use Exception;


class PemilihController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:pemilih view', ['only' => ['index','get_pemilih']]);
        $this->middleware('permission:pemilih vote-list', ['only' => ['vote_list','get_vote_list','vote_detail']]);
        $this->middleware('permission:pemilih vote-list-notifikasi', ['only' => ['vote_list_notifikasi','get_vote_list_notifikasi']]);
        $this->middleware('permission:pemilih vote-approve', ['only' => ['vote_approve']]);
        $this->middleware('permission:pemilih registrasi', ['only' => ['registration_list','get_registration_list']]);
        $this->middleware('permission:pemilih verifikasi', ['only' => ['update_verifikasi']]);
        $this->middleware('permission:pemilih detail', ['only' => ['detail']]);
        $this->middleware('permission:pemilih create', ['only' => ['create','store']]);
        $this->middleware('permission:pemilih update', ['only' => ['update','edit']]);
        $this->middleware('permission:pemilih delete', ['only' => ['destroy']]);
        $this->middleware('permission:pemilih kirim-auth-baru', ['only' => ['send_new_auth']]);
    }
    

    public function index()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Data <span class="text-danger font-weight-bold">Pemilih</span>';
        return view('a.pemilih.main',$d);
    }
    
    public function registration_list()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Notifikasi <span class="text-danger font-weight-bold">Pendaftar</span>';
        return view('a.pemilih.registration_list',$d);
    }
    
    public function vote_list_notifikasi()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Notifikasi <span class="text-danger font-weight-bold">Suara Masuk</span>';
        return view('a.pemilih.vote_list_notifikasi',$d);
    }

    public function vote_list()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='<span class="text-danger font-weight-bold">Suara Masuk</span>';
        return view('a.pemilih.vote_list',$d);
    }


    public function get_pemilih(Request $request)
    {

        $role=$request->session()->get('role');
        $kabupaten=$request->kabupaten;
        $kecamatan=$request->kecamatan;
        $kelurahan=$request->kelurahan;
       
        if ($request->ajax()) {
            $where="";
            if(!empty($kabupaten)){
                $where.=" AND pemilihs.kab_id='$kabupaten'";
            }
            if(!empty($kecamatan)){
                $where.=" AND pemilihs.kec_id='$kecamatan'";
            }
            if(!empty($kelurahan)){
                $where.=" AND pemilihs.kel_id='$kelurahan'";
            }
            
            $q = DB::table('pemilihs')
            ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
            ->whereRaw("email_verifikasi='Y' $where")
            ->select('pemilihs.id','pemilihs.nama','pemilihs.no_anggota','pemilihs.email','pemilihs.alamat','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan')
            ->orderBy('pemilihs.id','desc');

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'kabupaten',
                4=> 'kecamatan',
                5=> 'kelurahan',
                6=> 'alamat',
                7=> 'email',
                8=> 'telp',
                9=> 'action',
            );
    
            $totalData = $q->count();
                
            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            
            if($limit==-1){
                $limit=1000000000;
            }
            if(empty($request->input('search.value')))
            {            
                $lists = $q->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
            }
            else {
                $search = $request->input('search.value'); 

                $lists =  $q->where(function($query) use ($search) {
                    $query->where('pemilihs.nama','LIKE',"%{$search}%")
                         ->orWhere('pemilihs.no_anggota', 'LIKE',"%{$search}%");
                    })
                         ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
                         
                $totalFiltered =$q->count();

            }

            $data = array();
            if(!empty($lists))
            {
                $i=$start+1;
                foreach ($lists as $dt)
                {
                    
                    $btn='<div class="dropdown">
                    <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                    $btn .= '<a href="'.route('pemilih.detail',Formatter::encrypt0($dt->id)).'" title="Edit" class="btn-action btn-action-info detail"><i class="fa fa-eye" aria-hidden="true"></i> Detail</a>';
                    if($role=='a'){
                    $btn.='<span class="btn-action btn-action-warning new-auth" title="Kirim Auth Baru" data-id="'.Formatter::encrypt0($dt->id).'" data-nama="'.$dt->nama.'" data-email="'.$dt->email.'"><i class="fa fa-key" aria-hidden="true"></i> Kirim Auth Baru</span>';
                    }
                    $btn.='</div>';
                    $btn.='</div>';

                   
                    $nestedData['DT_RowIndex'] = $i++;
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['telp'] = $dt->telp;
                    $nestedData['email'] = $dt->email;
                    $nestedData['kabupaten'] = $dt->kabupaten;
                    $nestedData['alamat'] = $dt->alamat;
                    $nestedData['action'] = $btn;
                    $data[] = $nestedData;

                }
            }
            
            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,   
                "total_pemilih" => Formatter::pointSeparator($totalData),
            );
                
            echo json_encode($json_data); 
        }
        
    }
   
    public function get_registration_list(Request $request)
    {
       
        if ($request->ajax()) {

            $status_verifikasi=$request->status_verifikasi;
            $where="";
            if($status_verifikasi=='NP'){
                $where.=" AND pemilihs.email_verifikasi='N'";
            }
            if($status_verifikasi=='NA'){
                $where.=" AND (pemilihs.verify_by IS NULL OR pemilihs.verify_by = '' ) AND pemilihs.email_verifikasi='Y' ";
            }

            if($status_verifikasi=='Y'){
                $where.=" AND pemilihs.email_verifikasi='Y' AND pemilihs.verify_by IS NOT NULL AND pemilihs.verifikasi='Y' ";
            }
            
            if($status_verifikasi=='N'){
                $where.=" AND pemilihs.email_verifikasi='Y' AND pemilihs.verify_by IS NOT NULL AND pemilihs.verifikasi='N' ";
            }
            
            $q = DB::table('pemilihs')
            ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
            ->whereRaw("pemilihs.id<>'' $where")
            ->select('pemilihs.id','pemilihs.verifikasi','pemilihs.email_verifikasi','pemilihs.verify_by','pemilihs.nama','pemilihs.no_anggota','pemilihs.email','pemilihs.alamat','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','pemilihs.created_at','pemilihs.deleted_at')
            ->orderBy('pemilihs.verify_by','asc')
            ->orderBy('pemilihs.created_at','desc');
            // ->orderBy('pemilihs.verify_by','asc');

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'kabupaten',
                4=> 'kecamatan',
                5=> 'kelurahan',
                6=> 'alamat',
                7=> 'email',
                8=> 'telp',
                9=> 'status',
                10=> 'action',
            );
    
            $totalData = $q->count();
                
            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            
            if($limit==-1){
                $limit=1000000000;
            }
            if(empty($request->input('search.value')))
            {            
                $lists = $q->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
            }
            else {
                $search = $request->input('search.value'); 

                $lists =  $q->where(function($query) use ($search) {
                           $query->where('pemilihs.nama','LIKE',"%{$search}%")
                                ->orWhere('pemilihs.no_anggota', 'LIKE',"%{$search}%");
                           })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                                
                $totalFiltered =$q->count();
                                
                

            }

            $data = array();
            if(!empty($lists))
            {
                $i=$start+1;
                foreach ($lists as $dt)
                {
                    $btn='';
                    $btn='<div class="dropdown">
                    <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                    
                    if($dt->email_verifikasi=='Y'){
                        $btn .= '<a href="'.route('pemilih.detail',Formatter::encrypt0($dt->id)).'" title="Edit" class="btn-action btn-action-info detail"><i class="fa fa-eye" aria-hidden="true"></i> Detail</a>';
                    }else{
                        $btn.='<span class="btn-action btn-action-delete delete" title="Hapus" data-id="'.Formatter::encrypt0($dt->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Batal</span>';
                    }
                    $btn.='</div>';
                    $btn.='</div>';

                    $status='<span class="badge badge-secondary">Belum verifikasi Email</span>';    
                    if($dt->email_verifikasi=='Y'){
                        $status='<span class="badge badge-warning">Belum diverifikasi Admin</span>';
                        if(!empty($dt->verify_by)){
                            if($dt->verifikasi=='Y'){
                                $status='<span class="badge badge-success">Diterima</span>';
                            }else{
                                if(!empty($dt->deleted_at)){
                                    $status='<span class="badge badge-danger">Batal</span>';
                                }else{
                                    $status='<span class="badge badge-danger">Ditolak</span>';
                                }
                            }
                        }
                    }
                    
                   
                    $nestedData['DT_RowIndex'] = $i++;
                    $nestedData['created_at'] = Formatter::datetime($dt->created_at);
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['telp'] = $dt->telp;
                    $nestedData['email'] = $dt->email;
                    $nestedData['kabupaten'] = $dt->kabupaten;
                    $nestedData['alamat'] = $dt->alamat;
                    $nestedData['status'] = $status;
                    $nestedData['action'] = $btn;
                    $data[] = $nestedData;

                }
            }
            
            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,   
                "total_pemilih" => Formatter::pointSeparator($totalData),
            );
                
            echo json_encode($json_data); 
        }
        
    }
    
    public function get_vote_list_notifikasi(Request $request)
    {
       
        if ($request->ajax()) {

            $status_verifikasi=$request->status_verifikasi;
            $where="";
            
            $q = DB::table('pemilihs')
            ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
            ->whereRaw("pemilihs.vote_enc is NOT NULL AND pemilihs.verify_vote_by IS NULL")
            ->select('pemilihs.id','pemilihs.verifikasi_vote','pemilihs.verify_vote_by','pemilihs.nama','pemilihs.no_anggota','pemilihs.email','pemilihs.alamat','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','pemilihs.time_vote_final','pemilihs.deleted_at')
            ->orderBy('pemilihs.verify_vote_by','asc')
            ->orderBy('pemilihs.time_vote_final','asc');
            // ->orderBy('pemilihs.verify_by','asc');

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'kabupaten',
                4=> 'kecamatan',
                5=> 'kelurahan',
                6=> 'alamat',
                7=> 'email',
                8=> 'telp',
                9=> 'status',
                10=> 'action',
            );
    
            $totalData = $q->count();
                
            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            
            if($limit==-1){
                $limit=1000000000;
            }
            if(empty($request->input('search.value')))
            {            
                $lists = $q->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
            }
            else {
                $search = $request->input('search.value'); 

                $lists =  $q->where(function($query) use ($search) {
                           $query->where('pemilihs.nama','LIKE',"%{$search}%")
                                ->orWhere('pemilihs.no_anggota', 'LIKE',"%{$search}%");
                           })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                                
                $totalFiltered =$q->count();
                                
                

            }

            $data = array();
            if(!empty($lists))
            {
                $i=$start+1;
                foreach ($lists as $dt)
                {
                    $btn='';
                    $btn='<div class="dropdown">
                    <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                    
                    $btn .= '<a href="'.route('pemilih.vote_detail',Formatter::encrypt0($dt->id)).'?page=vote-list-notifikasi" title="Edit" class="btn-action btn-action-info detail"><i class="fa fa-eye" aria-hidden="true"></i> Detail</a>';
                    
                    $btn.='</div>';
                    $btn.='</div>';

                    if($dt->verifikasi_vote=='Y'){
                        $status='<span class="badge badge-success">Sah</span>';
                    }elseif($dt->verifikasi_vote=='N'){
                        $status='<span class="badge badge-danger">Tidak Sah</span>';
                    }else{
                        $status='<span class="badge badge-secondary">Belum Verifikasi</span>';
                    }
                    
                   
                    $nestedData['DT_RowIndex'] = $i++;
                    $nestedData['created_at'] = Formatter::datetime($dt->time_vote_final);
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['telp'] = $dt->telp;
                    $nestedData['email'] = $dt->email;
                    $nestedData['kabupaten'] = $dt->kabupaten;
                    $nestedData['alamat'] = $dt->alamat;
                    $nestedData['status'] = $status;
                    $nestedData['action'] = $btn;
                    $data[] = $nestedData;

                }
            }
            
            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,   
                "total_notifikasi" => Formatter::pointSeparator($totalData),
            );
                
            echo json_encode($json_data); 
        }
        
    }

    public function get_vote_list(Request $request)
    {
       
        if ($request->ajax()) {

            $where="";
            
            $q = DB::table('pemilihs')
            ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
            ->whereRaw("pemilihs.vote_enc IS NOT NULL $where")
            ->select('pemilihs.id','pemilihs.verifikasi','pemilihs.verifikasi_vote','pemilihs.verify_vote_by','pemilihs.nama','pemilihs.no_anggota','pemilihs.email','pemilihs.alamat','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','pemilihs.created_at','pemilihs.time_vote_final')
            ->orderBy('pemilihs.verify_vote_by','asc')
            ->orderBy('pemilihs.verifikasi_vote','desc')
            ->orderBy('pemilihs.time_vote_final','asc');
            // ->orderBy('pemilihs.verify_by','asc');

            $belumVerifikasi = DB::table('pemilihs')->whereRaw("pemilihs.vote_enc IS NOT NULL AND (pemilihs.verifikasi_vote IS NULL OR pemilihs.verifikasi_vote='')")->count();
            $sah = DB::table('pemilihs')->whereRaw("pemilihs.vote_enc IS NOT NULL AND pemilihs.verifikasi_vote='Y'")->count();
            $tidakSah = DB::table('pemilihs')->whereRaw("pemilihs.vote_enc IS NOT NULL AND pemilihs.verifikasi_vote='N'")->count();

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'kabupaten',
                4=> 'kecamatan',
                5=> 'kelurahan',
                6=> 'alamat',
                7=> 'email',
                8=> 'telp',
                9=> 'status',
                10=> 'action',
            );
    
            $totalData = $q->count();
                
            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            
            if($limit==-1){
                $limit=1000000000;
            }
            if(empty($request->input('search.value')))
            {            
                $lists = $q->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
            }
            else {
                $search = $request->input('search.value'); 

                $lists =  $q->where(function($query) use ($search) {
                           $query->where('pemilihs.nama','LIKE',"%{$search}%")
                                ->orWhere('pemilihs.no_anggota', 'LIKE',"%{$search}%");
                           })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                                
                $totalFiltered =$q->count();
                                
                

            }

            $data = array();
            if(!empty($lists))
            {
                $i=$start+1;
                foreach ($lists as $dt)
                {
                    $btn='';
                    $btn='<div class="dropdown">
                    <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                    
                    $btn .= '<a href="'.route('pemilih.vote_detail',Formatter::encrypt0($dt->id)).'?page=vote-list" title="Edit" class="btn-action btn-action-info detail"><i class="fa fa-eye" aria-hidden="true"></i> Detail</a>';
                    $btn.='</div>';
                    $btn.='</div>';

                    if($dt->verifikasi_vote=='Y'){
                        $status='<span class="badge badge-success">Sah</span>';
                    }elseif($dt->verifikasi_vote=='N'){
                        $status='<span class="badge badge-danger">Tidak Sah</span>';
                    }else{
                        $status='<span class="badge badge-secondary">Belum Verifikasi</span>';
                    }
                    
                   
                    $nestedData['DT_RowIndex'] = $i++;
                    $nestedData['created_at'] = Formatter::datetime($dt->time_vote_final);
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['telp'] = $dt->telp;
                    $nestedData['email'] = $dt->email;
                    $nestedData['kabupaten'] = $dt->kabupaten;
                    $nestedData['alamat'] = $dt->alamat;
                    $nestedData['status'] = $status;
                    $nestedData['action'] = $btn;
                    $data[] = $nestedData;

                }
            }
            
            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,   
                "total_suara" => Formatter::pointSeparator($totalData),
                "total_belum_verifikasi" => Formatter::pointSeparator($belumVerifikasi),
                "total_sah" => Formatter::pointSeparator($sah),
                "total_tidak_sah" => Formatter::pointSeparator($tidakSah),
            );
                
            echo json_encode($json_data); 
        }
        
    }

    public function create()
    {
        $d['icon']='pe-7s-plus';
        $d['title']='Tambah <span class="text-danger font-weight-bold">Pemilih</span>';
        $d['jenis_kelamin']=array('L'=>'Laki-laki','P'=>'Perempuan');
        $d['kawin']=array('B'=>'Belum','S'=>'Sudah','P'=>'Pisah');
        return view('a.pemilih.create',$d);
    }
    
    public function edit($id)
    {
        $id=Formatter::decrypt0($id);
        $d['icon']='pe-7s-note';
        $d['title']='Edit <span class="text-danger font-weight-bold">Pemilih</span>';
        $d['jenis_kelamin']=array('L'=>'Laki-laki','P'=>'Perempuan');
        $d['kawin']=array('B'=>'Belum','S'=>'Sudah','P'=>'Pisah');
        $d['q'] =DB::table('pemilihs')
        ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
        ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
        ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
        ->where('pemilihs.id', $id)
        ->select('pemilihs.*','pemilihs.nama','pemilihs.no_anggota','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan')
        ->first();
        return view('a.pemilih.edit',$d);
    }
    
    public function detail($id)
    {
        $id=Formatter::decrypt0($id);
        $d['icon']='pe-7s-look';
        $d['title']='Detail <span class="text-danger font-weight-bold">Pemilih</span>';
        $d['jenis_kelamin']=array('L'=>'Laki-laki','P'=>'Perempuan');
        $d['kawin']=array('B'=>'Belum','S'=>'Sudah','P'=>'Pisah');
        $d['q'] =DB::table('pemilihs')
        ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
        ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
        ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
        ->leftjoin('users','users.id','=','pemilihs.verify_by')
        ->where('pemilihs.id', $id)
        ->select('pemilihs.*','pemilihs.nama','pemilihs.no_anggota','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','users.nama as diverifikasi_oleh')
        ->first();
        return view('a.pemilih.detail',$d);
    }
    
    public function vote_detail($id)
    {
        $id=Formatter::decrypt0($id);
        $d['icon']='pe-7s-look';
        $d['title']='Detail <span class="text-danger font-weight-bold">Suara Masuk</span>';
        $d['jenis_kelamin']=array('L'=>'Laki-laki','P'=>'Perempuan');
        $d['kawin']=array('B'=>'Belum','S'=>'Sudah','P'=>'Pisah');
        $q = DB::table('pemilihs')
        ->leftjoin('users','users.id','=','pemilihs.verify_vote_by')
        ->where('pemilihs.id', $id)
        ->select('pemilihs.*','users.nama as diverifikasi_oleh')
        ->first();

        // Pilihan suara tersimpan terenkripsi (vote_enc). Didekripsi di sini agar
        // admin yang berwenang tetap dapat melihat kandidat yang dipilih.
        if ($q) {
            $q->nama_kandidat = null;
            $q->no_urut = null;
            $q->no_anggota_kandidat = null;
            $q->foto_kandidat = null;

            $kandidatId = Pemilih::decryptVote($q->vote_enc ?? null);
            if ($kandidatId !== null) {
                $kandidat = DB::table('kandidats')->where('id', $kandidatId)->first();
                if ($kandidat) {
                    $q->nama_kandidat       = $kandidat->nama;
                    $q->no_urut             = $kandidat->no_urut;
                    $q->no_anggota_kandidat = $kandidat->no_anggota;
                    $q->foto_kandidat       = $kandidat->file_photo;
                }
            }
        }
        $d['q'] = $q;
        return view('a.pemilih.vote_detail',$d);
    }

    public function store(Request $request)
    {
        $rules = [
            'no_anggota'=>'required|unique:pemilihs|max:20',
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $pemilih = new Pemilih;
        $pemilih->nama = $request->nama;
        $pemilih->no_anggota = $request->no_anggota;
        $pemilih->nkk = $request->nkk;
        $pemilih->jenis_kelamin = $request->jenis_kelamin;
        $pemilih->kawin = $request->kawin;
        $pemilih->telp = $request->telp;
        $pemilih->email = $request->email;
        $pemilih->alamat = $request->alamat;
        $pemilih->rw = $request->rw;
        $pemilih->rt = $request->rt;
        $pemilih->tempat_lahir = $request->tempat_lahir;
        $pemilih->tgl_lahir = $request->tgl_lahir;
        
        $pemilih->kab_id = $request->kab_id;
        $pemilih->kel_id = $request->kel_id;
        $pemilih->kec_id = $request->kec_id;
        $pemilih->tps = $request->tps;
        $pemilih->status = 'aktif';
        $pemilih->tps = '-';
        $pemilih->ektp = '-';
        $pemilih->difabel = '-';
        $pemilih->status_tps_label = 'Reguler';
        $pemilih->inserted_by =  $request->session()->get('id');

        $pemilih->save();
    }
    
    public function update(Request $request)
    {
        $id=Formatter::decrypt0($request->id);
        $pemilih = Pemilih::find($id);
        $rules = [
            'no_anggota'=>'required|max:20|unique:pemilihs,no_anggota,'.$pemilih->id,
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $pemilih->nama = $request->nama;
        $pemilih->no_anggota = $request->no_anggota;
        $pemilih->nkk = $request->nkk;
        $pemilih->jenis_kelamin = $request->jenis_kelamin;
        $pemilih->kawin = $request->kawin;
        $pemilih->telp = $request->telp;
        $pemilih->email = $request->email;
        $pemilih->alamat = $request->alamat;
        $pemilih->rw = $request->rw;
        $pemilih->rt = $request->rt;
        $pemilih->tempat_lahir = $request->tempat_lahir;
        $pemilih->tgl_lahir = $request->tgl_lahir;
        
        $pemilih->kab_id = $request->kab_id;
        $pemilih->kel_id = $request->kel_id;
        $pemilih->kec_id = $request->kec_id;
        $pemilih->tps = $request->tps;
        $pemilih->status = 'aktif';
        $pemilih->tps = '-';
        $pemilih->ektp = '-';
        $pemilih->difabel = '-';
        $pemilih->status_tps_label = 'Reguler';
        $pemilih->inserted_by =  $request->session()->get('id');

        $pemilih->save();
    }
    
    public function update_verifikasi(Request $request)
    {
            $id=Formatter::decrypt0($request->id);
            $pemilih = Pemilih::find($id);
            $role=$request->session()->get('role');
            if($role=='a'){
                $rules = [
                    'verifikasi'=>'required|max:3',
                    'catatan_verifikasi'=>'required|max:300',
                ];
    
                $messages = [
                    // 'username.required' => 'Username is empty',
                ];
    
                request()->validate($rules,$messages);
                $password_test='';
                $email=$pemilih->email;
                if($request->verifikasi=='Y'){
                    $password_test=RPG::Generate('luds', 10, 0, 0);
                    $pemilih->username=Str::random(6);
                    $pemilih->password=Hash::make($password_test);
                    $pemilih->aktif='Y';
                    $status='diterima';
                    $link=url('/');
                    
                }else{
                    $pemilih->email=Str::random(5).'-'.$email;
                    $pemilih->no_anggota=Str::random(5).'-'.$pemilih->no_anggota;
                    $pemilih->telp=Str::random(5).'-'.$pemilih->telp;
                    $status='ditolak';
                    $link=url('registrasi');
                }
    
            
    
                $pemilih->verifikasi = $request->verifikasi;
                $pemilih->catatan_verifikasi = $request->catatan_verifikasi;
                $pemilih->verify_by =  $request->session()->get('id');
    
                $metaEmail = [
                    'judul' => 'Verifikasi Pendaftaran '.$status,
                    'status' => $status,
                    'catatan_verifikasi' => $request->catatan_verifikasi,
                    'nama' => $pemilih->nama,
                    'username' => $pemilih->username,
                    'password' => $password_test,
                    'link_verifikasi' => $link,
                    'time' => Formatter::encrypt0(base64_encode($pemilih->time_update_akun))
                ];
                try {
                    Mail::to($email)->send(new VerifikasiRegistrationMail($metaEmail));
                    $pemilih->save();
                }catch (Exception $e) {
                    return response()->json([
                        'message' =>$e->getMessage()
                    ],500);
                }
            }else if($role=='p'){
                $rules = [
                    'catatan_panlih'=>'required|max:300',
                ];
    
                $messages = [
                    // 'username.required' => 'Username is empty',
                ];
    
                request()->validate($rules,$messages);
                
                
                $pemilih->catatan_panlih = $request->catatan_panlih;
                $pemilih->updated_by =  $request->session()->get('id');
                $pemilih->save();
            }
    }
    
    public function vote_approve(Request $request)
    {
            $rules = [
                'id'=>'required',
                'verifikasi_vote'=>'required|max:3',
            ];

            $messages = [
                // 'username.required' => 'Username is empty',
            ];

            request()->validate($rules,$messages);
            try {
                $id=Formatter::decrypt0($request->id);
                $pemilih = Pemilih::find($id);
                $pemilih->verifikasi_vote = $request->verifikasi_vote;
                $pemilih->verify_vote_by =  $request->session()->get('id');
                $pemilih->verify_vote_time =  date('Y-m-d H:i:s');
                $pemilih->save();
            }catch (Exception $e) {
                return response()->json([
                    'message' =>$e->getMessage()
                ],500);
            }
        
    }

    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function create_update_reject($value,$column){
        $row=DB::table('pemilihs')->whereRaw("SUBSTRING_INDEX($column, '-', 1)='$value' AND verifikasi='N'")->count();
        if($row>0){
            $max_id=DB::table('pemilihs')->selectRaw('MAX(RIGHT('.$column.',2)) max_id')->whereRaw("SUBSTRING_INDEX($column, '-', 1)='$value' AND verifikasi='N'")->first();
            $result=$value.'-C'.str_pad(((int)$max_id->max_id+1), 2, '0', STR_PAD_LEFT);
        }else {
            $result=$value.'-C'.'01';
        }
        return $result;
    }

    public function destroy(Request $request)
    {
        $pemilih = Pemilih::find(Formatter::decrypt0($request->id));
        $pemilih->email=Str::random(5).'-'.$pemilih->email;
        $pemilih->no_anggota=Str::random(5).'-'.$pemilih->no_anggota;
        $pemilih->telp=Str::random(5).'-'.$pemilih->telp;
        $pemilih->verifikasi = 'N';
        $pemilih->email_verifikasi = 'Y';
        $pemilih->catatan_verifikasi = 'Batal registrasi';
        $pemilih->deleted_at =  date('Y-m-d H:i:s');
        $pemilih->deleted_by =  $request->session()->get('id');
        $pemilih->verify_by =  $request->session()->get('id');
        $pemilih->save();
    }
    
    public function send_new_auth(Request $request)
    {
        $pemilih = Pemilih::find(Formatter::decrypt0($request->id));

        $password_test=RPG::Generate('luds', 10, 0, 0);
        $pemilih->username=Str::random(6);
        $pemilih->password=Hash::make($password_test);

        $metaEmail = [
            'judul' => 'Update Akun E-Vote '.date('Y-m-d H:i:s'),
            'status' => 'diterima',
            'catatan_verifikasi' => 'Silahkan login dengan akun baru anda',
            'nama' => $pemilih->nama,
            'username' => $pemilih->username,
            'password' => $password_test,
            'link_verifikasi' => url('/login'),
            'time' => Formatter::encrypt0(base64_encode($pemilih->time_update_akun))
        ];
        try {
            Mail::to($pemilih->email)->send(new VerifikasiRegistrationMail($metaEmail));
            $pemilih->save();
        }catch (Exception $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }
}
