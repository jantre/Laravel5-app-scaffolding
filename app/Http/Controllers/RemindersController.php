<?php
namespace App\Http\Controllers;

use Validator;

class RemindersController extends Controller {

  /**
   * Display the password reset view for the given token.
   *
   * @param  string  $token
   * @return Response
   */
  public function getReset($token = null)
  {
    if (is_null($token)) App::abort(404);

    return view('password.reset')->with('token', $token);
  }

  /**
   * Handle a POST request to reset a user's password.
   *
   * @return Response
   */
  public function postReset()
  {
    $credentials = Input::only(
      'email', 'password', 'password_confirmation', 'token'
    );
    $validator = Validator::make(
            array('email' => Input::get('email'), 'password' => Input::get('password'), 'password_confirm' => Input::get('password_confirm')),
            array('email' => 'required|unique:users,email|email', 'password' => 'required|min:6|max:20|same:password_confirm')
        );
    $response = Password::reset($credentials, function($user, $password)
    {
      $user->password = Hash::make($password);

      $user->save();
    });

    switch ($response)
    {
      case Password::INVALID_PASSWORD:
      case Password::INVALID_TOKEN:
      case Password::INVALID_USER:
      return Redirect::back()->with('error', Lang::get($response));

      case Password::PASSWORD_RESET:
      Session::flash('success','Password was successfully reset');
      return Redirect::to('/user/login');
    }
  }

}
