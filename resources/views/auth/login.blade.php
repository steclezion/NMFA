
 @include('layouts.css_libs')
 @include('layouts.js_libs')
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="" class="h2"><b>Medical Products Registration System</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="{{ route('signin') }}"  method="post">
      @csrf
        <div class="input-group mb-3">
        <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <input type="email"  name="email" id="email" class="form-control" placeholder="Email" required>
     

        
        </div>

        <div class="input-group mb-3">
        <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <input type="password"  name="password"  id="password" class="form-control" placeholder="Password" required>
          <div class="input-group mb-3">
          
      @error('password')<div class="alert alert-danger">{{ $message }}</div>@enderror   </div>
          
        </div>
        <div class="row">
          <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div> -->
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

   
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="{{ route('forgot_password')  }}">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="{{ route('signup') }}" class="text-center">Create a new account</a>
      </p>

           &nbsp;

       <div class="input-group mb-1">
       <script>
  // $.widget.bridge('uibutton', $.ui.button);
  // fadeTo(speed in ms, opacity)
  $("#alert-messages").fadeTo(15000, 1000).slideUp(500, function(){
      $("#alert-messages").slideUp(500);
  });
</script>
         @include('layouts.message')
          @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>

                      <!-- @if (count($errors) > 0)

<div class="alert alert-danger">

    <strong>Whoops!</strong> There were some problems with your input.<br><br>

    <ul>

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach -->

    </ul>

</div>

@endif

    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->





<!-- <script src="../../plugins/jquery/jquery.min.js"></script> -->
<!-- Bootstrap 4 -->
<!-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<!-- AdminLTE App -->
<!-- <script src="../../dist/js/adminlte.min.js"></script> -->

