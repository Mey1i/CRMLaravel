@extends('layouts.app')


@section('title') Statistics @endsection

@section('content')

<section class="container">
    <div class="kard-container">

        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Brands
                        </h3>
                        <h1>
                            {{ $bsay }}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-format-list-bulleted"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Clients
                        </h3>
                        <h1>
                            {{ $csay }}
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
                            Departments
                        </h3>
                        <h1>
                            {{ $dsay }}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-city"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="kard-container">
        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Expense
                        </h3>
                        <h1>
                            {{$esay}}
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
            <div class="kard">
                <div class="kard-content">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3>
                                All tasks
                            </h3>
                            <h1>
                                {{$psay}}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-bookmark"></i>
                            
                        </span>
                </div>
            </div>
    </div>

</section>

<section class="container">
<div class="kard-container">
    <div class="kard">
        <div class="kard-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                        <h3>
                            Positions
                        </h3>
                        <h1>
                            {{$posay}}
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
                            Products
                        </h3>
                        <h1>
                            {{$prosay}}
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
</section>


<section class="container">
    <div class="kard-container">
        <div class="kard">
            <div class="kard-content">
                <div class="flex items-center justify-between">
                    <div class="widget-label">
                        <h3>
                            Suppliers
                        </h3>
                        <h1>
                            {{$supsay}}
                        </h1>
                    </div>
                    <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-truck"></i>
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
</section>

<section class="container">
    <div class="kard-container">
        <div class="kard">
                <div class="kard-content">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3>
                            The total amount of expenses
                            </h3>
                            <h1>
                                {{$amountSay}}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-green-500">
                        <i class="mdi mdi-wallet"></i>
                        </span>
                    </div>
                </div>
        </div>
    </div>
</section>

@endsection
