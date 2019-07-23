<?php

namespace App\Http\Controllers;

use App\PindahGudang;
use App\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\WorkOrders;
use App\Employee;
use App\Stock;
use Response;
use App\TransaksiPindahGudang;
use DB;
use Alert;

class PindahGudangController extends Controller
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
        return view('transaksi.inventory.pindah_gudang', compact('gudangs','tanggal'));
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
        $data_user = Auth::user();
        $pindah_gudang = PindahGudang::create([
            'user_id'       => $data_user->id,
            'gudang_asal'   => $request->gudang_asal,
            'gudang_tujuan' => $request->gudang_tujuan,
            'tanggal'       => date('Y-m-d'),
            'keterangan'    => $request->keterangan
        ]);
        for ($i=0; $i <sizeof($data['kode']) ; $i++) { 
            $datas[$i]['barang_id'] = $data['id_barang'][$i];
            $datas[$i]['gudang_id'] = $request->gudang_tujuan;
            $datas[$i]['qty'] = $data['qty'][$i];
        }

        foreach($datas as $stocks){            
            if ($stocks['barang_id'] != null) {
                $check_qty = $stocks['qty'];
                while ($check_qty > 0) {
                    $stock = Stock::orderBy('created_at', 'asc')->where('barang_id', $stocks['barang_id'])->where('last_qty', '>', '0')->where('gudang_id', $request->gudang_asal)->first();
                    // echo $stock;exit();
                    if ((int) $stock['last_qty'] < $check_qty) {
                        $sisa_qty = (int) $check_qty - $stock['last_qty'];
                        $last_stock = 0;
                    }else{
                        $last_stock = (int) $stock['last_qty'] - $check_qty;
                        $sisa_qty = 0;
                    }
                    // echo $stock['id'];exit();
                    Stock::where('id', $stock['id'])->update([
                        'last_qty' => $last_stock,
                        ]);

                    $stock_create = Stock::create([
                        'barang_id'             => $stocks['barang_id'],
                        'gudang_id'             => $request->gudang_tujuan,
                        'qty'                   => (int) $check_qty - $sisa_qty,
                        'last_qty'              => (int) $check_qty - $sisa_qty,
                        'harga_pokok'           => $stock->harga_pokok,
                    ]);

                    
                    // Stock::where('id', $stock['id'])->update([
                    //     'last_qty' => $last_stock,
                    //     ]);

                    // StockOut::create([
                    //     'stock_id' => $stock['id'],
                    //     'invoice_id' => $work_orders->id,
                    //     'qty' => (int) $check_qty - $sisa_qty,
                    //     ]);

                    $check_qty = $sisa_qty;
                }

                $transaksi_pindah_gudang = TransaksiPindahGudang::create([
                    'pindah_gudang_id'      =>$pindah_gudang->id,
                    'barang_id'             => $stocks['barang_id'],
                    'qty'                   => $stocks['qty'],
                ]);
            }
        }
        Alert::success('Pindah Gudang Berhasil', 'Berhasil');
        return redirect("/pindah-gudang/");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PindahGudang  $pindahGudang
     * @return \Illuminate\Http\Response
     */
    public function show(PindahGudang $pindahGudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PindahGudang  $pindahGudang
     * @return \Illuminate\Http\Response
     */
    public function edit(PindahGudang $pindahGudang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PindahGudang  $pindahGudang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PindahGudang $pindahGudang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PindahGudang  $pindahGudang
     * @return \Illuminate\Http\Response
     */
    public function destroy(PindahGudang $pindahGudang)
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
