@extends('layout')

@section('content')

<div class='row'>
  <div class='landing-left hidden-xs col-sm-8 col-md-9'>
    @include('ipsum')
  </div>
  <div class='landing-right hidden-xs col-sm-4 col-md-3'>
    <div class='panel panel-info'>
      <div class="panel-heading">
        <h3 class="panel-title">Sign up</h3>
      </div>
      <div class="panel-body">
        @include('user.register_form')
      </div>
    </div>
  </div>
  <div class='hidden-sm hidden-md hidden-lg hidden-xl col-xs-10 col-sm-6 center-block login-form-wrapper'>
    <div class='panel panel-info'>
      <div class="panel-heading">
        <h3 class="panel-title">Sign In</h3>
      </div>
      <div class="panel-body">
        @include('user.login_form')
      </div>
      <div class="panel-footer">Don't have an account?  {!! HTML::link('signup','Signup') !!}</div>
    </div>
  </div>
</div>
@stop
