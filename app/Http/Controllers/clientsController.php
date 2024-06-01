<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\clientsRequest;

use App\Models\Clients;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;
use App\Models\Products;
use App\Models\Orders;
use App\Exports\ClientsExport;
use Maatwebsite\Excel\Facades\Excel;



class clientsController extends Controller
{
    public function send(clientsRequest $post){
        $user_id = Auth::id();
        $clientTelephone = $post->telephone;
        $clientEmail = $post->email;
    
        $existingClientByTelephone = Clients::where('telephone', $clientTelephone)->first();
        $existingClientByEmail = Clients::where('email', $clientEmail)->first();
    
        if ($existingClientByEmail && $existingClientByTelephone) {
            return redirect()->route('clients')->with(['message' => 'This telephone and email is already entered into the database!', 'message_type' => 'danger']);
        }
    
        if ($existingClientByEmail) {
            return redirect()->route('clients')->with(['message' => 'This email is already entered into the database!', 'message_type' => 'danger']);
        }
    
        if ($existingClientByTelephone) {
            return redirect()->route('clients')->with(['message' => 'This telephone  is already entered into the database!', 'message_type' => 'danger']);
        }

        $post->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ]);

        $con = new Clients;

        if ($post->file('image')) {
            $file = time().'.'.$post->image->extension();
            $post->image->storeAs('public/uploads/clients', $file);
            $con->image = 'storage/uploads/clients/'.$file;
        }
    
        $con->user_id = Auth::id();
        $con->name = $post->name;
        $con->surname = $post->surname;
        $con->email = $post->email;
        $con->telephone = $post->telephone;
        $con->company = $post->company;

        $con->save();

        return redirect()->route('clients')->with(['message' => 'Client has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request){
        // Get the authenticated user instance
        $user = Auth::user();
    
        $data = Clients::orderBy('id','desc')->get();
        $say = Clients::count();
        $psay = Products::count();
        $osay = Orders::count();
        $settings = Settings::first();

        $search = $request->input('search', '');

        if ($search !== "")
        {
            $data = Clients::where('name', 'LIKE', "%$search%")
            ->orWhere('surname', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->orWhere('telephone', 'LIKE', "%$search%")
            ->orWhere('company', 'LIKE', "%$search%")
            ->orderBy('id', 'desc')
            ->get();
        }
        else
        {
            $data = Clients::orderBy('id','desc')->get();
        }
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No clients found with the given search query' : null;

        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
    


        return view('clients', [
            'data' => $data,
            'say' => $say,
            'psay' => $psay,
            'osay' => $osay,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'userMenu' => $userMenu,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'settings'=>$settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }
    

    public function delete($id){
        $data = Clients::find($id)->delete();
        return redirect()->route('clients')->with(['message' => 'Client has been deleted successfully!', 'message_type' => 'success']);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $say = Clients::count();
        $settings = Settings::first();
        $psay = Products::count();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
        $osay = Orders::count();
        $edit = Clients::find($id);
        $search = $request->input('search', '');

        if ($search !== "")
        {
            $data = Clients::where('name', 'LIKE', "%$search%")
            ->orWhere('surname', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->orWhere('telephone', 'LIKE', "%$search%")
            ->orWhere('company', 'LIKE', "%$search%")
            ->orderBy('id', 'desc')
            ->get();
        }
        else
        {
            $data = Clients::orderBy('id','desc')->get();
        }
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No clients found with the given search query' : null;

    
        if (!$edit) {
            return redirect()->route('clients')->with(['message' => 'Client not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('clients');
        }
    
        return view('clients', [
            'data' => $data,
            'say' => $say,
            'edit' => $edit,
            'psay' =>$psay,
            'osay' =>$osay,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'userMenu' => $userMenu,
            'surname' => $user->surname,
            'user_id'=>$user,
            'settings'=>$settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function update(clientsRequest $request, $id)
    {
        $user_id = Auth::id();
    
        $yoxla = Clients::where('name', '=', $request->name)
            ->where('id', '!=', $post->id)
            ->orWhere('surname',$request->surname)
            ->where('id', '!=', $post->id)
            ->orWhere('email',$request->email)
            ->where('id', '!=', $post->id)
            ->orWhere('telephone',$request->telephone)
            ->where('id', '!=', $post->id)
            ->orWhere('company', $request->company)
            ->where('id', '!=', $id)
            ->count();
    
        if ($yoxla == 0) {
                $con = Clients::find($id);
        
                // Update client information
                $con->name = $request->name;
                $con->surname = $request->surname;
                $con->email = $request->email;
                $con->telephone = $request->telephone;
                $con->company = $request->company;
        
                // Check if a new image is uploaded and update it
                if ($request->file('image')) {
                    $request->validate([
                        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
                    ]);
        
                    $file = time().'.'.$request->image->extension();
                    $request->image->storeAs('public/uploads/brands', $file);
                    $con->image = 'storage/uploads/brands/'.$file;
                }
        
                $con->save();
        
                return redirect()->route('clients')->with(['message' => 'Client has been updated successfully!', 'message_type' => 'success']);
            } 
        else {
            return redirect()->back()->withErrors(['client' => 'Client information conflicts with existing records.']);
        }
    }
    
    public function delete_selected_clients(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No clients selected for deletion!', 'message_type' => 'danger']);
    }

    Clients::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected clients have been deleted successfully!', 'message_type' => 'success']);
}


public function export_clients(){return Excel::download(new ClientsExport,'clients.xlsx');}
}
