@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" name="form_create" onsubmit="return validateForm()" method="post" id="myform" action="create">
			{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Buat Daftar Akun</h3>
				</div>
				<div class="panel-body">
					<div class="form-group{{ $errors->has('username') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Username</label>
						<div class="col-md-5">                                            
							<input type="username" name="username" id="username" class="form-control" placeholder="Username" autofocus />
							{!! $errors->first('username', '<span class="help-block">:message</span>') !!}
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifUsername"> Username telah digunakan</span>  
						</div>
					</div>
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Nama Akun</label>
						<div class="col-md-5">                                            
							<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Akun" autofocus />
							{!! $errors->first('nama', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					<div class="form-group{{ $errors->has('password') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Password</label>
						<div class="col-md-5">                                            
							<input type="password" name="password" id="password" class="form-control" placeholder="Password"  autofocus />
							{!! $errors->first('password', '<span class="help-block">:message</span>') !!}
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifPassword"> Password Harus Diisi</span>  
						</div>
					</div>
					<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">Group</label>
						<div class="col-md-5">                                            
							<select name="group_id" id="group_id" class="form-control select" data-live-search="true">
								<option value="1">Manager</option>
								<option value="2">Supervisor</option>
								<option value="3">Sales</option>
							</select>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="col-sm-12" style="text-align: right">
						<a href="/user"><input class="btn btn-default" type="button" value="Kembali"></a>
						<button class="btn btn-primary">Selesai</button>
					</div>
				</div>
			</div>
		</form>
	<div class="modal fade bs-example-modal-sm1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Tambah Data Gruup</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="form-group">
                  {{ csrf_field() }}
                  <label class="col-md-3 control-label" for="textinput">Nama Group</label>  
                  <div class="col-md-8">
                    <input id="nama_group" name="nama_group" type="text" placeholder="Nama Group" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="button" id="add" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
	</div>
</div>
<script type="text/javascript">
	var checkUsername = false;
	$(function() {
		$('#username').on('keypress', function(e) {
			if (e.which == 32)
				return false;
		});
	});
	function validateForm() {
		var bool = false;
		var group_id = document.forms["form_create"]["group_id"].value;
		if (group_id == 0) {
			$('#showNotifNamaGroup').show();
			bool = false;
		}else{
			$('#showNotifNamaGroup').hide();
			bool = true;
		}

		return bool;
	}

	$('#username').on('blur', function(){
		console.log('Username : ' + $(this).val())
    	$('#showNotifUsername').hide();
		var username = $(this).val();
    	if (username){
    		$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
    		$.ajax({
                url: '/user/check_username',
                type: "POST",
          		data: {username:username},
                dataType: "json",
                success:function(data) {
                	console.log(data);
                	if (data >= 1) {
                		$('#showNotifUsername').show();
	    				$('#showNotifUsername').text('Username Telah Digunakan');
                		checkUsername = false;
                	}else{
                		$('#showNotifUsername').hide();
                		checkUsername = true;
                	}             	
                }
            });			
    	}
	});

	function validateForm() {
		var bool = false;
	    var password = document.forms["form_create"]["password"].value;
	    var username = document.forms["form_create"]["username"].value;

	    if (username == "") {
	    	$('#showNotifUsername').show();
	    	$('#showNotifUsername').text('Username Harus Diisi');
	        bool = false;
	    }else if (!checkUsername) {
	    	$('#showNotifUsername').show();
	    	$('#showNotifUsername').text('Username Telah Digunakan');
	        bool = false;
	    }else if (password == "") {
	    	$('#showNotifUsername').hide();
	        $('#showNotifPassword').show();
	        bool = false;
	    }else{
	        $('#showNotifPassword').hide();
	    	bool = true;
	    }
	    return bool;
	}
</script>
@endsection