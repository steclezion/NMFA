 @include('layouts.css_libs')
 @include('layouts.js_libs')
     <!-- Select2 -->

     <!-- Select2 -->
     <script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>


<link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" >
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">



<link rel="stylesheet" href="{{asset('asset/css/fontawesome.css')}}">
<link rel="stylesheet" href="{{asset('asset/css/fontawesome.min.css')}}">


<!--<link rel="stylesheet" href="{{ asset('3.3.6/bootstrap.min.css')}}" >-->
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
    <!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}" >

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">

<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->
<script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<!-- <script src="{{ asset('dist/js/demo.js')}}" ></script> -->




<body class="hold-transition register-page">
<meta name="_token" content="{{csrf_token()}}" />
<br><br>
<div class="card">
          <div class="card-header">
            <h3 class="card-title">Fill the fields to create an account</h3>

            <!-- <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div> -->
          </div>
          <!-- /.card-header -->
          <form   action="{{ route('customreg') }} "  method="post"    id="nmfacustomregister"  method="POST" enctype="multipart/form-data"   name="nmfacustomregister"  class="demo-form" data-parsley-validate="" novalidate="">
          @csrf

          <div class="card-body" id="Registration_Section">
            <div class="row">
            
              <div class="col-md-6">

        <!--        Prefixes      -->
        <!-- <div class="form-group">
              <label>Prefix</label>
            <select id ="prefixes"   class="form-control" style="width: 100%;"   name="prefixes"  >
            <option value="0" disable="true" selected="true">===Select Prefix===</option>
            <option value="Mr." >Mr.</option>
            <option value="Ms." >Ms.</option>
            <option value="Mrs." >Mrs.</option>
            
              
                  </select>
</div> -->
<div class="form-group">
<label>Prefix</label>
    <input list="prefexes"  id ="prefixes"   class="form-control" style="width: 100%;"   name="prefixes" >
    <datalist id="prefexes" >
    <option   value="" selecetd="true">  </option>
    <!-- <option value="0" disable="true" selected="true">===Select Prefix===</option> -->
            <option value="Mr." >Mr.</option>
            <option value="Ms." >Ms.</option>
            <option value="Mrs." >Mrs.</option>
    </datalist>

