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

  //use AuthenticatesAndRegistersUsers;

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

  public function getRegister()
  {
    return view('auth.register');
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
  * Function to handle verification of Registration form
  * and creating a new user in the database.
  */
  public function postRegister()
  {

    $rules = [
      'username' => 'required|min:4|unique:users',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:4'
    ];

    $input = Input::only(
      'username',
      'email',
      'password'
    );

    $validator = Validator::make($input, $rules);
    if($validator->fails())
    {
      return Redirect::back()->withInput()->withErrors($validator);
    }
    $user = [
      'username' => Input::get('username'),
      'email' => Input::get('email'),
      'password' => Hash::make(Input::get('password'))
    ];

    if(!env('REGISTRATION_SKIP_VERIFICATION',0))
    {
      $confirmation_code = str_random(30);
      $user['confirmation_code'] = $confirmation_code;
      // TODO:  the user create function here and below are repetitive. Perhaps use an event trigger to clean this up.
      User::create($user);
      //TODO:  Check to see if the user was actually created before sending out an email.
      Mail::send('emails.auth.regverification', array('confirmation_code'=>$confirmation_code), function($message) {
        $message->to(Input::get('email'), Input::get('username'))->subject('Verify your email address');
        Session::flash('success','Thanks for signing up! Please check your email to complete the registration process.');
      });
    }
    else
    {
      // NO EMAIL VERIFICATION ( NOT RECOMMENDED FOR PRODUCTION)
      $user['status'] = 1;
      User::create($user)->assignRole('member');

      // Authenticate the user automatically since verification is not required.
      Auth::attempt(array('email'=>Input::get('email'),'password'=>Input::get('password')));
    }
    return Redirect::home();
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

  /**
   * This function will confirm if the given confirmation_code is valid for registration.
   * If it is valid, it will set the user's status to active.
   * @param  string $confirmation_code. The confirmation code recieved during registration.
   * @return redirect
   */
  public function confirm($confirmation_code)
  {
      if( ! $confirmation_code)
      {
        Session::flash('error','A confirmation code was not provided');
        return Redirect::to('/signup');
      }

      $user = User::where('confirmation_code','=',$confirmation_code)->first();

      if ( ! $user)
      {
        Session::flash('error','Invalid confirmation code');
        return Redirect::home();

      }
      $user->status = 1;
      $user->confirmation_code = null;
      $user->save();
      Auth::login($user);
      if (Auth::check()){
        //Session::flash('success','You have successfully verified your account.');
        return Redirect::to('/app/main')->with('success','You have successfully verified your account. Enjoy!');
      }else{
        // If for some reason we were not able to log the user in while they verfied their account.
        return Redirect::to('login')->with('success','You have successfully verified your account. Please log in');
      }
  }
}//end class
