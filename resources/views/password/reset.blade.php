@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-xs-10 col-sm-6 col-md-5 col-lg-4 center-block'>
            <div class='panel panel-info'>
                <div class="panel-heading">
                    <h3 class="panel-title">Reset Password</h3>
                </div>
                <div class="panel-body">
                    @include('forms.passwordReset_form')
                </div>
                <div class='panel-footer'>Remember your password? {!!HTML::link('login','Login')!!}</div>
            </div>
        </div>
    </div>
@stop