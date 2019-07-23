@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<form name="form_sales" onsubmit="return validateForm()" action="sales-invoices/store" method="post">
				{{ csrf_field() }}
				<div class="panel-heading">
					<h3 class="panel-title"><strong>Pemesanan Barang </strong></h3>
				</div>
				<div class="panel panel-body">
					<div class="panel panel-success">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-6">
									<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
										<label class="col-md-3 col-xm-3">Kode Transaksi</label>
										<div class="col-md-4 col-xm-4">    
											<label class="control-label">{{$generate}}</label>      
										</div>
									</div>
									<br>
									<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
										<label class="col-md-3 col-xm-3">No Nota</label>
										<div class="col-md-5 col-xm-5">    
											<input type="text" id="no_nota" name="no_nota" class="form-control" style="text-transform:uppercase">    
										</div>
									</div>
									<div class="col-md-offset-3 col-md-9">
										<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifNoNota">No Nota harus diisi</span>  
									</div>
								</div>
								<div class="col-md-6">
									<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
										<label class="col-md-3 col-xm-3">Tanggal</label>
										<div class="col-md-5 col-xm-5">    
											<input type="text" id="tanggal" value="{{$tanggal}}" name="tanggal" class="form-control datepicker">
											<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifTanggal">Tanggal harus diisi</span>      
										</div>
									</div>
									<br>
									<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
										<label class="col-md-3 col-xm-3">Sales</label>
										<div class="col-md-5 col-xm-5">    
											<select name="employee_id" id="employee_id" class="form-control select" data-live-search="true">	
												<option value="0">Pilih Nama Sales</option>
												@foreach ($employees as $employee)
												<option value="{{ $employee->id }}">{{$employee->nama}}</option>
												@endforeach                   
											</select>
											<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifSales">Sales harus dipilih</span>   
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-success">
						<dir class="row">
							<div class="col-sm-12" style="text-align: right">
								<input type="button" class="btn btn-info" id="btnAddRow" onclick="AddRow()" value="Tambah Data"/>
							</div>
						</dir>
						<div class="panel-body">
							<div class="col-md-12" align="center">
								<table class="table" width="90%" align="center" id="tableWO">
									<tr class="header">
										<th width="20%" style="text-align: center;">Kode Barang</th>
										<th width="30%" style="text-align: center;">Nama Barang</th>
										<th width="10%" style="text-align: center;">Jumlah</th>
										<th width="15%" style="text-align: center;">Harga</th>
										<th width="15%" style="text-align: center;">Total</th>
										<th width="10%"></th>
									</tr>								
								</table>
							</div>
						</div>
					</div>
					<div class="panel panel-success">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-3">Keterangan</label>
										<div class="col-md-8">    
											<textarea name="keterangan" id="keterangan" class="form-control" rows="5"></textarea>     
										</div>
									</div>
									<br>
									<div class="form-group">
										<div style="padding: 5px;margin-top: 75px;" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-3">Pembayaran</label>
											<div class="col-md-3">    
												<select name="type_pembayaran" class="form-control">
													<option value="1">Tunai</option>
													<option value="2">Non Tunai</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-offset-3 col-md-3">Sub Total</label>
											<div class="col-md-6" style="text-align: right;">    
												Rp. <label class="control-label" id="sub_total">0</label>
												<input class="hidden" type="text" name="sub_totals" id="sub_totals"/> 
											</div>
										</div>
									</div>
									<br>
									<div class="form-group">
										<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-offset-3 col-md-3">Discount</label>
											<div class="col-md-6">    
												<input style="text-align: right;" type="text" id="discount" name="discount" class="form-control"> 
												<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifDiskon">Nilai diskon melebihi total harga Barang</>
											</div>
										</div>
									</div>
									<br>
									<div class="form-group">
										<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-offset-3 col-md-3">Pihak Ketiga</label>
											<div class="col-md-6">    
												<input style="text-align: right;" type="text" id="pihak_ketiga" name="pihak_ketiga" class="form-control">      
												<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifPihakKetiga">Nilai claim melebihi total harga Barang</>
											</div>
										</div>
									</div>
									<br>
									<div class="form-group">
										<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-offset-3 col-md-3">Total Harga</label>
											<div class="col-md-6" style="text-align: right;">
												Rp. <label class="control-label" id="total_harga">0</label>
												<input type="text" id="total_hargas" name="total_hargas" class="hidden">      
											</div>
										</div>
									</div>
									<br>
									<div class="form-group">
										<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-offset-3 col-md-3">Pembayaran</label>
											<div class="col-md-6">    
												<input style="text-align: right;" type="text" id="pembayaran" name="pembayaran" class="form-control">  
												<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifPembayaran">Jumlah Pembayaran kurang dari total harga barang</>    
											</div>
										</div>
									</div>
									<br>
									<div class="form-group">
										<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-offset-3 col-md-3">Kembalian</label>
											<div class="col-md-6" style="text-align: right;">    
												Rp. <label class="control-label" id="kembalian"></label>    
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="col-md-12" style="text-align: right;">
							<input type="submit" class="btn btn-primary" id="btnSubmit" value="Simpan"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Daftar Barang</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<table class="table" id="tableList">
						<thead>
							<tr>
								<th width="40%" style="text-align: center;">Kode Barang</th>
								<th width="60%" style="text-align: left;">Nama Barang</th>
							</tr>
						</thead>
						<tbody>

						</tbody>								
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="message-box message-box-danger animated fadeIn" data-sound="fail" id="check_spv">
    <div class="mb-container">
        <div class="mb-middle">
            <div class="mb-title">SPV Confirmation !!!</div>
            <div class="mb-content">
                <div class="row">
                    <div class="col-md-4">
                        <input class="form-control" type="text" name="username" placeholder="Username" id="username"/> 
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" type="password" name="password" placeholder="Password" id="password"/> 
                    </div>
                    <div class="col-md-4">
                    	<div class="col-md-6">
                    		<input type="button" class="btn btn-primary mb-control-close" name="btnCheckSpv" id="btnCheckSpv" onclick="checkSPV()" value="Konfirmasi"> 
                    	</div>
                    	<div class="col-md-6">
                    		<input type="button" class="btn btn-default mb-control-close" name="closeSPV" id="closeSPV" onclick="closeSPV()" value="Batal"> 
                    	</div>
                    	<span class="showNotifSPV" style="color: white; text-align:left; display:none" id="showNotifSPV">Username dan Password Konfirmasi Salah</span>
                    </div>
                </div>                  
            </div>
        </div>
    </div>
