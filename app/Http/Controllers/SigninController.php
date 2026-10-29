<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SystemLogsModel;

class SigninController extends Controller
{
    public function check_login(Request $request)
{
    $username = $request->input('username');
    $password = $request->input('password');

    $user = User::where('username', $username)->first();

    if (!$user) {
        return redirect('/login')->with('error', 'Incorrect Username!');
    }

    if (!Auth::attempt(['username' => $username, 'password' => $password])) {
        return redirect('/login')->with('error', 'Incorrect Password!');
    }

    // Get authenticated user
    $authUser = Auth::user();

    if (!$authUser->is_active) {
        Auth::logout();
        return redirect('/login')->with('error', 'This account has been disabled. Please contact the administrator.');
    }



        // Load privileges via relationship
$privileges = $authUser->userType
    ? $authUser->userType->privileges->pluck('privilege')->toArray()
    : [];

// 🚨 check if privileges is empty
if (empty($privileges)) {
    Auth::logout(); // optional but recommended
    return redirect('/login')->with('error', 'Unauthorized Account!');
}

    // Store in session
    session([
        'user_id'     => $authUser->id,
        'username'    => $authUser->username,
        'ut_id'       => $authUser->ut_id,
        'permissions' => $privileges,
    ]);

    SystemLogsModel::addLog('Logged in', json_encode([]), $authUser->id);

    return redirect("system/dashboard");
}
    public function logout(Request $request)
    {


        SystemLogsModel::addLog('Logged out', json_encode([]), Auth::id());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
