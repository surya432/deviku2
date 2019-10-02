@extends('adminlte::page')
@section('title', 'Title Post')

@section('content_header')
@stop

@section('content')
<div class="row">
    <section class="col-lg-12 connectedSortable ui-sortable">
        <div class="nav-tabs-custom" style="cursor: move;">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                <li class=""><a href="#revenue-chart" data-toggle="tab" aria-expanded="false">Detail</a></li>
                <li class="active"><a href="#sales-chart" data-toggle="tab" aria-expanded="true">Link</a></li>
                <li class="pull-left header"><i class="fa fa-inbox"></i> Links</li>
            </ul>
            <div class="tab-content no-padding">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane" id="revenue-chart" style="position: relative;">
                    Dalam Pengembangan
                </div>
                <div class="chart tab-pane active" id="sales-chart" style="position: relative;">
                    <div class="col-lg-12">
                        <!-- Button trigger modal -->
                        <button type="button" link="{{ route('content.create') }}" class="btn btn-primary btn-addlink btn-create btn-action btn-sm btn-flat " data-toggle="modal" data-target="#modelId">
                            Add New Link
                        </button>
                        <div class="table-responsive">
                            <div class="box-body">
                                <table class="table table-striped table-hover table-responsive" id="table">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Name</th>
                                            <th width="20%">Created By</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
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


        $('body').on('click', '.btn-addlink', function(elemen) {
            elemen.preventDefault();
            $(this).attr('link');
        });
        table =
            $('#table').DataTable({
                //server-side
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{!! route('ApiContentjson') !!}",
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
                        data: 'createdBy',
                        name: 'createdBy'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });


        $('body').on('click', '.addlinkDrive', function(elemen) {
            elemen.preventDefault();
            var i = $(this).attr('count');
            ++i;
            j = i + 1;
            $(".dynamicbox").append(
                '<div class="form-group"><tr><strong>Link Video ' + j + ':</strong>' +
                '<div class = "input-group control-group increment"> ' +
                '<input type="text" name="links[' + i + '][link]" placeholder="Link Google Drive" class="form-control" /></td>' +
                '<input type="text" name="links[' + i + '][kualitas]" placeholder="Kualitas" class="form-control" /></td>' +
                '<div class="input-group-btn"><button type="button" link="" class="btn btn-danger remove-tr"><i class="glyphicon glyphicon-trash"></i></button></td>' +
                '</tr></div>');
            $(this).attr("count", i);

        });

        $('body').on('click', '.remove-tr', function(elemen) {
            elemen.preventDefault();
            const urlsdelete = $(this).attr('link');
            if (urlsdelete == "") {
                $(this).parent().parent().parent().remove();
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to delete this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios({
                            url: urlsdelete,
                            credentials: true,
                            method: "DELETE",
                        }).then(response => {
                            console.log(response.data.status);
                            $(this).parent().parent().parent().remove();
                            swal2(response.data.status, response.data.message);
                        }).catch(error => {
                            console.log(error.response);
                        });
                    }
                })
            }


        });

    });
</script>
@endpush