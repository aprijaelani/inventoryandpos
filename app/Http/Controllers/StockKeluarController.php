<?php

namespace App\Http\Controllers;

use Response;
use App\StockKeluar;
use App\TransaksiStockKeluar;
use App\Gudang;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Alert;


class StockKeluarController extends Controller
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
        return view('transaksi.inventory.stock_keluar', compact('gudangs','tanggal'));
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
        // dd($request->all());exit;
        $data_user = Auth::user();
        // echo $data_user->id;exit();
        $stock_keluar = StockKeluar::create([
            'user_id'    => $data_user->id,
            'gudang'     => $request->gudang_id,
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        $data = $request->all();
        for ($i=0; $i <sizeof($data['id_barang']) ; $i++) { 
            $datas[$i]['id_barang'] = $data['id_barang'][$i];
            $datas[$i]['qty'] = $data['qty'][$i];
        }

        foreach ($datas as $stocks) {
            if ($stocks['id_barang'] != null) {
                $check_qty = $stocks['qty'];
                while ($check_qty > 0) {
                    $stock = Stock::orderBy('id', 'asc')->where('barang_id', $stocks['id_barang'])->where('last_qty', '>', '0')->where('gudang_id', $request->gudang_id)->first();

                    if ((int) $stock['last_qty'] < $check_qty) {
                        $sisa_qty = (int) $check_qty - $stock['last_qty'];
                        $last_stock = 0;
                    }else{
                        $last_stock = (int) $stock['last_qty'] - $check_qty;
                        $sisa_qty = 0;
                    }
                    Stock::where('id', $stock['id'])->update([
                        'last_qty' => $last_stock,
                        ]);

                    $check_qty = $sisa_qty;
                } 

                TransaksiStockKeluar::create([
                    'stock_keluar_id'   => $stock_keluar->id,
                    'barang_id'         => $stocks['id_barang'],
                    'qty'               => $stocks['qty']
                ]);
            }
        }

        Alert::success('Stok Keluar Berhasil', 'Berhasil');
        return redirect("/stok-keluar/");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockKeluar  $stockKeluar
     * @return \Illuminate\Http\Response
     */
    public function show(StockKeluar $stockKeluar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockKeluar  $stockKeluar
     * @return \Illuminate\Http\Response
     */
    public function edit(StockKeluar $stockKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockKeluar  $stockKeluar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockKeluar $stockKeluar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockKeluar  $stockKeluar
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockKeluar $stockKeluar)
    {
        //
    }

    public function barang (Request $request)
    {
        $barang = DB::table('stocks')
                     ->select('barangs.*','stocks.*', 'stocks.id as idstock')
                     ->addSelect(DB::raw('SUM(stocks.last_qty) as total'))
                     ->leftJoin('barangs', 'stocks.barang_id', '=', 'barangs.id')
                     ->where(function ($query) {
                        $query->where('barangs.kode_barcode', 'like', '%' .request('id'). '%')
                                ->orWhere('barangs.nama_barang', 'like', '%' .request('id'). '%');
                            })
                     ->where('stocks.last_qty','!=',0)
                     ->where('stocks.gudang_id', '=', $request->gudang_id)
                     ->groupBy('stocks.barang_id')
                     ->offset(0)
                     ->limit(15)
                     ->get(); 
        return Response::json($barang);
    }

}
