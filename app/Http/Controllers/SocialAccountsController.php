<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
//use Illuminate\Http\Request;
use Auth;
use Log;
use DB;
use Request;
//use App\Http\Requests;
//use App\Http\Controllers\Controller;
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
    $social = Socialite::with($provider)->user();
    //dd($social);
    //$user->debug = "YOu have logged in with $provider";
    //Check if ID and provider exist in the social table.
      $SA = SocialAccount::where('provider_uid','=',$social->getId())->first();
      if(!$SA)
      {
        $user = User::where('email','=',$social->getEmail())->first();
        if(!$user) {
          $user = User::create(['email' => $social->getEmail(),
              'firstname' => $social->getName(),
              'username' => $social->getEmail(), // TODO: Nickname can be null from the social provider
              'status' => 1
            ]);
        }
        $SA = SocialAccount::create(['provider_uid' => $social->getId(),
          'provider' => $provider,
          'user_id' => $user->id,
          'oauth_token' => $social->token,
        ]);
      }

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
}
