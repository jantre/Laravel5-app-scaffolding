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
  // TODO: The route file is probably not the best place to handle this.
  //       Look into it.
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
}));

/** Pages that can be visited as guest only **/
Route::group(array('before' => 'guest'), function()
{
  /*
  * Login routes
  */
  Route::get('login', [
    'as'=>'login',
    'uses'=>'AuthenticationController@getLogin'
    ]);
  Route::post('login', 'AuthenticationController@postLogin');

  /*
  * Registration Routes
  */
  Route::get('signup',[
    'as'=>'signup',
    'uses'=>'AuthenticationController@getRegister'
    ]);
  Route::post('signup','AuthenticationController@postRegister');

  /* Registration confirmation route (If needed) */
  Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'AuthenticationController@confirm'
  ]);

  /*
  * Reset password routes
  */
  Route::get('password/reset/{token}', 'PasswordController@getReset');
  Route::post('password/reset/{token}','PasswordController@postReset');
  Route::controller('password','PasswordController');

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


Route::get('logout','AuthenticationController@getlogout');



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
