@extends('layouts.app')
@section('content')  

<!-- check_list_js.blade.php -->

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" >
  <!-- CodeMirror -->
  <link rel="stylesheet" href="{{ asset('plugins/codemirror/codemirror.css') }} ">
  <link rel="stylesheet" href="{{ asset('plugins/codemirror/theme/monokai.css') }}">
  <!-- SimpleMDE -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
 
<!--   
plugins -->
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href={{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}>



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

<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->
<script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<!-- <script src="{{ asset('dist/js/demo.js')}}" ></script> -->




<div class="row" id="ajaxModel_receipt_receive" >
          <div class="col-12">
<!-- Content Wrapper. Contains page content -->
  <div class="content">
    <!-- Content Header (Page header) -->
    @foreach($check_list as $checked) @endforeach
    <a href="{{  route('check_list.index') }}"
    <span type="button" class="btn btn-primary float-md-right">
                     <i class="fas fa-arrow-alt-circle-left"></i> Back 
                  </span>
                  </a>
                  <div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h6>Acknowledgement  Receipt of Registration </h6>
          
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12" id="letter_acknowledgement">
<form method="POST" enctype="multipart/form-data" id="UploadAcknowledgmenet_Receipt"     method="POST" name="UploadAcknowledgmenet_Receipt"    accept-charset="utf-8" class="form-horizontal"  >
@csrf   
          <textarea id="summernote">

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>


                <div class="container">
                  <img src="../../../images/nmfa_header.png"  class="img-responsive" style="width: 100%;height: auto;"  alt="image" height="140" width="800"/>
                </div>

                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
  <div class="row invoice-info" >
  <input type="hidden"  id="application_id" value="{{ $check_list[0]->application_id}}" />
<div class="container" id="template_redesigned" > 
<p class="list-group">
  <div class="panel panel-default">

  <div class="panel-heading"  >Date: <span id="current_date"> @php $t=time(); echo date("d-m-Y",$t); @endphp</span> </div>
  <br/>
  <div class="panel-body"  >Ref: <span id="RL_squential_number">   {{ $squential_Reference_number }}       </span></div>
  <br/>
  <div class="panel-body">
 To: <span id="applicant_name"> {{ $check_list[0]->trade_name }}  </span>  <br/>
 <ul>
    <li> <span id="state_plot_number"> {{ $check_list[0]->address_line_one.' '.$check_list[0]->address_line_two }}  </span> </li> <br/>
    <!-- <li> <span id="country">  </span> </li>  </br> -->
    <li> <span id="region_state"> {{$check_list[0]->mstate }}</span>  </li>    </br> 
  </ul>
</div>
</div>
  </p>
 
<style>
p,block {
    text-align: justify;
}


</style>
     
<b>    Subject: Acknowledgement of Receipt of Registration Application   </b> 
<br/><br/>
<block style="text-align: justify;">
<p>Dear Sir/Madam or  <span id="contact_person_name"> {{ $check_list[0]->fullname_contact }}</span>,</p>
This is to acknowledge receipt of your application for registration of a medicine in reference to your 

letter dated   <span id="change_after_creation">  <input type="date" min="0" width="10" hieght="10"   id="date_of_letter" /> </span> .<br>

The application number for the below product is  <b> <span id="application_number"> {{ $check_list[0]->application_number }}  </span> </b>.

<br><br>
<p>
Product Name: <span id='p_n'> {{ $check_list[0]->product_name.' '.$dosage_forms[0]->name.' , '.$check_list[0]->product_trade_name }}  </span>
<br><br>
Documents received:
</p>

<ul>

<span>  [list out the received document types in bullets]   </span>


</ul>
<br>
<p>   No. of DVDs received: 

</block>
<br/><br/>
<p> Best regards,  </p>

Iyassu Bahta
Director,
<br>
 National Medicines and Food Administration
<br>
Ministry of Health
<br>
Asmara, Eritrea
<br> <br><br>
</div>

<br><br><br>
    <p>
<br><br>
<p>
<img src="../../../images/nmfa_footer.png"  alt="image" style="width: 100%;height: auto;"/>       
</p>        
</div>    
</div>
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>

 </textarea>
            </div>
          
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
           
      </div><!-- /.container-fluid -->
      </div><!-- /.col -->
        </div><!-- /.row -->
           
      </div><!-- /.container-fluid -->


      <!-- this row will not appear when printing -->
      <div class="row no-print">
                <div class="col-12" >
   @if($path =='')
   <button type="submit" class="btn btn-primary float-md-left" style="margin-left: 15px;"  title="save acknowledgment receipt letter of registration" id="saveBtnn"> <i class="fas fa-save"></i> Save </button>
                
   <div id="actions_to_applicant"  style="float-right;display:none"> 
                  <a href=""  id="get_path" rel="noopener" target="_blank" class="btn btn-warning"><i class="fas fa-download"></i> Download </a>
                  &nbsp;  &nbsp;  &nbsp;

                  <button type="button" class="btn btn-info float-right"   id="upload_acknowledgment_letter">
                  <i class="fas fa-upload"></i> Send To Applicant  ( {{ $checked->first_name}} {{ $checked->middle_name}} {{ $checked->last_name}}  )
                  </button>
@else



  <div id="actions_to_applicant"  style="float-right;display:block"> 
                  <a href="{{ $path }}"  id="get_path" rel="noopener" target="_blank" class="btn btn-warning"><i class="fas fa-download"></i> Download </a>
                  &nbsp;  &nbsp;  &nbsp;

  <button type="button" id="upload_acknowledgment_letter" class="btn btn-info"> <i class="fas fa-upload"></i> Send To Applicant ( {{ $checked->first_name}} {{ $checked->middle_name}} {{ $checked->last_name}}
                  </button>


  @endif


   </form>

    </div>
                  <!-- <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button> -->
                </div>
                <br><br>
    </section>
    <!-- /.content -->
  </div>


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

@include('layouts.acknowledgement_list_js')

@include('layouts.modal_upload_acknowledgment_letter')


<script>
  $(function () {
    // Summernote
     $('#summernote').summernote();

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  })
</script>






















<script type="text/javascript">


$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  });

