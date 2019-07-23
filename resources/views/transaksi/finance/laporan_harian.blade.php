@extends('layouts.header')
@section('content')

<!-- START RESPONSIVE TABLES -->
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Laporan Kas Harian</h3>
      </div>
      <div class="panel-body">
        <div class="form-group{{ $errors->has('nama') ? 'has-error' : '' }}">
          <label class="col-md-4 control-label">Tanggal</label>
          <div class="col-md-5">
            <input type="text" id="tanggal" value="{{$tanggal}}" name="tanggal" class="form-control datepicker">
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="col-md-12" style="text-align: center;">
          <button class="btn btn-primary" onclick="Cari()">Cari</button>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div class="panel-body panel-body-table">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-actions" id="laporan_harian">
                    <thead>
                      <tr>
                        <th width="15%" style="text-align: center;">tanggal</th>
                        <th width="70%" style="text-align: center;">Keterangan</th>
                        <th width="15%" style="text-align: right;">Total</th>
                      </tr>
                    </thead>
                    <tbody>                  
                      @foreach ($neracas as $key => $neraca)
                        @if($neraca->status == 2)
                          <tr class="tbody warning">
                            <td style="text-align: center;">{{$neraca->tanggal}}</td>
                            <td style="text-align: left;">{{$neraca->keterangan}}</td>
                            <td style="text-align: right;">Rp. {{number_format($neraca->total)}}</td></td>
                          </tr>
                        @else
                          <tr class="tbody success">
                            <td style="text-align: center;">{{$neraca->tanggal}}</td>
                            <td style="text-align: left;">{{$neraca->keterangan}}</td>
                            <td style="text-align: right;">Rp. {{number_format($neraca->total)}}</td></td>
                          </tr>
                        @endif

                        @if($neracas->last() === $neraca)
                          <tr class="tbody info">
                            <td colspan="2">Total</td>
                            <td style="text-align: right;">Rp. {{number_format($total->last_total)}}</td></td>
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
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
  var rowTable = 0

  function Cari()
  {
    var tanggal = $('#tanggal').val();
    if (tanggal){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url: '/laporan-harian/cari',
        type: "POST",
        data: {search:tanggal},
        dataType: "json",
        success:function(data) {
          console.log(data);  
          jQuery('.tbody').remove();  
          if(data.length == 0){
            var row = '<tr class="tbody"><th style="text-align: left;" colspan="4">Tidak Ada Data</th></tr>';
            $('#laporan_harian').append(row) 
          }else{
            if (data.total != null) {
              $.each(data.neracas, function(key, value) {
                rowTable ++;
                var Angka = toRp(value.total);
                if (value.status == 2) {
                  var row = '<tr class="tbody warning"><td style="text-align: center;">'+value.tanggal+'</td><td style="text-align: left;">'+value.keterangan+'</td><td style="text-align: right;">Rp. '+Angka+'</td></td></tr>';
                }else{
                  var row = '<tr class="tbody success"><td style="text-align: center;">'+value.tanggal+'</td><td style="text-align: left;">'+value.keterangan+'</td><td style="text-align: right;">Rp. '+Angka+'</td></td></tr>';
                }
                
                $('#laporan_harian').append(row);
                console.log(rowTable);  
              }); 

              var row = '<tr class="tbody info"><td colspan="2">Total</td><td style="text-align: right;">Rp. '+toRp(data.total.last_total)+'</td></td></tr>';
                $('#laporan_harian').append(row);
            }else{
              var row = '<tr class="tbody"><th style="text-align: left;" colspan="4">Tidak Ada Data</th></tr>';
              $('#laporan_harian').append(row) 
            }
          }         
        }
      }); 
    }
  }

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

  function toRp(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
      rev2  += rev[i];
      if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
        rev2 += '.';
      }
    }
    return rev2.split('').reverse().join('');
  }
</script>
@endsection