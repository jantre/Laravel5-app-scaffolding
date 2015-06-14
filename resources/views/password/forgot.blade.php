@extends('layouts.main')
@section('content')
<div class='row'>
  <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block'>
    <div class='panel panel-info'>
      <div class="panel-heading">
        <h3 class="panel-title">Forgot Password</h3>
      </div>
      <div class="panel-body">
        {!! Form::open(array('id'=>'fp-form')) !!}
        {!! Form::label('email','Email',array('class'=>'sr-only')) !!}
        {!! Form::email('email', Input::old('email'), array('placeholder'=>'Email','class'=>'form-control','required'=>'','autofocus'=>'')) !!}
        {!! Form::submit('Send Reminder',array('class'=>'btn btn-info')) !!}
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@stop