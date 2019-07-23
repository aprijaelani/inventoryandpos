<?php

namespace App\Http\Controllers;

use App\PreOrder;
use App\PreOrderOut;
use App\Supplier;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreOrderListController extends Controller
{
    public function index(){
        $data_user = Auth::user();
        if ($data_user) {
            $preOrders = DB::table('pre_orders')
                         ->select('pre_orders.*','suppliers.nama as nama_supplier', 'users.nama as nama_user')
                         ->addSelect(DB::raw('SUM((pre_order_outs.qty - pre_order_outs.qty_diterima) * pre_order_outs.harga_po) as total'))
                         ->leftjoin('pre_order_outs', 'pre_orders.id', '=', 'pre_order_outs.pre_order_id')
                         ->leftjoin('suppliers', 'pre_orders.supplier_id', '=', 'suppliers.id')
                         ->leftjoin('users', 'pre_orders.user_id', '=', 'users.id')
                         ->where('pre_orders.status', '=', '1')
                         ->where('pre_orders.deleted_at', '=', null)
                         ->groupBy('pre_orders.id')
                         ->paginate(10);

            return view('transaksi.pembelian.list_po', compact('preOrders'));
        }else{
            return redirect('/');
        }
            
    }

	/**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreOrder $preOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PreOrder $preOrder, $id)
    {
    	$PreOrder = PreOrder::where('id', $id)->with('supplier')->first();
    	$PreOrderOuts = PreOrderOut::where('pre_order_id', $id)->with('barang')->get();
        $total_harga = PreOrderOut::select(DB::raw('SUM(harga_po * qty) as total_po'))->where('pre_order_id',$id)->first();
        $total_qty = PreOrderOut::select(DB::raw('SUM(qty) as total_qty'))->where('pre_order_id',$id)->first();
    	// echo $PreOrder;exit();
        return view('transaksi.pembelian.detail_po', compact('PreOrder', 'PreOrderOuts', 'total_harga', 'total_qty'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $preOrders = DB::table('pre_orders')
                     ->select('pre_orders.*','suppliers.nama as nama_supplier', 'users.nama as nama_user')
                     ->addSelect(DB::raw('SUM((pre_order_outs.qty - pre_order_outs.qty_diterima) * pre_order_outs.harga_po) as total'))
                     ->leftjoin('pre_order_outs', 'pre_orders.id', '=', 'pre_order_outs.pre_order_id')
                     ->leftjoin('suppliers', 'pre_orders.supplier_id', '=', 'suppliers.id')
                     ->leftjoin('users', 'pre_orders.user_id', '=', 'users.id')
                     ->where('pre_orders.status', '=', '1')
                     ->where('pre_orders.deleted_at', '=', null)
                     ->where(function ($query) use ($search){
                            $query  ->Where('pre_orders.kode_po', 'like', '%' .$search. '%')
                                    ->orWhere('suppliers.nama', 'like', '%' .$search. '%');
                         })
                     ->groupBy('pre_orders.id')
                     ->paginate(10);
                     
        return view('transaksi.pembelian.list_po', compact('preOrders'));
    }
}
