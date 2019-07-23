@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form name="form_create" onsubmit="return validateForm()" class="form-horizontal" method="post" action="import" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Import Barang</h3>
				</div>
				<div class="panel-body">
					<div class="row">    
						<div class="col-md-12">
							<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
								<label class="col-md-3 col-xs-12 control-label">Upload File</label>
								<div class="col-md-6 col-xs-12">                                                   
									<input type="file" name="import" class="form-control" />
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
</script>   
@endsection