@extends('adminlte::page')

@section('title', 'Master Role')

@section('content_header')
<h1>Master Role</h1>
@stop

@section('content')
<div class="page-wrapper">

    <div class="row">

        <div class="col-lg-12">

        </div>
    </div>


    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <div class="panel panel-primary">
        <div class="panel-heading">
            List of Role
            @can('roles-create')
            <a class="btn btn-success btn-xs text-center" href="{{ route('roles.create') }}"> Create New Role</a>
            @endcan
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="table" class="table table-responsive table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a class="btn btn-info btn-xs" href="{{ route('roles.show',$role->id) }}">Show</a>
                                @can('roles-edit')
                                <a class="btn btn-primary btn-xs" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                @endcan
                                @can('roles-delete')
                                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>


</div>
@stop

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable({});
    });
</script>
@stop