@extends('layouts.app_app')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- start: Css -->

  <!-- -->

<link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" >
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!--<link rel="stylesheet" href="{{ asset('3.3.6/bootstrap.min.css')}}" >-->
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
    <!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}" >

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">

<!-- plugins -->
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->

<!-- <script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('dist/js/demo.js')}}" ></script> -->
<!-- Content Wrapper. Contains page content -->
<!-- Content Header (Page header) -->
    <!-- MultiStep Form -->


    
@include('layouts.custom_supplier_js')

<div class="container-fluid" id="grad1">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-md-5 col-md-7 col-lg-10 text-center p-0 mt-3 mb-2">
        <div  class="alert alert-success align-content-sm-center" id="app_id" >Application Number == {{ $application_number}}</div>
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <h2><strong>Dossier  Submission
                 </strong>
                 <i  style="color:blue;cursor:pointer;font-size:15px;" 
           title="Dossier submission
Instruction: You can submit your electronic dossiers by one of the following ways: 
1. Online submission
For dossiers you intend to submit online, please provide the link indicating where the dossier is located. 
2. Email
For dossiers you intend to send via Email, you can submit the dossiers to er.peru.nmfa@gmail.com. 
3. Electronic media delivery
Alternatively, you can send an electronic copy of your dossier using one of the following media:
•	CD or DVD
•	USB flash drive
•	USB external hard drive
The label for each unit of media should include the following minimum information:
•	Applicant name
•	Product name
•	Name of the dossier
•	Total number of CDs/DVDs, USB flash drives or USB hard drives
4. Paper submissions
If you cannot submit your dossier by online submission, email or electronic media delivery, you may submit a single hard copy of the dossier. 
Please visit the Eritrean Medicines Registration Guideline for information on the documentation to be submitted for the registration of pharmaceutical products in Eritrea. 
"
           class="fas fa-info-circle fa-0">  
           </i>
                 </h2>
            <!-- <p>Fill all form fields to go to the next step</p> -->
                <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform">
                            <!-- progressbar -->
                            <ul id="progressbar">
                           <!-- <li  class="active" id="Application_Type"><strong>Application Type</strong></li>
                           <li class="active" id="dossier_sample" clas="fas fa-supple"><strong> Dossier Link and Sample</strong></li> -->
                            <!-- <li id="confirm"><strong>Finish</strong></li> -->
                              
                            </ul> <!-- fieldsets -->
                                 <!------------------------------   -->
                                 <!---------------------------------------------------------------------------------------------------------   -->

<input class="form-control" id="generated_application_id" type="hidden" name="Application_ID"  value="{{ $application_id}}" />
         
<Fieldset>
        
         <div class="form-card">
         <h2 class="fs-title">Submit dossier through</h2>

         <div class="col-sm-3 no-print">
         <label>Mail </label> 
         <input  name="flag_dossier_url"   value="0"  id="flag_dossier_url_express" class="form-control"  type="hidden"  >

         <input style="width:20px;position:float-center;" class="form-control" id="DHL" type="radio" name="customradio1"   /> 
         
         <label style="display:none"  id="express_settings_name_label">Express Name        </label> 
         <input id="express_settings_name" style="display:none"  placeholder ="Eg. DHL" 
          style="width:20px;position:float:left;" class="form-control"  type="text" name="expresss_setting"  /> 
   
        </div>


          <div class="col-sm-3 no-print">
         <label >Link         </label> 
         <input style="width:20px;position:float-center;" class="form-control" id="LINK" type="radio"  />
   
        </div>

<div class="container">
<div class="row">
    <div class="col-sm-3">         
            <label  id="lable_dossier_link" style="display:none" >Dossier Link</label>  
    </div>

  

    <div class="col-sm-9">
    
<div id="cs_response_website_url"></div>
<div id="cs_response_website_url_danger" class="alert alert-danger" style="display:none"></div>
<div id="cs_response_website_url_warning" class="alert alert-warning" style="display:none"></div>
<div id="cs_response_website_url_success" class="alert alert-success" style="display:none"></div>