</div>

             <!--        First Name       -->
               <label>First Name*</label>
                <div class="input-group mb-4">
                <input id ="firstname" type="text" name="first_name"  onkeyup="AllowonlyText(event,'firstname')"   class="form-control" placeholder="First Name"  data-parsley-length="[4, 20]" data-parsley-group="block1" required>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div>
                </div>
               </div>

             <!--        Middle Name       -->

                <label>Middle Name   </label>
                <div class="input-group mb-4">
                <input id ="middlename" type="text"  onkeyup="AllowonlyText(event,'middlename')"  class="form-control" name="middle_name" placeholder="Middle Name"  required>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div>
                </div>
               </div>

        <!--        Last Name       -->
              <label>Last Name*</label>
                <div class="input-group mb-4">
                <input id ="lastname" type="text" class="form-control"   onkeyup="AllowonlyText(event,'lastname')" name="last_name" placeholder="Last Name"  required>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div>
                </div>
               </div>

                

                <!------------------------Position ----------------> 
         <div class="form-group">
              <label> Position </label>
              <div class="input-group mb-4">
              <input id="position" type="text" name="position" class="form-control" placeholder="Position"  onkeyup="AllowonlyText(event,'position')"    >
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-podcast "></span></div>
                </div>
                </div>

             </div>


        <!--        Country      -->
              <div class="form-group">
              <label>Country*</label>
            <select id ="country_id"  class="form-control select2bs4"  style="width: 100%;"   name="country_id" id="country"  onchange="fetch_tele(this.value,'country_code','{{url('get_tele_code/tele_country_code')}}')" >
            <option value="0" disable="true" selected="true">===Select Country===</option>
                @foreach ($countries as $key => $value)
                  <option value="{{$value->id}}">{{ $value->country_name }}</option>
                @endforeach
                  </select>
             </div>


            <!--        City       -->
                <label>City*</label>
                <div class="input-group mb-4">
                <input id="city" type="text" name="city" onkeyup="AllowonlyText(event,'city')" class="form-control" placeholder="City" name="city"   required>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-city"></span></div>
                </div>
                </div>

             <!--        Street       -->

                <label>Street*</label>
                <div class="input-group mb-4">
                <input id="street" type="text" name="street"  class="form-control" placeholder="Street"    required>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-street-view"></span></div>
                </div>
                </div>


          <!--        Postal Address       -->
               <label>Postal Address* </label>
                <div class="input-group mb-4">
                <input id="postal_code"  type="text" name="postal_code" class="form-control" placeholder="Postal Address"    required>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div>
                </div>
                </div>



       


          

    <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">

              
                           <!--        Telephone       -->
        <i style="color:blue"  class="fas fa-phone fa-2xs" id="country_code">     </i>        
        <label>Phone Number*</label>
        <div class="input-group mb-4">
        <input  id="tele" type="number" min=0 name="telephone" class="form-control" placeholder="Phone Number"   onkeyup="AllowonlyText_Tele(event,'tele')"   required>
        <div class="input-group-append"><div class="input-group-text"><span class="fas fa-phone"></span></div>
        </div>
                
                </div>

                  <!--        Country      -->
                  <div class="form-group">
              <label> Fax </label>
              <div class="input-group mb-4">
              <input id="fax" type="text" name="fax" class="form-control" placeholder="Fax"    >
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-fax"></span></div>
                </div>
                </div>

             </div>


           
<!-- Website URL /> --> 

       <div class="form-group">
       <label>Website</label>
       <div id="age_response_website_url_danger" class="alert alert-danger btn-sm" style="display:none"></div>
                <div id="age_response_website_url_warning" class="alert alert-warning btn-sm" style="display:none"></div>
                <div id="age_response_website_url_success" class="alert alert-success btn-sm" style="display:none"></div>
       <div class="input-group mb-4">
       <div id="age_response_website_url"></div>
                
       <input id="website_url" type="url" class="form-control"  name="website_url" placeholder="Website Url"   onkeyup="valdiate_url(this.value,'age_response_website_url',' ','Register')"/>  
       <div class="input-group-append"><div class="input-group-text"><span class="fas  fa-external-link-alt"></span></div> 
       </div>
       </div>
       </div>

       <!--   Email  --> 
    <div class="form-group">
    <label>Institutional Email*</label>
            <div id="success" class="alert alert-success btn-sm" style="display:none"></div>
	          <div id="danger" class="alert alert-danger btn-sm" style="display:none"></div>
            <div id="warning" class="alert alert-warning btn-sm" style="display:none"></div>
    <div class="input-group mb-4">
    <input type="email" class="form-control"  id="email" onKeyup="ValidateEmail(this.value)" 
    name="email" placeholder="Institutional Email"   required>
    <div class="input-group-append"> <div class="input-group-text"> <span class="fas fa-mail-bulk"></span></div></div>
    </div>


    <!--   bussiness Address  --> 
    <div class="form-group">
    <label>  Business Address* </label>
    <div class="input-group mb-4">
    <input id="business_address"  type="text" class="form-control"  name="business_address" placeholder="Business Address"   required>
    <div class="input-group-append"> <div class="input-group-text"> <span class="fas fa-business-time"></span></div></div>
    </div>


      <!--   bussiness Address  --> 
      <!-- <div class="form-group">
    <label> Position </label>
    <div class="input-group mb-4">
    <input id="position"  type="text" class="form-control"  name="position" placeholder="Position"   required>
    <div class="input-group-append"> <div class="input-group-text"> <span class="fas fa-face-flushed"></span></div></div>
    </div> -->


