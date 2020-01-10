<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function doLogout() {
        Auth::logout();
        session()->flash("success-message", "You have logged out.");
        return redirect()->route('home');
    }
    public function doLogin(Request $request) {
        $user = User::find($request->input('user_id'));
        if (!$user) abort(400);
        Auth::login($user);
        session()->flash("success-message", "You have logged in.");
        return Response::json(["redirect" => route('home')], 200);
    }
}
