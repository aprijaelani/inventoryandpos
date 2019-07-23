@extends('layouts.header')
@section('content')

<!-- START RESPONSIVE TABLES -->
<br>
<div class="row">
  <div class="col-md-12">
    <div class="page-content-wrap">
      <a href="/set_kas">
        <div 
          @if ($check == null)
            class="alert alert-danger"
          @else
            class="alert alert-danger hidden"
          @endif
         role="alert" data-toggle="tooltip" data-placement="bottom" title="Please Set Kas">
            <strong>Peringatan! </strong> Tolong Masukkan Kas Untuk Memulai Aktifitas.
        </div>
      </a>  
      <div class="row">
        <div class="col-md-3">
          <!-- START WIDGET SLIDER -->
          <div class="widget widget-default widget-carousel">
              <div class="owl-carousel" id="owl-example">
                  <div>                                    
                      <div class="widget-title">Total Transaksi</div>                                       
                      <div class="widget-subtitle plugin-date">27/08/2014 15:23</div>
                      <div class="widget-int">{{$count_penjualan}}</div>
                  </div>
                  <div>                                    
                      <div class="widget-title">Work Order</div>
                      <div class="widget-subtitle">Menunggu</div>
                      <div class="widget-int">{{$count_workorder}}</div>
                  </div>
              </div>                                 
          </div>         
          <!-- END WIDGET SLIDER -->
              
          </div>
          <div class="col-md-3">
              
            <!-- START WIDGET MESSAGES -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='/list_po';">
                <div class="widget-item-left">
                    <span class="fa fa-shopping-cart"></span>
                </div>                             
                <div class="widget-data">
                    <div class="widget-int num-count">{{$count_preorder}}</div>
                    <div class="widget-title">Pre Order</div>
                    <div class="widget-subtitle">Menunggu</div>
                </div>      
            </div>                            
            <!-- END WIDGET MESSAGES -->
              
          </div>
          <div class="col-md-3">
              
            <!-- START WIDGET REGISTRED -->
            <div class="widget widget-warning widget-item-icon" onclick="location.href='/list-penjualan';">
                <div class="widget-item-left">
                    <span class="fa fa-info"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">{{$count_salesinvoice}}</div>
                    <div class="widget-title">Sales Invoice</div>
                    <div class="widget-subtitle">Hari Ini</div>
                </div>                          
            </div>                            
            <!-- END WIDGET REGISTRED -->
              
          </div>
          <div class="col-md-3">
              
            <!-- START WIDGET CLOCK -->
            <div class="widget widget-info widget-padding-sm">
                <div class="widget-big-int plugin-clock">00:00</div>
                <div class="widget-subtitle plugin-date">Loading...</div>
            </div>                        
            <!-- END WIDGET CLOCK -->
              
          </div>
      </div>
      <!-- END WIDGETS -->  

      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Deadline Work Order</h3>
        </div>
        <div class="panel-body">
          <div class="panel-body panel-body-table">
            <div class="table-responsive">
              <table class="table table-striped table-actions" id="tableList">
                <thead>
                  <tr>
                    <th width="15%">Kode PO</th>
                    <th width="15%">Supplier</th>
                    <th width="10%">Pemesan</th>
                    <th width="15%">Tanggal Pesan</th>
                    <th width="15%">Tanggal Estimasi</th>
                    <th width="20%">Keterangan</th>
                    <th width="10%">Total</th>
                  </tr>
                </thead>
                <tbody>                                            
                  @foreach ($preOrders as $preOrder)
                  <tr onclick="document.location = '/list_po/{{$preOrder->id}}/detail';">
                    <td>{{$preOrder->kode_po}}</td>
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