@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" onsubmit="return validateForm()" name="form_gudang_edit" method="post" action="update">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Ubah Gudang</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-4 control-label">Nama Gudang</label>
						<div class="col-md-4">                                            
							<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Gudang" value="{{ old('nama') ?: $gudang->nama }}" autofocus />
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifGudang">Gudang Harus Diisi</span> 
							<span style="color: red; text-align:left; display:none" id="checkGudang">Gudang Telah Digunakan</span> 
							{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/gudang"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div> 
<script type="text/javascript">
	var checkGudang = true;

	// $(document).ready(function(){
	// 	$('#nama').on('input', function(){
	//     	$('#showNotifGudang').hide();
	// 		var gudang = $(this).val();
 //        	if (gudang){
 //        		$.ajaxSetup({
	// 			    headers: {
	// 			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 			    }
	// 			});
 //        		$.ajax({
 //                    url: '/gudang/check_gudang',
 //                    type: "POST",
 //              		data: {gudang:gudang},
 //                    dataType: "json",
 //                    success:function(data) {
 //                    	if (data == 1) {
 //                    		$('#checkGudang').show();
 //                    		checkGudang = false;
 //                    	}else{
 //                    		$('#checkGudang').hide();
 //                    		checkGudang = true;
 //                    	}             	
 //                    }
 //                });			
 //        	}
	// 	});
	// });

	function validateForm() {
		var bool = false;
	    var nama = document.forms["form_gudang_edit"]["nama"].value;

	    if (nama == "") {
	        $('#showNotifGudang').show();
	        bool = false;
	    }else{
	    	if (!checkGudang) {
                $('#checkGudang').show();
	    		bool = false;
	    	}else{
                $('#checkGudang').hide();
	    		$('#showNotifGudang').hide();
	    		bool = true;
	    	}
	    }
	    return bool;
	}
</script>  
@endsection