<?php

namespace App\Http\Controllers;

use Response;
use App\StockMasuk;
use App\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Alert;
use App\Stock;
use App\TransaksiStockMasuk;

class StockMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_user = Auth::user();
        $gudangs = Gudang::all();
        $tanggal = date('Y-m-d');
        return view('transaksi.inventory.stock_masuk', compact('gudangs','tanggal'));
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
        // dd($request->all());exit();
        $data_user = Auth::user();
        $stock_masuk = StockMasuk::create([
            'user_id'    => $data_user->id,
            'gudang_id'  => $request->gudang_id,
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);
        $data = $request->all();

        for ($i=0; $i <sizeof($data['kode']) ; $i++) { 
            $datas[$i]['barang_id'] = $data['id_barang'][$i];
            $datas[$i]['harga'] = $data['harga'][$i];
            $datas[$i]['qty'] = $data['qty'][$i];
        }

        foreach ($datas as $data) {
            $harga_str = preg_replace("/[^0-9]/", "", $data['harga']);
            $harga = (int) $harga_str;

            $stock = Stock::create([
                'barang_id'             => $data['barang_id'],
                'gudang_id'             => $request->gudang_id,
                'qty'                   => $data['qty'],
                'last_qty'              => $data['qty'],
                'harga_pokok'           => $harga,
            ]);
            $transaksi_stock_masuk = TransaksiStockMasuk::create([
                'stock_masuk_id'    => $stock_masuk->id,
                'stock_id'          => $stock->id,
            ]);
        }

        Alert::success('Tambah Stok Manual Berhasil', 'Berhasil');
        return redirect("/stok-masuk/");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockMasuk  $stockMasuk
     * @return \Illuminate\Http\Response
     */
    public function show(StockMasuk $stockMasuk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockMasuk  $stockMasuk
     * @return \Illuminate\Http\Response
     */
    public function edit(StockMasuk $stockMasuk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockMasuk  $stockMasuk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockMasuk $stockMasuk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockMasuk  $stockMasuk
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockMasuk $stockMasuk)
    {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function barang (Request $request)
    {

        $barang = DB::table('stocks')
                     ->select('barangs.*')
                     ->addSelect(DB::raw('(CASE WHEN ('.DB::raw('SUM(stocks.last_qty)').') IS NULL  THEN 0 ELSE ('.DB::raw('SUM(stocks.last_qty)').') END) AS total'))
                     ->rightJoin('barangs', 'stocks.barang_id', '=', 'barangs.id')
                     ->where(function ($query) {
                        $query->where('barangs.kode_barcode', 'like', '%' .request('id'). '%')
                                ->orWhere('barangs.nama_barang', 'like', '%' .request('id'). '%');
                            })
                     ->groupBy('barangs.id')
                     ->offset(0)
                     ->limit(15)
                     ->get(); 

        // $barang = DB::table('stocks')
        //              ->select('barangs.*','stocks.*', 'stocks.id as idstock')
        //              ->addSelect(DB::raw('SUM(stocks.last_qty) as total'))
        //              ->leftJoin('barangs', 'stocks.barang_id', '=', 'barangs.id')
        //              ->where(function ($query) {
        //                 $query->where('barangs.kode_barcode', 'like', '%' .request('id'). '%')
        //                         ->orWhere('barangs.nama_barang', 'like', '%' .request('id'). '%');
        //                     })
        //              ->where('stocks.gudang_id', '=', $request->gudang_id)
        //              ->groupBy('stocks.barang_id')
        //              ->offset(0)
        //              ->limit(15)
        //              ->get(); 
        return Response::json($barang);
    }
}
