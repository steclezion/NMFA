<!-- <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script> -->


<div  class="modal fade" id="ajax_model_upload_alert_notification" aria-hidden="true" data-backdrop="static" tabindex="-1">
<div class="modal-dialog modal-lg">
<input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />

<!-- <div class="modal-content"> -->
<div class="modal-content bg-default">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading_upload_alert_notification"></h4>

<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
</div>
<div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="UploadNMFADIRECTORForm"     method="POST" name="UploadNMFADIRECTORForm"    accept-charset="utf-8"  class="form-horizontal"  >
@csrf

<input  type="hidden" id="user_id" name="user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">   
<input type="hidden" value="" id="application_id" name="application_id" />

<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<!-- <p class="text-muted"> <input type="file" name="file"  required   class="form-control"   placeholder="File"  /> </p>  -->
<input onchange="filechangevalidation(this.value,'upload_file_psur','UploadData')"  type="file" name="file"   required class="form-control" id="upload_file_psur">
<p id="error1" style="display:none; color:#FF0000;">
Invalid File Format! File format  Must Be pdf.
</p>
<p id="error2" style="display:none; color:#FF0000;">
Maximum File Size Limit is 10MB.
</p>

<p id="error3" style="display:none; color:#FF0000;">
Current selected file size is <span id="file_size"></span>MB
</p>
<p>


<div class="col-md-offset-2 col-sm-10">
<label class="form-control-md"> Applicant </label>
<input type="checkbox" class="form-control-md" id="Applicant"  name="Applicant" />

&nbsp;&nbsp;&nbsp;

<label class="form-control-md"> Supervisor </label>
<input type="checkbox" class="form-control-md" id="Supervisor" name="Supervisor" />

<div>
<div class="col-md-offset-2 col-sm-10">
<button style="display:block"  type="submit" class="btn btn-primary"  title="Upload alert file"  id="UploadData" value="create"><i class="fas fa-upload"> </i>Upload</button>
<br>
</div>
</div>
</form>

            </div>

                   <table  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <!-- <th> PSUR reference  </th> -->
                    <th>NMFA Director Alert File</th>
                    <th>File Sent To</th>
                    <th>Uploaded Date </th>
                    <th>Delete </th>
                
                  </tr>
                  </thead>
                  <tbody id="table_upload_alert_notification">
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


    $('body').on('click', '.uploadnmfa_file', function () {
      var application_id = $(this).data('id');
      var application_number = $(this).data('app_number');
      
      $('#application_id').val(application_id);
      $('#modelHeading_upload_alert_notification').html("Upload Alert Notifications");
      $('#ajax_model_upload_alert_notification').modal('show');
      $('#application_id').val(application_id);


      
     var user_id = document.getElementById('user_id').value;


$.ajax({
url: "{{ route('fetch_alert_uploaded_files_nmfa')  }}",
type:'POST',
data: {
user_id:user_id,
application_id:application_id,
},
processData: true,
success: (data) => {
if(data.Message==true)  
{
document.getElementById('table_upload_alert_notification').innerHTML = data.Data_returned;
}
else
{

this.reset();
var Toast = Swal.mixin({
 toast: true,
 position: 'top-end',
 showConfirmButton: false,
 timer: 6000
}); 


$('#UploadData').html('Save changes');
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


    
    $('#modal_upload_alert_notification').click(function () {
        // alert('hellow');
       
        $('#modelHeading_upload_alert_notification').html("Upload Alerts");
        $('#ajax_model_upload_alert_notification').modal('show');



     var user_id = document.getElementById('user_id').value;


$.ajax({
url: "{{ route('fetch_alert_uploaded_files_nmfa')  }}",
type:'POST',
data: {
user_id:user_id,
},
processData: true,
success: (data) => {
if(data.Message==true)  
{
document.getElementById('table_upload_alert_notification').innerHTML = data.Data_returned;
}
else
{

this.reset();
var Toast = Swal.mixin({
 toast: true,
 position: 'top-end',
 showConfirmButton: false,
 timer: 6000
}); 


$('#UploadData').html('Save changes');
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




  });
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



$('#UploadNMFADIRECTORForm').submit(function(e) 
{
e.preventDefault();

//console.log(e.preventDefault());

if(document.getElementById('Applicant').checked ==  false && document.getElementById('Supervisor').checked ==  false)
 {
    document.getElementById('Applicant').focus();
    Toastr(); toastr.warning("To proceed uploading choose an option from the checkboxes!")
     return false;
    
 }

// if(document.getElementById('Applicant').checked ==   true) { let Choise = {Applicant:true, Supervisor:false};}
// else if (document.getElementById('Supervisor').checked ==   true){ let Choise = {Applicant:false, Supervisor:true}; }



$('#UploadData').html('Uploading alert notification......');
//document.getElementById('UploadData').disabled = true;

var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ route('upload_alert_nmfa_director_file')  }}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {
if(data.Message==true)  
{
 this.reset();
Toastr();
toastr.success("NMFA Director alert file has been uploaded successfully.")

 $('#UploadData').html('Save changes');
 $('#UploadData').html('Upload alert notifications');
 document.getElementById('UploadData').disabled = false;
 document.getElementById('table_upload_alert_notification').innerHTML = data.Data_returned;
} 
else
{
    
this.reset();
 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    }); 


$('#UploadData').html('Save changes');
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


//deletequery  di
$('body').on('click', '.deletequery', function () {

var document_id = $(this).data('id');
var nmfa_id = $(this).data('nmfa_id');
var application_id = document.getElementById('application_id').value;


//alert(application_id);

if (confirm("Are sure  you want to remove this file??") == true)
               { 
                   

$.ajax({
    data:{
   document_id:document_id,
   application_id:application_id,
   nmfa_id:nmfa_id,
    },

type:'POST',
dataType: 'json',
url: "{{ route('delete_file_data_uploaded_nmfa_director')  }}",

success: (data) => { 


 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    }); 

Toastr();
    toastr.error('File Deleted!')
    document.getElementById('table_upload_alert_notification').innerHTML = data.Data_returned;
},

error: function(data){
console.log(data);
}

});
        
        
         } 
             
             
              else { return false;}

                

      



});





  });
</script>




