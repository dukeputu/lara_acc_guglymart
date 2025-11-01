<?php
namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class MemberLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'password'  => 'required',
        ]);

        $input = $request->member_id;

        // 🔍 Try to find user by either member_id or phone
        $member = Member::where('member_id', $input)
            ->orWhere('phone', $input)
            ->first();

        if ($member && Hash::check($request->password, $member->password)) {
            Session::put('member_id', $member->member_id);
            Session::put('member_name', $member->name); // optional
                                                        // return redirect('/dashboard');
                                                        // redirect to named route instead of hardcoded path
            return redirect()->route('admin.dashboard');
        }
        return back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        Session::forget('member_id');

        return redirect()->route('login.form')->with('error', 'Logged out successfully');
    }
}
