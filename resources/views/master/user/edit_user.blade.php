@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" name="form_create" onsubmit="return validateForm()" method="post" id="myform" action="update">
				{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Ubah Data Akun</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-4 control-label">Nama Akun</label>
						<div class="col-md-4">                                            
							<input type="text" value="{{ old('nama') ?: $user->nama }}" name="nama" id="nama" class="form-control" placeholder="Nama Akun"/>
							{!! $errors->first('nama', '<span class="help-block">:message</span>')!!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Username</label>
						<div class="col-md-4">                                            
							<input type="text" value="{{ old('username') ?: $user->username }}" name="username" id="username" class="form-control" placeholder="Username" readonly />
							{!! $errors->first('username', '<span class="help-block">:message</span>')!!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Password</label>
						<div class="col-md-4">                                            
							<input type="password" name="password" id="password" class="form-control" placeholder="Password" />
							{!! $errors->first('password', '<span class="help-block">:message</span>')!!}
							<span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifPassword"> Password Harus Diisi</span>  
						</div>
					</div>
					<div class="form-group">
						
						<label class="col-md-4 control-label">Group</label>
							<div class="col-md-4">                                            
								<select name="group_id" id="group_id" class="form-control select" data-live-search="true">
									@if($user->group_id == 1)
									<option value="1" selected>Manager</option>
									<option value="2">Supervisor</option>
									<option value="3">Sales</option>
									@elseif($user->group_id == 2)
									<option value="1">Manager</option>
									<option value="2" selected>Supervisor</option>
									<option value="3">Sales</option>
									@else
									<option value="1">Manager</option>
									<option value="2">Supervisor</option>
									<option value="3" selected>Sales</option>
									@endif
								</select>
									{!! $errors->first('email', '<span class="help-block">:message</span>')!!}
							</div>
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
	</div>
</div> 
<script>
	function validateForm() {
		var bool = false;
	    var password = document.forms["form_create"]["password"].value;

	    if (password == "") {
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