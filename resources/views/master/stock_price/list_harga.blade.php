@extends('layouts.header')
@section('content')

<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel-body panel-body-table">
        <div class="panel panel-default">
          <div class="panel-heading">                                
              <h3 class="panel-title">Daftar Harga Barang</h3>
              <ul class="panel-controls">
                  <li style="margin-right: 3px"><a href="/price_list" class=""><span class="fa fa-refresh"></span></a></li>
                  <li>
                    <form method="post" action="{{ url('price_list/search') }}">
                        {{ csrf_field() }}
                      <input class="form-control" type="text" name="search" placeholder="Cari Barang..."/> 
                    </form>
                  </li>
              </ul>      

          </div>
            <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-actions">
                    <thead>
                      <tr>
                        <th width="15%" style="text-align: center;">Kode Barang</th>
                        <th width="60%">Nama Barang</th>
                        <th width="15%" style="text-align:center;">Harga Minimal</th>
                        <th width="15%" style="text-align:center;">Harga Retail</th>
                        <th width="5%"></th>
                      </tr>
                    </thead>
                    <tbody>  
                      @foreach ($pricelists as $num => $pricelist)
                      <tr>
                        <td style="text-align: center;">{{$pricelist->barang['kode_barcode']}}</td>
                        <td>{{$pricelist->barang['nama_barang']}}</td>
                        <td align="right">Rp. {{number_format($pricelist->min_harga)}}</td>
                        <td align="right">Rp. {{number_format($pricelist->max_harga)}}</td>
                        <td style="text-align: center;"><a href="/price_list/{{ $pricelist->barang_id }}/edit"><span class="fa fa-pencil fa-2x"></span></a></td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  {{ $pricelists->appends(['search' => $search])->links() }}
                </div> 
            </div>
        </div>
      </div> 
  </div>
</div>
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
  </div>
</div>

<<!-- script type="text/javascript">
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
  });
</script> -->
<!-- <script type="text/javascript">
$(document).on('click', '.delete-modal', function() {
    $('#id-delete').val($(this).data('id'));
    $('.bs-example-modal-sm3').modal('show');
  });
$("#delete").click(function() {
    $.ajax({
      type: 'post',
      url: '/employee/delete',
      data: {
        '_token': $('input[name=_token]').val(),
        'id' : $('input[name=id-delete]').val()
      },
      success: function(data) {
        $('.item' + data.id).remove();
        toastr.success("Data Berhasil Dihapus.");
        location.href = "/employee"
      }
    });
  });
</script> -->
@endsection