<!-- <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script> -->


<div  class="modal fade" id="ajax_model_mailing" aria-hidden="true" data-backdrop="static" tabindex="-1">
<div class="modal-dialog modal-lg">
<input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />

<!-- <div class="modal-content"> -->
<div class="modal-content bg-default">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading_mailing"></h4>

<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
</div>
<div class="modal-body">

  <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
              <div style="overflow-x:auto;">
                   <table  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th> -- </th>
                    <th {{ $i=0 }}>ID</th>
                    <th>Receiver Name</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <!-- <th>Delete </th> -->
                
                  </tr>
                  </thead>
                  <tbody id="table_mailing">
                   </tbody>
                  <tfoot>
                
                  
                  </tfoot>
                </table>

        </div>
        
                <h3 class="card-title">Compose New Message</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                  <input type="text"  id="To"  readonly class="form-control" placeholder="To:">

                  <input   type="hidden" id="supervisor_id"  class="form-control" placeholder="To:">
                </div>
                <div class="form-group">
                  <input id="subject"class="form-control" placeholder="Subject:">
                </div>
                <div class="form-group">
                    <textarea id="compose-textarea" class="form-control" style="height: 100px">
                   
                    </textarea>
                </div>
                <script>
  $(function () {
    //Add text editor
  //  $('#compose-textarea').summernote()
  })
</script>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div class="float-right">
                  <!-- <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button> -->
                  <button type="submit" id="send_mail" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                </div>
                <button data-dismiss="modal" aria-label="Close"  type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>

            </div>
            
    </div>
</div>
</div></div>


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


    
    $('#send_message_to_supervisor').click(function () {
        // alert('hellow');
       
        $('#modelHeading_mailing').html("Mail To:");
        $('#ajax_model_mailing').modal('show');



     var user_id = document.getElementById('user_id').value;
    var application_id = document.getElementById('application_id').value;

$.ajax({
url: "{{ route('mailing')  }}",
type:'POST',
data: {
user_id:user_id,
application_id:application_id,
},
processData: true,
success: (data) => {
if(data.Message==true)  
{
document.getElementById('table_mailing').innerHTML = data.Data_returned;
document.getElementById('supervisor_id').value = data.Supervisor_user_id;
document.getElementById('To').value = data.Supervisor_name;
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






  $('#send_mail').click(function () {
       
    var user_id = document.getElementById('user_id').value;
    var application_id = document.getElementById('application_id').value;
    var to = document.getElementById('supervisor_id').value;
    var subject = document.getElementById('subject').value;
     var message = document.getElementById('compose-textarea').value;

if(subject == '' || subject==0) { document.getElementById('subject').focus(); return false;} 
if(message == '' ||message==0) { document.getElementById('compose-textarea').focus(); alert('Message Box is empty!');return false;} 

$.ajax({
url: "{{ route('mailing_send')  }}",
type:'POST',
data: {
user_id:user_id,
application_id:application_id,
to:to,
subject:subject,
message:message,
},
processData: true,
success: (data) => {
if(data.Message==true)  
{
    document.getElementById('table_mailing').innerHTML = data.Data_returned;
document.getElementById('supervisor_id').value = data.Supervisor_user_id;
document.getElementById('To').value = data.Supervisor_name;
 document.getElementById('subject').value='';
 document.getElementById('compose-textarea').value='';
 toastr.success('Comment Report sent successfully.')
}
else
{

Toastr();
toastr.error('Communicate with Admin please invalid Error!!')

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
document.getElementById('table_mailing').innerHTML = data.Data_returned;

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


//alert(application_id);

if (confirm("Are sure  you want to remove this file??") == true)
               { 
                   

$.ajax({
    data:{
   document_id:document_id,
   application_id:application_id,
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
    document.getElementById('table_mailing').innerHTML = data.Data_returned;
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




