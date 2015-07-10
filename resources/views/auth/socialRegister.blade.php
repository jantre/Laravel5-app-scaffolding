@extends('layouts.main')
@section('content')
    <div class='row'>
        <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block'>
            <div class='panel panel-info'>
                <div class="panel-heading">
                    <h3 class="panel-title">Signing in with {{$provider}}</h3>
                </div>
                <div class="panel-body">
                    <div class="social-form-warning alert-warning alert-block warningdiv">
                        We couldn't get your email address from {{$provider}}.
                        Provide your email address to complete the signup process.
                    </div>
                        {!! Form::open(array('url' => '/socialRegister','id'=>'socialregister-form','role'=>'form')) !!}
                        {!! Form::email('email', '' , array('placeholder'=>'Email','class'=>'form-control','required'=>'','autofocus'=>'')) !!}
                        <!-- TODO:  Add javascript validation -->
                        {!! Form::submit('Create Account', array('class' => 'btn btn-info')) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class='panel-footer'>Already Have an Account? {!!HTML::link('login','Login')!!}</div>
            </div>
        </div>
    </div>
@stop
