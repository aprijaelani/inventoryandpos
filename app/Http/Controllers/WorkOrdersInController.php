<?php

namespace App\Http\Controllers;

use App\WorkOrdersIn;
use Illuminate\Http\Request;
use App\WorkOrders;
use App\Employee;
use App\Stock;
use App\StockOut;
use App\NeracaKeuangan;
use App\NeracaPemasukan;
use Illuminate\Support\Facades\Auth;
use Response;
use App\WorkOrdersOut;
use DB;

class WorkOrdersInController extends Controller
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
        $check_wo = WorkOrders::where('tanggal', date('Y-m-d'))->count();
        $generate_loop = str_pad($check_wo + 1, 4, 0, STR_PAD_LEFT);
        $data_user = Auth::user();
        $generate = "SI".date('dmy').$data_user['id'].$generate_loop;
        $employees = Employee::all();
        return view('transaksi.penjualan.sales_invoices', compact('employees','generate','tanggal'));
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
        $check_wo = WorkOrders::where('tanggal', date('Y-m-d'))->count();
        $generate_loop = str_pad($check_wo + 1, 4, 0, STR_PAD_LEFT);
        $data_user = Auth::user();
        $kode_wo = "SI".date('dmy').$data_user['id'].$generate_loop;
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
            'tanggal_estimasi' => $request->tanggal,
            'tanggal_pengambilan' => $request->tanggal,
            'status'           => 2,
            'keterangan'       => $request->keterangan,
            'pembayaran'       => $request->type_pembayaran,
            'discount'         => $discount,
            'pihak_ketiga'     => $pihak3,
        ]);

        foreach($datas as $work_order){
            $harga_str = preg_replace("/[^0-9]/", "", $work_order['harga']);
            $harga = (int) $harga_str;
            if ($work_order['id_barang'] != null) {
                $work_order_out = WorkordersIn::create([
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
        if ($request->type_pembayaran == 2) {
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $request->total_hargas,
                    'last_total'        => 0,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $request->total_hargas,
                    'keterangan'    => 'Penjualan Barang Langsung ID Transaksi '.$work_orders->kode_wo,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $request->total_hargas,
                    'last_total'        => $last_total->last_total,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $request->total_hargas,
                    'keterangan'    => 'Penjualan Barang Langsung ID Transaksi '.$work_orders->kode_wo,
                    ]);
            }
        }else{
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $request->total_hargas,
                    'last_total'        => (int) 0 + $request->total_hargas,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $request->total_hargas,
                    'keterangan'    => 'Penjualan Barang Langsung Kode Transaksi '.$work_orders->kode_wo,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal,
                    'total'             => $request->total_hargas,
                    'last_total'        => (int) $last_total->last_total + $request->total_hargas,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal,
                    'total'         => $request->total_hargas,
                    'keterangan'    => 'Penjualan Barang Langsung Kode Transaksi '.$work_orders->kode_wo,
                    ]);
            }
        }
        return redirect('/sales-invoices');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invoice(Request $request)
    {   
        // dd($request->all());exit;
        $data_user = Auth::user();
        $tanggal_sekarang = date('Y-m-d');
        $tanggal = $request->tanggal;
        $sisa = $request->sisas;
        $kode_wo = $request->kode_wo;
        $data = $request->all();
        for ($i=0; $i <sizeof($data['id_barang']) ; $i++) { 
            $datas[$i]['id_barang'] = $data['id_barang'][$i];
            $datas[$i]['qty'] = $data['qty'][$i];
            $datas[$i]['harga'] = $data['harga'][$i];
        }

        WorkOrders::where('kode_wo', $kode_wo)->update([
            'tanggal_pengambilan' => $tanggal,
        ]);

        $work_orders = WorkOrders::where('kode_wo', $kode_wo)->first();

        foreach($datas as $work_order){
            $harga_str = preg_replace("/[^0-9]/", "", $work_order['harga']);
            $harga = (int) $harga_str;
            if ($work_order['id_barang'] != null) {
                // echo $work_orders['id'];exit();
                $work_order_out = WorkordersOut::where('work_order_id', $work_orders['id'])
                                                ->where('barang_id', $work_order['id_barang'])
                                                ->update([
                    'harga_wo' =>$harga,
                    ]);
            }
        }

        $last_total = NeracaKeuangan::where('tanggal', date('Y-m-d'))->orderBy('id', 'desc')->first();
        if ($request->type_pembayaran == 2) {
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $sisa,
                    'last_total'        => 0,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $sisa,
                    'keterangan'    => 'Pelunasan Barang WO Kode Transaksi '.$kode_wo,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $sisa,
                    'last_total'        => $last_total->last_total,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $sisa,
                    'keterangan'    => 'Pelunasan Barang WO Kode Transaksi '.$kode_wo,
                    ]);
            }
        }else{
            if ($last_total == '') {
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $sisa,
                    'last_total'        => (int) 0 + $sisa,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]); 

                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $sisa,
                    'keterangan'    => 'Pelunasan Barang WO Kode Transaksi '.$kode_wo,
                    ]);   
            }else{
                $neraca_keuangan = NeracaKeuangan::create([
                    'tanggal'           => $tanggal_sekarang,
                    'total'             => $sisa,
                    'last_total'        => (int) $last_total->last_total + $sisa,
                    'status'            => '1',
                    'status_pembayaran' => $request->type_pembayaran,
                    'user_id'           => $data_user->id,

                    ]);
                    
                $neraca_pengeluaran = NeracaPemasukan::create([
                    'neraca_id'     => $neraca_keuangan->id,
                    'date'          => $tanggal_sekarang,
                    'total'         => $sisa,
                    'keterangan'    => 'Pelunasan Barang WO Kode Transaksi '.$kode_wo,
                    ]);
            }
        }
        return redirect('/wo-invoices');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkOrdersIn  $workOrdersIn
     * @return \Illuminate\Http\Response
     */
    public function show(WorkOrdersIn $workOrdersIn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkOrdersIn  $workOrdersIn
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkOrdersIn $workOrdersIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkOrdersIn  $workOrdersIn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkOrdersIn $workOrdersIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkOrdersIn  $workOrdersIn
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkOrdersIn $workOrdersIn)
    {
        //
    }

    public function wo_invoices()
    {
        $tanggal = date('Y-m-d');
        $check_wo = WorkOrders::where('tanggal', date('Y-m-d'))->count();
        $generate_loop = str_pad($check_wo + 1, 4, 0, STR_PAD_LEFT);
        $data_user = Auth::user();
        $generate = date('dm').$data_user['id'].$generate_loop;
        $employees = Employee::all();
        return view('transaksi.penjualan.wo_invoices', compact('employees','generate','tanggal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchWorkOrders (Request $request)
    {
        // echo $request->search;exit();
        $search = $request->search;
        $work_order = DB::table('work_orders')
                     ->select('work_orders.*', 'employees.nama')
                     ->leftjoin('employees', 'work_orders.employee_id', '=', 'employees.id')
                     ->where(function ($query) use ($search){
                        $query  ->Where('work_orders.tanggal', 'like', '%' .$search. '%')
                                 ->orWhere('work_orders.no_nota', 'like', '%' .$search. '%')
                                 ->orWhere('employees.nama', 'like', '%' .$search. '%');
                     })
                     ->Where('work_orders.status', '=', '1')
                     ->Where('work_orders.tanggal_pengambilan', '=', null)
                     ->offset(0)
                     ->limit(15)
                     ->get();
        return Response::json($work_order);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showWorkOrders (Request $request)
    {
        // echo $request->search;exit();
        $work_order = DB::table('work_orders_outs')
                     ->select('work_orders_outs.*', 'barangs.*', 'price_lists.*')
                     ->addSelect(DB::raw('SUM(stocks.last_qty) as total'))
                     ->leftjoin('barangs', 'work_orders_outs.barang_id', '=', 'barangs.id')
                     ->join('stocks', 'barangs.id', '=', 'stocks.barang_id')
                     ->leftjoin('price_lists', 'barangs.id', '=', 'price_lists.barang_id')
                     ->where('work_order_id', $request->search)
                     ->groupBy('work_orders_outs.barang_id')
                     ->get();
        // $work_order = WorkOrdersOut::where('work_order_id', $request->search)
        //                             ->with('barang')
        //                             ->get();
        return Response::json($work_order);
    }
}
