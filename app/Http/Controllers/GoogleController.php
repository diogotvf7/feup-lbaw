<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Exceptions\ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class GoogleController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle() {

        $google_user = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $google_user->getId())->first();
        
        // If the user does not exist, create one
        if (!$user) {

            $time = Carbon::now()->timestamp; //Used to generate a random username
            $username = explode(" ", $google_user->getName())[0].$time;

            // Store the provided name, email, and Google ID in the database
            $new_user = User::create([
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'username' => $username,
                'google_id' => $google_user->getId(),
            ]);

            Auth::login($new_user);

        // Otherwise, simply log in with the existing user
        } else {
            Auth::login($user);
        }

        // After login, redirect to homepage
        return redirect()->route('homepage');
    }
}
