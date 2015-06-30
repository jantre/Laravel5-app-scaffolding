<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Laravel\Socialite\Facades\Socialite;



class ProviderController extends Controller
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

    $user = Socialite::with($provider)->user();
    //$user->debug = "YOu have logged in with $provider";
    //Check if ID and provider exist in the social table.
  dd($user->getId());

    //If so then we can log this user into the application

    // If not then this is a registration.  Pass the user object to the registrationController
    dd($user);
    // $user->token;
// All Providers

    $user->getNickname();
    $user->getName();
    $user->getEmail();
    $user->getAvatar();
  }
}
