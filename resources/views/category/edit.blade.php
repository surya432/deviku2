{!! Form::open(array('route' => ['category.update', $data->id],'method'=>'PATCH','role' => 'form', 'id' => 'my_form')) !!}
{{ Form::hidden('id', $data->id, array('id' => 'invisible_id')) }}

<div class="modal-header">
    <h5 class="modal-title">Modal Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name</strong>
                {!! Form::text('name', $data->name, array('placeholder' => 'Category','class' => 'form-control') ) !!}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" id="saveBtn" value="create" class="btn btn-primary btn-submit btn-action">Save changes</button>
</div>
{!! Form::close() !!}