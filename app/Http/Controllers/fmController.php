<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class fmController extends Controller
{
    public function list(){
        

        $user = Auth::user();


        return view('fm',[
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
        ]);
    }
}
