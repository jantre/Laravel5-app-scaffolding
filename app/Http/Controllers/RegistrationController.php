<?php namespace App\Http\Controllers;

use Input;
use Validator;
use Redirect;
use Hash;
use Mail;
use Auth;
use Session;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Requests\UserFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class RegistrationController extends Controller
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

    // Only guest's can access this controller.
    $this->middleware('guest', ['except' => 'getLogout']);
  }

    public function getRegister()
    {
        return view('auth.register');
    }

  /*
  * Function to handle verification of Registration form.
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
      if ($validator->fails())
      {
          return Redirect::back()->withInput()->withErrors($validator);
      }
      $user = [
          'username' => Input::get('username'),
          'email' => Input::get('email'),
          'password' => Hash::make(Input::get('password'))
      ];
      $UC = new UserController($this->auth, $this->registrar);
      $userObj = $UC->addUser($user);

      if (env('REQUIRE_REGISTRATION_VERIFICATION', 0))
      {
          // TODO: split confirmation_code into its own table.
          $confirmation_code = str_random(30);
          $userObj->confirmation_code = $confirmation_code;
          $userObj->save();
          Mail::send('emails.auth.regverification', array('confirmation_code'=>$confirmation_code), function ($message) {
              $message->to(Input::get('email'), Input::get('username'))->subject('Verify your email address');
              Session::flash('success', 'Thanks for signing up! Please check your email to complete the registration process.');
          });
      }
      else
      {
          // NO EMAIL VERIFICATION REQUIRED
          $userObj->status = 1;
          $userObj->save();
          // Authenticate the user automatically since verification is not required.
          Auth::loginUsingId($userObj->id);
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
      if (! $confirmation_code)
      {
          Session::flash('error', 'A confirmation code was not provided');
          return Redirect::to('/signup');
      }

      $user = User::where('confirmation_code', '=', $confirmation_code)->first();

      if (! $user)
      {
          Session::flash('error', 'Invalid confirmation code');
          return Redirect::home();
      }
      $user->status = 1;
      $user->confirmation_code = null;
      $user->save();
      $user->assignRole('member');
      Auth::login($user);
      if (Auth::check())
      {
          //Session::flash('success','You have successfully verified your account.');
          return Redirect::to('/app/main')->with('success', 'You have successfully verified your account. Enjoy!');
      }
      else
      {
          // If for some reason we were not able to log the user in while they verfied their account.
          return Redirect::to('login')->with('success', 'You have successfully verified your account. Please log in');
      }
  }
}//end class
