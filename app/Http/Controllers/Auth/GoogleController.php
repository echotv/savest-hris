<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\SystemLogsModel;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {

            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                return redirect('/login')->with('error', 'Account not found.');
            }

            // Same rule as username/password login: disabled or deleted-employee accounts can't sign in
            if (!$user->is_active || $user->hasDeletedEmployee()) {
                return redirect('/login')->with('error', 'This account has been disabled. Please contact the administrator.');
            }

            // Login user
            Auth::login($user);

            // Reload user with relationships
            $authUser = Auth::user()->load('userType.privileges');

            // Get privileges
            // Load privileges via relationship
$privileges = $authUser->userType
    ? $authUser->userType->privileges->pluck('privilege')->toArray()
    : [];

// 🚨 check if privileges is empty
if (empty($privileges)) {
    Auth::logout(); // optional but recommended
    return redirect('/login')->with('error', 'Unauthorized Account!');
}

            // Store session data
            session([
                'user_id'     => $authUser->id,
                'username'    => $authUser->username,
                'ut_id'       => $authUser->ut_id,
                'permissions' => $privileges,
            ]);

            SystemLogsModel::addLog(
                'Logged in via Google',
                json_encode([]),
                $authUser->id
            );

            return redirect("system/dashboard");

        } catch (\Exception $e) {

            return redirect('/login')->with('error', 'Google login failed!');
        }
    }
}
