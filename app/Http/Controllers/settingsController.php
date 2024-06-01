<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Settings::first();
        $user = Auth::user();
        return view('settings', compact('settings', 'user'));
    }

    public function updateLogo(Request $request)
    {
        $this->validateLogo($request);

        $settings = $this->getOrCreateSettings();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/uploads/settings');
            $settings->image = str_replace('public/', 'storage/', $imagePath);
        }

        $settings->title = $request->input('title');

        if ($settings->save()) {
            return redirect()->route('settings.edit')->with(['message' => 'Logo updated successfully!', 'message_type' => 'success']);
        } else {
            return redirect()->route('settings.edit')->with(['message' => 'Failed to update logo.', 'message_type' => 'danger']);
        }
    }

    public function updateContact(Request $request)
    {
        $this->validateContact($request);

        $settings = $this->getOrCreateSettings();

        $settings->email = $request->input('email');
        $settings->phone = $request->input('phone');
        $settings->address = $request->input('address');

        if ($settings->save()) {
            return redirect()->route('settings.edit')->with(['message' => 'Contact information updated successfully!', 'message_type' => 'success']);
        } else {
            return redirect()->route('settings.edit')->with(['message' => 'Failed to update contact information.', 'message_type' => 'danger']);
        }
    }

    public function updateFooter(Request $request)
    {
        $this->validateFooter($request);

        $settings = $this->getOrCreateSettings();

        $settings->footer = $request->input('footer');

        if ($settings->save()) {
            return redirect()->route('settings.edit')->with(['message' => 'Footer updated successfully!', 'message_type' => 'success']);
        } else {
            return redirect()->route('settings.edit')->with(['message' => 'Failed to update footer.', 'message_type' => 'danger']);
        }
    }

    private function validateLogo(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string',
        ]);
    }

    private function validateContact(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);
    }

    private function validateFooter(Request $request)
    {
        $request->validate([
            'footer' => 'required|string',
        ]);
    }

    private function getOrCreateSettings()
    {
        $settings = Settings::first();

        if (!$settings) {
            // If there are no settings yet, create a new instance
            $settings = new Settings();
        }

        return $settings;
    }
}
