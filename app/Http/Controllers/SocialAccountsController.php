<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Auth;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;


class SocialAccountsController extends Controller
{
  /*
* Socialite functions
*/

  public function getIndex($provider)
  {
    switch ($provider)
    {
      case "facebook":
        $scopes = ['email','publish_actions'];
        break;
      default:
        return Redirect::home()->withError('Unknown provider ' . $provider);
    }
    return Socialite::with($provider)->scopes($scopes)->redirect();
  }

  public function handleProviderCallback($provider)
  {

    $social = Socialite::with($provider)->user();
    //$user->debug = "YOu have logged in with $provider";
    //Check if ID and provider exist in the social table.
      $SA = SocialAccount::where('provider_uid','=',$social->getId())->first();
      if(!$SA)
      {
        Log::debug("Social Account DOES NOT exist.");
        $user = User::where('email','=',$social->getEmail())->first();
        if(!$user) {
          $user = User::create(['email' => $social->getEmail(),
              'firstname' => $social->getName(),
              'username' => $social->getEmail(), // TODO: Nickname can be null from the social provider
              'status' => 1
            ]);
        }
        //TODO: This is not producing the right insert query.
        $SA = SocialAccount::create(['provider_uid' => $social->getId(),
          'provider' => $provider,
          'user_id' => $user->id,
          'oauth_token' => $social->token,
        ]);
        dd(DB::getQueryLog());
      }else{
        Log::debug("Social Account exists.");
      }
      //dd($userObj);

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
