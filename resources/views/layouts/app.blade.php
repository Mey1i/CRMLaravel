<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{url('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{url('assets/vendors/font-awesome/css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">

    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{url('assets/css/style.css')}}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{url('assets/images/favicon.png')}}" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <div class="centered-content">
        <a class="navbar-brand brand-logo" href="{{route('stats')}}">
            <img src="{{url($settings->image)}}" style="width:50px; height:40px;" alt="logo" />
        </a>
        <span class="ml-2">{{$settings->title}}</span>
    </div>
</div>




        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="search-field d-none d-xl-block">
            <form class="d-flex align-items-center h-100">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="search" class="form-control bg-transparent border-0" placeholder="Search..." name="search">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
              

            </form>
          </div>
          <ul class="navbar-nav navbar-nav-right">
           
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                <img src="{{ url(Auth::user()->image) }}">
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">{{ Auth::user()->name }} {{ Auth::user()->surname }}</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="profileDropdown" data-x-placement="bottom-end">
                <div class="p-3 text-center bg-primary">
                  <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ url(Auth::user()->image) }}" alt="">
                </div>
                <div class="p-2">
                  <h5 class="dropdown-header text-uppercase pl-2 text-dark">User Options</h5>
                  <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="{{ route('update_profile', ['id' => Auth::id()]) }}">
                    <span>Profile</span>
                    <span class="p-0">
                      <i class="mdi mdi-account-outline ml-1"></i>
                    </span>
                  </a>
                  <div role="separator" class="dropdown-divider"></div>
                  <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="{{route('logout')}}">
                    <span>Log Out</span>
                    <i class="mdi mdi-logout ml-1"></i>
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
          @if (Auth::user()->is_superuser == 1)
<li class="nav-item nav-category">Admin panel</li>
<li class="nav-item sidebar-user-actions">
    <div class="sidebar-user-menu">
        <a href="{{route('manage')}}" class="nav-link"><i class="mdi mdi-clipboard-text"></i>
            <span class="menu-title">Manage</span>
        </a>
    </div>
</li>
<li class="nav-item sidebar-user-actions">
    <div class="sidebar-user-menu">
        <a href="{{route('admin')}}" class="nav-link"><i class="mdi mdi-account-card-details"></i>
            <span class="menu-title">Admin</span>
        </a>
    </div>
</li>
<li class="nav-item sidebar-user-actions">
    <div class="sidebar-user-menu">
        <a href="{{route('message')}}" class="nav-link"><i class="mdi mdi-message"></i>
            <span class="menu-title">Messages</span>
        </a>
    </div>
</li>
<li class="nav-item sidebar-user-actions">
    <div class="sidebar-user-menu">
        <a href="{{route('settings.edit')}}" class="nav-link"><i class="mdi mdi-settings menu-icon"></i>
            <span class="menu-title">Settings</span>
        </a>
    </div>
</li>
@endif

@php
    $secim = Auth::user()->menu;
    $secim = $secim ? unserialize($secim) : [];
    if(empty($secim)) {
        $secim = [];
    }
@endphp


@if (Auth::user()->is_superuser == 1)
<li class="nav-item nav-category">Main</li>
        <li class="nav-item">
              <a class="nav-link" href="{{ route('brands') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Brands</span>
              </a>
        </li>
<li class="nav-item">
      <a class="nav-link" href="{{ route('products') }}">
        <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
        <span class="menu-title">Products</span>
      </a>
    </li>
    <li class="nav-item">
              <a class="nav-link" href="{{ route('clients') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Clients</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('departments') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Departments</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('expense') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Expense</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('orders') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Orders</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('planner') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Planner</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('positions') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Positions</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('staff') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Staff</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('suppliers') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Suppliers</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('fm') }}">
                <span class="icon-bg"><i class="mdi mdi-floppy"></i></span>
                <span class="menu-title">File manager</span>
              </a>
            </li>


