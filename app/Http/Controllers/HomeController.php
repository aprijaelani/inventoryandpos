<?php

namespace App\Http\Controllers;

use App\PreOrder;
use App\PreOrderOut;
use App\NeracaKeuangan;
use App\WorkOrders;
use App\Stock;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $class_active = 'home';
        $data_user = Auth::user();
        if ($data_user) {
            $date = date('Y-m-d');
            $preOrders = DB::table('pre_orders')
                         ->select('pre_orders.*','suppliers.nama as nama_supplier', 'users.nama as nama_user')
                         ->addSelect(DB::raw('SUM((pre_order_outs.qty - pre_order_outs.qty_diterima) * pre_order_outs.harga_po) as total'))
                         ->leftjoin('pre_order_outs', 'pre_orders.id', '=', 'pre_order_outs.pre_order_id')
                         ->leftjoin('suppliers', 'pre_orders.supplier_id', '=', 'suppliers.id')
                         ->leftjoin('users', 'pre_orders.user_id', '=', 'users.id')
                         ->where('pre_orders.status', '=', '1')
                         ->where('pre_orders.tanggal_estimasi', '=', date('Y-m-d'))
                         ->where('pre_orders.deleted_at', '=', null)
                         ->groupBy('pre_orders.id')
                         ->paginate(10);
            $check = NeracaKeuangan::where('tanggal', date('Y-m-d'))->where('status','3')->orderBy('id','asc')->first();
            $count_preorder = PreOrder::where('status', '1')->where('deleted_at', null)->count();
            $count_salesinvoice = WorkOrders::where('status', '2')->where('tanggal', date('Y-m-d'))->count();
            $count_penjualan = WorkOrders::where('tanggal', date('Y-m-d'))->count();
            $count_workorder = WorkOrders::where('status', '1')->where('tanggal', date('Y-m-d'))->where('tanggal_pengambilan', null)->count();
            return view('master.home', compact('preOrders', 'check', 'count_preorder', 'count_salesinvoice', 'count_penjualan', 'count_workorder','class_active'));
        }else{
            return redirect('/');
        }
    }
}
