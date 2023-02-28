<div  class="modal fade" id="ajax_check_steps_completion" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <!-- <div class="modal-content"> -->
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading_check_steps"></h4>

                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="Screening"     method="GET" name="Checklist"   action=""  accept-charset="utf-8" class="form-horizontal"  >
@csrf
<input  type="hidden" id="assesor_user_id" name="assesor_user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">    
<input type="hidden"  name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="application_id" id="application_id" value=" {{ $product_name->application_id}}  " />

               <div class="col-sm-12">
                                    <!-- checkbox -->
                                        <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                        <input readonly type="checkbox" id="checkboxSuccess_step1">
                                        <label for="checkboxPrimary1"></label>
                                        </div>  
                                        <div class="icheck-primary d-inline">
                                        <label for="checkboxPrimary3"> Section 1: Product Details  </label>
                                        </div>
                                        </div>


                                        <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                        <input readonly type="checkbox" id="checkboxSuccess_step2">
                                        <label for="checkboxPrimary1"></label>
                                        </div>  
                                        <div class="icheck-primary d-inline">
                                        <label for="checkboxPrimary3"> Section 2: General Requirements </label>
                                        </div>
                                        </div>
@foreach($check_list as $check_applications_check_list )  @endforeach
@if($check_applications_check_list->application_type !=1  )

                                        <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                        <input readonly type="checkbox" id="checkboxSuccess_step3">
                            
                                        <label for="checkboxPrimary1"></label>
                                        </div>  
                                        <div class="icheck-primary d-inline">
                                        <label for="checkboxPrimary3"> Section 3: Specific requirements for fast-track registration  </label>
                                        </div>
                                        <input readonly hidden type="checkbox" id="checkboxSuccess_step4">
                                        </div>
@endif

@foreach($check_list as $check_applications_check_list )  @endforeach
@if($check_applications_check_list->application_type !=2 )
                                        <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                        <input readonly type="checkbox" id="checkboxSuccess_step4">
                                        <label for="checkboxPrimary1"></label>
                                        </div>  
                                        <div class="icheck-primary d-inline">
                                        <label for="checkboxPrimary3"> Section 4: Sample details  </label>
                                        </div>
                                        <input readonly hidden type="checkbox" id="checkboxSuccess_step3">
                                        </div>
@endif

                                        <div class="form-group clearfix">
                                        <div class="icheck-success d-inline">
                                        <input readonly type="checkbox" id="checkboxSuccess_step5">
                                        <label for="checkboxPrimary1"></label>
                                        </div>  
                                        <div class="icheck-primary d-inline">
                                        <label for="checkboxPrimary3"> Section 5: Payment details  </label>
                                        </div>
                                        </div>

                                </div>
                                <div class="row">
    <div class="col-sm-9"> 



                   
                   <br>
                    </div>


                       <div class="col-sm-9"> 
                   
                    </div>
                </div>    

                   <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <button style="display:block"   type="button" title="Save Acnowledgment Letter" class="btn btn-primary"  id="proceed_acknow" value="create"><i class="fas fa-arrow-circle-right">  </i> Accept and  Generate Acknowldegement letter
                     </button>
          </div>
          <div class="col-sm-6">
          <button style="display:block"   type="button" title="Reject Acnowledgment Letter" class="btn btn-danger"  id="reject_acknowledgment_application" value="create"><i class="fas fa-close">  </i> Reject Application
                     </button>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
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

$('#proceed_acknow').click(function () {

let application_id = document.getElementById('application_id').value;

 window.location = '/Acknowledgement_Letter_preliminary_screening/'+application_id.trim();

});



$('#reject_acknowledgment_application').click(function () {

let application_id = document.getElementById('application_id').value;

 window.location = '/reject_Acknowledgement_Letter_preliminary_screening_application/'+application_id.trim();

});





$('#check_steps').click(function () {
        //alert("hellow Eyoba");
     $('#modelHeading_check_steps').html("Screening Checklist");
     $('#ajax_check_steps_completion').modal('show');

let user_id = document.getElementById('user_id').value;
let application_id = document.getElementById('application_id').value;

 $.ajax({
url: "{{ route('check_preliminary_screening')  }}",
type:'POST',
data: {
user_id:user_id,
application_id:application_id,
},
processData: true,
success: (data) => {

if(data.section_one==true)   { document.getElementById('checkboxSuccess_step1').checked= true; } else if(data.section_one==false)   { document.getElementById('checkboxSuccess_step1').checked= false; }
if(data.section_two==true)     { document.getElementById('checkboxSuccess_step2').checked= true; } else if(data.section_two==false)   { document.getElementById('checkboxSuccess_step2').checked= false; }
if(data.section_three==true)   { document.getElementById('checkboxSuccess_step3').checked= true; } else if(data.section_three==false)   { document.getElementById('checkboxSuccess_step3').checked= false; }
if(data.section_four==true)   { document.getElementById('checkboxSuccess_step4').checked= true; } else if(data.section_four==false)   { document.getElementById('checkboxSuccess_step4').checked= false; }
if(data.section_five==true)   { document.getElementById('checkboxSuccess_step5').checked= true; } else if(data.section_five==false)   { document.getElementById('checkboxSuccess_step5').checked= false; }


if(data.final_button==true)   { document.getElementById('proceed_acknow').disabled= false;document.getElementById('proceed_acknow').title="Save Acnowledgment Letter"; } else if (data.final_button==false) { document.getElementById('proceed_acknow').disabled= true; document.getElementById('proceed_acknow').disabled= true; document.getElementById('proceed_acknow').disabled= true; document.getElementById('proceed_acknow').title="check steps caerfully to grant acknowlegement letter";}


},

error: function(data){
console.log(data);
}

});

     
    });


    $('body').on('click', '.editReceipt', function () {
      var invoice_number = $(this).data('id');
          $('#modelHeading_query').html("Import Receipt Information");
          $('#saveBtn').html("Import");
          $('#ajaxModel').modal('show');
          $('#invoice_number').val(invoice_number);
        });
 
  });
</script>




