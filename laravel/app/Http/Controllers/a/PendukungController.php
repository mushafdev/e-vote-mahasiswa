<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pemilih;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Nullix\CryptoJsAes\CryptoJsAes;


class PendukungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        $d['icon']='pe-7s-id';
        $d['title']='Data <span class="text-danger font-weight-bold">Pendukung</span>';
        return view('a.pendukung.main',$d);
    }

    public function invite_pendukung(Request $request)
    {
        $invited_id=$request->session()->get('id');
        $d['icon']='pe-7s-plus';
        $d['title']='Invite <span class="text-danger font-weight-bold">Pendukung</span>';
        $d['last_invited']=DB::table('pemilihs')->whereRaw("invited_by='$invited_id' AND pemilihs.pendukung='Y'")
        ->leftjoin('tims','tims.id','=','pemilihs.tim_id')
        ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
        ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
        ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
        ->select('pemilihs.id','pemilihs.nama','pemilihs.nik','pemilihs.kategori_pendukung','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','tims.nama as referensi','pemilihs.tps')
        ->orderBy('time_invite','desc')->limit(10)->get();
        return view('a.pendukung.invite_pendukung',$d);
    }

    public function search_pendukung(Request $request)
    {
        $keyword=addslashes($request->keyword);
        $q = DB::table('pemilihs')->whereRaw("(nama like '%".$keyword."%') OR (nik like '%".$keyword."%') ")
        ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
        ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
        ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
        ->select('pemilihs.id','pemilihs.nama','pemilihs.nik','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','pemilihs.tps')
        ->orderBy('nama')->get();
        $total=$q->count();
        if($total>0){
            $total_show=$total.' data ditemukan';
        }else{
            $total_show='<span class="text-danger">data tidak ditemukan</span>';
        }

        $table='';
        $table.='<div class="table-responsive">';
        $table.='<table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_pendukung">';
        $table.='<thead class="thead-light">';
        $table.='<tr>';
        $table.='<th scope="col" class="text-center">No</th>';
        $table.='<th scope="col">Nama</th>';
        $table.='<th scope="col">NIK</th>';
        $table.='<th scope="col">Kabupaten</th>';
        $table.='<th scope="col">Kecamatan</th>';
        $table.='<th scope="col">Kel/Desa</th>';
        $table.='<th scope="col">TPS</th>';
        $table.='<th scope="col" class="text-center span2 datatable-nosort">Aksi</th>';
        $table.='</tr>';
        $table.='</thead>';
        $table.='<tbody>';
        $i=1;
        if($q->count()>0){
            foreach($q as $dt){

                $table.='<tr data-id="' . Formatter::encrypt0($dt->id) . '">';
                $table.='<td scope="col">'.$i++.'</td>';
                $table.='<td scope="col">'.$dt->nama.'</td>';
                $table.='<td scope="col">'.$dt->nik.'</td>';
                $table.='<td scope="col">'.$dt->kabupaten.'</td>';
                $table.='<td scope="col">'.$dt->kecamatan.'</td>';
                $table.='<td scope="col">'.$dt->kelurahan.'</td>';
                $table.='<td scope="col">'.$dt->tps.'</td>';
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
    
    public function get_detail_pendukung(Request $request)
    {
        $id=Formatter::decrypt0($request->id);
        $q = DB::table('pemilihs')->whereRaw("pemilihs.id='$id'")
        ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
        ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
        ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
        ->select('pemilihs.nama','pemilihs.nik','pemilihs.jenis_kelamin','pemilihs.rt','pemilihs.rw','pemilihs.alamat','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','pemilihs.tps','pemilihs.kab_id','pemilihs.kec_id','pemilihs.kel_id')
        ->first();
       
        return response()->json([
            'id' => Formatter::encrypt0($id),
            'q' => $q,
        ]);
    }

    public function get_pendukung(Request $request)
    {
        $role=$request->session()->get('role');
        if($role=='a'){
            $kelompok_tim=$request->kelompok_tim;
        }else{
            $kelompok_tim=$request->session()->get('unit_kerja_id');
        }
        $tim_id=Formatter::decrypt0($request->tim_id);
        $kabupaten=$request->kabupaten;
        $kecamatan=$request->kecamatan;
        $kelurahan=$request->kelurahan;
        $tps=$request->tps;
        $nkk=$request->nkk;
        $kategori_pendukung=$request->kategori_pendukung;
        if ($request->ajax()) {
            $where="";
            if(!empty($kelompok_tim)){
                $where.=" AND tims.kelompok_tim_id='$kelompok_tim'";
            }
            if(!empty($tim_id)){
                $where.=" AND pemilihs.tim_id='$tim_id'";
            }
            if(!empty($nkk)){
                $where.=" AND pemilihs.nkk='$nkk'";
            }
            if(!empty($kabupaten)){
                $where.=" AND pemilihs.kab_id='$kabupaten'";
            }
            if(!empty($kecamatan)){
                $where.=" AND pemilihs.kec_id='$kecamatan'";
            }
            if(!empty($kelurahan)){
                $where.=" AND pemilihs.kel_id='$kelurahan'";
            }
            if(!empty($tps)){
                $where.=" AND pemilihs.tps='$tps'";
            }
            if(!empty($kategori_pendukung)){
                $where.=" AND pemilihs.kategori_pendukung='$kategori_pendukung'";
            }
            $q = DB::table('pemilihs')
            ->leftjoin('tims','tims.id','=','pemilihs.tim_id')
            ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
            ->whereRaw("pemilihs.pendukung='Y' $where")
            ->select('pemilihs.id','pemilihs.nama','pemilihs.nik','pemilihs.nkk','pemilihs.kategori_pendukung','pemilihs.telp','kabupatens.kabupaten','kecamatans.kecamatan','kelurahans.kelurahan','pemilihs.tps','tims.nama as referensi')
            ->orderBy('time_invite','desc');

            $tot_strong = DB::table('pemilihs')
            ->leftjoin('tims','tims.id','=','pemilihs.tim_id')
            ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
            ->whereRaw("pemilihs.pendukung='Y' $where AND kategori_pendukung='S'")->count();
            $tot_weak = DB::table('pemilihs')
            ->leftjoin('tims','tims.id','=','pemilihs.tim_id')
            ->leftjoin('kabupatens','kabupatens.id','=','pemilihs.kab_id')
            ->leftjoin('kecamatans','kecamatans.id','=','pemilihs.kec_id')
            ->leftjoin('kelurahans','kelurahans.id','=','pemilihs.kel_id')
            ->whereRaw("pemilihs.pendukung='Y' $where AND kategori_pendukung='W'")->count();
            
            $columns = array( 
                0 =>'id', 
                1 =>'nama',
                2=> 'nik',
                3=> 'nkk',
                4=> 'kabupaten',
                5=> 'kecamatan',
                6=> 'kelurahan',
                7=> 'tps',
                8=> 'kategori_pendukung',
                9=> 'telp',
                10=> 'referensi',
                11=> 'action',
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

                $totalFiltered = $q->where('pemilihs.nama','LIKE',"%{$search}%")
                                ->orWhere('pemilihs.nik', 'LIKE',"%{$search}%")
                                ->count();
                                
                $lists =  $q->where('pemilihs.nama','LIKE',"%{$search}%")
                                ->orWhere('pemilihs.nik', 'LIKE',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            }

            $data = array();
            if(!empty($lists))
            {
                $i=$start+1;
                foreach ($lists as $dt)
                {
                    
                    if($role=='a'){
                        $btn='<div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                        
                        $btn .= '<span class="btn-action btn-action-delete delete" title="Batal" title="Batal" data-id="'.Formatter::encrypt0($dt->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Batal</span>';
                        $btn.='</div>';
                        $btn.='</div>';
                    }else{
                        $btn='-';
                    }

                    $d_kategori_pendukung=array(NULL=>'-','S' =>'Strong','W'=>'Weak');
                    $d_color=array(NULL =>'light','S' =>'danger' ,'W'=>'warning');
                    $kategori_pendukung= '<span class="badge badge-'.$d_color[$dt->kategori_pendukung].'">'.$d_kategori_pendukung[$dt->kategori_pendukung].'</span>';

                    $nestedData['DT_RowIndex'] = $i++;
                    $nestedData['nama'] = $dt->nama;
                    $nestedData['nik'] = $dt->nik;
                    $nestedData['nkk'] = $dt->nkk;
                    $nestedData['kabupaten'] = $dt->kabupaten;
                    $nestedData['kecamatan'] = $dt->kecamatan;
                    $nestedData['kelurahan'] = $dt->kelurahan;
                    $nestedData['tps'] = $dt->tps;
                    $nestedData['kategori_pendukung'] = $kategori_pendukung;
                    $nestedData['telp'] = $dt->telp;
                    $nestedData['referensi'] = $dt->referensi;
                    $nestedData['action'] = $btn;
                    $data[] = $nestedData;

                }
            }
            
            $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,   
                "total_pendukung" => Formatter::pointSeparator($totalData),
                "total_strong" => Formatter::pointSeparator($tot_strong),
                "total_weak" => Formatter::pointSeparator($tot_weak),
            );
                
            echo json_encode($json_data); 
        }
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invite_pendukung_update(Request $request)
    {

        $rules = [
            'tps'=>'required',
            'telp'=>'required|max:20',
            'tim_id'=>'required|max:50',
            'kategori_pendukung'=>'required',
            'photo'=>'max:2048|mimes:jpeg,png',
        ];

        $messages = [
            // 'username.required' => 'Username is empty',
        ];

        request()->validate($rules,$messages);

        $id=Formatter::decrypt0($request->id);
        $pemilih = Pemilih::find($id);
        if($pemilih->pendukung=='N'){
            $pemilih->tps = $request->tps;
            $pemilih->telp = $request->telp;
            $pemilih->kategori_pendukung = $request->kategori_pendukung;
            $pemilih->tim_id = Formatter::decrypt0($request->tim_id);
            $pemilih->pendukung='Y';
            $pemilih->invited_by =  $request->session()->get('id');
            $pemilih->time_invite =  now();
    
            $pemilih->save();
        }else{
            return response()->json([
                'status' => 500,
                'message' => 'Data sudah diinvite',
            ]);
        }

        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
        $pemilih = Pemilih::find(Formatter::decrypt0($request->id));
        $pemilih->tim_id=NULL;
        $pemilih->time_invite=NULL;
        $pemilih->pendukung='N';
        $pemilih->kategori_pendukung=NULL;
        $pemilih->invited_by =  NULL;
        $pemilih->updated_by=$request->session()->get('id');
        $pemilih->save();
    }
}