<input  style="display:none"class="form-control" id="dossier_id" type="url" name="dossier_id"   Placeholder="Please write the link where dossier files are located" onkeyup="valdiate_url(this.value,'cs_response_website_url','{{url('/Validate/url/customer_supply')}}','dossier_sample_save')" />
<input  name="flag_dossier_url"   value="1"  id="flag_dossier_url" class="form-control"  type="hidden"  >

    </div>
    <div class="col-sm-3">
        <label id="sample" style="display:none" ></label>  
        </div>
        <div class="col-sm-9">
            <!-- <input style="display:none" style="width:20px;position:float:left;" class="form-control" id="sample_status" type="checkbox" name="sample_status"  /> -->
          </div>
           </div>

    
    <span  id="dossier_sample_id"  style="display:none"> 
    <!-- <input type="button" class="save action-buttonn" value="Save"    id="dossier_sample_save"       ></span> -->
    <button   type="button" class="btn btn-success" value="Save"    id="dossier_sample_save"   > <i class="fas fa-saveS">  </i> Save </button>

    <span  id="dossier_update"  style="display:none"> 
    <!-- <input type="button" class="save action-update" value="Update"    id="dossier_sample_update"       ></span> -->
    <button     type="button" class="btn btn-success" value="Update"    id="dossier_sample_update"  > <i class="fas fa-edit">  </i> Update </button>

</div>
</div>

<!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
<input type="button" name="next" class="next action-button" value="Next Step"   id="next_dossier_Status"   style="displa:none"/> -->
</fieldset>
                          

<!---------------  ------------------------------------------------------------

<fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully Signed Up</h5>
                                            </div> 
     <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
    <input type="button" name="make_payment" class="next action-button" value="Confirm" />


                                        </div>
                                    </div>
                                </div>
                            </fieldset>-->
                          
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0
}

html {
    height: 100%
}

#grad1 {
    background-color: : #9C27B0;
    /* background-color: rgba(240, 226, 227, 0.815); */
   /*background-image: linear-gradient(120deg, #FF4081, #81D4FA)*/
}

#msform {
    text-align: center;
    position: relative;
    margin-top: 20px
}

#msform fieldset .form-card {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    padding: 20px 40px 30px 40px;
    box-sizing: border-box;
    width: 94%;
    margin: 0 3% 20px 3%;
    position: relative
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform fieldset .form-card {
    text-align: left;
    color: #9E9E9E
}

#msform input,
#msform textarea {
    padding: 0px 8px 4px 8px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: none;
    font-weight: bold;
    border-bottom: 2px solid skyblue;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: blue;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 2px;
    /* cursor: pointer;*/
    padding: 10px 5px;
    margin: 10px 5px;
    /*display:none*/;
}

#msform .action-update {
    width: 100px;
    background: rgba(250, 171, 0, 0.979);
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    /* cursor: pointer;*/
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-buttonn {
    width: 100px;
    background: rgba(12, 218, 12, 0.925);
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
     cursor: pointer
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button:hover,
#msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
}

select.list-dt {
    border: none;
    outline: 0;
    border-bottom: 1px solid #ccc;
    padding: 2px 5px 3px 5px;
    margin: 2px
}

select.list-dt:focus {
    border-bottom: 2px solid skyblue
}

.card {
    z-index: 0;
    border: none;
    border-radius: 0.5rem;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #2C3E50;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #000000
}

#progressbar li {
    list-style-type: none;
    font-size: 12px;
    width:10%;
    float: left;
    position: relative
}

#progressbar #supplier:before {
    font-family: FontAwesome;
    content: "\f023"
}

#progressbar #Agent:before {
    font-family: FontAwesome;
    content: "\f007"
}

#progressbar #Application_Type:before {
    font-family: FontAwesome;
    content: "\f09d"
}

#progressbar #product_details:before {
    font-family: FontAwesome;
    content: "\f3f9"
}

