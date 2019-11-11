@extends('adminlte::page')
@section('title', 'Post')

@section('content_header')
@stop

@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">
        List Post

        <!-- Button trigger modal -->
        <button type="button" link="{{ route('post.create') }}" class="btn btn-primary btn-create btn-action btn-sm btn-flat " data-toggle="modal" data-target="#modelId">
            Add New Post
        </button>

        <!-- Modal -->
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <div class="box-body">
                <table class="table table-striped table-hover table-responsive" id="table">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th width="50%">Name</th>
                            <th width="20%">Category</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(document).ready(function() {


        table =
            $('#table').DataTable({
                //server-side
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{!! route('ApiPostjson') !!}",
                    "type": "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category.name',
                        name: 'category.name'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });



    });
</script>
@endpush
