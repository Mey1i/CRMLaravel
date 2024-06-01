@extends('layouts.app')

@section('title') Positions @endsection

@section('content')

@php
    $user = Auth::user();
    try {
            $secim = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $secim = [];
        }
    $requiredValues = ['8-1'];

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
                            Positions
                        </h3>
                        <h1>
                            {{$say}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                    <i class="mdi mdi-tie"></i>
                    </span>
                </div>
            </div>
</div>
        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Departments
                        </h3>
                        <h1>
                            {{$dsay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-city"></i>
                    </span>
                </div>
            </div>
        </div>



        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Staff
                        </h3>
                        <h1>
                            {{$ssay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-account"></i>
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

@if (in_array('8-2',$userMenu) or Auth::user()->is_superuser == 1)
@isset($edit)
<form method="post" action="{{ route('update_positions', ['id' => $edit->id]) }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Positions</h4>
            <form class="forms-sample">
                <div class="form-group">
                    <label for="department_id">Department:</label><br>
                    <select class="form-control" id="department_id" name="department_id">
                        <option value="">Choose department</option>
                        @foreach($ddata as $d)
                            <option value="{{ $d->id }}" {{ $edit->department_id == $d->id ? 'selected' : '' }}>{{ $d->department }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="position">Position:</label><br>
                    <input type="text" class="form-control" id="position" name="position" value="{{ $edit->position }}"><br>
                </div>
                <button type="submit" class="btn btn-primary" title="Update position">Update</button> <a href="{{ route('positions', ['cancel_clicked' => 1]) }}"><button type="button" title="Cancel" class="btn btn-secondary mr-2">Cancel</button></a>
            </form>
        </div>
    </div>
</form>
@else
<form method="post" action="{{ route('submit_positions') }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Positions</h4>
            <form class="forms-sample">
                <div class="form-group">
                    <label for="department_id">Department:</label><br>
                    <select class="form-control" id="department_id" name="department_id">
                        <option value="">Choose department</option>
                        @foreach($ddata as $d)
                            <option value="{{ $d->id }}">{{ $d->department }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="form-group">
                    <label for="position">Position:</label><br>
                    <input type="text" class="form-control" id="position" name="position"><br>
                </div>
                <button type="submit" class="btn btn-primary" title="Send position">Submit</button>
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
    <h4 class="card-title">Positions table</h4>
    <form action = "{{ route('delete_selected_positions') }}" >
    <table class="table table-dark">
        <thead>
            <tr>
            @if (in_array('8-2',$userMenu) or Auth::user()->is_superuser == 1)  
            <th>Checkbox</th>
            @endif
                <th> №</th>
                <th> Department</th>
                <th> Position </th>
                @if (in_array('8-2',$userMenu) or Auth::user()->is_superuser == 1)  
                <th> Actions
                <button class="btn btn-danger" title="Delete selected positions"><i class="mdi mdi-delete"></i></button>
                <a href="{{route('export_positions')}}" class="btn btn-success" title="Export Positions">
                        <i class="mdi mdi-file-excel"></i>
                </a>     
            </th>
            @endif
            </tr>
        </thead>
        @foreach($data as $i=>$info)
        <tbody>
            <tr>
            @if(in_array('8-2',$userMenu) or Auth::user()->is_superuser == 1)
                <td><input type="checkbox" name="secim[]" value="{{ $info->posid }}"></td>
            @endif
                <td>{{$i+=1}} </td>
                <td>{{$info->department}} </td>
                <td>{{$info->position}} </td>
                <td>
                @if(in_array('8-2',$userMenu) or Auth::user()->is_superuser == 1)
                    <a href="{{route('delete_positions',$info->posid)}}"><button type="button" title="Delete position" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>
                    <a href="{{route('edit_positions',$info->posid)}}"><button type="button" title="Edit position" class="btn btn-warning btn-rounded btn-fw"><i class="mdi mdi-grease-pencil"></i></button></a>
                @endif
                </td>
            </tr>
        </tbody>
        @endforeach
    </table>
    </form>
</div>
@endif
@endsection