#progressbar #product_composition:before {
    font-family: FontAwesome;
    content: "\f3f9"
}

#progressbar #product_manufacturers:before {
    font-family: FontAwesome;
    content: "\f3f9"
}

#progressbar #product_manufacturers_api:before {
    font-family: FontAwesome;
    content: "\f3f9"
}

#progressbar #dossier_sample:before {
    font-family: FontAwesome;
    content: "\f3f9"
}


#progressbar #decleration:before {
    font-family: FontAwesome;
    content: "\f3f9"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 18px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: blue
}

.radio-group {
    position: relative;
    margin-bottom: 25px
}

.radio {
    display: inline-block;
    width: 204;
    height: 104;
    border-radius: 0;
    background: lightblue;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    cursor: pointer;
    margin: 8px 2px
}

.radio:hover {
    box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
}

.radio.selected {
    box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
}

.fit-image {
    width: 100%;
    object-fit: cover
}
</style>

<script>
$(document).ready(function(){

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

$(".next").click(function(){

current_fs = $(this).parent();
next_fs = $(this).parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({'opacity': opacity});
},
duration: 600
});
});

$(".previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 600
});
});

$('.radio-group .radio').click(function(){
$(this).parent().find('.radio').removeClass('selected');
$(this).addClass('selected');
});

$(".submit").click(function(){
return false;
})

});

</script>


<script>

$(document).ready(function(){
var  app_new_application = document.getElementById('app_new_application');
var  app_renewal_application = document.getElementById('app_renewal_application');
var  new_application_mode = document.getElementById('new_application_mode');
var  app_renew_new_application_mode = document.getElementById('app_renew_new_application_mode');

var generated_value = document.getElementById('generated_application_id').value;
  


var  app_variations = document.getElementById('app_variations');
var  track_mode = document.getElementById('app_fast_track_mode');

//App New  Applications
 $("#app_new_application").click(function(){
    if((app_renewal_application.checked==true  ))
{  
    if(generated_value == 0){} else {return false;}
    $("#app_renew_new_application_mode").hide(); 
    $("#new_application_mode").show(); 
     app_renewal_application.checked=false;
     $('#appicaiton_save').hide();

}
else
{
    $("#app_renew_new_application_mode").hide(); 
    $("#new_application_mode").show(); 
     app_renewal_application.checked=false;
     $('#appicaiton_save').hide();


}
    
    });



 $("#app_renewal_application").click(function(){
    if((app_new_application.checked == true  ))
{if(generated_value == 0){} else {return false;}
    $("#new_application_mode").hide(); 
    $("#app_renew_new_application_mode").show(); 
    app_new_application.checked = false;
    $('#appicaiton_save').hide();

}
else  
{   if(generated_value == 0){} else {return false;}
    $("#new_application_mode").hide(); 
    $("#app_renew_new_application_mode").show(); 
    app_new_application.checked = false;
    $('#appicaiton_save').hide();

}
    
    });

/*

//check Track mode from the Application new track mode Select Box
    $("#app_fast_track_mode").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
   
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
  
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    
    });

    ////check Track mode from the Application new track mode Select Box
    $("#app_variations").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
  
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
 
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    
    });

       ////check Track mode from the Application new track mode Select Box
       $("#app_renewal_application ").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
    //$('#appicaiton_save').show();
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
    //$('#appicaiton_save').show();
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    
    });

          ////check Track mode from the Application new track mode Select Box
          $("#app_new_application ").click(function(){
if((app_new_application.checked==true || app_variations.checked==true || app_renewal_application.checked==true  ))
{ 
    $("#app_select_mode").hide();  
    generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
else if(track_mode.checked == true )
{ 
    $("#app_select_mode").show(); 
   // $('#save_application_info').show();
     generated_application_id = document.getElementById('generated_application_id').value;
    if (generated_application_id == 0) {
        $('#appicaiton_save').show()
    }
}
    });



*/
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
 $('#appicaiton_save').show()

  
}
</script>


  


@endsection