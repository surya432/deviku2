@extends('adminlte::page')

@section('title', 'Master Access')

@section('content_header')
<h1>Master Access</h1>
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
                    List Access
                    @can('permission-create')
                    <a class="btn btn-success btn-xs" href="{{ route('permission.create') }}"> Create New Permission</a>
                    @endcan
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>
                                        <a class="btn btn-info btn-xs" href="{{ route('permission.show',$permission->id) }}">Show</a>
                                        @can('permission-edit')
                                        <a class="btn btn-primary btn-xs" href="{{ route('permission.edit',$permission->id) }}">Edit</a>
                                        @endcan
                                        @can('permission-delete')
                                        {!! Form::open(['method' => 'DELETE','route' => ['permission.destroy', $permission->id],'style'=>'display:inline']) !!}
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
    </div>
</div>
@stop
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>
@stop