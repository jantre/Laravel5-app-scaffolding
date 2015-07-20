@extends('layouts.main')
@section('content')
<div class='row'>
  <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block login-form-wrapper'>
    <div class='panel panel-signin'>
      <div class="panel-heading">
        <h3 class="panel-title">Log In</h3>
      </div>
      <div class="panel-body">
        @include('forms.login_form')
          <div class="social-wrapper">
              <div class="connect-with-text">Or log in with</div>
              @include('social_buttons')
          </div>
      </div>
      <div class="panel-footer">Don't have an account?  <a href='/signup' class='link'>Sign up!</a></div>
    </div>
  </div>
</div>
@stop
