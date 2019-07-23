<?php

namespace App\Http\Controllers;

use App\PreOrderIn;
use App\Supplier;
use App\PreOrder;
use Illuminate\Http\Request;
use App\PreOrderOut;
use DB;
use Illuminate\Support\Facades\Auth;
use App\PenerimaanBarang;
use App\Barang;
use Alert;
use Response;
use App\Gudang;

class PreOrderInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $data_user = Auth::user();
        $tanggal = date('Y-m-d');
        if ($data_user) {
            $pre_orders = PreOrder::where('tanggal_estimasi','!=','null')->get();
            $suppliers = PreOrder::where('tanggal_estimasi','!=',null)->with('supplier')->groupBy('supplier_id')->get();
            // print_r($suppliers->toArray());exit;
            return view('transaksi.pembelian.penerimaan_barang', compact('pre_orders', 'suppliers', 'tanggal'));        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PreOrderIn  $preOrderIn
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreOrderIn  $preOrderIn
     * @return \Illuminate\Http\Response
     */
    public function edit(PreOrderIn $preOrderIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreOrderIn  $preOrderIn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $tanggal = $request->tanggal_sj;
        $tanggal_next = date('Y-m-j', strtotime('+1 month', strtotime($tanggal)));
        $old_data = PreOrder::where('id',$request->pre_order_id)->first();
        $penerimaan_barang = PenerimaanBarang::create([
            'kode_po'            => $old_data->kode_po,
            'supplier_id'        => $old_data->supplier_id,
            'tanggal'            => $tanggal,
            'user_id'            => $old_data->user_id,
            'no_sj'              => $request->no_sj,
            'status'             => 1,
            'gudang_id'          => $request->gudang,
            'tanggal_pembayaran' => $tanggal_next,
            'keterangan'         => $request->keterangan,
        ]);
        $data_user = Auth::user();
        $pre_order_id = $penerimaan_barang->id;
        $data = $request->all();
        for ($i=0; $i <sizeof($data['pre_order_out_id']) ; $i++) { 
            $datas[$i]['pre_order_out_id'] = $data['pre_order_out_id'][$i];
            $datas[$i]['kode_barcode'] = $data['kode_barcode'][$i];
            $datas[$i]['nama_barang'] = $data['nama_barang'][$i];
            $datas[$i]['qty'] = $data['qty'][$i];
            $datas[$i]['qty_diterima'] = $data['qty_diterima'][$i];
            $datas[$i]['harga_po'] = $data['harga_po'][$i];
            $datas[$i]['barang_id'] = Barang::where('kode_barcode',$data['kode_barcode'][$i])->first();
        }

        foreach($datas as $pre_order){            
            $pre_order_in = PreOrderIn::create([
            'penerimaan_barang_id'  => $pre_order_id,
            'pre_order_out_id'      => $pre_order['pre_order_out_id'],
            'user_id'               => $data_user->id,
            'barang_id'             => $pre_order['barang_id']['id'],
            'qty'                   => $pre_order['qty_diterima'],
            'harga_po'              => $pre_order['harga_po'],
            ]);

            $pre_order_out = PreOrderOut::where('id',$pre_order['pre_order_out_id'])->first();
            $qty_diterima = $pre_order_out->qty_diterima + $pre_order['qty_diterima'];
            $pre_order_out_update = PreOrderOut::where('id',$pre_order['pre_order_out_id'])->update([
                'qty_diterima' => $qty_diterima
                ]);
        }

        $po_in = PreOrderIn::where('penerimaan_barang_id',$pre_order_id)->with('pre_order_out')->get();
        foreach ($po_in as $value) {
            if ($value->pre_order_out['qty_diterima'] >= $value->pre_order_out['qty']) {
                $delete_po_out = PreOrderOut::where('id',$value->pre_order_out_id)->delete();
            }

        }

        $po_out = PreOrderOut::where('pre_order_id',$request->pre_order_id)->with('pre_order')->count();
        // print_r(json_decode(json_encode($po_out)));exit;
        if ($po_out == 0) {
            $pre_order = PreOrder::where('id',$request->pre_order_id)->delete();
        }

        Alert::success('Penerimaan Barang Berhasil', 'Berhasil');

        return redirect('/po_invoice/'.$penerimaan_barang->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreOrderIn  $preOrderIn
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreOrderIn $preOrderIn)
    {
        //
    }

    public function showPo(Request $request)
    {
        $tanggal = date('Y-m-d');
        $suppliers = PreOrder::where('tanggal_estimasi','!=',null)->with('supplier')->groupBy('supplier_id')->get();
        $supplier_mine = PreOrder::where('tanggal_estimasi','!=',null)->with('supplier')->first();;
        $pre_orders = PreOrder::where('tanggal_estimasi','!=','null')->get();   
        // print_r(json_decode(json_encode($suppliers)));exit;
        $data = PreOrderOut::select('*',DB::raw('harga_po * qty as total_harga'),DB::raw('qty - qty_diterima as jumlah_pesan'))->where('pre_order_id',$request->kode_po)->with('pre_order','barang')->get();
        $total_harga = PreOrderOut::select(DB::raw('SUM(harga_po * qty) as total_po'))->where('pre_order_id',$request->kode_po)->first();
        $total_qty = PreOrderOut::select(DB::raw('SUM(qty - qty_diterima) as total_qty'))->where('pre_order_id',$request->kode_po)->first();
        $gudangs = Gudang::all();
        // print_r(json_decode(json_encode($data)));exit;
        return view('transaksi.pembelian.penerimaan_barang_show', compact('pre_orders', 'suppliers','data','total_harga','total_qty','supplier_mine','gudangs', 'tanggal'));
    }

    public function showSupplier(Request $request)
    {
       $tanggal = date('Y-m-d');
       $suppliers = PreOrder::where('tanggal_estimasi','!=',null)->with('supplier')->groupBy('supplier_id')->get();
       $supplier_mine = Supplier::where('id',$request->supplier_id)->first();
       $pre_orders = PreOrder::where('tanggal_estimasi','!=','null')->get();
       $data = DB::table('pre_order_outs')
                     ->select('pre_order_outs.*','pre_order_outs.id as pre_order_out_id')
                     ->addSelect('pre_orders.*',DB::raw('pre_order_outs.qty - pre_order_outs.qty_diterima as jumlah_pesan'))
                     ->addSelect('barangs.nama_barang','barangs.kode_barcode','barangs.id')
                     ->addSelect(DB::raw('harga_po * qty as total'))
                     ->leftJoin('pre_orders', 'pre_order_outs.pre_order_id', '=', 'pre_orders.id')
                     ->leftJoin('barangs', 'pre_order_outs.barang_id', '=', 'barangs.id')
                     ->where('pre_orders.supplier_id', '=', $request->supplier_id)
                     ->where('pre_orders.deleted_at','=',null)
                     ->get(); 
       $data_supplier = Supplier::where('id',$request->supplier_id)->with([
        'total_qty','pre_order_out' => function($query){
            $query->addSelect([DB::raw('*,harga_po * qty as total')]);
        }])->first();
       $total_qty = $supplier_mine->pre_order_out()->sum('qty');
       $total_harga = $data_supplier->pre_order_out->sum('total');
       $tanggal_preorder = PreOrder::where('supplier_id',$request->supplier_id)->first();
       return view('transaksi.pembelian.penerimaan_barang_show_supplier', compact('pre_orders', 'suppliers','total_harga','total_qty','supplier_mine','tanggal_preorder','data', 'tanggal'));
    }

    public function updatesupplier(Request $request)
    {
        $data_user = Auth::user();
        $data = $request->all();
        $tanggal = $request->tanggal_sj;
        $tanggal_next = date('Y-m-j', strtotime('+1 month', strtotime($tanggal)));
        $penerimaan_barang = PenerimaanBarang::create([
            'no_sj'              => $request->no_sj,
            'supplier_id'        => $request->supplier_id,
            'user_id'            => $data_user->id,
            'tanggal'            => $tanggal,
            'tanggal_pembayaran' => $tanggal_next,
            'keterangan'         => $request->keterangan,
            'status'             => 1
        ]);
        for ($i=0; $i <sizeof($data['pre_order_id']) ; $i++) {
            $datas[$i]['pre_order_id'] = $data['pre_order_id'][$i];
            $datas[$i]['pre_order_out_id'] = $data['pre_order_out_id'][$i];
            $datas[$i]['barang_id'] = $data['barang_id'][$i];
            $datas[$i]['qty'] = $data['qty'][$i];
            $datas[$i]['qty_diterima'] = $data['qty_diterima'][$i];
            $datas[$i]['harga_po'] = $data['harga_po'][$i];
        }
        foreach($datas as $pre_order){
            $qty_str = preg_replace("/[^0-9]/", "", $pre_order['qty_diterima']);
            $qty_diterima = (int) $qty_str;

            $harga_str = preg_replace("/[^0-9]/", "", $pre_order['harga_po']);
            $harga_po = (int) $harga_str;

            $pre_order_in = PreOrderIn::create([
            'penerimaan_barang_id'     => $penerimaan_barang->id,
            'pre_order_out_id'         => $pre_order['pre_order_out_id'],
            'user_id'                  => $data_user->id,
            'barang_id'                => $pre_order['barang_id'],
            'qty'                      => $qty_diterima,
            'harga_po'                 => $harga_po,
            ]);
        }
        $pre_order_out = PreOrderOut::where('id',$pre_order['pre_order_out_id'])->first();
            $qty_diterima = $pre_order_out->qty_diterima + $pre_order['qty_diterima'];
            $pre_order_out_update = PreOrderOut::where('id',$pre_order['pre_order_out_id'])->update([
                'qty_diterima' => $qty_diterima
                ]);

        $po_in = PreOrderIn::where('penerimaan_barang_id',$penerimaan_barang->id)->with('pre_order_out')->get();
        foreach ($po_in as $value) {
            if ($value->qty >= $value->pre_order_out['qty']) {
                $delete_po_out = PreOrderOut::where('id',$value->pre_order_out_id)->delete();
            }
        }

        foreach ($datas as $value) {
            $po_out = PreOrderOut::where('pre_order_id',$value['pre_order_id'])->with('pre_order')->count();
            if ($po_out == 0) {
                $pre_order = PreOrder::where('id',$value['pre_order_id'])->delete();
            }
        }

        return redirect('/po_invoice/'.$penerimaan_barang->id);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkNoSJ (Request $request)
    {
        // echo $request;exit();
        $no_sj = PenerimaanBarang::where('no_sj', $request->kode)->count();

        return Response::json($no_sj);
    }
}
