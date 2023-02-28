<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div  class="modal fade" id="ajax_model_acknowledgment_letter_psur" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <!-- <div class="modal-content"> -->
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>  &nbsp;
<a href="" 
data-toggle="tooltip" 
id="download_acknowledgment_letter"
title="Download generated acknowledgment letter"  
data-original-title="Download" 
class="edit btn btn-success btn-sm">
<i class='fas fa-download'></i> 
</a>
                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="ACKForm"     method="POST" name="ACKForm"  action="javascript:void(0)"  accept-charset="utf-8" class="form-horizontal"  >

<!-- <form id="ACKForm"    enctype="multipart/form-data"  action="{{ route('upload_file_acknowledgement') }}"  method="POST" name="ACKForm" class="form-horizontal"  > -->
              @csrf
              
              <input  type="hidden" id="applicant_number" name="application_number" required  class="form-control"  value="">    
              <input  type="hidden" id="applicant_id" name="application_id" required  class="form-control"  value="{{  @$application->application_id }}">    

              <input  type="hidden" id="psur_reference_number_hidden" name="psur_reference_number_hidden" required  class="form-control"  value="">    


              <input  type="hidden" id="applicant_user_id" name="applicant_user_id" required  class="form-control"  value="{{ @$application->user_id  }}">    
              <input  type="hidden" id="supervisor_id" name="supervisor_id" required  class="form-control"  value="{{ Auth::user()->id  }}">    

              <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="form-group">
                        <label class="">Applicant Name</label>
                        <div class="col-sm-12">
                  <input id="app_name" name="app_name" required  class="form-control"  type="text" readonly >   
                        </div>
                        </div>

                        <div class="form-group">
                        <label > Sealed Acknowledgement Letter </label>
                        <div class="col-sm-12">
                        <input onchange="filechangevalidation(this.value,'receipt_file','UploadData')"  id="receipt_file" type="file" name="file" required placeholder="File" class="form-control" >
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


                        </div>
                   
  <div class="col-md-offset-2 col-sm-10">
<button style="display:block"  type="submit" class="btn btn-primary"  title="Upload Data" 
 id="UploadData" value="create"><i class="fas fa-cloud-upload"> </i>Upload
                     </button>
                   
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
                  <tbody id="table_upload_acknowledgement_letter_psur">
                  <th colspan="4" style="text-align:center"> No Data </th>
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

  $('body').on('click', '.upload_acknowledgment_letter_psur', function () {

      var contact_person_name= $(this).data('contact_person_name');
      var psur_refrence_number  = $(this).data('psur_refrence_number');
      var application_number = $(this).data('application_number');
      var applicant_id = $(this).data('application_id');

      $('#app_name').val(contact_person_name.toUpperCase().trim());
      $('#psur_reference_number_hidden').val(psur_refrence_number);
      $('#applicant_id').val(applicant_id);
      $('#applicant_number').val(application_number);

    


      var application_number = $('#applicant_number').val();
      var applicant_user_id =  $('#applicant_user_id').val();
      var supervisor_id =  $('#supervisor_id').val();


$.ajax({
type:'POST',
url: "{{ route('fetch_uploaded_acknowledgement_letter_if_any_psur') }}",
data: {
    application_number :  application_number,
    applicant_user_id  :  applicant_user_id,
    supervisor_id     :  supervisor_id ,
    psur_refrence_number :psur_refrence_number ,

},

success: (data) => {

        if(data.Message==true)  
           {

        document.getElementById('table_upload_acknowledgement_letter_psur').innerHTML = data.Data_returned;
        $('#modelHeading').html("Upload Acknowledgement Letter PSUR");
        $('#ajax_model_acknowledgment_letter_psur').modal('show');
        $('#download_acknowledgment_letter').attr("href", data.Download_Link);

           } 

           else
           {
           // this.reset();

var contact_person_name= $(this).data('contact_person_name');
var psur_refrence_number  = $(this).data('psur_refrence_number');
$('#app_name').val(contact_person_name.toUpperCase().trim());



Toastr();
toastr.error('Allowed Files Type is only .PDF (PDF Document)')

           }


},
error: function(data){
console.log(data);
}
});











        });
 



