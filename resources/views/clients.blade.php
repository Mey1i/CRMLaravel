@extends('layouts.app')

@section('title') Clients @endsection

@section('content')

@php
    $user = Auth::user();
    try {
            $secim = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $secim = [];
        }

    $requiredValues = ['3-1'];

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
                            Clients
                        </h3>
                        <h1>
                            {{$say}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-account-multiple-outline"></i>
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



@if (in_array('3-2',$userMenu) or Auth::user()->is_superuser == 1)
@isset($edit)
<form method="post" action="{{ route('update_clients', ['id' => $edit->id]) }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Clients</h4>
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
                    <label for="name">Name</label><br>
                    <input type="text" class="form-control" id="name" placeholder="Enter client's name"
                           name="name" value="{{ old('name', $edit->name) }}">
                </div>

                <div class="form-group">
                    <label for="surname">Surname</label><br>
                    <input type="text" class="form-control" id="surname" placeholder="Enter client's surname"
                           name="surname" value="{{ old('surname', $edit->surname) }}">
                </div>

                <div class="form-group">
                    <label for="email">Email</label><br>
                    <input type="text" class="form-control" id="email" placeholder="Enter client's email"
                           name="email" value="{{ old('email', $edit->email) }}">
                </div>

                <div class="form-group">
                    <label for="telephone">Telephone</label><br>
                    <input type="text" class="form-control" id="telephone" placeholder="Enter client's telephone"
                           name="telephone" value="{{ old('telephone', $edit->telephone) }}">
                </div>

                <div class="form-group">
                    <label for="company">Company</label><br>
                    <input type="text" class="form-control" id="company" placeholder="Enter client's company"
                           name="company" value="{{ old('company', $edit->company) }}">
                </div>

                <button type="submit" class="btn btn-primary mr-2" title="Update client">Update</button> <a href="{{ route('clients', ['cancel_clicked' => 1]) }}"><button type="button" class="btn btn-secondary mr-2" title="Cancel">Cancel</button></a>
            </form>
        </div>
    </div>
</form>

@else
    <form method="post" action="{{ route('submit_clients') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Clients</h4>
                <form class="forms-sample">
                    <div class="form-group">
                        <label>Photo</label>
                        <input type="file" name="image" id="uploadImage" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <button class="file-upload-browse btn btn-primary" type="button" onclick="document.getElementById('uploadImage').click()">Upload</button>
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter client's name" name="name">
                    </div>

                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" class="form-control" id="surname" placeholder="Enter client's surname" name="surname">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" placeholder="Enter client's email" name="email">
                    </div>

                    <div class="form-group">
                        <label for="telephone">Telephone</label>
                        <input type="text" class="form-control" id="telephone" placeholder="Enter client's telephone" name="telephone">
                    </div>

                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" class="form-control" id="company" placeholder="Enter client's company" name="company">
                    </div>

                    <button type="submit" class="btn btn-primary mr-2" title="Send client">Submit</button>
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
    <h4 class="card-title">Clients table</h4>
    <form action = "{{ route('delete_selected_clients') }}" >
    <table class="table table-dark">
        <thead>
            <tr>
            @if (in_array('3-2',$userMenu) or Auth::user()->is_superuser == 1)    
                <th>Checkbox</th>
            @endif
                <th> №</th>
                <th> Photo </th>
                <th> Name </th>
                <th> Surname </th>
                <th> Email </th>
                <th> Telephone </th>
                <th> Company </th>
                @if (in_array('3-2',$userMenu) or Auth::user()->is_superuser == 1)    
                <th> Actions
                <button class="btn btn-danger" title="Delete selected clients"><i class="mdi mdi-delete" ></i></button>
                <a href="{{route('export_clients')}}" class="btn btn-success" title="Export Clients">
                        <i class="mdi mdi-file-excel"></i>
                </a>
            </th>          
            @endif  
            </tr>
        </thead>
        @foreach($data as $i=>$info)
        <tbody>
            <tr>
            @if (in_array('3-2',$userMenu) or Auth::user()->is_superuser == 1)    
            <td>
            <input type="checkbox" name="secim[]" value="{{ $info->id }}"></td>
            @endif
                <td>{{ $i+=1 }} </td>
                <td><img style="width:70px; height:60px;" src="{{ url($info->image) }}"> </td>
                <td>{{ $info->name }} </td>
                <td>{{ $info->surname }} </td>
                <td>{{ $info->email }} </td>
                <td>{{ $info->telephone }} </td>
                <td>{{ $info->company }} </td>
                @if (in_array('3-2',$userMenu) or Auth::user()->is_superuser == 1)  
                <td>
                    <a href="{{ route('delete_clients',$info->id) }}"><button type="button" title="Delete client" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete" ></i></button></a>  
                    <a href="{{ route('edit_clients', ['id' => $info->id]) }}">
                            <button type="button" class="btn btn-warning btn-rounded btn-fw" title="Edit client">
                                <i class="mdi mdi-grease-pencil"></i>
                            </button>
                            </a>
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
