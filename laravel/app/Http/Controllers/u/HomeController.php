<?php

namespace App\Http\Controllers\u;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kandidat;
use Formatter;
use Illuminate\Support\Facades\DB;
use Exception;

class HomeController extends Controller
{
    public function index(Request $request){
        $d['icon']='';
        $d['title']='';
        $d['calon_kandidats']=Kandidat::where('kandidat_status','Y')->orderBy('no_urut','asc')->get();
        $d['kandidats']=Kandidat::where('kandidat_status','Y')->orderBy('no_urut','asc')->get();
        return view('u.home',$d);
    }
    
    public function kandidat_detail($id){
        $id=Formatter::decrypt0($id);
        $kandidat=Kandidat::where('id',$id)->first();
        if ($kandidat === null) {
            abort(404);
        }
        $d['icon']='';
        $d['title']='';
        $d['kandidat']=$kandidat;
        return view('u.kandidat_detail',$d);
    }

}
