@extends('layouts.app')

@section('title') Products @endsection

@section ('content')

@php
    $user = Auth::user();
    try {
            $secim = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $secim = [];
        }

    $requiredValues = ['2-1'];

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
                            Products
                        </h3>
                        <h1>
                            {{$say}}
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
                            Brands
                        </h3>
                        <h1>
                            {{$bsay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-format-list-bulleted"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Orders
                        </h3>
                        <h1>
                            {{$osay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-credit-card"></i>
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




<br><br>

@if(in_array('2-2',$userMenu) or Auth::user()->is_superuser == 1)
@isset($edit)
<form method="post" action="{{ route('update_products', $edit->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Products</h4>
            <form class="forms-sample">
            <div class="form-group">
                    <label>Current Photo</label><br>
                    <img style="width:70px; height:60px;" src="{{ url($edit->image) }}"><br>
                </div>
                <div class="form-group">
                    <label>New Photo</label><br>
                    <input type="file" name="image" id="uploadImage" class="file-upload-default">
                    <div class="input-group col-xs-12">
                        <button class="file-upload-browse btn btn-primary" type="button" onclick="document.getElementById('uploadImage').click()">Upload</button>
                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                        <span class="input-group-append"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="supplier_id">Supplier:</label><br>
                    <select class="form-control" id="supplier_id" name="supplier_id">
                        <option value="">Choose supplier</option>
                        @foreach($supdata as $s)
                            <option value="{{ $s->id }}" {{ $edit->supplier_id == $s->id ? 'selected' : '' }}>{{ $s->firm }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="brand_id">Brand:</label><br>
                    <select class="form-control" id="brand_id" name="brand_id">
                        <option value="">Choose brand</option>
                        @foreach($bdata as $b)
                            <option value="{{ $b->id }}" {{ $edit->brand_id == $b->id ? 'selected' : '' }}>{{ $b->brand }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="name">Product's name:</label><br>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $edit->name }}"><br>
                </div>
                <div class="form-group">
                    <label for="purchase">Purchase:</label><br>
                    <input type="text" class="form-control" id="purchase" name="purchase" value="{{ $edit->purchase }}"><br>
                </div>
                <div class="form-group">
                    <label for="sale">Sale:</label><br>
                    <input type="text" class="form-control" id="sale" name="sale" value="{{ $edit->sale }}"><br>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label><br>
                    <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $edit->quantity }}"><br>
                </div>
                <button type="submit" class="btn btn-primary" title="Update product">Update</button> <a href="{{ route('products', ['cancel_clicked' => 1]) }}"><button type="button" title="Cancel" class="btn btn-secondary mr-2">Cancel</button></a>
            </form>
        </div>
    </div>
</form>
@else
<form method="post" action="{{ route('submit_products') }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Products</h4>
            <form class="forms-sample">
            <div class="form-group">
                        <label>Photo</label>
                        <input type="file" name="image" id="uploadImage" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <button class="file-upload-browse btn btn-primary" type="button" onclick="document.getElementById('uploadImage').click()">Upload</button>
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                            </span>
                        </div>
                    </div>
                <div class="form-group">
                    <label for="supplier_id">Supplier:</label><br>
                    <select class="form-control" id="supplier_id" name="supplier_id">
                        <option value="">Choose supplier</option>
                        @foreach($supdata as $s)
                            <option value="{{ $s->id }}">{{ $s->firm }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="brand_id">Brand:</label><br>
                    <select class="form-control" id="brand_id" name="brand_id">
                        <option value="">Choose brand</option>
                        @foreach($bdata as $b)
                            <option value="{{ $b->id }}">{{ $b->brand }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="name">Product's name:</label><br>
                    <input type="text" class="form-control" id="name" name="name"><br>
                </div>
                <div class="form-group">
                    <label for="purchase">Purchase:</label><br>
                    <input type="text" class="form-control" id="purchase" name="purchase"><br>
                </div>
                <div class="form-group">
                    <label for="sale">Sale:</label><br>
                    <input type="text" class="form-control" id="sale" name="sale"><br>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label><br>
                    <input type="text" class="form-control" id="quantity" name="quantity"><br>
                </div>
                <button type="submit" class="btn btn-primary" title="Send product">Submit</button>
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
    <h4 class="card-title">Products table</h4>
    <form action = "{{ route('delete_selected_products') }}" >
    <table class="table table-dark">
        <thead>
            <tr>
            @if(in_array('2-2',$userMenu) or Auth::user()->is_superuser == 1)    
            <th>Checkbox</th>
            @endif
                <th> №</th>
                <th> Photo</th>
                <th> Supplier</th>
                <th> Brand </th>
                <th> Product's name </th>
                <th> Purchase </th>
                <th> Sale </th>
                <th> Quantity </th>
                @if(in_array('2-2',$userMenu) or Auth::user()->is_superuser == 1)    
                <th> Actions
                <button class="btn btn-danger" title="Delete selected products"><i class="mdi mdi-delete"></i></button>
                <a href="{{route('export_products')}}" class="btn btn-success" title="Export Products">
                        <i class="mdi mdi-file-excel"></i>
                </a>
            </th>
            @endif
            </tr>
        </thead>
        @foreach($data as $i=>$info)
        <tbody>
            <tr>
            @if(in_array('2-2',$userMenu) or Auth::user()->is_superuser == 1)
                <td><input type="checkbox" name="secim[]" value="{{ $info->pid }}"></td>
            @endif
                <td>{{$i+=1}} </td>
                <td><img style="width:70px; height:60px;" src="{{url($info->imagee)}}"></td>
                <td>{{$info->firm}} </td>
                <td>{{$info->brand}} </td>
                <td>{{$info->produkt}} </td>
                <td>{{$info->purchase}} </td>
                <td>{{$info->sale}} </td>
                <td>{{$info->quantity}} </td>
                @if(in_array('2-2',$userMenu) or Auth::user()->is_superuser == 1)
                <td>
                    <a href="{{route('delete_products',$info->pid)}}"><button type="button" title="Delete product" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>
                    <a href="{{route('edit_products',$info->pid)}}"><button type="button" title="Edit product" class="btn btn-warning btn-rounded btn-fw"><i class="mdi mdi-grease-pencil"></i></button></a>
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
