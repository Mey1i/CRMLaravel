@extends('layouts.app')


@section('title') Messages  @endsection

@section('content')

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


<div class="card-body">
    <h4 class="card-title">Letters table</h4>
    <form>
    <table class="table table-dark">
        <thead>
            <tr>
                <th> №</th>
                <th> Name </th>
                <th> Email    </th>
                <th> Phone </th>
                <th> Title </th>
                <th> Problem </th>
                <th> Actions</th>
            </tr>
        </thead>
        @foreach($message as $i=>$info)
        <tbody>
            <tr>
                <td>{{$i+=1}} </td>
                <td> {{$info->name}} </td>
                <td> {{$info->email}}</td>
                <td>{{$info->phone}}</td>
                <td>{{$info->title}}</td>
                <td>{{$info->problem}}</td>
                @if($info->accept == 0)
                <td>
                    <a href="{{route('delete_message',$info->id)}}"><button type="button" title="Delete message" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>  
                    <a href="{{ route('accept_message', $info->id) }}"><button type="button" title="Accept message" class="btn btn-success btn-rounded btn-fw"><i class="mdi mdi-check"></i></button></a>                </td>
                @else
                <td>
                    <a href="{{ route('cancel_message', $info->id) }}"><button type="button" title="Cancel message" class="btn btn-secondary btn-rounded btn-fw"><i class="mdi mdi-close"></i></button></a>
                </td>
                @endif
            </tr>
        </tbody>
        @endforeach
    </table>
    </form>
</div>
@endsection