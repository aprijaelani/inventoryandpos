@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" method="post" action="po_invoice/create-invoice">
				{{ csrf_field() }}
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
										<select name="no_sj" class="form-control select" data-live-search="true" style="display: none;">
										@foreach ($pre_orders as $pre_order)
		                                    <option>{{$pre_order->no_sj}}</option>
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
								<td width="25%" style="text-align: left;"></td>
								<td width="40%"></td>
							</tr>
							<tr>
								<th style="text-align: right;" height="40px"><label>Tanggal PO</label></th>
								<th style="text-align: center;"><label> : </label></th>
								<td style="text-align: left;"><input type="text" name="tanggal" value="" class="form-control datepicker"></td>
								<td></td>
							</tr>
							<tr>
								<th style="text-align: right;" height="40px"><label>Pemasok</label></th>
								<th style="text-align: center;"><label> : </label></th>
								<td style="text-align: left;"><label name="pemasok" id="pemasok">Pemasok</label></td>
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
								<tr>
									<td style="text-align: center;"></td>
									<td style="text-align: center;"></td>
									<td style="text-align: left;"></td>
									<td style="text-align: center;"></td>
									<td style="text-align: center;"></td>
									<td style="text-align: center;"></td>	
								</tr>
							</table>
						</div>
						<br>
						<table width="90%" align="center">
							<tr>
								<th width="60%" height="30px"></th>
								<th width="15%" style="text-align: right;"><label>Jumlah Barang</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><label name="jumlahBarang" id="jumlahBarang">- Pcs</label></th>
								<th width="5%"></th>
							</tr>
							<tr>
								<th width="60%" height="30px"></th>
								<th width="15%" style="text-align: right;"><label>Total Barang</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><label name="totalBarang" id="totalBarang">- Pcs</label></th>
								<th width="5%"></th>
							</tr>
							<tr>
								<th width="60%" height="50px"></th>
								<th width="15%" style="text-align: right;"><label>Tanggal Pembayaran</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><input class="form-control datepicker" type="text" name="tanggalEstimasi"></th>
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
								<th width="17%" style="text-align: left;"><button class="btn btn-info btn-block" disabled="true" >Save</button></th>
								<th width="5%"></th>
							</tr>
						</table>
					{{-- <div id="nonPreOrder" style="display:none">
						<div class="col-md-12">
							<table style="margin-top: 10px" width="100%">
							<tr>
								<th width="30%" height="40px" style="text-align: right;"><label>No. Surat Jalan</label></th>
								<th width="5%" style="text-align: center;"><label> : </label></th>
								<td width="25%" style="text-align: left;">XXXXXX</td>
								<td width="40%"></td>
							</tr>
							<tr>
								<th style="text-align: right;" height="40px"><label>Tanggal PO</label></th>
								<th style="text-align: center;"><label> : </label></th>
								<td style="text-align: left;"><input type="text" name="tanggal" value="" class="form-control datepicker"></td>
								<td></td>
							</tr>
							<tr>
								<th style="text-align: right;" height="40px"><label>Pemasok</label></th>
								<th style="text-align: center;"><label> : </label></th>
								<td style="text-align: left;">
									<select name="supplierName" class="form-control select" data-live-search="true" style="display: none;">
										@foreach ($suppliers as $supplier)
		                                    <option value="{{$supplier->id}}">{{$supplier->nama}}</option>
	              						@endforeach
	                                </select></td>
								<td><button class="btn btn-info"><i class="fa fa-plus"></i></button></td>
							</tr>
						</table>
						</div>
						<br>
						<table width="90%" border="2" rules="none" align="center" bordercolor="#EEF2F7">
							<tr>
								<th width="10%" style="text-align: center; height: 50px">Jumlah</th>
								<th width="20%" style="text-align: center;">Kode Barang</th>
								<th width="40%" style="text-align: center;">Nama Barang</th>
								<th width="20%" style="text-align: center;">Harga Satuan</th>	
								<th width="10%"></th>
							</tr>
							<tr>
								<th width="10%" style="height: 50px; text-align: center;"><input type="text" onKeyUp="multiply()" name="qty" id="qty" style="width: 50px;" value="1"></th>
								<th width="15%" style="text-align: center;">
									<select name="kode_barcode" id="kode_barcode" class="form-control select" data-live-search="true">
									<option value="">Pilih Kode Barcode</option>
									</select>
								</th>
								<th width="30%" style="text-align: center;">
								<label id="nama_barang"></label>
								</th>
								<th width="15%" style="text-align: center;"><input style="text-align: center;width: 150px; align-items: center;" type="text" name="price" id="dengan-rupiah"/></th>
								<th rowspan="2" width="10%" style="text-align: center;"><button id="add" class="btn btn-info">Submit</button></th>
							</tr>
						</table>
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
								<tr>
									<td style="text-align: center;">1</td>
									<td style="text-align: center;"></td>
									<td style="text-align: left;"></td>
									<td style="text-align: center;"></td>
									<td style="text-align: center;"></td>
									<td style="text-align: center;"></td>	
								</tr>
							</table>
						</div>
						<br>
						<table width="90%" align="center">
							<tr>
								<th width="60%" height="30px"></th>
								<th width="15%" style="text-align: right;"><label>Jumlah Barang</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><label name="jumlahBarang" id="jumlahBarang">10 Pcs</label></th>
								<th width="5%"></th>
							</tr>
							<tr>
								<th width="60%" height="30px"></th>
								<th width="15%" style="text-align: right;"><label>Total Barang</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><label name="totalBarang" id="totalBarang">10 Pcs</label></th>
								<th width="5%"></th>
							</tr>
							<tr>
								<th width="60%" height="50px"></th>
								<th width="15%" style="text-align: right;"><label>Tanggal Pembayaran</label></th>
								<th width="3%" style="text-align: center;"><label> : </label></th>
								<th width="17%" style="text-align: left;"><input class="form-control datepicker" type="text" name="tanggalEstimasi"></th>
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
								<th width="15%" style="text-align: right;">	</th>
								<th width="3%" style="text-align: center;"></th>
								<th width="17%" style="text-align: left;"><button class="btn btn-info btn-block" disabled="true">Save</button></th>
								<th width="5%"></th>
							</tr>
						</table>		
					</div> --}}
				</div>
				<div class="panel-footer">
				</div>
			</div>
		</form>
	</div>
</div>   
<script type="text/javascript">
</script>
@endsection