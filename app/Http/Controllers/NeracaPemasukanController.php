<?php

namespace App\Http\Controllers;

use App\NeracaPemasukan;
use App\NeracaKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NeracaPemasukanController extends Controller
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
            $check = NeracaKeuangan::where('tanggal', date('Y-m-d'))->where('status','3')->orderBy('id','asc')->first();
            $date = date('Y-m-d');
            return view('transaksi.finance.set_kas_awal', compact('check'));
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_user = Auth::user();
        $last_total = NeracaKeuangan::where('tanggal', date('Y-m-d'))->orderBy('id','desc')->first();

        $kas_str = preg_replace("/[^0-9]/", "", $request->tambah_kas);
        $kas_masuk = (int) $kas_str;
        if ($last_total == null) {
            $neraca_keuangan = NeracaKeuangan::create([
                'tanggal' => date('Y-m-d'),
                'total' => $kas_masuk,
                'last_total' => $kas_masuk,
                'status' => '3',
                'status_pembayaran' => '1',
                'user_id' => $data_user->id
                ]);    
        }else{
            $neraca_keuangan = NeracaKeuangan::create([
                'tanggal' => date('Y-m-d'),
                'total' => $kas_masuk,
                'last_total' => (int) $last_total->last_total + $kas_masuk,
                'status' => '3',
                'status_pembayaran' => '1',
                'user_id' => $data_user->id
                ]);
        }
        
        // echo $neraca_keuangan->id;exit();
        NeracaPemasukan::create([
            'neraca_id' => $neraca_keuangan->id,
            'total' => $kas_masuk,
            'keterangan' => 'Buka Kas'
            ]);
        return redirect('/set_kas');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NeracaPemasukan  $neracaPemasukan
     * @return \Illuminate\Http\Response
     */
    public function show(NeracaPemasukan $neracaPemasukan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NeracaPemasukan  $neracaPemasukan
     * @return \Illuminate\Http\Response
     */
    public function edit(NeracaPemasukan $neracaPemasukan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NeracaPemasukan  $neracaPemasukan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NeracaPemasukan $neracaPemasukan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NeracaPemasukan  $neracaPemasukan
     * @return \Illuminate\Http\Response
     */
    public function destroy(NeracaPemasukan $neracaPemasukan)
    {
        //
    }
}
