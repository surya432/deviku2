{!! Form::open(array('route' => ['content.store'],'method'=>'POST','role' => 'form','autocomplete'=>'off', 'id' => 'my_form')) !!}
{{ Form::hidden('post_id', null, array('id' => 'invisible_id','class'=>'PostId',)) }}

<div class="modal-header">
    <h5 class="modal-title">Modal title</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 dynamicbox">
            <div class="form-group">
                <strong>Title Link:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Category Name','class' => 'form-control') ) !!}
            </div>

            <div class="form-group">
                <strong>Link Video 1:</strong>
                <div class="input-group control-group increment">
                    {!! Form::text('links[0][link]', null, array('placeholder'=> 'Link Google Drive','class' => 'form-control col-lg-8') ) !!}
                    {!! Form::text('links[0][kualitas]', null, array('placeholder' => 'Kualitas','class' => 'form-control ') ) !!}
                    <div class="input-group-btn">
                        <button id="remove-tr" link="" class="btn btn-danger remove-tr"><i class="glyphicon glyphicon-trash"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button id="addlinkDrive" link="" count="0" class="btn btn-success addlinkDrive"><i class="glyphicon glyphicon-plus"></i></button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" id="saveBtn" value="create" class="btn btn-primary btn-submit btn-action">Save changes</button>
</div>

{!! Form::close() !!}