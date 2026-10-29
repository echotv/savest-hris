<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $title = "MY PROFILE - SavestHRIS";
        $menu = "Settings";

        return view('system.account_profile', compact('user', 'title', 'menu'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        SystemLogsModel::addLog('Update Own Password', 'Own account password was changed.', $user->id);

        return back()->with('success', 'Password updated successfully');
    }

    public function updatePicture(Request $request)
    {
        $uploadedPath = $request->input('uploaded_profile_picture'); // temp/xxxx.jpg

        if (!$uploadedPath || !Storage::disk('public')->exists($uploadedPath)) {
            return back()->withErrors(['profile_picture' => 'No valid profile picture uploaded.']);
        }

        $user = Auth::user();

        // Move file from temp/ → profile_pictures/
        $newPath = str_replace('temp/', 'profile_pictures/', $uploadedPath);
        Storage::disk('public')->move($uploadedPath, $newPath);

        $user->update([
            'profile_picture' => $newPath,
        ]);

        SystemLogsModel::addLog('Updated Profile Picture', json_encode([['profile_picture' => $newPath]]), $user->id);

        if ($user->employee_id && EmployeeModel::where('employee_id', $user->employee_id)->exists()) {
            EmployeeModel::where('employee_id', $user->employee_id)->update([
                'profile_picture' => $newPath,
                'updated_at' => Carbon::now('Asia/Manila'),
                'updated_by_id' => $user->id,
                'updated_byname' => $user->name,
            ]);
        }

        return back()->with('success', 'Profile picture updated successfully');
    }
}
