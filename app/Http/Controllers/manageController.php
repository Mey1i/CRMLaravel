<?php

namespace App\Http\Controllers;
use App\Models\Brands;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Settings;

use Illuminate\Http\Request;

class manageController extends Controller
{
    public function index(){
        $user = Auth::user();
        $settings = Settings::first();
        $user_list = User::where('id', '!=', $user->id)
        ->orderBy('id', 'desc')
        ->get();

        return view('manage',[
        'user' => $user,
        'image'=>$user->image,
        'name' => $user->name,
        'surname' => $user->surname,
        'user_list'=> $user_list,
    'settings'=>$settings,]);
    }

    public function store(Request $request)
    {
        // Get the selected user ID from the request
        $selectedUserId = $request->input('user_list');
    
        // Ensure a user is selected
        if (!$selectedUserId) {
            return redirect()->back()->with('error', 'Please choose a user.');
        }
    
        // Find the selected user
        $user = User::find($selectedUserId);
    
        // Check if the user is found
        if (!$user) {
            return redirect()->back()->with('error', 'Selected user not found.');
        }
    
        // Get the array from the request
        $secim = $request->input('secim', []);
    
        // Update the selected user's menu settings
        $user->menu = serialize($secim);
        $user->save();
    
        // Redirect back with a success message
        return redirect()->back()->with([
            'message' => 'Settings saved successfully for ' . $user->name . ' ' . $user->surname,

            'message_type' => 'success'
        ]);
        
    }
    
    
}
