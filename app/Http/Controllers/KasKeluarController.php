<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NeracaPengeluaran;
use App\NeracaKeuangan;
use App\BiayaPendapatan;
use Illuminate\Support\Facades\Auth;
use Alert;
use Response;

class KasKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tanggal = date('Y-m-d');
        return view('transaksi.finance.kas_keluar', compact('tanggal'));
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
        $data = $request->all();  
        $total = 0;
        $data_user = Auth::user();
        for ($i=0; $i <sizeof($data['nominal']) ; $i++) { 
            $nominal_str = preg_replace("/[^0-9]/", "", $data['nominal'][$i]);
            $nominal = (int) $nominal_str;

            $total = $total + $nominal;

            $datas[$i]['nominal'] = $nominal;
            $datas[$i]['keterangan'] = $data['keterangan'][$i];
            $datas[$i]['keteranganid'] = $data['keteranganid'][$i];
        }

        $check_last_transaksi = NeracaKeuangan::where('tanggal',$request->tanggal)->orderBy('id','desc')->first();
        if ($check_last_transaksi != null) {
            $last_total = $check_last_transaksi->last_total - $total;
        }else{
            $last_total = -$total;
        }
        $neraca_keuangan = NeracaKeuangan::create([
            'tanggal'        => $request->tanggal,
            'total'          => $total,
            'last_total'     => $last_total,
            'status'         => 2,
            'status_pembayaran'=> 1,
            'user_id'        => $data_user->id,
        ]);
        foreach ($datas as $data) {
            if ($data['keterangan'] != null) {
                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $request->tanggal,
                    'total'         => $data['nominal'],
                    'keterangan'    => $data['keterangan'],
                    'keterangan_id'    => $data['keteranganid'],
                ]);
            }
        }

        Alert::success('Kas Keluar Berhasil', 'Berhasil');
        return redirect("/kas-keluar");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function biaya_pendapatan (Request $request)
    {
        // dd($request->all());exit();
        $biaya_pendapatan = BiayaPendapatan::where('nama', 'LIKE', '%'.$request->search.'%')->where('jenis', '2')->offset(0)->limit(15)->get(); 
        return Response::json($biaya_pendapatan);
    }
}
