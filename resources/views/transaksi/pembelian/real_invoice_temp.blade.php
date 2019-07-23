@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Pemesanan Barang </strong></h3>
			</div>
			<div class="col-md-12">
			<form action="update" method="post">
				{{ csrf_field() }}
					<table class="table" width="100%" rules="none">
						<tr>
							<th width="30%" style="text-align: right;">Kode Transaksi</th>
							<th width="5%" style="text-align: center;"> : </th>
							<td width="25%" style="text-align: left;">{{$preOrders->id}}</td>
							<td width="40%"></td>
						</tr>
						<tr>
							<th style="text-align: right;">Tanggal</th>
							<th style="text-align: center;"> : </th>
							<td style="text-align: left;"><input type="text" name="tanggal" value="{{$date}}" class="form-control datepicker" 
								@if ($total_qty->total_qty > 0)
									readonly="true" 
								@endif></td>
							<td>
								@if ($total_qty->total_qty > 0)
									@foreach ($suppliers as $supplier)
										<input type="hidden" name="supplier_id_hidden" value="{{$supplier->id}}"> 
									@endforeach
								@endif
							</td>
						</tr>
						<tr>
							<th style="text-align: right;">Daftar Pemasok</th>
							<th style="text-align: center;"> : </th>
							<td style="text-align: left;">
								<select name="supplier_id" id="supplier_id" class="form-control select" data-live-search="true" 
								@if ($total_qty->total_qty > 0)
									disabled="true" 
								@endif>
									<option value="">Pilih Nama Supplier</option>
									@foreach ($suppliers as $supplier)
										<option value="{{ $supplier->id }}"
											@if ($supplier->id == old('supplier_id', $preOrders->supplier_id))
												selected="selected" 
											@endif
										>{{$supplier->nama}}</option>
									@endforeach                   
								</select>
							</td>
							<td>
								<button class="btn btn-info" 
								@if ($total_qty->total_qty > 0)
									disabled="true" 
								@endif>
								<i class="fa fa-plus"></i></button>	
							</td>
						</tr>
					</table>
				</div>
				<br>
				<table width="90%" border="2" rules="none" align="center" bordercolor="#EEF2F7" id="table">
					<tr>
						<th width="10%" style="text-align: center; height: 50px">Jumlah</th>
						<th width="15%" style="text-align: center;">Kode Barang</th>
						<th width="30%" style="text-align: center;">Nama Barang</th>
						<th width="15%" style="text-align: center;">Harga Satuan</th>
						<th rowspan="2" width="10%" style="text-align: center;"><button id="add" class="btn btn-info" type="Submit">Submit</button></th>
					</tr>
					<tr>
						<th width="10%" style="height: 50px; text-align: center;"><input type="number" name="qty" id="qty" style="width: 50px;" value="1" required=""></th>
						<th width="15%" style="text-align: center;">
							<select name="kode_barcode" id="kode_barcode" class="form-control select" data-live-search="true" autofocus="true">
							<option value="">Pilih Kode Barcode</option>
							</select>
						</th>
						<th width="30%" style="text-align: center;">
						<label id="nama_barang"></label>
						</th>
						<th width="15%" style="text-align: center;"><input style="text-align: center;width: 150px; align-items: center;" type="text" name="harga_po" id="dengan-rupiah" required="true" /></th>
					</tr>
				</table>
				</form>
				<br>
				<div class="col-md-12" align="center">
					<table class="table" width="90%" align="center">
						<tr class="header">
							<th width="5%" style="text-align: center;">No</th>
							<th width="15%" style="text-align: center;">Kode Barcode</th>
							<th width="30%" style="text-align: center;">Nama Barang</th>
							<th width="10%" style="text-align: center;">Jumlah</th>
							<th width="15%" style="text-align: center;">Harga</th>
							<th width="15%" style="text-align: center;">Total</th>
							<th width="10%"></th>
						</tr>
						@foreach ($pre_order_ins as $pre_order_in)
						<tr>
							<td style="text-align: center;">{{$pre_order_in->id}}</td>
							<td style="text-align: center;">{{$pre_order_in->barang['kode_barcode']}}</td>
							<td style="text-align: center;">{{$pre_order_in->barang['nama_barang']}}</td>
							<td style="text-align: center;">{{$pre_order_in->qty}}</td>
							<td style="text-align: center;">Rp. {{number_format($pre_order_in->harga_po)}}</td>
							<td style="text-align: center;">Rp. {{number_format($pre_order_in->total_sales)}}</td>
							<td style="text-align: center;"><a><i class="delete-modal fa fa-trash-o" data-id="{{$pre_order_in->id}}" style="margin-right: 10px;"></i></a></td>	
						</tr>
						@endforeach
					</table>
				</div>
				<br>
				<form method="post" action="selesaiinvoice">
				{{ csrf_field() }}
				<table width="90%" align="center">
					<tr>
						<th width="60%" height="30px"></th>
						<th width="15%" style="text-align: right;"><label>Jumlah Barang</label></th>
						<th width="3%" style="text-align: center;"><label> : </label></th>
						<th width="17%" style="text-align: left;"><label name="jumlahBarang" id="jumlahBarang">{{$total_qty->total_qty}} Pcs</label></th>
						<th width="5%"></th>
					</tr>
					<tr>
						<th width="60%" height="30px"></th>
						<th width="15%" style="text-align: right;"><label>Total Harga</label></th>
						<th width="3%" style="text-align: center;"><label> : </label></th>
						<th width="17%" style="text-align: left;"><label name="totalBarang" id="totalBarang">Rp.{{ number_format($total_harga->total_po) }},-</label></th>
						<th width="5%"></th>
					</tr>
					<tr>
						<th width="60%" height="50px"></th>
						<th width="15%" style="text-align: right;"><label>Keterangan</label></th>
						<th width="3%" style="text-align: center;"><label> : </label></th>
						<th width="17%" style="text-align: left;"><textarea rows="3" class="form-control" id="keterangan" name="keterangan"></textarea></th>
						<th width="5%"></th>
					</tr>
					<tr>
						<th width="60%" height="50px"></th>
						<th width="15%" style="text-align: right;"><label>Metode Pembayaran</label></th>
						<th width="3%" style="text-align: center;"><label> : </label></th>
						<th width="17%" style="text-align: left;">
							<select name="metodePembayaran" class="form-control">
								<option value="1">Tunai</option>
								<option value="2">Non Tunai</option>
                            </select>
						</th>
						<th width="5%"></th>
					</tr>
				</table>
				<br>
				<table width="90%" align="center">
					<tr>
						<th width="60%" height="50px"></th>
						<th width="15%" style="text-align: right;"></th>
						<th width="3%" style="text-align: center;"></th>
						<th width="17%" style="text-align: left;"><button class="btn btn-info btn-block" type="Submit">Simpan</button></th>
						<th width="5%"></th>
					</tr>
				</table>
				</form>
				<div class="panel-footer">
				</div>
			</form>

			<div class="modal fade bs-example-modal-sm3" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
		        <div class="modal-dialog modal-md" role="document">
		          <div class="modal-content">
		            <div class="modal-header">
		              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		              <h4 class="modal-title">Delete Data</h4>
		            </div>
		            <div class="modal-body">
		              <div class="form-group">
		                {{ csrf_field() }}
		                <input type="hidden" name="id-delete" id="id-delete">
		                <p>Yakin Ingin Menghapus Data? </p>
		              </div>
		              <div class="form-group" align="right">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" id="delete" class="btn btn-danger" data-dismiss="modal">Delete</button>
		              </div>
		            </div>
		          </div>
		        </div>
		    </div>

		    <!-- Modal -->
			<div class="modal fade" id="modalSupplier" role="dialog">
				<div class="modal-dialog">

				  <!-- Modal content-->
				  <div class="modal-content">
				    <div class="modal-header">
				      <button type="button" class="close" data-dismiss="modal">&times;</button>
				      <h4 class="modal-title">Modal Header</h4>
				    </div>
				    <div class="modal-body">
				      <p>Some text in the modal.</p>
				    </div>
				    <div class="modal-footer">
				      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				    </div>
				  </div>
				  
				</div>
			</div>
		</div>
	</div>
