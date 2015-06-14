@extends('layouts.email')

@section('title')
    One more step to create your account!
@stop

@section('body')
    To create your account you need to verify your email address simply by clicking on the link below<br/>
    {!! URL::to('register/verify/' . $confirmation_code) !!}.<br/><br/>
    This link will expire in {!! Config::get('auth.reminder.expire', 60) !!} minutes.
@stop
