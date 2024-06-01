@extends('layouts.app')
@section('title') Profile @endsection

@section('content')

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

<form method="post" action="{{ route('update_profile', ['id' => $user_id]) }}" enctype="multipart/form-data">

    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Your current profile</h4>
            <p class="card-description"> </p>
            <div class="form-group">
                <label>Current Photo</label><br>
                @if(isset($user_photo))
                    <img style="width:70px; height:60px;" src="{{ url($user_photo) }}"><br>
                @else
                    <img style="width:70px; height:60px;" src="{{ url('public/storage/image.jpg') }}"><br>
                @endif
            </div>
            <div class="form-group">
                <label>New Photo</label><br>
                <input type="file" name="new_photo" id="uploadImage" class="file-upload-default">
                <div class="input-group col-xs-12">
                    <button class="file-upload-browse btn btn-primary" type="button" onclick="document.getElementById('uploadImage').click()">Upload</button>
                    <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                    <span class="input-group-append"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleInputName">Name</label>
                <input type="text" class="form-control" id="exampleInputName" placeholder="Name" name="name" value="{{ $user->name }}">
            </div>
            <div class="form-group">
                <label for="exampleInputSurname">Surname</label>
                <input type="text" class="form-control" id="exampleInputSurname" placeholder="Surname" name="surname" value="{{ $user->surname }}">
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email"  value="{{ $user->email }}">
            </div>
            <div class="form-group">
                <label for="exampleInputPhone">Phone</label>
                <input type="text" class="form-control" id="exampleInputPhone" placeholder="Phone" name="phone" value="{{ $user->phone }}">
            </div>
            <div class="form-group">
                <label for="exampleInputConfirmPassword1">Confirm Password</label>
                <input type="password" class="form-control" id="exampleInputConfirmPassword1" placeholder="Password" name="confirm_password" id="confirm_password">
            </div>
            <button type="submit" class="btn btn-primary mr-2" title="Update profile">Submit</button>
        </div>
    </div>
</form>


<div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Password update</h4>
                    <form method="post" action="{{ route('password.update') }}">
    @csrf
    <div class="field">
        <label class="label">Current password:</label>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Current Password" name="password" id="current_password">
        </div>
        <p class="help">Please enter your current password</p>
    </div>
    <hr>
    <div class="field">
        <label class="label">New password:</label>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="New Password" name="newpassword" id="new_password">
        </div>
        <p class="help">Please enter your new password</p>
    </div>
    <div class="field">
        <label class="label">Confirm new password:</label>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Confirm New Password" name="Tnewpassword" id="confirm_new_password">
        </div>
        <p class="help">Please confirm your new password</p>
    </div>
    <hr>
    <div class="field">
        <div class="control">
            <button type="submit" class="btn btn-primary mr-2" title="Update password">Submit</button>
        </div>
    </div>
</form>
                 </div>
                </div>

@endsection
