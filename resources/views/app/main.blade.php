@extends('layout')

@section('content')
<div class='row'>
  <div class='hidden-xs list-group col-sm-3'>
    @include('app.app_menu')
  </div>

  <div class='col-xs-12 col-sm-9'>
		{!! Form::label('til', 'Submit something..') !!}
		{!! Form::textarea('til') !!}
  	<div>
  		{!! Form::button('Submit', ['class' => 'submit-button tillog-button']) !!}
  	</div>
    <hr>
  </div>
</div>

@stop