<hr>
<!--   Users --> 
<div class="form-group">
    <label>User Name*</label>
    <div id="danger-user" class="alert alert-danger btn-sm" style="display:none"></div>
	  <div id="warning-user" class="alert alert-warning btn-sm" style="display:none"></div>
    <div id="success-user" class="alert alert-success btn-sm" style="display:none"></div>
    <div class="input-group mb-4">
    <input id="user_name" type="text" name="user_name" class="form-control"  onkeyup="Validate_UserName(this.value)"  id="User_Name" placeholder="User Name"  required>
    <div class="input-group-append"> <div class="input-group-text"> <span class="fas fa-users-slash"></span></div></div>
    </div>

<!--   Passwrod   --> 
    <div class="form-group">
    <label>Password*</label>
   
    <div id="pass_email_success" class="alert alert-success btn-sm alert-sm" style="display:none"></div>
    <div id="pass_response_email_danger" class="alert alert-danger btn-sm alert-sm" style="display:none"></div>
    <div id="pass_response_email_warning" class="alert alert-warning btn-sm alert-sm" style="display:none"></div>

    <div id="password_check" class="alert alert-warning btn-sm" style="display:none;width:auto;"></div>
    <div class="input-group mb-4">
    <input id="password" type="password" id="password" name="password" class="form-control" placeholder="Password"   onkeyup="check_strength_password(this.value)" required>
    <div class="input-group-append"> <div class="input-group-text"> <span class="fas fa-lock"></span></div></div>
    </div>


  <!--   Passwrod   --> 
  <div class="form-group">
    <label>Confirm Password*</label>
    <div class="input-group mb-4">
    <input type="password" id="confirm_password" class="form-control" onkeyup ="check_strength_password_confirm(password.value,this.value)"  placeholder="Confirm Password"  required>
    <div class="input-group-append"> <div class="input-group-text"> <span class="fas fa-lock"></span></div></div>
    <div id="confirm_check" class="alert alert-danger btn-sm" style="display:none"></div>
   </div>


     <!--   Modal Forgot Password   --> 
    <div class="form-group">
    <label> Questions </label>
    <div class="input-group mb-4">
    <div class="col-6"><span  id="modal_forgot_password"  class="btn btn-info">Security Questions</span></div>
    @include('layouts.modal_forgot_password')

    </div>   
    </div>

</div>

 </div>
   
      
 </div>  
      
      

</div>

</div>
  
     
</div>  
     
     <br>


          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree"  required>
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <span    id="Register" class="btn btn-primary validate">Register</span>
          </div>
          <!-- /.col -->
        </div>
</form>

<div class="social-auth-links text-center">
<!-- <a href="#" class="btn btn-block btn-primary"> <i class="fab fa-facebook mr-2"></i> Sign up using Facebook </a>
<a href="#" class="btn btn-block btn-danger"><i class="fab fa-google-plus mr-2"></i> Sign up using Google+</a> -->
</div>



      <a href="{{ route('login') }}" class="text-center">I already have an account</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->


@include('layouts.modal_forgot_password')


@include('layouts.custom_registration') 


