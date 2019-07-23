@extends('layouts.header')
@section('content')

<!-- START RESPONSIVE TABLES -->
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel-body panel-body-table">
      <div class="panel panel-default">
        <div class="panel-heading">                                
          <h3 class="panel-title">Daftar Supplier</h3>
          <ul class="panel-controls">
            <li><a href="/supplier"><span class="fa fa-refresh"></span></a></li>
            <li style="margin-right: 3px"><a href="/supplier/create" title="Tambah Supplier"><span class="fa fa-plus"></span></a></li>
            <li>
              <form method="post" action="{{ url('supplier/search') }}">
                {{ csrf_field() }}
                <input class="form-control" type="text" name="search" placeholder="Cari Supplier..."/> 
              </form>
            </li>
          </ul>      

        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-actions">
              <thead>
                <tr>
                  <th width="90%">Nama Supplier</th>
                  <th width="10%"></th>
                </tr>
              </thead>
              <tbody>                                            
                @foreach ($suppliers as $supplier)
                <tr>
                  <td>{{$supplier->nama}}</td>
                  <td>
                    <a href="/supplier/{{ $supplier->id }}/edit" style="margin-right: 10px" title="Ubah"><span class="fa fa-pencil fa-2x"></span></a>
                    <a class="delete-modal" data-id="{{$supplier->id}}" style="margin-right: 10px" title="Hapus"><span class="fa fa-trash-o fa-2x"></span></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{ $suppliers->links() }}
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
      url: '/supplier/delete',
      data: {
        '_token': $('input[name=_token]').val(),
        'id' : $('input[name=id-delete]').val()
      },
      success: function(data) {
        if (data == 'false') {
          swal ( "Oops" ,  "Tidak Dapat Menghapus Data Master Yang Memiliki Data Child!" ,  "error" )
        }else{
          $('.item' + data.id).remove();
          swal("Success", "Delete Data Supplier Berhasil", "success");
          location.href = "/supplier"
        }
      }
    });
  });
</script>
@endsection