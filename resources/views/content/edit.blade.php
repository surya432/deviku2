{!! Form::open(array('route' => ['content.update', $data->id],'method'=>'PATCH','role' => 'form', 'autocomplete'=>'off','id' => 'my_form')) !!}
{{ Form::hidden('id', $data->id, array('id' => 'invisible_id')) }}

<div class="modal-header">
    <h5 class="modal-title">Modal Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 dynamicbox">
            <div class="form-group">
                <strong>Name</strong>
                {!! Form::text('name', $data->name, array('placeholder' => 'Category','class' => 'form-control') ) !!}
            </div>
            <?php $i = 0; ?>
            @foreach($data->links as $items)

            <div class="form-group">
                <strong>Link Video:</strong>
                <div class="input-group control-group increment">
                    {{ Form::hidden("links[$i][id]", $items->id, array('id' => 'invisible_id')) }}
                    {!! Form::text("links[$i][link]", $items->link, array('placeholder'=> 'Link Google Drive','class' => 'form-control col-lg-8') ) !!}
                    {!! Form::text("links[$i][kualitas]", $items->kualitas, array('placeholder' => 'Kualitas','class' => 'form-control ') ) !!}
                    <div class="input-group-btn">
                        <button id="remove-tr" link="{{ route('metalink.destroy',$items->id)}}" class="btn btn-danger remove-tr"><i class="glyphicon glyphicon-trash"></i></button>
                    </div>
                </div>
                <?php ++$i; ?>

            </div>
            @endforeach

        </div>
    </div>
</div>
<div class="modal-footer">
    <button id="addlinkDrive" count="{{$i}}" class="btn btn-success text-center addlinkDrive"><i class="glyphicon glyphicon-plus"></i></button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" id="saveBtn" value="create" class="btn btn-primary btn-submit btn-action">Save changes</button>
</div>

{!!Form::close() !!}