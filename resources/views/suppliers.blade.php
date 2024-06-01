@extends('layouts.app')

@section('title') Suppliers @endsection

@section ('content')

@php
    $user = Auth::user();
    try {
            $secim = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $secim = [];
        }

    $requiredValues = ['10-1'];

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
                            Suppliers
                        </h3>
                        <h1>
                            {{$say}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-truck"></i>
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

@if(in_array('10-2',$userMenu) or Auth::user()->is_superuser == 1)
@isset($edit)
<form method="post" action="{{ route('update_suppliers', $edit->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Suppliers</h4>
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
                    <label for="firm">Supplier:</label><br>
                    <input type="text" class="form-control" id="firm" name="firm" value="{{ $edit->firm }}"><br>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label><br>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $edit->name }}"><br>
                </div>
                <div class="form-group">
                    <label for="surname">Surname:</label><br>
                    <input type="text" class="form-control" id="surname" name="surname" value="{{ $edit->surname }}"><br>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label><br>
                    <input type="text" class="form-control" id="email" name="email" value="{{ $edit->email }}"><br>
                </div>
                <div class="form-group">
                    <label for="telephone">Telephone:</label><br>
                    <input type="text" class="form-control" id="telephone" name="telephone" value="{{ $edit->telephone }}"><br>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label><br>
                    <input type="text" class="form-control" id="address" name="address" value="{{ $edit->address }}"><br>
                </div>
                <button type="submit" class="btn btn-primary" title="Update supplier">Update</button> <a href="{{ route('suppliers', ['cancel_clicked' => 1]) }}"><button type="button" title="Cancel" class="btn btn-secondary mr-2">Cancel</button></a>
            </form>
        </div>
    </div>
</form>
@else
<form method="post" action="{{ route('submit_suppliers') }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Suppliers</h4>
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
                    <label for="firm">Supplier:</label><br>
                    <input type="text" class="form-control" id="firm" name="firm"><br>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label><br>
                    <input type="text" class="form-control" id="name" name="name"><br>
                </div>
                <div class="form-group">
                    <label for="surname">Surname:</label><br>
                    <input type="text" class="form-control" id="surname" name="surname"><br>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label><br>
                    <input type="text" class="form-control" id="email" name="email"><br>
                </div>
                <div class="form-group">
                    <label for="telephone">Telephone:</label><br>
                    <input type="text" class="form-control" id="telephone" name="telephone"><br>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label><br>
                    <input type="text" class="form-control" id="address" name="address"><br>
                </div>
                <button type="submit" class="btn btn-primary" title="Send supplier">Submit</button>
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
    <h4 class="card-title">Suppliers table</h4>
    <form action = "{{ route('delete_selected_suppliers') }}" >
    <table class="table table-dark">
        <thead>
            <tr>
            @if(in_array('10-2',$userMenu) or Auth::user()->is_superuser == 1)
            <th>Checkbox</th>
            @endif
                <th> №</th>
                <th> Photo</th>
                <th> Supplier </th>
                <th> Name </th>
                <th> Surname </th>
                <th> Email </th>
                <th> Telephone </th>
                <th> Address </th>
                @if(in_array('10-2',$userMenu) or Auth::user()->is_superuser == 1)
                <th> Actions
                <button class="btn btn-danger" title="Delete selected suppliers"><i class="mdi mdi-delete"></i></button>
                <a href="{{route('export_suppliers')}}" class="btn btn-success" title="Export Suppliers">
                        <i class="mdi mdi-file-excel"></i>
                </a>
            </th>
            @endif
            </tr>
        </thead>
        @foreach($data as $i=>$info)
        <tbody>
            <tr>
            @if(in_array('10-2',$userMenu) or Auth::user()->is_superuser == 1)
                <td><input type="checkbox" name="secim[]" value="{{ $info->id }}"></td>
            @endif
                <td>{{$i+=1}} </td>
                <td><img style="width:70px; height:60px;" src="{{url($info->image)}}"></td>
                <td>{{$info->firm}} </td>
                <td>{{$info->name}} </td>
                <td>{{$info->surname}} </td>
                <td>{{$info->email}} </td>
                <td>{{$info->telephone}} </td>
                <td>{{$info->address}} </td>
                @if(in_array('10-2',$userMenu) or Auth::user()->is_superuser == 1)
                <td>
                    <a href="{{route('delete_suppliers',$info->id)}}"><button type="button" title="Delete supplier" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>
                    <a href="{{ route('edit_suppliers',$info->id) }}">
                            <button type="button" title="Edit supplier" class="btn btn-warning btn-rounded btn-fw">
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
