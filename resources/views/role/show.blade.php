@extends('adminlte::page')

@section('title', 'Master Role')

@section('content_header')
<h1>Master Role</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Show Role</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $role->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permissions:</strong>
            @if(!empty($rolePermissions))
            @foreach($rolePermissions as $v)
            <label class="label label-success">{{ $v->name }},</label>
            @endforeach
            @endif
        </div>
    </div>
</div>

@stop
@section('js')
<script>
    $(document).ready(function() {
        $('#table').DataTable({
            ajax: '{{ route("ajax.master.roles") }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'deskripsi',
                    name: 'deskripsi'
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
        });
        $("#btnAdd").on("click", function() {
            $('#formModal')[0].reset();
            $("input[type='hidden']").val("");
            $('#formModal').attr('method', "POST");
            $('#formModal').attr('action', 'http://uri-for-button2.com');
            alert($('#formModal').attr('action'));
            $('#modal').modal('show');
        });
        $("#reloadBtn").on('click', function() {
            reloadTable();
        });
    });
</script>
@stop