<?php

namespace App\Http\Controllers;

use App\Supplier;
use Alert;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
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
            $suppliers = Supplier::orderBy('id','asc')->paginate(10);
            return view('master.supplier.list_supplier', compact('suppliers'));
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
            return view('master.supplier.create_supplier');
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
        Supplier::create([
            'nama' => request('nama')
            ]);
        Alert::success('Tambah Supplier Berhasil', 'Berhasil');
        return redirect("/supplier");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        $data_user = Auth::user();
        if ($data_user) {
            return view('master.supplier.edit_supplier', compact('supplier'));
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        Supplier::where('id', $supplier->id)->update([
            'nama' => request('nama')
            ]);
        Alert::success('Ubah Supplier Berhasil', 'Berhasil');
        return redirect("/supplier");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $check_child = Supplier::where('id',$request->id)->with('barang')->first();
        if ($check_child->barang->count() == 0) {
            $supplier = Supplier::find($request->id);
            $supplier->delete();
            return redirect("/supplier");
         }else{
            echo "false";
         }
    }

    public function search(Request $request)
    {
        $suppliers = Supplier::where('nama','like','%'.$request->search.'%')->paginate(10);
        return view('master.supplier.list_supplier', compact('suppliers'));   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkSupplier (Request $request)
    {
        // echo $request;exit();
        $supplier = Supplier::where('nama', $request->supplier)->count();

        return Response::json($supplier);
    }
}
