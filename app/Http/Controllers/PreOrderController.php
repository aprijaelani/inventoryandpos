<?php

namespace App\Http\Controllers;

use App\PreOrder;
use App\Barang;
use App\Supplier;
use Illuminate\Http\Request;
use Response;
use App\Stock;
use App\PreOrderOut;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Gudang;
use Alert;

class PreOrderController extends Controller
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
            $generate = "PO".date('dmy').$data_user->id.$generate_loop;

            $suppliers = Supplier::all();    
            $gudangs = Gudang::all();
            $tanggal = date('Y-m-d');
            $date_estimasi = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
            return view("transaksi.pembelian.po", compact('generate', 'suppliers', 'tanggal', 'gudangs', 'date_estimasi'));
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
        $generate = "PO".date('dmy').$data_user->id.$generate_loop;

        $totals = $request->total_hargas;
        $tanggal = $request->tanggal;
        $tanggal_sekarang = date('Y-m-d');
        $supplier_id = $request->suppliers_id;
        $keterangan = $request->keterangan;
        $pembayaran = $request->metodePembayaran;
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
            'tanggal'               => $tanggal,
            'keterangan'            => $keterangan,
            'tanggal_estimasi'      => $request->tanggal_estimasi,
            'status'                => 1,
        ]);

        foreach($datas as $pre_order_out){
            $harga_str = preg_replace("/[^0-9]/", "", $pre_order_out['harga']);
            $harga = (int) $harga_str;

            $qty_str = preg_replace("/[^0-9]/", "", $pre_order_out['qty']);
            $qty = (int) $qty_str;
            if ($pre_order_out['id_barang'] != null) {
                $pre_order_in = PreOrderOut::create([
                    'pre_order_id'      =>$pre_orders->id,
                    'barang_id'         =>$pre_order_out['id_barang'],
                    'user_id'           =>$data_user->id,
                    'qty'               =>$qty,
                    'harga_po'          =>$harga,
                    ]);
            }
        }
        Alert::success('Pre Order Berhasil', 'Berhasil');
        return redirect('/po');
        // $pre_order=PreOrder::create([
        //     'supplier_id' => request('supplier_id'),
        //     'tanggal'     => request('tanggal'),
        //     ]);


        // $pre_order_id = $pre_order->id;
        // $kodebarang = Barang::where('kode_barcode',$request->kode_barcode)->get();

        // $check = PreOrder::where('id',$pre_order_id)->get();
        // if ($check['keterangan']==null) {
        //     return redirect("/barang/".$pre_order_id."/edit");
        // }else{
        // $pre_order_ins = PreOrderOut::create([
        //     'pre_order_id' => $pre_order_id,
        //     'barang_id'    => $barang_id,
        //     'qty'          => $request->qty,
        //     'harga_po'     => $request->harga_pokok,
        //     ]);
        // }

        // return redirect("/barang/".$pre_order_id."/edit");
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
        $data_user = Auth::user();
        if ($data_user) {
            $barangs = Barang::all();
            $preOrders = PreOrder::where('id', $id)->with('pre_order_out')->first();
            $check_PreOrderOut = PreOrderOut::where('pre_order_id', $id)->count();
            if ($check_PreOrderOut > 0) {
                $suppliers = Supplier::where('id', $preOrders->supplier_id)->get(); 
            }else{
                $suppliers = Supplier::all();
            }
            $date = $preOrders->tanggal;
            if ($date != '') {
                $now = strtotime($preOrders->tanggal);
                $date_estimasi = date('Y-m-d', strtotime('+1 month', $now));
            }else{
                $date = date('Y-m-d');
                $date_estimasi = '';
            }
            $pre_order_outs = PreOrderOut::select('*',DB::raw('harga_po * qty as total_sales'))->where('pre_order_id',$id)->with('user','barang')->get();
            $total_harga = PreOrderOut::select(DB::raw('SUM(harga_po * qty) as total_po'))->where('pre_order_id',$id)->first();
            $total_qty = PreOrderOut::select(DB::raw('SUM(qty) as total_qty'))->where('pre_order_id',$id)->first();
            return view('transaksi.pembelian.po',compact('suppliers','barangs','date','preOrders','pre_order_outs','total_harga','total_qty','date_estimasi'));
        }else{
            return redirect('/');
        }
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
        $data_user = Auth::user();
        if ($data_user) {
            $check_PreOrderOut = PreOrderOut::where('pre_order_id', $id)->count();
            if ($check_PreOrderOut > 0) {
                $pre_order = PreOrder::where('id',$id)->update([
                    'supplier_id' => $request->supplier_id_hidden,
                    'tanggal' => $request->tanggal,
                    'user_id'   => $data_user->id,
                    'status'   => '1'
                    ]);       
            }else{
                $pre_order = PreOrder::where('id',$id)->update([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'user_id'   => $data_user->id,
                    'status'   => '1'
                ]);
            }

            $validasiItem = PreOrderOut::where('pre_order_id', $id)->where('barang_id',$request->kode_barcode)->first();
            if ($validasiItem != null) {
                $pre_order_out = PreOrderOut::where('pre_order_id', $id)->where('barang_id',$request->kode_barcode)->update([
                    'pre_order_id' => $id,
                    'barang_id'    => $request->kode_barcode,
                    'qty'          => (int)$request->qty + $validasiItem->qty,
                    'harga_po'     => $request->harga_po,
                    'user_id'      => $data_user->id
                    ]); 
            }else{
                $pre_order_out = PreOrderOut::create([
                    'pre_order_id' => $id,
                    'barang_id'    => $request->kode_barcode,
                    'qty'          => $request->qty,
                    'harga_po'     => $request->harga_po,
                    'user_id'      => $data_user->id
                    ]);    
            }

            Alert::success('Pre Order Berhasil', 'Berhasil');
            return redirect("/po/");
        }else{
            return redirect('/');
        }
        
    }

    public function updatePreOrder(Request $request, $id)
    {
        $data_user = Auth::user();
        if ($data_user) {
            if ($data_user) {
                $pre_order = PreOrder::where('id',$id)->update([
                'tanggal'   => date('Y-m-d'),
                'tanggal_estimasi' => $request->tanggal_estimasi,
                'keterangan' => $request->keterangan,
                'user_id'   => $data_user->id,
                    'status'   => '1'
                ]);
                return redirect("/po/");
            }else{
                return redirect('/');
            }
            $pre_order = PreOrder::where('id',$id)->update([
                'tanggal'   => date('Y-m-d'),
                'tanggal_estimasi' => $request->tanggal_estimasi,
                'keterangan' => $request->keterangan,
                'user_id'   => $data_user->id,
                    'status'   => '1'
                ]);
            return redirect("/po/");
        }else{
            return redirect('/');
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreOrder  $preOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $preorderouts = PreOrderOut::where('id', $request->id)->delete();
        return redirect("/po");
    }

    public function buatrp($angka)
    {
        $jadi = "Rp " . number_format($angka,2,',','.');
        return $jadi;
    }

    public function kodebarang ($id)
    {
        $kodebarang = Barang::where('supplier_id',$id)->get();
        return Response::json($kodebarang);
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
