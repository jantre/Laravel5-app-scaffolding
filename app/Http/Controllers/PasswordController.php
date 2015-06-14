<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Validator;
use Input;
use Redirect;
use App;
use Log;
use Password;
use Lang;
use Hash;

class PasswordController extends Controller {

use ResetsPasswords;

  protected $redirectTo = '/';
  /**
   * Create a new password controller instance.
   *
   * @param  \Illuminate\Contracts\Auth\Guard  $auth
   * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
   * @return void
   */
  public function __construct(Guard $auth, PasswordBroker $passwords)
  {
    $this->auth = $auth;
    $this->passwords = $passwords;

    $this->middleware('guest');
  }


  /**
  * return the forgot password page.
  */
  public function getForgot(){
    return view('password.forgot');
  }

  /**
   * Send a reset link to the given user.
   *
   * @param  Request  $request
   * @return Response
   */
  public function postForgot(Request $request)
  {
    $validator = Validator::make(
        ['email' => $request->get('email')],
        ['email' => 'required|email']
    );

    if($validator->fails())
    {
      return Redirect::back()->withInput()->withErrors($validator);
    }

    $response = $this->passwords->sendResetLink($request->only('email'), function ($m) {
      // Set the subject of the password reset email.
      $m->subject($this->getEmailSubject());
    });

    switch ($response) {
      case PasswordBroker::RESET_LINK_SENT:
        return Redirect::to('login')->withSuccess('Please check your email for password reset instructions.');

      case PasswordBroker::INVALID_USER:
        return redirect()->back()->withErrors(['username' => trans($response)]);

      default:
        return redirect()->back()->withErrors(['username' => trans($response)]);
    }
  }

  public function getReset($token = null)
  {
    if(is_null($token))
    {
      //log::warning("someone is trying to access the password.reset view without a token");
      return Redirect::to('/password/forgot');
    }
    return view('password.reset')->withToken($token);
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
            array('email' => 'required|unique:users,email|email', 'password' => 'required|min:4|max:20|same:password_confirm')
        );
    $response = Password::reset($credentials, function($user, $password)
    {
      $user->password = Hash::make($password);
      $user->save();
    });

    switch ($response)
    {
      case Password::INVALID_TOKEN:
        return Redirect::to('password/forgot')->with('error',Lang::get($response));

      case Password::INVALID_PASSWORD:
      case Password::INVALID_USER:
        return Redirect::back()->with('error', Lang::get($response));

      case Password::PASSWORD_RESET:
        return Redirect::to('/login')->with('success','Password was successfully reset!<br> You make now Log in.');
    }
  }
}