@else

            <li class="nav-item nav-category">Main</li>

            @if (in_array('1-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('brands') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Brands</span>
              </a>
            </li>
            @endif

            @if (in_array('2-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('products') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Products</span>
              </a>
            </li>
            @endif

            @if (in_array('3-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('clients') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Clients</span>
              </a>
            </li>
            @endif

            @if (in_array('4-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('departments') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Departments</span>
              </a>
            </li>
              @endif

            @if (in_array('5-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('expense') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Expense</span>
              </a>
            </li>
            @endif

            @if (in_array('6-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('orders') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Orders</span>
              </a>
            </li>
            @endif

            @if (in_array('7-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('planner') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Planner</span>
              </a>
            </li>
            @endif

            @if (in_array('8-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('positions') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Positions</span>
              </a>
            </li>
            @endif

            @if (in_array('9-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('staff') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Staff</span>
              </a>
            </li>
            @endif

            @if (in_array('10-1',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('suppliers') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Suppliers</span>
              </a>
            </li>
            @endif

            @if (in_array('11-2',$secim))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('fm') }}">
                <span class="icon-bg"><i class="mdi mdi-floppy"></i></span>
                <span class="menu-title">File manager</span>
              </a>
            </li>
            @endif
@endif

            <li class="nav-item nav-category">Others</li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('stats') }}">
                <span class="icon-bg"><i class="mdi mdi-chart-histogram"></i></span>
                <span class="menu-title">Stats</span>
              </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ route('update_profile', ['id' => Auth::id()]) }}">
    <span class="icon-bg"><i class="mdi mdi-account-box"></i></span>
    <span class="menu-title">Profile</span>
</a>

            </li>



            <li class="nav-item sidebar-user-actions">
              <div class="sidebar-user-menu">
                <a href="{{route('logout')}}" class="nav-link"><i class="mdi mdi-logout menu-icon"></i>
                  <span class="menu-title">Log Out</span></a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
        @yield('content')
        <div class="footer-divider"></div>

        <footer>
        {{$settings->footer}}
</footer>
</div>
        <!-- main-panel ends -->

      </div>
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/jquery-circle-progress/js/circle-progress.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
  </body>

</html>

<style>

.custom-message-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.custom-message {
    padding: 15px;
    color: white;
    border-radius: 5px;
    position: relative;
    animation: slideIn 0.5s ease-out;
    display: flex;
    align-items: center;
}

footer {
    text-align: center;
    width: 100%;
    padding: 10px;
    box-sizing: border-box;

    bottom: 0;
}
.footer-divider {
    height: 1px;
    background-color: #ddd; /* Цвет полоски */
}
.success { background-color: #4CAF50; }
.danger { background-color: #FF5252; }
.info { background-color: #3498DB; }
.warning { background-color: #FFC107; }

.message-icon {
    font-size: 24px;
    margin-right: 10px;
}

.message-content {
    flex: 1;
}

.close-message {
    margin-left: 10px;
    cursor: pointer;
    background: none;
    border: none;
    font-size: 18px;
    color: white;
}

@keyframes slideIn {
    from {
        right: -200px;
    }
    to {
        right: 20px;
    }
}



</style>

<style>
        .kard-container {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Adjust the gap according to your design */
        }

        .kard {
            flex: 1; /* Distribute space equally among cards */
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .kard-content {
            padding: 15px;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .widget-label {
            margin-right: 20px; /* Add margin for spacing between text and icon */
        }

        .widget-label h3, .widget-label h1 {
            margin: 0;
        }

        .icon {
            display: flex;
            align-items: center;
        }

        .icon i {
            font-size: 48px; /* Adjust the font size of the icon */
            color: #03A10A; /* Adjust the color of the icon */
        }

        .navbar-brand-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.centered-content {
    display: flex;
    align-items: center;
}

.navbar-brand-wrapper img {
    margin-right: 10px;
}



    </style>

