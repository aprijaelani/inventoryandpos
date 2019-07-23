<?php

namespace App\Http\Controllers;

use App\Barang;
use Illuminate\Http\Request;
use App\Grouping;
use App\Gudang;
use App\Supplier;
use App\PriceList;
use DB;
use PDF;
use Response;
use Alert;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $class_active = 'barang';
        $data_user = Auth::user();
        if ($data_user) {
            $search = "";
            $barangs = Barang::orderBy('nama_barang','asc')->with('supplier','grouping')->paginate(10);
            return view('master.barang.list_barang', compact('barangs','class_active', 'search'));
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
        $data_user = Auth::user();
        if ($data_user) {
            $suppliers = Supplier::all();
            $groups = Grouping::all();
            return view('master.barang.create_barang',compact('suppliers','gudangs','groups'));
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
        $nama_groups = Grouping::where('id',$request->group_id)->pluck('nama');
        $nama_supplier = Supplier::where('id',$request->supplier_id)->pluck('nama');
        $get_first_letter = strtoupper(substr($nama_groups, 2, 1));
        $get_second_letter = strtoupper(substr($nama_supplier, 2, 1));
        $count_data = Barang::where('kode_barcode','like',$get_first_letter.'_'.'%')->count();
        $generate = str_pad($count_data + 1, 10, 0, STR_PAD_LEFT);
        $kode_barcode = $get_first_letter.$generate;
        // echo $kode_barcode;exit();

        $checkBarang = Barang::where('nama_barang', request('nama_barang'))
                            ->where('kode_supplier', request('kode_supplier'))
                            ->where('supplier_id', request('supplier_id'))
                            ->count();

        if ($checkBarang == 0) {
            // for($i = 1; $i <= 250; $i++){
            //     Barang::create([
            //     'kode_supplier' => $i,
            //     'supplier_id' => request('supplier_id'),
            //     'kode_barcode' => $i,
            //     'nama_barang' => $i,
            //     'grouping_id' => request('group_id'),
            //     'status' => 1,
            //     ]);

            // $barang_id = Barang::where('kode_barcode', $i)->value('id');

            // $min_str = preg_replace("/[^0-9]/", "", request('min_harga'));
            // $harga_min = (int) $min_str;

            // $max_str = preg_replace("/[^0-9]/", "", request('max_harga'));
            // $harga_max = (int) $max_str;

            // PriceList::create([
            //     'barang_id' => $barang_id,
            //     'min_harga' => $harga_min,
            //     'max_harga' => $harga_max,
            //     ]);
            // }

            Barang::create([
                'kode_supplier' => request('kode_supplier'),
                'supplier_id' => request('supplier_id'),
                'kode_barcode' => $kode_barcode,
                'nama_barang' => request('nama_barang'),
                'grouping_id' => request('group_id'),
                'status' => 1,
                ]);

            $barang_id = Barang::where('kode_barcode', $kode_barcode)->value('id');

            $min_str = preg_replace("/[^0-9]/", "", request('min_harga'));
            $harga_min = (int) $min_str;

            $max_str = preg_replace("/[^0-9]/", "", request('max_harga'));
            $harga_max = (int) $max_str;
            
            PriceList::create([
                'barang_id' => $barang_id,
                'min_harga' => $harga_min,
                'max_harga' => $harga_max,
                ]);
        }
        
        Alert::success('Tambah Barang Berhasil', 'Berhasil');
        return redirect("/barang");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        $data_user = Auth::user();
        if ($data_user) {
            $suppliers = Supplier::all();
            $gudangs = Gudang::all();
            $groups = Grouping::all();
            $barangs = Barang::find('id');
            // $nama_supplier = Supplier::whereId($barang['supplier_id'])->get();
            $nama_grouping = Grouping::whereId($barang['grouping_id'])->get();
            return view('master.barang.edit_barang',compact('barang','suppliers','gudangs','groups','barangs','nama_grouping'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $nama_groups = Grouping::where('id',$request->group_id)->pluck('nama');
        $nama_supplier = Supplier::where('id',$request->supplier_id)->pluck('nama');
        $get_first_letter = strtoupper(substr($nama_groups, 2, 1));
        $get_second_letter = strtoupper(substr($nama_supplier, 2, 1));
        $count_data = Barang::where('kode_barcode','like',$get_first_letter.'_'.'%')->where('kode_barcode','like','_'.$get_second_letter.'%' )->count();
        $generate = str_pad($count_data + 1, 6, 0, STR_PAD_LEFT);
        $kode_barcode = $get_first_letter.$get_second_letter.$generate;

        Barang::where('id',$id)->update([
            'kode_supplier' => request('kode_supplier'),
            'supplier_id' => request('supplier_id'),
            'nama_barang' => request('nama_barang'),
            'grouping_id' => request('group_id'),
            'status' => 1,
            ]);
        Alert::success('Ubah Barang Berhasil', 'Berhasil');
        return redirect("/barang");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $time   = date('Y-m-d H:i:s');
        $barang = Barang::find($request->id);
        $barang->delete();

        PriceList::where('barang_id',$request->id)->update([
            'deleted_at' => $time
            ]);
        return redirect("/barang");
    }


    public function search(Request $request)
    {
        $search = $request->search;
        $barangs = Barang::where('nama_barang','like','%'.$request->search.'%')->orWhere('kode_barcode','like','%'.$request->search.'%')->with('supplier','grouping')->orderBy('nama_barang', 'ASC')->paginate(10);
        return view('master.barang.list_barang', compact('barangs', 'search'));   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkKode (Request $request)
    {
        // echo $request;exit();
        $kode = Barang::where('kode_supplier', $request->kode)->count();

        return Response::json($kode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkBarang (Request $request)
    {
        // echo $request;exit();
        $barang = Barang::where('nama_barang', $request->kode)->count();

        return Response::json($barang);
    }
}
