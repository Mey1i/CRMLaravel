<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\registerRequest; // You should import the registerRequest class
use Illuminate\Support\Facades\Auth;
class registerController extends Controller

{
    public function registerIndex(){
        return view('register');
    }

    public function register(registerRequest $request){ // Change $post to $request

        $con = new User;

        $con->name = $request->name;
        $con->surname = $request->surname;
        $con->phone = $request->phone;
        $con->email = $request->email;
        $con->is_superuser = 0;
        $con->is_admin = 0;
        $con->is_blocked = 1;
        $con->menu = 0;
        $con->password = Hash::make($request->password);

        $con->save();

        return redirect()->route('register')->with(['message'=>'User has been registered successfully!','message_type'=>'success']);
    }
}
