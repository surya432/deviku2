<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;

class PermissionController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware( 'permission:permission-list');
    //     $this->middleware( 'permission:permission-create', ['only' => ['create', 'store']]);
    //     $this->middleware( 'permission:permission-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware( 'permission:permission-delete', ['only' => ['destroy']]);
    // }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Permission::orderBy('id', 'DESC')->get();
        return view( 'permission.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( 'permission.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
        ]);

        $dept = strtolower($request->input('name'));
        $data = array(
            array(
                'name' => $dept . '-list',
                'guard_name' => 'web'
            ),
            array(
                'name' => $dept . '-edit',
                'guard_name' => 'web'
            ),
            array(
                'name' => $dept . '-delete',
                'guard_name' => 'web'
            ),
            array(
                'name' => $dept . '-create',
                'guard_name' => 'web'
            ),
        );
        $role = Role::findByName('SuperAdmin');
        foreach ($data as $b) {
            Permission::create($b);
            $role->givePermissionTo($b[ 'name']);
        }
        return redirect()->route( 'permission.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Permission = Permission::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->join( "roles", "role_has_permissions.permission_id","=","roles.id")
            ->select("roles.name")
            ->where("role_has_permissions.role_id", $id)
            ->get();
        return view( 'permission.show', compact( 'rolePermissions', 'Permission'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::find($id);
        return view( 'permission.edit', compact('permission'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $Permission = Permission::find($id);
        $Permission->name = $request->input('name');
        $Permission->save();
        return redirect()->route( 'permission.index')
            ->with('success', 'Permissions updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table( "permissions")->where('id', $id)->delete();
        return redirect()->route( 'permission.index')
            ->with('success', 'Permissions deleted successfully');
    }
}
