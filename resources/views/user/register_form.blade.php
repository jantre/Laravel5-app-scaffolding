{!! Form::open(array('url' => '/signup','id'=>'signup-form','role'=>'form')) !!}
  {!! Form::email('email', Input::old('email'), array('placeholder'=>'Email','class'=>'form-control','required'=>'','autofocus'=>'')) !!}
	{!! Form::text('username', Input::old('username'), array('placeholder' => 'Username','class'=>'form-control','required'=>'')) !!}
	{!! Form::password('password',array('placeholder'=>'Password','class'=>'form-control','required'=>'')) !!}
<!-- TODO:  Add javascript validation -->
	{!! Form::submit('Create Account', array('class' => 'btn btn-info')) !!}
  {!! Form::close() !!}