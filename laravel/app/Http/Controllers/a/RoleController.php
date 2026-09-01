<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use Formatter;
use App\Http\Controllers\a\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:role view', ['only' => ['index','get_role']]);
        $this->middleware('permission:role create', ['only' => ['create','store']]);
        $this->middleware('permission:role update', ['only' => ['edit','store']]);
        $this->middleware('permission:role delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Role';
        return view('a.permissions.role',$d);
    }
    
    public function permission($id)
    {
        $id=Formatter::decrypt0($id);
        $role_name=array('p'=>'Panlih','kp'=>'Ketua Panlih','a'=>'Admin','su'=>'Super Admin','v'=>'Verifikator Suara');
        $permissions = Permission::get();
        $role=Role::findOrFail($id);
        $rolePermissions =  DB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id', $role->id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();
        $d['role']=$role;
        $d['permissions']=$permissions;
        $d['rolePermissions']=$rolePermissions;
        $d['icon']='pe-7s-angle-right-circle';
        $d['title']='Role Permission - '.$role_name[$role->name];
        return view('a.permissions.role_permission',$d);
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        $request->validate([
            'permission' => 'required'
        ]);
        $roleId=Formatter::decrypt0($roleId);
        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);

        return redirect()->back()->with('status','Permissions added to role');
    }

    public function get_role(Request $request)
    {
        $f_kategori=$request->x;
        if ($request->ajax()) {
            $data = Role::orderBy('id','DESC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('name', function($row){
                        $role_name=array('p'=>'Panlih','kp'=>'Ketua Panlih','a'=>'Admin','su'=>'Super Admin','v'=>'Verifikator Suara');
        
                        return $row->name.' - '.$role_name[$row->name];
                    })
                    ->addColumn('action', function($row){
                        $btn='<div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-datatable" aria-labelledby="dropdownMenuButton">';
                        $btn .= '
                        <a href="'.route('role.permission',Formatter::encrypt0($row->id)).'" title="Permission" class="btn-action btn-action-info permission"><i class="fa fa-file" aria-hidden="true"></i> Add/Edit Permission</a>
                        <a href="#" class="btn-action edit" title="Edit"  data-id="' . Formatter::encrypt0($row->id) . '" data-toggle="modal" data-target="#modal"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
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
        $d['title']='Tambah Role';
        return view('a.role.create',$d);
    }

    public function get_data(Request $request)
    {
        $id = Formatter::decrypt0($request->id);
        $q = Role::where('id', $id)->first();

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
                'name'=>'required|unique:roles|max:50',
            ];
    
            $messages = [
                // 'username.required' => 'Username is empty',
            ];
    
            request()->validate($rules,$messages);

            $role = new Role;
            $text = 'Data sukses disimpan';
        } else {
            $role = Role::find(Formatter::decrypt0($request->id));
            $text = 'Data sukses diupdate';
        }

        $role->name = $request->name;

        $role->save();

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
        $role = Role::find(Formatter::decrypt0($request->id));
        $role->delete();
    }
}
