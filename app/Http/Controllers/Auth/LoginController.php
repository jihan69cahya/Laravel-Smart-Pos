<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response('Username tidak terdaftar', 401);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $routeName = 'dashboard.' . $user['role_name'];
            return response([
                'message' => 'Berhasil Login!',
                'route'   => route($routeName),
            ], 200);
        } else {
            return response('Password Salah', 400);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Berhasil Logout');
    }
}
