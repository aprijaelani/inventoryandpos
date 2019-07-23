@extends('layouts.header')
@section('content')

<br>
<div class="row">
  <div class="col-md-12">
    <!-- START DEFAULT DATATABLE -->
    <div class="panel panel-default">
      <div class="panel-heading">                                
        <h3 class="panel-title">Daftar Biaya dan Pendapatan</h3>
        <ul class="panel-controls">
          <li><a href="/biaya-pendapatan/create" title="Tambah Sales"><span class="fa fa-plus"></span></a></li>
        </ul>                                
      </div>
      <div class="panel-body">
        <table class="table datatable">
          <thead>
            <tr>
              <th style="text-align: left;" width="10%">Jenis</th>
              <th style="text-align: left;" width="80%">Keterangan</th>
              <th width="10%"></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($biayaPendapatans as $biayaPendapatan)
            <tr>
              <td style="text-align: left;">{{$biayaPendapatan->jenis}}</td>
              <td>{{$biayaPendapatan->nama}}</td>
              <td>
               <a href="/biaya-pendapatan/{{ $biayaPendapatan->id }}/edit" style="margin-right: 10px" title="Ubah"><span class="fa fa-pencil fa-2x"></span></a>
               <a class="delete-modal" data-id="{{$biayaPendapatan->id}}" title="Hapus"><span class="fa fa-trash-o fa-2x"></span></a>
             </td>
           </tr>
           @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- END RESPONSIVE TABLES -->
<div class="modal fade bs-example-modal-sm3" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
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
<script type="text/javascript">
  $(document).on('click', '.delete-modal', function() {
    $('#id-delete').val($(this).data('id'));
    $('.bs-example-modal-sm3').modal('show');
  });
  $("#delete").click(function() {
    $.ajax({
      type: 'post',
      url: '/biaya-pendapatan/delete',
      data: {
        '_token': $('input[name=_token]').val(),
        'id' : $('input[name=id-delete]').val()
      },
      success: function(data) {
        $('.item' + data.id).remove();
        swal("success", "Delete Data Biaya Pendapatan Berhasil", "Berhasil");
        location.href = "/biaya-pendapatan"
      }
    });
  });
</script>
@endsection