</div>

<script>
	var checkHarga = false;
	var checkDiscount = true;
	var checkPihakKetiga = true;
	var checkPembayaran = false;
	var checkQty = false;
	var checkNoNota = false;
	var resultSPV = false;
	var numRow = 0;
	function AddRow()
	{
		numRow ++;
		console.log(numRow);
		var row = '<tr class="realBody" id="rowNum'+numRow+'"><th width="20%" style="text-align: center;"><div class="form-group has-feedback"><input style="text-align:center" class="form-control autocomplete" id="kode_'+numRow+'" name="kode[]" type="text"><span class="glyphicon glyphicon-tasks form-control-feedback"></span></div><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotif_'+numRow+'">Tidak Ada Data</span><input class="hidden" id="id_'+numRow+'" name="id_barang[]" type="text"></th><th width="30%" style="text-align: center;"><input class="form-control" type="text" id="nama_'+numRow+'" name"barang[]" readonly="true"/></th><th width="10%" style="text-align: center;"><input style="text-align:right" class="form-control math qty" type="text" id="qty_'+numRow+'" name="qty[]"><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifStock_'+numRow+'">Stok Tidak Tersedia</span><input class="form-control stock math hidden" type="text" id="stock_'+numRow+'" name="stock[]"></th><th width="15%" style="text-align: center;"><input style="text-align:right" class="form-control math harga" style="text-align:right" type="text" id="harga_'+numRow+'" name="harga[]"><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifHarga_'+numRow+'">Harga melampaui batas harga minimal</span><input class="hidden" id="max_harga_'+numRow+'" type="text" name="harga_max[]"><input class="min_harga hidden" id="min_harga_'+numRow+'" type="text" name="harga_min[]"></th><th width="15%" style="text-align: center;"><input class="form-control unmask total" type="text" id="total_'+numRow+'" style="text-align:right" name="total[]" readonly="true"></th><th width="10%"><input type="button" value="Hapus" class="btn btn-danger" onclick="removeRow('+numRow+');" id="delete" class="btn btn-default btn-xs" title="Hapus"></th></tr>';
		$('#tableWO').append(row)


		$('#harga_'+numRow).prop('readonly', true);
		$('#qty_'+numRow).prop('readonly', true);
        $('#btnSubmit').removeAttr('disabled'); //Enable Button Submit
        $('#discount').removeAttr('disabled'); //Enable Button Submit
        $('#pihak_ketiga').removeAttr('disabled'); //Enable Button Submit
        $('#pembayaran').removeAttr('disabled'); //Enable Button Submit
	}

	function removeRow(rnum) {
		jQuery('#rowNum'+rnum).remove();
		update_amounts();

		var rowCount = $('#tableWO tr').length;
		// console.log("row Count : " + rowCount);
		if(rowCount == 1){
			$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit
			$('#discount').attr('disabled','disabled'); //Disable Button Submit
			$('#pihak_ketiga').attr('disabled','disabled'); //Disable Button Submit
			$('#pembayaran').attr('disabled','disabled'); //Disable Button Submit
		}
	}
	
	$(document).ready(function(){
		var id;
		var index;
		var splitid;
		var rowTable = 0;

	    $('#discount').maskMoney({thousands:',', precision: 0});
	    $('#pihak_ketiga').maskMoney({thousands:',', precision: 0});
	    $('#pembayaran').maskMoney({thousands:',', precision: 0});

		$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit
		$('#discount').attr('disabled','disabled'); //Disable Button Submit
		$('#pihak_ketiga').attr('disabled','disabled'); //Disable Button Submit
		$('#pembayaran').attr('disabled','disabled'); //Disable Button Submit

		$(document).on('blur', '.autocomplete', function() {
			id = this.id;
			splitid = id.split('_');
			index = splitid[1];
			var kode = $('#kode_'+index).val();
			if (kode){
				$.ajax({
					url: '/wo/kode_barcode/'+kode,
					type: "GET",
					dataType: "json",
					success:function(data) {
						rowTable = 0;
						jQuery('#tableList tbody').html('');
						$('#tableList tbody').remove();
						if(data.length == 0){
							$('#showNotif_'+index).show();
							var row = '<tr class="body"><th style="text-align: left;" colspan="4">Tidak Ada Data</th></tr>';
							$('#tableList').append(row)	
						}else{
							$('#showNotif_'+index).hide();
							var check = 0;
							$.each(data, function(key, value) {
								rowTable ++; 

								if (rowTable == 1) {

									if (value.total == 0) {
								    	checkQty = false;
								    	$('#showNotifStock_'+index).show();
								    }else{
								    	checkQty = true;
								    	$('#showNotifStock_'+index).hide();
								    }

									$("#myModal").modal("hide");
								    $('#kode_'+index).val(value.kode_barcode);
								    $('#nama_'+index).val(value.nama_barang);
								    $('#qty_'+index).val('1');
								    $('#id_'+index).val(value.barang_id);
								    $('#max_harga_'+index).val(value.max_harga);
								    $('#min_harga_'+index).val(value.min_harga);
								    $('#stock_'+index).val(value.total);
									$('#harga_'+index).prop('readonly', false);
									$('#qty_'+index).prop('readonly', false);
								}else{
									$("#myModal").modal("show");
								}
								console.log(value);
								check ++;
								var row = '<tr class="body" id="rowNum'+rowTable+'"><th style="text-align: center;" class="kode">'+value.kode_barcode+'</th><th style="text-align:left;" class="nama">'+value.nama_barang+'</th><th style="text-align:left;" class="id hidden">'+value.barang_id+'</th><th style="text-align:left;" class="min_harga hidden">'+value.min_harga+'</th><th style="text-align:left;" class="max_harga hidden">'+value.max_harga+'</th><th style="text-align:left;" class="stok hidden">'+value.total+'</th></tr>';
								$('#tableList').append(row);
							}); 	
						}
					}
				});	

				// $(document).ajaxStart(function(){
	   //  			$("#myModal").modal("hide");	
				// 	// $('#loading').show();
				// }).ajaxStop(function(){
	   //  			$("#myModal").modal("show");	
				// 	// $('#loading').hide();
				// });	
			}
		});

		$('#tableList').on( 'click', 'tr', function () {
		    // var id = $(this).closest('tr').attr('id');
		    var kode_barcode = $(this).closest('tr').children('th.kode').text();
		    var nama_barang = $(this).closest('tr').children('th.nama').text();
		    var min_harga = $(this).closest('tr').children('th.min_harga').text();
		    var max_harga = $(this).closest('tr').children('th.max_harga').text();
		    var id_barang = $(this).closest('tr').children('th.id').text();
		    var stock = $(this).closest('tr').children('th.stok').text();

		    console.log(min_harga);
		    console.log(stock);

		    if (stock == 0) {
		    	checkQty = false;
		    	$('#showNotifStock_'+index).show();
		    }else{
		    	checkQty = true;
				$('#showNotifStock_'+index).hide();
		    }

		    $('#kode_'+index).val(kode_barcode);
		    $('#nama_'+index).val(nama_barang);
		    $('#qty_'+index).val('1');
		    $('#max_harga_'+index).val(max_harga);
		    $('#min_harga_'+index).val(min_harga);
		    $('#id_'+index).val(id_barang);
		    $('#stock_'+index).val(stock);
			$('#harga_'+index).prop('readonly', false);
			$('#qty_'+index).prop('readonly', false);
			$("#myModal").modal('toggle');

		});	

		$(document).on('input', '.math', function() {
			var id_1 = this.id;
			var splitid_1 = id_1.split('_');
			var index_1 = splitid_1[1];

			console.log(id_1);
		    $('#harga_'+index_1).maskMoney({thousands:',', precision: 0});
			$('#harga_'+index_1).keyup(function() { 
				var qty = $('#qty_'+index_1).val();
				var harga = parseInt($(this).val().split(',').join(""));
				var total = qty * harga;
				var min_harga = parseInt($('#min_harga_'+index_1).val());

				console.log(harga + "<" + min_harga);
				if(harga < min_harga){
					console.log("Show");
					$('#showNotifHarga_'+index_1).show();
					checkHarga = false;
				}else{
					console.log("Hide");
					checkHarga = true;
					$('#showNotifHarga_'+index_1).hide();
				}

				$('tr.realBody').each(function() {
			        var loopHarga = parseInt($(this).find('.harga').val().split(',').join(""));
			        var loopMinHarga = parseInt($(this).find('.min_harga').val().split(',').join(""));

			        if(loopHarga < loopMinHarga){
						checkHarga = false;
					}
			    });

				$('#total_'+index_1).val(toRp(total));
			    update_amounts();
			});
			$('#qty_'+index_1).bind('input', function(){
				var qty = $(this).val();
				var check_stock = $('#stock_'+index_1).val();
			    var harga = $('#harga_'+index_1).val().split(',').join("");
			    var total = qty * harga;

			    console.log("check Stock : " + check_stock);

			    if (qty == 0) {
			    	checkQty = false;
			    	$('#showNotifStock_'+index_1).show();
			    }else{
			    	if (parseInt(check_stock) < qty) {
				    	checkQty = false;
				    	$('#showNotifStock_'+index_1).show();
				    }else{
				    	checkQty = true;
				    	$('#showNotifStock_'+index_1).hide();
				    }
			    }


				$('tr.realBody').each(function() {
			        var loopQty = parseInt($(this).find('.qty').val().split(',').join(""));
			        var loopStock = parseInt($(this).find('.stock').val().split(',').join(""));

			        if (parseInt(loopStock) < loopQty) {
				    	checkQty = false;
				    }
			    });

			    $('#total_'+index_1).val(toRp(total));
			    update_amounts();
			});
		});

		$('#discount').keyup(function() { 			
			var sub_total = $('#sub_totals').val();
			var discount = toAngka('Rp' + $(this).val().split(',').join(""));
			var pihak_ketiga = toAngka('Rp' + $('#pihak_ketiga').val().split(',').join(""));

			if(isNaN(discount)) {
				discount = 0;
			}

	        if(isNaN(pihak_ketiga)) {
				pihak_ketiga = 0;
			}

			var result = sub_total - discount - pihak_ketiga;

			console.log(discount+pihak_ketiga +">" + sub_total);

			if(discount+pihak_ketiga > sub_total){
				checkDiscount = false;
				$('#showNotifDiskon').show();
			}else{
				checkDiscount = true;
				checkPihakKetiga = true;
				$('#showNotifPihakKetiga').hide();
				$('#showNotifDiskon').hide();
			    $('#total_harga').text(toRp(result));
			    $('#total_hargas').val(result);	
				console.log(result);
			}
		});

		$('#pihak_ketiga').keyup(function() { 			
			var sub_total = $('#sub_totals').val();
			var pihak_ketiga = $(this).val().split(',').join("");
	        var Angka = toAngka('Rp'+pihak_ketiga);
			var discount = $('#discount').val().split(',').join("");
	        var discountAngka = toAngka('Rp'+discount);

	        if(isNaN(Angka)) {
				Angka = 0;
			}

	        if(isNaN(discountAngka)) {
				discountAngka = 0;
			}
			
			var result = sub_total - discountAngka - Angka;

			console.log(discountAngka+Angka +">" + sub_total);

			if(Angka+discountAngka > sub_total){
				checkPihakKetiga = false;
				$('#showNotifPihakKetiga').show();
			}else{
				checkPihakKetiga = true;
				checkDiscount = true;
				$('#showNotifDiskon').hide();
				$('#showNotifPihakKetiga').hide();
			    $('#total_harga').text(toRp(result));
			    $('#total_hargas').val(result);	
				console.log(result);
			}
		});

		$('#pembayaran').keyup(function() { 			
			var sub_total = $('#total_hargas').val();
			var pembayaran = parseInt($(this).val().split(',').join(""));
			
			var result = pembayaran - sub_total;

			if(pembayaran < parseInt(sub_total)){
				checkPembayaran = false;
				$('#showNotifPembayaran').show();
			}else{
				checkPembayaran = true;
				$('#showNotifPembayaran').hide();
			    $('#kembalian').text(toRp(result));	
				console.log(result);
			}
		});

		$('#no_nota').on('input', function(){
	    	$('#showNotifNoNota').hide();
			var kode = $(this).val();
        	if (kode){
        		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/wo/check_nota',
                    type: "POST",
              		data: {nota:kode},
                    dataType: "json",
                    success:function(data) {
                    	console.log(data);
                    	if (data >= 1) {
                    		$('#showNotifNoNota').show();
                    		$('#showNotifNoNota').text("No Nota Telah Digunakan");
                    		checkNoNota = false;
                    	}else{
                    		$('#showNotifNoNota').hide();
                    		$('#showNotifNoNota').text("No Nota Harus Diisi");
                    		checkNoNota = true;
                    	}             	
                    }
                });			
        	}
		});
	});

	$(document).on("keypress", 'form', function (e) {
	    var code = e.keyCode || e.which;
	    if (code == 13) {
	    	$('.autocomplete').blur();
	        e.preventDefault();
	        return false;
	    }
	});

	function closeSPV(){
		$('#check_spv').modal('toggle');
	}

	function checkSPV(){
		var username = $('#username').val();
		var password = $('#password').val();

		if (username != '' && password != ''){
    		$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
    		$.ajax({
                url: '/wo/check_harga',
                type: "POST",
          		data: {username:username, password:password},
                dataType: "json",
                success:function(data) {
                	console.log(data);
                	if (data == 1) {
                		$('#showNotifSPV').hide();
                		$('#check_spv').modal('toggle');
                		resultSPV = true;
						jQuery(function(){
						   jQuery('#btnSubmit').click();
						});
                	}else{
                		$('#showNotifSPV').show();
                	}               	
                }
            });			
    	}else{
    		alert("Check Username Password");
    	}
	}

	function toRp(angka){
		var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
		var rev2    = '';
		for(var i = 0; i < rev.length; i++){
			rev2  += rev[i];
			if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
				rev2 += '.';
			}
		}
		return rev2.split('').reverse().join('');
	}

	function toAngka(rp){return parseInt(rp.replace(/,.*|\D/g,''),10)}

	function update_amounts()
	{
	    var sum = 0.0;
	    $('tr.realBody').each(function() {
	        var total = $(this).find('.total').val();
	        var totalAngka = toAngka('Rp'+total);
	        if(isNaN(totalAngka)) {
				totalAngka = 0;
			}
	        if(totalAngka != 0){
	        	sum+=totalAngka;
	        }
	    });
	    //just update the total to sum  
	    $('#sub_total').text(toRp(sum));
	    $('#sub_totals').val(sum);

	    var discount = document.forms["form_sales"]["discount"].value;
	    var pihak_ketiga = document.forms["form_sales"]["pihak_ketiga"].value;
	    var discountAngka = toAngka('Rp'+discount);
	    var Angka = toAngka('Rp'+pihak_ketiga);
	    if(isNaN(discountAngka)){
	    	discountAngka = 0;
	    }

	    if(isNaN(Angka)){
	    	Angka = 0;
	    }

	    var result = sum - discountAngka - Angka;
	    console.log(discountAngka + " : " + Angka);

		if(sum <= discountAngka+Angka){
			checkDiscount = false;
			checkPihakKetiga = false;
			$('#showNotifDiskon').show();
			$('#showNotifPihakKetiga').show();
		    $('#total_harga').text(toRp(result));
		    $('#total_hargas').val(result);	
		}else{
			$('#showNotifDiskon').hide();
		    $('#total_harga').text(toRp(result));
		    $('#total_hargas').val(result);	
			console.log(result);
		}

		if(sum <= Angka){
			$('#showNotifPihakKetiga').show();
		}else{
			$('#showNotifPihakKetiga').hide();
		    $('#total_harga').text(toRp(result));
		    $('#total_hargas').val(result);	
			console.log(result);
		}
	}

	function update_discount(){
		var sub_total = $('#sub_totals').val();
		var discount = $(this).val();
        var discountAngka = toAngka('Rp'+discount);
		var result = sub_total - discountAngka;

		console.log(sub_total+"<=" + discountAngka);

		if(sub_total <= discountAngka){
			$('#showNotifDiskon').show();
		}else{
			$('#showNotifDiskon').hide();
		    $('#total_harga').text(toRp(result));
		    $('#total_hargas').val(result);	
			console.log(result);
		}
	}

	function validateForm() {
		var bool = false;
	    var tanggal = document.forms["form_sales"]["tanggal"].value;
	    var sales = document.forms["form_sales"]["employee_id"].value;
	    var no_nota = document.forms["form_sales"]["no_nota"].value;
	    if (tanggal == "") {
	        $('#showNotifTanggal').show();
	        bool = false;
	        // break;
	    }else{
	    	$('#showNotifTanggal').hide();
	    	if(sales == "0"){
		    	$('#showNotifSales').show();
		    	bool = false;
		    	// break;
		    }else{
		    	$('#showNotifSales').hide();
		    	if (no_nota == "") {
		    		$('#showNotifNoNota').show();
                    $('#showNotifNoNota').text("No Nota Harus Diisi");
		    		bool = false;
		    	}else{
		    		if (!checkPembayaran){
			    		console.log("pembayaran");
			    		bool = false;
			    		$('#showNotifPembayaran').show();
			    	}else if (!checkQty){
			    		console.log("qty");
			    		bool = false;
			    	}else if (!checkNoNota){
		    			$('#showNotifNoNota').show();
			    		console.log("No Nota");
			    		bool = false;
			    	}else{
			    		if (checkHarga) {
			    			bool = true;
				    	}else{
			    			console.log("harga");
				    		console.log(bool);
				    		if (resultSPV) {
				    			bool = true;
				    		}else{	
					    		bool = false;
					    		$('#check_spv').modal('show');
				    		}
				    	}
			    	}
		    	}
		    }
	    }
	    return bool;
	}
</script>
@endsection