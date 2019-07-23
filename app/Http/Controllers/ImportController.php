<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Excel;
use App\Barang;
use App\PriceList;
use App\Grouping;
use App\Supplier;
use App\Stock;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.import.import_barang');
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
        if(Input::hasFile('import')){
            $path = Input::file('import')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            // print_r($data->toArray());exit;
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    if (!empty($value->nama_barang)) {
                        $nama_groups = Grouping::where('id',$value->grouping_id)->pluck('nama');
                        $nama_supplier = Supplier::where('id',$value->supplier_id)->pluck('nama');
                        $get_first_letter = strtoupper(substr($nama_groups, 2, 1));
                        $get_second_letter = strtoupper(substr($nama_supplier, 2, 1));
                        $count_data = Barang::where('kode_barcode','like',$get_first_letter.'_'.'%')->where('kode_barcode','like','_'.$get_second_letter.'%' )->count();
                        $generate = str_pad($count_data + 1, 10, 0, STR_PAD_LEFT);
                        $kode_barcode = $get_first_letter.$get_second_letter.$generate;

                        $barang = Barang::create([
                            'kode_supplier' => $value->kode_supplier,
                            'supplier_id' => $value->supplier_id,
                            'kode_barcode' => $kode_barcode,
                            'nama_barang' => $value->nama_barang,
                            'grouping_id' => $value->grouping_id,
                            'status' => 1,
                            ]);
                        
                        PriceList::create([
                            'barang_id' => $barang->id,
                            'min_harga' => $value->min_harga,
                            'max_harga' => $value->max_harga,
                            ]);


                        Stock::create([
                            'barang_id'     => $barang->id,
                            'gudang_id'     => 2,
                            'qty'           => $value->stock,
                            'last_qty'      => $value->stock,
                            'harga_pokok'   => $value->harga_pokok,
                        ]);
                    }
                }
                // dd('Insert Record successfully.');
                // foreach ($data as $key => $value) {
                //     $insert[] = ['kode_supplier' => $value->kode_supplier, 
                //                  'supplier_id' => $value->supplier_id, 
                //                  'kode_barcode' => $value->kode_barcode,
                //                  'nama_barang' => $value->nama_barang,
                //                  'status' => 1, 
                //                  'grouping_id' => $value->grouping_id
                //                 ];
                // }
                // if(!empty($insert)){
                //     $barang = Barang::insert($insert);
                //     dd($barang);exit();
                //     dd('Insert Record successfully.');
                // }
            }
        return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function price_list()
    {
        return view('master.import.import_harga');
    }

    public function import_price_list(Request $request)
    {
        if(Input::hasFile('import')){
            $path = Input::file('import')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            // print_r($data->toArray());exit;
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = ['barang_id' => $value->barang_id, 
                                 'min_harga' => $value->min_harga, 
                                 'max_harga' => $value->max_harga
                                ];
                }
                if(!empty($insert)){
                    PriceList::insert($insert);
                    dd('Insert Record successfully.');
                }
            }
        return back();
        }
    }
}
