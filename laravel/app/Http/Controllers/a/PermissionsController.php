<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;


class PermissionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:permission view', ['only' => ['index','get_permissions']]);
        $this->middleware('permission:permission create', ['only' => ['create','store']]);
        $this->middleware('permission:permission update', ['only' => ['edit','store']]);
        $this->middleware('permission:permission delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Permissions';
        return view('a.permissions.main',$d);
    }

    public function get_permissions(Request $request)
    {
        $f_kategori=$request->x;
        if ($request->ajax()) {
            $data = Permission::orderBy('id','DESC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn='<div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                        $btn .= '<a href="#" class="btn-action edit" title="Edit"  data-id="' . Formatter::encrypt0($row->id) . '" data-toggle="modal" data-target="#modal"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                        <span class="btn-action btn-action-delete delete" title="Hapus" data-id="'.Formatter::encrypt0($row->id).'"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</span>';
                        $btn.='</div>';
                        $btn.='</div>';
                        return $btn;
                    })


                    ->rawColumns(['action'])
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
        $d['title']='Tambah Permissions';
        return view('a.permissions.create',$d);
    }

    public function get_data(Request $request)
    {
        $id = Formatter::decrypt0($request->id);
        $q = Permission::where('id', $id)->first();

        return response()->json([
            'id' => Formatter::encrypt0($q->id),
            'name' => $q->name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->param == 'add') {

            $rules = [
                'name'=>'required|unique:permissions|max:50',
            ];
    
            $messages = [
                // 'username.required' => 'Username is empty',
            ];
    
            request()->validate($rules,$messages);

            $permissions = new Permission;
            $text = 'Data sukses disimpan';
        } else {
            $permissions = Permission::find(Formatter::decrypt0($request->id));
            $text = 'Data sukses diupdate';
        }

        $permissions->name = $request->name;

        $permissions->save();

        return response()->json([
            'text' => $text
        ]);
    }

    /**
     * Display the specified resource.
     *
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
        $permissions = Permission::find(Formatter::decrypt0($request->id));
        $permissions->delete();
    }
}
