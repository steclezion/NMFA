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

  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">


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
<!-- <script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script> -->
<script rel="javascript" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->

<script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- <script src="{{ asset('dist/js/demo.js')}}" ></script> -->

<script>


</script>
<!-- Content Wrapper. Contains page content -->
<!-- Content Header (Page header) -->
    <!-- MultiStep Form -->



  <br>
  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-edit"></i>
                  Report Form/ Applications received By
                </h3>
              </div>
              <div class="card-body">
                <button type="button" class="edit btn btn-default btn-md Year"  data-toggle="tooltip"  data-id="1" data-original-title="Edit"  >
                 Year  
                </button>
            <button type="button" class="btn btn-default btn-md Product" data-toggle="modal" data-target="#modal-modal-default">
                 product
                </button>
                <button type="button" class="btn btn-default btn-md Applicant" data-toggle="modal" data-target="#modal-modal-default">
                 Applicants
                </button>  
               
                <button type="button"  class="btn btn-default btn-md Country_Address" data-toggle="modal" data-target="#modal-lg">
                Company country Address
                </button>
               
                <button type="button"  class="btn btn-default btn-md application_type" data-toggle="modal" data-target="#modal-lg">
                Type of application
                </button>

                   <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                   Route of registration
                </button>

                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                Local Agent
                </button>
<br />  <br />
                 <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                 Number of applications received which passed vs failed preliminary screening
                </button>
              
              </div>
              <!-- /.card -->
            </div>


           
          <!-- /.col -->
        </div>
        <!-- ./row -->
      </div><!-- /.container-fluid -->




             <div class="card">
              <!-- <div class="card-header">
                <h3 class="card-title">Application Status</h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body" style="display:block" id="rendered_year_response">
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->





     </div>


          <script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>




<!--  Modal Generation Form --------------------------------------------------------------------------------->


      <!-- Creating a model for the Year  Report  -->
      <div class="modal fade" id="ajaxModel_year" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="YearReportForm" name="YearReportForm" class="form-horizontal">
                   <input type="hidden" name="application_id" id="application_id">

                     <!-- Date range -->
                <div class="form-group">
                  <label>Date range:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation">
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->

                        <div class="form-group">
                       

                        <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="customCheckbox1" >
                          <label for="customCheckbox1" class="custom-control-label">Completed Application</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="customCheckbox2" >
                          <label for="customCheckbox2" class="custom-control-label">Processing Application</label>
                        </div>
                      
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="customCheckbox4" >
                          <label for="customCheckbox4" class="custom-control-label">Incomplete Application</label>
                        </div>


                         <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="customCheckbox5" >
                          <label for="customCheckbox5" class="custom-control-label">All Application</label>
                        </div>
                        </div>
                  


         

<p id="demo"></p>
                    <div class="col-md-offset-2 col-sm-10">
                     <button style="display:block"  type="submit" class="btn btn-primary" id="saveBtn_Year" value="create">Proceed
                     </button>
                   
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

</div>



<!-- Modal Report Application   Received By Product-->


<!--  Modal Generation Form  -->


      <!-- Creating a model for the product Report  -->
      <div class="modal fade" id="ajaxModel_product" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading_product"></h4>
            </div>
            <div class="modal-body">
                <form id="ProductReportForm" name="ProductReportForm" class="form-horizontal">
                   <input type="hidden" name="application_id" id="applicant">
                       <div class="form-group">
                       <div class="input-group mb-3">
                       <select class="form-control select2" style="width: 100%;" name="generic_id" id="generic_id"  required >
                                    <option value="0" disable="true" selected="true">=== Select Product Name ===</option>
                                    @foreach ($applicants as  $key => $value)
                                   <option value="{{ $value->gen_id }}">{{ $value->pro_name}}</option>
                                    @endforeach
                                    </select>
                                    </div>
                        <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input  type="checkbox" id="customCheckboxx1" >
                          <label for="customCheckboxx1" >Completed Application</label>
                        </div>
                        <div  class="custom-control custom-checkbox">
                          <input  type="checkbox" id="customCheckboxx2" >
                          <label   for="customCheckboxx2"  >Processing Application</label>
                        </div>
                      
                        <div class="custom-control custom-checkbox">
                          <input  type="checkbox" id="customCheckboxx4" >
                          <label for="customCheckboxx4"  >Incomplete Application</label>
                       </div>

                         <div class="custom-control custom-checkbox">
                          <input  type="checkbox" id="customCheckboxx5" >
                          <label for="customCheckboxx5" >All Application</label>

                        </div>
                        </div>
                  


         

