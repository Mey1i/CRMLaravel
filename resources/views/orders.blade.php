@extends('layouts.app')

@section('title') Orders @endsection

@section('content')
@php
    $user = Auth::user();
    try {
            $secim = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $secim = [];
        }
    $requiredValues = ['6-1'];

    $isSuperadmin = $user->is_superuser === 1;

    // Check if the required values are missing and the user is not a superadmin
    $missingValues = array_diff($requiredValues, $secim);
    if (!empty($missingValues) && !$isSuperadmin) {
        echo '<script>window.history.back();</script>';
    }
@endphp


<div class="kard-container">
        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Orders
                        </h3>
                        <h1>
                            {{$say}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                    <i class="mdi mdi-credit-card"></i>
                        
                    </span>
                </div>
            </div>
        </div>

        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Products
                        </h3>
                        <h1>
                            {{$psay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                    <i class="mdi mdi-cart-outline"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Clients
                        </h3>
                        <h1>
                            {{$csay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                    <i class="mdi mdi-account-multiple-outline"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>




@if($errors->any())
    <div class="custom-message alert danger">
        <span class="message-icon">❌</span>
        <span class="message-content">
            @foreach($errors->all() as $sehv)
                {{ $sehv }}<br>
            @endforeach
        </span>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    {{-- Include Bootstrap JS if you haven't already --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endif


@if(session('message'))
    <div class="custom-message alert {{ session('message_type') }}">
        <span class="message-icon">
            @if(session('message_type') == 'success') ✔
            @elseif(session('message_type') == 'danger') ❌
            @elseif(session('message_type') == 'info') ℹ️
            @elseif(session('message_type') == 'warning') ⚠️
            @endif
        </span>
        <span class="message-content">{{ session('message') }}</span>
        <button type="button" class="close" onclick="closeCustomMessage(this)">&times;</button>
    </div>

    {{-- Custom JavaScript for closing the message --}}
    <script>
        function closeCustomMessage(button) {
            button.parentElement.style.display = 'none';
        }
    </script>
@endif



@if(in_array('6-2',$userMenu) or Auth::user()->is_superuser == 1) 
@isset($edit)
<form method="post" action="{{ route('update_orders', ['id' => $edit->id]) }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Orders</h4>
            <form class="forms-sample">
                <div class="form-group">
                    <label for="product_id">Product:</label><br>
                    <select class="form-control" id="product_id" name="product_id">
                        <option value="">Choose product</option>
                        @foreach($proddata as $p)
                            <option value="{{ $p->prodid }}" {{ $edit->product_id == $p->prodid ? 'selected' : '' }}>{{ $p->brand }} - {{ $p->produkt }} [{{ $p->quantity }}]</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="client_id">Client:</label><br>
                    <select class="form-control" id="client_id" name="client_id">
                        <option value="">Choose client</option>
                        @foreach($cdata as $c)
                            <option value="{{ $c->id }}" {{ $edit->client_id == $c->id ? 'selected' : '' }}> {{ $c->name }}  {{ $c->surname }} </option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label><br>
                    <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $edit->quantity }}"><br>
                </div>
                <button type="submit" class="btn btn-primary mr-2" title="Update order">Update</button> <a href="{{ route('orders', ['cancel_clicked' => 1]) }}"><button type="button" title="Cancel" class="btn btn-secondary mr-2">Cancel</button></a>
            </form>
        </div>
    </div>
</form>

@else
    <form method="post" action="{{ route('submit_orders') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Orders</h4>
                <form class="forms-sample">
                    <div class="form-group">
                        <label for="product_id">Product:</label><br>
                        <select class="form-control" id="product_id" name="product_id">
                            <option value="">Choose product</option>
                            @foreach($proddata as $p)
                                <option value="{{ $p->prodid }}">{{ $p->brand }} - {{ $p->produkt }} [{{ $p->quantity }}]</option>
                            @endforeach
                        </select><br>
                    </div>
                    <div class="form-group">
                        <label for="client_id">Client:</label><br>
                        <select class="form-control" id="client_id" name="client_id">
                            <option value="">Choose client</option>
                            @foreach($cdata as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} {{ $c->surname }}</option>
                            @endforeach
                        </select><br>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label><br>
                        <input type="text" class="form-control" id="quantity" name="quantity"><br>
                    </div>
                    <button type="submit" title="Send order" class="btn btn-primary mr-2">Submit</button>
                </form>
            </div>
        </div>
    </form>
@endisset
@endif
<br><br>


@if(isset($notFoundMessage))
    <div class="alert alert-danger">
        <span class="message-icon">❌</span>
        <span class="message-content">{{ $notFoundMessage }}</span>
        <button type="button" class="close" onclick="closeCustomMessage(this)">&times;</button>
    </div>

    <script>
        function closeCustomMessage(button) {
            button.parentElement.style.display = 'none';
        }
    </script>

@else
<div class="card-body">
    <h4 class="card-title">Orders table</h4>
    <form action = "{{ route('delete_selected_orders') }}" >
    <table class="table table-dark">
        <thead>
            <tr>
            @if(in_array('6-2',$userMenu) or Auth::user()->is_superuser == 1)     
            <th>Checkbox</th>
            @endif
                <th> №</th>
                <th> Brand</th>
                <th> Product </th>
                <th> Number of products </th>
                <th> Client </th>
                <th> Quantity </th>
                @if(in_array('6-2',$userMenu) or Auth::user()->is_superuser == 1)     
                <th> Actions
                <button class="btn btn-danger" title="Delete selected orders"><i class="mdi mdi-delete"></i></button>
                <a href="{{route('export_orders')}}" class="btn btn-success" title="Export Orders">
                        <i class="mdi mdi-file-excel"></i>
                </a>
            </th>
            @endif
            </tr>
        </thead>
        @foreach($data as $i=>$info)
        <tbody>
            <tr>
            @if (in_array('6-2',$userMenu) or Auth::user()->is_superuser == 1)    
            <td>
            <input type="checkbox" name="secim[]" value="{{ $info->oid }}"></td>
            @endif
                <td>{{$i+=1}} </td>
                <td>{{$info->brand}} </td>
                <td>{{$info->produkt}} </td>
                <td>{{$info->number}} </td>
                <td>{{$info->ad}} {{$info->soyad}} </td>
                <td>{{$info->quantity}} </td>
                
                @if(in_array('6-2',$userMenu) or Auth::user()->is_superuser == 1) 
                    <td>
                    @if($info->accept == 0)
                        <a href="{{ route('delete_orders', $info->oid) }}"><button type="button" title="Delete order" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>
                        <a href="{{ route('edit_orders', $info->oid) }}"><button type="button" title="Edit order" class="btn btn-warning btn-rounded btn-fw"><i class="mdi mdi-grease-pencil"></i></button></a>
                        <a href="{{ route('accept_orders', $info->oid) }}"><button type="button" title="Accept order" class="btn btn-success btn-rounded btn-fw"><i class="mdi mdi-check"></i></button></a>
                        @else
                        <a href="{{ route('cancel_orders', $info->oid) }}"><button type="button" title="Cancel order" class="btn btn-secondary btn-rounded btn-fw"><i class="mdi mdi-close"></i></button></a>
                        @endif
                    </td>      
                @endif
            </tr>
        </tbody>
        @endforeach
    </table>
    </form>
</div>
@endif
@endsection
