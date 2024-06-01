@extends('layouts.app')

@section('title') Manage @endsection

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

<section class="section main-section">
    <header class="card-header">
            <span class="icon"><i class="mdi mdi-monitor-dashboard"></i></span>
            İdarə paneli
    </header>
    <div class="card-content">
        <div class="form-group">
            <select class="form-control" id="user_list" name="user_list">
                <option value="">Choose client</option>
                @foreach($user_list as $userr)
                    <option value="{{ $userr->id }}">{{ $userr->name }}  {{ $userr->surname }}</option>
                @endforeach
            </select><br>
        </div>

        <form id="checkboxForm" method="post" action="{{ route('save-settings') }}" style="display:none;" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="user_list" id="selected_user_id" value="">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Manage Menu</th>
                        <th>Table</th>
                        <th>DOCS</th>
                        <th>Database Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Brands</td>
                        <td><input type="checkbox" name="secim[]" value="1-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="1-2"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Products</td>
                        <td><input type="checkbox" name="secim[]" value="2-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="2-2"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Clients</td>
                        <td><input type="checkbox" name="secim[]" value="3-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="3-2"></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Departments</td>
                        <td><input type="checkbox" name="secim[]" value="4-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="4-2"></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Expense</td>
                        <td><input type="checkbox" name="secim[]" value="5-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="5-2"></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Orders</td>
                        <td><input type="checkbox" name="secim[]" value="6-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="6-2"></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Planner</td>
                        <td><input type="checkbox" name="secim[]" value="7-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="7-2"></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Positions</td>
                        <td><input type="checkbox" name="secim[]" value="8-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="8-2"></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Staff</td>
                        <td><input type="checkbox" name="secim[]" value="9-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="9-2"></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Suppliers</td>
                        <td><input type="checkbox" name="secim[]" value="10-1"></td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="10-2"></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>DOCS</td>
                        <td></td>
                        <td><input type="checkbox" name="secim[]" value="11-2"></td>
                        <td></td>
                    </tr>
                    <!-- Добавьте остальные строки, как вам нужно -->
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary mr-2" title="Add rules">Submit</button>        
        </form>
    </div>
</section>

<script>
    document.getElementById('user_list').addEventListener('change', function() {
        var checkboxForm = document.getElementById('checkboxForm');
        var selectedUserId = this.value;
        document.getElementById('selected_user_id').value = selectedUserId;
        checkboxForm.style.display = selectedUserId ? 'block' : 'none';
    });
</script>

@endsection
