<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Member;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // same form
    }

    public function login(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'password'  => 'required',
        ]);

        $input = $request->member_id;
        $password = $request->password;

        $memberLoggedIn = false;
        $userLoggedIn = false;

        // 1️⃣ Try MEMBER login
        $member = Member::where('member_id', $input)
                        ->orWhere('phone', $input)
                        ->first();

        if ($member && Hash::check($password, $member->password)) {
            Session::put('member_id', $member->member_id);
            Session::put('member_name', $member->name);
            $memberLoggedIn = true;
        }

        // 2️⃣ Try APP USER login
        $appUser = DB::table('app_users')->where('phone_number', $input)->first();

        if ($appUser && $appUser->status != 0 && Hash::check($password, $appUser->password)) {
                 Session::put('app_user_id', $appUser->id);
                Session::put('app_user_name', $appUser->app_u_name ?? '');
                Session::put('app_user_phone', $appUser->phone_number ?? '');
                Session::put('app_user_photo', $appUser->user_pic_img ?? '');
                Session::put('app_user_wallet', $appUser->user_wallet ?? '');
                Session::put('introducer_id', $appUser->introducer_id ?? '');
                Session::put('introducer_phone', $appUser->introducer_phone ?? '');
            $userLoggedIn = true;
        }

        // 🔁 Redirection logic
      /*   if ($memberLoggedIn && $userLoggedIn) {
            return redirect()->route('dual.dashboard')->with('success', 'Logged in as both Member and User!');
        }  */
        if ($memberLoggedIn) {
            return redirect()->route('admin.dashboard')->with('success', 'Member login successful.');
        } elseif ($userLoggedIn) {
            return redirect()->route('user.dashboard')->with('success', 'App user login successful.');
        } else {
            return back()->with('error', 'Invalid credentials or inactive account.');
        }
    }

    // 🧱 Admin logout (only clears admin/member session)
    public function adminLogout()
    {
        Session::forget('member_id');
        Session::forget('member_name');

        return redirect()->route('login.form')->with('success', 'Admin logged out successfully (User still active).');
    }

    // 🧱 App User logout (only clears app user session)
    public function userLogout()
    {
        Session::forget([
            'app_user_id',
            'app_user_name',
            'app_user_phone',
            'app_user_photo',
            'app_user_wallet',
            'introducer_id',
            'introducer_phone'
        ]);

        return redirect()->route('login.form')->with('success', 'User logged out successfully (Admin still active).');
    }

    // 🧱 Full logout (optional — clears everything)
    public function logoutAll()
    {
        Session::flush();
        return redirect()->route('login.form')->with('success', 'Fully logged out.');
    }
}
