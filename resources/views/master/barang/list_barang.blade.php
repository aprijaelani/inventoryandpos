@extends('layouts.header')
@section('content')

<!-- START RESPONSIVE TABLES -->
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel-body panel-body-table">
        <div class="panel panel-default">
          <div class="panel-heading">                                
              <h3 class="panel-title">Daftar Barang</h3>
              <ul class="panel-controls">
                  <li><a href="/barang"><span class="fa fa-refresh"></span></a></li>
                  <li style="margin-right: 3px"><a href="/barang/create"><span class="fa fa-plus"></span></a></li>
                  <li>
                    <form method="post" action="{{ url('barang/search') }}">
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
                        <th style="text-align: center;">Kode Barang</th>
                        <th style="text-align: left;">Nama Barang</th>
                        <th style="text-align: left;">Jenis Barang</th>
                        <th style="text-align: center;">Action</th>
                      </tr>
                    </thead>
                    <tbody>                                            
                      @foreach ($barangs as $barang)
                      <tr>
                        <td style="text-align: center;">{{$barang->kode_barcode}}</td>
                        <td style="text-align: left;">{{$barang->nama_barang}}</td>
                        <td style="text-align: left;">{{$barang->grouping['nama']}}</td>
                        <td align="center">
                          <a href="/barang/{{ $barang->id }}/edit" style="margin-right: 10px;"><span class="fa fa-pencil fa-2x"></span></a>
                          <a href="/barang/{{ $barang->id }}/barcode" style="margin-right: 10px;"><span class="fa fa-barcode fa-2x"></span></a>
                          <a><i class="delete-modal fa fa-trash-o fa-2x" data-id="{{$barang->id}}" style="margin-right: 10px;"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  {{ $barangs->appends(['search' => $search])->links() }}
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
        swal("Success", "Delete Data Barang Berhasil", "success");
        location.href = "/barang"
      }
    });
  });
</script>
@endsection