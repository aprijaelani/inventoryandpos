<?php

namespace App\Http\Controllers;

use App\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Gudang;
use Response;
use App\TransaksiStockOpname;
use App\Stock;
use DB;
use Alert;

class StockOpnameController extends Controller
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
        return view('transaksi.inventory.stock_opname', compact('gudangs','tanggal'));
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
        $stock_opname = StockOpname::create([
            'gudang_id'  => $request->gudang,
            'tanggal'    => $request->tanggal_check,
            'keterangan' => $request->keterangan,
        ]);
        $data = $request->all();
        for ($i=0; $i <sizeof($data['id_barang']) ; $i++) { 
            $qty_str = preg_replace("/[^0-9]/", "", $data['qty'][$i]);
            $qty = (int) $qty_str;

            $datas[$i]['id_barang'] = $data['id_barang'][$i];
            $datas[$i]['qty'] = $qty;
            $datas[$i]['idstock'] = $data['idstock'][$i];
        }
        // dd($datas);exit();

        foreach ($datas as $stocks) {
            if ($stocks['id_barang'] != null) {

                
                $sum_stock = Stock::where('barang_id',$stocks['id_barang'])->where('gudang_id', $request->gudang)->sum('last_qty');
                $selisih = (int) $sum_stock - $stocks['qty'];
                // echo $selisih;exit();

                if ((int) $selisih < 0) { //Penambahan Stock
                    $check_qty = $selisih * -1;
                    while ($check_qty > 0) {
                        $stock = Stock::orderBy('id', 'desc')->where('barang_id', $stocks['id_barang'])->whereRaw('last_qty < qty')->where('gudang_id', $request->gudang)->first();
                        $sisa_stock = (int) $stock['qty'] - $stock['last_qty'];

                        // dd($stock);exit();
                        if ($stock != null) {
                            if ((int) $sisa_stock < $check_qty) {
                                $sisa_qty = (int) $check_qty - $sisa_stock;
                                $last_stock = $stock['qty'];
                            }else{
                                $last_stock = (int) $stock['last_qty'] + $check_qty;
                                $sisa_qty = 0;
                            }

                            Stock::where('id', $stock['id'])->update([
                                'last_qty' => $last_stock,
                                ]);

                            $check_qty = $sisa_qty;
                        }else{
                            $check_harga = Stock::orderBy('id', 'desc')->where('barang_id', $stocks['id_barang'])->where('last_qty', '>', '0')->where('gudang_id', $request->gudang)->first();
                            // echo $check_harga;exit();
                            $stock_create = Stock::create([
                                'barang_id'             => $stocks['id_barang'],
                                'gudang_id'             => $request->gudang,
                                'qty'                   => $check_qty,
                                'last_qty'              => $check_qty,
                                'harga_pokok'           => $check_harga->harga_pokok,
                            ]);

                            $check_qty = 0;
                        }
                    }
                }else if((int) $selisih > 0){ //Pengurangan Stock
                    $check_qty = $selisih;
                    while ($check_qty > 0) {
                        $stock = Stock::orderBy('id', 'asc')->where('barang_id', $stocks['id_barang'])->where('last_qty', '>', '0')->where('gudang_id', $request->gudang)->first();

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
                }
                

                TransaksiStockOpname::create([
                    'stock_opname_id' => $stock_opname->id,
                    'barang_id'       => $stocks['id_barang'],
                    'stock_id'        => $stocks['idstock'],
                    'selisih'         => $selisih
                ]);
            }
        }

        Alert::success('Stok Opnam Berhasil', 'Berhasil');
        return redirect("/stok-opname/");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockOpname  $stockOpname
     * @return \Illuminate\Http\Response
     */
    public function show(StockOpname $stockOpname)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockOpname  $stockOpname
     * @return \Illuminate\Http\Response
     */
    public function edit(StockOpname $stockOpname)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockOpname  $stockOpname
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockOpname $stockOpname)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockOpname  $stockOpname
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockOpname $stockOpname)
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
                     // ->where('stocks.last_qty','!=',0)
                     ->where('stocks.gudang_id', '=', $request->gudang_id)
                     ->groupBy('stocks.barang_id')
                     ->offset(0)
                     ->limit(15)
                     ->get();
                     echo $barang;exit();
        return Response::json($barang);exit;
    }
}
