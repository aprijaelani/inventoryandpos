<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\PreOrder;
use App\Supplier;
use App\PreOrderIn;
use App\PreOrderOut;
use App\NeracaKeuangan;
use App\NeracaPengeluaran;
use Illuminate\Http\Request;
use DB;
use App\PenerimaanBarang;
use App\Stock;
use Illuminate\Support\Facades\Auth;
use Alert;

class InvoiceController extends Controller
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
            $pre_orders = PenerimaanBarang::where('pembayaran','=',null)->get();
            $suppliers = Supplier::all();
            return view('transaksi.pembelian.po_invoice', compact('pre_orders', 'suppliers','no_sj','pre_orders_date','pre_order_in','pemasok','jumlah_barang','total_harga','total_barang'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($penerimaan_barang)
    {
        $data_user = Auth::user();
        if ($data_user) {
            $suppliers = Supplier::all();
            $search_no_sj = PenerimaanBarang::where('pembayaran',null)->get();
            $pre_order_in = PreOrderIn::addSelect(DB::raw('*,harga_po * qty as total'))->where('penerimaan_barang_id',$penerimaan_barang)->with('barang')->get();
            $pre_orders_date = PreOrderIn::where('penerimaan_barang_id',$penerimaan_barang)->with('pre_order')->first();
            $pre_orders = PreOrderIn::where('penerimaan_barang_id',$pre_orders_date->penerimaan_barang)->with('pre_order')->get();
            $no_sj = PenerimaanBarang::where('id',$penerimaan_barang)->with('supplier')->first();
            $tanggal_pre_order = PreOrder::withTrashed()->where('kode_po',$no_sj['kode_po'])->first();
            $pemasok = Supplier::withTrashed()->where('id',$tanggal_pre_order['supplier_id'])->first();
            $total_barang = $pre_order_in->sum('qty');
            $total_harga = $pre_order_in->sum('total');
            $jumlah_barang = $pre_order_in->count('id');
            return view('transaksi.pembelian.po_invoice_view', compact('tanggal_pre_order','search_no_sj','pre_orders','no_sj', 'suppliers','pre_orders_date','pre_order_in','pemasok','jumlah_barang','total_harga','total_barang','penerimaan_barang'));
        }else{
            return redirect('/');
        }
    }

    public function createInvoice(Request $request)
    {
        $data_user = Auth::user();
        if ($data_user) {
            $no_sj = PenerimaanBarang::where('no_sj',$request->no_sj)->with('supplier')->first();
            $suppliers = Supplier::all();
            $search_no_sj = PenerimaanBarang::where('pembayaran',null)->get();
            $pre_order_in = PreOrderIn::addSelect(DB::raw('*,harga_po * qty as total'))->where('penerimaan_barang_id',$no_sj->id)->with('barang')->get();
            // print_r(json_decode(json_encode($pre_order_in)));exit;
            $pre_orders_date = PreOrderIn::where('penerimaan_barang_id',$no_sj->id)->with('pre_order')->first();
            $pre_orders = PreOrderIn::where('penerimaan_barang_id',$pre_orders_date->penerimaan_barang)->with('pre_order')->get();
            $tanggal_pre_order = PreOrder::withTrashed()->where('kode_po',$no_sj['kode_po'])->first();
            $pemasok = Supplier::withTrashed()->where('id',$tanggal_pre_order['supplier_id'])->first();
            $total_barang = $pre_order_in->sum('qty');
            $total_harga = $pre_order_in->sum('total');
            $jumlah_barang = $pre_order_in->count('id');
            return view('transaksi.pembelian.po_invoice_open', compact('tanggal_pre_order','search_no_sj','pre_orders','no_sj', 'suppliers','pre_orders_date','pre_order_in','pemasok','jumlah_barang','total_harga','total_barang','penerimaan_barang'));
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
        $data_user = Auth::user();
        $total_harga = array_sum($request->total_harga);
        $penerimaan_barang = PenerimaanBarang::where('id',$request->id_penerimaan)->update([
            'pembayaran'        => $request->metode_pembayaran,
        ]);
        $invoices = Invoice::create([
            'penerimaan_barang_id'  => $request->id_penerimaan,
            'total_harga'           => $total_harga,
            'keterangan'            => $request->keterangan,
            ]);
        $data = $request->all();
        for ($i=0; $i <sizeof($data['total_harga']) ; $i++) { 
            $datas[$i]['pre_order_in_id'] = $data['pre_order_in_id'][$i];
            $datas[$i]['total_harga'] = $data['total_harga'][$i];
            $datas[$i]['pre_order_out_id'] = PreOrderIn::select('pre_order_out_id')->where('id',$data['pre_order_in_id'][$i])->first();
        }
        foreach($datas as $data_loop){
            $pre_order_in = PreOrderIn::where('id',$data_loop['pre_order_in_id'])->with('penerimaan_barang')->first();
            // $pre_order_out = PreOrderOut::withTrashed()->where('id',$data_loop['pre_order_out_id']['pre_order_out_id'])->with('pre_order','pre_order_in')->first();
            $stocks = Stock::create([
                'barang_id'     => $pre_order_in->barang_id,
                'gudang_id'     => $pre_order_in->penerimaan_barang['gudang_id'],
                'qty'           => $pre_order_in->qty,
                'last_qty'      => $pre_order_in->qty,
                'harga_pokok'   => $pre_order_in->harga_po
            ]);
        }

        $kode_po = PenerimaanBarang::where('id', $request->id_penerimaan)->first();
        $last_total = NeracaKeuangan::where('tanggal', date('Y-m-d'))->orderBy('id', 'desc')->first();
        if ($request->metode_pembayaran == 2) {
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => date('Y-m-d'),
                    'total'             => $total_harga,
                    'last_total'        => 0,
                    'status'            => '2',
                    'status_pembayaran' => $request->metode_pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => date('Y-m-d'),
                    'total'         => $total_harga,
                    'keterangan'    => 'Pembelian Barang Pre Order Kode Transaksi '.$kode_po->kode_po,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => date('Y-m-d'),
                    'total'             => $total_harga,
                    'last_total'        => $last_total->last_total,
                    'status'            => '2',
                    'status_pembayaran' => $request->metode_pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => date('Y-m-d'),
                    'total'         => $total_harga,
                    'keterangan'    => 'Pembelian Barang Pre Order Kode Transaksi '.$kode_po->kode_po,
                    ]);
            }
        }else{
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => date('Y-m-d'),
                    'total'             => $total_harga,
                    'last_total'        => (int) 0 - $total_harga,
                    'status'            => '2',
                    'status_pembayaran' => $request->metode_pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => date('Y-m-d'),
                    'total'         => $total_harga,
                    'keterangan'    => 'Pembelian Barang Pre Order Kode Transaksi '.$kode_po->kode_po,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => date('Y-m-d'),
                    'total'             => $total_harga,
                    'last_total'        => (int) $last_total->last_total - $total_harga,
                    'status'            => '2',
                    'status_pembayaran' => $request->metode_pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPengeluaran::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => date('Y-m-d'),
                    'total'         => $total_harga,
                    'keterangan'    => 'Pembelian Barang Pre Order Kode Transaksi '.$kode_po->kode_po,
                    ]);
            }
        }
        Alert::success('Invoice Berhasil', 'Berhasil');
        return redirect('/po_invoice/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
