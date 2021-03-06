@extends('layouts.main')
@section('content')
<div class='row'>
  <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block login-form-wrapper'>
    <div class='panel'>
      <div class="panel-heading">
        <h3 class="panel-title">Change password</h3>
      </div>
      <div class="panel-body">
          {!! Form::open(array('url' => '/user/change-password','id'=>'profile-form')) !!}
		    <div class='form-group'>
			    {!! Form::label('currentpass','Current Password') !!}
	  			{!! Form::password('currentpass', array('class'=>'form-control','required'=>'','autofocus'=>'')) !!}
	  		</div>
			<div class='form-group'>
			    {!! Form::label('newpass','New Password') !!}
	  			{!! Form::password('newpass', array('class'=>'form-control','required'=>'')) !!}
	  		</div>
			<div class='form-group'>
				{!! Form::label('confirm-newpass','Confirm Password') !!}
	  			{!! Form::password('confirm-newpass', array('class'=>'form-control','required'=>'')) !!}
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