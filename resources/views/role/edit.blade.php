@extends('adminlte::page')

@section('title', 'Master User')

@section('content_header')
<h1>Master User</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Role</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


{!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">

            {{Form::label('permission', 'Permission')}}
            <select multiple="multiple" name="permission[]" id="permission" class="permisionlist form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1">
                @foreach($permission as $value)
                <option value="{{$value->id}}" {{in_array($value->id, $rolePermissions) ? "selected" : ""}}>{{ $value->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}
@stop
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('.permisionlist').select2();
    });
</script>
@stop