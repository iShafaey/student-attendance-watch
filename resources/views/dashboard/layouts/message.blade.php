@if(session()->has('success'))
    <div class="alert alert-success">
        {!! session()->get('success') !!}
    </div>
@elseif(session()->has('warning'))
    <div class="alert alert-warning">
        {!! session()->get('warning') !!}
    </div>
@elseif(session()->has('error'))
    <div class="alert alert-danger">
        {!! session()->get('error') !!}
    </div>
@elseif(session()->has('info'))
    <div class="alert alert-info">
        {!! session()->get('info') !!}
    </div>
@elseif (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif
