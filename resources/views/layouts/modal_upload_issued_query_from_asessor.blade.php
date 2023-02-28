<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div  class="modal fade" id="ajax_model_query_from_assessor" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <!-- <div class="modal-content"> -->
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title" id="download_query_from_modelHeading"></h4>

                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>

              
            </div>
            <div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="ACKForm"     method="POST" name="ACKForm"  action="javascript:void(0)"  accept-charset="utf-8" class="form-horizontal"  >

              @csrf
              <input  type="hidden" id="applicant_number" name="application_number" required  class="form-control"  value="{{  @$application->application_number }}">    
    
              <input  type="hidden" id="applicant_user_id" name="applicant_user_id" required  class="form-control"  value="{{ @$application->user_id  }}">    
              <input  type="hidden" id="assesor_user_id" name="assesor_user_id"     required  class="form-control"  value="{{ Auth::user()->id  }}">    
              <input  type="hidden" id="sequence_number" name="sequence_number"     required  class="form-control"  value="{{ @$application->PS_squential_number  }}">    
              <input  type="hidden" id="dosage_form" name="dosage_form"     required  class="form-control"  value="{{ @$application->PS_squential_number  }}">    
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                


                    
                </form>
            </div>
            <table  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>Reference Number</th>
                    <th>Brand Name</th>
                    <th>Dosage Forms</th>
                    <th>Strength </th>
                    <th>Download query files</th>
                  
                
                  </tr>
                  </thead>
                  <tbody id="table_upload_doc_assessor">
                  <th colspan"6">Pending... <th>
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
  
//uploaded_assessor
$('body').on('click', '.uploaded_assessor', function () {
var sequence_number = $(this).data('id');
document.getElementById('sequence_number').value= sequence_number;
var contact= 'contact_person_name_'+sequence_number;
var contact_person = document.getElementById(contact).innerHTML;
document.getElementById('dosage_form').value = contact_person;

      


$('#download_query_from_modelHeading').html("Download query files");
$('#ajax_model_query_from_assessor').modal('show');



$.ajax({
    data:{
    sequence_number :sequence_number ,
    },

type:'POST',
dataType: 'json',
url: "{{ route('retrive_issued_query_from_assessor')  }}",

success: (data) => { 
    document.getElementById('table_upload_doc_assessor').innerHTML = data.Data_returned;
},

error: function(data){
console.log(data);
}

});





});


  });
</script>




