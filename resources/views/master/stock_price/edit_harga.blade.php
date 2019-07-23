@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" onsubmit="return validateForm()" name="form_create" method="post" action="update">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Ubah Harga Barang</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('nama_barang') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Nama Barang</label>
						<div class="col-md-5">   
							<label class="control-label">{{$barang}}</label>              
						</div>
					</div>
					<div class="form-group{{ $errors->has('harga_minimum') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Harga Minimal</label>
						<div class="col-md-5">                 
							<input style="text-align: right;" type="text" name="harga_minimum" value="{{number_format($priceList->min_harga)}}" id="harga_minimum" class="form-control" placeholder="Harga Minimum" />
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifMinHarga">Minimal Harga harus diisi</span>  
						</div>
					</div>
					<div class="form-group{{ $errors->has('harga_maximum') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Harga Retail</label>
						<div class="col-md-5">                 
							<input style="text-align: right;" type="text" name="harga_maximum" value="{{number_format($priceList->max_harga)}}" id="harga_maximum" class="form-control" placeholder="Harga Maksimum" />
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifMaxHarga">Maksimal Harga harus diisi</span>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/price_list"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function(){
		$('#harga_maximum').maskMoney({thousands:',', precision: 0});
		$('#harga_minimum').maskMoney({thousands:',', precision: 0});
	});

	function validateForm() {
		var bool = false;
		var min_harga = document.forms["form_create"]["harga_minimum"].value.split(',').join("");
		var max_harga = document.forms["form_create"]["harga_maximum"].value.split(',').join("");

		if(parseInt(max_harga) <= parseInt(min_harga)){
			console.log(max_harga + "<" + min_harga);
			$('#showNotifMaxHarga').text("Maksimal harga harus lebih besar dari minimal")
			$('#showNotifMaxHarga').show();
			bool = false;	

		}else{
			console.log(max_harga + ">" + min_harga);
			$('#showNotifMaxHarga').hide();
			bool = true;
		}

		return bool;
	}
</script>    
@endsection