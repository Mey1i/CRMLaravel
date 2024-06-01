<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class messageController extends Controller
{
    public function messageIndex()
    {
        $user = Auth::user();
        $settings = Settings::first();
        $message = Contacts::orderBy('id','desc')->get();

        return view('message',[
            'message'=>$message,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'settings'=>$settings,]);
    }

    public function delete_message($id)
    {
        $data = Contacts::find($id)->delete();
        return redirect()->route('message')->with(['message' => 'Message has been deleted successfully!', 'message_type' => 'success']);
    }

    public function accept_message($id)
    {
        $message = Contacts::find($id);

        $message->accept = 1; 
        $message->save();

        return redirect()->route('message')->with(['message' => 'Message has been accepted successfully!', 'message_type' => 'success']);
    }

    public function cancel_message($id)
    {
        $message = Contacts::find($id);

        $message->accept = 0; 
        $message->save();
        
        return redirect()->route('message')->with(['message' => 'Message has been canceled successfully!', 'message_type' => 'success']);
    }
}
