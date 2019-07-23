@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" onsubmit="return validateForm()" name="form_sales_create" method="post" action="create">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Tambah Sales</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Nama Sales</label>
						<div class="col-md-5">                                            
							<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Sales" autofocus />
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifSales">Nama Sales Harus Diisi</span> 
							<span style="color: red; text-align:left; display:none" id="checkSales">Nama Sales Telah Digunakan</span> 
							{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/employee"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div> 
<script type="text/javascript">
	var checkSales = true;

	// $(document).ready(function(){
	// 	$('#nama').on('input', function(){
	//     	$('#showNotifSales').hide();
	// 		var sales = $(this).val();
 //        	if (sales){
 //        		$.ajaxSetup({
	// 			    headers: {
	// 			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 			    }
	// 			});
 //        		$.ajax({
 //                    url: '/employee/check_sales',
 //                    type: "POST",
 //              		data: {sales:sales},
 //                    dataType: "json",
 //                    success:function(data) {
 //                    	if (data >= 1) {
 //                    		$('#checkSales').show();
 //                    		checkSales = false;
 //                    	}else{
 //                    		$('#checkSales').hide();
 //                    		checkSales = true;
 //                    	}             	
 //                    }
 //                });			
 //        	}
	// 	});
	// });

	function validateForm() {
		var bool = false;
	    var nama = document.forms["form_sales_create"]["nama"].value;

	    if (nama == "") {
	        $('#showNotifSales').show();
	        bool = false;
	    }else{
	    	if (!checkSales) {
                $('#checkSales').show();
	    		bool = false;
	    	}else{
                $('#checkSales').hide();
	    		$('#showNotifSales').hide();
	    		bool = true;
	    	}
	    }
	    return bool;
	}
</script>   
@endsection