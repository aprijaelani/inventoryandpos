<?php

namespace App\Http\Controllers;

use App\Gudang;
use Alert;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
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
            $gudangs = Gudang::orderBy('id','asc')->get();
            return view('master.gudang.list_gudang', compact('gudangs'));
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
            return view('master.gudang.create_gudang');
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
        Gudang::create([
            'nama' => request('nama')
            ]);
        Alert::success('Tambah Gudang Berhasil', 'Berhasil');
        return redirect("gudang");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function show(Gudang $gudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function edit(Gudang $gudang)
    {
        $data_user = Auth::user();
        if ($data_user) {
            return view('master.gudang.edit_gudang', compact('gudang'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gudang $gudang)
    {
        Gudang::where('id', $gudang->id)->update([
            'nama' => request('nama')
            ]);
        Alert::success('Ubah Gudang Berhasil', 'Berhasil');
        return redirect("/gudang");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $check_child = Gudang::where('id',$request->id)->with('stock')->first();
        if ($check_child->stock->count() == 0) {
            $gudang = Gudang::find($request->id);
            $gudang->delete();
            return redirect("/gudang");
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
    public function checkGudang (Request $request)
    {
        // echo $request;exit();
        $gudang = Gudang::where('nama', $request->gudang)->count();

        return Response::json($gudang);
    }
}