function Toastr()

{
        toastr.options.closeButton = true;
        toastr.options.timeOut = 10000; // How long (in milisec) the toast will display without user interaction
        toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it
        toastr.options.progressBar = true;
}


$('#UploadACKForm').submit(function(e) 
{
e.preventDefault();
console.log(e.preventDefault());


$('#UploadData').html('Uploading Acknowledegment Receipt Letter......');
document.getElementById('UploadData').disabled = true;


var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ route('receipts.upload_to_applicant')  }}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {
if(data.Message==true)  
{
this.reset();
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 6000
  }); 


$('#ajax_model_upload_acknowledgment_receipt').modal('hide');

Toastr();
toastr.success("Acknowledgment receipt for registration application uploaded successfully ")

$('#UploadData').html('Save changes');
$('#UploadData').html('Upload Acknowledgment Receipt');
document.getElementById('UploadData').disabled = false;
document.getElementById('table_upload_ack').innerHTML = data.Data_returned;
} 
else
{
  
this.reset();



$('#UploadData').html('Save changes');
document.getElementById('UploadData').disabled = false;
Toastr();
toastr.error('Allowed Files Type is only .PDF (PDF Document)')

         }
},

error: function(data){
console.log(data);
}

});

});





$('body').on('click', '.receipt_register', function () {
   

        var application_id = $(this).data('id');     
        $('#application_id').val(application_id );
        $('#applicant_name').html( $(this).data('contact_person'));
        $('#contact_person_name').html( $(this).data('contact_person'));
        $('#modelHeading_re').html('Acknowledgement of Receipt of Registration Application');
        $('#saveBtn').val("save");
        $('#applicantt_id').val(application_id);
        $('#RL_squential_number').html( $(this).data('sequence_number'));
        $('#state_plot_number').html( $(this).data('street_plot_number'));
        $('#region_state').html( $(this).data('region_state'));
        $('#p_n').html( $(this).data('p_n'));
        $('#application_number').html( $(this).data('application_number'));
        $('#ajaxModel_receipt_receive').modal('show');


});




$('body').on('click', '.upload_receipt_register', function () {
   
//receipts.retrive_file_uploaded_to_applicant

   var application_id = $(this).data('id');     
   var path = $(this).data('path');

   var return_data = $(this).data('return_data');

   $('#app_id').val(application_id);
  // $('#table_upload_ack').html(return_data);
   $('#Download_File').attr("href", path);
   $('#modelHeading_upload_acknowledgment_receipt').html("Acknowledgement Receipt of Registration");




$.ajax({
          type: "POST",
          data:{
            application_id:application_id,
            
          },
          url: "{{ route('receipts.retrive_file_uploaded_to_applicant') }}",
          success: function (data) {
             // table.draw();

  //this.reset();
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: true,
    timer: 5000
  }); 
  
//  Toast.error("Uploaded Document Deleted Successfully")


             $('#table_upload_ack').html(data.return_data);


          },
          error: function (data) {
              console.log('Error:', data);
          }
      });
 

 
   $('#ajax_model_upload_acknowledgment_receipt').modal('show');

   
});


































