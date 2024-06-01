<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts;
use App\Models\Settings;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function contactIndex()
    {
        $settings = Settings::first();
        return view('contact', ['settings' => $settings]);
    }
    
    public function contact(Request $request)
    {
        // Define the validation rules
        $rules = [
            'name' => 'required|name',
            'email' => 'required|email',
            'phone' => 'required',
            'title' => 'required',
            'problem' => 'required',
        ];
    
        // Define custom error messages
        $messages = [

        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('contact')->withErrors($validator)->withInput();
        }
        // If validation passes, create a new Contacts instance and save
        $con = new Contacts;

        $con->name = $request->input('name');
        $con->email = $request->input('email');
        $con->phone = $request->input('phone');
        $con->title = $request->input('title');
        $con->problem = $request->input('problem');
        $con->accept = 0;
    
        $con->save();
    
        return redirect()->route('contact')->with(['message' => 'Message has been added successfully!', 'message_type' => 'success']);
    }
}

