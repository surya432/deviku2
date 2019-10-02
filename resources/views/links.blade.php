@if(!empty($url_detail))
<button link="{{ $url_detail }}" class="btn btn-success btn-detail btn-action btn-sm btn-flat "><i class='fa fa-eye'></i></button>
@endif
@if(!empty($url_view))
<a href="{{ $url_view }}" class="btn btn-success btn-sm btn-flat "><i class='fa fa-eye'></i></a>
@endif
@if(!empty($url_edit))
<button link="{{ $url_edit }}" class='btn btn-primary btn-edit btn-action btn-sm btn-flat '><i class='fa fa-pencil' aria-hidden="true"></i></button>
@endif
@if(!empty($url_hapus))
<button link="{{ $url_hapus }}" class='btn btn-danger btn-sm btn-action delete btn-flat '><i class="fa fa-trash" aria-hidden="true"></i></button>

@endif
{{-- <button class  = 'btn btn-danger btn-sm btn-action'><i class  = 'fa fa-close'></i></button> --}}