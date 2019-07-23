@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" onsubmit="return validateForm()" name="form_supplier_create" method="post" action="create">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Tambah Supplier</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Nama Supplier</label>
						<div class="col-md-5">                                            
							<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Supplier" autofocus />
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifSupplier">Nama Supplier Harus Diisi</span> 
							<span style="color: red; text-align:left; display:none" id="checkSupplier">Nama Supplier Telah Digunakan</span> 
							{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/supplier"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>   
<script type="text/javascript">
	var checkSupplier = true;

	// $(document).ready(function(){
	// 	$('#nama').on('input', function(){
	//     	$('#showNotifSupplier').hide();
	// 		var supplier = $(this).val();
 //        	if (supplier){
 //        		$.ajaxSetup({
	// 			    headers: {
	// 			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 			    }
	// 			});
 //        		$.ajax({
 //                    url: '/supplier/check_supplier',
 //                    type: "POST",
 //              		data: {supplier:supplier},
 //                    dataType: "json",
 //                    success:function(data) {
 //                    	if (data >= 1) {
 //                    		$('#checkSupplier').show();
 //                    		checkSupplier = false;
 //                    	}else{
 //                    		$('#checkSupplier').hide();
 //                    		checkSupplier = true;
 //                    	}             	
 //                    }
 //                });			
 //        	}
	// 	});
	// });

	function validateForm() {
		var bool = false;
	    var nama = document.forms["form_supplier_create"]["nama"].value;

	    if (nama == "") {
	        $('#showNotifSupplier').show();
            $('#checkSupplier').hide();
	        bool = false;
	    }else{
	    	if (!checkSupplier) {
                $('#checkSupplier').show();
	    		bool = false;
	    	}else{
                $('#checkSupplier').hide();
	    		$('#showNotifSupplier').hide();
	    		bool = true;
	    	}
	    }
	    return bool;
	}
</script>   
@endsection