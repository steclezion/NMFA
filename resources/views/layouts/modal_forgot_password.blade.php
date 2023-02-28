<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>


<div  class="modal fade" id="ajax_model_forgot_password" aria-hidden="true" data-backdrop="static" tabindex="-1">
<div class="modal-dialog modal-lg">

<!-- <div class="modal-content"> -->
<div class="modal-content bg-default">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading_question_answer"></h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
</div>
<div class="modal-body">
<form method="POST" enctype="multipart/form-data" id="modal_forgot_pass"     method="POST" name="UploadCVForm"    accept-charset="utf-8" class="form-horizontal"  >
@csrf
<input  type="hidden" id="user_id" name="user_id" required  class="form-control"  >   
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<div class="form-group">
<label>1. Personal Information Questions </label>
<select class="form-control" style="width: 100%;"   name="personel_information" id="personal_informations"  required>
<option value="0" disable="true" selected="true"></option>
@foreach ($forgotpassword_one as $key => $value)
<option value="{{$value->id}}">{{ $value->Question_Name }}</option>
@endforeach
</select> 
</div>

<div class="form-group">
<p class="text-muted"> 
<input id="personal_information" type="text" name="faq_1"  required   class="form-control"   required placeholder="Answer Question Number 1"  /> 
</p> 
</div>

<div class="form-group">
<label>2. Childhood Questions </label>
<select class="form-control" style="width: 100%;"   name="child_hood" id="child_hood" required >
<option value="0" disable="true" selected="true"> </option>

@foreach ($forgotpassword_two as $key => $value)
<option value="{{$value->id}}"><span style="color:black"> {{ $value->Question_Name }} </span>  </option>
@endforeach
</select> 
</div>


<div class="form-group">
<p class="text-muted"> 
<input id="childhood_questions" type="text" name="faq_2"  required   class="form-control"   placeholder="Answer Question Number 2" required />
</p> 
</div>


<div class="form-group">
<label>3. Hobbies  </label>
<select class="form-control" style="width: 100%;"   name="hobbies_id" id="hobbies_select" required >
<option value="0" disable="true" selected="true"> </option>
@foreach ($forgotpassword_three as $key => $value)
<option value="{{$value->id}}">{{ $value->Question_Name }}</option>
@endforeach
</select>  
</div>

<div class="form-group">
<p class="text-muted"> 
<input id="hobbies" type="text" name="faq_3"  required   class="form-control"   placeholder="Answer Question Number 3" > 
</p> 
</div>














<div class="col-md-offset-2 col-sm-10">
<span style="display:col-4"   class="btn btn-primary"  id="ok_forgot_password" > OK </button>
<br>

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

$('#modal_forgot_password').click(function () {
        
     $('#modelHeading_question_answer').html("Fill all security questions");
     $('#ajax_model_forgot_password').modal('show');
     document.getElementById('pass_email_success').style.display = "none";
  
      });

      

      $('#ok_forgot_password').click(function () {
        
        //$('#modelHeading_question_answer').html("Fill all security questions");
        $('#ajax_model_forgot_password').modal('hide');
     
         });




});
</script>




