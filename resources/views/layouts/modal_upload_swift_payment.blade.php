<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div  class="modal fade" id="ajax_model_swift" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <!-- <div class="modal-content"> -->
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading_query"></h4>

                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" id="ACKForm"     method="POST" name="ACKForm"  action="javascript:void(0)"  accept-charset="utf-8" class="form-horizontal"  >
              @csrf
              <input  type="hidden" id="assesor_user_id" name="assesor_user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">    
             
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />

<div class="form-group">

<input  type="text" id="application_number" name="application_number" required  class="form-control" value="" readonly >    

<input  type="hidden" id="application_id" name="application_id" required  class="form-control" value="">    

</div>

<div class="form-group">
<label > Swift Payment File</label>
<div class="col-sm-12">
<input onchange="filechangevalidation(this.value,'receipt_file','upload_swift_payment')"  id="receipt_file" type="file" name="file" required placeholder="File" class="form-control" >
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

</div>
                    

 <div class="col-md-offset-2 col-sm-10">
<button style="display:block"  type="submit" class="btn btn-primary"  id="upload_swift_payment" value="create">Upload Payment Swift
                     </button>
                   <br>
                    </div>
                    
                </form>
                <table  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>File Name</th>
                    <th>Uploaded Date </th>
                    <th>Action </th>
                
                  </tr>
                  </thead>
                  <tbody id="table_upload_swift_payment">
                  <th colspan="4" style="text-align:center"> No Data </th>
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

function Toastr()
  
  {
          toastr.options.closeButton = true;
          toastr.options.timeOut = 10000; // How long (in milisec) the toast will display without user interaction
          toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it
          toastr.options.progressBar = true;
  }





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
    url: "{{ route('delete_file_swift_payment.remove') }}",
    success: function (data) {
       // table.draw();
       Toastr();
       toastr.error("Swift Payment  Deleted Successfully")
       $('#table_upload_swift_payment').html(data.Data_returned);
       $('#upload_swift_payment').html("Upload swift payment");

    },
    error: function (data) {
        console.log('Error:', data);
    }
});
});


    $('body').on('click', '.swift_payment', function () 
    {
    var  application_number  = $(this).data('app_number');
    var  application_id =      $(this).data('application_id');
    
     $('#modelHeading_query').html("Upload Payment Swift");
     $('#ajax_model_swift').modal('show');
     $('#application_number').val(application_number);
     $('#application_id').val(application_id);



     $.ajax({
type:'POST',
url: "{{ url('fetch_file_swift_payment')}}",
data:{
      application_id:     application_id,
      application_number : application_number,
    },

success: (data) => {

        if(data.Message==true)  
           {

document.getElementById('table_upload_swift_payment').innerHTML = data.Data_returned;


           } 

},
error: function(data){
console.log(data);
}
});



    });




 

 $('#ACKForm').submit(function(e) {
e.preventDefault();
//console.log(e.preventDefault());
$('#upload_swift_payment').html('Uploading Letter......');
document.getElementById('upload_swift_payment').disabled = true;
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ url('upload_file_swift_payment')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {

        if(data.Message==true)  
           {

//this.reset();
$('#upload_swift_payment').html('Upload Payment Swift');

document.getElementById('table_upload_swift_payment').innerHTML = data.Data_returned;
$('#ajax_model_swift').modal('hide');
document.getElementById('upload_swift_payment').disabled = false;
Toastr();
toastr.success("Swift payment file uploaded successfully.")


           } 

           else
           {
            this.reset();
$('#upload_swift_payment').html('Uploading Letter...');
document.getElementById('upload_swift_payment').disabled = false;
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

//  $('#IssueQForm').submit(function(e) {
// e.preventDefault();
// console.log(e.preventDefault());
// $('#UploadData_query').html('Issue Query......');
// var application_id= document.getElementById('number_application').value;
// var formData = new FormData(this);
// $.ajax({
// type:'POST',
// url: "{{ route('application.IssueQuery_front') }}",
// data: formData,
// cache:false,
// contentType: false,
// processData: false,
// success: (data) => {

//         if(data.Message==true)  
//            {

// this.reset();
//  var Toast = Swal.mixin({
//       toast: true,
//       position: 'top-end',
//       showConfirmButton: false,
//       timer: 6000
//     }); 

// toastr.success("Query responses uploded successfully")

// // alert('Image has been uploaded using jQuery ajax successfully');

//  $('#ajax_model_issue_query').modal('hide');
//  $('#UploadData').html('Save changes');
// document.getElementById('UploadData').disabled = false;
// $('#get_path_uploaded').show('100');
// $('#get_path_uploaded').attr("href", data.Download_Link);
// $('#upload_query').hide('100');
//            } 

//            else
//            {
//             this.reset();
//  var Toast = Swal.mixin({
//       toast: true,
//       position: 'top-end',
//       showConfirmButton: false,
//       timer: 6000
//     }); 

// $('#UploadData').html('Save changes');
// document.getElementById('UploadData').disabled = false;
// var contact_person  = document.getElementById('contact_person_name').innerHTML;
//         $('#app_name').val(contact_person.toUpperCase().trim());
// toastr.error('Allowed Files Type is only .PDF (PDF Document)')

//            }


// },
// error: function(data){
// console.log(data);
// }
// });

// });


  });
</script>