$('#UploadAcknowledgmenet_Receipt').submit(function(e) 
{
e.preventDefault();
console.log(e.preventDefault());

var  date_of_letter = document.getElementById('date_of_letter').value;
 


var  application_id = document.getElementById('application_id').value; 
var  current_date = document.getElementById('current_date').innerHTML ; 
var  RL_squential_number    = document.getElementById('RL_squential_number').innerHTML; 
var  applicant_name    = document.getElementById('applicant_name').innerHTML; 
var  region_state   = document.getElementById('region_state').innerHTML; 
var  contact_person_name   = document.getElementById('contact_person_name').innerHTML ; 
var  application_number  = document.getElementById('application_number').innerHTML ; 
var  p_n = document.getElementById('p_n').innerHTML; 



$("#ajaxModel_receipt_receive").click(function(){
  $('#date_of_letter').css("background-color", "#ffffff");
  $('#document_received_types').css("background-color", "#ffffff");
  $('#dvd_received').css("background-color", "#ffffff");
  
});


if(date_of_letter == ''){          document.getElementById('date_of_letter').focus();   $('#date_of_letter').css("background-color", "skyblue"); return false;}
// if(document_received_types == ''){ document.getElementById('document_received_types').focus(); $('#document_received_types').css("background-color", "skyblue"); return false; }
// if(dvd_received == ''){            document.getElementById('dvd_received').focus();  $('#dvd_received').css("background-color", "skyblue");   return false;}


if (confirm("Are you sure you want to save this letter  Acknowledgement of Receipt of Registration Application."+
             "Actions will not be reverted.") == true) 
              {

document.getElementById('saveBtnn').disabled= true;
document.getElementById('saveBtnn').innerHTML= 'Saving....';
document.getElementById('change_after_creation').innerHTML  = date_of_letter;
var template_redesigned = document.getElementById('template_redesigned').innerHTML;

var formData = new FormData(this);

$.ajax({
type:'POST',

data: {
p_n:p_n,
application_number:application_number,
contact_person_name:contact_person_name,
region_state:region_state,
application_id:application_id,
date_of_letter:date_of_letter,
current_date:current_date,
RL_squential_number:RL_squential_number,
applicant_name:applicant_name,
template_redesigned:template_redesigned,
},
url: "{{ route('receipts.upload_acknowledgment_receipt')  }}",
dataType: 'json',
success: (data) => {
if(data.Message==true)  
{
///this.reset();
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000
  }); 
  Toastr();
  toastr.success("Acknowledgment Receipt of Registration Application Saved Successfully")






document.getElementById('saveBtnn').style.display= 'none';
//$('#Download_File').show('100');
//$('#upload_File').show('100');
//$('#name_contact').html(contact_person_name);
//$('#Download_File').attr("href", data.Download_Link);




window.location = "{{ route('check_list.index') }}";


//document.getElementById('table_upload_cv').innerHTML = data.Data_returned;


} 
else
{
  
//this.reset();
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 6000
  }); 



$('#saveBtnn').html('Saving Information......');
document.getElementById('saveBtnn').disabled = false;
// var contact_person  = document.getElementById('contact_person_name').innerHTML;
// $('#app_name').val(contact_person.toUpperCase().trim());

Toastr();
toastr.error(data.Message.item)

         }
},

error: function(data){
console.log(data);
}

});



}

else { return false;}




});












  $('#upload_File').click(function (e) {
      e.preventDefault();
     
var date_of_letter = document.getElementById('date_of_letter').value;
var document_received_types = document.getElementById('document_received_types').innerHTML;
var dvd_received = document.getElementById('dvd_received').innerHTML; 
var application_id = document.getElementById('application_id').value; 
var current_date = document.getElementById('current_date').innerHTML ; 
var  RL_squential_number    = document.getElementById('RL_squential_number').innerHTML; 
var applicant_name    = document.getElementById('applicant_name').innerHTML; 
var  region_state   = document.getElementById('region_state').innerHTML; 
var  contact_person_name   = document.getElementById('contact_person_name').innerHTML ; 
var  application_number  = document.getElementById('application_number').innerHTML ; 
var  p_n = document.getElementById('p_n').innerHTML; 
  
  $.ajax({
        url: "{{ route('receipts.upload_to_applicant') }}",
        type: "POST",
        data:
        {
p_n:p_n,
application_number:application_number,
contact_person_name:contact_person_name,
region_state:region_state,
dvd_received :dvd_received ,
application_id:application_id,
date_of_letter:date_of_letter,
current_date:current_date,
RL_squential_number:RL_squential_number,
document_received_types:document_received_types,
applicant_name:applicant_name,

        },
        success: function (data) {
          if( data.Message == true )
          {
           
             
    var Toast = Swal.mixin({
    toast: true,
    position: 'top-center',
    showConfirmButton: false,
    timer: 6000
     });

     
document.getElementById('upload_File').disabled = true;

 Toastr();
  toastr.success("Acknowledgement Receipt of Registration Uploaded Successfully")
  $('#ajax_model_upload_acknowledgment_receipt').modal('hide');

          }

        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }
    });

  });




  $('body').on('click', '.deleteFile', function () {

      var application_id = $(this).data("id");
      var document_id = $(this).data("document_id");

      {if(confirm("Are You sure You Want To Delete This File")){}else{return false;}}
      $.ajax({
          type: "DELETE",
          data:{
            application_id:application_id,
            document_id : document_id,
          },
          url: "{{ route('reciept.acknowledgment_receipt.remove') }}",
          success: function (data) {
             // table.draw();
             $('#table_upload_ack').html(data.return_data);


          },
          error: function (data) {
              console.log('Error:', data);
          }
      });
  });

});
</script>




<script>
$(function () {
  // Summernote
  $('#document_received_typess').summernote();
  // $('#summernotee').summernote();
  // $('#summernote_Remark_section_four').summernote();

  // CodeMirror
  // CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
  //   mode: "htmlmixed",
  //   theme: "monokai"
  // });
})
</script>


@endsection