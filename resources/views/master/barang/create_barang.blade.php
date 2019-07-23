@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form name="form_create" onsubmit="return validateForm()" class="form-horizontal" method="post" action="create">
			{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Tambah Barang</h3>
				</div>
				<div class="panel-body">
					<div class="row">    
						<div class="col-md-12">
							<div class="form-group {{ $errors->has('nama') ? 'has-error' : '' }}">
								<label class="col-md-3 col-xs-12 control-label">Nama Barang</label>
								<div class="col-md-6 col-xs-12">
									<input type="text" name="nama_barang" id="nama_barang" class="form-control" />
									<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifNamaBarang">Nama Barang Harus Diisi</span>
									<span style="color: red; text-align:left; display:none" id="checkBarang">Nama Barang Telah Digunakan</span>                                                      
								</div>
							</div>
							<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
								<label class="col-md-3 col-xs-12 control-label">Jenis Barang</label>
								<div class="col-md-6 col-xs-12">
									<select name="group_id" id="group_id" class="form-control select" data-live-search="true">
										<option value="0">Jenis Barang</option>
										@foreach ($groups as $group)
										<option value="{{ $group->id }}">{{$group->nama}}</option>
										@endforeach                   
									</select>
									<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifJenis">Jenis Barang Harus dipilih</span>
								</div>
							</div>
							<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
								<label class="col-md-3 col-xs-12 control-label">Minimal Harga</label>
								<div class="col-md-6 col-xs-12">                                                   
									<input style="text-align: right;" type="text" name="min_harga" id="min_harga" class="form-control" />
									<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifMinHarga">Minimal Harga harus diisi</span>  
									{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}                                                    
								</div>
							</div>
							<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
								<label class="col-md-3 col-xs-12 control-label">Harga Retail</label>
								<div class="col-md-6 col-xs-12">                                            
									<input style="text-align: right;" type="text" name="max_harga" id="max_harga" class="form-control" />
									{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
									<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifMaxHarga">Maksimal Harga harus diisi</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/barang"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var checkKode = true;
	var checkBarang = true;

	$(document).ready(function(){
		$('#min_harga').maskMoney({thousands:',', precision: 0});
		$('#max_harga').maskMoney({thousands:',', precision: 0});
		// $('#kode_supplier').on('input', function(){
	 //    	$('#showNotifKodeSup').hide();
		// 	var kode = $(this).val();
  //       	if (kode){
  //       		$.ajaxSetup({
		// 		    headers: {
		// 		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// 		    }
		// 		});
  //       		$.ajax({
  //                   url: '/barang/check_kode',
  //                   type: "POST",
  //             		data: {kode:kode},
  //                   dataType: "json",
  //                   success:function(data) {
  //                   	if (data == 1) {
  //                   		$('#checkKode').show();
  //                   		checkKode = false;
  //                   	}else{
  //                   		$('#checkKode').hide();
  //                   		checkKode = true;
  //                   	}             	
  //                   }
  //               });			
  //       	}
		// });

		// $('#nama_barang').on('input', function(){
	 //    	$('#showNotifNamaBarang').hide();
		// 	var kode = $(this).val();
  //       	if (kode){
  //       		$.ajaxSetup({
		// 		    headers: {
		// 		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// 		    }
		// 		});
  //       		$.ajax({
  //                   url: '/barang/check_barang',
  //                   type: "POST",
  //             		data: {kode:kode},
  //                   dataType: "json",
  //                   success:function(data) {
  //                   	if (data == 1) {
  //                   		$('#checkBarang').show();
  //                   		checkBarang = false;
  //                   	}else{
  //                   		$('#checkBarang').hide();
  //                   		checkBarang = true;
  //                   	}             	
  //                   }
  //               });			
  //       	}
		// });
	});

	function validateForm() {
		var bool = false;
		var kode_supplier = document.forms["form_create"]["kode_supplier"].value;
		var nama_barang = document.forms["form_create"]["nama_barang"].value;
		var supplier = document.forms["form_create"]["supplier_id"].value;
		var group = document.forms["form_create"]["group_id"].value;
		var min_harga = document.forms["form_create"]["min_harga"].value.split(',').join("");
		var max_harga = document.forms["form_create"]["max_harga"].value.split(',').join("");
		var checkHarga = false;

		if(parseInt(max_harga) <= parseInt(min_harga)){
			console.log(max_harga + "<" + min_harga);
			$('#showNotifMaxHarga').text("Maksimal harga harus lebih besar dari minimal")
			$('#showNotifMaxHarga').show();
			checkHarga = false;	
		}else{
			console.log(max_harga + ">" + min_harga);
			$('#showNotifMaxHarga').hide();
			checkHarga = true;
		}

		if (nama_barang == "") {
			$('#showNotifNamaBarang').show();
			bool = false;
		}else if (group == "0") {
			$('#showNotifJenis').show();
			bool = false;
		}else if (min_harga == "") {
			$('#showNotifMinHarga').show();
			bool = false;
		}else if (max_harga == "") {
			$('#showNotifMaxHarga').show();
			bool = false;
		}else if (!checkKode) {
			$('#checkKode').show();
			bool = false;
		}else if(!checkBarang){
			$('#checkBarang').show();
			bool = false;
		}else if(!checkHarga){
			bool = false;
		}else{
			$('#showNotifNamaBarang').hide();
			$('#showNotifKodeSup').hide();
			$('#showNotifNamaSupplier').hide();
			$('#showNotifJenis').hide();
			$('#showNotifMinHarga').hide();
			bool = true;
		}
		return bool;
	}
</script>   
@endsection