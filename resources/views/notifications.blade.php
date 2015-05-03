
@if (count($errors->all()) > 0)
  <div class="alert alert-error alert-block errordiv">
    <ul>
      @foreach ($errors->all() as $error)
        <li>
          <span class='glyphicon glyphicon-exclamation-sign'></span>
          <span class='error'> {!! $error !!}</span>
        </li>
      @endforeach
    </ul>
  </div>
@endif

@if ($message = Session::get('success'))
  <div class="alert alert-success alert-block successdiv">
    {!! $message !!}
  </div>
@endif

@if ($message = Session::get('error'))
  <div class="alert alert-error alert-block errordiv">
    <h4>Error</h4>
    <ul>
      <li class='danger'>
        <span class='glyphicon glyphicon-exclamation-sign'></span>
        <span class='error'> {!! $message !!}</span>
      </li>    
    </ul>
  </div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block warningdiv">
  <h4>Warning</h4>
  {!! $message !!}
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block infodiv">
  {!! $message !!}
</div>
@endif