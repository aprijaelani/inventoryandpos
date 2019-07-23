@extends('layouts.header')
@section('content')

<!-- START RESPONSIVE TABLES -->
<br>
<div class="row">
  <div class="col-md-12">
    <form class="form-horizontal" method="post" action="/list-penjualan/filter_list">
        {{ csrf_field() }}
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Pilih Sales dan Tanggal Work Orders</h3>
        </div>
        <div class="panel-body">
          <div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
            <label class="col-md-4 control-label">Nama Sales</label>
            <div class="col-md-5">                                            
              <select name="employee_id" id="employee_id" class="form-control select" data-live-search="true">  
                        <option value="0">All</option>
                        @foreach ($sales as $sale)
                        <option value="{{ $sale->id }}">{{$sale->nama}}</option>
                        @endforeach                   
                      </select>
            </div>
          </div>
          <div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
            <label class="col-md-4 control-label">Tanggal Awal</label>
            <div class="col-md-5">
              <input type="text" id="date_from" value="{{$tanggal}}" name="date_from" class="form-control datepicker">
            </div>
          </div>
          <div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
            <label class="col-md-4 control-label">Tanggal Akhir</label>
            <div class="col-md-5">
              <input type="text" id="date_end" value="{{$tanggal}}" name="date_end" class="form-control datepicker">
              <span class="showNotif" style="color: red; text-align:left; display:none" id="showNotifTanggal">Tanggal Akhir Harus Lebih Besar dari Tanggal Awal</span>  
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <div class="col-md-12" style="text-align: center;">
            <button class="btn btn-primary">Cari</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel-body panel-body-table">
        <div class="panel panel-default">
          <div class="panel-heading">                                
              <h3 class="panel-title">Daftar Penjualan</h3>
              <!-- <ul class="panel-controls">
                  <li style="margin-right: 3px"><a href="/list-penjualan"><span class="fa fa-refresh"></span></a></li>
                  <li>
                    <form method="post" action="{{ url('list-penjualan/search') }}">
                        {{ csrf_field() }}
                      <input class="form-control" type="text" name="search" placeholder="Cari Barang..."/> 
                    </form>
                  </li>
              </ul>  -->     

          </div>
            <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-actions">
                    <thead>
                      <tr>
                        <th width="15%">Kode Transaksi</th>
                        <th width="15%">No Nota</th>
                        <th width="15%">Sales Name</th>
                        <th width="15%">Tanggal</th>
                        <th width="10%">Total Transaksi</th>
                        <th width="10%">Status Pembayaran</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $total = 0;?>                  
                      @foreach ($work_orders as $key => $work_order)
                        @if($work_orders->last() === $work_order)                    
                          <tr>
                            <th colspan="3" style="text-align: left;">Sub Total</th>
                            <td colspan="2" style="text-align: right;">Rp. {{number_format($total)}}</td>
                            <td></td>
                          </tr>
                        @else
                          <?php
                            if ($work_order->status_pembayaran == "Cash") {
                               $total = $total + $work_order->total;
                             } 
                          ?>
                          <tr>
                            <td>{{$work_order->kode_wo}}</td>
                            <td>{{$work_order->no_nota}}</td>
                            <td>{{$work_order->nama}}</td>
                            <td>{{$work_order->tanggal}}</td>
                            <td style="text-align: right">Rp. {{number_format($work_order->total)}}</td>
                            <td style="text-align: center;">{{$work_order->status_pembayaran}}</td>
                          </tr>
                        @endif
                      @endforeach

                    </tbody>
                  </table>
                  <!-- {{ $work_orders->links() }}   -->
                </div>
                <br>
                <!-- <div class="table-responsive">
                  <table class="table table-striped table-actions">
                    <thead>
                      <tr>
                        <th width="60%" style="text-align: right;">Sub Total</th>
                        <th width="10%">Total Transaksi</th>
                        <th width="10%">Status Pembayaran</th>
                      </tr>
                    </thead>
                  </table>
                </div> -->
            </div>
        </div>
      </div>                                               
  </div>
</div>
<!-- END RESPONSIVE TABLES -->

<!-- END RESPONSIVE TABLES -->
      <div class="modal fade bs-example-modal-sm3" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Delete Data</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                {{ csrf_field() }}
                <input type="hidden" name="id-delete" id="id-delete">
                <p>Yakin Ingin Menghapus Data? </p>
              </div>
              <div class="form-group" align="right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="delete" class="btn btn-danger" data-dismiss="modal">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END DEFAULT DATATABLE -->

<script type="text/javascript">
  $(document).on('click', '.delete-modal', function() {
    $('#id-delete').val($(this).data('id'));
    $('.bs-example-modal-sm3').modal('show');
  });
  $("#delete").click(function() {
    $.ajax({
      type: 'post',
      url: '/barang/delete',
      data: {
        '_token': $('input[name=_token]').val(),
        'id' : $('input[name=id-delete]').val()
      },
      success: function(data) {
        $('.item' + data.id).remove();
        toastr.success("Data Berhasil Dihapus.");
        location.href = "/barang"
      }
    });
  });
</script>
@endsection