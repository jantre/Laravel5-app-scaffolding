{!! Form::open(array('url' => '/login','id'=>'login-form','class'=>'form-signin','role'=>'form')) !!}
  {!! Form::label('email','Email',array('class'=>'sr-only')) !!}
  {!! Form::text('email', Input::old('email'), array('placeholder'=>'Username or Email','class'=>'form-control','required'=>'','autofocus'=>'')) !!}

  {!! Form::label('password','Password',array('class'=>'sr-only')) !!}
  {!! Form::password('password',array('placeholder'=>'Password','class'=>'new form-control','required'=>'')) !!}</li>

  {!! Form::submit('Log In', array('class' => 'btn btn-sm button')) !!}
{!! Form::close() !!}
<div class="links">
  <div class="col-xs-12 col-sm-6 col-md-6 remembermewrapper">
    <input type="checkbox" name='rememberme' value="rememberme" class='remembermechk' checked='checked'><span class='link remembermespan'> Remember me</span>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6">
    <a href='/password/forgot' class='link'>Forgot Password?</a>
  </div>
</div>

