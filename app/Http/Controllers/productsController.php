<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\productsRequest;
use App\Models\Products;
use App\Models\Brands;
use App\Models\Orders;
use App\Models\Suppliers;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class productsController extends Controller
{
    public function send(productsRequest $post){

        $post->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ]);

        $con = new Products;

        if ($post->file('image')) 
        {
            $file = time().'.'.$post->image->extension();
            $post->image->storeAs('public/uploads/products', $file);
            $con->image = 'storage/uploads/products/'.$file;
        }

        $con->user_id = Auth::id();
        $con->supplier_id = $post->supplier_id;
        $con->brand_id = $post->brand_id;
        $con->name = $post->name;
        $con->purchase = $post->purchase;
        $con->sale = $post->sale;
        $con->quantity = $post->quantity;

        $con->save();

        return redirect()->route('products')->with(['message' => 'Product has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request){
        $user = Auth::user();
        $data = Products::join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('suppliers','products.supplier_id','=','suppliers.id')
            ->select('*','products.id as pid','products.name as produkt','products.image as imagee')
            ->orderBy('products.id','desc')
            ->get();
    
        $bdata = Brands::orderBy('id','asc')->get();
        $supdata = Suppliers::orderBy('id','asc')->get();
        $say = Products::count();
        $bsay = Brands::count();
        $settings = Settings::first();
        $osay = Orders::count();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Products::join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('suppliers','products.supplier_id','=','suppliers.id')
            ->select('*','products.id as pid','products.name as produkt','products.image as imagee')
            ->where('suppliers.name','LIKE',"%$search%")
            ->orWhere('brands.brand','LIKE',"%$search%")
            ->orWhere('products.name','LIKE',"%$search%")
            ->orWhere('products.purchase','LIKE',"%$search%")
            ->orWhere('products.sale','LIKE',"%$search%")
            ->orWhere('products.quantity','LIKE',"%$search%")
            ->orderBy('products.id','desc')
            ->get();
        } else {
            $data = Products::join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('suppliers','products.supplier_id','=','suppliers.id')
            ->select('*','products.id as pid','products.name as produkt','products.image as imagee')
            ->orderBy('products.id','desc')
            ->get();
        }
    
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No products found with the given search query' : null;

        return view('products',[
            'data'=>$data,
            'say'=>$say,
            'osay'=>$osay,
            'bsay'=>$bsay,
            'bdata'=>$bdata,
            'supdata'=>$supdata,
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

    public function delete($id){
        $bdata = Brands::orderBy('id','asc')->get();
        $supdata = Suppliers::orderBy('id','asc')->get();
        $data = Products::find($id)->delete();
        return redirect()->route('products')->with(['message' => 'Product has been deleted successfully!', 'message_type' => 'success']);
    }

    public function edit(Request $request,$id)
    {
        $user = Auth::user();
            $settings = Settings::first();
    
        $bdata = Brands::orderBy('id','asc')->get();
        $supdata = Suppliers::orderBy('id','asc')->get();
        $say = Products::count();
        $bsay = Brands::count();
        $osay = Orders::count();
        $edit = Products::find($id);
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
        if (!$edit) {
            return redirect()->route('products')->with(['message' => 'Product not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('products');
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Products::join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('suppliers','products.supplier_id','=','suppliers.id')
            ->select('*','products.id as pid','products.name as produkt','products.image as imagee')
            ->where('suppliers.name','LIKE',"%$search%")
            ->orWhere('brands.brand','LIKE',"%$search%")
            ->orWhere('products.name','LIKE',"%$search%")
            ->orWhere('products.purchase','LIKE',"%$search%")
            ->orWhere('products.sale','LIKE',"%$search%")
            ->orWhere('products.quantity','LIKE',"%$search%")
            ->orderBy('products.id','desc')
            ->get();
        } else {
            $data = Products::join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('suppliers','products.supplier_id','=','suppliers.id')
            ->select('*','products.id as pid','products.name as produkt','products.image as imagee')
            ->orderBy('products.id','desc')
            ->get();
        }
    
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No products found with the given search query' : null;
    
        return view('products', [
            'data' => $data,
            'say'=>$say,
            'osay'=>$osay,
            'bsay'=>$bsay,
            'edit' => $edit,
            'bdata' => $bdata,
            'supdata' => $supdata,
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
    
    public function update(productsRequest $post, $id)
    {
        $yoxla = Products::where('name', '=', $post->name)
            ->where('id', '!=', $id)
            ->orWhere('purchase', '=', $post->purchase)
            ->where('id', '!=', $id)
            ->orWhere('sale', '=', $post->sale)
            ->where('id', '!=', $id)
            ->orWhere('quantity', '=', $post->quantity)
            ->where('id', '!=', $id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Products::find($id);
    
            $con->supplier_id = $post->supplier_id;
            $con->brand_id = $post->brand_id;
            $con->name = $post->name;
            $con->purchase = $post->purchase;
            $con->sale = $post->sale;
            $con->quantity = $post->quantity;
    
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
    
            return redirect()->route('products')->with(['message' => 'Product has been updated successfully!', 'message_type' => 'success']);
        } 
    }


    public function delete_selected_products(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No products selected for deletion!', 'message_type' => 'danger']);
    }

    Products::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected products have been deleted successfully!', 'message_type' => 'success']);
}
    

public function export_products(){return Excel::download(new ProductsExport,'products.xlsx');}
    
}
