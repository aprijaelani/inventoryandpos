@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<form action="wo-invoices/invoice" onsubmit="return validateForm()" name="form_wo_invoice" method="post">
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
											<input type="text" name="kode_wo" id="kode_wo" class="form-control" readonly="true">      
										</div>
									</div>
									<br>
									<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
										<label class="col-md-3 col-xm-3">Tanggal WO</label>
										<div class="col-md-4 col-xm-4">    
											<input type="text" id="tanggal_wo" name="tanggal_wo" class="form-control" readonly="true">     
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
										<label class="col-md-3 col-xm-3">Tanggal</label>
										<div class="col-md-4 col-xm-4">    
											<input type="text" id="tanggal" value="{{$tanggal}}" name="tanggal" class="form-control datepicker">     
										</div>
									</div>
									<br>
									<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
										<label class="col-md-3 col-xm-3">No Nota</label>
										<div class="col-md-4 col-xm-4">   
											<div class="form-group has-feedback">
												<input style="text-transform:uppercase" type="text" id="search" name="search" class="form-control">
												<span class="glyphicon glyphicon-tasks form-control-feedback"></span>
											</div> 
											  
											<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifSearch">Tidak Ada Data</span>   
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-success">
						<div class="panel-body">
							<div class="col-md-12" align="center">
								<table class="table" width="80%" align="center" id="tableWO">
									<tr class="header">
										<th width="20%" style="text-align: center;">Kode Barang</th>
										<th width="30%" style="text-align: center;">Nama Barang</th>
										<th width="10%" style="text-align: center;">Jumlah</th>
										<th width="20%" style="text-align: center;">Harga</th>
										<th width="20%" style="text-align: center;">Total</th>
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
											<label class="col-md-offset-3 col-md-3">DP</label>
											<div class="col-md-6">    
												<input style="text-align: right;" type="text" id="dp" name="dp" class="form-control">  
												<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifDp">DP melebihi total harga barang</>    
											</div>
										</div>
									</div>
									<br>
									<div class="form-group">
										<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
											<label class="col-md-offset-3 col-md-3">Sisa</label>
											<div class="col-md-6" style="text-align: right;">    
												Rp. <label class="control-label" id="sisa"></label> <input type="text" id="sisas" name="sisas" class="hidden">     
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
						<div class="col-md-offset-10 col-md-2">
							<input type="submit" class="btn btn-default" id="btnSubmit" value="Simpan"/>
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
				<h4 class="modal-title">Daftar WO</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<table class="table" id="tableList">
						<thead>
							<tr>
								<th width="30%" style="text-align: center;">Kode Transaksi</th>
								<th width="30%" style="text-align: center;">No Nota</th>
								<th width="20%" style="text-align: left;">Tanggal</th>
								<th width="20%" style="text-align: left;">Sales Name</th>
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
	var checkHarga = true;
	var checkDiscount = true;
	var checkPihakKetiga = true;
	var checkPembayaran = false;
	var checkQty = true;
	var resultSPV = false;
	var numRow = 0;
	
	$(document).ready(function(){
		var rowTable = 0;

	    $('#discount').maskMoney({thousands:',', precision: 0});
	    $('#pihak_ketiga').maskMoney({thousands:',', precision: 0});
	    $('#dp').maskMoney({thousands:',', precision: 0});
	    $('#pembayaran').maskMoney({thousands:',', precision: 0});

		$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit
		$('#discount').attr('disabled','disabled'); //Disable Button Submit
		$('#pihak_ketiga').attr('disabled','disabled'); //Disable Button Submit
		$('#dp').attr('disabled','disabled'); //Disable Button Submit

		$(document).on('blur', '#search', function() {
			var search = $(this).val();
			console.log(search);
			if (search){
				$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/wo-invoices/work-order',
                    type: "POST",
              		data: {search:search},
                    dataType: "json",
                    success:function(data) {
						jQuery('#tableList tbody').html('');
						$('#tableList tbody').remove();
						console.log(data);
						var rowWO = 0;	
						if(data.length == 0){
							$('#showNotifSearch').show();
							var row = '<tr class="body"><th style="text-align: left;" colspan="4">Tidak Ada Data</th></tr>';
							$('#tableList').append(row)	
						}else{
							$('#showNotifSearch').hide();
							$.each(data, function(key, value) {					
								rowWO ++;
								$("#myModal").modal("show");

								var row = '<tr class="body"><th style="text-align: center;" class="id_wo hidden">'+value.id+'</th><th style="text-align: center;" class="kode_wo">'+value.kode_wo+'</th><th style="text-align: center; text-transform: uppercase;" class="no_nota">'+value.no_nota+'</th><th style="text-align:left;" class="tanggal_wo">'+value.tanggal+'</th><th style="text-align:left;" class="sales_name">'+value.nama+'</th><th style="text-align:left;" class="discount hidden">'+value.discount+'</th><th style="text-align:left;" class="pihak_ketiga hidden">'+value.pihak_ketiga+'</th><th style="text-align:left;" class="dp hidden">'+value.dp+'</th><th style="text-align:left;" class="pembayaran hidden">'+value.pembayaran+'</th><th style="text-align:left;" class="keterangan hidden">'+value.keterangan+'</th></tr>';
								$('#tableList').append(row);
							}); 	
						}
                    }
                });	
			}
		});

		$('#tableList').on( 'click', 'tr', function () {
			$("#myModal").modal('toggle');
			$('#btnSubmit').removeAttr('disabled'); //Enable Button Submit
		    var id_transaksi = $(this).closest('tr').children('th.id_wo').text();
		    var kode_transaksi = $(this).closest('tr').children('th.kode_wo').text();
		    var no_nota = $(this).closest('tr').children('th.no_nota').text();
		    var tanggal_wo = $(this).closest('tr').children('th.tanggal_wo').text();
		    var value_discount = $(this).closest('tr').children('th.discount').text();
		    var value_pihak_ketiga = $(this).closest('tr').children('th.pihak_ketiga').text();
		    var value_dp = $(this).closest('tr').children('th.dp').text();
		    var value_pembayaran = $(this).closest('tr').children('th.pembayaran').text();
		    var value_keterangan = $(this).closest('tr').children('th.keterangan').text();
		    var hitungTotal;
		    var hitungSubTotal = 0;
		    var row;
		    var stock;

		    console.log(id_transaksi);
		    if (id_transaksi){
				$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/wo-invoices/show-work-order',
                    type: "POST",
              		data: {search:id_transaksi},
                    dataType: "json",
                    success:function(data) {
						jQuery('.realBody').remove();
						// jQuery('#tableWO realBody').html('');
						// $('#tableWO realBody').remove();
						console.log(data);	
						if(data.length == 0){
							$('#showNotifSearch').show();
						}else{
							$('#showNotifSearch').hide();
							$.each(data, function(key, value) {		
								numRow ++;
								hitungTotal = value.qty * value.harga_wo;
								hitungSubTotal = hitungSubTotal + hitungTotal;
								stock = parseInt(value.total + value.qty);
								row = '<tr class="realBody" id="rowNum'+numRow+'"><th width="20%" style="text-align: center;"><div class="form-group has-feedback"><input class="form-control autocomplete" id="kode_'+numRow+'" name="kode[]" type="text" value="'+value.kode_barcode+'" readonly><span class="glyphicon glyphicon-tasks form-control-feedback"></span></div><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotif_'+numRow+'">Tidak Ada Data</span><input class="hidden" id="id_'+numRow+'" name="id_barang[]" type="text" value="'+value.id+'"></th><th width="30%" style="text-align: center;"><input class="form-control" type="text" id="nama_'+numRow+'" name"barang[]" value="'+value.nama_barang+'" readonly="true"/></th><th width="10%" style="text-align: center;"><input class="form-control math qty" type="number" id="qty_'+numRow+'" name="qty[]" value="'+value.qty+'" readonly><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifStock_'+numRow+'">Stok Tidak Tersedia</span><input class="form-control stock math hidden" type="text" id="stock_'+numRow+'" name="stock[]" value="'+stock+'"></th><th width="15%" style="text-align: center;"><input class="form-control math harga" style="text-align:right" type="text" id="harga_'+numRow+'" name="harga[]" value="'+toRp(value.harga_wo)+'"><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifHarga_'+numRow+'">Harga melampaui batas harga minimal</span><input class="hidden" id="max_harga_'+numRow+'" type="text" name="harga_max[]" value="'+value.max_harga+'"><input class="min_harga hidden" id="min_harga_'+numRow+'" type="text" name="harga_min[]" value="'+value.min_harga+'"></th><th width="15%" style="text-align: center;"><input class="form-control unmask total" type="text" id="total_'+numRow+'" style="text-align:right" name="total[]" value="'+toRp(hitungTotal)+'" readonly="true"></th></tr>';
								$('#tableWO').append(row);
								$('#sub_total').text(toRp(hitungSubTotal));
								$('#sub_totals').val(hitungSubTotal);

							    $('#total_harga').text(toRp(hitungSubTotal - value_discount - value_pihak_ketiga));
							    $('#total_hargas').val(hitungSubTotal - value_discount - value_pihak_ketiga);
							    $('#sisa').text(toRp(hitungSubTotal - value_discount - value_pihak_ketiga - value_dp));
							    $('#sisas').val(hitungSubTotal - value_discount - value_pihak_ketiga - value_dp);
							}); 	
						}
                    }
                });	
			}
		    $('#kode_wo').val(kode_transaksi);
		    $('#search').val(no_nota);
		    $('#tanggal_wo').val(tanggal_wo);
		    $('#discount').val(toRp(value_discount));
		    $('#pihak_ketiga').val(toRp(value_pihak_ketiga));
		    $('#dp').val(toRp(value_dp));
		    if (value_keterangan == "null") {
		    	value_keterangan = "";
		    }
		    $('#keterangan').val(value_keterangan);
		});	

		$(document).on('input', '.math', function() {
			var id_1 = this.id;
			var splitid_1 = id_1.split('_');
			var index_1 = splitid_1[1];

			console.log(index_1);
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

		$('#pembayaran').keyup(function() { 			
			var sisa = $('#sisas').val();
			var bayar = $(this).val().split(',').join("");
	        var bayarAngka = toAngka('Rp'+bayar);
			
			var result = bayarAngka - sisa;

			if(sisa > bayarAngka){
				checkPembayaran = false;
				$('#showNotifPembayaran').show();
			    $('#kembalian').text(toRp(result));	
			}else{
				checkPembayaran = true;
				$('#showNotifPembayaran').hide();
			    $('#kembalian').text(toRp(result));	
				console.log(result);
			}
		});
	});

	// $(".autocomplete").keyup(function(e) {
	//     if(e.which == 13) {
 //        e.preventDefault();
	// 		$("#myModal").modal("show");	
	//     }
	// });

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
            $('#showNotifSPV').show();
    	}
	}

	function toRp(angka){
		var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
		var rev2    = '';
		for(var i = 0; i < rev.length; i++){
			rev2  += rev[i];
			if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
				rev2 += ',';
			}
		}
		return rev2.split('').reverse().join('');
	}

	function toAngka(rp){return parseInt(rp.replace(/,.*|\D/g,''),10)}

	function update_amounts()
	{
	    var sum = 0.0;
	    $('tr.realBody').each(function() {
	        var total = $(this).find('.total').val().split(',').join("");
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

	    var discount = document.forms["form_wo_invoice"]["discount"].value.split(',').join("");
	    var pihak_ketiga = document.forms["form_wo_invoice"]["pihak_ketiga"].value.split(',').join("");
	    var dp = document.forms["form_wo_invoice"]["dp"].value.split(',').join("");
	    console.log(dp);
	    var discountAngka = toAngka('Rp'+discount);
	    var Angka = toAngka('Rp'+pihak_ketiga);
	    var dpAngka = toAngka('Rp'+dp);
	    if(isNaN(discountAngka)){
	    	discountAngka = 0;
	    }

	    if(isNaN(Angka)){
	    	Angka = 0;
	    }

	    if (isNaN(dpAngka)) {
	    	dpAngka = 0;
	    }

	    var result = sum - discountAngka - Angka;
	    var sisa = result - dpAngka;

		if(sum <= discountAngka+Angka){
			checkDiscount = false;
			checkPihakKetiga = false;
			$('#showNotifDiskon').show();
			$('#showNotifPihakKetiga').show();
		}else{
			$('#showNotifDiskon').hide();
		    $('#total_harga').text(toRp(result));
		    $('#total_hargas').val(result);
		    $('#sisa').text(toRp(sisa));
		    $('#sisas').val(sisa);	
			console.log(result);
		}

		if(sum <= Angka){
			$('#showNotifPihakKetiga').show();
		}else{
			$('#showNotifPihakKetiga').hide();
		    $('#total_harga').text(toRp(result));
		    $('#total_hargas').val(result);
		    $('#sisa').text(toRp(sisa));
		    $('#sisas').val(sisa);	
			console.log(result);
		}
	}

	function update_discount(){
		var sub_total = $('#sub_totals').val();
		var discount = $(this).val().split(',').join("");
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
	    var tanggal = document.forms["form_wo_invoice"]["tanggal"].value;
	    if (tanggal == "") {
	        $('#showNotifTanggal').show();
	        bool = false;
	    }else{
	    	$('#showNotifTanggal').hide();
	    	if (!checkPembayaran){
	    		console.log("pembayaran");
	    		bool = false;
	    		$('#showNotifPembayaran').show();
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
	    return bool;
	}

	$(document).on("keypress", 'form', function (e) {
	    var code = e.keyCode || e.which;
	    if (code == 13) {
	    	$('#search').blur();
	        e.preventDefault();
	        return false;
	    }
	});
</script>
@endsection