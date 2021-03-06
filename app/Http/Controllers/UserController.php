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
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use DB;

class UserController extends Controller
{
    /**
   * Create a new authentication controller instance.
   *
   * @param  \Illuminate\Contracts\Auth\Guard $auth
   * @param  \Illuminate\Contracts\Auth\Registrar $registrar
   * @return void
   */
  public function __construct(Guard $auth, Registrar $registrar)
  {
      $this->auth = $auth;
      $this->registrar = $registrar;

    // Only Authenticated users can access this controller.
    $this->middleware('auth');
  }

  /**
   * Add the user to the users table.
   * Return the user object or false.
   * @param array $user
   * @param null $role
   * @return bool or User object
   */
  public function addUser(Array $user, $role = null)
  {
      User::create($user);
      $u = User::where('email', '=', $user['email'])->first();

      if (!$u)
      {
          return false;
      }

      if (!empty($role))
      {
          $u->assignRole($role);
      }
      return $u;
  }

    public function getChangePassword()
    {
        return view('user.settings.changePassword');
    }

    public function getProfile()
    {
        return view('user.settings.profile');
    }

  /**
   * Update the user's profile information.
   * @return [type]
   */
  public function postProfile()
  {
      if (!Auth::user()) {
          return Redirect::back()->withInput()->withErrors('error', 'Something went wrong. If the problem persists please log out and log back in then try again.');
      }
      if (Auth::user()->email != Input::get('email'))
      {
          $rules = ['email' => 'required|email|unique:users'];
          $validator = Validator::make(array('email'=>Input::get('email')), $rules);
          if ($validator->fails())
          {
              return Redirect::back()->withInput()->withErrors($validator);
          }

          Auth::user()->email = Input::get('email');
          Session::flash('info', 'Email address has been changed to '.Auth::user()->email);
          //TODO: Send out verification email to the new address.
      }
      Auth::user()->firstname = Input::get('firstname');
      Auth::user()->lastname = Input::get('lastname');
      Auth::user()->save();
      return Redirect::to('/app/main')->withSuccess("Profile Updated");
  }

  /**
   * Update the user's password to the new password.
   * @return [type]
   */
  public function postChangePassword()
  {
      $rules = ['currentpass' => 'required|min:6',
                  'newpass' => 'required|min:6',
                  'confirm-newpass' => 'required|min:6'
              ];
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails())
      {
          return Redirect::back()->withInput()->withErrors($validator);
      }
      if (Input::get('newpass')!=Input::get('confirm-newpass'))
      {
          return Redirect::back()->withInput()->withErrors("New Passwords and Confirm Password fields do not match. Please try again.");
      }
      if (!Hash::check(Input::get('currentpass'), Auth::user()->getAuthPassword()))
      {
          return Redirect::back()->withInput()->withErrors("Current password is incorrect. Please Try again.");
      }
      if (!Auth::user())
      {
          Session::flash('error', 'D\'oh! Something went wrong. If the problem persists please log out and log back in then try again.');
          return view('user.settings.profile');
      }
      Auth::user()->password=Hash::make(Input::get('newpass'));
      Auth::user()->save();
      return Redirect::to('/app/main')->withSuccess('Password has been changed!');
  }

    public function getSocialSettings()
    {
        $providers = [];
        $collection = DB::table('social_accounts')
            ->select('provider')
            ->where('user_id', '=', Auth::id())->get();
        foreach ($collection as $c)
        {
            $providers[] = $c->provider;
        }
        return view('user.settings.socialSettings')->withProviders($providers);
    }
}//end class

