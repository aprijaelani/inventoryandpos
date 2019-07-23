@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" onsubmit="return validateForm()" name="form_supplier_create" method="post" action="create">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Tambah Biaya Pendapatan</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Jenis</label>
						<div class="col-md-5">    
							<select name="jenis" class="form-control">
								<option value="1">Pendapatan</option>
								<option value="2">Biaya</option>
							</select>
						</div>
					</div>
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Keterangan</label>
						<div class="col-md-5">        
							<textarea name="keterangan" id="keterangan" class="form-control" rows="5"></textarea>
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotif">Keterangan Harus Diisi</span> 
							{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/biaya-pendapatan"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>   
<script type="text/javascript">
	function validateForm() {
		var bool = false;
	    var keterangan = document.forms["form_supplier_create"]["keterangan"].value;
	    var jenis = document.forms["form_supplier_create"]["jenis"].value;

	    if (keterangan == "") {
	    	$('#showNotif').show();
	    	bool = false;
	    }else if (jenis == 0) {
	    	$('#showNotif').hide();
	    	bool = false;
	    }else{
	    	bool = true;
	    }

	    return bool;
	}
</script>   
@endsection