<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Contact page</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <h6 class="font-weight-light">Please write your message to the admin</h6>
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
                <form class="pt-3" method="post" action="{{route('contact')}}" >
                  @csrf
  <div class="form-group">
<input type="text" class="form-control form-control-lg" name="name" id="exampleInputName" placeholder="Name">
  </div>
  <div class="form-group">
    <input type="email" class="form-control form-control-lg" name="email" id="exampleInputEmail" placeholder="E-mail">
  </div>
  <div class="form-group">
    <input type="text" class="form-control form-control-lg" name="phone" id="exampleInputPhone" placeholder="Telephone">
  </div>
  <div class="form-group">
    <input type="text" class="form-control form-control-lg" name="title" id="exampleInputTitle" placeholder="Problem's title">
  </div>
  <div class="form-group">
    <textarea  class="form-control form-control-lg" name="problem" id="exampleInputProblem" placeholder="Problem"></textarea>
  </div>
  <div class="mt-3">
    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Send message</button>
  </div>
  <div class="my-2 d-flex justify-content-between align-items-center">
  </div>
  <div class="text-center mt-4 font-weight-light"> Have you write your message? <a href="{{ route('login') }}" class="text-primary">Enter to the account</a>
  </div>
  <br><br><br>
  <h4><label>Contact form</label></h4>
  <label><b>E-mail:</b>{{$settings->email}}</label><br>
  <label><b>Phone:</b>{{$settings->phone}}</label><br>
  <label><b>Address:</b>{{$settings->address}}</label>
</form>

              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/misc.js"></script>
    <!-- endinject -->
  </body>



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
</html>