<p id="demo"></p>
                    <div class="col-md-offset-2 col-sm-10">
                     <button style="display:block"  type="submit" class="btn btn-primary" id="saveBtn_Product" value="create">Proceed
                     </button>
                   
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>





<!-- Modal Report Application   Received By Applicant-->


<!--  Modal Generation Form  -->


      <!-- Creating a model for the product Report  -->
      <div class="modal fade" id="ajaxModel_Applicant" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading_applicant"></h4>
            </div>
            <div class="modal-body">
                <form id="ApplicantReportForm" name="ApplicantReportForm" class="form-horizontal">
                   <input type="hidden" name="application_id" id="applicant">
                           <!-- Date range -->
                <div class="form-group">
                  <label>Date range:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation_applicant">
                  </div>
                  <!-- /.input group -->
                </div>

                 <div class="input-group mb-6">
                 <label>Applicant Name:</label>
                       <select class="form-control select2" style="width: 100%;height:50px;"  name="user_id_app" id="user_id_app"  required >
                                    <option value="0" disable="true" selected="true">Select Applicant Name</option>
                                    @foreach ($users as  $key => $value)
                                   <option value="{{ $value->id }}">{{ $value->first_name." ".$value->middle_name." ".$value->last_name}}</option>
                                    @endforeach
                                    <option value="0" disable="false" selected="false">All Applicant</option>
                                    </select>
                                    </div>
                <!-- /.form group -->
                       
                  

<p id="demo"></p>
                    <div class="col-md-offset-2 col-md-10">
                     <button style="display:block"  type="submit" class="btn btn-primary" id="saveBtn_Applicant" value="proceed">Proceed
                     </button>
                   
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>



 <!-- Creating a model for the Country Report  -->
 <div class="modal fade" id="ajaxModel_Country_Address" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading_Country_Address"></h4>
            </div>
            <div class="modal-body">
                <form id="Country_AddressReportForm" name="Country_AddressReportForm" class="form-horizontal">
                  
                   <label>Date range:</label>

<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">
      <i class="far fa-calendar-alt"></i>
    </span>
  </div>
  <input type="text" class="form-control float-right" id="reservation_country_address">
</div>
<!-- /.input group -->

<p id="demo"></p>
                    <div class="col-md-offset-2 col-sm-10">
                     <button style="display:block"  type="submit" class="btn btn-primary" id="saveBtn_country_address" value="counry_address">Proceed
                     </button>
                   
                    </div>
</div>
                        
                 


                    
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>



<!-- Creating a model for the Applicant Type Report   -->
<div class="modal fade" id="ajaxModel_applicant_type" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading_applicant_type"></h4>
            </div>
            <div class="modal-body">
                <form id="applicant_type_form_report" name="applicant_type_form_report" class="form-horizontal">
                  
                   <label>Type of Applications:</label>

<div class="input-group">

 <select class="form-control select2" style="width: 100%;height:50px;"  name="track_mode" id="track_mode"  required >
    <option value="0" disable="true" selected="true">  </option>
    <option value="1_"> Standard Mode </option>
  
    @foreach ($fast_track_applications as $key => $value)
    <option value="2_{{ $value->name }}">Fast Track Mode {{ $value->name }}</option>
    @endforeach
   </select>
<br><br>


<p id="demo"></p>
                    <div class="col-md-offset-2 col-sm-10">
                     <button style="display:block"  type="submit" class="btn btn-primary" id="saveBtn_applicant_type" value="saveBtn_applicant_type">Proceed
                     </button>
                   
                    </div>
