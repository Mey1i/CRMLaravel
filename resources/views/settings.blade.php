@extends('layouts.app')

@section('title') Settings @endsection

@section('content')

<!-- Include your existing JavaScript code here -->

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

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Logo Update</h4>
        <form method="post" action="{{ route('settings.update.logo') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Current Photo</label><br>
                @if ($settings)
                    <img style="width:70px; height:60px;" src="{{ url($settings->image) }}"><br>
                @endif
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
                <label for="title">Title</label><br>
                <input type="text" class="form-control" id="title" placeholder="Enter title" name="title" value="{{ $settings->title ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary mr-2" title="Update title">Update</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Contact Update</h4>
        <form method="post" action="{{ route('settings.update.contact') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label><br>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="{{ $settings->email ?? '' }}">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label><br>
                <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone" value="{{ $settings->phone ?? '' }}">
            </div>
            <div class="form-group">
                <label for="address">Address</label><br>
                <input type="text" class="form-control" id="address" placeholder="Enter address" name="address" value="{{ $settings->address ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary mr-2" title="Update contact info">Update</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Footer Update</h4>
        <form method="post" action="{{ route('settings.update.footer') }}">
            @csrf
            <div class="form-group">
                <label for="footer">Footer</label><br>
                <input type="text" class="form-control" id="footer" placeholder="Enter footer" name="footer" value="{{ $settings->footer ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary mr-2" title="Update footer">Update</button>
        </form>
    </div>
</div>

@endsection
