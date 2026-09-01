<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kandidat;
use App\Models\Pemilih;
use Illuminate\Support\Facades\Storage;
use Formatter;


class KandidatController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:kandidat view', ['only' => ['index','get_kandidat']]);
        $this->middleware('permission:kandidat calon-kandidat', ['only' => ['calon_kandidat','get_calon_kandidat']]);
        $this->middleware('permission:kandidat calon-kandidat-2', ['only' => ['calon_kandidat_2','get_calon_kandidat_2']]);
        $this->middleware('permission:kandidat hasil-kandidat', ['only' => ['hasil_kandidat','get_hasil_kandidat']]);
        $this->middleware('permission:kandidat create', ['only' => ['create','store']]);
        $this->middleware('permission:kandidat update', ['only' => ['update','edit']]);
        $this->middleware('permission:kandidat delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Data <span class="text-danger font-weight-bold">Kandidat</span>';
        return view('a.kandidat.main',$d);
    }

    public function get_kandidat(Request $request)
    {
       
        if ($request->ajax()) {
            $where="";
            
            $q = DB::table('kandidats')
            ->select('kandidats.id','kandidats.nama','kandidats.no_anggota','kandidats.file_photo','kandidats.no_urut','kandidats.calon_kandidat_status','kandidats.kandidat_status')
            ->selectRaw("(SELECT count(p2.id) FROM pemilihs AS p2 WHERE p2.calon_kandidat_2_id=kandidats.id) as total_suara")
            ->orderBy('kandidats.no_urut','asc');

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'total_suara',
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
                    $query->where('kandidats.nama','LIKE',"%{$search}%")
                         ->orWhere('kandidats.no_anggota', 'LIKE',"%{$search}%");
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
                    $btn .= '<a href="'.route('kandidat.edit',Formatter::encrypt0($dt->id)).'" title="Edit" class="btn-action edit"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                    <span class="btn-action btn-action-delete delete" title="Hapus" title="Hapus" data-id="'.Formatter::encrypt0($dt->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</span>';
                    $btn.='</div>';
                    $btn.='</div>';

                    if (!empty($dt->file_photo)){
                        $photo= '<img src="'.url('images/kandidat/'.$dt->file_photo).'" class="photo-table"></img>';
                    }else{
                        $photo= '<div class="photo-table"><i class="fa fa-user"></i></div>';
                    }
                    $calon_kandidat_status='';
                    $kandidat_status='';
                    if($dt->calon_kandidat_status=='Y'){
                        $calon_kandidat_status='<span class="badge badge-success">Ya</span>';
                    }
                    if($dt->kandidat_status=='Y'){
                        $kandidat_status='<span class="badge badge-success">Ya</span>';
                    }

                    $nestedData['DT_RowIndex'] = $i++;
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_urut'] = $dt->no_urut;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['action'] = $btn;
                    $nestedData['user_photo'] = $photo;
                    $nestedData['calon_kandidat_status'] = $calon_kandidat_status;
                    $nestedData['kandidat_status'] = $kandidat_status;
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
    public function calon_kandidat()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Data <span class="text-danger font-weight-bold">Calon Kandidat</span>';
        return view('a.kandidat.calon_kandidat',$d);
    }
    
    public function calon_kandidat_2()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Data <span class="text-danger font-weight-bold">Bakal Calon Kandidat 2</span>';
        return view('a.kandidat.calon_kandidat_2',$d);
    }
    
    public function hasil_kandidat()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Hasil Perolehan Suara <span class="text-danger font-weight-bold">Kandidat</span>';
        return view('a.kandidat.hasil_kandidat',$d);
    }
    


    public function get_calon_kandidat(Request $request)
    {
        $kabupaten=$request->kabupaten;
        $kecamatan=$request->kecamatan;
        $kelurahan=$request->kelurahan;
       
        if ($request->ajax()) {
            $where="";
            
            $q = DB::table('calon_kandidats')
            ->whereRaw("(SELECT count(p2.id) FROM pemilihs AS p2 WHERE p2.calon_kandidat_id=calon_kandidats.id)>0")
            ->select('calon_kandidats.id','calon_kandidats.nama','calon_kandidats.no_anggota')
            ->selectRaw("(SELECT count(p2.id) FROM pemilihs AS p2 WHERE p2.calon_kandidat_id=calon_kandidats.id) as total_suara")
            ->orderBy('calon_kandidats.nama','asc');

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'total_suara',
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
                    $query->where('calon_kandidats.nama','LIKE',"%{$search}%")
                         ->orWhere('calon_kandidats.no_anggota', 'LIKE',"%{$search}%");
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
                foreach ($lists->sortByDesc('total_suara') as $dt)
                {

                   
                    $nestedData['DT_RowIndex'] = $i++;
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['total_suara'] = $dt->total_suara;
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


    public function get_calon_kandidat_2(Request $request)
    {
       
        if ($request->ajax()) {
            $where="";
            
            $q = DB::table('kandidats')
            ->select('kandidats.id','kandidats.nama','kandidats.no_anggota','kandidats.no_urut')
            ->selectRaw("(SELECT count(p2.id) FROM pemilihs AS p2 WHERE p2.calon_kandidat_2_id=kandidats.id) as total_suara")
            ->where('calon_kandidat_status','Y')
            ->orderBy('kandidats.no_urut','asc');

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'total_suara',
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
                    $query->where('kandidats.nama','LIKE',"%{$search}%")
                         ->orWhere('kandidats.no_anggota', 'LIKE',"%{$search}%");
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
                foreach ($lists->sortByDesc('total_suara') as $dt)
                {

                   
                    $nestedData['DT_RowIndex'] = $dt->no_urut;
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['total_suara'] = $dt->total_suara;
                    $data[] = $nestedData;

                }
            }

            $totalPemilih=DB::table('pemilihs')->where('verifikasi','Y')->where('email_verifikasi','Y')->where('aktif','Y')->count();
            $totalTelahMemilih=DB::table('pemilihs')->where('verifikasi','Y')->where('email_verifikasi','Y')->whereNotNull('calon_kandidat_2_id')->count();
            $totalBelumMemilih=DB::table('pemilihs')->where('verifikasi','Y')->where('email_verifikasi','Y')->whereNull('calon_kandidat_2_id')->count();
            
            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,   
                "total_kandidat" => Formatter::pointSeparator($totalData),
                "total_pemilih" => Formatter::pointSeparator($totalPemilih),
                "total_telah_memilih" => Formatter::pointSeparator($totalTelahMemilih),
                "total_belum_memilih" => Formatter::pointSeparator($totalBelumMemilih),
            );
                
            echo json_encode($json_data); 
        }
        
    }
    
    /**
     * Hitung suara final dari kolom terenkripsi vote_enc.
     * Mengembalikan map [kandidat_id => jumlah_suara] hanya untuk suara sah (verifikasi_vote='Y').
     */
    private function tallyVotesTerenkripsi(): array
    {
        $ciphers = DB::table('pemilihs')
            ->whereNotNull('vote_enc')
            ->where('verifikasi_vote', 'Y')
            ->pluck('vote_enc');

        $counts = [];
        foreach ($ciphers as $cipher) {
            $kandidatId = Pemilih::decryptVote($cipher);
            if ($kandidatId !== null) {
                $counts[$kandidatId] = ($counts[$kandidatId] ?? 0) + 1;
            }
        }
        return $counts;
    }

    public function get_hasil_kandidat(Request $request)
    {
       
        if ($request->ajax()) {
            $where="";
            
            // Rekapitulasi suara dari kolom terenkripsi (vote_enc), didekripsi &
            // dihitung di layer aplikasi karena ciphertext tidak dapat di-GROUP BY di SQL.
            $tallySuara = $this->tallyVotesTerenkripsi();

            $q = DB::table('kandidats')
            ->select('kandidats.id','kandidats.nama','kandidats.no_anggota','kandidats.no_urut')
            ->where('kandidat_status','Y')
            ->orderBy('kandidats.no_urut','asc');

            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'no_anggota',
                3=> 'total_suara',
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
                    $query->where('kandidats.nama','LIKE',"%{$search}%")
                         ->orWhere('kandidats.no_anggota', 'LIKE',"%{$search}%");
                    })
                         ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
                         
                $totalFiltered =$q->count();

            }
            // Tempelkan jumlah suara hasil dekripsi ke tiap kandidat
            foreach ($lists as $dt) {
                $dt->total_suara = $tallySuara[$dt->id] ?? 0;
            }

            $data = array();
            if(!empty($lists))
            {
                $i=$start+1;
                foreach ($lists->sortByDesc('total_suara') as $dt)
                {


                    $nestedData['DT_RowIndex'] = $dt->no_urut;
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['no_anggota'] = $dt->no_anggota;
                    $nestedData['total_suara'] = $dt->total_suara;
                    $data[] = $nestedData;

                }
            }

            $totalPemilih=DB::table('pemilihs')->where('verifikasi','Y')->where('email_verifikasi','Y')->where('aktif','Y')->count();
            $belumMemilih = DB::table('pemilihs')->where('verifikasi','Y')->where('email_verifikasi','Y')->where('aktif','Y')->whereNull('pemilihs.vote_enc')->count();
            $sah = DB::table('pemilihs')->whereNotNull('pemilihs.vote_enc')->whereNotNull('pemilihs.verify_vote_by')->where('pemilihs.verifikasi_vote','Y')->count();
            $tidakSah = DB::table('pemilihs')->whereNotNull('pemilihs.vote_enc')->whereNotNull('pemilihs.verify_vote_by')->where('pemilihs.verifikasi_vote','N')->count();

            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,   
                "total_kandidat" => Formatter::pointSeparator($totalData),
                "total_pemilih" => Formatter::pointSeparator($totalPemilih),
                "total_belum_memilih" => Formatter::pointSeparator($belumMemilih),
                "total_sah" => Formatter::pointSeparator($sah),
                "total_tidak_sah" => Formatter::pointSeparator($tidakSah),
            );
                
            echo json_encode($json_data); 
        }
        
    }

    public function create()
    {
        $d['icon']='pe-7s-plus';
        $d['title']='Tambah Kandidat';
        $d['calon_kandidat_status']=array('N'=>'Bukan','Y'=>'Ya');
        $d['kandidat_status']=array('N'=>'Bukan','Y'=>'Ya');
        return view('a.kandidat.create',$d);
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
            'no_anggota'=>'required|unique:kandidats|max:50',
            'no_urut'=>'required|unique:kandidats|max:50',
            'nama'=>'required|unique:kandidats|max:50',
            'photo'=>'max:2048|mimes:jpeg,png',
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $users = new Kandidat;

        if(!empty($request->photo)){
            $fileName = time().'.'.$request->photo->extension();
            Storage::disk('kandidat')->put($fileName, file_get_contents($request->photo));
            $users->file_photo = $fileName;
        }

        $users->nama = $request->nama;
        $users->no_anggota = $request->no_anggota;
        $users->no_urut = $request->no_urut;
        $users->deskripsi = $request->deskripsi;
        $users->calon_kandidat_status = $request->calon_kandidat_status;
        $users->kandidat_status = $request->kandidat_status;



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
        $id=Formatter::decrypt0($id);
        $d['icon']='pe-7s-note';
        $d['title']='Edit Kandidat';
        $d['calon_kandidat_status']=array('N'=>'Bukan','Y'=>'Ya');
        $d['kandidat_status']=array('N'=>'Bukan','Y'=>'Ya');
        $d['q'] = Kandidat::where('id', $id)->first();
        return view('a.kandidat.edit',$d);
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
        $users = Kandidat::find($id);

        $rules = [
            'no_anggota'=>'required|max:50',
            'no_urut'=>'required|max:50',
            'nama'=>'required|max:50',
            'photo'=>'max:2048|mimes:jpeg,png',
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        if(!empty($request->photo)){
            $fileName = time().'.'.$request->photo->extension();
            Storage::disk('kandidat')->put($fileName, file_get_contents($request->photo));
            $users->file_photo = $fileName;
        }

        $users->nama = $request->nama;
        $users->no_anggota = $request->no_anggota;
        $users->no_urut = $request->no_urut;
        $users->deskripsi = $request->deskripsi;
        $users->calon_kandidat_status = $request->calon_kandidat_status;
        $users->kandidat_status = $request->kandidat_status;

        $users->save();
    }

    public function destroy(Request $request)
    {
        $users = Kandidat::find(Formatter::decrypt0($request->id));
        $users->delete();
    }
}
