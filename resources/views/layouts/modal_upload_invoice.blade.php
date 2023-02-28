<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div  class="modal fade" id="ajax_model_upload_invoice" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <!-- <div class="modal-content"> -->
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingg"></h4>

                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="ACKForm"     method="POST" name="ACKForm"  action="javascript:void(0)"  accept-charset="utf-8" class="form-horizontal"  >

<!-- <form id="ACKForm"    enctype="multipart/form-data"  action="{{ route('upload_file_acknowledgement') }}"  method="POST" name="ACKForm" class="form-horizontal"  > -->
              @csrf
              <input  type="hidden" id="applicant_number" name="application_number" required  class="form-control"  value="">   
              <input  type="hidden" id="application" name="application_id" required  class="form-control"  value="">   
 
              <input  type="hidden" id="sequence_number" name="sequence_number" required  class="form-control"  value="">    

              <input  type="hidden" id="applicant_user_id" name="applicant_user_id" required  class="form-control"  value="">    
              <input  type="hidden" id="assesor_user_id" name="assesor_user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">    

              <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="form-group">
                        <label class="">Application Number</label>
                  <div class="col-sm-12">
                  <input id="app_number" name="app_number" required  class="form-control"  type="text" readonly >   
                  </div>
                        </div>

                        <div class="form-group">
                        <label > Sealed Invoice Letter</label>
                        <div class="col-sm-12">
                        <input  id="invoice_file" type="file" name="file" onchange="filechangevalidation(this.value,'invoice_file','UploadData')" required placeholder="File" class="form-control" >
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

                        </div>
              
              
                       <button  type="submit" class="btn btn-primary"  title="upload invoice to applicant"  id="UploadData" value="create"> <i class="fas fa-upload">  </i>Upload </button>
                   
                  
                    
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
                  <tbody id="table_upload_invoice_letter">
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
    url: "{{ route('delete_file_uploaded_invoice_letter.remove') }}",
    success: function (data) {
       // table.draw();
       Toastr();
       toastr.error("Invoice letter Deleted Successfully")

       $('#table_upload_invoice_letter').html(data.Data_returned);
       $('#UploadData').html("Upload");
       //document.getElementById('table_upload_invoice_letter').innerHTML = data.Data_returned;

    },
    error: function (data) {
        console.log('Error:', data);
    }
});
});


$('#upload_invoice').click(function () {
    
        $('#modelHeadingg').html("Query/Upload");
        $('#ajax_model_upload_invoice').modal('show');

    });


    $('body').on('click', '.edituploadinvoice', function () {
         

          var  application_number  = $(this).data('application_number');
          var  application_id  =     $(this).data('id');

          $('#app_number').val(application_number);
          $('#application').val(application_id);


          $('#modelHeadingg').html("Upload invoice letter");
          $('#UploadData').html("Upload");
          $('#ajax_model_upload_invoice').modal('show');

$.ajax({
type:'POST',
url: "{{ url('fetch_uploaded_invoice_letter')}}",
data: {

    application_id:application_id,
},
success: (data) => {

        if(data.Message==true)  
           {
document.getElementById('table_upload_invoice_letter').innerHTML = data.Data_returned;

 } 

           else
           {

$('#UploadData').html('Save changes');
document.getElementById('UploadData').disabled = false;
Toastr();
toastr.error('internal error consult the admin please!!')

           }
},
error: function(data){
console.log(data);
}
});
            });
 

 $('#ACKForm').submit(function(e) {
e.preventDefault();
console.log(e.preventDefault());
$('#UploadData').html('Uploading invoice Letter......');
document.getElementById('UploadData').disabled = true;
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ url('upload_invoice_letter')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {

        if(data.Message==true)  
           {


 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    }); 
    Toastr();
toastr.success("Invoice letter Uploaded Successfully")
//this.reset();

$('#ajax_model_upload_invoice').modal('hide');
 document.getElementById('table_upload_invoice_letter').innerHTML = data.Data_returned;

document.getElementById('UploadData').disabled = false;
$('#get_path_uploaded').show('100');
$('#get_path_uploaded').attr("href", data.Download_Link);
//$('#upload_query').hide('100');

           } 

           else
           {
           // this.reset();
 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    }); 

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


  });


</script>




