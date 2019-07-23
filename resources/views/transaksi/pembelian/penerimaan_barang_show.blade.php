@extends('layouts.header')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Penerimaan Barang </strong></h3>
			</div>
			<div class="col-md-12">
				<table width="100%">	
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
					<form action="/penerimaan/show-po" method="post">
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
					<form action="/penerimaan/show-supplier" method="post">
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
				<form onsubmit="return validateForm()" name="form_penerimaan" method="post" action="update">
					{{ csrf_field() }}
					<input type="hidden" value="{{$data[0]->pre_order_id}}" name="pre_order_id">
					<table style="margin-top: 10px" rules="none" width="100%" border="2" bordercolor="#EEF2F7">
						<tr>
							<th width="10%" height="40px" style="text-align: right;"><label>No. Surat Jalan</label></th>
							<th width="5%" style="text-align: center;"><label> : </label></th>
							<td width="25%" style="text-align: left;">
								<input type="text" name="no_sj" id="no_sj" class="form-control" style="text-transform:uppercase">
								<span style="color: red; text-align:left; display:none" id="checkNoSJ">No Surat Jalan Telah Digunakan</span> 
							</td>
							<td width="60%"></td>
						</tr>
						<tr id="child_option_3">
							<th style="text-align: right;" height="40px"><label>Tanggal SJ.</label></th>
							<th style="text-align: center;"><label> : </label></th>
							<td style="text-align: left;"><input type="text" id="tanggal_sj" name="tanggal_sj" class="form-control datepicker" value="{{$tanggal}}">
								<span style="color: red; text-align:left; display:none" id="checkTanggalSJ">Tanggal SJ. Tidak Boleh Kosong</span></td>
							<td></td>
						</tr>
						<tr id="child_option_3">
							<th style="text-align: right;" height="40px"><label>Tanggal PO</label></th>
							<th style="text-align: center;"><label> : </label></th>
							<td style="text-align: left;"><label name="tanggalPo" id="tanggalPo">{{$data[0]->pre_order['tanggal']}}</label></td>
							<td></td>
						</tr>
						<tr>
							<th style="text-align: right;" height="40px"><label>Supplier</label></th>
							<th style="text-align: center;"><label> : </label></th>
							<td style="text-align: left;"><label name="pemasok" id="pemasok"></label>{{$supplier_mine->supplier['nama']}}</td>
							<td></td>
						</tr>
						<tr>
							<th style="text-align: right;" height="40px"><label>Gudang</label></th>
							<th style="text-align: center;"><label> : </label></th>
							<td style="text-align: left;">
							<select name="gudang" class="form-control select" data-live-search="true" style="display: none;">
								@foreach ($gudangs as $gudang)
								<option value="{{$gudang->id}}">{{$gudang->nama}}</option>
								@endforeach
							</select>
							<td></td>
						</tr>
						</table>
					</div>
					<br>
					<div class="col-md-12" align="center">
						<table class="table" width="90%" align="center">
							<tr class="header">
								<th width="5%" style="text-align: center;">No.</th>
								<th width="15%" style="text-align: center;">Kode Barang</th>
								<th width="30%" style="text-align: center;">Nama Barang</th>
								<th width="10%" style="text-align: center;">Jumlah Pesan</th>
								<th width="10%" style="text-align: center;">Jumlah Diterima</th>
							</tr>
							@foreach ($data as $index => $datas)
							<tr class="realBody">
								<td style="text-align: center;">{{ $index +1 }}</td>
								<input type="hidden" value="{{ $datas->id }}" name="pre_order_out_id[]" value="{{ $datas->barang['nama_barang'] }}" style="text-align: center;" readonly>
								<td style="text-align: center;"><input type="text" class="form-control" value="{{ $datas->barang['kode_barcode'] }}" class="form-control" name="kode_barcode[]" value="{{ $datas->barang['nama_barang'] }}" style="text-align: center;color: black;" readonly></td>
								<td style="text-align: center;"><input class="form-control" type="text" name="nama_barang[]" value="{{ $datas->barang['nama_barang'] }}" style="text-align: left;color: black;" readonly></td>
								<td style="text-align: center;"><input type="text" class="form-control qty" id="qty_{{$index}}" name="qty[]" value="{{ $datas->jumlah_pesan }}" style="text-align: right;color: black;" readonly></td>
								<td style="text-align: center;"><input type="text" class="form-control qty_diterima" min="0" style="text-align: right;color: black;" id="qtyditerima_{{$index}}" name="qty_diterima[]" style="width: 75px"><span style="color: red; text-align:left; display:none" id="checkQtyDiterima_{{$index}}">Qty Diterima Tidak Boleh Kosong</span> </td>
								<input class="form-control" type="hidden" name="harga_po[]" value="{{ $datas->harga_po }}" style="text-align: left;color: black;" readonly>
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
							<th width="17%" style="text-align: left;"><label name="jumlahBarang" id="jumlahBarang">{{$total_qty->total_qty}}</label></th>
							<th width="5%"></th>
						</tr>
						<tr>
							<th width="60%" height="50px"></th>
							<th width="15%" style="text-align: right;"><label>Keterangan</label></th>
							<th width="3%" style="text-align: center;"><label> : </label></th>
							<th width="17%" style="text-align: left;"><textarea rows="3" class="form-control" id="keterangan" name="keterangan"></textarea></th>
							<th width="5%"></th>
						</tr>
					</table>
					<br>
					<table width="90%" align="center">
						<tr>
							<th width="60%" height="50px"></th>
							<th width="15%" style="text-align: right;"></th>
							<th width="3%" style="text-align: center;"></th>
							<th width="17%" style="text-align: left;"><button class="btn btn-info btn-block">Simpan</button>
								<span style="color: red; text-align:left; display:none" id="chcekValidasi"></span> 
							</th>
							<th width="5%"></th>
						</tr>
					</table>
				</form>	
				<div class="panel-footer">
				</div>
			</div>
		</div>
	</div>   
	<script type="text/javascript">
		var checkSJ = false;
		var checkQtyDiterima = false;

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

		$(document).ready(function(){
			$('#no_sj').on('input', function(){
				$('#checkNoSJ').hide();
				var no_sj = $(this).val();
				if (no_sj){
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						url: '/penerimaan/check_no_sj',
						type: "POST",
						data: {kode:no_sj},
						dataType: "json",
						success:function(data) {
							if (data >= 1) {
								$('#checkNoSJ').text("No Surat Jalan Telah Digunakan")
								$('#checkNoSJ').show();
								checkSJ = false;
							}else{
								$('#checkNoSJ').hide();
								checkSJ = true;
							}             	
						}
					});			
				}
			});

			$(document).on('input', '.qty_diterima', function(){
				var value = this.id;
				var splitid = value.split('_');
				var index = splitid[1];
				var qty = parseInt($('#qty_'+index).val());
				var qty_diterima = parseInt($(this).val());

				if (qty_diterima < 0) {
					$('#checkQtyDiterima_'+index).text("Qty Tidak Boleh Kosong");
					$('#checkQtyDiterima_'+index).css("color", "red");
					$('#checkQtyDiterima_'+index).show();
					checkQtyDiterima = false;
				}else if (qty_diterima > qty){
					$('#checkQtyDiterima_'+index).text("Qty Melebihi Pesanan Barang");
					$('#checkQtyDiterima_'+index).css("color", "green");
					$('#checkQtyDiterima_'+index).show();
					checkQtyDiterima = true;
				}else{
					$('#checkQtyDiterima_'+index).hide();
					checkQtyDiterima = true;
				}
			});
		});

		function validateForm() {
			var bool = false;
			var checkQty = false;
			var tanggal_pembayaran = document.forms["form_penerimaan"]["tanggal_pembayaran"].value;
			var no_sj = document.forms["form_penerimaan"]["no_sj"].value;
			var tanggal_sj = document.forms["form_penerimaan"]["tanggal_sj"].value;

			console.log(tanggal_sj);

			$('tr.realBody').each(function() {
				var loopQtyDiterima = parseInt($(this).find('.qty_diterima').val().split('.').join(""));
				var loopQty = parseInt($(this).find('.qty').val().split('.').join(""));

				if (isNaN(loopQtyDiterima)) {
					loopQtyDiterima = 0;
				}
				console.log(loopQtyDiterima);
				if (loopQtyDiterima <= 0) {
					$('#chcekValidasi').text("Tolong Cek Qty Diterima");
					$('#chcekValidasi').show();
					checkQty = false;
				}
			});

			if (tanggal_sj == "") {
				$('#checkTanggalSJ').show();
				bool = false;
			}else if (tanggal_pembayaran == "") {
				$('#checkTanggalPembayaran').show();
				bool = false;
			}else if (no_sj == ""){
				$('#checkNoSJ').text("No Surat Jalan Tidak Boleh Kosong");
				$('#checkNoSJ').show();
				bool = false;
			}else if(!checkSJ){
				bool = false;
			}else if (!checkQtyDiterima) {
				bool = false;
			}else{
				$('#checkTanggalSJ').hide();
				$('#checkTanggalPembayaran').hide();
				$('#checkNoSJ').hide();
				bool = true;
			}
			return bool;
		}
	</script>
	@endsection