<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\suppliersRequest;
use App\Models\Suppliers;
use Illuminate\Support\Facades\Auth;
use App\Exports\SuppliersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings;
class suppliersController extends Controller
{
    public function send(suppliersRequest $post){

        $supplierTelephone = $post->telephone;
        $supplierEmail = $post->email;
    
        $existingSupplierByTelephone = Suppliers::where('telephone', $supplierTelephone)->first();
        $existingSupplierByEmail = Suppliers::where('email', $supplierEmail)->first();
    
        if ($existingSupplierByEmail && $existingSupplierByTelephone) {
            return redirect()->route('suppliers')->with(['message' => 'This telephone and email is already entered into the database!', 'message_type' => 'danger']);
        }
    
        if ($existingSupplierByEmail) {
            return redirect()->route('suppliers')->with(['message' => 'This email is already entered into the database!', 'message_type' => 'danger']);
        }
    
        if ($existingSupplierByTelephone) {
            return redirect()->route('suppliers')->with(['message' => 'This telephone  is already entered into the database!', 'message_type' => 'danger']);
        }

        $post->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ]);

        $con = new Suppliers;


        if ($post->file('image')) 
        {
            $file = time().'.'.$post->image->extension();
            $post->image->storeAs('public/uploads/suppliers', $file);
            $con->image = 'storage/uploads/suppliers/'.$file;
        }

        $con->user_id = Auth::id();
        $con->firm = $post->firm;
        $con->name = $post->name;
        $con->surname = $post->surname;
        $con->email = $post->email;
        $con->telephone = $post->telephone;
        $con->address = $post->address;

        $con->save();

        return redirect()->route('suppliers')->with(['message' => 'Supplier has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request){
        $user = Auth::user();
        $say = Suppliers::count();
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Suppliers::where('name', 'LIKE', "%$search%")
            ->orWhere('surname','LIKE',"%$search%")
            ->orWhere('email','LIKE',"%$search%")
            ->orWhere('telephone','LIKE',"%$search%")
            ->orWhere('address','LIKE',"%$search%")
            ->orderBy('id', 'desc')
            ->get();
        } else {
            $data = Suppliers::orderBy('id', 'desc')->get();
        }

        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No suppliers found with the given search query' : null;

    

        return view('suppliers',[
            'data'=>$data,
            'say'=>$say,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'userMenu' => $userMenu,
            'user_id' => $user->id,
            'settings'=>$settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function delete($id){
        $data = Suppliers::find($id)->delete();
        return redirect()->route('suppliers')->with(['message' => 'Supplier has been deleted successfully!', 'message_type' => 'success']);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $say = Suppliers::count();
        $edit = Suppliers::find($id);
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
    
        if (!$edit) {
            return redirect()->route('suppliers')->with(['message' => 'Supplier not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('suppliers');
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Suppliers::where('name', 'LIKE', "%$search%")
            ->orWhere('surname','LIKE',"%$search%")
            ->orWhere('email','LIKE',"%$search%")
            ->orWhere('telephone','LIKE',"%$search%")
            ->orWhere('address','LIKE',"%$search%")
            ->orderBy('id', 'desc')
            ->get();
        } else {
            $data = Suppliers::orderBy('id', 'desc')->get();
        }

        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No suppliers found with the given search query' : null;
    
        return view('suppliers', [
            'data' => $data,
            'say' => $say,
            'edit' => $edit,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'userMenu' => $userMenu,
            'settings'=>$settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function update(suppliersRequest $post)
    {
        $yoxla = Suppliers::where('firm', '=', $post->firm)
            ->where('id', '!=', $post->id)
            ->orwhere('name', '=', $post->name)
            ->where('id', '!=', $post->id)
            ->orwhere('surname', '=', $post->surname)
            ->where('id', '!=', $post->id)
            ->orwhere('email', '=', $post->email)
            ->where('id', '!=', $post->id)
            ->orwhere('telephone', '=', $post->telephone)
            ->where('id', '!=', $post->id)
            ->orwhere('address', '=', $post->address)
            ->where('id', '!=', $post->id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Suppliers::find($post->id);

            
    
            $con->firm = $post->firm;
            $con->name = $post->name;
            $con->surname = $post->surname;
            $con->email = $post->email;
            $con->telephone = $post->telephone;
            $con->address = $post->address;

        // Check if a new image is uploaded and update it
        if ($post->file('image')) {
            $post->validate([
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            ]);

            $file = time().'.'.$post->image->extension();
            $post->image->storeAs('public/uploads/brands', $file);
            $con->image = 'storage/uploads/brands/'.$file;
        }

            $con->save();
    
            return redirect()->route('suppliers')->with(['message' => 'Supplier has been updated successfully!', 'message_type' => 'success']);
        }
    }


    public function delete_selected_suppliers(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No suppliers selected for deletion!', 'message_type' => 'danger']);
    }

    Suppliers::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected suppliers have been deleted successfully!', 'message_type' => 'success']);
}


public function export_suppliers(){return Excel::download(new SuppliersExport,'suppliers.xlsx');}

}
