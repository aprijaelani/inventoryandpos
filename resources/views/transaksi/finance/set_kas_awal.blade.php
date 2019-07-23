@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" method="post" action="set_kas/create">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Buka Kas Toko</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('tambah_kas') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Kas Awal</label>
						<div class="col-md-5">                                            
							<input name="tambah_kas" id="tambah_kas" class="form-control" placeholder="Kas Tambahan" 
							@if ($check == null)
								type="text" value="0" required autofocus
							@else
								type="text" value="Rp. {{number_format($check->total)}}" readonly="true" 
							@endif
							/>
							{!! $errors->first('tambah_kas', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-md-12" style="text-align: center;">                 
						<button class="btn btn-primary pull-center" 
						@if ($check != null)
							disabled="true"
						@endif>
							Buka Toko
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>   
<script type="text/javascript">
	$(document).ready(function(){
		$('#tambah_kas').maskMoney({thousands:',', precision: 0});
	})

	function formatSatuan(angka, prefix){
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
		split	= number_string.split(','),
		sisa 	= split[0].length % 3,
		rupiah 	= split[0].substr(0, sisa),
		ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);

		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? rupiah + ' Pcs' : '');
	}
</script>
@endsection