<script>
    function ValidateEmail(Email) {

if (Email != '') {

    jQuery(document).ready(function() {

        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: "{{  url('/Validate/post')}}",
            method: 'post',
            data: {
                Email: jQuery('#email').val(),
            },
            success: function(result) {
              jQuery('#danger').hide('100');

             
                if (result.error == undefined) {

                   /* jQuery('#success').show('100');
                    jQuery('#success').html(result.success);
                    jQuery('#danger').hide('100');
                    $("#sucess").animate({ left: '250px' });
                    jQuery('#success').hide('100');*/

                    var id = setInterval(validate, 500);

                    function validate() {

                        if ((Email.indexOf("@") < 1) || ((Email.indexOf("@") == (Email.length < 1)))) {
                            jQuery('#warning').show('100');
                            jQuery('#warning').html('Invalid Email Axerate Symbol is Absent');
                            document.getElementById('email').focus();
                            document.getElementById('Register').disabled = true;
                            clearInterval(id);
                            return false
                        }

                        if (Email.indexOf("@") <= 2) {
                            jQuery('#warning').show('100');
                            jQuery('#warning').html('Email length should be at least 3 charcter long.');
                            document.getElementById('email').focus();
                            document.getElementById('Register').disabled = true;
                            clearInterval(id);
                            return false
                        }

                        if ((Email.indexOf(".") == -1)) {
                            document.getElementById('Register').disabled = true;
                            jQuery('#warning').show('100');
                            jQuery('#warning').html('Invalid Email (.) Symbol is Absent');
                            document.getElementById('email').focus();
                            clearInterval(id);
                            return false
                        }

                        if ((Email.indexOf("@") > 1) || ((Email.indexOf("@") != (Email.length < 1)) || (Email.indexOf(".") != -1)))
                         {
                            document.getElementById('Register').disabled = false;
                            jQuery('#warning').hide('100');
                            document.getElementById('email').focus();
                            clearInterval(id);
                        }
                          
                          jQuery('#Register').show();

                    }

                } else {
                  //alert(result.error);
                  jQuery('#Register').hide();
                    jQuery('#danger').show('100');
                    jQuery('#danger').html(result.error);
                    
                    // $("#danger").animate({ right: '15px' });
                    jQuery('#success').hide('100');
                    jQuery('#warning').hide('100');
                    document.getElementById('Register').disabled = true;


                }
            },
        });

    });

} else {

    jQuery('#danger').hide('100');
    jQuery('#warning').hide('100');
    jQuery('#success').hide('100');
}

}

</script>




