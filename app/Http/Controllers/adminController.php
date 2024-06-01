<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;

class adminController extends Controller
{
   public function adminIndex(Request $request)
   {
       $user = Auth::user();
        $settings = Settings::first();
       $users = User::where('id', '!=', $user->id)
                     ->orderBy('id', 'desc')
                     ->get();
   
                     $search = $request->input('search', '');
    
        if ($search !== "") {
            $users = User::where('id', '!=', $user->id)
            ->orWhere('name', 'LIKE', "%$search%")
            ->orWhere('surname','LIKE',"%$search%")
            ->orWhere('phone','LIKE',"%$search%")
            ->orWhere('email','LIKE',"%$search%")
            ->orderBy('id', 'desc')->get();
        } else {
            $users = User::where('id', '!=', $user->id)
            ->orderBy('id', 'desc')
            ->get();
        }               

        $notFoundMessage = ($search !== "" && $users->isEmpty()) ? 'No users found with the given search query' : null;
       return view('admin', [
           'users'   => $users,
           'user'    => $user,
           'image'   => $user->image,
           'name'    => $user->name,
           'surname' => $user->surname,
           'settings'=> $settings,
           'search' => $search,
           'notFoundMessage' => $notFoundMessage,
       ]);
   }

   // Остальные методы без изменений...
   public function edit_user($id)
   {
       $user = Auth::user();
       $edit_user = User::find($id);
       $settings = Settings::first();
       $users = User::where('id', '!=', $user->id)->orderBy('id', 'desc')->get();
   
       if (!$edit_user) {
           return redirect()->route('admin')->with(['message' => 'User not found!', 'message_type' => 'danger']);
       }
   
       return view('admin', [
           'users' => $users,
           'edit_user' => $edit_user,
           'user' => $user,
           'image' => $user->image,
           'name' => $user->name,
           'settings'=>$settings,
           'surname' => $user->surname,
       ]);
   }
   

   public function update_user($id = null, Request $request)
   {
       $user = User::find($id);
       
       if (!$user) {
           return redirect()->route('admin')->with(['message' => 'User not found', 'message_type' => 'danger']);
       }
   
       $request->validate([
           'password' => 'nullable|string',
           'confirm_password' => 'nullable|string',
           'name' => 'nullable|string|max:255',
           'surname' => 'nullable|string|max:255',
           'email' => 'nullable|email|max:255',
           'phone' => 'nullable|string|max:20',
           'new_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
       ]);
   
       // Password handling
       if ($request->filled('confirm_password')) {
           // Validate current password
           if (!Hash::check($request->input('confirm_password'), $user->password)) {
               return redirect()->route('edit_user', ['id' => $id])->with(['message' => 'Password is incorrect. Please enter your current password into the confirm password field!', 'message_type' => 'error']);
           }
   
           // Update password if a new one is provided
           if ($request->filled('password')) {
               $user->password = Hash::make($request->input('password'));
           }
       }
   
       // Photo handling
       if ($request->hasFile('new_photo')) {
        $request->validate([
            'new_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store and update the user's photo
        $file = time() . '.' . $request->file('new_photo')->extension();
        $request->file('new_photo')->storeAs('public/uploads/admin', $file);
        $user->image = 'storage/uploads/admin/' . $file;
    }
   
       // Update user fields
       $user->name = $request->input('name', $user->name);
       $user->surname = $request->input('surname', $user->surname);
       $user->email = $request->input('email', $user->email);
       $user->phone = $request->input('phone', $user->phone);
   
       if ($user->save()) {
           return redirect()->route('admin')->with(['message' => 'Profile has been updated successfully!', 'message_type' => 'success']);
       } else {
           return redirect()->route('admin')->with(['message' => 'Failed to update profile.', 'message_type' => 'danger']);
       }
   }
   
   
   // Остальные методы без изменений...

}
