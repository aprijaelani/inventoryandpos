@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form name="form_create" onsubmit="return validateForm()" class="form-horizontal" method="post" action="update">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Ubah Barang</h3>
				</div>
				<div class="panel-body">
					<div class="col-md-12">
						<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label">Kode Supplier</label>
							<div class="col-md-6">                                            
								<input type="text" name="kode_supplier" value="{{$barang->kode_supplier}}" id="kode_supplier" class="form-control" placeholder="Kode Supplier" />
								<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifKodeSup">Kode Supplier harus diisi</span> 
								<span style="color: red; text-align:left; display:none" id="checkKode">Kode Supplier Telah Digunakan</span>  
								{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
							</div>
						</div>
	                   	
	                   	<div class="form-group{{ $errors->has('nama_barang') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label">Nama Barang</label>
							<div class="col-md-6">                                            
								<input type="text" name="nama_barang" value="{{$barang->nama_barang}}" id="nama_barang" class="form-control" placeholder="Nama Barang" />
								<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifNamaBarang">Nama Barang Harus Diisi</span>
								<span style="color: red; text-align:left; display:none" id="checkBarang">Nama Barang Telah Digunakan</span> 
							</div>
						</div>
						<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label">Nama Pemasok</label>
							<div class="col-md-6">                       
								<select name="supplier_id" class="form-control select" data-live-search="true">
	                 				<option value="{{ $barang->supplier_id }}" selected>{{$nama_supplier[0]['nama']}}</option>
									@foreach ($suppliers as $supplier)
									<option value="{{ $supplier->id }}">{{$supplier->nama}}</option>
									@endforeach 
								</select>
								<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifNamaSupplier">Supplier Harus dipilih</span>	 
							</div>
						</div>

						<div class="form-group{{ $errors->has('group_id') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label">Jenis Barang</label>
							<div class="col-md-6">                                            
								<select name="group_id" class="form-control select" data-live-search="true">
	                   				<option value="{{ $barang->grouping_id }}" selected>{{$nama_grouping[0]['nama']}}</option>
									@foreach ($groups as $group)
									<option value="{{ $group->id }}">{{$group->nama}}</option>
									@endforeach 
								</select>
								<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifJenis">Jenis Barang Harus dipilih</span>
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
	var checkKode = false;
	var checkBarang = false;

	$(document).ready(function(){
		$('#kode_supplier').on('input', function(){
	    	$('#showNotifKodeSup').hide();
			var kode = $(this).val();
        	if (kode){
        		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/barang/check_kode',
                    type: "POST",
              		data: {kode:kode},
                    dataType: "json",
                    success:function(data) {
                    	if (data == 1) {
                    		$('#checkKode').show();
                    		checkKode = false;
                    	}else{
                    		$('#checkKode').hide();
                    		checkKode = true;
                    	}             	
                    }
                });			
        	}
		});

		$('#nama_barang').on('input', function(){
	    	$('#showNotifNamaBarang').hide();
			var kode = $(this).val();
        	if (kode){
        		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/barang/check_barang',
                    type: "POST",
              		data: {kode:kode},
                    dataType: "json",
                    success:function(data) {
                    	if (data == 1) {
                    		$('#checkBarang').show();
                    		checkBarang = false;
                    	}else{
                    		$('#checkBarang').hide();
                    		checkBarang = true;
                    	}             	
                    }
                });			
        	}
		});
	});

	function validateForm() {
		var bool = false;
		var kode_supplier = document.forms["form_create"]["kode_supplier"].value;
		var nama_barang = document.forms["form_create"]["nama_barang"].value;
		var supplier = document.forms["form_create"]["supplier_id"].value;
		var group = document.forms["form_create"]["group_id"].value;

		if (kode_supplier == "") {
			$('#showNotifKodeSup').show();
			bool = false;
		}else if (nama_barang == "") {
			$('#showNotifNamaBarang').show();
			bool = false;
		}else if (supplier == "0") {
			$('#showNotifNamaSupplier').show();
			bool = false;
		}else if (group == "0") {
			$('#showNotifJenis').show();
			bool = false;
		}else if (!checkKode) {
			$('#checkKode').show();
			bool = false;
		}else if(!checkBarang){
			$('#checkBarang').show();
			bool = false;
		}else{
			$('#showNotifNamaBarang').hide();
			$('#showNotifKodeSup').hide();
			$('#showNotifNamaSupplier').hide();
			$('#showNotifJenis').hide();
			bool = true;
		}
		return bool;
	}
</script> 
@endsection