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
  //From the forgot password page we can't seem to pass back
  //$success unless we do it this way.
  if(empty($success)){
    return View::make('landing');
  }
  return View::make('landing')->with('success',$success);

}
));

/** Pages that can be visited as guest only **/
Route::group(array('before' => 'guest'), function()
{
  Route::get('login', [
    'as'=>'login',
    'uses'=>'UserController@getLogin'
    ]);
  Route::post('login', 'UserController@postLogin');

  Route::get('forgotpassword', [
    'as'=>'forgotpassword',
    'uses'=>'UserController@getForgotpassword'
  ]);
  Route::post('forgotpassword', 'UserController@postForgotpassword');

  Route::get('signup',[
    'as'=>'signup',
    'uses'=>'UserController@getRegister'
    ]);
  Route::post('signup', 'UserController@postRegister');

  Route::get('password/reset/{token}', 'RemindersController@getReset');
  Route::post('password/reset/{token}','RemindersController@postReset');
  Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'UserController@confirm'
  ]);
});

Route::group(array('before' => 'auth'), function()
{
  Route::get('/user/profile',function()
  {
    return View::make('user.settings.profile');
  });

  Route::get('/user/change-password',function()
  {
    return View::make('user.settings.changepassword');
  });
  Route::post('/user/change-password','UserController@postChangePassword');
  /* Ajax forms to handle form submisison*/
  Route::get('/user/settings/ajax/forms/{page}',function($page)
  {
     return View::make('user.settings.'.$page);
  });

  Route::controller('app', 'AppController');

  Route::post('/user/profile','UserController@postProfile');


});


//Route::get('user/profile/{username}','UserController@viewProfile');


Route::get('logout','UserController@getlogout');



/* alias to sign up page */
Route::get('register', function()
{
  return Redirect::to('/signup');
});

// Redirect auth/login to just /login so we don't have to change app/Http/Middleware/Authenticate.php
Route::get('auth/login',function()
{
  return Redirect::to('/login');
});