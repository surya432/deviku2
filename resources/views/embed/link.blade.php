
    @if(!empty($url_googledrive))
    <li class="active linkserver" data-status="0" data-video="{{$url_googledrive}}">drive.google.com</li>
    @endif
    @if(!empty($url_fembed))
    <li class="linkserver" data-status="1" data-video="{{$url_fembed}}">fembed.com</li>
    @endif
    @if(!empty($url_openload))
    <li class="linkserver" data-status="1" data-video="{{$url_openload}}">openload.com</li>
    @endif
    @if(!empty($url_rapidvideo))
    <li class="linkserver" data-status="1" data-video="{{$url_openload}}">rapidvideo.com</li>
    @endif
