<?php

namespace App\Http\Controllers;

use App\WorkOrders;
use App\WorkOrdersOut;
use App\Barang;
use App\StockOut;
use App\Stock;
use App\User;
use App\NeracaKeuangan;
use App\NeracaPemasukan;
use Response;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class WorkOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tanggal = date('Y-m-d');
        $now = strtotime(date('Y-m-d'));
        $date_estimasi = date('Y-m-d', strtotime('+1 day', $now));
        $check_wo = WorkOrders::where('tanggal', date('Y-m-d'))->count();
        $generate_loop = str_pad($check_wo + 1, 4, 0, STR_PAD_LEFT);
        $data_user = Auth::user();
        $generate = "WO".date('dmy').$data_user['id'].$generate_loop;
        $employees = Employee::all();
        return view('transaksi.penjualan.wo', compact('employees','generate','tanggal', 'date_estimasi'));
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
        $check_wo = WorkOrders::where('tanggal', date('Y-m-d'))->count();
        $generate_loop = str_pad($check_wo + 1, 4, 0, STR_PAD_LEFT);
        $data_user = Auth::user();
        $kode_wo = "WO".date('dmy').$data_user['id'].$generate_loop;
        $tanggal = date('Y-m-d');
        $tanggal_estimasi = $request->tanggal_estimasi;
        $employee_id = $request->employee_id;
        $data = $request->all();
        for ($i=0; $i <sizeof($data['id_barang']) ; $i++) { 
            $datas[$i]['id_barang'] = $data['id_barang'][$i];
            $datas[$i]['qty'] = $data['qty'][$i];
            $datas[$i]['harga'] = $data['harga'][$i];
        }

        $discount_str = preg_replace("/[^0-9]/", "", $request->discount);
        $discount = (int) $discount_str;


        $pihak3_str = preg_replace("/[^0-9]/", "", $request->pihak_ketiga);
        $pihak3 = (int) $pihak3_str;

        $dp_str = preg_replace("/[^0-9]/", "", $request->dp);
        $dp = (int) $dp_str;
        $work_orders = WorkOrders::create([
            'employee_id'      => $employee_id,
            'kode_wo'          => $kode_wo,
            'no_nota'          => $request->no_nota,
            'tanggal'          => $request->tanggal,
            'tanggal_estimasi' => $tanggal_estimasi,
            'status'           => 1,
            'keterangan'       => $request->keterangan,
            'pembayaran'       => $request->pembayaran,
            'discount'         => $discount,
            'pihak_ketiga'     => $pihak3,
            'dp'               => $dp,
        ]);

        foreach($datas as $work_order){
            $harga_str = preg_replace("/[^0-9]/", "", $work_order['harga']);
            $harga = (int) $harga_str;
            if ($work_order['id_barang'] != null) {
                $work_order_out = WorkordersOut::create([
                    'work_order_id'     =>$work_orders->id,
                    'sales_id'          =>$employee_id,
                    'barang_id'         =>$work_order['id_barang'],
                    'qty'               =>$work_order['qty'],
                    'harga_wo'          =>$harga,
                    ]);

                $check_qty = $work_order['qty'];
                while ($check_qty > 0) {
                    $stock = Stock::orderBy('created_at', 'asc')->where('barang_id', $work_order['id_barang'])->where('last_qty', '>', '0')->where('gudang_id', '1')->first();
                    // echo $stock;exit();
                    if ($stock['last_qty'] < $check_qty) {
                        $sisa_qty = (int) $check_qty - $stock['last_qty'];
                        $last_stock = 0;
                    }else{
                        $last_stock = (int) $stock['last_qty'] - $check_qty;
                        $sisa_qty = 0;
                    }
                    
                    Stock::where('id', $stock['id'])->update([
                        'last_qty' => $last_stock,
                        ]);

                    StockOut::create([
                        'stock_id' => $stock['id'],
                        'invoice_id' => $work_orders->id,
                        'qty' => (int) $check_qty - $sisa_qty,
                        ]);

                    $check_qty = $sisa_qty;
                }
            }

            // exit;
        }

        $last_total = NeracaKeuangan::where('tanggal', date('Y-m-d'))->orderBy('id', 'desc')->first();
        if ($request->pembayaran == 2) {
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $dp,
                    'last_total'        => 0,
                    'status'            => '1',
                    'status_pembayaran' => $request->pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pemasukan = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $dp,
                    'keterangan'    => 'Penjualan Barang WO Kode Transaksi '.$kode_wo,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $dp,
                    'last_total'        => $last_total->last_total,
                    'status'            => '1',
                    'status_pembayaran' => $request->pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pemasukan = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $dp,
                    'keterangan'    => 'Penjualan Barang WO Kode Transaksi '.$kode_wo,
                    ]);
            }
        }else{
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $dp,
                    'last_total'        => (int) 0 + $dp,
                    'status'            => '1',
                    'status_pembayaran' => $request->pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pemasukan = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $dp,
                    'keterangan'    => 'Penjualan Barang WO Kode Transaksi '.$kode_wo,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $dp,
                    'last_total'        => (int) $last_total->last_total + $dp,
                    'status'            => '1',
                    'status_pembayaran' => $request->pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pemasukan = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $dp,
                    'keterangan'    => 'Penjualan Barang WO Kode Transaksi '.$kode_wo,
                    ]);
            }
        }
        return redirect('/wo');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkOrders  $workOrders
     * @return \Illuminate\Http\Response
     */
    public function show(WorkOrders $workOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkOrders  $workOrders
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkOrders $workOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkOrders  $workOrders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkOrders $workOrders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkOrders  $workOrders
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkOrders $workOrders)
    {
        //
    }

    public function kodebarang ($id)
    {
        // echo $id;exit();

        $kodebarang = DB::table('barangs')
                     ->select('barangs.*','stocks.*', 'price_lists.max_harga', 'price_lists.min_harga')
                     ->addSelect(DB::raw('SUM(stocks.last_qty) as total'))
                     ->join('stocks', 'barangs.id', '=', 'stocks.barang_id')
                     ->leftjoin('price_lists', 'barangs.id', '=', 'price_lists.barang_id')
                     ->where(function ($query) use ($id){
                        $query  ->Where('barangs.kode_barcode', 'like', '%' .$id. '%')
                                ->orWhere('barangs.nama_barang', 'like', '%' .$id. '%');
                     })
                     ->where('stocks.gudang_id', '=', '1')
                     ->groupBy('stocks.barang_id')
                     ->offset(0)
                     ->limit(15)
                     ->get();
        // $kodebarang = Barang::limit(15)->orWhere('kode_barcode', 'like', '%' .$id. '%')->orWhere('nama_barang', 'like', '%' .$id. '%')->with('price_list', 'stock')->get();

        // print_r($kodebarang->toArray());exit;

        // $kodebarangArray = $kodebarang->toArray();
        // echo "<ul id='country-list'>";
        
        // foreach($kodebarangArray as $kode) {
        // echo "<li onClick='selectCountry(".$kode['kode_barcode'].");'> ".$kode['kode_barcode']."</li>";
        // }
        // echo "</ul>";
        // echo $kodebarang;exit();
        return Response::json($kodebarang);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkHarga (Request $request)
    {
        // echo $request;exit();
        $userCheck = User::
                where('username', $request->username)->
                where('group_id', '!=', '3')->
                first();
        // echo $request->password;
        if ($userCheck) {
            if (password_verify ( $request->password , $userCheck->password )) {
                $response = 1;
            }else{
                $response = 0;
            }
        }else{
            $response = 0;
        }
        
        // echo $response;exit();
        return Response::json($response);
    }

    public function daftar_wo ()
    {
        $work_orders = DB::table('work_orders')
                     ->select('work_orders.*','employees.*',DB::raw('(CASE WHEN work_orders.pembayaran = 1 THEN "Cash" ELSE "Non Cash" END) AS status_pembayaran'),DB::raw('(CASE WHEN work_orders.status = 1 THEN "WO" ELSE "Langsung" END) AS status_wo'))
                     ->addSelect(DB::raw('SUM(work_orders_outs.qty * work_orders_outs.harga_wo) as total'))
                     ->leftJoin('employees', 'work_orders.employee_id', '=', 'employees.id')
                     ->leftJoin('work_orders_outs', 'work_orders_outs.work_order_id', '=', 'work_orders.id')
                     ->where('work_orders.tanggal_pengambilan','=',null)
                     ->where('work_orders.status','=', '1')
                     ->groupBy('work_orders.kode_wo')
                     ->paginate(15);
        return view('transaksi.penjualan.daftar_wo', compact('work_orders'));
    }

    public function list_penjualan ()
    {
        $sales = Employee::all();
        $work_orders = DB::table('work_orders')
                     ->select('work_orders.*','employees.nama',DB::raw('(CASE WHEN work_orders.pembayaran = 1 THEN "Cash" ELSE "Non Cash" END) AS status_pembayaran'),DB::raw('(CASE WHEN work_orders.status = 1 THEN "WO" ELSE "Langsung" END) AS status_wo'))
                     ->addSelect(DB::raw('(CASE WHEN work_orders.status = 1 THEN ('.DB::raw('SUM(work_orders_outs.qty * work_orders_outs.harga_wo)').') ELSE ('.DB::raw('SUM(work_orders_ins.qty * work_orders_ins.harga_wo)').') END) AS total'))
                     ->leftJoin('employees', 'work_orders.employee_id', '=', 'employees.id')
                     ->leftJoin('work_orders_ins', 'work_orders_ins.work_order_id', '=', 'work_orders.id')
                     ->leftJoin('work_orders_outs', 'work_orders_outs.work_order_id', '=', 'work_orders.id')
                     ->where('work_orders.tanggal','=', date('Y-m-d'))
                     ->where('work_orders.deleted_at','=',null)
                     ->groupBy(DB::raw('work_orders.created_at ASC WITH ROLLUP'))
                     ->paginate(15);
        $tanggal = date('Y-m-d');
        // $total = DB::table('work_orders')
        //              ->addSelect(DB::raw('(CASE WHEN work_orders.status = 1 THEN ('.DB::raw('SUM(work_orders_outs.qty * work_orders_outs.harga_wo)').') ELSE ('.DB::raw('SUM(work_orders_ins.qty * work_orders_ins.harga_wo)').') END) AS total'))
        //              ->leftJoin('work_orders_ins', 'work_orders_ins.work_order_id', '=', 'work_orders.id')
        //              ->leftJoin('work_orders_outs', 'work_orders_outs.work_order_id', '=', 'work_orders.id')
        //              ->where('work_orders.deleted_at','=',null)
        //              ->groupBy(DB::raw('work_orders.kode_wo WITH ROLLUP'))
        //              ->latest('total')
        //              ->first();
        // print_r(json_decode(json_encode($work_orders)));exit;
        // $work_orders = WorkOrders::where('tanggal_pengambilan', '!=' ,null)->with('employee')->paginate(10);
        return view('transaksi.penjualan.list_penjualan', compact('work_orders','sales','tanggal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter_list(Request $request)
    {
        // echo $request;exit();
        $sales = Employee::all();
        if ($request->employee_id == 0) {
            $work_orders = DB::table('work_orders')
                     ->select('work_orders.*','work_orders_ins.*','employees.*',DB::raw('(CASE WHEN work_orders.pembayaran = 1 THEN "Cash" ELSE "Non Cash" END) AS status_pembayaran'),DB::raw('(CASE WHEN work_orders.status = 1 THEN "WO" ELSE "Langsung" END) AS status_wo'))
                     ->addSelect(DB::raw('work_orders_ins.qty * work_orders_ins.harga_wo as total_harga'))
                     ->addSelect(DB::raw('(CASE WHEN work_orders.status = 1 THEN ('.DB::raw('SUM(work_orders_outs.qty * work_orders_outs.harga_wo)').') ELSE ('.DB::raw('SUM(work_orders_ins.qty * work_orders_ins.harga_wo)').') END) AS total'))
                     ->leftJoin('employees', 'work_orders.employee_id', '=', 'employees.id')
                     ->leftJoin('work_orders_ins', 'work_orders_ins.work_order_id', '=', 'work_orders.id')
                     ->leftJoin('work_orders_outs', 'work_orders_outs.work_order_id', '=', 'work_orders.id')
                     ->where('work_orders.deleted_at','=',null)
                     ->whereBetween('work_orders.tanggal',[$request->date_from, $request->date_end])
                     ->groupBy(DB::raw('work_orders.created_at ASC WITH ROLLUP'))
                     ->paginate(10);
        }else{
            $work_orders = DB::table('work_orders')
                     ->select('work_orders.*','work_orders_ins.*','employees.*',DB::raw('(CASE WHEN work_orders.pembayaran = 1 THEN "Cash" ELSE "Non Cash" END) AS status_pembayaran'),DB::raw('(CASE WHEN work_orders.status = 1 THEN "WO" ELSE "Langsung" END) AS status_wo'))
                     ->addSelect(DB::raw('work_orders_ins.qty * work_orders_ins.harga_wo as total_harga'))
                     ->addSelect(DB::raw('(CASE WHEN work_orders.status = 1 THEN ('.DB::raw('SUM(work_orders_outs.qty * work_orders_outs.harga_wo)').') ELSE ('.DB::raw('SUM(work_orders_ins.qty * work_orders_ins.harga_wo)').') END) AS total'))
                     ->leftJoin('employees', 'work_orders.employee_id', '=', 'employees.id')
                     ->leftJoin('work_orders_ins', 'work_orders_ins.work_order_id', '=', 'work_orders.id')
                     ->leftJoin('work_orders_outs', 'work_orders_outs.work_order_id', '=', 'work_orders.id')
                     ->where('work_orders.deleted_at','=',null)
                     ->whereBetween('work_orders.tanggal',[$request->date_from, $request->date_end])
                     ->where('work_orders.employee_id','=',$request->employee_id)
                     ->groupBy(DB::raw('work_orders.created_at ASC WITH ROLLUP'))
                     ->paginate(10);
        }
        $tanggal = date('Y-m-d');
        // print_r(json_decode(json_encode($work_orders)));exit;
        // $work_orders = WorkOrders::where('tanggal_pengambilan', '!=' ,null)->with('employee')->paginate(10);
        return view('transaksi.penjualan.list_penjualan', compact('work_orders','sales','tanggal'));
    }

    public function list_penjualan_done ()
    {
        $work_orders = WorkOrders::where('keterangan', '!=' ,null)->paginate(10);
        return view('transaksi.penjualan.daftar_wo', compact('work_orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkNota (Request $request)
    {
        // echo $request;exit();
        $nota = WorkOrders::where('no_nota', $request->nota)->count();

        return Response::json($nota);
    }

    
    public function search(Request $request)
    {
        $search = $request->search;
        $work_orders = DB::table('work_orders')
                 ->select('work_orders.*','work_orders_ins.*','employees.*',DB::raw('(CASE WHEN work_orders.pembayaran = 1 THEN "Cash" ELSE "Non Cash" END) AS status_pembayaran'),DB::raw('(CASE WHEN work_orders.status = 1 THEN "WO" ELSE "Langsung" END) AS status_wo'))
                 ->addSelect(DB::raw('work_orders_ins.qty * work_orders_ins.harga_wo as total_harga'))
                 ->addSelect(DB::raw('(CASE WHEN work_orders.status = 1 THEN ('.DB::raw('SUM(work_orders_outs.qty * work_orders_outs.harga_wo)').') ELSE ('.DB::raw('SUM(work_orders_ins.qty * work_orders_ins.harga_wo)').') END) AS total'))
                 ->leftJoin('employees', 'work_orders.employee_id', '=', 'employees.id')
                 ->leftJoin('work_orders_ins', 'work_orders_ins.work_order_id', '=', 'work_orders.id')
                 ->leftJoin('work_orders_outs', 'work_orders_outs.work_order_id', '=', 'work_orders.id')
                 ->where('work_orders.deleted_at','=',null)
                 ->where(function ($query) use ($search){
                        $query  ->Where('work_orders.kode_wo', 'like', '%' .$search. '%')
                                ->orWhere('work_orders.no_nota', 'like', '%' .$search. '%');
                     })
                 ->groupBy('work_orders.kode_wo')
                 ->paginate(10);
        $sales = Employee::all();
        $tanggal = date('Y-m-d');
        return view('transaksi.penjualan.list_penjualan', compact('work_orders','sales','tanggal'));
    }
}
