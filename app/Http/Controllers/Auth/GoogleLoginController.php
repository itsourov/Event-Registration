<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    public function googleCallback()
    {


        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->withTrashed()->first();
            if (!$user) {


                $password = Str::random(10);
                $new_user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt($password),
                ]);

                Auth::login($new_user);
                Notification::make()
                    ->title("You are now logged in!")
                    ->success()
                    ->send();
                if ($googleUser['verified_email']) {
                    $new_user->markEmailAsVerified();
                }
//                event(new NewUserRegistered($new_user,$password));

                return redirect()->route('home');
            } else {
                if ($user->deleted_at) {
                    $user->restore();
                }
                Auth::login($user);
//                $user->update(['token' => $googleUser->token]);
                if ($googleUser['verified_email']) {
                    $user->markEmailAsVerified();
                }
                Notification::make()
                    ->title("You are now logged in!")
                    ->success()
                    ->send();
                return redirect()->intended(route('home'));
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title("There was an error while logging in.")
                ->body($th->getMessage())
                ->danger()
                ->send();
            return redirect()->intended(route('home')) ;

        }
    }
}
