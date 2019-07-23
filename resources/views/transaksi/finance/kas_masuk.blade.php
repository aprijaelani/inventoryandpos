@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" name="form_create" onsubmit="return validateForm()" method="post" id="myform" action="kas-masuk/proses">
			{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Kas Masuk Toko</h3>
				</div>
				<div class="panel-body">
					<div class="panel panel-success">
						<div class="panel-heading">
							<div class="form-group{{ $errors->has('username') ? 'has-error' : '' }}">
								<label class="col-md-4 control-label">Tanggal</label>
								<div class="col-md-5">                                            
									<input type="text" name="tanggal" id="tanggal" class="form-control datepicker" value="{{$tanggal}}">
									<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifTanggal">Tanggal harus diisi</span>  
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<dir class="row">
							<div class="col-sm-12" style="text-align: right">
								<input type="button" class="btn btn-info" id="btnAddRow" onclick="AddRow()" value="Tambah Data"/>
							</div>
						</dir>
						<div class="panel-body">
							<div class="col-md-12" align="center">
								<table class="table" width="90%" align="center" id="tableWO">
									<tr class="header">
										<th width="20%" style="text-align: center;">Nominal</th>
										<th width="70%" style="text-align: center;">Keterangan</th>
										<th width="10%"></th>
									</tr>								
								</table>
							</div>
						</div>
						<div class="panel-footer">
							<div class="col-md-12" style="text-align: right;">
								<input type="submit" class="btn btn-primary" id="btnSubmit" value="Simpan"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>

		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Daftar Barang</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<table class="table" id="tableList">
								<thead>
									<tr>
										<th width="100%" style="text-align: left;">Keterangan</th>
									</tr>
								</thead>
								<tbody>

								</tbody>								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var numRow = 0;
	var checkStock = false;
	var checkQty = false;
	var checkKeterangan = false;
	function AddRow()
	{
		numRow ++;
		console.log(numRow);
		var row = '<tr class="realBody" id="rowNum'+numRow+'"><th style="text-align: center;"><input class="form-control math nominal" type="text" id="nominal_'+numRow+'" name="nominal[]" style="text-align: right;"></th><th><div class="form-group has-feedback"><input class="form-control keterangan autocomplete" type="text" id="keterangan_'+numRow+'" name="keterangan[]" style="text-align: left;"><input class="form-control keterangan_id hidden" type="text" id="keteranganid_'+numRow+'" name="keteranganid[]" style="text-align: left;"><span class="glyphicon glyphicon-tasks form-control-feedback"></span></div><span class="showNotif" style="color: red; text-align:right; display:block" id="showNotif_'+numRow+'">Keterangan Wajib Diisi</span></th><th width="10%"><input type="button" value="hapus" class="btn btn-danger" onclick="removeRow('+numRow+');" class="btn btn-default btn-xs" title="Delete"></th></tr>';
	    $('#tableWO').append(row)

        $('#btnSubmit').removeAttr('disabled'); //Enable Button Submit
        $('#nominal_'+numRow).maskMoney({thousands:',', precision: 0});
	}

	function removeRow(rnum) {
    	jQuery('#rowNum'+rnum).remove();
		
		var rowCount = $('#tableWO tr').length;
		if(rowCount == 1){
			$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit
		}
    }

    $(document).ready(function(){
    	var id;
		var index;
		var splitid;
		var rowTable = 0;

		$('#btnSubmit').attr('disabled','disabled'); //Disable Button Submit

		$(document).on('change','#tanggal', function(){
        	var tanggal = $('#tanggal').val();

		    if (tanggal != "") {
				$('#btnAddRow').removeAttr('disabled'); //Enable Button Tambah
		    }else{
				$('#btnAddRow').attr('disabled','disabled'); //Disable Button Submit
		    }
		});


		$(document).on('blur', '.autocomplete', function() {
			console.log("autocomplete");
			id = this.id;
			splitid = id.split('_');
			index = splitid[1];
        	var search = $('#keterangan_'+index).val();
        	console.log(search);
        	if (search){
        		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
        		$.ajax({
                    url: '/kas-masuk/biaya_pendapatan',
                    type: "POST",
              		data: {search:search},
                    dataType: "json",
                    success:function(data) {
                    	$("#myModal").modal("show");	
						jQuery('#tableList tbody').html('');
						$('#tableList tbody').remove();
						console.log(data);
						if(data.length == 0){
							$('#showNotif_'+index).show();
							var row = '<tr class="body"><th style="text-align: left;" colspan="4">Tidak Ada Data</th></tr>';
							$('#tableList').append(row)	
						}else{
							$('#showNotif_'+index).hide();
							$.each(data, function(key, value) {
								rowTable ++; 
								var row = '<tr class="body" id="rowNum'+rowTable+'"><th style="text-align: center;" class="id hidden">'+value.id+'</th><th style="text-align:left;" class="keterangan">'+value.nama+'</th></tr>';
								$('#tableList').append(row);
							}); 	
						}
                    	
                    }
                });			
        	}
		});

		$('#tableList').on( 'click', 'tr', function () {
		    // var id = $(this).closest('tr').attr('id');
		    var id = $(this).closest('tr').children('th.id').text();
		    var keterangan = $(this).closest('tr').children('th.keterangan').text();
		    $('#showNotif_'+index).hide();
		    $('#keteranganid_'+index).val(id);
		    $('#keterangan_'+index).val(keterangan);
			$("#myModal").modal('toggle');

		});	

	});
	
	$(document).on("keypress", 'form', function (e) {
	    var code = e.keyCode || e.which;
	    if (code == 13) {
	    	$('.autocomplete').blur();
	        e.preventDefault();
	        return false;
	    }
	});

	function toRp(angka){
	    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
	    var rev2    = '';
	    for(var i = 0; i < rev.length; i++){
	        rev2  += rev[i];
	        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
	            rev2 += '.';
	        }
	    }
	    return rev2.split('').reverse().join('');
	}

	function toAngka(rp){return parseInt(rp.replace(/,.*|[^0-9]/g, ''), 10);}

	function validateForm() {
		var bool = false;
	    var tanggal = document.forms["form_create"]["tanggal"].value;

	    $('tr.realBody').each(function() {
	        var id = $(this).find('.keterangan_id').val();
	        var nominal = $(this).find('.nominal').val();


	        checkKeterangan = true;

	        if (id == "" || nominal == "") {
	        	checkKeterangan = false;
	        }
	    });

	    if (tanggal == "") {
	        $('#showNotifTanggal').show();
	        bool = false;
	    }else if (!checkKeterangan) {
	    	$('#showNotifTanggal').hide();
	    	bool = false;
	    }else{
	    	bool = true;
	    }
	    return bool;
	}
</script>
@endsection 