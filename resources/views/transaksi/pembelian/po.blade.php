@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
		<form name="form_real_invoice" onsubmit="return validateForm()" action="/po/store" method="post">
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
									<div class="col-md-5 col-xm-5">    
										<label class="control-label">{{$generate}}</label>      
									</div>
								</div>
								<br>
								<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
									<label class="col-md-3 col-xm-3">Tanggal</label>
									<div class="col-md-5 col-xm-5">    
										<input type="text" id="tanggal" name="tanggal" class="form-control datepicker" value="{{$tanggal}}">
										<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifTanggal">Tanggal harus diisi</span>   
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
									<label class="col-md-3 col-xm-4">Supplier</label>
									<div class="col-md-5 col-xm-5"> 
										<div class="form-group has-feedback">
											<input type="text" name="supplier_id" id="supplier_id" class="form-control">
											<span class="glyphicon glyphicon-tasks form-control-feedback"/>
										</div>    
										{{-- <select name="supplier_id" id="supplier_id" class="form-control select" data-live-search="true">	
											<option value="0">- Pilih Supplier -</option>
												@foreach ($suppliers as $supplier)
													<option value="{{ $supplier->id }}">{{$supplier->nama}}</option>
												@endforeach                   
										</select>   --}}    
									</div>
									<div class="col-md-4">
										<input type="text" name="suppliers_id" id="suppliers_id" class="hidden">
									</div>
								</div>
								<div class="col-md-offset-3 col-md-12">
									
										<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifSupplier">Supplier Tidak Tersedia</span> 
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
			</div>
					<div class="panel panel-success">
						<div class="panel-body">
							<div class="row">
							<div class="col-md-offset-6 col-md-6">
								<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
									<label class="col-md-4 col-xm-4">Total</label>
									<div class="col-md-6 col-xm-6" align="right">    
										Rp. 
										<label id="total_harga" class="control-label">0</label>
										<input class="hidden" type="text" name="total_hargas" id="total_hargas"/>      
									</div>
								</div>
								<br>
								<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
									<label class="col-md-4 col-xm-4">Tanggal Estimasi</label>
									<div class="col-md-6 col-xm-6">    
										<input type="text" name="tanggal_estimasi" id="tanggal_estimasi" class="form-control datepicker" value="{{$date_estimasi}}">   
									</div>
								</div>
								<div class="col-md-offset-4 col-md-6">
									<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifTanggalEstimasi">Tanggal Estimasi harus diisi</span> 
								</div>
								<br>
								<div style="padding: 5px" class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
									<label class="col-md-4 col-xm-4">Keterangan</label>
									<div class="col-md-6 col-xm-6">    
										<textarea rows="3" class="form-control" id="keterangan" name="keterangan"></textarea>    
									</div>
								</div>
								<br>
								<br>
								<br>
							</div>
						</div>
					</div>
					<div class="col-md-12" style="text-align: right;">
						<input type="submit" class="btn btn-primary" id="btnSubmit" value="Simpan"/>
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
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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

<div class="modal fade" id="modalSupplier" tabindex="-1" role="dialog" aria-labelledby="modalSup" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Daftar Supplier</h4>
    		</div>
    		<div class="modal-body">
    			<div class="row">
    				<table class="table" id="tableSupplier">
						<thead>
							<tr>
								<th width="60%" style="text-align: left;">Nama Supplier</th>
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

