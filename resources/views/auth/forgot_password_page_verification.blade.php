<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NMFA</title>

  <!-- Google Font: Source Sans Pro -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
    <a href="" class="h2"><b>Medical Products Registration System</b></a>
    </div>
<div class="card-body">
<p class="login-box-msg">Please answer the security questions to retrieve a new password.</p>

@error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror

 
{!! Form::open(array('route' => 'user.forgot_password_with_verification','method'=>'POST')) !!}
@csrf
<div class="modal-body">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

@if(session()->has('message'))  <input type="hidden" name="user_id" value="{{ session()->get('message') }}" /> @elseif (session()->has('error'))  @endif

<div class="form-group">
<label>1. Personal Information Questions </label>
<select class="form-control" style="width: 100%;"   name="personel_information" id="personal_informations"  required>
<option value="" disable="true" selected="true"></option>
@foreach ($forgotpassword_one as $key => $value)
<option value="{{$value->id}}">{{ $value->Question_Name }}</option>
@endforeach
</select> 
</div>

<div class="form-group">
<p class="text-muted"> 
<input id="personal_information" type="text" name="faq_1"  required   class="form-control"   required placeholder="Answer Question Number 1"  /> 
</p> 
</div>

<div class="form-group">
<label>2. Childhood Questions </label>
<select class="form-control" style="width: 100%;"   name="child_hood" id="child_hood" required >
<option value="" disable="true" selected="true"> </option>

@foreach ($forgotpassword_two as $key => $value)
<option value="{{$value->id}}"><span style="color:black"> {{ $value->Question_Name }} </span>  </option>
@endforeach
</select> 
</div>


<div class="form-group">
<p class="text-muted"> 
<input id="childhood_questions" type="text" name="faq_2"  required   class="form-control"   placeholder="Answer Question Number 2" required />
</p> 
</div>
<div class="form-group">
<label>3. Hobbies  </label>
<select class="form-control" style="width: 100%;"   name="hobbies_id" id="hobbies_select" required >
<option value="" disable="true" selected="true"> </option>
@foreach ($forgotpassword_three as $key => $value)
<option value="{{$value->id}}">{{ $value->Question_Name }}</option>
@endforeach
</select>  
</div>

<div class="form-group">
<p class="text-muted"> 
<input id="hobbies" type="text" name="faq_3"  required   class="form-control"   placeholder="Answer Question Number 3" > 
</p> 
</div>
<div class="col-md-offset-2 col-sm-10">
<button type="submit" name="proceed"  style="display:col-4"   class="btn btn-primary"  title="fill the form proceed to next page" id="ok_forgot_password" ><i class="fas fa-save"> </i> Proceed </button>
<br>

                    </div>
        {!! Form::close() !!}

      <p class="mt-3 mb-1">
        <a  class="btn btn-primary btn-sm"   title="Back" href="{{  url('/forgotpassword')  }}"> <i class="fas fa-arrow-alt-circle-left"> </i>Back </a>
      </p>
    </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>



</body>
</html>