$('#upload_acknowledgment_letter_psurr').click(function () {
        //alert("hellow Eyoba");
        var contact_person  = document.getElementById('contact_person_name').innerHTML;
        $('#app_name').val(contact_person.toUpperCase().trim());
         //document.getElementById('app_name').readonly = true;
      
      var application_number = $('#applicant_number').val();
      var applicant_user_id =  $('#applicant_user_id').val();
      var supervisor_id =  $('#supervisor_id').val();
     


//var formData = new FormData(this);

$.ajax({
type:'POST',
url: "{{ route('fetch_uploaded_acknowledgement_letter_if_any_psur') }}",
data: {
    application_number :  application_number,
    applicant_user_id  :  applicant_user_id,
    supervisor_id     :  supervisor_id ,

},

success: (data) => {

        if(data.Message==true)  
           {

        document.getElementById('table_upload_acknowledgement_letter_psur').innerHTML = data.Data_returned;
        $('#modelHeading').html("Upload Acknowledgement Letter PSUR");
        $('#ajax_model_acknowledgment_letter_psur').modal('show');

           } 

           else
           {
           // this.reset();
$('#UploadData').html('Uploading Letter...');
document.getElementById('UploadData').disabled = false;
var contact_person  = document.getElementById('contact_person_name').innerHTML;
$('#app_name').val(contact_person.toUpperCase().trim());

Toastr();
toastr.error('Allowed Files Type is only .PDF (PDF Document)')

           }


},
error: function(data){
console.log(data);
}
});

});


    $('body').on('click', '.editReceipt', function () {
      var invoice_number = $(this).data('id');
          $('#modelHeading').html("Import Receipt Information");
          $('#saveBtn').html("Import");
          $('#ajaxModel').modal('show');
          $('#invoice_number').val(invoice_number);
        });
 




 $('#ACKForm').submit(function(e) {
e.preventDefault();
console.log(e.preventDefault());



document.getElementById('UploadData').disabled = true;
$('#UploadData').html('Uploading Letter......');



var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ url('upload_file_acknowledgement_psur')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {

        if(data.Message==true)  
           {

//this.reset();
document.getElementById('table_upload_acknowledgement_letter_psur').innerHTML = data.Data_returned;
Toastr();
toastr.success("AcknowLedgment Letter Uploaded Successfully")

$('#UploadData').html('Upload');
document.getElementById('UploadData').disabled = false;


 $('#ajax_model_acknowledgment_letter_psur').modal('hide');


           } 

           else
{

this.reset();
$('#UploadData').html('Uploading Letter...');
document.getElementById('UploadData').disabled = false;
var contact_person  = document.getElementById('contact_person_name').innerHTML;
$('#app_name').val(contact_person.toUpperCase().trim());
Toastr();
toastr.error('Allowed Files Type is only .PDF (PDF Document)')

}
},
error: function(data){
console.log(data);
}
});



});





$('body').on('click', '.deleteFile_psur', function () {

var application_id = $(this).data("id");
var document_id =    $(this).data("document_id");
var psur_reference_number = $(this).data("psur_reference_number");


{if(confirm("Are You sure You Want To Delete This File")){}else{return false;}}
$.ajax({
    type: "DELETE",
    data:{
      application_id:application_id,
      document_id : document_id,
      psur_reference_number:psur_reference_number,
    },
    url: "{{ route('delete_file_uploaded_acknowledgment_letter_pusr.remove') }}",
    success: function (data) {
       // table.draw();
       Toastr();
       toastr.error("Acknowledgment letter for PSUR  Deleted Successfully")

       $('#table_upload_acknowledgement_letter_psur').html(data.Data_returned);
       $('#UploadData').html("Upload");
       //document.getElementById('table_upload_invoice_letter').innerHTML = data.Data_returned;

    },
    error: function (data) {
        console.log('Error:', data);
    }
});


});






























  });
</script>




