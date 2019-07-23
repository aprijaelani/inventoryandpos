@extends('layouts.header')
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
	<form class="form-horizontal" method="post" action="">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Detail Pesanan</h3>
			</div>
			<div class="panel-body">
				<div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">Nama Supplier :</label>
					<div class="col-md-5">    
						<label class="control-label">{{$PreOrder->supplier['nama']}}</label>      
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">Tanggal PO :</label>
					<div class="col-md-5">   
						<label class="control-label">{{$PreOrder->tanggal}}</label>    
					</div>
				</div>
				<div class="form-group{{ $errors->has('email') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">Tanggal Estimasi :</label>
					<div class="col-md-5">   
						<label class="control-label">{{$PreOrder->tanggal_estimasi}}</>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="col-sm-offset-1 col-sm-10">
					<div class="row">
						<div class="col-md-12">
							<table width="100%" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th width="10%">Id</th>
										<th width="10%">Jumlah</th>
										<th width="20%">Kode Barang</th>
										<th width="40%">Nama Barang</th>
										<th width="20%">Harga</th>
									</tr>
								</thead>
								<tbody>
									@foreach($PreOrderOuts as $PreOrderOut)
										<tr class="success">
											<td>{{$PreOrderOut->id}}</td>
											<td>{{$PreOrderOut->qty}}</td>
											<td>{{$PreOrderOut->barang['kode_supplier']}}</td>
											<td>{{$PreOrderOut->barang['nama_barang']}}</td>
											<td style="text-align: right">{{number_format($PreOrderOut->harga_po)}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>	
					</div>
					<div class="row">
						<div class="col-sm-offset-8 col-md-4">
							<div class="form-group{{ $errors->has('email') ? 'has-error' : '' }}">
								<label class="col-md-4 control-label">Total Harga :</label>
								<div class="col-md-5">   
									<label class="control-label">Rp. {{number_format($total_harga->total_po)}}</>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-offset-8 col-md-4">
							<div class="form-group{{ $errors->has('email') ? 'has-error' : '' }}">
								<label class="col-md-4 control-label">Jumlah :</label>
								<div class="col-md-5">   
									<label class="control-label">{{$total_qty->total_qty}} Pcs</>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" style="text-align: right;">
						<a href="/list_po">
							<input class="btn btn-primary" type="button" name="back" value="Kembali">
						</a>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>   
@endsection