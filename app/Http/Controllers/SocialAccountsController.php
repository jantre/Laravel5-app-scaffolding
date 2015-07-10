<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Http\Request;
use Auth;
use Log;
use DB;
use Request;
use View;
Use Session;
use Input;
use App\Models\User;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;


class SocialAccountsController extends Controller
{
  private $scopes = [];
  /*
* Socialite functions
*/
//TODO: Perhaps a better design is to use an interface with a class for each social provider.
  public function getIndex($provider)
  {
    switch ($provider)
    {
      case "facebook":
        $this->scopes = ['email','publish_actions'];
        break;
      case "google":
        break;
      default:
        return Redirect::home()->withError('Unknown provider ' . $provider);
    }

    if(!empty($scopes)){
      return Socialite::with($provider)->scopes($this->scopes)->redirect();
    }
    return Socialite::with($provider)->redirect();
  }

  public function handleProviderCallback($provider)
  {
    if(!Request::has('code')){
     return Redirect::to('/signup');
    }

    try
    {
    $social = Socialite::with($provider)->user();
    } catch(ClientException $c) {
      Log::error($c);
      abort(400);
    }

    // Check if ID and provider exist in the social table.
    $SA = SocialAccount::where('provider_uid','=',$social->getId())->first();
    if(!$SA)
    {
      if(empty($social->getEmail())){
        Session::put('social',$social);
        Session::put('provider',$provider);
        return View::make('auth.socialRegister')->withProvider(ucfirst($provider));;
      }
      $this->socialRegistration();
      }

    // TODO: clean up this repetative authenticate and redirect also being done in the socialRegister function below
    Auth::loginUsingId($SA->user_id);

    return Redirect::home();


    //If so then we can log this user into the application

    // If not then this is a registration.  Pass the user object to the registrationController
    //dd($user);
    // $user->token;
// All Providers

//    $user->getNickname();
//    $user->getName();
//    $user->getEmail();
//    $user->getAvatar();
  }

  /**
   * This function can either be called from handleProviderCallback, where $email would be null
   * Or it can be called from a socialRegister form submit on view auth.socialRegister
   * @param Socialite $social
   * @param $email
   * @return Eloquent model
   */
  public function socialRegistration(){
    $social = Session::get('social');
    $provider = Session::get('provider');
    $email = @Input::get('email') ? : $social->getEmail();

    if(empty($email)){
      Log::error('Email is empty');
      abort(400,'Email is empty');
    }

    $user = User::where('email','=',$email)->first();
    if(!$user) {
      $user = User::create(['email' => $email,
        'firstname' => $social->getName(),
        'username' => $email, // Nickname can be null from the social provider so just use email
        'status' => 1
      ]);
    }
    $SA = SocialAccount::create(['provider_uid' => $social->getId(),
      'provider' => $provider,
      'user_id' => $user->id,
      'oauth_token' => $social->token,
    ]);
    Session::forget('social');
    Session::forget('provider');

    // Authenticating and redirecting here because we can't simply return $SA
    // for the reason that this function is called directly from socialRegister route.
    // TODO: clean up this repetative authenticate and redirect also being done in the handler above
    Auth::loginUsingId($SA->user_id);

    return Redirect::home();
  }

}
