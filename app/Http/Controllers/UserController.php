<?php
namespace app\Http\Controllers;

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

class UserController extends Controller {

  public function getLogin()
  {
    return view('user.login');
  }


  /*
  * Clear authentication and session information
  * Redirect the user back to landing page.
  */
  public function getLogout()
  {
    Auth::logout(); //logout the current user
    Session::flush(); //delete the session
    return Redirect::to('/'); //redirect to login page
  }

  public function getForgotpassword(){
    return view('password.remind');
  }

  public function postForgotpassword(){
    //TODO:  Validate that an email address was given.
    $email=trim(Input::get('email'));
    if(empty($email)){
      return Redirect::to('/forgotpassword')->withErrors('The email field is required.');
    }
    if(!$this->checkEmailRegistered($email)){
      return Redirect::to('/forgotpassword')->withErrors($email.' is not registered with us. Would you like to <a href=\'/signup\'>Sign up</a>?');
    }
        switch ($response = Password::remind(Input::only('email')))
    {
      case Password::INVALID_USER:
        return Redirect::to('login')->with('error', Lang::get($response));

      case Password::REMINDER_SENT:
        Session::Flash('success','Please check your email for password reset instructions.');
        return Redirect::to('login')->withInput(array('email'=>Input::get('email')));
    }
  }
  public function getRegister(){
    return view('user.register');
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

    if(!env('REGISTRATION_SKIP_VERIFICATION',0)){
      $confirmation_code = str_random(30);
      $user['confirmation_code'] = $confirmation_code;
      // TODO:  the user create function here and below are repetative. Perhaps use an event trigger to clean this up.
      User::create($user);
      //TODO:  Check to see if the user was actually created before sending out an email.
      Mail::send('emails.auth.regverification', array('confirmation_code'=>$confirmation_code), function($message) {
        $message->to(Input::get('email'), Input::get('username'))->subject('Verify your email address');
        Session::flash('success','Thanks for signing up! Please check your email to complete the registration process.');
      });
    }else{
      // NO EMAIL VERIFICATION ( NOT RECOMMENDED FOR PRODUCTION)
      $user['status'] = 1;
      User::create($user);
      // Authenticate the user automatically since verification is not required.
      Auth::attempt(array('email'=>Input::get('email'),'password'=>Input::get('password')));
    }
    return Redirect::home();
  }

  /*
  *  Function to handle a user login form verification
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

    Session::flash('message','Welcome back!');

    return Redirect::home();
  }

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


  public function postProfile(){
    if(!Auth::user()){
      return Redirect::back()->withInput()->withErrors('error','D\'oh! Something went wrong. If the problem persists please log out and log back in then try again.');
    }
    if(Auth::user()->email != Input::get('email')){
      $rules = ['email' => 'required|email|unique:users'];
      $validator = Validator::make(array('email'=>Input::get('email')), $rules);
      if($validator->fails())
      {
        return Redirect::back()->withInput()->withErrors($validator);
      }

      Auth::user()->email = Input::get('email');
      Session::flash('info','Email address has been changed to '.Auth::user()->email);
      //TODO: Send out verification email to the new address.
    }
    Auth::user()->firstname = Input::get('firstname');
    Auth::user()->lastname = Input::get('lastname');
    Auth::user()->save();
    Session::flash('success','Profile Updated');
    return Redirect::to('/app/main');
  }

  public function postChangePassword(){
    $rules = ['currentpass' => 'required|min:6',
              'newpass' => 'required|min:6',
              'confirm-newpass' => 'required|min:6'
              ];
    $validator = Validator::make(Input::all(), $rules);
    if($validator->fails())
    {
      return Redirect::back()->withInput()->withErrors($validator);
    }
    if(Input::get('newpass')!=Input::get('confirm-newpass')){
      return Redirect::back()->withInput()->withErrors("New Passwords and Confirm Password fields do not match. Please try again.");
    }
    if(!Hash::check(Input::get('currentpass'), Auth::user()->getAuthPassword())){
      return Redirect::back()->withInput()->withErrors("Current password is incorrect. Please Try again.");
    }
    if(!Auth::user()){
      Session::flash('error','D\'oh! Something went wrong. If the problem persists please log out and log back in then try again.');
      return view('user.settings.profile');
    }
    Auth::user()->password=Hash::make(Input::get('newpass'));
    Auth::user()->save();
    return Redirect::back()->withSuccess('Password has been changed!');
  }
  /*********************
  ** HELPER FUNCTIONS **
  **********************/
  
 
  /*
  *  Function that checks to see if the user exists in our system.
  */
  private function checkEmailRegistered($email){
    if(sizeof(User::where('email','=',$email)->get()) > 0){
      // email has been registered.
      return 1;
    }else{
      //email has not been registered
      return 0;
    }
  }

}//end class