<script>
function Validate_UserName(username) 
{
var name = document.getElementById('user_name').value;

if( username.trim() != '' )
{


if( name.length <= 4 )
{
    var id = setInterval(username_delay_time, 50);
  function username_delay_time() {
jQuery('#danger-user').hide('100');
document.getElementById('Register').disabled=true;
jQuery('#danger-user').hide('100');
jQuery('#warning-user').show('100');
jQuery('#warning-user').html('Username should be at least 5 charcters long.');
jQuery('#User_Name').focus();
$("#Register").addClass("important blue");
$('#Register').hide('10')
clearInterval(id);
}

}


else{
    
    jQuery(document).ready(function() {

$.ajaxSetup({
    headers: {

        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

jQuery.ajax({
    url: "{{  url('/Validate/user')  }}",
    method: 'post',
    data: {
        name: jQuery('#user_name').val(),
          },
success: function(result) {
console.log(result.success);
jQuery('#danger-user').hide('100');
if(result.success == true  ){

document.getElementById('Register').disabled=false;
jQuery('#danger-user').hide('100');
$('#Register').show('10')
jQuery('#success-user').hide('100');
jQuery('#warning-user').hide('100');

}

else{

document.getElementById('Register').disabled=true;
jQuery('#danger-user').show('100');
$('#Register').hide('10')
jQuery('#danger-user').html(result.error);
//$("#danger-user").animate({left: '15px'});
jQuery('#success-user').hide('100');
jQuery('#warning-user').hide('100');

    }
    },
    });
    });



// $('#Register').show('10')
// document.getElementById('Register').disabled=false;
// $('#warning-user').hide('100');
// document.getElementById('user_name').focus();

}







}


else
{

		jQuery('#danger-user').hide('100');
		jQuery('#warning-user').hide('100');
		jQuery('#success-user').hide('100');
}

}
</script>


<script>

function check_validity_password(password,confirmpassword)
{
  if(confirmpassword == '' && password != '' ){jQuery('#password_check').hide('10');}
  if(confirmpassword != '' && password == '' ){

     var id = setInterval(checkconfirmation, 500);
  function checkconfirmation() {
jQuery('#password_check').html('Password is Empty:First Fill Password');
// $("#password_check").animate({left: '15px'});
jQuery('#password_check').show('1000');
jQuery('#password').focus();
jQuery('#confirm_password').val('');
clearInterval(id);

}
jQuery('#password_check').hide('100');
}
/*
var confirmpassword= $("#confirmpassword").val();
var password = $("#password").val();
 if(confirmpassword != '' && password !='' && confirmpassword != password  )
 {
     console.log(password);

var id = setInterval(checkconfirm, 1000);
function checkconfirm(){
   
        
jQuery('#confirm_check').html('Password Incorrect');
$("#confrim_check").animate({left: '35px'});
jQuery('#confirm_check').show('10000');
clearInterval(id);
}
jQuery('#confirm_check').hide('');
}
*/
}


</script>












<script>




function   fetch_tele(tele,response_id,url) {
//  alert(tele);
if (tele >= 0) {
   jQuery(document).ready(function() {
      $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
              });

     jQuery.ajax({
            url: url,
            method: 'post',
            data: {
                tele: tele,
            },
            success: function(result) {
            // alert(result.Code);
  document.getElementById(response_id).innerHTML = result.Code;
  document.getElementById('tele').value = result.Code;
  // jQuery('#'+response_id).val(result.Code);

                    }

           
        });

    });

} else {

    jQuery('#danger').hide('100');
    jQuery('#warning').hide();
    jQuery('#success').hide('100');
}

}


 $("#Registration_Section").click(function(){

    $('#firstname').css("background-color", "#ffffff");
    $('#middlename').css("background-color", "#ffffff");
    $('#lastname').css("background-color", "#ffffff");
    $('#country_id').css("background-color", "#ffffff");
    $('#prefixes').css("background-color", "#ffffff");
    $('#position').css("background-color", "#ffffff");
    $('#city').css("background-color", "#ffffff");
    $('#street').css("background-color", "#ffffff");
    $('#postal_code').css("background-color", "#ffffff");
    $('#tele').css("background-color", "#ffffff");
    $('#fax').css("background-color", "#ffffff");
    $('#position').css("background-color", "#ffffff");
    $('#username').css("background-color", "#ffffff");
    $('#website_url').css("background-color", "#ffffff");
    $('#email').css("background-color", "#ffffff");
    $('#business_address').css("background-color", "#ffffff");
    $('#personal_information').css("background-color", "#ffffff");
    $('#childhodd_questions').css("background-color", "#ffffff");
    $('#hobbies').css("background-color", "#ffffff");
    $('#confirm_password').css("background-color", "#ffffff");
    $('#password').css("background-color", "#ffffff");
    
 });


$("#nmfacustomregister").click(function(){
$('#firstname').css("background-color", "#ffffff")
$('#lastname').css("background-color", "#ffffff");
$('#country_id').css("background-color", "#ffffff");
$('#position').css("background-color", "#ffffff");
$('#prefixes').css("background-color", "#ffffff");
$('#street').css("background-color", "#ffffff");
$('#city').css("background-color", "#ffffff"); 
$('#postal_code').css("background-color", "#ffffff");
$('#tele').css("background-color", "#ffffff");
$('#user_name').css("background-color", "#ffffff");
$('#email').css("background-color", "#ffffff");
$('#confirm_password').css("background-color", "#ffffff");
$('#business_address').css("background-color", "#ffffff");
$('#childhood_questions').css("background-color", "#ffffff");
$('#child_hood').css("background-color", "#ffffff");
$('#hobbies').css("background-color", "#ffffff");
$('#hobbies_select').css("background-color", "#ffffff");
$('#personal_information').css("background-color", "#ffffff");
$('#personal_informations').css("background-color", "#ffffff");
$('#confirm_password').css("background-color", "#ffffff");
$('#password').css("background-color", "#ffffff");
    
});



function isValidURL(string) {
   var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
   return (res !== null)
  };

  function validURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return !!pattern.test(str);
}



