@extends('layout')

@section('content')
<div class='form-block'>
  {!! Form::open(array('id'=>'fp-form')) !!}
  <ul>
    <li class='form-title'>Reset Password</li>
    <li>
      <input type="hidden" name="token" value="{!! $token !!}">
      {!! Form::text('email', Input::old('email'), array('placeholder' => 'Email')) !!}
    </li>
  	<li>{!! Form::password('password', array('placeholder' => 'Password')) !!}</li>
  	<li>{!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password')) !!}</li>
    <li><input type="submit" class='submit-button' value="Reset Password"></li>
  </ul>
  {!! Form::close() !!}
</div>
@stop