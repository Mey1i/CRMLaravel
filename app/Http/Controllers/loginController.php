<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\loginRequest;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function loginIndex(){
        return view('login');
    }

    public function login(loginRequest $request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (!Auth::attempt($credentials)) {
            // Check if the email is correct but the password is wrong
            $userWithEmail = \App\Models\User::where('email', $request->email)->first();
            if ($userWithEmail && !password_verify($request->password, $userWithEmail->password)) {
                return redirect()->back()->with(['message'=>'You entered incorrect e-mail address. Please contact the administrators to resolve the issue with your account.','message_type'=>'info']);
            }

            return redirect()->back()->with(['message'=>'The login or password you entered is incorrect!','message_type'=>'danger']);
        }

        $user = Auth::user();

        if ($user->is_blocked == 1) {
            Auth::logout();
            return redirect()->route('login')->with(['message'=>'Your account is currently blocked. Please contact the administrators to unblock your account.','message_type'=>'info']);
        }

        return redirect()->route('update_profile', ['id' => $user->id]);
    }

    public function logout(){
        Auth::logout(); // Change auth() to Auth::

        return redirect()->route('login');
    }
}