</div>

	

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="supplier_id"]').on('change', function() {
            var supplierID = $(this).val();
            var id_barang;
            if(supplierID) {
                $.ajax({
                    url: '/po/kode_barcode/'+supplierID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        
			            $('#nama_barang').empty();
                        $('#kode_barcode').empty();
                        $.each(data, function(key, value) {
                        	if (key === 0) {
                        		console.log(value);	
                        		$('#nama_barang').append('<label>'+ value.nama_barang +'</label>');
                        	}
                            $('#kode_barcode').append('<option value="'+ value.id +'">'+ value.kode_barcode +' - '+ value.kode_supplier +'</option>');
                            $('#kode_barcode').selectpicker("refresh");
                        });
                    }
                });
            }else{
			    $('#nama_barang').empty();
            	$('#kode_barcode').append('<option value="">Pilih Kode Barcode</option>');
                $('#kode_barcode').selectpicker("refresh");
            }
        });

        $(window).load(function(){
        	var supplierID = $('#supplier_id').val();
        	var id_barang;
            if(supplierID) {
                $.ajax({
                    url: '/po/kode_barcode/'+supplierID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        
			            $('#nama_barang').empty();
                        $('select[name="kode_barcode"]').empty();
                        $.each(data, function(key, value) {
                        	if (key === 0) {
                        		console.log(value);	
                        		$('#nama_barang').append('<label>'+ value.nama_barang +'</label>');
                        	}
                            $('#kode_barcode').append('<option value="'+ value.id +'">'+ value.kode_barcode +' - '+ value.kode_supplier +'</option>');
                            $('#kode_barcode').selectpicker("refresh");
                        });
                    }
                });
            }else{
			    $('#nama_barang').empty();
            	$('#kode_barcode').append('<option value="">Pilih Kode Barcode</option>');
                $('#kode_barcode').selectpicker("refresh");
            }
        });

        $('select[name="kode_barcode"]').on('change', function() {
            var id_barang = $(this).val();
            if(id_barang) {
                $.ajax({
                    url: '/po/nama_barang/'+id_barang,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        $('#nama_barang').empty();
                        $.each(data, function(key, value) {
                            $('#nama_barang').append('<label>'+ value.nama_barang +'</label>');
                        });

                    }
                });
            }else{
                $('#nama_barang').empty();
            }
        });
    });