<script>
	var numRow = 0;
	var checkHarga = false;
	var checkQty = false;
	function AddRow()
	{
		if (numRow != 0) {
			var harga = $('#harga_'+numRow).val();
			if (harga == 0) {
				checkHarga = false;
				$('#showNotifHarga_'+numRow).show();
			}else{
				checkHarga = true;
				$('#showNotifHarga_'+numRow).hide();
			}
		}
		numRow ++;
		var row = '<tr class="realBody" id="rowNum'+numRow+'"><th width="20%" style="text-align: center;"><div class="form-group has-feedback"><input style="text-align: center;" class="form-control autocomplete" id="kode_'+numRow+'" name="kode[]" type="text" style="text-align="right"><span class="glyphicon glyphicon-tasks form-control-feedback"></span></div><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotif_'+numRow+'">Tidak Ada Data</span><input class="hidden" id="id_'+numRow+'" name="id_barang[]" type="text"></th><th width="30%" style="text-align: center;"><input class="form-control" style="color:black; text-align: left; type="text" id="nama_'+numRow+'" name"barang[]" readonly="true"/></th><th width="10%" style="text-align: center;"><input class="form-control math qty" type="text" id="qty_'+numRow+'" name="qty[]" style="text-align: right;"><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifQty_'+numRow+'">Tidak Ada Data</span></th><th width="15%" style="text-align: center;"><input class="form-control math harga" style="text-align:right" type="text" id="harga_'+numRow+'" name="harga[]"><span class="showNotif" style="color: red; text-align:right; display:none" id="showNotifHarga_'+numRow+'">Harga Wajib Diisi</span><input class="hidden" id="max_harga_'+numRow+'" type="text" name="harga_max[]"><input class="hidden" id="min_harga_'+numRow+'" type="text" name="harga_min[]"></th><th width="15%" style="text-align: center;"><input class="form-control unmask total" type="text" id="total_'+numRow+'" style="text-align:right;color:black;" name="total[]" readonly="true"></th><th width="10%"><input type="button" value="Hapus" class="btn btn-danger" onclick="removeRow('+numRow+');" class="btn btn-default btn-xs" title="Delete"></th></tr>';
	    $('#tableWO').append(row)

		$('#harga_'+numRow).prop('readonly', true);
		$('#qty_'+numRow).prop('readonly', true);
		$('#supplier_id').attr('disabled','disabled'); //Disable Button Tambah
        $('#btnSubmit').removeAttr('disabled'); //Enable Button Submit
	}

	function removeRow(rnum) {
    	jQuery('#rowNum'+rnum).remove();
		update_amounts();
		
		var rowCount = $('#tableWO tr').length;
		// console.log("row Count : " + rowCount);
		if(rowCount == 1){
        	$('#supplier_id').removeAttr('disabled'); //Enable Button Tambah
			$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit
		}
    }

    $(document).ready(function(){
    	var id;
		var index;
		var splitid;
		var supplier_id;
		var rowTable = 0;

		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah
		$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit

		$(document).on('blur', '#supplier_id', function(){
			console.log("supplier_id");
        	supplier_id = $('#supplier_id').val();
        	if (supplier_id){
        		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/real_invoice/supplier',
                    type: "POST",
              		data: {supplier_id:supplier_id},
                    dataType: "json",
                    success:function(data) {
						jQuery('#tableSupplier tbody').html('');
						$('#tableSupplier tbody').remove();
						console.log(data);
						var rowSupplier = 0;	
						if(data.length == 0){
							$('#showNotifSupplier').show();
							var row = '<tr class="body"><th style="text-align: left;" colspan="4">Tidak Ada Data</th></tr>';
							$('#tableSupplier').append(row)	
						}else{
							$('#showNotifSupplier').hide();
							$.each(data, function(key, value) {					
								rowSupplier ++;
								console.log(rowSupplier);
								if (data.length == 1) {
									$('#btnAddRow').removeAttr('disabled'); //Enable Button Tambah
									$("#modalSupplier").modal("hide");
									$('#supplier_id').val(value.nama);
									$('#suppliers_id').val(value.id);
								}else{
									$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah
									$("#modalSupplier").modal("show");
								} 

								var row = '<tr class="body"><th style="text-align: left;" class="nama_supplier">'+value.nama+'</th><th style="text-align:left;" class="id_supplier hidden">'+value.id+'</th></tr>';
								$('#tableSupplier').append(row);
							}); 	
						}
                    	
                    }
                });			
        	}
			
		});

		$('#tableSupplier').on('click', 'tr', function () {
			$('#btnAddRow').removeAttr('disabled'); //Enable Button Tambah
		    var supplier_id = $(this).closest('tr').children('th.id_supplier').text();
		    var nama_supplier = $(this).closest('tr').children('th.nama_supplier').text();

			$('#supplier_id').val(nama_supplier);
			$('#suppliers_id').val(supplier_id);
			$("#modalSupplier").modal('toggle');

		});	

		$(document).on('blur', '.autocomplete', function() {
			console.log("autocomplete");
			id = this.id;
			splitid = id.split('_');
			index = splitid[1];
        	var search = $('#kode_'+index).val();

        	supplier_id = $('#suppliers_id').val();
        	// console.log(supplier_id);
        	if (search){
        		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/real_invoice/kode_barcode',
                    type: "POST",
              		data: {id:search, supplier:supplier_id},
                    dataType: "json",
                    success:function(data) {
						jQuery('#tableList tbody').html('');
						$('#tableList tbody').remove();
						console.log(data);
						if(data.length == 0){
							$('#showNotif_'+index).show();
							var row = '<tr class="body"><th style="text-align: left;" colspan="4">Tidak Ada Data</th></tr>';
							$('#tableList').append(row)	
						}else{
							if (data.length == 1) {
								$.each(data, function(key, value) {			
								    $('#kode_'+index).val(value.kode_barcode);
								    $('#nama_'+index).val(value.nama_barang);
								    $('#qty_'+index).val('1');
								    $('#max_harga_'+index).val(value.price_list.max_harga);
								    $('#min_harga_'+index).val(value.price_list.min_harga);
								    $('#id_'+index).val(value.id);
									$('#harga_'+index).prop('readonly', false);
									$('#qty_'+index).prop('readonly', false);
								}); 
							}else{
                    			$("#myModal").modal("show");	
								$('#showNotif_'+index).hide();
								$.each(data, function(key, value) {
									rowTable ++; 

									var row = '<tr class="body" id="rowNum'+rowTable+'"><th style="text-align: center;" class="kode">'+value.kode_barcode+'</th><th style="text-align:left;" class="nama">'+value.nama_barang+'</th><th style="text-align:left;" class="id hidden">'+value.id+'</th><th style="text-align:left;" class="min_harga hidden">'+value.price_list.min_harga+'</th><th style="text-align:left;" class="max_harga hidden">'+value.price_list.max_harga+'</th></tr>';
									$('#tableList').append(row);
								});
							} 	
						}
                    	
                    }
                });	

    //             $(document).ajaxStart(function(){
	   //  			$("#myModal").modal("hide");	
				// }).ajaxStop(function(){
	   //  			$("#myModal").modal("show");	
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

		    console.log("Min Harga : "+min_harga);
		    console.log("Max Harga : "+max_harga);

		    $('#kode_'+index).val(kode_barcode);
		    $('#nama_'+index).val(nama_barang);
		    $('#qty_'+index).val('1');
		    $('#max_harga_'+index).val(max_harga);
		    $('#min_harga_'+index).val(min_harga);
		    $('#id_'+index).val(id_barang);
			$('#harga_'+index).prop('readonly', false);
			$('#qty_'+index).prop('readonly', false);
			$("#myModal").modal('toggle');

		});	

		$(document).on('input', '.math', function() {
			var id_1 = this.id;
			var splitid_1 = id_1.split('_');
			var index_1 = splitid_1[1];

		    $('#harga_'+index_1).maskMoney({thousands:',', precision: 0});
		    $('#qty_'+index_1).maskMoney({thousands:',', precision: 0});

			$('#harga_'+index_1).keyup(function() { 
				var qty = $('#qty_'+index_1).val().split(',').join("");
				var harga = $(this).val().split(',').join("");
				var total = qty * harga;
				if (harga == 0) {
					checkHarga = false;
					$('#showNotifHarga_'+index_1).show();
				}else{
					checkHarga = true;
					$('#showNotifHarga_'+index_1).hide();
					$('#total_'+index_1).val(toRp(total));
				}
			    update_amounts();
			});
			
			$('#qty_'+index_1).keyup(function(){
				var qty = $(this).val().split(',').join("");
			    var harga = $('#harga_'+index_1).val().split(',').join("");
			    var total = qty * harga;

			    if (qty == 0) {
			    	checkQty = false;
			    	$('#showNotifQty_'+index_1).show();
			    }else{
			    	checkQty = true;
			    	$('#showNotifQty_'+index_1).hide();
			   		$('#total_'+index_1).val(toRp(total));
			    }
			    update_amounts();
			})
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

	function toAngka(rp){return parseInt(rp.replace(/,.*|[^0-9]/g, ''), 10);}

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
	    $('#total_harga').text(toRp(sum));
	    $('#total_hargas').val(sum);
	}

	function validateForm() {
		var bool = false;
	    var tanggal = document.forms["form_real_invoice"]["tanggal"].value;
	    var harga = document.forms["form_real_invoice"]["total_hargas"].value;
	    var tanggal_estimasi = document.forms["form_real_invoice"]["tanggal_estimasi"].value;
	    // var harga = document.table["tableWO"][".qty"].value;
	    // console.log(harga);
	    if (tanggal == "") {
	        $('#showNotifTanggal').show();
	        bool = false;
	    }else{
	    	$('#showNotifTanggal').hide();
	    	if (tanggal_estimasi == "") {
    			$('#showNotifTanggalEstimasi').show();
    		}else{
    			$('#showNotifTanggalEstimasi').hide();
				if (harga == 0) {
	    			checkHarga = false;
	    			if (numRow > 0) {
	    				$('#showNotifHarga_1').show();	
	    			}	    			
	    		}else{
	    			if (!checkHarga) {
		    			bool = false;		
	    			}else{
	    				bool = true;
	    			}
	    		}
    		}
	    }
	    return bool;
	}
</script>
@endsection 