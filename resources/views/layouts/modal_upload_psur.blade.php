<div  class="modal fade" id="ajax_model_upload_psur" aria-hidden="true" data-backdrop="static" tabindex="-1">
<div class="modal-dialog modal-lg">
<input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />

<!-- <div class="modal-content"> -->
<div class="modal-content bg-default">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading_upload_psur"></h4>

<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
</div>
<div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="UploadCVForm"     method="POST" name="UploadCVForm"    accept-charset="utf-8"  class="form-horizontal"  >
@csrf

<div class="container">
 <!-- <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo"><i class="fas fa-circle-info"> </i>  Field Instructions for the Submission of PSUR</button>
<br>
<div id="demo" class="collapse">
Please make sure that an official dated and signed cover letter for submission of PSUR and the PSUR file(s) are ready for attachment. 
The materials to be submitted should meet the general requirements outlined in link xxx. 
Please 'zip' the documents and upload it here as a single attachment provided it is less than 10 MB. If the 'zipped' file is larger than 10 MB, send the document following the guide on link xxxx.
</div> -->


</div>
<br>
<input  type="hidden" id="user_id" name="user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">   
<input type="hidden" value="{{ $application_id }}" id="application_id" name="application_id" />
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<!-- <p class="text-muted"> <input type="file" name="file"  required   class="form-control"   placeholder="File"  /> </p>  -->
<input onchange="filechangevalidation_zip(this.value,'upload_file_psur','UploadData')"  type="file" name="file"   required class="form-control" id="upload_file_psur">
<p id="error1" style="display:none; color:#FF0000;">
Invalid File Format! File format  Must Be rar,zip,daa,iso.
</p>
<p id="error2" style="display:none; color:#FF0000;">
Maximum File Size Limit is 10MB.
</p>

<p id="error3" style="display:none; color:#FF0000;">
Current selected file size is <span id="file_size"></span>MB
</p>
<p>

<div class="col-md-offset-2 col-sm-10">
<button style="display:block"  type="submit" class="btn btn-primary"  title="Upload a Document(Zip only)"  id="UploadData" value="create"><i class="fas fa-upload"> </i>Upload</button>
<br>
</div>
</form>

            </div>
            <div style="overflow-x:auto;">
                   <table  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>PSUR Reference Number</th>
                    <th>PSUR File</th>
                    <th>Uploaded Date </th>
                    <th>Delete </th>
                
                  </tr>
                  </thead>
                  <tbody id="table_upload_psur">
                   </tbody>
                  <tfoot>
                
                  
                  </tfoot>
                </table>

        </div>
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


    
    $('#modal_upload_psur').click(function () {
        // alert('hellow');
       
        $('#modelHeading_upload_psur').html("Upload PSUR Document");
        $('#ajax_model_upload_psur').modal('show');



     var user_id = document.getElementById('user_id').value;
    var application_id = document.getElementById('application_id').value;

$.ajax({
url: "{{ route('fetch_psur_uploaded_files')  }}",
type:'POST',
data: {
user_id:user_id,
application_id:application_id,
},
processData: true,
success: (data) => {
if(data.Message==true)  
{
document.getElementById('table_upload_psur').innerHTML = data.Data_returned;
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
// var contact_person  = document.getElementById('contact_person_name').innerHTML;
// $('#app_name').val(contact_person.toUpperCase().trim());
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


$('#upload_cv').click(function () {
        //alert("hellow Eyoba");
     $('#modelHeading_upload_cv').html("Upload CV");
     $('#ajax_model_upload_cv').modal('show');
     var user_id = document.getElementById('user_id').value;


     $.ajax({

url: "{{ route('upload_file_CV_screen')  }}",
type:'POST',
data: {
user_id:user_id,
},
processData: true,
success: (data) => {
if(data.Message==true)  
{
document.getElementById('table_upload_cv').innerHTML = data.Data_returned;

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



    $('body').on('click', '.editupload', function () {
      var invoice_number = $(this).data('id');
          $('#modelHeading_query').html("Import Receipt Information");
          $('#saveBtn').html("Import");
          $('#ajaxModel').modal('show');
          $('#invoice_number').val(invoice_number);
        });




$('#UploadCVForm').submit(function(e) 
{
e.preventDefault();
//console.log(e.preventDefault());


$('#UploadData').html('Uploading PSUR......');
document.getElementById('UploadData').disabled = true;


var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ route('upload_file_psur')  }}",
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
    Toastr();
toastr.success("PSUR file uploaded successfully.")
document.getElementById('table_upload_psur').innerHTML = data.Data_returned;

 $('#UploadData').html('Save changes');
 $('#UploadData').html('Upload PSUR');
 document.getElementById('UploadData').disabled = false;



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
// var contact_person  = document.getElementById('contact_person_name').innerHTML;
// $('#app_name').val(contact_person.toUpperCase().trim());
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
var application_id = document.getElementById('application_id').value;
var psur_reference = $(this).data('psur_reference');


//alert(application_id);

if (confirm("Are sure  you want to remove this file??") == true)
{ 
$.ajax({
    data:{
   document_id:document_id,
   application_id:application_id,
   psur_reference:psur_reference ,
    },

type:'POST',
dataType: 'json',
url: "{{ route('delete_file_data_uploaded_psur')  }}",

success: (data) => { 


 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    }); 

Toastr();
    toastr.error('File Deleted!')
    document.getElementById('table_upload_psur').innerHTML = data.Data_returned;
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




