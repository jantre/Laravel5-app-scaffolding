<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', array('as'=>'home', 'uses'=> function()
{
  // If the user is already logged in, redirect them into the app.
  if(Auth::check()){
    return Redirect::to('/app/main');
  }
  //TODO: Something to look into here.
  //From the forgot pwd page we can't seem to pass back
  //$success unless we do it this way.
  if(empty($success)){
    return View::make('landing');
  }
  return View::make('landing')->with('success',$success);

}
));