</script>
<script type="text/javascript">
function multiply() {
  a = Number(document.getElementById('qty').value);
  b = Number(document.getElementById('price').value);
  c = a * b;

  document.getElementById('TOTAL').value = c;
}

</script>

<script type="text/javascript">
  $(document).on('click', '.delete-modal', function() {
    $('#id-delete').val($(this).data('id'));
    $('.bs-example-modal-sm3').modal('show');
    // console.log($(this).data('id'));
  });
  $("#delete").click(function() {
    $.ajax({
      type: 'post',
      url: '/real_invoice/delete',
      data: {
        '_token': $('input[name=_token]').val(),
        'id' : $('input[name=id-delete]').val()
      },
      // console.log(data);
      success: function(data) {
      	console.log(data);
        $('.item' + data.id).remove();
        toastr.success("Data Berhasil Dihapus.");
        location.href = "/real_invoice"
      }
    });
  });
</script>

@endsection




<?php

namespace App\Http\Controllers;

use App\PreOrder;
use App\Barang;
use App\Supplier;
use Illuminate\Http\Request;
use Response;
use App\Stock;
use App\NeracaKeuangan;
use App\NeracaPemasukan;
use App\PreOrderIn;
use DB;
use Illuminate\Support\Facades\Auth;

class RealInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_user = Auth::user();
        //Check Count PO By Account
        $checkPO = PreOrder::where('user_id', $data_user->id)->count();
        if ($checkPO == 0) { //if Account dont have PO, Create New PO
            $create = PreOrder::create([
                'user_id' => $data_user->id,
                'status' => '2'
                ]);
            $pre_order_id = $create->id;
            return redirect("/real_invoice/".$pre_order_id."/edit");
        }else{
            //Get Last ID PO
            $last_insert_id = PreOrder::where('user_id',$data_user->id)->orderBy('id', 'desc')->first();
            //Get Data PreOrder By Last ID
            $check_PreOrder = PreOrder::where('id',$last_insert_id->id)->where('user_id',$data_user->id)->first();
            //if Data PO Not Complete || Tanggal is Empty
            if ($check_PreOrder->pembayaran == null || 
                $check_PreOrder->pembayaran == '0') {
                return redirect("/real_invoice/".$check_PreOrder->id."/edit");
            }else{ //if Data Complete Create new PO
                $create = PreOrder::create([
                    'user_id' => $data_user->id,
                'status' => '2'
                    ]);
                $pre_order_id = $create->id;
                return redirect("/real_invoice/".$pre_order_id."/edit");
            }
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
        // echo $id;exit();
        $barangs = Barang::all();
        $preOrders = PreOrder::where('id', $id)->with('pre_order_out')->first();
        $check_PreOrderIn = PreOrderIn::where('pre_order_id', $id)->count();
            // echo $id;exit();    
        if ($check_PreOrderIn > 0) {
            $date = $preOrders->tanggal;
            $suppliers = Supplier::where('id', $preOrders->supplier_id)->get(); 
            // echo $suppliers;exit(); 
        }else{
            $date = date('Y-m-d');
            $suppliers = Supplier::all();
        }
        // echo $preOrders;exit();
        // echo $preOrders->supplier_id;exit();
        $pre_order_ins = PreOrderIn::select('*',DB::raw('harga_po * qty as total_sales'))->where('pre_order_id',$id)->with('user','barang')->get();
        $total_harga = PreOrderIn::select(DB::raw('SUM(harga_po * qty) as total_po'))->where('pre_order_id',$id)->first();
        $total_qty = PreOrderIn::select(DB::raw('SUM(qty) as total_qty'))->where('pre_order_id',$id)->first();
        return view('transaksi.pembelian.real_invoice',compact('suppliers','barangs','date','preOrders','pre_order_ins','total_harga','total_qty','date_estimasi'));
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
        // echo $id;
        $data_user = Auth::user();
        $check_PreOrderIn = PreOrderIn::where('pre_order_id', $id)->count();
        // echo $check_PreOrderOut;exit();
        if ($check_PreOrderIn > 0) {
            $pre_order = PreOrder::where('id',$id)->update([
                'supplier_id' => $request->supplier_id_hidden,
                'tanggal' => $request->tanggal,
                'user_id'   => $data_user->id,
                'status' => '2'
                ]);       
        }else{
            $pre_order = PreOrder::where('id',$id)->update([
            'supplier_id' => $request->supplier_id,
            'tanggal' => $request->tanggal,
            'user_id'   => $data_user->id,
                'status' => '2'
            ]);
        }

        $validasiItem = PreOrderIn::where('pre_order_id', $id)->where('barang_id',$request->kode_barcode)->first();
        if ($validasiItem != null) {
            // $total = (int)$request->qty + $validasiItem->qty;
            // echo $total;exit();
            $pre_order_out = PreOrderIn::where('pre_order_id', $id)->where('barang_id',$request->kode_barcode)->update([
                'pre_order_id' => $id,
                'barang_id'    => $request->kode_barcode,
                'qty'          => (int)$request->qty + $validasiItem->qty,
                'harga_po'     => $request->harga_po,
                'user_id'      => $data_user->id
                ]); 
        }else{
            $pre_order_out = PreOrderIn::create([
                'pre_order_id' => $id,
                'barang_id'    => $request->kode_barcode,
                'qty'          => $request->qty,
                'harga_po'     => $request->harga_po,
                'user_id'      => $data_user->id
                ]);    
        }
        // if ($request->tanggal_estimasi == null || $request->tanggal_estimasi='0000-00-00') {
        //     return redirect("/po/".$id."/edit");
        // }
        return redirect("/real_invoice/");
    }

    public function updateInvoice(Request $request, $id)
    {
        // echo $request->metodePembayaran;exit();
        $data_user = Auth::user();
        $data = PreOrder::where('id', $id)->first();
        $pre_order = PreOrder::where('id',$id)->update([
            'tanggal_pembayaran' => $data->tanggal,
            'keterangan' => $request->keterangan,
            'pembayaran' => $request->metodePembayaran,
            'user_id'   => $data_user->id,
                'status' => '2'
            ]);

        $total = PreOrderIn::select(DB::raw('SUM(harga_po * qty) as total_harga'))->where('pre_order_id',$id)->first();
        $last_total = NeracaKeuangan::orderBy('id','desc')->first();
        $neraca_keuangan = NeracaKeuangan::create([
            'tanggal' => date('Y-m-d'),
            'total' => $total->total_harga,
            'last_total' => (int) $last_total->last_total + $total->total_harga,
            'status' => '2',
            'status_pembayaran' => $request->metodePembayaran,
            'user_id' => $data_user->id
            ]);

        NeracaPemasukan::create([
            'neraca_id' => $neraca_keuangan->id,
            'total' => $total->total_harga,
            'keterangan' => 'Pembelian Barang'
            ]);

        $pre_order_ins = PreOrderIn::where('pre_order_id', $id)->get();
        foreach ($pre_order_ins as $key => $pre_order_in) {
            Stock::create([
                'barang_id' => $pre_order_in->barang_id,
                'gudang_id' => '1',
                'qty' => $pre_order_in->qty,
                'harga_pokok' => $pre_order_in->harga_po
                ]);   
        }
        return redirect("/real_invoice");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreOrder  $preOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // echo $request->id;exit();
        $preorderin = PreOrderIn::where('id', $request->id)->delete();
        return redirect("/real_invoice");
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
