@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" name="form_create" onsubmit="return validateForm()" method="post" id="myform" action="pindah-gudang/proses">
			{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><strong>Pindah Gudang</strong></h3>
				</div>
				<div class="panel-body">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="form-group{{ $errors->has('username') ? 'has-error' : '' }}">
								<label class="col-md-4 control-label">Gudang Asal</label>
								<div class="col-md-5">                                            
									<select name="gudang_id_asal" id="gudang_id_asal" class="form-control select" data-live-search="true">
										<option value="0">Pilih Gudang Asal</option>
										@foreach ($gudangs as $gudang)
										<option value="{{$gudang->id}}">{{$gudang->nama}}</option>
										@endforeach              
									</select>
									<input type="hidden" name="gudang_asal" id="gudang_asal">
								</div>
							</div>
							<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
								<label class="col-md-4 control-label">Gudang Tujuan</label>
								<div class="col-md-5">                                            
									<select name="gudang_id_tujuan" id="gudang_id_tujuan" class="form-control select" data-live-search="true">
										<option value="0">Pilih Gudang Tujuan</option>
										@foreach ($gudangs as $gudang)
										<option value="{{$gudang->id}}">{{$gudang->nama}}</option>
										@endforeach            
									</select>
									<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifGudang">Gudang Tujuan Tidak Boleh Sama</span>
									<input type="hidden" name="gudang_tujuan" id="gudang_tujuan">
								</div>
							</div>
						</div>
					</div>
					

					<div class="panel panel-default">
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
										<th width="10%" style="text-align: center;">Stock</th>
										<th width="15%" style="text-align: center;">Jumlah</th>
										<th width="10%"></th>
									</tr>								
								</table>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>Keterangan</strong></h3>
						</div>
						<div class="panel-body">
							<textarea class="form-control" rows="10" name="keterangan"></textarea>
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
	</div>
</div>
<script>
	var numRow = 0;
	var checkStock = true;
	var checkBarang = false;
	function AddRow()
	{
		numRow ++;
		console.log(numRow);
		var row = '<tr class="realBody" id="rowNum'+numRow+'"><th width="20%" style="text-align: center;"><div class="form-group has-feedback"><input style="text-align: center;" class="form-control autocomplete" id="kode_'+numRow+'" name="kode[]" type="text" autofocus><span class="glyphicon glyphicon-tasks form-control-feedback"></span></div><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotif_'+numRow+'">Tidak Ada Data</span><input class="hidden" id="idbarang_'+numRow+'" name="id_barang[]" type="text"></th><th width="30%" style="text-align: center;"><input class="form-control" style="text-align: left;color:black;" type="text" id="namabarang_'+numRow+'" name"namabarang[]" readonly="true"/><th width="30%" style="text-align: center;"><input class="form-control lastqty" style="text-align: right;color:black;" type="text" id="lastqty_'+numRow+'" name"lastqty[]" readonly="true"/></th><th width="10%" style="text-align: right;color:black;"><input class="form-control math qty" type="text" id="qty_'+numRow+'" name="qty[]" style="text-align: right;color:black;"><span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifQty_'+numRow+'">Data Tidak Sesuai</span></th><th width="10%"><input type="button" value="hapus" class="btn btn-danger" onclick="removeRow('+numRow+');" class="btn btn-default btn-xs" title="Delete"></th></tr>';
	    $('#tableWO').append(row)

		$('#harga_'+numRow).prop('readonly', true);
		$('#qty_'+numRow).prop('readonly', true);
		$('#gudang_id_asal').attr('disabled','disabled'); //Disable Button Tambah
		$('#gudang_id_tujuan').attr('disabled','disabled'); //Disable Button Tambah
        $('#btnSubmit').removeAttr('disabled'); //Enable Button Submit
	}

	function removeRow(rnum) {
    	jQuery('#rowNum'+rnum).remove();
		update_amounts();
		
		var rowCount = $('#tableWO tr').length;
		if(rowCount == 1){
        	$('#gudang_id_asal').removeAttr('disabled'); //Enable Button Tambah
        	$('#gudang_id_tujuan').removeAttr('disabled'); //Enable Button Tambah
			$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit
		}
    }

    $(document).ready(function(){
    	var id;
		var index;
		var splitid;
		var rowTable = 0;

		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah
		$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit

		$(document).on('change','#gudang_id_asal', function(){
        	var gudang_id_asal = $('#gudang_id_asal').val();
        	var gudang_id_tujuan = $('#gudang_id_tujuan').val();
		    $('#gudang_asal').val(gudang_id_asal);

        	if (gudang_id_asal != 0 && gudang_id_tujuan != 0){
				if (gudang_id_asal == gudang_id_tujuan){
		    		$('#showNotifGudang').show();
		    		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah
		    	}else{
		    		$('#showNotifGudang').hide();	
					$('#btnAddRow').removeAttr('disabled'); //Enable Button Tambah
		    	} 
        	}else{
        		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah			
        	}
		});
		$(document).on('change','#gudang_id_tujuan', function(){
        	var gudang_id_asal = $('#gudang_id_asal').val();
        	var gudang_id_tujuan = $('#gudang_id_tujuan').val();
		    $('#gudang_tujuan').val(gudang_id_tujuan);

        	if (gudang_id_asal != 0 && gudang_id_tujuan != 0){
				if (gudang_id_asal == gudang_id_tujuan){
		    		$('#showNotifGudang').show();
		    		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah
		    	}else{
		    		$('#showNotifGudang').hide();	
					$('#btnAddRow').removeAttr('disabled'); //Enable Button Tambah
		    	} 
        	}else{
        		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah			
        	}
		});
		// $(document).on('change','#sales_id', function(){
  //       	var gudang_id_asal = $('#gudang_id_asal').val();
  //       	var gudang_id_tujuan = $('#gudang_id_tujuan').val();
  //       	var sales_id = $('#sales_id').val();
  //       	if (gudang_id_asal == gudang_id_tujuan){
  //       		$('#showNotifGudang').show();
  //       		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah
  //       	}else{
  //       		$('#showNotifGudang').hide();	
		// 		$('#btnAddRow').removeAttr('disabled'); //Enable Button Tambah
  //       	} 

  //       	if (gudang_id_asal != 0 && gudang_id_tujuan != 0 && sales_id !=0){
		// 		$('#btnAddRow').removeAttr('disabled'); //Enable Button Tambah
  //       	}else{
  //       		$('#btnAddRow').attr('disabled','disabled'); //Disable Button Tambah			
  //       	}
		// });


		$(document).on('blur', '.autocomplete', function() {
			console.log("autocomplete");
			id = this.id;
			splitid = id.split('_');
			index = splitid[1];
        	var search = $('#kode_'+index).val();

        	gudang_id_asal = $('#gudang_id_asal').val();
        	if (search){
        		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/pindah-gudang/barang',
                    type: "POST",
              		data: {id:search, gudang_id:gudang_id_asal},
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
							$('#showNotif_'+index).hide();
							if (data.length == 1) {	
								checkBarang = true;
								$.each(data, function(key, value) {
									$('#kode_'+index).val(value.kode_barcode);
								    $('#namabarang_'+index).val(value.nama_barang);
								    $('#idbarang_'+index).val(value.barang_id);
								    $('#lastqty_'+index).val(value.total);
								    $('#qty_'+index).val('1');
								    $('#qty_'+index).prop('readonly', false);
								}); 
							}else{
                    			$("#myModal").modal("show");
								$.each(data, function(key, value) {
									rowTable ++; 
									var row = '<tr class="body" id="rowNum'+rowTable+'"><th style="text-align: center;" class="barang_id hidden">'+value.barang_id+'</th><th style="text-align:left;" class="kode_barcode">'+value.kode_barcode+'</th><th style="text-align:left;" class="nama_barang">'+value.nama_barang+'</th><th style="text-align: center;" class="last_qty hidden">'+value.total+'</th></tr>';
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
		    var barang_id = $(this).closest('tr').children('th.barang_id').text();
		    var nama_barang = $(this).closest('tr').children('th.nama_barang').text();
		    var kode_barcode = $(this).closest('tr').children('th.kode_barcode').text();
		    var last_qty = $(this).closest('tr').children('th.last_qty').text();

		    console.log(last_qty)

		    $('#kode_'+index).val(kode_barcode);
		    $('#namabarang_'+index).val(nama_barang);
		    $('#idbarang_'+index).val(barang_id);
		    $('#lastqty_'+index).val(last_qty);
		    $('#qty_'+index).val('1');
		    $('#qty_'+index).prop('readonly', false);
			$("#myModal").modal('toggle');
			checkBarang = true;

		});	

		$(document).on('input', '.math', function() {
			var id_1 = this.id;
			var splitid_1 = id_1.split('_');
			var index_1 = splitid_1[1];

		    $('#qty_'+index_1).maskMoney({thousands:'.', decimal:',', precision: 0});
		    $('#lastqty_'+index_1).maskMoney({thousands:'.', decimal:',', precision: 0});

			$('#qty_'+index_1).keyup(function() { 
				var stock = $('#lastqty_'+index_1).val().split('.').join("");
				var qty = $(this).val().split('.').join("");
				if (qty <= 0) {
					checkStock = false;
					$('#showNotifQty_'+index_1).text('Jumlah Tidak Boleh Nol');
					$('#showNotifQty_'+index_1).show();
				}else{
					if (parseInt(stock) < qty) {
						checkStock = false;
						$('#showNotifQty_'+index_1).text('Jumlah Tidak Boleh Lebih Besar Dari Stock');
						$('#showNotifQty_'+index_1).show();
					}else{
						checkStock = true;
						$('#showNotifQty_'+index_1).hide();
					}
				}

				$('tr.realBody').each(function() {
			        var loopStock = parseInt($(this).find('.lastqty').val());
			        var loopQty = parseInt($(this).find('.qty').val());
			        console.log(loopStock+'<'+loopQty);
			        if (parseInt(loopStock) < loopQty) {
				    	checkStock = false;
				    }
			    });

			});
			
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
	    var gudang_asal = document.forms["form_create"]["gudang_id_asal"].value;
	    var gudang_tujuan = document.forms["form_create"]["gudang_id_tujuan"].value;

	    if (gudang_asal == gudang_tujuan) {
    		$('#showNotifGudang').show();
    		bool = false
    	}else{
    		$('#showNotifGudang').hide();

			if (!checkStock) {
				console.log("checkStock");
    			bool = false;		
			}else if (!checkBarang) {
				console.log("checkBarang");
				bool = false;
			}else{
				bool = true;
			}
    	}
	    return bool;
	}
</script>
@endsection 