{!! Form::open(array('id'=>'fp-form')) !!}
<input type="hidden" name="token" value="{!! $token !!}">
{!! Form::email('email', Input::old('email'), array('placeholder'=>'Email','class'=>'form-control','required'=>'','autofocus'=>'')) !!}
{!! Form::password('password',array('placeholder'=>'Password','class'=>'form-control','required'=>'')) !!}
{!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password','class'=>'form-control')) !!}
{!! Form::submit('Reset Password', array('class' => 'btn btn-info')) !!}
{!! Form::close() !!}