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

  public function redirectToProvider($provider)
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

    //Check if ID and provider exist in the social table.


    //If so then we can log this user into the application

    // If not then this is a registration.  Pass the user object to the registrationController
    dd($user);
    // $user->token;
  }
}
