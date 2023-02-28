@extends('layouts.app') 

@section('content')



<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- SweetAlert2 -->



<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row mb-2">

        <div class="col-sm-6">
            <h1>Profile</h1>
        </div>

        <div class="col-sm-6">
            <!-- <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li> -->
            <!-- </ol> -->
        </div>
    </div>
</div>
<!-- /.container-fluid -->


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                @php $count=0; @endphp @foreach ($dataa as $key => $user) @if(!empty($user->getRoleNames())) @foreach($user->getRoleNames() as $v) @if($v == 'Applicant') @php $count=1; @endphp @endif @endforeach @endif @endforeach

                <!-- About Me Box -->


                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About Me</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if($count == 0 )
                        <br>
                        <!-- <strong><i class="far fa-file-alt mr-1"></i> <u> Upload A Document </u></strong> -->
                        <br> @foreach( $user_upload_cv as $up_cv) @if($up_cv->dname)
                        <p>

                            <a class="btn-link text-danger text-7xl" target="_blank" title="CV(Curriclum Vitae)" href="{{$up_cv->path}}"> <i class="fas fa-file-pdf fa-1"> </i> Uploaded Document</a>
                        </p>
                        @endif @endforeach
                        <button class="btn btn-primary" title="Upload a Document(PDF only)" id="upload_cv"> <i class="fas fa-upload"> </i>  Upload Document  </button> @endif

                    </div>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->


            <div class="col-md-9">
                <div class="card">

                    <div class="card-body">
                        <div class="tab-content">


                            <div class="active tab-pane" id="settings">
                                <div class="container-fluid">
                                    <div class="row">

                                        <div class="col-12">
                                            @if (count($errors) > 0)

                                            <div class="alert alert-danger">

                                                <strong>Whoops!</strong> There were some problems with your input.<br><br>

                                                <ul>

                                                    @foreach ($errors->all() as $error)

                                                    <li>{{ $error }}</li>

                                                    @endforeach

                                                </ul>

                                            </div>

                                            @endif
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">
                                                        <h2>Edit User: {{ Auth::user()->user_name }} </h2>
                                                    </h3>


                                                    <div class="card-tools">

                                                        <a class="btn btn-primary" href="{{ url('/') }}" title="Back to previous page"> <i class="fas fa-arrow-alt-circle-left fa-1"> </i> Back</a>

                                                    </div>

                                                </div>
                                                <!-- {!! Form::model($user, ['method' => 'PATCH','route' => ['users_profile.update', $user->id]]) !!} -->

                                                <form id="userForm" accept-charset="utf-8" enctype="multipart/form-data" action="{{ route('users_profile.update', $user->id) }}" method="POST" name="userForm" class="form-horizontal">
                                                    @csrf @foreach($dataa as $users)
                                                    <div class="card-body">
                                                        <strong>Prefix:</strong>
                                                        <!-- {!! Form::select('country_id',$countries_id,$countries, array('class' => 'form-control')) !!} -->
                                                        <!-- <select readonly  class="form-control select2bs4" style="width: 100%;"  name="prefixes" id="prefixes"  required >
