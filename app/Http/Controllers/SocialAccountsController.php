<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Log;
use DB;
use Request;
use View;
use Session;
use Input;
use App\Models\User;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountsController extends Controller
{
    public function getIndex($provider)
    {
        $scopes=[];
        switch ($provider) {
            case "facebook":
                $scopes = ['email','publish_actions'];
                break;
            case "google":
                break;
            default:
                return Redirect::home()->withError('Unknown provider ' . $provider);
        }

        if (!empty($scopes)) {
            return Socialite::with($provider)->scopes($scopes)->redirect();
        }
        return Socialite::with($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        if (!Request::has('code'))
        {
            return Redirect::to('/signup');
        }

        try
        {
            $social = Socialite::with($provider)->user();
        } catch (ClientException $c) {
            Log::error($c);
            abort(400, "Something went wrong. Please try again.");
        }

        if (Auth::check())
        {
            $SA = $this->createSocialAccount(Auth::id(), $social, $provider);
            return Redirect::back();
        }

        // Check if ID and provider exist in the socialAccount model.
       $SA = SocialAccount::where('provider_uid', '=', $social->getId())->first();
       if ($SA)
       {
            // We know who this user is so log them in.
            Auth::loginUsingId($SA->user_id);
            return Redirect::home();
        }

        // We do not know who this is so let's register them.
        /*
         * We are storing social values in a session here rather than a private class variable because the
         * socialRegister form will post to SocialReigster (new instance of this class) to complete the signup process.
         */
        Session::put('social', $social);
        Session::put('provider', $provider);

        if (empty($social->getEmail()))
        {
            return View::make('auth.socialRegister')->withProvider(ucfirst($provider));
        }
        return $this->socialRegistration();
    }


  /**
   * This function can either be called from handleProviderCallback, where $email would be null
   * Or it can be called from a socialRegister form submit on view auth.socialRegister
   * @param Socialite $social
   * @param $email
   * @return Eloquent model
   */
    public function socialRegistration()
    {
        if ((Session::get('social') === null) && (Session::get('provider') === null))
        {
            Log::error('Social session variable is not set');
            abort(400, "Something went wrong. Please try again.");
        }

        $social = Session::get('social');

        $provider = Session::get('provider');
        $email = @Input::get('email') ? : $social->getEmail();

        if (empty($email))
        {
            Log::error('Email is empty');
            abort(400, 'Email is empty');
        }

        $user = User::where('email', '=', $email)->first();
        if (!$user)
        {
           $user = User::create(['email' => $email,
              'firstname' => $social->getName(),
              'username' => $email, // Nickname can be null from the social provider so just use email
              'status' => 1
          ]);
        }

        $SA = $this->createSocialAccount($user->id, $social, $provider);
        Session::forget('social');
        Session::forget('provider');

        // Authenticating and redirecting here because we can't simply return $SA
        // for the reason that this function is called directly from socialRegister route.
        Auth::loginUsingId($SA->user_id);

        return Redirect::home();
    }

    private function createSocialAccount($user_id, $social, $provider)
    {
        $SA = SocialAccount::firstOrNew(['provider_uid' => $social->getId(),
            'provider' => $provider,
            'user_id' => $user_id,
        ]);
        $SA->oauth_token= $social->token;
        $SA->save();

        return $SA;
    }
}
