<?php

// File: app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Settings;


class ProfileController extends Controller
{
    public function update_profile(Request $request, $id = null)
    {
        $user = User::find(Auth::id());
        $settings = Settings::first();

        $data = [
            'user' => $user,
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_photo' => $user->image,
            'user_id' => $user->id,
            'settings'=>$settings,
        ];

        if ($request->isMethod('post')) {
            $request->validate([
                'password' => 'nullable|string|min:6',
                'confirm_password' => 'required_with:name,surname,email,phone,password|nullable|string',
                'name' => 'nullable|string|max:255',
                'surname' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'new_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->filled('confirm_password')) {
                if (!Hash::check($request->input('confirm_password'), $user->password)) {
                    return redirect()->back()->with(['message' => 'Password is incorrect. Please enter your current password into the confirm password field!', 'message_type' => 'error']);
                }

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->input('password'));
                }
            }

            if ($request->hasFile('new_photo')) {
                $request->validate([
                    'new_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $file = time().'.'.$request->file('new_photo')->extension();
                $request->file('new_photo')->storeAs('public/uploads/profile', $file);
                $user->image = 'storage/uploads/profile/'.$file;
            }

            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');

            if ($user->save()) 
            {
                return redirect()->back()->with(['message' => 'Profile has been updated successfully!', 'message_type' => 'success']);
            } 
            else {
                return redirect()->back()->with(['message' => 'Failed to update profile.', 'message_type' => 'danger']);
            }
        }

        return view('profile', $data);
    }

    public function updatepassword(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'password' => 'required|string',
            'newpassword' => 'required|string',
            'Tnewpassword' => 'required|string|same:newpassword',
        ]);

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->with(['message' => 'Current password is incorrect!', 'message_type' => 'error']);
        }

        $user->password = Hash::make($request->input('newpassword'));
        $user->save();

        return redirect()->back()->with(['message' => 'Password has been updated successfully!', 'message_type' => 'success']);
    }
}
