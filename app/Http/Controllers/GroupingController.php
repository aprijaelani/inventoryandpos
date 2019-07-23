<?php

namespace App\Http\Controllers;

use App\Grouping;
use Alert;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_user = Auth::user();
        if ($data_user) {
            $groups = Grouping::orderBy('id','asc')->paginate(10);
            return view('master.grouping.list_group', compact('groups'));
        }else{
            return redirect('/');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data_user = Auth::user();
        if ($data_user) {
            return view('master.grouping.create_group');
        }else{
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Grouping::create([
            'nama' => request('nama')
        ]);
        Alert::success('Tambah Jenis Barang Berhasil', 'Berhasil');
        return redirect("/grouping");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grouping  $grouping
     * @return \Illuminate\Http\Response
     */
    public function show(Grouping $grouping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Grouping  $grouping
     * @return \Illuminate\Http\Response
     */
    public function edit(Grouping $grouping)
    {   
        $data_user = Auth::user();
        if ($data_user) {
            return view('master.grouping.edit_group', compact('grouping'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grouping  $grouping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grouping $grouping)
    {
        Grouping::where('id', $grouping->id)->update([
            'nama' => request('nama')
        ]);
        Alert::success('Ubah Jenis Barang Berhasil', 'Berhasil');
        return redirect("grouping");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Grouping  $grouping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $check_child = Grouping::where('id',$request->id)->with('barang')->first();
        if ($check_child->barang->count() == 0) {
            $grouping = Grouping::find($request->id);
            $grouping->delete();
            return redirect("/grouping");
        }else{
            echo "false";
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkGroup (Request $request)
    {
        // echo $request;exit();
        $group = Grouping::where('nama', $request->group)->count();

        return Response::json($group);
    }
}
