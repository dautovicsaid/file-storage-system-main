<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function initGoogleLogin(){
        return Socialite::driver('google')->redirect();
    }

    public function googleLoginCallback() {
        $user = Socialite::driver('google')->user();

        return $this->loginSocialUser($user);
    }

    public function initTwitterLogin(){
        return Socialite::driver('twitter')->redirect();
    }

    public function twitterLoginCallback() {
        $user = Socialite::driver('twitter')->user();
        return $this->loginSocialUser($user);
    }

    public function initFacebookLogin(){
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookLoginCallback() {
        $user = Socialite::driver('facebook')->user();
        return $this->loginSocialUser($user);
    }


    public function loginSocialUser($user){

        $user_exists = User::query()->where('email',$user->getEmail())->count() > 0;

        $existing_user= User::firstOrCreate([
            'email' => $user->getEmail()
        ],[
            'name' => $user->getName(),
            'password' => Hash::make(Str::random(10))
        ]);

        if(!$user_exists) {
            $existing_user->notify(New NewUserNotification($existing_user));
        }

        Auth::login($existing_user,true);
        return redirect()->route('home.index');
    }
}
