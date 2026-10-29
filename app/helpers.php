<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('userCan')) {
    function userCan(string $privilege): bool
    {
        $permissions = session('permissions');

        if (is_array($permissions)) {
            return in_array($privilege, $permissions);
        }

        $user = Auth::user();
        if (!$user) return false;

        return \App\Models\UserPrivilegeModel::where('ut_id', $user->ut_id)
            ->where('privilege', $privilege)
            ->exists();
    }
}

if (!function_exists('userBranches')) {
    function userBranches(): ?array
    {
        $user = Auth::user();
        if (!$user) return [];

        $branches = \App\Models\AssignedBranchModel::where('ut_id', $user->ut_id)
            ->pluck('branch_code')
            ->toArray();

        return empty($branches) ? null : $branches;
    }
}
