<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;

use Auth;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:users-list');
        $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->hasRole('superadmin')) {
            $data = User::orderBy('id', 'DESC')->get();
        } elseif ($request->user()->hasRole('admin')) {
            $data = User::where('cmp_id', Auth::user()->cmp_id)->orderBy('id', 'DESC')->get();
        }
        return view('user.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('user.create', compact('roles'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        if (empty($input['site'])) {
            $input['cmp_id'] = Auth::user()->cmp_id;
        } else {
            $input['cmp_id'] = \App\Cmp::firstOrCreate(array("name" => $request->input('site')))->id;
        }
        $user = User::create($input);
        if (!empty($input['roles'])) {
            $user->assignRole($request->input('roles'));
        } else {
            $user->assignRole("user");
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('user.show', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('user.edit', compact('user', 'roles', 'userRole'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
        ]);


        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));
        }


        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        if (!empty($input['roles'])) {
            $user->assignRole($request->input('roles'));
        } else {
            $user->assignRole("user");
        }



        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \App\User::where('id', $id)->first();
        $user->delete();

        if ($user != null) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully');
        }

        return redirect()->route('users.index')->with('error', 'Wrong Id!');;
    }
}