<option value="" disable="true" >===Select prefixes===</option>
@php if($users->prefixes == 'Mr.')  {$selected_mr = "selected='true'"; }   else if($users->prefixes == 'Ms.')  {$selected_ms="selected='true'"; }   else if($users->prefixes == 'Mrs.') { $selected_mrs="selected='true'";}  @endphp
<option {{ @$selected_mr }}value="Mr." >Mr.</option>
<option {{ @$selected_ms }} value="Ms." >Ms.</option>
<option {{ @$selected_mrs  }}value="Mrs." >Mrs.</option>
</select> -->
                                                        <br>

                                                        <div class="form-group">
                                                            <input list="prefexes" id="prefixes" value="{{ $users->prefixes }}" class="form-control" style="width: 100%;" name="prefixes">
                                                            <datalist id="prefexes">
    <option   value="" selecetd="true">  </option>
    <!-- <option value="0" disable="true" selected="true">===Select Prefix===</option> -->
            <option value="Mr." >Mr.</option>
            <option value="Ms." >Ms.</option>
            <option value="Mrs." >Mrs.</option>
    </datalist>

                                                        </div>


                                                        <strong>First Name:</strong>
                                                        <!-- {!! Form::text('first_name',null , array('placeholder' => 'First Name','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" onkeyup="AllowonlyText(event,'first_name')" value="{{ $users->first_name }}" id="first_name" name="first_name" required Placeholder="First Name" />
                                                        <br>
                                                        <strong>Middle Name:</strong>
                                                        <!-- {!! Form::text('middle_name',null , array('placeholder' => 'Middle Name','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" value="{{ $users->middle_name }}" onkeyup="AllowonlyText(event,'middle_name')" id="middle_name" name="middle_name" required Placeholder="Middle Name" />
                                                        <br>
                                                        <strong>Last Name:</strong>
                                                        <!-- {!! Form::text('last_name',null , array('placeholder' => 'Last Name','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" value="{{ $users->last_name }}" id="last_name" onkeyup="AllowonlyText(event,'last_name')" name="last_name" required Placeholder="Last Name" />
                                                        <br>

                                                        <strong>Position:</strong>
                                                        <!-- {!! Form::text('last_name',null , array('placeholder' => 'Last Name','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" value="{{ $users->position }}" id="Position" onkeyup="AllowonlyText(event,'position')" name="position" required Placeholder="Position" />
                                                        <br>

                                                        <strong>Country:</strong>
                                                        <!-- {!! Form::select('country_id',$countries_id,$countries, array('class' => 'form-control')) !!} -->
<select disabled class="form-control select2bs4" style="width: 100%;" name="country_id" id="country_id" required>
<option value="0" disable="true" >===Select Country===</option>
@foreach ($countries as $key => $value)
@if($users->country_id == $value->id)
<option selected ='true' value="{{ $value->id }}">{{  $value->country_name  }}  </option>
@else
<option value="{{ $value->id }}">{{  $value->country_name  }}  </option>
@endif
@endforeach
</select>
                                                        <br>
                                                        <strong>City:</strong>
                                                        <!-- {!! Form::text('city',null , array('placeholder' => 'city','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" value="{{ $users->city }}" id="city" onkeyup="AllowonlyText(event,'city')" name="city" required Placeholder="City" />
                                                        <br>
                                                        <strong>Street :</strong>
                                                        <!-- {!! Form::text('street',null , array('placeholder' => 'Street','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" value="{{ $users->street }}" id="street" name="street" required Placeholder="Street" />
                                                        <br>
                                                        <strong>Postal Address :</strong>
                                                        <input readonly class='form-control' type="text" value="{{ $users->postal_code }}" id="postal_code" name="postal_code" required Placeholder="Postal Address" />
                                                        <br>

                                                        <strong>Phone Number:</strong>
                                                        <!-- {!! Form::text('telephone',null , array('placeholder' => 'telephone','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" value="{{ $users->telephone }}" id="telephone" name="telephone" required Placeholder="Phone Number" />
                                                        <br>
                                                        <strong>Fax:</strong>
                                                        <input readonly class='form-control' type="text" value="{{ $users->fax }}" id="fax" name="fax" Placeholder="Fax" />
                                                        <br>
                                                        <strong>Website:</strong>
                                                        <!-- {!! Form::text('website_url',null , array('placeholder' => 'website url','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="url" value="{{ $users->website_url }}" id="website_url" name="website_url" Placeholder="Website Url" />
                                                        <br>
                                                        <div class="form-group">
                                                            <strong>Institutional Email:</strong>
                                                            <!-- {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!} -->
                                                            <input readonly class='form-control' type="email" value="{{ $users->email }}" id="email" name="email" required Placeholder="Institutional Email" />
                                                        </div>
                                                        <br>
                                                        <strong>Business Address :</strong>
                                                        <!-- {!! Form::text('business_address',null , array('placeholder' => 'email','class' => 'form-control')) !!} -->
                                                        <input readonly class='form-control' type="text" value="{{ $users->business_address }}" id="business_address" name="business_address" required Placeholder="Business Address" />
                                                        <br>

                                                        <div class="form-group">
                                                            <hr>

                                                            <strong>User Name:</strong>
                                                            <!-- {!! Form::text('user_name',null , array('readonly','placeholder' => 'user name','class' => 'form-control')) !!} -->
                                                            <input required class='form-control' type="text" value="{{ $users->user_name }}" id="user_name" name="user_name" required Placeholder="User Name" />

                                                            <strong>Password:</strong>
                                                            <div id="pass_success" class="alert alert-success btn-sm" style="display:none"></div>
                                                            <div id="pass_response_danger" class="alert alert-danger btn-sm" style="display:none"></div>
                                                            <div id="pass_response_warning" class="alert alert-warning btn-sm" style="display:none"></div>
                                                            <!-- {!! Form::password('password', array( 'id'=>'password','placeholder' =>  'Password','class' => 'form-control','onkeyup'=>'check_strength_password(this.value)')) !!} -->
                                                            <input class='form-control' type="password" value="" id="password" onkeyup="check_strength_password_up(this.value)" name="password" Placeholder="Password" />
                                                        </div>


                                                        <div class="form-group">
                                                            <strong>Confirm Password:</strong>
                                                            <!-- {!! Form::password('confirm-password',array('id'=>'confirm_password','placeholder' => 'Confirm Password','class' =>  'form-control','onkeyup'=>'check_strength_password_confirm(password.value,this.value)')) !!} -->
                                                            <input class='form-control' type="password" value="" id="confirm_password" onkeyup="check_strength_password_confirm_up(password.value,this.value)" name="confirm_password" Placeholder="Confirm Password" />


                                                        </div>

                                                        <strong>Profile Picture :</strong>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input onchange="filechangevalidation_pic(this.value,'upload_avatar','submit_Save')" type="file" class="custom-file-input" id="upload_avatar" name="file">
                                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>

                                                            </div>
                                                            <p id="error1" style="display:none; color:#FF0000;">
                                                                Invalid File Format! File format Must Be JPG, PNG, ICO, GIF,JPEG.
                                                            </p>
                                                            <p id="error2" style="display:none; color:#FF0000;">
                                                                Maximum File Size Limit is 10MB.
                                                            </p>

                                                            <p id="error3" style="display:none; color:#FF0000;">
                                                                Current selected file size is <span id="file_size"></span>MB
                                                            </p>
                                                            <p>
                                                                <!-- <div class="input-group-append">
<span class="input-group-text">Upload</span>
</div> -->
                                                                <div class="col-sm-6 pull-right">
                                                                    <img title="profile picture" width="50" height="50" id="preview-image" src="{{ $users->avatar_path }} " alt="preview image" style="max-height: 250px;">
                                                                </div>
                                                        </div>
                                                        <br>

                                                        <div class="card-footer">
                                                            <button type="submit" id="submit_Save" class="btn btn-success">Submit</button>
                                                        </div>

                                                    </div>
                                                    <!-- {!! Form::close() !!} -->
                                                    @endforeach
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


</section>

<!-- jQuery -->
<!-- <script src="../../plugins/jquery/jquery.min.js"></script> -->
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="../../dist/js/demo.js"></script> -->
<script>
    $('#upload_avatar').change(function() {

        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);

    });



    function check_strength_password_up(password) {


        document.getElementById('confirm_password').value = "";


        if (password != '') {

            $.ajax({
                data: {
                    password: password,
                },
                url: "{{ route('check_password') }}",

                type: "GET",
                dataType: 'json',
                success: function(data) {
                    if (data.Message == true) {

                        jQuery('#pass_response_warning').hide('100');
                        jQuery('#submit_Save').show('100');
                        jQuery('#pass_success').show('100');
                        document.getElementById('pass_success').innerHTML = data.result;
                        document.getElementById('pass_response_danger').style.display = "none";


                    } else if (data.Message == false) {

                        document.getElementById('submit_Save').disabled = false;
                        jQuery('#submit_Save').hide('100');
                        jQuery('#pass_success').hide('100');
                        jQuery('#pass_response_warning').show('100');
                        document.getElementById('pass_response_warning').innerHTML = data.result;


                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }


            });
        } else

        {

            document.getElementById('pass_success').style.display = "none";
            document.getElementById('pass_response_warning').style.display = "none";
            document.getElementById('pass_response_danger').style.display = "none";
        }

    }





    function check_strength_password_confirm_up(password, confirm_password)

    {

        if (confirm_password != '') {

            $.ajax({
                data: {
                    password: confirm_password,
                },
                url: "{{   route('check_password')   }}",
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    if (data.Message == true) {


                        var id = setInterval(delay_refresh_page, 6000);

                        function delay_refresh_page() {
                            jQuery('#pass_response_warning').hide('100');
                            jQuery('#submit_Save').show('100');
                            jQuery('#pass_success').show('100');
                            jQuery('#pass_response_danger').hide('100');
                            document.getElementById('pass_success').innerHTML = data.result;
                            clearInterval(id);;

                        }

                        delay_refresh_page();






                        if (confirm_password != password) {



                            var id = setInterval(delay_refresh_page, 6000);

                            function delay_refresh_page() {
                                jQuery('#pass_response_warning').hide('100');
                                jQuery('#submit_Save').hide('100');
                                jQuery('#pass_success').hide('100');
                                jQuery('#pass_response_danger').show('100');
                                document.getElementById('pass_response_danger').innerHTML = "Password do not Match!";
                                clearInterval(id);;

                            }

                            delay_refresh_page();

                        }


                    } else if (data.Message == false) {

                        var id = setInterval(delay_refresh_page, 6000);

                        function delay_refresh_page() {
                            jQuery('#submit_Save').hide('100');
                            jQuery('#pass_success').hide('100');
                            jQuery('#pass_response_warning').show('100');
                            jQuery('#pass_response_danger').hide('100');
                            document.getElementById('pass_response_warning').innerHTML = data.result;
                            clearInterval(id);;

                        }
                        delay_refresh_page();
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }


            });
        } else

        {

            document.getElementById('pass_success').style.display = "none";
            document.getElementById('pass_response_warning').style.display = "none";
        }

    }
</script>




@include('layouts.modal_upload_cv') @include('layouts.custom_supplier_js') @endsection