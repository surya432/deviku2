@extends('adminlte::page')

@section('title', 'Setting')

@section('content_header')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h1>Setting</h1>
        </div>


    </div>
</div>
@stop

@section('content')
<div class="page-wrapper">


    <div class="row">
        <div class="col-lg-12">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif

            <div class="panel panel-primary">
                <div class="panel-heading">
                    Daftar Nasabah

                </div>
                <div class="panel-body">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Tambah Asset Nasabah</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="row">
                            {!! Form::open(array('route' => 'setting.store','method'=>'POST')) !!}
                            {{Form::hidden('id', null, array('id' => 'id')) }}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Key</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('keys', null, array('placeholder' => 'Key','id' => 'keys','class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">Value</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('value', null, array('placeholder' => 'Value','id' => 'value','class' => 'form-control')) !!}
                                    </div>
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <div class='class="box-footer'>
                                <div class="box-footer col-sm-6">
                                    <button type="submit" class="btn btn-info pull-right">Simpan</button>
                                </div>
                            </div> <!-- /.box-footer -->
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Keys</th>
                                    <th>Value</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>

                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@stop
@section('js')
<script>
    $(document).ready(function(event) {

        table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("tableSetting") }}',
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'keys'
                }, {
                    data: 'value'
                }, {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }

            ]
        });
        $('body').on('click', '#btnEditSetting', function(e) {
            var id = $(this).data("id");
            var keys = $(this).data("keys");
            var value = $(this).data("value");


            $("#id").val(id);
            $("#keys").val(keys);

            $("#value").val(value);

        });
        $('body').on('click', '#btnDeleteSetting', function(e) {
            var user_id = $(this).data("id");

            confirm("Apa Anda Yakin menghapus Data Setting !");

            $.ajax({
                type: 'get',
                url: "/setting/delete/" + user_id,
                success: function(data) {
                    var oTable = $('#table').dataTable();
                    oTable.fnDraw(false);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
    });
</script>
@stop