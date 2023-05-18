<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        // dd('callback working');
        // $user = Socialite::driver('github')->user();
        $user = Socialite::driver('github')->stateless()->user();
        // dd($user);
        // Do something with the user data
        $user = User::firstOrCreate(['email' => $user->email], [
            'name' => $user->name,
            'password' => 'password',
        ]);
        // dd($user);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
