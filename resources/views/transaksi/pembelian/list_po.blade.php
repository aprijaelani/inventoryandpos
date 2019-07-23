@extends('layouts.header')
@section('content')

<!-- START RESPONSIVE TABLES -->
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel-body panel-body-table">
        <div class="panel panel-default">
          <div class="panel-heading">                                
              <h3 class="panel-title">Daftar Pesanan</h3>
              <ul class="panel-controls">
                  <li style="margin-right: 5px"><a href="/list_po"><span class="fa fa-refresh"></span></a></li>
                  <li>
                    <form method="post" action="{{ url('list_po/search') }}">
                        {{ csrf_field() }}
                      <input class="form-control" type="text" name="search" placeholder="Cari Barang..."/> 
                    </form>
                  </li>
              </ul>      

          </div>
            <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-actions" id="tableList">
                    <thead>
                      <tr>
                        <th width="15%" style="text-align: center;">Kode PO</th>
                        <th width="15%">Nama Supplier</th>
                        <th width="10%">Pemesan</th>
                        <th width="10%">Tanggal Pesan</th>
                        <th width="10%">Tanggal Estimasi</th>
                        <th width="20%">Keterangan</th>
                        <th width="10%" style="text-align: center;">Total</th>
                      </tr>
                    </thead>
                    <tbody>                                            
                      @foreach ($preOrders as $preOrder)
                      <tr onclick="document.location = '/list_po/{{$preOrder->id}}/detail';">
                        <td style="text-align: center;">{{$preOrder->kode_po}}</td>
                        <td>{{$preOrder->nama_supplier}}</td>
                        <td>{{$preOrder->nama_user}}</td>
                        <td>{{$preOrder->tanggal}}</td>
                        <td>{{$preOrder->tanggal_estimasi}}</td>
                        @if ($preOrder->keterangan == null)
                        <td>-</td>
                        @else
                        <td>{{$preOrder->keterangan}}</td>
                        @endif
                        <td style="text-align: right;">Rp. {{number_format($preOrder->total)}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  {{ $preOrders->links() }}
                </div> 
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalSupplier" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
              </div>
              <div class="modal-body">
                <p>Some text in the modal.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
            
          </div>
        </div>
      </div>                                               
  </div>
</div>

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
{{-- <script type="text/javascript">
  $('tr').on('click',function(){
    $("#modalSupplier").modal("show");
  });

  // var table = $('#tableList').DataTable();
  // $('#tableList').on( 'click', 'tr', function () {
  //   $("#modalSupplier").modal("show");
  //   var id = table.row( this ).id();
  //   alert( 'Clicked row id '+id );
  // });
</script> --}}
@endsection