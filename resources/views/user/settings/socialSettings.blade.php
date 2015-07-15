<?php
$listedProviders = ['facebook','google'];
?>
@extends('layouts.main')
@section('content')
    @foreach($listedProviders as $lp)
        @if(in_array($lp,$providers))
            <div class="social-wrapper col-xs-4 col-lg-4">
                    <i class="btn btn-{{$lp}} fa fa-{{$lp}}"></i>
                </a> <b>Connected with {{ ucfirst($lp) }}</b>
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" >Disconnect</a>
            </div>

        @else
            <div class="social-wrapper col-xs-4 col-lg-4">
            <a href="/sociallogin/{{$lp}}" class="btn btn-social btn-{{$lp}}">
                    <i class="fa fa-{{$lp}}"></i>Connect with {{ ucfirst($lp) }}
                </a>
            </div>
        @endif
        <div></div>
    @endforeach

@stop