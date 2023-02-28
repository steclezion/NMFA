<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="Country_Forms" name="Country_Forms" class="form-horizontal">
                   <input type="hidden" name="country_id" id="coutntry_id">

                    <div class="form-group">
                    <label  class="">Name</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control" id="country_name" name="name" placeholder="Name" value="" maxlength="50" required="">
                    </div>
                    </div>

                    <div class="form-group">
                    <label  class="">Code</label>
                    <div class="col-sm-12">
                    <input type="text" id="code" name="code" required  placeholder="code" class="form-control" />
                    </div>
                    </div>


                    <div class="form-group">
                    <label  class="">International Dialing Number</label>
                    <div class="col-sm-12">
                    <input type="text"  id="dialing_number" name="dialing_number" required  placeholder="International Dialing Number" class="form-control">
                    </div>
                    </div>

     

                    <div class="col-md-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create"> <i class="fas fa-save"> </i> Save changes
                    </button>
                    </div>
                    </form>

            </div>
        </div>
    </div>
</div>

<style>
.important {
    font-weight: bold;
    font-size: xx-large;
}

.blue {
    color: blue;
}
</style>





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

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('country_list.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'country_name', name: 'country_name'},
            {data: 'country_code', name: 'country_code'},
            {data: 'International_dialing', name: 'International_dialing'},
           {data: 'action', name: 'action', orderable: true, searchable: true},
        ]
    });


    
    $('#createNewDosage').click(function () {
        //alert("hellow Eyoba");
        $('#saveBtn').val("create-Dosage");
        $('#coutntry_id').val('');
        $('#Country_Forms').trigger("reset");
        $('#modelHeading').html("Create New Dosages");
        $('#ajaxModel').modal('show');
        //$('#Dosage_id').removeClass("blue");
    });



    $('body').on('click', '.editcountries', function () {

      var country_id = $(this).data('id');
      $('#coutntry_id').removeClass("blue");
      $.get("{{ route('country_list.index') }}" +'/' + country_id+'/edit', function (data) {
          $('#modelHeading').html("Edit Country");
          $('#saveBtn').val("edit-enlm");
          $('#ajaxModel').modal('show');

          $('#coutntry_id').val(data.id);
         $('#country_name').val(data.country_name);
          $('#code').val(data.country_code);
          $('#dialing_number').val(data.International_dialing);


          $('#saveBtn').html("<i class='fas fa-edit'>  </i>Update");
          $("#saveBtn").addClass("btn btn-success");

      })
   });
   
        $('#saveBtn').click(function (e) {

 e.preventDefault();
 if(document.getElementById('country_name').value == '') {  $('#country_name').addClass("blue"); document.getElementById('country_name').focus();  return false;}
 if(document.getElementById('code').value == '') {  $('#code').addClass("blue"); document.getElementById('code').focus();  return false;}
 if(document.getElementById('dialing_number').value == '') {  $('#dialing_number').addClass("blue"); document.getElementById('dialing_number').focus();  return false;} 
 $(this).html('Save');


        $.ajax({
          data: $('#Country_Forms').serialize(),
          url: "{{ route('country_list.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
   
              $('#Country_Forms').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
Toastr();
toastr.error(data.statusText+"Fill all Provided fields correctly")
$('#saveBtn').html('Save Changes');

          }
      });
    });
    



    $('body').on('click', '.deletecountries', function () {
     
        var coutntry_id = $(this).data("id");
        var html_coutntry_id = "<span style='color:yellow'> "+ coutntry_id + "</span>";
        {if(confirm("Are You sure You Want To Delete This Row")){}else{return false;}}
        $.ajax({
            type: "DELETE",
            url: "{{ route('dosageforms.store') }}"+'/'+coutntry_id,
            success: function (data) {
                Toastr();
toastr.error("Row with ID "+  html_coutntry_id  + " deleted successfully!!")
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>