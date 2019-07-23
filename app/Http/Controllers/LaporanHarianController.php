<?php

namespace App\Http\Controllers;

use App\LaporanStock;
use App\NeracaKeuangan;
use Illuminate\Http\Request;
use DB;
use Response;

class LaporanHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tanggal = date('Y-m-d');
        // $tanggal = '2017-10-15';
        $neracas = DB::table('neraca_keuangans')
                     ->select('neraca_keuangans.created_at AS tanggal', 'neraca_keuangans.status AS status')
                     ->addSelect(DB::raw('(CASE WHEN neraca_keuangans.status = 2 THEN (neraca_pengeluarans.total) ELSE (neraca_pemasukans.total) END) AS total'))
                     ->addSelect(DB::raw('(CASE WHEN neraca_keuangans.status = 2 THEN (neraca_pengeluarans.keterangan) ELSE (neraca_pemasukans.keterangan) END) AS keterangan'))
                     ->leftJoin('neraca_pemasukans', 'neraca_pemasukans.neraca_id', '=', 'neraca_keuangans.id')
                     ->leftJoin('neraca_pengeluarans', 'neraca_pengeluarans.neraca_id', '=', 'neraca_keuangans.id')
                     ->where("neraca_keuangans.tanggal", $tanggal)
                     ->where("neraca_keuangans.status_pembayaran", "1")
                     ->orderBy("neraca_keuangans.created_at", "asc")
                     // ->groupBy(DB::raw('neraca_keuangans.id WITH ROLLUP'))
                     ->get();
        $total = NeracaKeuangan::where('tanggal', $tanggal)->orderBy('id', 'desc')->first();
                     // echo $total;exit();
        // $neracas = NeracaKeuangan::where("tanggal", $tanggal)->paginate(10);
        return view('transaksi.finance.laporan_harian', compact('tanggal', 'neracas', 'total'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search (Request $request)
    {
    	$tanggal = $request->search;
        $neracas = DB::table('neraca_keuangans')
                     ->select('neraca_keuangans.created_at AS tanggal', 'neraca_keuangans.status AS status')
                     ->addSelect(DB::raw('(CASE WHEN neraca_keuangans.status = 2 THEN (neraca_pengeluarans.total) ELSE (neraca_pemasukans.total) END) AS total'))
                     ->addSelect(DB::raw('(CASE WHEN neraca_keuangans.status = 2 THEN (neraca_pengeluarans.keterangan) ELSE (neraca_pemasukans.keterangan) END) AS keterangan'))
                     ->leftJoin('neraca_pemasukans', 'neraca_pemasukans.neraca_id', '=', 'neraca_keuangans.id')
                     ->leftJoin('neraca_pengeluarans', 'neraca_pengeluarans.neraca_id', '=', 'neraca_keuangans.id')
                     ->where("neraca_keuangans.tanggal", $tanggal)
                     ->where("neraca_keuangans.status_pembayaran", "1")
                     ->orderBy("neraca_keuangans.created_at", "asc")
                     // ->groupBy(DB::raw('neraca_keuangans.id WITH ROLLUP'))
                     ->get();
        $total = NeracaKeuangan::where('tanggal', $tanggal)->orderBy('id', 'desc')->first();

        $arr = array('neracas' => $neracas,
                            'total' => $total );
        return Response::json($arr);
    }
}
