<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>


<div  class="modal fade" id="ajax_model_upload_cv" aria-hidden="true" data-backdrop="static" tabindex="-1">
<div class="modal-dialog modal-lg">
<input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
<!-- <div class="modal-content"> -->
<div class="modal-content bg-default">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading_upload_cv"></h4>

<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
</div>
<div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="UploadCVForm"     method="POST" name="UploadCVForm"    accept-charset="utf-8"  class="form-horizontal"  >
@csrf
<input  type="hidden" id="user_id" name="user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">   
 
<input type="hidden" name="_token" value="{{ csrf_token() }}" />




 <!-- <p class="text-muted"> <input type="file" name="file"  required   class="form-control"   placeholder="File"  /> </p>  -->
 
 <input onchange="filechangevalidation(this.value,'upload_file_cv','UploadData')"  type="file" name="file"   required class="form-control" id="upload_file_cv">
 
 <p id="error1" style="display:none; color:#FF0000;">
Invalid File Format! File format  Must Be PDF,OXPS, Doc file.
</p>
<p id="error2" style="display:none; color:#FF0000;">
Maximum File Size Limit is 10MB.
</p>

<p id="error3" style="display:none; color:#FF0000;">
Current selected file size is <span id="file_size"></span>MB
</p>
<p>

                   



<div class="col-md-offset-2 col-sm-10">
<button style="display:block"  type="submit" class="btn btn-primary"  title="Upload a Document(PDF only)"  id="UploadData" value="create"><i class="fas fa-upload"> </i>Upload Curriclum Vitae</button>
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
                  <tbody id="table_upload_cv">
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
console.log(e.preventDefault());


$('#UploadData').html('Uploading CV......');
document.getElementById('UploadData').disabled = true;


var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ route('upload_file_CV')  }}",
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
toastr.success("CV uploaded Successfully ")

 $('#UploadData').html('Save changes');
 $('#UploadData').html('Upload Curriclum Vitae');
 document.getElementById('UploadData').disabled = false;
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


//deletequery  di
$('body').on('click', '.deletequery', function () {

var document_id = $(this).data('id');


//alert(document_id );

if (confirm("Are sure  you want to remove this file??") == true)
               { 
                   

$.ajax({
    data:{
   document_id:document_id,
    },

type:'POST',
dataType: 'json',
url: "{{ route('delete_file_data_uploaded_cv')  }}",

success: (data) => { 


 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    }); 

Toastr();
    toastr.error('File Deleted!')
    document.getElementById('table_upload_cv').innerHTML = data.Data_returned;
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




