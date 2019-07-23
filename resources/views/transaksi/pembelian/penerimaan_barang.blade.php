@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><strong>Penerimaan Barang </strong></h3>
				</div>
				<div class="col-md-12">
					<table width="100%" align="center">	
						<tr>
							<th width="10%" height="30px" style="text-align: right;"><label>Search By</label></th>
							<th width="5%" style="text-align: center;"><label> : </label></th>
							<td width="25%">
                                <div class="form-group col-md-12">
									<input type="radio" name="chpas" id="option1" checked />&nbsp Kode PO &nbsp &nbsp
									<input type="radio" name="chpas" id="option2" />&nbsp Supplier
                                </div>
							</td>
							<td width="60%"></td>
						</tr>
						<form action="penerimaan/show-po" method="post">
						{{ csrf_field() }}
						<tr id="child_option_1">
							<th width="10%" height="40px" style="text-align: right;"></th>
							<th width="5%" style="text-align: center;"></th>
							<td width="25%" style="text-align: left;">
								<div>
									<select name="kode_po" class="form-control select" data-live-search="true" style="display: none;">
									@foreach ($pre_orders as $pre_order)
	                                    <option value="{{$pre_order->id}}">{{$pre_order->kode_po}}</option>
              						@endforeach
	                                </select>
								</div>
							</td>
							<td width="60%"><button class="btn btn-info"><i class="fa fa-search"></i></button></td>
						</tr>
						</form>
						<form action="penerimaan/show-supplier" method="post">
						{{ csrf_field() }}
						<tr id="child_option_2" style="display:none">
							<th width="10%" height="40px" style="text-align: right;"></th>
							<th width="5%" style="text-align: center;"></th>
							<td width="25%" style="text-align: left;">
								<div>
									<select name="supplier_id" class="form-control select" data-live-search="true" style="display: none;">
										@foreach ($suppliers as $supplier)
		                                    <option value="{{$supplier->supplier->id}}">{{$supplier->supplier->nama}}</option>
	              						@endforeach
	                                </select>
								</div>
							</td>
							<td width="60%"><button class="btn btn-info"><i class="fa fa-search"></i></button></td>
						</tr>
						</form>
					</table>
				</div>
				<br>
				<div class="col-md-12">
					<table style="margin-top: 10px" rules="none" width="100%" border="2" bordercolor="#EEF2F7">
					<tr>
						<th width="10%" height="40px" style="text-align: right;"><label>No. Surat Jalan</label></th>
						<th width="5%" style="text-align: center;"><label> : </label></th>
						<td width="25%" style="text-align: left;"><input type="text" name="suratJalan" class="form-control" disabled="true"></td>
						<td width="60%"></td>
					</tr>
					<tr id="child_option_3">
						<th style="text-align: right;" height="40px"><label>Tanggal PO</label></th>
						<th style="text-align: center;"><label> : </label></th>
						<td style="text-align: left;"><label name="tanggalPo" id="tanggalPo">00-00-0000</label></td>
						<td></td>
					</tr>
					<tr>
						<th style="text-align: right;" height="40px"><label>Supplier</label></th>
						<th style="text-align: center;"><label> : </label></th>
						<td style="text-align: left;"><label name="pemasok" id="pemasok">-</label></td>
						<td></td>
					</tr>
				</table>
				</div>
				<br>
				<div class="col-md-12" align="center">
					<!-- <table class="table" width="90%" align="center">
					<tr class="header">
						<th width="15%" style="text-align: center;">No.</th>
						<th width="15%" style="text-align: center;">Kode Barang</th>
						<th width="30%" style="text-align: center;">Nama Barang</th>
						<th width="10%" style="text-align: center;">Jumlah</th>
						<th width="15%" style="text-align: center;">Harga</th>
						<th width="15%" style="text-align: center;">Total</th>
						<th width="10%" style="text-align: center;">Lengkap</th>
					</tr>
					<tr>	
					</tr>
				</table> -->
				</div>
				<br>
				
				<br>
				<div class="panel-footer">
				</div>
			</div>
	</div>
</div>   
<script type="text/javascript">
	// var dengan_satuan = document.getElementById('dengan-satuan');
	// dengan_satuan.addEventListener('keyup', function(e)
	// {
	// 	dengan_satuan.value = formatSatuan(this.value, ' Pcs');
	// });

	// function formatSatuan(angka, prefix){
	// 	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	// 	split	= number_string.split(','),
	// 	sisa 	= split[0].length % 3,
	// 	rupiah 	= split[0].substr(0, sisa),
	// 	ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);

	// 	if (ribuan) {
	// 		separator = sisa ? '.' : '';
	// 		rupiah += separator + ribuan.join('.');
	// 	}
		
	// 	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	// 	return prefix == undefined ? rupiah : (rupiah ? rupiah + ' Pcs' : '');
	// }

	$("#option1").click(function() {
	  $("#child_option_2").hide();
	  $("#child_option_1").show();
	  $("#child_option_3").show();
	});
	$("#option2").click(function() {
	  $("#child_option_1").hide();
	  $("#child_option_2").show();
	  $("#child_option_3").hide();
	});
</script>
@endsection