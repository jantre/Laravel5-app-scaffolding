<?php namespace App\Http\Controllers;

use Response;
use Input;
use Validator;
use Redirect;
use Hash;
use Mail;
use Auth;
use Session;
use App\Models\User;
use App\Http\Requests\UserFormRequest;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;


class AuthenticationController extends Controller
{

  /**
   * Create a new authentication controller instance.
   *
   * @param  \Illuminate\Contracts\Auth\Guard  $auth
   * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
   * @return void
   */
  public function __construct(Guard $auth, Registrar $registrar)
  {
    $this->auth = $auth;
    $this->registrar = $registrar;

    // Only guest's can access this controller, except for the getLogout method.
    $this->middleware('guest', ['except' => 'getLogout']);
  }

  /**
   * Simply return the login view
   * @return view
   */
  public function getLogin()
  {
    return view('auth.login');
  }

 /**
  * Clear authentication and session information
  * Redirect the user back to landing page.
  * @return [type]
  */
  public function getLogout()
  {
     $this->auth->logout();
    return Redirect::to('/'); //redirect to login page
  }

  /*
  * Function to handle a user login form verification
  * and authenticating with email & password credentials.
  */
  public function postLogin()
  {
    $rules = [
      'email' => 'required',
      'password' => 'required|min:6'
    ];

    $input = Input::only('email', 'password');

    $validator = Validator::make($input, $rules);

    if($validator->fails())
    {
        return Redirect::back()->withInput()->withErrors($validator);
    }

    $creds = [
        'password' => Input::get('password'),
        'status' => 1
    ];
    if ( ! Auth::attempt(array_merge(array('username'=>Input::get('email')),$creds),Input::get('rememberme') ) )
    {
      if ( ! Auth::attempt(array_merge(array('email'=>Input::get('email')),$creds),Input::get('rememberme') ) )
      {
        return Redirect::to('login')
            ->withInput()
            ->withErrors([
                'credentials' => 'The email and password you entered does not match our records. Please try again.'
            ]);
      }
    }
    return Redirect::home();
  }
}//end class
