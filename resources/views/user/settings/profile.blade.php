@extends('layouts.main')
@section('content')
<div class='row'>
  <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block login-form-wrapper'>
    <div class='panel'>
      <div class="panel-heading">
        <h3 class="panel-title">Edit Profile</h3>
      </div>
      <div class="panel-body">
          {!! Form::open(array('url' => '/user/profile','id'=>'profile-form')) !!}
            <div class='form-group'>
			    {!! Form::label('email','Email') !!}
  				{!! Form::email('email', Auth::user()->email, array('placeholder'=>'Email','class'=>'form-control','required'=>'','autofocus'=>'')) !!}
  			</div>
  			<div class='form-group'>
				{!! Form::label('firstname','First Name') !!}
				{!! Form::text('firstname', Auth::user()->firstname,['class'=>'form-control']) !!}
			</div>
			<div class='form-group'>
				{!! Form::label('lastname','Last Name') !!}
				{!! Form::text('lastname', Auth::user()->lastname,['class'=>'form-control']) !!}
			</div>
      </div>
        <!-- TODO:  Add javascript validation -->
		<div class='panel-footer'>
			{!! Form::submit('Save', ['class' => 'btn button']) !!}
			{!! HTML::link('/app/main','Cancel',array('class'=>'btn button button-cancel'))!!}
		</div>
		{!! Form::close() !!}
    </div>
  </div>
</div>
@stop

