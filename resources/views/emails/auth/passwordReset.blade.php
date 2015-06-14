@extends('layouts.email')

@section('title')
    Password Reset
@stop

@section('body')
    To reset your password, complete this form: {!! URL::to('password/reset', array($token)) !!}.<br/><br/>
    This link will expire in {!! Config::get('auth.reminder.expire', 60) !!} minutes.
@stop
