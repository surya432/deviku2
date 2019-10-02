{!! Form::open(array('route' => ['post.store'],'method'=>'POST','role' => 'form', 'autocomplete'=>"off",'id' => 'my_form')) !!}

<div class="modal-header">
    <h5 class="modal-title">Modal Create</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name</strong>
                {!! Form::text('name', null, array('placeholder' => 'Title','class' => 'form-control') ) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Sources</strong>
                {!! Form::text('sources', null, array('placeholder' => 'Sources Url','class' => 'form-control') ) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Category:</strong>
                {!! Form::select('category_id', $items, null,array('class' => 'form-control') ) !!}
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" id="saveBtn" value="create" class="btn btn-primary btn-submit btn-action">Save changes</button>
</div>
{!! Form::close() !!}