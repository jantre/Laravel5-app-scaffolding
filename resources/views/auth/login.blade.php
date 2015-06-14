@extends('layouts.main')
@section('content')
<div class='row'>
  <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block login-form-wrapper'>
    <div class='panel panel-info'>
      <div class="panel-heading">
        <h3 class="panel-title">Sign In</h3>
      </div>
      <div class="panel-body">
        @include('forms.login_form')
      </div>
      <div class="panel-footer">Don't have an account?  {!! HTML::link('signup','Signup') !!}</div>
    </div>
  </div>
</div>
@stop
