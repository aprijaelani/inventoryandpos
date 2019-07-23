<?php

namespace App\Http\Controllers;

use App\PriceList;
use App\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class PriceListController extends Controller
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
            $search = "";
            $pricelists = PriceList::orderBy('id', 'asc')->with('barang')->paginate(10);
            return view('master.stock_price.list_harga', compact('pricelists', 'search'));
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
     * @param  \App\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function show(PriceList $priceList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceList $priceList)
    {
        // echo $priceList['id'];
        $data_user = Auth::user();
        if ($data_user) {
            $barang = Barang::whereId($priceList['id'])->value('nama_barang');
            // echo $barang;exit();
            return view('master.stock_price.edit_harga', compact('barang', 'priceList'));
        }else{
            return redirect('/');
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $min_str = preg_replace("/[^0-9]/", "", request('harga_minimum'));
        $harga_min = (int) $min_str;

        $max_str = preg_replace("/[^0-9]/", "", request('harga_maximum'));
        $harga_max = (int) $max_str;

        PriceList::where('id',$id)->update([
            'min_harga' => $harga_min,
            'max_harga' => $harga_max,
            ]);
        return redirect("/price_list");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceList $priceList)
    {
        //
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $pricelists = PriceList::with('barang')->leftjoin('barangs','price_lists.barang_id','=','barangs.id')
            ->where('barangs.nama_barang','like','%'.$request->search.'%')
            ->orwhere('barangs.kode_barcode','like','%'.$request->search.'%')
            ->paginate(10);
        return view('master.stock_price.list_harga', compact('pricelists', 'search'));
    }
}
