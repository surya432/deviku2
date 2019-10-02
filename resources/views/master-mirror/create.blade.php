{!! Form::open(array('route' => ['ApiMasterMirrorStore'],'method'=>'POST','role' => 'form', 'id' => 'my_form')) !!}

<div class="modal-header">
    <h5 class="modal-title">Modal title</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Status:</strong>
                <select class="form-control" name="status">
                    <option value="Up">Up</option>
                    <option value="Down">Down</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" id="saveBtn" value="create" class="btn btn-primary btn-submit btn-action">Save changes</button>
</div>
{!! Form::close() !!}