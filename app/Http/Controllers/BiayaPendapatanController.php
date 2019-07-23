<?php

namespace App\Http\Controllers;

use App\BiayaPendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Alert;
use Response;

class BiayaPendapatanController extends Controller
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
            $biayaPendapatans = DB::table('biaya_pendapatans')
                         ->select('id', 'nama', DB::raw('(CASE WHEN jenis = 1 THEN "Pendapatan" ELSE "Biaya" END) AS jenis'))
                         ->where('deleted_at')
                         ->paginate(15);
            return view('master.biaya_pendapatan.list_biaya_pendapatan', compact('biayaPendapatans'));
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
            return view('master.biaya_pendapatan.create_biaya_pendapatan');
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
        // dd($request->all());exit();
        BiayaPendapatan::create([
            'nama'  => request('keterangan'),
            'jenis' => request('jenis')
        ]);

        if (request('jenis') == 1) {
            Alert::success('Tambah Pendapatan Berhasil', 'Berhasil');
        }else{
            Alert::success('Tambah Biaya Berhasil', 'Berhasil');
        }
        return redirect("/biaya-pendapatan");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BiayaPendapatan  $biayaPendapatan
     * @return \Illuminate\Http\Response
     */
    public function show(BiayaPendapatan $biayaPendapatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BiayaPendapatan  $biayaPendapatan
     * @return \Illuminate\Http\Response
     */
    public function edit(BiayaPendapatan $biayaPendapatan)
    {
        // echo $biayaPendapatan;exit();
        $data_user = Auth::user();
        if ($data_user) {
            return view('master.biaya_pendapatan.edit_biaya_pendapatan', compact('biayaPendapatan'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BiayaPendapatan  $biayaPendapatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BiayaPendapatan $biayaPendapatan)
    {
        // dd($request->all());exit();
        BiayaPendapatan::where('id', request('id'))->update([
            'nama'  => request('keterangan'),
            'jenis' => request('jenis')
        ]);

        if (request('jenis') == 1) {
            Alert::success('Ubah Pendapatan Berhasil', 'Berhasil');
        }else{
            Alert::success('Ubah Biaya Berhasil', 'Berhasil');
        }
        return redirect("/biaya-pendapatan");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BiayaPendapatan  $biayaPendapatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $time   = date('Y-m-d H:i:s');
        $biaya_pendapatan = BiayaPendapatan::find(request('id'));
        // echo $biaya_pendapatan;exit();
        $biaya_pendapatan->delete();
        return redirect("/biaya-pendapatan");
    }
}
