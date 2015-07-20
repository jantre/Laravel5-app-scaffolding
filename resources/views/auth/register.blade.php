@extends('layouts.main')
@section('content')
<div class='row'>
  <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block'>
    <div class='panel panel-signup'>
      <div class="panel-heading">
        <h3 class="panel-title">Sign up</h3>
      </div>
      <div class="panel-body">
          @include('forms.register_form')
          <div class="social-wrapper">
             <div class="connect-with-text">Or connect with</div>
              @include('social_buttons')
          </div>
      </div>

      <div class='panel-footer'>Already Have an Account? <a href="/login" class="link">Log in!</a></div>
    </div>
  </div>
</div>
@stop