//Section To Validate URL 
function valdiate_url(url_string,xmlxhttp_response_id,url,action_button) {


 var warning = xmlxhttp_response_id +"_warning";
 var danger = xmlxhttp_response_id +"_danger";
 var success = xmlxhttp_response_id +"_success";


if (url_string != '') {
 

   url_string = validURL(url_string);

           

              var id = setInterval(validate, 500);
              function validate() {
           if(url_string == false)
            {
           //document.getElementById(response_id).innerHTML = result.Code;
           jQuery('#'+danger).show();
           jQuery('#'+success).hide();
           jQuery('#'+danger).html('Invalid Web URL(e.g www.google.com)');
           clearInterval(id);
           document.getElementById(action_button).disabled = true;
           jQuery('#'+action_button).hide('100');
            }

           else if(url_string == true)
            {
        //jQuery('#'+success).show();
        jQuery('#'+danger).hide();
      // jQuery('#'+success).html(result.success);
       clearInterval(id);
       document.getElementById(action_button).disabled = false;
       jQuery('#'+action_button).show('100');
            }

      
                    }
} 

else{
  jQuery('#'+danger).hide();
  jQuery('#'+success).hide();
  document.getElementById(action_button).disabled = false;
  jQuery('#'+action_button).show('100');

}

}






