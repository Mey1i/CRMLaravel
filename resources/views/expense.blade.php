@extends('layouts.app')

@section('title') Expense @endsection

@section ('content')

@php
    $user = Auth::user();
    try {
            $secim = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $secim = [];
        }

    $requiredValues = ['5-1'];

    $isSuperadmin = $user->is_superuser === 1;

    // Check if the required values are missing and the user is not a superadmin
    $missingValues = array_diff($requiredValues, $secim);
    if (!empty($missingValues) && !$isSuperadmin) {
        echo '<script>window.history.back();</script>';
    }
@endphp



<section class="card-container">
<div class="kard-container">
        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Expense
                        </h3>
                        <h1>
                            {{$say}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-wallet-travel"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Amount
                        </h3>
                        <h1>
                            {{$asay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                    <i class="mdi mdi-wallet"></i>
                    </span>
                </div>
            </div>
        </div>
</section>

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

   @if (in_array('5-2',$userMenu) or Auth::user()->is_superuser == 1)
    @isset($edit)
        <form method="post" action="{{ route('update_expense', ['id' => $edit->id]) }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Expense</h4>
                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="appointment">Appointment</label><br>
                            <input type="text" class="form-control" id="appointment" placeholder="Enter appointment" name="appointment" value="{{ $edit->appointment }}">
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label><br>
                            <input type="text" class="form-control" id="amount" placeholder="Enter amount" name="amount" value="{{ $edit->amount }}">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2" title="Update expense">Update</button> <a href="{{ route('expense', ['cancel_clicked' => 1]) }}"><button type="button" title="Cancel" class="btn btn-secondary mr-2">Cancel</button></a>
                    </form>
                </div>
            </div>
        </form>
    @else
        <form method="post" action="{{ route('submit_expense') }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Expense</h4>
                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="appointment">Appointment</label><br>
                            <input type="text" class="form-control" id="appointment" placeholder="Enter appointment" name="appointment">
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label><br>
                            <input type="text" class="form-control" id="amount" placeholder="Enter amount" name="amount">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2" title="Send expense">Submit</button>
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
        <h4 class="card-title">Expense table</h4>
        <form action = "{{ route('delete_selected_expense') }}" >
        <table class="table table-dark">
            <thead>
            <tr>
            @if (in_array('5-2',$userMenu) or Auth::user()->is_superuser == 1)        
                <th>Checkbox</th>
            @endif
                <th> №</th>
                <th> Appointment </th>
                <th> Amount </th>
                @if (in_array('5-2',$userMenu) or Auth::user()->is_superuser == 1)        
                <th> Actions
                <button class="btn btn-danger" title="Delete selected expenses"><i class="mdi mdi-delete"></i></button>
                <a href="{{route('export_expense')}}" class="btn btn-success" title="Export Expense">
                        <i class="mdi mdi-file-excel"></i>
                </a>
            </th>
            @endif
            </tr>
            </thead>
            @foreach($data as $i=>$info)
                <tbody>
                <tr>
                @if (in_array('5-2',$userMenu) or Auth::user()->is_superuser == 1)    
                <td>
                <input type="checkbox" name="secim[]" value="{{ $info->id }}">
            </td>
            @endif+
                    <td>{{$i+=1}} </td>
                    <td> {{$info->appointment}} </td>
                    <td> {{$info->amount}} </td>
                    @if(in_array('5-2',$userMenu) or Auth::user()->is_superuser == 1) 
                    <td>
                        <a href="{{route('delete_expense',$info->id)}}"><button type="button" title="Delete expense" class="btn btn-danger btn-rounded btn-fw"><i class="mdi mdi-delete"></i></button></a>
                        <a href="{{ route('edit_expense',$info->id) }}">
                            <button type="button" title="Edit expense" class="btn btn-warning btn-rounded btn-fw">
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
