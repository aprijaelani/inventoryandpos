@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" onsubmit="return validateForm()" name="form_group_create" method="post" action="create">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Tambah Jenis Barang</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Jenis Barang</label>
						<div class="col-md-5">                                            
							<input type="text" name="nama" id="nama" class="form-control" placeholder="Jenis Barang" autofocus />
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifGroup">Jenis Barang Harus Diisi</span> 
							<span style="color: red; text-align:left; display:none" id="checkGroup">Jenis Barang Telah Digunakan</span> 
							{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/grouping"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>  
<script type="text/javascript">
	var checkGroup = true;

	// $(document).ready(function(){
	// 	$('#nama').on('input', function(){
	//     	$('#showNotifGroup').hide();
	// 		var group = $(this).val();
 //        	if (group){
 //        		$.ajaxSetup({
	// 			    headers: {
	// 			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 			    }
	// 			});
 //        		$.ajax({
 //                    url: '/grouping/check_group',
 //                    type: "POST",
 //              		data: {group:group},
 //                    dataType: "json",
 //                    success:function(data) {
 //                    	if (data >= 1) {
 //                    		$('#checkGroup').show();
 //                    		checkGroup = false;
 //                    	}else{
 //                    		$('#checkGroup').hide();
 //                    		checkGroup = true;
 //                    	}             	
 //                    }
 //                });			
 //        	}
	// 	});
	// });

	function validateForm() {
		var bool = false;
	    var nama = document.forms["form_group_create"]["nama"].value;

	    if (nama == "") {
	        $('#showNotifGroup').show();
	        bool = false;
	    }else{
	    	if (!checkGroup) {
                $('#checkGroup').show();
	    		bool = false;
	    	}else{
                $('#checkGroup').hide();
	    		$('#showNotifGroup').hide();
	    		bool = true;
	    	}
	    }
	    return bool;
	}
</script> 
@endsection