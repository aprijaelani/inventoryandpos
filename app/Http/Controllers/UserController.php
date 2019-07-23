<?php

namespace App\Http\Controllers;

use App\User;
use Alert;
use App\Group;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::orderBy('id','asc')->paginate(10);

        $users = DB::table('users')
                     ->select('id', 'nama', 'username')
                     ->addSelect(DB::raw('(CASE group_id WHEN 1 THEN "Manager" WHEN 2 THEN "Supervisor" ELSE "Sales" END) AS group_id'))
                     ->orderBy('group_id','asc')
                     ->paginate(10);
        return view('master.user.list_user', compact('users'));
    }

    public function checkUsername()
    {
        $user = User::all()->where('username', Input::get('username'))->first();
        if ($user) {
            return Response::json(Input::get('username').' is already taken');
        } else {
            return Response::json(Input::get('username').' Username is available');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check_username (Request $request)
    {
        // echo $request;exit();
        $userCheck = User::
                where('username', $request->username)->
                first();

        if ($userCheck) {
            $response = 1;
        }else{
            $response = 0;
        }
        
        return Response::json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        return view('master.user.create_user',compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        User::create([
            'nama' => request('nama'),
            'password' => Hash::make(request('password')),
            'group_id' => request('group_id'),
            'username' => request('username')
            ]);
        Alert::success('Tambah User Berhasil', 'Berhasil');
        return redirect("/user");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data_user = Auth::user();
        if ($data_user) {
            $user = User::find($user->id);
            return view('master.user.edit_user', compact('user'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        User::where('id', $user->id)->update([
            'nama' => request('nama'),
            'password' => Hash::make(request('password')),
            'username' => request('username'),
            'group_id' => request('group_id'),
            ]);
        Alert::success('Ubah User Berhasil', 'Berhasil');
        return redirect("/user");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return redirect("/user");
    }
}