$('#Register').click(function () {

//     $('#nmfacustomregister').submit(function(e) 
// {
// e.preventDefault();
// console.log(e.preventDefault());

var firstname = document.getElementById('firstname').value;
var middlename = document.getElementById('middlename').value;
var lastname = document.getElementById('lastname').value;
var country_id = document.getElementById('country_id').value;

var prefixes = document.getElementById('prefixes').value;
var position = document.getElementById('position').value;

var city = document.getElementById('city').value;
var street = document.getElementById('street').value;
var postal_code = document.getElementById('postal_code').value;
var tele = document.getElementById('tele').value;
var fax = document.getElementById('fax').value;
var username = document.getElementById('user_name').value;
var website_url = document.getElementById('website_url').value;
var email = document.getElementById('email').value;
var business_address = document.getElementById('business_address').value;
var password = document.getElementById('password').value;
var confirm_password = document.getElementById('confirm_password').value;
var personal_information = document.getElementById('personal_information').value;
var childhood_questions = document.getElementById('childhood_questions').value;
var hobbies = document.getElementById('hobbies').value;
var agreeTerms = document.getElementById('agreeTerms').value;
var personal_informations = document.getElementById('personal_informations').value;
var child_hood = document.getElementById('child_hood').value;
var hobbies_select = document.getElementById('hobbies_select').value;
var country_code = document.getElementById('country_code').innerHTML;



// if(firstname !=''){ var fname = firstname.length; var count_if_a_number=0;  for (var i=0; i < fname ; i++) { console.log( Number(firstname[i]) ); if(Number(firstname[i]).toString() != 'NaN'){count_if_a_number++;}       }}
// if(middlename  !=''){var mname = middlename.length; var count_if_a_number_middle=0;  for (var i=0; i < mname ; i++) { console.log( Number(middlename[i]) ); if(Number(middlename[i]).toString() != 'NaN'){count_if_a_number_middle++;}       }}
// if(lastname  !=''){var lname = lastname.length; var count_if_a_number_last=0;  for (var i=0; i < lname ; i++) { console.log( Number(lastname[i]) ); if(Number(lastname[i]).toString() != 'NaN'){count_if_a_number_last++;}       }}
// if(city !=''){var ccity = city.length; var count_if_a_number_city=0;  for (var i=0; i < ccity ; i++) { console.log( Number(city[i]) ); if(Number(city[i]).toString() != 'NaN'){count_if_a_number_city++;}       }}
// if( count_if_a_number >= 1 ){  $('#firstname').css("background-color", "#f13f3c"); document.getElementById('firstname').focus(); return false;}
// if( count_if_a_number_middle >= 1 ){  $('#middlename').css("background-color", "#f13f3c"); document.getElementById('middlename').focus(); return false;}
// if( count_if_a_number_last >= 1 ){  $('#lastname').css("background-color", "#f13f3c"); document.getElementById('lastname').focus(); return false;}
// if( count_if_a_number_city >= 1 ){  $('#city').css("background-color", "#f13f3c"); document.getElementById('city').focus(); return false;}
//if(prefixes =='' || prefixes == 0 ){$('#prefixes').css("background-color", "#e6c1c7"); document.getElementById('prefixes').focus(); return false;}
//if( prefixes == 0 ){$('#prefixes').css("background-color", "#e6c1c7"); document.getElementById('prefixes').focus(); return false;}




if(firstname ==''){  $('#firstname').css("background-color", "#e6c1c7"); document.getElementById('firstname').focus(); return false;}
// if(middlename ==''){  $('#middlename').css("background-color", "#e6c1c7"); document.getElementById('middlename').focus(); return false;}
if(lastname ==''){ $('#lastname').css("background-color", "#e6c1c7"); document.getElementById('lastname').focus(); return false;}
if(position ==''){ /* $('#position').css("background-color", "#e6c1c7"); document.getElementById('position').focus(); return false;*/}
if(position !='' ){var cposition = position.length; var count_if_a_number_position=0;  for (var i=0; i < cposition ; i++) { console.log( Number(position[i]) ); if(Number(position[i]).toString() != 'NaN'){count_if_a_number_position++;}       }}
//if( count_if_a_number_position >= 1 ){  $('#position').css("background-color", "#f13f3c"); document.getElementById('position').focus(); return false;}
if(country_id =='' || country_id==0){ $('#country_id').css("background-color", "#e6c1c7");  document.getElementById('country_id').focus(); return false; }
if(city ==''){ $('#city').css("background-color", "#e6c1c7"); document.getElementById('city').focus(); return false;}
if(street ==''){ $('#street').css("background-color", "#e6c1c7"); document.getElementById('street').focus(); return false;}
if(postal_code ==''){$('#postal_code').css("background-color", "#e6c1c7"); document.getElementById('postal_code').focus(); return false;}
if(tele ==''){$('#tele').css("background-color", "#e6c1c7"); document.getElementById('tele').focus(); return false;}
if(fax ==''){ /*$('#fax').css("background-color", "#e6c1c7"); document.getElementById('fax').focus(); return false; */}
if(website_url==''){ /* $('#website_url').css("background-color", "#e6c1c7"); /* document.getElementById('website_url').focus(); return false; */}
if(email ==''){$('#email').css("background-color", "#e6c1c7"); document.getElementById('email').focus(); return false;}
if(business_address ==''){ $('#business_address').css("background-color", "#e6c1c7"); document.getElementById('business_address').focus(); return false;}
if(username ==''){ $('#user_name').css("background-color", "#e6c1c7"); document.getElementById('user_name').focus(); return false;}
if(password ==''){ $('#password').css("background-color", "#e6c1c7"); document.getElementById('password').focus(); return false;}
if(confirm_password ==''){ $('#confirm_password').css("background-color", "#e6c1c7"); document.getElementById('confirm_password').focus(); return false;}






if(personal_information == '' || personal_informations==0 ){
    $('#modelHeading_question_answer').html("Fill all security questions");
     $('#ajax_model_forgot_password').modal('show');
     $('#personal_information').css("background-color", "#e6c1c7");
     $('#personal_informations').css("background-color", "#e6c1c7");
    document.getElementById('personal_information').focus(); return false;
    }

    if(childhood_questions == ''  || child_hood==0){
        $('#modelHeading_question_answer').html("Fill all security questions");
     $('#ajax_model_forgot_password').modal('show');
     $('#childhood_questions').css("background-color", "#e6c1c7");
     $('#child_hood').css("background-color", "#e6c1c7");
    document.getElementById('childhood_questions').focus(); return false;
    }

    if(hobbies == '' || hobbies_select==0){
        $('#modelHeading_question_answer').html("Fill all security questions");
        $('#hobbies').css("background-color", "#e6c1c7");
        $('#hobbies_select').css("background-color", "#e6c1c7");
       $('#ajax_model_forgot_password').modal('show');
    document.getElementById('hobbies').focus(); return false;
    }


if(document.getElementById('agreeTerms').checked == false){ $('#agreeTerms').css("background-color", "red"); 

    var Toast = Swal.mixin({
      toast: true,
      position: 'center',
      showConfirmButton: false,
      timer: 3000
    });

//    Toast.fire({
//         icon: 'info',
//         title: 'Click I agree button to proceed with registration.'
//       })
 

   toastr.error("Click I agree button to proceed with registration.")

document.getElementById('agreeTerms').focus(); 

return false;}



$.ajax({
    //data: $('#bookForm').serialize(),
    data:{ 

 first_name : firstname,
 middle_name:middlename,
 last_name:lastname,
 country_id:country_id,
 position:position,
 prefixes:prefixes,
 country_code:country_code,
 city:city,
 street:street,
 postal_code:postal_code,
 telephone:tele,
 sate:city,
 addressline_one:business_address,
 fax:fax,
 country_code:country_code,
 user_name:username,
 website_url:website_url,
 email : email,
 business_address :business_address, 
 password:password,
 confirm_password :confirm_password,
 personal_information : personal_information,
 childhood_questions : childhood_questions,
 hobbies :hobbies,
 agreeTerms :agreeTerms,
 personal_informations : personal_informations,
 child_hood : child_hood,
 hobbies_select :hobbies_select,
   },
    url: "{{  route('customreg')   }}",
    type: "POST",
    dataType: 'json',
    success: function (data) {


        if(data.Message == true)  
{

//alert(data.email);


  if(data.email == true)   { toastr.error("Invalid Email"); return false;}


    toastr.success("User registered successfully")
    var id = setInterval(user_reg, 2000);
    function user_reg() {
    window.location = "/login";
    clearInterval(id);
                        }
              
                         } 
     else
                         {
              
               toastr.error(data.Message.item)
              }

                            },
    error: function (data) {
        console.log('Error:', data);
        $('#saveBtn').html('Save Changes');
    }

});
});
</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
    //Money Euro
    $('[data-mask]').inputmask();

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    });
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    );

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    });

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox();

    //Colorpicker
    $('.my-colorpicker1').colorpicker();
    //color picker with addon
    $('.my-colorpicker2').colorpicker();

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  });
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  });

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false;

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template");
  previewNode.id = "";
  var previewTemplate = previewNode.parentNode.innerHTML;
  previewNode.parentNode.removeChild(previewNode);

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  });

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  });

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  });

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1";
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  });

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  });

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  };
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End



function start_applications(value){
console.log(value);
var application_id = document.getElementById('app_id').innerHTML ;

var selected_value = document.getElementById('new_application_mode').value ;
if(application_id == '' &&  value != 0 )
 { $('#appicaiton_save').show(); 
    
 
 }
 else if(application_id != '' &&   value != 0 )
 {

     $('#applicaion_updatee').show();
 }
 else if(application_id != '' &&   value == 0 )
 {
    $('#appicaiton_save').hide('10'); 
     $('#applicaion_updatee').hide('10');
 }
 else if(application_id == '' &&   value == 0 )
 {
    $('#appicaiton_save').hide('10'); 
     $('#applicaion_updatee').hide('10');
 }
  
}
</script>
