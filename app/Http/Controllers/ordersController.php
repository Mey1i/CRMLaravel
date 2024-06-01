<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ordersRequest;
use App\Models\Orders;
use App\Models\Clients;
use App\Models\Brands;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings;
class ordersController extends Controller
{
    public function send(ordersRequest $post){

        $con = new Orders;

        $con->user_id = Auth::id();
        $con->product_id = $post->product_id;
        $con->client_id = $post->client_id;
        $con->quantity = $post->quantity;
        $con->accept = 0;

        $con->save();

        return redirect()->route('orders')->with(['message' => 'Order has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request){

        $user = Auth::user();
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }

        $proddata = Products::join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('suppliers','products.supplier_id','=','suppliers.id')
            ->select('*','products.id as prodid','products.name as produkt')
            ->orderBy('prodid','desc')
            ->get();
        $cdata = Clients::orderBy('id','asc')->get();
        $say = Orders::count();
        $csay = Clients::count();
        $psay = Products::count();

        $search = $request->input('search', '');


        if ($search !== "") {
            $data = Orders::join('clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                '*',
                'orders.id as oid',
                'products.name as produkt',
                'products.quantity as number',
                'clients.name as ad',
                'clients.surname as soyad',
                'orders.quantity as quantity',
                'orders.accept',
            )
            ->where('products.name','LIKE',"%$search%")
            ->orWhere('products.quantity','LIKE',"%$search%")
            ->orWhere('clients.name','LIKE',"%$search%")
            ->orWhere('clients.surname','LIKE',"%$search%")
            ->orWhere('orders.quantity','LIKE',"%$search%")
            ->orWhere('brands.brand','LIKE',"%$search%")
            ->orderBy('oid', 'desc')
            ->get();
        } else {
            $data = Orders::join('clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                '*',
                'orders.id as oid',
                'products.name as produkt',
                'products.quantity as number',
                'clients.name as ad',
                'clients.surname as soyad',
                'orders.quantity as quantity',
                'orders.accept',
            )            
            ->orderBy('oid', 'desc')
            ->get();
        }

        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No orders found with the given search query' : null;


        return view('orders',[
            'data'=>$data,
            'say'=>$say,
            'csay'=>$csay,
            'psay'=>$psay,
            'cdata'=>$cdata,
            'proddata'=>$proddata,
            'userMenu' => $userMenu,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'settings'=>$settings,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function delete($id){
        $data = Orders::find($id)->delete();
        return redirect()->route('orders')->with(['message' => 'Order has been deleted successfully!', 'message_type' => 'success']);
    }

    public function edit(Request $request,$id)
    {
        $user = Auth::user();
        $settings = Settings::first();

                
        $proddata = Products::join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('suppliers','products.supplier_id','=','suppliers.id')
            ->select('*','products.id as prodid','products.name as produkt')
            ->orderBy('prodid','desc')
            ->get();
        $cdata = Clients::orderBy('id','asc')->get();
        $say = Orders::count();
        $csay = Clients::count();
        $psay = Products::count();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
        $edit = Orders::find($id);
    
        if (!$edit) {
            return redirect()->route('orders')->with(['message' => 'Order not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('orders');
        }

        $search = $request->input('search', '');


        if ($search !== "") {
            $data = Orders::join('clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                '*',
                'orders.id as oid',
                'products.name as produkt',
                'products.quantity as number',
                'clients.name as ad',
                'clients.surname as soyad',
                'orders.quantity as quantity',
                'orders.accept',
            )
            ->where('products.name','LIKE',"%$search%")
            ->orWhere('products.quantity','LIKE',"%$search%")
            ->orWhere('clients.name','LIKE',"%$search%")
            ->orWhere('clients.surname','LIKE',"%$search%")
            ->orWhere('orders.quantity','LIKE',"%$search%")
            ->orWhere('brands.brand','LIKE',"%$search%")
            ->orderBy('oid', 'desc')
            ->get();
        } else {
            $data = Orders::join('clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                '*',
                'orders.id as oid',
                'products.name as produkt',
                'products.quantity as number',
                'clients.name as ad',
                'clients.surname as soyad',
                'orders.quantity as quantity',
                'orders.accept',
            )            
            ->orderBy('oid', 'desc')
            ->get();
        }

        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No orders found with the given search query' : null; 
    
        return view('orders', [
            'data' => $data,
            'say' => $say,
            'csay'=>$csay,
            'psay'=>$psay,
            'edit' => $edit,
            'cdata' => $cdata,
            'proddata' => $proddata,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'userMenu' => $userMenu,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'settings'=>$settings,
        ]);
    }

    public function update(ordersRequest $request, $id)
    {
        $yoxla = Orders::where('quantity', '=', $request->quantity)
            ->where('id', '!=', $request->id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Orders::find($request->id);
    
            $con->product_id = $request->product_id;
            $con->client_id = $request->client_id;
            $con->quantity = $request->quantity;
    
            $con->save();
    
            return redirect()->route('orders')->with(['message' => 'Order has been updated successfully!', 'message_type' => 'success']);
        }
    }

    public function accept($id)
    {
        $order = Orders::find($id);
        $product = Products::find($order->product_id);

        if($order->quantity <= $product->quantity)
        {
            $conclusion = $product->quantity - $order->quantity;
            $order->accept = 1;
            $order->save();

            $product->quantity = $conclusion;
            $product->save();

            return redirect()->route('orders')->with(['message' => 'Order has been accepted successfully!', 'message_type' => 'success']);
        }

        return redirect()->route('orders')->with(['message' => 'You can accept this order!', 'message_type' => 'danger']);
    }


    public function cancel($id)
{
    $order = Orders::find($id);
    
    if ($order->accept == 1) {
        $product = Products::find($order->product_id);

        $product->quantity += $order->quantity;
        $product->save();

        $order->accept = 0; 
        $order->save();

        return redirect()->route('orders')->with(['message' => 'Order has been canceled successfully!', 'message_type' => 'success']);
    }

    return redirect()->route('orders')->with(['message' => 'Order not found', 'message_type' => 'danger']);
}

public function delete_selected_orders(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No orders selected for deletion!', 'message_type' => 'danger']);
    }

    Orders::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected orders have been deleted successfully!', 'message_type' => 'success']);
}


public function export_orders(){return Excel::download(new OrdersExport,'orders.xlsx');}

}
