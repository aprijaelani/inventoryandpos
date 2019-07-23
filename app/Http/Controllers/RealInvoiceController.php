<?php

namespace App\Http\Controllers;

use App\PreOrder;
use App\Barang;
use App\Supplier;
use Illuminate\Http\Request;
use Response;
use App\Stock;
use App\NeracaKeuangan;
use App\NeracaPengeluaran;
use App\PreOrderIn;
use App\Gudang;
use DB;
use Alert;
use Illuminate\Support\Facades\Auth;

class RealInvoiceController extends Controller
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
            //Check Count PO By Account
            $checkPO = PreOrder::where('tanggal', date('Y-m-d'))->count();
            $generate_loop = str_pad($checkPO + 1, 4, 0, STR_PAD_LEFT);
            $generate = "RI".date('dmy').$data_user->id.$generate_loop;

            $suppliers = Supplier::all();    
            $gudangs = Gudang::all();
            $tanggal = date('Y-m-d');
            return view("transaksi.pembelian.real_invoice", compact('generate', 'suppliers', 'tanggal', 'gudangs'));
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
        //Check Count PO By Account
        $checkPO = PreOrder::where('tanggal', date('Y-m-d'))->count();
        $generate_loop = str_pad($checkPO + 1, 4, 0, STR_PAD_LEFT);
        $generate = "RI".date('dmy').$data_user->id.$generate_loop;

        $totals = $request->total_hargas;
        $tanggal = $request->tanggal;
        $tanggal_sekarang = date('Y-m-d');
        $supplier_id = $request->suppliers_id;
        $keterangan = $request->keterangan;
        $pembayaran = $request->metodePembayaran;
        $gudang = $request->gudang_id;
        // echo $pembayaran;exit();
        $data = $request->all();
        for ($i=0; $i <sizeof($data['id_barang']) ; $i++) { 
            $datas[$i]['id_barang'] = $data['id_barang'][$i];
            $datas[$i]['qty'] = $data['qty'][$i];
            $datas[$i]['harga'] = $data['harga'][$i];
        }

        $pre_orders = PreOrder::create([
            'kode_po'               => $generate,
            'supplier_id'           => $supplier_id,
            'user_id'               => $data_user->id,
            'gudang_id'             => $request->gudang_id,
            'tanggal'               => $tanggal,
            'tanggal_pembayaran'    => $tanggal,
            'keterangan'            => $keterangan,
            'pembayaran'            => $pembayaran,
            'status'                => 2,
        ]);

        foreach($datas as $real_invoice){
            $harga_str = preg_replace("/[^0-9]/", "", $real_invoice['harga']);
            $harga = (int) $harga_str;

            $qty_str = preg_replace("/[^0-9]/", "", $real_invoice['qty']);
            $qty = (int) $qty_str;
            if ($real_invoice['id_barang'] != null) {
                $pre_order_in = PreOrderIn::create([
                    'pre_order_id'      =>$pre_orders->id,
                    'barang_id'         =>$real_invoice['id_barang'],
                    'user_id'           =>$data_user->id,
                    'qty'               =>$qty,
                    'harga_po'          =>$harga,
                    ]);

                $restock = Stock::create([
                        'barang_id'     => $real_invoice['id_barang'],
                        'gudang_id'     => $gudang,
                        'qty'           => $real_invoice['qty'],
                        'last_qty'      => $real_invoice['qty'],
                        'harga_pokok'   => $harga,
                    ]);
            }
        }

        $last_total = NeracaKeuangan::where('tanggal', date('Y-m-d'))->orderBy('id', 'desc')->first();
        if ($pembayaran == 2) {
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $totals,
                    'last_total'        => 0,
                    'status'            => '2',
                    'status_pembayaran' => $pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $totals,
                    'keterangan'    => 'Pembelian Barang Langsung Kode Transaksi '.$generate,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $totals,
                    'last_total'        => $last_total->last_total,
                    'status'            => '2',
                    'status_pembayaran' => $pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $totals,
                    'keterangan'    => 'Pembelian Barang Langsung Kode Transaksi '.$generate,
                    ]);
            }
        }else{
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $totals,
                    'last_total'        => (int) 0 - $totals,
                    'status'            => '2',
                    'status_pembayaran' => $pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $totals,
                    'keterangan'    => 'Pembelian Barang Langsung Kode Transaksi '.$generate,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $totals,
                    'last_total'        => (int) $last_total->last_total - $totals,
                    'status'            => '2',
                    'status_pembayaran' => $pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $totals,
                    'keterangan'    => 'Pembelian Barang Langsung Kode Transaksi '.$generate,
                    ]);
            }
        }
        Alert::success('Pembelian Langsung Berhasil', 'Berhasil');
        return redirect('/real_invoice');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PreOrder  $preOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PreOrder $preOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreOrder  $preOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // echo $id;exit();
        $barangs = Barang::all();
        $preOrders = PreOrder::where('id', $id)->with('pre_order_out')->first();
        $check_PreOrderIn = PreOrderIn::where('pre_order_id', $id)->count();
            // echo $id;exit();    
        if ($check_PreOrderIn > 0) {
            $date = $preOrders->tanggal;
            $suppliers = Supplier::where('id', $preOrders->supplier_id)->get(); 
            // echo $suppliers;exit(); 
        }else{
            $date = date('Y-m-d');
            $suppliers = Supplier::all();
        }
        // echo $preOrders;exit();
        // echo $preOrders->supplier_id;exit();
        $pre_order_ins = PreOrderIn::select('*',DB::raw('harga_po * qty as total_sales'))->where('pre_order_id',$id)->with('user','barang')->get();
        $total_harga = PreOrderIn::select(DB::raw('SUM(harga_po * qty) as total_po'))->where('pre_order_id',$id)->first();
        $total_qty = PreOrderIn::select(DB::raw('SUM(qty) as total_qty'))->where('pre_order_id',$id)->first();
        return view('transaksi.pembelian.real_invoice',compact('suppliers','barangs','date','preOrders','pre_order_ins','total_harga','total_qty','date_estimasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreOrder  $preOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // echo $id;
        $data_user = Auth::user();
        $check_PreOrderIn = PreOrderIn::where('pre_order_id', $id)->count();
        // echo $check_PreOrderOut;exit();
        if ($check_PreOrderIn > 0) {
            $pre_order = PreOrder::where('id',$id)->update([
                'supplier_id' => $request->supplier_id_hidden,
                'tanggal' => $request->tanggal,
                'user_id'   => $data_user->id,
                'status' => '2'
                ]);       
        }else{
            $pre_order = PreOrder::where('id',$id)->update([
            'supplier_id' => $request->supplier_id,
            'tanggal' => $request->tanggal,
            'user_id'   => $data_user->id,
                'status' => '2'
            ]);
        }

        $validasiItem = PreOrderIn::where('pre_order_id', $id)->where('barang_id',$request->kode_barcode)->first();
        if ($validasiItem != null) {
            // $total = (int)$request->qty + $validasiItem->qty;
            // echo $total;exit();
            $pre_order_out = PreOrderIn::where('pre_order_id', $id)->where('barang_id',$request->kode_barcode)->update([
                'pre_order_id' => $id,
                'barang_id'    => $request->kode_barcode,
                'qty'          => (int)$request->qty + $validasiItem->qty,
                'harga_po'     => $request->harga_po,
                'user_id'      => $data_user->id
                ]); 
        }else{
            $pre_order_out = PreOrderIn::create([
                'pre_order_id' => $id,
                'barang_id'    => $request->kode_barcode,
                'qty'          => $request->qty,
                'harga_po'     => $request->harga_po,
                'user_id'      => $data_user->id
                ]);    
        }
        // if ($request->tanggal_estimasi == null || $request->tanggal_estimasi='0000-00-00') {
        //     return redirect("/po/".$id."/edit");
        // }
        return redirect("/real_invoice/");
    }

    public function updateInvoice(Request $request, $id)
    {
        // echo $request->metodePembayaran;exit();
        $data_user = Auth::user();
        $data = PreOrder::where('id', $id)->first();
        $pre_order = PreOrder::where('id',$id)->update([
            'tanggal_pembayaran' => $data->tanggal,
            'keterangan' => $request->keterangan,
            'pembayaran' => $request->metodePembayaran,
            'user_id'   => $data_user->id,
                'status' => '2'
            ]);

        $total = PreOrderIn::select(DB::raw('SUM(harga_po * qty) as total_harga'))->where('pre_order_id',$id)->first();
        $last_total = NeracaKeuangan::orderBy('id','desc')->first();
        $neraca_keuangan = NeracaKeuangan::create([
            'tanggal' => date('Y-m-d'),
            'total' => $total->total_harga,
            'last_total' => (int) $last_total->last_total + $total->total_harga,
            'status' => '2',
            'status_pembayaran' => $request->metodePembayaran,
            'user_id' => $data_user->id
            ]);

        NeracaPemasukan::create([
            'neraca_id' => $neraca_keuangan->id,
            'total' => $total->total_harga,
            'keterangan' => 'Pembelian Barang'
            ]);

        $pre_order_ins = PreOrderIn::where('pre_order_id', $id)->get();
        foreach ($pre_order_ins as $key => $pre_order_in) {
            Stock::create([
                'barang_id' => $pre_order_in->barang_id,
                'gudang_id' => '1',
                'qty' => $pre_order_in->qty,
                'harga_pokok' => $pre_order_in->harga_po
                ]);   
        }
        return redirect("/real_invoice");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreOrder  $preOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // echo $request->id;exit();
        $preorderin = PreOrderIn::where('id', $request->id)->delete();
        return redirect("/real_invoice");
    }

    public function buatrp($angka)
    {
        $jadi = "Rp " . number_format($angka,2,',','.');
        return $jadi;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function kodebarang (Request $request)
    {
        // echo $request;exit();
        $kodebarang = Barang::limit(15)->where(function ($query){
            $query->where('kode_barcode', 'like', '%' .request('id'). '%')->orWhere('nama_barang', 'like', '%' .request('id'). '%');
        })->with('price_list')->get();

        return Response::json($kodebarang);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function supplier (Request $request)
    {
        // echo $request;exit();
        $supplier = Supplier::limit(15)->where('nama', 'like', '%'.request('supplier_id'). '%')->get();

        return Response::json($supplier);
    }

    public function nama_barang ($id)
    {
        $kodebarang = Barang::where('id',$id)->get();
        return Response::json($kodebarang);
    }

    public function harga_pokok ($id)
    {
        $kodebarang = Barang::where('id',$id)->get();
        return Response::json($kodebarang);
    }
}
