<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div  class="modal fade" id="ajax_model_show_letter" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <!-- <div class="modal-content"> -->
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title" id="show_modelHeading"></h4>

                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="ACKForm"     method="POST" name="ACKForm"  action="javascript:void(0)"  accept-charset="utf-8" class="form-horizontal"  >

              @csrf
              <input  type="hidden" id="applicant_number" name="application_number" required  class="form-control"  value="{{  @$application->application_number }}">    
    
    <!-- <input  type="hidden" id="applicant_user_id" name="applicant_user_id" required  class="form-control"  value="{{ @$application->user_id  }}">    
    <input  type="hidden" id="assesor_user_id" name="assesor_user_id"     required  class="form-control"  value="{{ Auth::user()->id  }}">    
    <input  type="hidden" id="sequence_number" name="sequence_number"     required  class="form-control"  value="{{ @$application->PS_squential_number  }}">    

    <input  type="hidden" id="dosage_form" name="dosage_form"     required  class="form-control"  value="{{ @$application->PS_squential_number  }}">     -->

    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

       

           
<div class="col-md-offset-2 col-sm-10">
<br>
           </button>
         
          </div>
          
      </form>
               </div>

            <table  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>Application Number</th>
                    <th>Brand Name</th>
                    <th>Dosage Forms</th>
                    <th>Date of upload </th>
                    <th>Response files received</th>
                
                  </tr>
                  </thead>
                  <tbody id="table_upload_d">
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


$('body').on('click', '.show', function () {
var sequence_number = $(this).data('id');
var contact= 'contact_person_name_'+sequence_number;
var contact_person = document.getElementById(contact).innerHTML;


$('#app_name').val(contact_person.toUpperCase().trim());
$('#show_modelHeading').html("View responses");
$('#ajax_model_show_letter').modal('show');



$.ajax({
    data:{
    sequence_number :sequence_number ,
    },

type:'POST',
dataType: 'json',
url: "{{ route('retrive_anwered_query_from_applicant') }}",

success: (data) => { 

document.getElementById('table_upload_d').innerHTML = data.Data_returned;
},

error: function(data){
console.log(data);
}

});





});

 



  });
</script>