</div>
                        
                 


                    
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>




























    </section>
    <!-- /.content -->
  </div>


  <script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

     $('#saveBtn_Year').click(function (e) {
        e.preventDefault();
        $(this).html('Proceeding');
         var Date_range = document.getElementById('reservation').value;
         var form_date = Date_range.split('-');
         var From_Date = form_date[0];
         var To_Date = form_date[1];

         var Get_Value_From_Date=form_date[0].split('/');
         var From_Date_Month = Get_Value_From_Date[0];
         var From_Date_Day = Get_Value_From_Date[1];
         var From_Date_Year =Get_Value_From_Date[2];

      
        var  From_Date = form_date[1];
        var  Get_Value_To_Date =form_date[1].split('/');
        var To_Date_Month = Get_Value_To_Date[0];
        var To_Date_Day = Get_Value_To_Date[1];
        var To_Date_Year =Get_Value_To_Date[2];


if(document.getElementById('customCheckbox1').checked == true) {  var application_Stat = 'completed'; }
if(document.getElementById('customCheckbox2').checked== true) {   var application_Stat = 'processing'; }
if(document.getElementById('customCheckbox4').checked  == true){  var application_Stat = 'incomplete'; }
if(document.getElementById('customCheckbox5').checked  == true){  var application_Stat = 'all'; }
if(document.getElementById('customCheckbox1').checked  == false && document.getElementById('customCheckbox2').checked  == false &&  document.getElementById('customCheckbox4').checked  == false && document.getElementById('customCheckbox5').checked  == false )

{document.getElementById('customCheckbox1').focus();document.getElementById('customCheckbox2').focus();document.getElementById('customCheckbox4').focus();document.getElementById('customCheckbox5').focus();  return false; }



        $.ajax({
        //   data: $('#YearReportForm').serialize(),
        data:{ 
              application_Stat:application_Stat,
               From_Date_Day:From_Date_Day,
               From_Date_Month:From_Date_Month,
               From_Date_Year:From_Date_Year,
               from_date:From_Date,
               To_Date_Day:To_Date_Day,
               To_Date_Month:To_Date_Month,
               To_Date_Year:To_Date_Year,
               to_date:To_Date,
               //application_id:application_id,
                },
          url: "{{ route('Year.Generate_Year') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
   
              $('#YearReportForm').trigger("reset");
              $('#ajaxModel_year').modal('hide');
              document.getElementById('rendered_year_response').style.display = 'block';
              document.getElementById('rendered_year_response').innerHTML = data.rendered_card;

              //table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });''
   
 

     $('#saveBtn_Product').click(function (e) {
        e.preventDefault();
        $(this).html('Proceeding');
        if(document.getElementById('generic_id').value == '' || document.getElementById('generic_id').value==0){
          document.getElementById('generic_id').focus();
          document.getElementById('generic_id').style.color='green';
          return false;
        }

if(document.getElementById('customCheckboxx1').checked  == false && document.getElementById('customCheckboxx2').checked  == false &&  document.getElementById('customCheckboxx4').checked  == false && document.getElementById('customCheckboxx5').checked  == false )
{ 

document.getElementById('customCheckboxx1').checked = true;
document.getElementById('customCheckboxx1').focus();
document.getElementById('customCheckboxx2').focus();
document.getElementById('customCheckboxx4').focus();
document.getElementById('customCheckboxx5').focus();  

return true; 
}

if(document.getElementById('customCheckboxx1').checked == true) {  var application_Stat = 'completed'; }
if(document.getElementById('customCheckboxx2').checked== true) {   var application_Stat = 'processing'; }
if(document.getElementById('customCheckboxx4').checked  == true){  var application_Stat = 'incomplete'; }
if(document.getElementById('customCheckboxx5').checked  == true){  var application_Stat = 'all'; }

var medicine_id = document.getElementById('generic_id').value;


        $.ajax({
        //   data: $('#YearReportForm').serialize(),
        data:{ 
              application_Stat:application_Stat,
               medicine_id:medicine_id,
             
                },
          url: "{{ route('Product.Generate_Product_Report') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
   
              $('#ProductReportForm').trigger("reset");
              $('#ajaxModel_product').modal('hide');
              document.getElementById('rendered_year_response').style.display = 'block';
              document.getElementById('generic_id').value='';
              document.getElementById('generic_id').selected='';
              document.getElementById('rendered_year_response').innerHTML = data.rendered_card;

              //table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });





 $('body').on('click', '.application_type', function () {
     // var application_id = $(this).data('id');
     
     
          $('#modelHeading_applicant_type').html("Applications Received  By Applicant Type");
          $('#saveBtn_applicant_type').val("Proceed");
          $('#ajaxModel_applicant_type').modal('show');
          document.getElementById('rendered_template').innerHTML = "";

   });



 $('body').on('click', '.Year', function () {
     // var application_id = $(this).data('id');
     
     
          $('#modelHeading_year').html("Applications Received  By Year");
          $('#saveBtn').val("edit-book");
          $('#applicantt_id').val(application_id);
          $('#ajaxModel_year').modal('show');
          $('#application_id').val(application_id);
         document.getElementById('rendered_template').innerHTML = "";

   });


 $('body').on('click', '.Applicant', function () {
     // var application_id = $(this).data('id');
     
     
          $('#modelHeading_applicant').html("Applications Received  By Applicant");
          $('#saveBtn').val("edit-book");
          $('#applicantt_id').val(application_id);
          $('#ajaxModel_Applicant').modal('show');
          $('#application_id').val(application_id);
         document.getElementById('rendered_template').innerHTML = "";

   });



    $('body').on('click', '.Country_Address', function () {
     // var application_id = $(this).data('id');
     
     
          $('#modelHeading_Country_Address').html("Applications Received  By Country Address");
          $('#saveBtn').val("edit-book");
          $('#applicantt_id').val(application_id);
          $('#ajaxModel_Country_Address').modal('show');
          $('#application_id').val(application_id);
         document.getElementById('rendered_template').innerHTML = "";

   });



    $('body').on('click', '.Product', function () {
     
         $('#modelHeading_product').html("Applications Received  By Product");
          $('#applicantt_id').val(application_id);
          $('#ajaxModel_product').modal('show');
         

   });


  $('#customCheckbox1').click(function () {
var complete = document.getElementById('customCheckbox1').checked;
var processing = document.getElementById('customCheckbox2').checked;
var incomplete = document.getElementById('customCheckbox4').checked;

var all_application = document.getElementById('customCheckbox5').checked;
if(document.getElementById('customCheckbox1').checked == true) { document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if(document.getElementById('customCheckbox2').checked== true) {document.getElementById('customCheckbox1').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if( document.getElementById('customCheckbox4').checked  == true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox1').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if( document.getElementById('customCheckbox5').checked== true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox1').checked=false;}
});

  $('#customCheckbox2').click(function () {
var complete = document.getElementById('customCheckbox1').checked;
var processing = document.getElementById('customCheckbox2').checked;
var incomplete = document.getElementById('customCheckbox4').checked;
var all_application = document.getElementById('customCheckbox5').checked;
// if(document.getElementById('customCheckbox1').checked == true) { document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
if(document.getElementById('customCheckbox2').checked== true) {document.getElementById('customCheckbox1').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if( document.getElementById('customCheckbox4').checked  == true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox1').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if( document.getElementById('customCheckbox5').checked== true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox1').checked=false;}
});

  $('#customCheckbox4').click(function () {
var complete = document.getElementById('customCheckbox1').checked;
var processing = document.getElementById('customCheckbox2').checked;
var incomplete = document.getElementById('customCheckbox4').checked;
var all_application = document.getElementById('customCheckbox5').checked;
// if(document.getElementById('customCheckbox1').checked == true) { document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if(document.getElementById('customCheckbox2').checked== true) {document.getElementById('customCheckbox1').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
 if( document.getElementById('customCheckbox4').checked  == true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox1').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if( document.getElementById('customCheckbox5').checked== true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox1').checked=false;}
});


  $('#customCheckbox5').click(function () {
var complete = document.getElementById('customCheckbox1').checked;
var processing = document.getElementById('customCheckbox2').checked;
var incomplete = document.getElementById('customCheckbox4').checked;
var all_application = document.getElementById('customCheckbox5').checked;
// if(document.getElementById('customCheckbox1').checked == true) { document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if(document.getElementById('customCheckbox2').checked== true) {document.getElementById('customCheckbox1').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox5').checked=false;}
// if( document.getElementById('customCheckbox4').checked  == true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox1').checked=false;document.getElementById('customCheckbox5').checked=false;}
if( document.getElementById('customCheckbox5').checked== true) {document.getElementById('customCheckbox2').checked=false; document.getElementById('customCheckbox4').checked=false;document.getElementById('customCheckbox1').checked=false;}
});








 $('#customCheckboxx1').click(function () {
var complete = document.getElementById('customCheckboxx1').checked;
var processing = document.getElementById('customCheckboxx2').checked;
var incomplete = document.getElementById('customCheckboxx4').checked;

var all_application = document.getElementById('customCheckboxx5').checked;
if(document.getElementById('customCheckboxx1').checked == true) { document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if(document.getElementById('customCheckboxx2').checked== true) {document.getElementById('customCheckboxx1').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if( document.getElementById('customCheckboxx4').checked  == true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx1').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if( document.getElementById('customCheckboxx5').checked== true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx1').checked=false;}
});

  $('#customCheckboxx2').click(function () {
var complete = document.getElementById('customCheckboxx1').checked;
var processing = document.getElementById('customCheckboxx2').checked;
var incomplete = document.getElementById('customCheckboxx4').checked;
var all_application = document.getElementById('customCheckboxx5').checked;
// if(document.getElementById('customCheckboxx1').checked == true) { document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
if(document.getElementById('customCheckboxx2').checked== true) {document.getElementById('customCheckboxx1').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if( document.getElementById('customCheckboxx4').checked  == true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx1').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if( document.getElementById('customCheckboxx5').checked== true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx1').checked=false;}
});

  $('#customCheckboxx4').click(function () {
var complete = document.getElementById('customCheckboxx1').checked;
var processing = document.getElementById('customCheckboxx2').checked;
var incomplete = document.getElementById('customCheckboxx4').checked;
var all_application = document.getElementById('customCheckboxx5').checked;
// if(document.getElementById('customCheckboxx1').checked == true) { document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if(document.getElementById('customCheckboxx2').checked== true) {document.getElementById('customCheckboxx1').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
 if( document.getElementById('customCheckboxx4').checked  == true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx1').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if( document.getElementById('customCheckboxx5').checked== true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx1').checked=false;}
});


  $('#customCheckboxx5').click(function () {
var complete = document.getElementById('customCheckboxx1').checked;
var processing = document.getElementById('customCheckboxx2').checked;
var incomplete = document.getElementById('customCheckboxx4').checked;
var all_application = document.getElementById('customCheckboxx5').checked;
// if(document.getElementById('customCheckboxx1').checked == true) { document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if(document.getElementById('customCheckboxx2').checked== true) {document.getElementById('customCheckboxx1').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx5').checked=false;}
// if( document.getElementById('customCheckboxx4').checked  == true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx1').checked=false;document.getElementById('customCheckboxx5').checked=false;}
if( document.getElementById('customCheckboxx5').checked== true) {document.getElementById('customCheckboxx2').checked=false; document.getElementById('customCheckboxx4').checked=false;document.getElementById('customCheckboxx1').checked=false;}
});










    

$('#saveBtn_Applicant').click(function (e) {
   e.preventDefault();
   $(this).html('Proceeding');

         var Date_range = document.getElementById('reservation_applicant').value;
         var user_id_app = document.getElementById('user_id_app').value;
         var form_date = Date_range.split('-');
         var From_Date = form_date[0];
         var To_Date = form_date[1];

         var Get_Value_From_Date=form_date[0].split('/');
         var From_Date_Month = Get_Value_From_Date[0];
         var From_Date_Day = Get_Value_From_Date[1];
         var From_Date_Year =Get_Value_From_Date[2];

      
        var  From_Date = form_date[1];
        var  Get_Value_To_Date =form_date[1].split('/');
        var To_Date_Month = Get_Value_To_Date[0];
        var To_Date_Day = Get_Value_To_Date[1];
        var To_Date_Year =Get_Value_To_Date[2];


   $.ajax({
   //   data: $('#YearReportForm').serialize(),
   data:{ 
               From_Date_Day:From_Date_Day,
               From_Date_Month:From_Date_Month,
               From_Date_Year:From_Date_Year,
               from_date:From_Date,
               To_Date_Day:To_Date_Day,
               To_Date_Month:To_Date_Month,
               To_Date_Year:To_Date_Year,
               to_date:To_Date,
               user_id:user_id_app,
        
           },
     url: "{{ route('Applicant.Generate_Applicant_Report') }}",
     type: "POST",
     dataType: 'json',
     success: function (data) {

         $('#ApplicantReportForm').trigger("reset");
         $('#ajaxModel_Applicant').modal('hide');
         document.getElementById('rendered_year_response').style.display = 'block';
         document.getElementById('rendered_year_response').innerHTML = data.rendered_card;

         //table.draw();
    
     },
     error: function (data) {
         console.log('Error:', data);
         $('#saveBtn').html('Save Changes');
     }
 });
});



  
                  


$('#saveBtn_country_address').click(function (e) {
   e.preventDefault();
   $(this).html('Proceeding');

         var Date_range = document.getElementById('reservation_country_address').value;
         var form_date = Date_range.split('-');
         var From_Date = form_date[0];
         var To_Date = form_date[1];

         var Get_Value_From_Date=form_date[0].split('/');
         var From_Date_Month = Get_Value_From_Date[0];
         var From_Date_Day = Get_Value_From_Date[1];
         var From_Date_Year =Get_Value_From_Date[2];

      
        var  From_Date = form_date[1];
        var  Get_Value_To_Date =form_date[1].split('/');
        var To_Date_Month = Get_Value_To_Date[0];
        var To_Date_Day = Get_Value_To_Date[1];
        var To_Date_Year =Get_Value_To_Date[2];


   $.ajax({
   //   data: $('#YearReportForm').serialize(),
   data:{ 
               From_Date_Day:From_Date_Day,
               From_Date_Month:From_Date_Month,
               From_Date_Year:From_Date_Year,
               from_date:From_Date,
               To_Date_Day:To_Date_Day,
               To_Date_Month:To_Date_Month,
               To_Date_Year:To_Date_Year,
               to_date:To_Date,
           
        
           },
     url: "{{ route('Country.Generate_Address_Report') }}",
     type: "POST",
     dataType: 'json',
     success: function (data) {

         $('#Country_AddressReportForm').trigger("reset");
         $('#ajaxModel_Country_Address').modal('hide');
         document.getElementById('rendered_year_response').style.display = 'block';
         document.getElementById('rendered_year_response').innerHTML = data.rendered_card;

         //table.draw();
    
     },
     error: function (data) {
         console.log('Error:', data);
         e.preventDefault();
        $(this).html('Proceeding');
         $('#saveBtn_country_address').html('Proceeding...');
     }
 });
});






$('#saveBtn_applicant_type').click(function (e) {
   e.preventDefault();
   $(this).html('Proceeding');

       
    var fast_track_details =document.getElementById('track_mode').value;
      var fast_data=fast_track_details.split('_');
       fast_track_details  =  fast_data[1];
       application_type = fast_data[0]; 


   $.ajax({
   //   data: $('#YearReportForm').serialize(),
   data:{ 
               application_type:application_type,
               fast_track_details:fast_track_details,
       },

     url: "{{ route('Applicant_Type.Generate_Applicant_Report') }}",
     type: "POST",
     dataType: 'json',
     success: function (data) {

         $('#applicant_type_form_report').trigger("reset");
         $('#ajaxModel_applicant_type').modal('hide');
         $('#saveBtn_country_address').html('Proceed');
         document.getElementById('rendered_year_response').style.display = 'block';
         document.getElementById('rendered_year_response').innerHTML = data.rendered_card;

         //table.draw();

    
     },
     error: function (data) {
         console.log('Error:', data);
         e.preventDefault();
        $(this).html('Proceeding');
         //$('#saveBtn_country_address').html('Proceeding...');
     }
 });
});














































     $('#createNewBook').click(function () {
        //alert("hellow Eyoba");
        $('#saveBtn').val("create-book");
        $('#application_id').val('');
        $('#bookForm').trigger("reset");
        $('#modelHeading').html("Generate Invoice");
        $('#ajaxModel_year').modal('show');
    });

  });


</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
 
 
 //Custom
 $('#datemask3').inputmask('yyyy-dd-mm', { 'placeholder': 'yyyy-mm-dd' })

 
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker  
    $('#reservationdate').datetimepicker({
        format: 'L'
    });


    

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()


     //Date applicant_date_range
     $('#reservation_applicant').daterangepicker()

       //Date applicant_date_range
       $('#reservation_country_address').daterangepicker()

     
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
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
        startDate: moment().subtract(129, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End
</script>







  @include('layouts.custom_supplier_js')



    
    <!--<script rel="stylesheet" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>-->
<!-- <script rel="stylesheet" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script> -->
<script rel="stylesheet" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="stylesheet" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="stylesheet" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>


<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js')  }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- BS-Stepper -->
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- dropzonejs -->
<script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
@endsection