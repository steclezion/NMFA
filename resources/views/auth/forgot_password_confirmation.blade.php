<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NMFA</title>
  @include('layouts.custom_registration') 
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
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
    <a href="" class="h2"><b>Medical Products Registration System</b></a>
    </div>
    <div class="card-body">
    <!-- <p class="login-box-msg">Please answer the security questions to retrieve a new password.</p> -->
     
 @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror

 <!-- @if(session()->has('message'))  <div class="alert alert-success"> {{ session()->get('message') }}  </div>  @elseif (session()->has('error'))  @endif -->



{!! Form::open(array('route' => 'user.forgot_password_confirmation','method'=>'POST')) !!}
<div id="pass_email_success" class="alert alert-success alert-sm" style="display:none"></div>
<div id="pass_response_email_danger" class="alert alert-danger alert-sm" style="display:none"></div>
<div id="pass_response_email_warning" class="alert alert-warning alert-sm" style="display:none"></div>

<div class="form-group">
<strong>New Password:</strong>

{!! Form::password('password', array( 'id'=>'password','required','placeholder' =>  'Password','class' => 'form-control','onkeyup'=>'check_strength_password(this.value)')) !!}
</div>

<div class="form-group">
<strong>Confirm Password:</strong>
{!! Form::password('confirm-password',array('id'=>'confirm_password', 'required','placeholder' => 'Confirm Password','class' =>  'form-control','onkeyup'=>'check_strength_password_confirm(password.value,this.value)')) !!}
</div>




        <div class="row">
          <div class="col-12">
            <button type="submit" id="Request_new_passwod" title="Save your new password" class="btn btn-primary btn-block"> <i class="fas fa-save"> </i>Save new password</button>
          </div>
          <!-- /.col -->
        </div>
     
        {!! Form::close() !!}

        <p class="mt-3 mb-1">
        <a  class="btn btn-primary btn-sm"   title="Back" href="{{  url('/forgot_password_with_verification')  }}"> <i class="fas fa-arrow-alt-circle-left"> </i>Back </a>
      </p>
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




<script>
function check_strength_password(password)
{
document.getElementById('confirm_password').value="";
            if( password != '')
            {
      
        $.ajax({
          data:{ password: password,},
          url: "{{   route('check_passwordd')   }}",
          type: "GET",
          dataType: 'json',
          success: function (data) {
            if(data.Message == true)
            {
            
                jQuery('#pass_response_email_warning').hide('100');
                jQuery('#Request_new_passwod').show('100');
                jQuery('#pass_email_success').show('100');
                document.getElementById('pass_email_success').innerHTML =  data.result ;

             
          }
          else if (data.Message == false)
          {
            
             jQuery('#Request_new_passwod').hide('100');
             jQuery('#pass_email_success').hide('100');
             jQuery('#pass_response_email_warning').show('100');
            document.getElementById('pass_response_email_warning').innerHTML =  data.result ;
          

         }
                             },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

        
      });
            }
            else

            {

            document.getElementById('pass_email_success').style.display = "none";
            document.getElementById('pass_response_email_warning').style.display = "none";
            }

}





function check_strength_password_confirm(password,confirm_password)

{
  
            if( confirm_password != '')
            {
      
        $.ajax({
          data:{ password: confirm_password,},
          url: "{{   route('check_passwordd')   }}",
          type: "GET",
          dataType: 'json',
          success: function (data) {
            if(data.Message == true)
            {
           
                jQuery('#pass_response_email_warning').hide('100');
                jQuery('#Request_new_passwod').show('100');
                jQuery('#pass_email_success').show('100');
                jQuery('#pass_response_email_danger').hide('100');
                document.getElementById('pass_email_success').innerHTML =  data.result ;

                  if(confirm_password != password)
                 {
                jQuery('#pass_response_email_warning').hide('100');
                jQuery('#Request_new_passwod').hide('100');
                jQuery('#pass_email_success').hide('100');
                jQuery('#pass_response_email_danger').show('100');
                document.getElementById('pass_response_email_danger').innerHTML =  "Password not match" ;
                } 

             
          }
          else if (data.Message == false)
          {
            
             jQuery('#Request_new_passwod').hide('100');
             jQuery('#pass_email_success').hide('100');
             jQuery('#pass_response_email_warning').show('100');
             jQuery('#pass_response_email_danger').hide('100');
            document.getElementById('pass_response_email_warning').innerHTML =  data.result ;
           

         }
                     
         
                     
                     
                     
                     
                             },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

        
      });
            }
            else

            {

            document.getElementById('pass_email_success').style.display = "none";
            document.getElementById('pass_response_email_warning').style.display = "none";
            }

}



</script>


</body>
</html>
