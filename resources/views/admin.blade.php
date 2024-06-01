@extends('layouts.app')

@section('title') Panel @endsection

@section('content')

@if($errors->any())
    <!-- Ошибки валидации -->
    <div class="custom-message alert danger">
        <span class="message-icon">❌</span>
        <span class="message-content">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </span>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    {{-- Подключение Bootstrap JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endif

@if(session('message'))
    <!-- Сообщение о результате операции -->
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

    {{-- JavaScript для закрытия сообщения --}}
    <script>
        function closeCustomMessage(button) {
            button.parentElement.style.display = 'none';
        }
    </script>
@endif

@if(isset($edit_user))
    <!-- Форма обновления профиля пользователя -->
    <!-- Update Profile Form -->
<form method="post" action="{{ route('update_user', ['id' => $edit_user->id]) }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Your current profile</h4>
            <div class="form-group">
    <label>Current Photo</label><br>
    @if(isset($edit_user->image))
        <img style="width:70px; height:60px;" src="{{ asset($edit_user->image) }}"><br>
    @else
        <img style="width:70px; height:60px;" src="{{ asset('public/storage/image.jpg') }}"><br>
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
                <input type="text" class="form-control" id="exampleInputName" placeholder="Name" name="name" value="{{ $edit_user->name }}">
            </div>
            <div class="form-group">
                <label for="exampleInputSurname">Surname</label>
                <input type="text" class="form-control" id="exampleInputSurname" placeholder="Surname" name="surname" value="{{ $edit_user->surname }}">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email" value="{{ $edit_user->email }}">
            </div>
            <div class="form-group">
                <label for="exampleInputPhone">Phone</label>
                <input type="text" class="form-control" id="exampleInputPhone" placeholder="Phone" name="phone" value="{{ $edit_user->phone }}">
            </div>
            <div class="form-group">
                <label for="exampleInputConfirmPassword1">Current Password</label>
                <input type="password" class="form-control" id="exampleInputConfirmPassword1" placeholder="Password" name="confirm_password">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">New Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="New Password" name="password">
            </div>
            <button type="submit" title="Update user" class="btn btn-primary mr-2">Submit</button><a href="{{ route('admin', ['cancel_clicked' => 1]) }}"><button type="button" title="Cancel" class="btn btn-secondary mr-2">Cancel</button></a>
        </div>
    </div>
</form>

@else
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
        <h4 class="card-title">Users table</h4>
        <form>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th> №</th>
                        <th> Photo</th>
                        <th> Name </th>
                        <th> Surname </th>
                        <th> Phone </th>
                        <th> Email </th>
                        <th> Actions </th>
                    </tr>
                </thead>
                @foreach($users as $i=>$info)
                <tbody>
                    <tr>
                        <td>{{$i+=1}} </td>
                        <td><img style="width:70px; height:60px;" src="{{url($info->image)}}"> </td>
                        <td> {{$info->name}} </td>
                        <td> {{$info->surname}}</td>
                        <td>{{$info->phone}}</td>
                        <td>{{$info->email}} </td>
                        <!-- Остальные поля... -->
                        <td>
                            <a href="{{route('delete_user',$info->id)}}"><button type="button" title="Delete user" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>  
                            <!-- Добавление/удаление админских прав -->
                            @if($info->is_admin == 0)
                                <a href="{{route('admin_user',$info->id)}}"><button type="button" title="Set admin rules" class="btn btn-success btn-rounded btn-fw"><i class="mdi mdi-account-key"></i></button></a>
                            @else
                                <a href="{{route('unadmin_user',$info->id)}}"><button type="button" title="Remove admin rules" class="btn btn-secondary btn-rounded btn-fw"><i class="mdi mdi-account-minus"></i></button></a>
                            @endif
                            <!-- Блокировка/разблокировка пользователя -->
                            @if($info->is_blocked == 0)
                                <a href="{{route('block_user',$info->id)}}"><button type="button" title="Block user" class="btn btn-success btn-rounded btn-fw"><i class="mdi mdi-lock"></i></button></a>
                            @else
                                <a href="{{route('unblock_user',$info->id)}}"><button type="button" title="Unblock user" class="btn btn-secondary btn-rounded btn-fw"><i class="mdi mdi-lock-open"></i></button></a>
                            @endif
                            <!-- Редактирование пользователя -->
                            <a href="{{route('edit_user',$info->id)}}"><button type="button" title="Edit user" class="btn btn-warning btn-rounded btn-fw"><i class="mdi mdi-grease-pencil"></i></button></a>
                        </td>
                    </tr>
                </tbody>
                @endforeach
            </table>
        </form>
    </div>
@endif
@endif
@endsection
