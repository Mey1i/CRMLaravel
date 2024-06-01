@extends('layouts.app')

@section('title') Planner @endsection

@section('content')

@php
    $user = Auth::user();
    try {
            $secim = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $secim = [];
        }

    $requiredValues = ['7-1'];

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
                                All tasks
                            </h3>
                            <h1>
                                {{$say}}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-bookmark"></i>
                            
                        </span>
                    </div>
                </div>
        </div>
        <div class="kard">
                <div class="kard-content">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3>
                                Active tasks
                            </h3>
                            <h1>
                                {{$activeTasks}}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-bookmark-remove"></i>
                            
                        </span>
                    </div>
                </div>
        </div>
        <div class="kard">
                <div class="kard-content">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3>
                                Complete tasks
                            </h3>
                            <h1>
                                {{$completeTasks}}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-bookmark-check"></i>
                            
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

@if (in_array('7-2',$userMenu) or Auth::user()->is_superuser == 1)
@isset($edit)
<form method="post" action="{{ route('update_planner', ['id' => $edit->id]) }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Planner</h4>
            <div class="form-group">
                    <label for="staff_id">Staff:</label><br>
                    <select class="form-control" id="staff_id" name="staff_id">
                        <option value="">Choose staff</option>
                        @foreach($sdata as $s)
                            <option value="{{ $s->id }}" {{ $edit->staff_id == $s->id ? 'selected' : '' }}>{{ $s->name }} {{ $s->surname }}</option>
                        @endforeach
                    </select><br>
                </div>
            <div class="form-group">
                <label for="task">Task:</label><br>
                <input type="text" class="form-control" id="task" name="task" value="{{ $edit->task }}"><br>
            </div>
            <div class="form-group">
                <label for="dtask">Date:</label><br>
                <input type="date" class="form-control" id="dtask" name="dtask" value="{{ $edit->dtask }}"><br>
            </div>
            <div class="form-group">
                <label for="ttask">Time:</label><br>
                <input type="time" class="form-control" id="ttask" name="ttask" value="{{ $edit->ttask }}"><br>
            </div>
            <button type="submit" class="btn btn-primary mr-2" title="Update task">Update</button> <a href="{{ route('planner', ['cancel_clicked' => 1]) }}"><button type="button"  title="Cancel" class="btn btn-secondary mr-2">Cancel</button></a>
        </div>
    </div>
</form>
@else
<form method="post" action="{{ route('submit_planner') }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Planner</h4>
            <div class="form-group">
            <div class="form-group">
                    <label for="staff_id">Department:</label><br>
                    <select class="form-control" id="staff_id" name="staff_id">
                        <option value="">Choose staff</option>
                        @foreach($sdata as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} {{ $s->surname }}</option>
                        @endforeach
                    </select><br>
                </div>
                <label for="task">Task:</label><br>
                <input type="text" class="form-control" id="task" name="task"><br>
            </div>
            <div class="form-group">
                <label for="dtask">Date:</label><br>
                <input type="date" class="form-control" id="dtask" name="dtask"><br>
            </div>
            <div class="form-group">
                <label for="ttask">Time:</label><br>
                <input type="time" class="form-control" id="ttask" name="ttask"><br>
            </div>
            <button type="submit" title="Send task" class="btn btn-primary mr-2">Submit</button>
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
    <h4 class="card-title">Planner table</h4>
    <form action = "{{ route('delete_selected_planner') }}" >
    <table class="table table-dark">
        <thead>
            <tr>
            @if (in_array('7-2',$userMenu) or Auth::user()->is_superuser == 1)
            <th>Checkbox</th>
            @endif
                <th> №</th>
                <th>Staff</th>
                <th> Task</th>
                <th> Date </th>
                <th> Time </th>
                <th>Remaining time</th>
                @if (in_array('7-2',$userMenu) or Auth::user()->is_superuser == 1)    
                <th> Actions
                <button class="btn btn-danger" title="Delete selected tasks"><i class="mdi mdi-delete"></i></button>
                <a href="{{route('export_planner')}}" class="btn btn-success" title="Export Planner">
                        <i class="mdi mdi-file-excel"></i>
                </a>
            </th>
            @endif
            </tr>
        </thead>
        @foreach($data as $i=>$info)
        <tbody>
            <tr>
            @if (in_array('7-2',$userMenu) or Auth::user()->is_superuser == 1)
                <td><input type="checkbox" name="secim[]" value="{{ $info->pid }}"></td>
            @endif
                <td>{{$i+=1}} </td>
                <td>{{$info->name}} {{$info->surname}}</td>
                <td>{{$info->task}} </td>
                <td>{{$info->dtask}} </td>
                <td>{{$info->ttask}} </td>
                <td>{{$info->remainingTime}}</td> {{-- Display remaining time --}}
                @if (in_array('7-2',$userMenu) or Auth::user()->is_superuser == 1)
                    <td>                
                        @if($info->accept == 0)
                        <a href="{{ route('delete_planner', $info->pid) }}"><button type="button" title="Delete task" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>
                        <a href="{{ route('edit_planner', $info->pid) }}"><button type="button" title="Edit task" class="btn btn-warning btn-rounded btn-fw"><i class="mdi mdi-grease-pencil"></i></button></a>
                        <a href="{{ route('accept_task', $info->pid) }}"><button type="button" title="Accept task" class="btn btn-success btn-rounded btn-fw"><i class="mdi mdi-check"></i></button></a>
                        @else
                        <a href="{{ route('cancel_task', $info->pid) }}"><button type="button" title="Cancel task" class="btn btn-secondary btn-rounded btn-fw"><i class="mdi mdi-close"></i></button></a>
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
