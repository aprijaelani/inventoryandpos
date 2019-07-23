@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" method="post" action="store">
				{{ csrf_field() }}
			<input type="hidden" name="id_penerimaan" value="{{$no_sj->id}}">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><strong>Invoice </strong></h3>
				</div>
				<div class="col-md-12">
					{{-- <table width="100%">	
						<tr>
							<th width="30%" height="30px" style="text-align: right;"><label>Search By</label></th>
							<th width="5%" style="text-align: center;"><label> : </label></th>
							<td width="25%">
                                <div class="form-group col-md-12">
									<input type="radio" name="chpas" value="KodePO" onchange="document.getElementById('preOrder').style.display='block';document.getElementById('nonPreOrder').style.display='none';" checked />&nbsp Pre Order
									<input type="radio" name="chpas" value="Supplier" onchange="document.getElementById('preOrder').style.display='none';document.getElementById('nonPreOrder').style.display='block';" />&nbsp Non Pre Order
                                </div>
							</td>
							<td width="40%"></td>
						</tr>
					</table> --}}
						<div class="col-md-12">
							<table width="100%" align="center">
							<tr>
								<th width="10%" height="40px" style="text-align: right;">Search No SJ</th>
								<th width="5%" style="text-align: center;"> : </th>
								<td width="25%" style="text-align: left;">
									<div id="searchSj" style="display:block;">
										<select name="kode_po" class="form-control select" data-live-search="true" style="display: none;">
										@foreach ($search_no_sj as $no_sj)
		                                    <option>{{$no_sj['no_sj']}}</option>
	              						@endforeach
		                                </select>
									</div>
								</td>
								<td width="40%">
									<div id="btnSearch" style="display: block;">
										<button class="btn btn-info"><i class="fa fa-search"></i></button>
									</div>
								</td>
							</tr>
						</table>
						<br>
							<table style="margin-top: 10px" rules="none" width="100%" border="2" bordercolor="#EEF2F7">
							<tr>
								<th width="10%" height="40px" style="text-align: right;"><label>No. Surat Jalan</label></th>
								<th width="5%" style="text-align: center;"><label> : </label></th>
								<td width="25%" style="text-align: left;">{{$no_sj['no_sj']}}</td>
								<td width="40%"></td>
							</tr>
							<tr>
								<th style="text-align: right;" height="40px"><label>Tanggal PO</label></th>
								<th style="text-align: center;"><label> : </label></th>
								<td style="text-align: left;"><label name="tanggal" id="tanggal">{{$no_sj['tanggal']}}</label></td>
								<td></td>
							</tr>
							<tr>
								<th style="text-align: right;" height="40px"><label>Pemasok</label></th>
								<th style="text-align: center;"><label> : </label></th>
								<td style="text-align: left;"><label name="pemasok" id="pemasok">{{$no_sj->supplier['nama']}}</label></td>
								<td></td>
							</tr>
						</table>
						</div>
						<br>
						<div class="col-md-12" align="center">
							<table class="table" width="90%" align="center">
							<tr class="header">
								<th width="15%" style="text-align: center;">No.</th>
								<th width="15%" style="text-align: center;">Kode Barcode</th>
								<th width="30%" style="text-align: center;">Nama Barang</th>
								<th width="10%" style="text-align: center;">Jumlah</th>
								<th width="15%" style="text-align: center;">Harga</th>
								<th width="15%" style="text-align: center;">Total</th>
							</tr>
							@foreach ($pre_order_in as $key => $value)
							<tr>
								<input type="hidden" name="pre_order_in_id[]" value="{{$value->id}}">
								<input type="hidden" name="total_harga[]" value="{{$value->total}}">
								<td style="text-align: center;">{{$key +1}}</td>
								<td style="text-align: center;">{{$value->barang['kode_barcode']}}</td>
								<td style="text-align: center;">{{$value->barang['nama_barang']}}</td>
								<td style="text-align: center;">{{$value->qty}}</td>
								<td style="text-align: center;">{{number_format($value->harga_po)}}</td>
								<td style="text-align: center;">{{number_format($value->total)}}</td>
							</tr>
							@endforeach
						</table>
						</div>
						<br>
						<table width="90%" align="center">
							<tr>
								<th width="60%" height="30px"></th>
								<th width="15%" style="text-align: right;"><label>Jumlah Barang</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><label name="jumlahBarang" id="jumlahBarang">{{$jumlah_barang}}</label></th>
								<th width="5%"></th>
							</tr>
							<tr>
								<th width="60%" height="30px"></th>
								<th width="15%" style="text-align: right;"><label>Total Barang</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><label name="totalBarang" id="totalBarang">{{$total_barang}}</label></th>
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
									<select name="metode_pembayaran" class="form-control">
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
								<th width="17%" style="text-align: left;"><button class="btn btn-info btn-block">Save</button></th>
								<th width="5%"></th>
							</tr>
						</table>
					</form>
				</div>
				<div class="panel-footer">
				</div>
			</div>
		</form>
	</div>
</div>   
@endsection