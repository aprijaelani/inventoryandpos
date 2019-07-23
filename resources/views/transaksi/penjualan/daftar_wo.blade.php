@extends('layouts.header')
@section('content')

<!-- START RESPONSIVE TABLES -->
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel-body panel-body-table">
        <div class="panel panel-default">
          <div class="panel-heading">                                
              <h3 class="panel-title">Daftar Work Order</h3>
              <!-- <ul class="panel-controls">
                  <li><a href="/barang"><span class="fa fa-refresh"></span></a></li>
                  <li style="margin-right: 3px"><a href="/barang/create"><span class="fa fa-plus"></span></a></li>
                  <li>
                    <form method="post" action="{{ url('barang/search') }}">
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
                        <th width="10%">Sales Name</th>
                        <th width="10%">Tanggal</th>
                        <th width="10%" style="text-align: center;">Status Wo</th>
                        <th width="10%">Total Transaksi</th>
                        <th width="10%">Status Pembayaran</th>
                      </tr>
                    </thead>
                    <tbody>                  
                    @foreach ($work_orders as $key => $work_order)
                      <tr>
                        <td>{{$work_order->kode_wo}}</td>
                        <td class="text-uppercase">{{$work_order->no_nota}}</td>
                        <td>{{$work_order->nama}}</td>
                        <td>{{$work_order->tanggal}}</td>
                        <td style="text-align: center;">{{$work_order->status_wo}}</td>
                        <td style="text-align: right">Rp. {{number_format($work_order->total)}}</td>
                        <td style="text-align: center;">{{$work_order->status_pembayaran}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  {{ $work_orders->links() }}  
                </div> 
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