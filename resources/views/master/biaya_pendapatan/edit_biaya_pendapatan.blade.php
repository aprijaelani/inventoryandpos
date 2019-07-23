@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" onsubmit="return validateForm()" name="form_supplier_edit" method="post" action="update">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Ubah Biaya Pendapatan</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Jenis</label>
						<div class="col-md-5">    
							<select name="jenis" class="form-control">
								@if($biayaPendapatan->jenis == 1)
									<option value="1" selected="true">Pendapatan</option>
									<option value="2">Biaya</option>
								@else
									<option value="1">Pendapatan</option>
									<option value="2" selected="true">Biaya</option>
								@endif
							</select>
						</div>
						<input type="hidden" name="id" value="{{$biayaPendapatan->id}}">
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Keterangan</label>
						<div class="col-md-5">        
							<textarea name="keterangan" id="keterangan" class="form-control" rows="5">{{$biayaPendapatan->nama}}</textarea>
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
	    var nama = document.forms["form_supplier_edit"]["keterangan"].value;

	    if (nama == "") {
	        $('#showNotif').show();
	        bool = false;
	    }else{
	    	bool = true;
	    }
	    return bool;
	}
</script>  
@endsection