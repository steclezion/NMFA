<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4><h5> &nbsp; <u>  <span style="display:none" id="app_id"> </span> </h5> </u> 
                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              

            </div>
            <div class="modal-body">
                <form id="assignForm" name="assignForm" class="form-horizontal">
                   <input type="hidden" name="application_id" id="application_id">
                   <input type="hidden" name="psur_refrence_number" id="psur_refrence_number">


                    <div class="form-group">
                        <label for="name" class="">Assign To</label>
                        <div class="col-sm-12">
                    <select   class="form-control select2bs4" style="width: 300px;"    name="user_id" id="user_id" required >
                         @foreach ($dataa as $key => $user)
                            @if(!empty($user->getRoleNames())) 
                            @foreach($user->getRoleNames() as $v)
                             @if($v=='PERC' || $v=='Assessor' )
                             <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->middle_name }} <b style="color:green" > <u>({{ $v }}) </u> </b></option>
                          
                             @endif
                             @endforeach
                            @endif
                            @endforeach 
                            </select>


                        </div>
                    </div>
     
                    <div class="form-group">
                        <label class="">Assignment Date</label>
                        <div class="col-sm-12">
                       <input id="AssignmentDate" name="AssignmentDate" required=""  readonly value="@php $t=time(); echo date("Y-m-d",$t); @endphp"  class="form-control"></textarea>
                        </div>
                    </div>


                         <div class="form-group">
                        <label class="">Set Deadline</label>
                        <div class="col-sm-12">
                       <input id="deadline" name="deadlineDate" required=""  type="date" value="@php $t=time(); echo date("Y-m-d",$t); @endphp"  class="form-control">
                       
                        </div>
                    </div>


      
                    <div class="col-md-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveAssign" value="create">Assign
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



    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('un_assigned_psur.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'regnumber', name: 'regnumber','visible': true},
            {data: 'psur_refrence_number', name:'psur_refrence_number'},
            {data: 'product_name', name:'product_name'},
            {data: 'product_trade_name', name:'product_trade_name','visible': true},
            {data: 'trade_name', name:'trade_name','visible': true},
           
            {data: 'registration_type', name:'registration_type','visible': false},
            {data: 'assigned_To', name:'assignto'},
            {data: 'assigned_By', name:'assignby','visible': false},
            {data: 'deadline', name:'deadline','visible': true},
            {data: 'application_status', name:'application_status','visible': false},
            {data: 'path_psur', name:'path_psur'},
            {data: 'action', name: 'action', orderable: true, searchable: true},
        ]
    });


    
    $('#createNewBook').click(function () {
       
        $('#saveAssign').val("create-book");
        $('#application_id').val('');
        $('#assignForm').trigger("reset");
        $('#modelHeading').html("Create New Book");
        $('#ajaxModel').modal('show');
    });



    $('body').on('click', '.editAssign', function () {
      var application_id = $(this).data('id');
      var psur_refrence_number = $(this).data('psur_refrence_number');
    //   $.get("{{ route('assignment.index') }}" +'/' + application_number +'/edit', function (data) {
          $('#modelHeading').html("Assign PSUR");
          $('#app_id').html(application_id);
          $('#saveAssign').val("Assign");
          $('#ajaxModel').modal('show');
          $('#psur_refrence_number').val(psur_refrence_number);
        

   });
   


   
    $('#saveAssign').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
       var  psur_refrence_number =   document.getElementById('psur_refrence_number').value;
        var assigned_To = document.getElementById('user_id').value;
        var assigned_By = document.getElementById('user_idd').innerHTML;
        var application_id = document.getElementById('app_id').innerHTML;
        var deadline = document.getElementById('deadline').value;


        $.ajax({
        //   data: $('#assignForm').serialize(),
        data:{      assigned_To:assigned_To,
                    assigned_By:assigned_By,
                    application_id:application_id,
                    psur_refrence_number:psur_refrence_number,
                    deadline:deadline,
                              },
          url: "{{ route('assignment_psur.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
             $('#saveAssign').html("Assign");
              $('#assignForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveAssign').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteBook', function () {
     
        var application_id = $(this).data("id");
        {if(confirm("Are You sure You Want To Delete This File")){}else{return false;}}
        $.ajax({
            type: "DELETE",
            url: "{{ route('books.store') }}"+'/'+application_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>