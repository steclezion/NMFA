<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>



<div  class="modal fade" id="ajax_model_upload_acknowledgment_receipt" aria-hidden="true" data-backdrop="static" tabindex="-1">
<div class="modal-dialog modal-lg">
<input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
<!-- <div class="modal-content"> -->
<div class="modal-content bg-default">
<div class="modal-header">

<h4 class="modal-title" id="modelHeading_upload_acknowledgment_receipt"></h4>
&nbsp;
<a  href="" style="display:block"   title="Acknowledgment receipt of Application Registration "  class="btn btn-warning" id="Download_File" >   <i class="fas fa-download">   </i></a>

<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
</div>
<div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="UploadACKForm"     method="POST" name="UploadACKForm"    accept-charset="utf-8" class="form-horizontal"  >
@csrf
<input  type="hidden" id="user_id" name="user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">   
<input  type="hidden" id="app_id" name="app_id" required  class="form-control"  >   

<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<div class="form-group">
<p class="text-muted"> <input type="file" name="file_ACK"  required  id="receipts" class="form-control" onchange="filechangevalidation(this.value,'receipts','UploadData')"   placeholder="File"  /> </p> 
</div>

<p id="error1" style="display:none; color:#FF0000;">
Invalid File Format! File format  Must Be PDF, EPUB, OXPS.
</p>
<p id="error2" style="display:none; color:#FF0000;">
Maximum File Size Limit is 10MB.
</p>

<p id="error3" style="display:none; color:#FF0000;">
Current selected file size is <span id="file_size"></span>MB
</p>
<p>


 <!-- Area to display the percent of progress -->
 
 <div id='percent'></div>
 
 <!-- area to display a message after completion of upload --> 
 <div id='status'></div>



<div class="col-md-offset-2 col-sm-10">
<button style="display:block"  type="submit" class="btn btn-primary"  id="UploadData" value="create">Upload File</button>



<br>

                    </div>
                    
                </form>
            </div>

            

                   <table  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>File Name</th>
                    <th>Uploaded Date </th>
                    <th>Delete </th>
                
                  </tr>
                  </thead>
                  <tbody id="table_upload_ack">
                   </tbody>
                  <tfoot>
                
                  
                  </tfoot>
                </table>

        </div>
    </div>
</div>




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

//alert('hellow');


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
var  document_received_types = document.getElementById('document_received_types').value;
var  dvd_received = document.getElementById('dvd_received').value ; 
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
if(document_received_types == ''){ document.getElementById('document_received_types').focus(); $('#document_received_types').css("background-color", "skyblue"); return false; }
if(dvd_received == ''){            document.getElementById('dvd_received').focus();  $('#dvd_received').css("background-color", "skyblue");   return false;}



document.getElementById('saveBtnn').disabled= false;
document.getElementById('saveBtnn').innerHTML= 'Saving....';

var formData = new FormData(this);

$.ajax({
type:'POST',

data: {
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




window.location = " {{ route('receipts.received')  }}";


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

});












  $('#upload_File').click(function (e) {
      e.preventDefault();
     
var date_of_letter = document.getElementById('date_of_letter').value;
var document_received_types = document.getElementById('document_received_types').value;
var dvd_received = document.getElementById('dvd_received').value ; 
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

