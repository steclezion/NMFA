<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div  class="modal fade" id="ajax_model_acknowledgment_letter" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <!-- <div class="modal-content"> -->
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>

                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="ACKForm"     method="POST" name="ACKForm"  action="javascript:void(0)"  accept-charset="utf-8" class="form-horizontal"  >

<!-- <form id="ACKForm"    enctype="multipart/form-data"  action="{{ route('upload_file_acknowledgement') }}"  method="POST" name="ACKForm" class="form-horizontal"  > -->
              @csrf
              <input  type="hidden" id="applicant_number" name="application_number" required  class="form-control"  value="{{  $checked->application_number }}">    
              <input  type="hidden" id="sequence_number" name="sequence_number" required  class="form-control"  value="">    

              <input  type="hidden" id="applicant_user_id" name="applicant_user_id" required  class="form-control"  value="{{ $checked->user_id  }}">    
              <input  type="hidden" id="assesor_user_id" name="assesor_user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">    

              <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="form-group">
                        <label class="">Applicant Name</label>
                        <div class="col-sm-12">
                  <input id="app_name" name="app_name" required  class="form-control"  type="text" readonly >   
                        </div>
                        </div>

                        <div class="form-group">
                        <label > Sealed Issued Query </label>
                        <div class="col-sm-12">
                        <input  id="receipt_file" type="file" name="file" required placeholder="File" class="form-control" >
                        </div>
                        </div>
                    
<!--
                        <div class="form-group">
                        <label class="col-sm-2 control-label">Amount</label>
                        <div class="col-sm-12">
                        <input id="amount" name="amount" required placeholder="Amount" class="form-control"  type="text" >                        </div>
                        </div>
                         
-->
                         <!-- <div class="form-group">
                        <label class="col-sm-2 control-label">Received Date</label>
                        <div class="col-sm-12">
                        <input id="date" name="receipt_data" required placeholder="Date" class="form-control"  type="text" value="@php $t=time(); echo date("Y-m-d",$t); @endphp"  readonly >                        </div>
                        </div> -->

                    
                    <!-- <div class="form-group">
                        <label class="col-sm-2 control-label"> Description </label>
                        <div class="col-sm-12">
                            <textarea id="description" name="description" required placeholder="Description" class="form-control"></textarea>
                        </div>
                    </div> -->
                    
                   


                    

      
                    <div class="col-md-offset-2 col-sm-10">
<button style="display:block"  type="submit" class="btn btn-primary"  id="UploadData" value="create">Save changes
                     </button>
                   
                    </div>
                    
                </form>
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

$('#upload_query').click(function () {
        //alert("hellow Eyoba");
        var contact_person  = document.getElementById('contact_person_name').innerHTML;
        $('#app_name').val(contact_person.toUpperCase().trim());
    //   document.getElementById('app_name').readonly = true;
      
        $('#modelHeading').html("Upload Response Files");
        $('#ajax_model_acknowledgment_letter').modal('show');
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
$('#UploadData').html('Uploading Letter......');
document.getElementById('UploadData').disabled = true;
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ url('upload_file_issued_query')}}",
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
toastr.success("Responses uploded successfully")

// alert('Image has been uploaded using jQuery ajax successfully');

 $('#ajax_model_acknowledgment_letter').modal('hide');
 $('#UploadData').html('Uploading...');
document.getElementById('UploadData').disabled = false;
$('#get_path_uploaded').show('100');
$('#get_path_uploaded').attr("href", data.Download_Link);
$('#upload_query').hide('100');
           } 

           else
           {
            this.reset();

$('#UploadData').html('Uploading...');
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




