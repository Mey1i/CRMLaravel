<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BrandsRequest;
use App\Models\Brands;
use App\Models\Products;
use App\Models\Orders;
use App\Models\Settings;
use App\Exports\BrandsExport;
use Maatwebsite\Excel\Facades\Excel; // Import the Excel class




class brandsController extends Controller
{
    public function send(BrandsRequest $post)
    {
        // Check if the brand already exists
       $existingBrand = Brands::where('brand', '=', $post->brand)->first();

        if ($existingBrand) {
            return redirect()->route('brands')->with(['message' => 'Brand is already in the database!', 'message_type' => 'danger']);
        }
        // Validate the image
        $post->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ]);
    
        
        // Create a new Brands instance
        $con = new Brands;
    
        // Check if the image file is present and store it
        if ($post->file('image')) 
        {
            $file = time().'.'.$post->image->extension();
            $post->image->storeAs('public/uploads/brands', $file);
            $con->image = 'storage/uploads/brands/'.$file;
        }
    
        // Assign brand name and save the record
        $con->user_id = Auth::id();
        $con->brand = $post->brand;
        $con->save();
    
        // Redirect with success message
        return redirect()->route('brands')->with(['message' => 'Brand has been added successfully!', 'message_type' => 'success']);
    }
    

    public function list(Request $request)
    {
        $user = Auth::user();
        $say = Brands::count();
        $psay = Products::count();
        $osay = Orders::count();
        $settings = Settings::first();
    
        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Brands::where('brand', 'LIKE', "%$search%")->orderBy('id', 'desc')->get();
        } else {
            $data = Brands::orderBy('id', 'desc')->get();
        }
    
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
    
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No brands found with the given search query' : null;
    
        return view('brands', [
            'data' => $data,
            'say' => $say,
            'psay' => $psay,
            'osay' => $osay,
            'userMenu' => $userMenu,
            'user' => $user,
            'image' => $user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'settings' => $settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }
    

    public function delete($id){
        $data = Brands::find($id)->delete();
        return redirect()->route('brands')->with(['message' => 'Brand has been deleted successfully!', 'message_type' => 'success']);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
    
        // Fetch all brands for the listing
    
        $settings = Settings::first();
        $say = Brands::count();
        $psay = Products::count();
    
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
    
        $osay = Orders::count();
        $edit = Brands::find($id);
    
        if (!$edit) {
            return redirect()->route('brands')->with(['message' => 'Brand not found!', 'message_type' => 'danger']);
        }
    
        // Perform search if the search query is present
        $search = $request->input('search', '');
        $notFoundMessage = null;
    
        if ($search !== "") {
            $data = Brands::where('brand', 'LIKE', "%$search%")->orderBy('id', 'desc')->get();
            $notFoundMessage = $data->isEmpty() ? 'No brands found with the given search query' : null;
        }
        else{
            $data = Brands::orderBy('id', 'desc')->get();
        }
    
        // Check if cancel button is clicked
        if ($request->has('cancel_clicked')) {
            return redirect()->route('brands');
        }
    
        return view('brands', [
            'data' => $data,
            'say' => $say,
            'osay' => $osay,
            'psay' => $psay,
            'edit' => $edit,
            'user_id' => $user,
            'user' => $user,
            'image' => $user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'userMenu' => $userMenu,
            'settings' => $settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }
    
    
    public function update(BrandsRequest $post)
{
    $yoxla = Brands::where('brand', '=', $post->brand)
    ->where('id', '!=', $post->id)
    ->count();


    if ($yoxla == 0) {
        $con = Brands::find($post->id);

        // Validate and update the brand name
        $post->validate([
            'brand' => 'required|string|max:255',
        ]);

        $con->brand = $post->brand;

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

        return redirect()->route('brands')->with(['message' => 'Brand has been updated successfully!', 'message_type' => 'success']);
}

    
    
}

public function delete_selected_brands(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No brands selected for deletion!', 'message_type' => 'danger']);
    }

    Brands::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected brands have been deleted successfully!', 'message_type' => 'success']);
}


public function export_brands(){return Excel::download(new BrandsExport,'brands.xlsx');}



}