<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div  class="modal fade" id="ajax_model_issue_query" aria-hidden="true" data-backdrop="static" tabindex="-1">
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
<form method="POST" enctype="multipart/form-data" id="IssueQForm"     method="POST" name="upload_first_form"   action="{{ route('application.IssueQuery_front')   }}" 

 accept-charset="utf-8" class="form-horizontal"  >

              @csrf
              <input  type="hidden" id="assesor_user_id" name="assesor_user_id" required  class="form-control"  value="{{ Auth::user()->id  }}">    
             
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="form-group">
                    <select required class="form-control select2bs4" id="number_application"  name="number_application"  required>
                                    <option value="" disable="true" selected="true">Select Application Number</option>
                                    @foreach($applications_front as $value)
                                   <option value="{{$value->application_id}}">{{ $value->application_number }} ({{ $value->product_trade_name }})</option>
                                    @endforeach
                                    </select>
                        </div>

                        <!-- <div class="form-group">
                        <label > Sealed Issued Query </label>
                        <div class="col-sm-12">
                        <input  id="receipt_file" type="file" name="file" required placeholder="File" class="form-control" >
                        </div> -->
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
<button style="display:block"  type="submit" class="btn btn-primary"  id="" value="create">Issue Query
                     </button>
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

$('#issue_query').click(function () {
        //alert("hellow Eyoba");
     $('#modelHeading_query').html("Issue Query");
     $('#ajax_model_issue_query').modal('show');
     
    });


    $('body').on('click', '.editReceipt', function () {
      var invoice_number = $(this).data('id');
          $('#modelHeading_query').html("Import Receipt Information");
          $('#saveBtn').html("Import");
          $('#ajaxModel').modal('show');
          $('#invoice_number').val(invoice_number);
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




