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
                <form id="Dosage_Forms" name="Dosage_Forms" class="form-horizontal">
                   <input type="hidden" name="dosage_id" id="dosage_id">

                    <div class="form-group">
                    <label  class="">Route Name</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control" id="dosage_name" name="name" placeholder="Route Name" value="" maxlength="50" required="">
                    </div>
                    </div>

                      <div class="form-group">
                    <label  class="">Description</label>
                    <div class="col-sm-12">
                    <textarea id="description" name="description" required  placeholder="Description" class="form-control"></textarea>
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
        ajax: "{{ route('route_of_administration.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
           {data: 'action', name: 'action', orderable: true, searchable: true},
        ]
    });


    
    $('#createNewDosage').click(function () {
        //alert("hellow Eyoba");
        $('#saveBtn').val("create-Dosage");
        $('#dosage_id').val('');
        $('#Dosage_Forms').trigger("reset");
        $('#modelHeading').html("Create New Route");
        $('#ajaxModel').modal('show');
        //$('#Dosage_id').removeClass("blue");
    });



    $('body').on('click', '.editdosage', function () {
      var dosage_id = $(this).data('id');
      $('#Dosage_id').removeClass("blue");
      $.get("{{ route('route_of_administration.index') }}" +'/' + dosage_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Route Name");
          $('#saveBtn').val("edit-enlm");
          $('#ajaxModel').modal('show');
          $('#dosage_id').val(data.id);
          $('#dosage_name').val(data.name);
          $('#description').val(data.description);
          $('#saveBtn').html("<i class='fas fa-edit'>  </i>Update");
          $("#saveBtn").addClass("btn btn-success");

      })
   });
   
    $('#saveBtn').click(function (e) {
        e.preventDefault();
      
 
 if(document.getElementById('dosage_name').value == '') {  $('#dosage_name').addClass("blue"); document.getElementById('dosage_name').focus();  return false;}
 if(document.getElementById('description').value == '') {  $('#description').addClass("blue"); document.getElementById('description').focus();  return false;}
     
 $(this).html('Save');

        $.ajax({
          data: $('#Dosage_Forms').serialize(),
          url: "{{ route('route_of_administration.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
   
              $('#Dosage_Forms').trigger("reset");
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
    



    $('body').on('click', '.deletedosage', function () {
     
        var dosage_id = $(this).data("id");
        var html_dosage_id = "<span style='color:yellow'> "+ dosage_id + "</span>";
        {if(confirm("Are You sure You Want To Delete This Row")){}else{return false;}}
        $.ajax({
            type: "DELETE",
            url: "{{ route('route_of_administration.store') }}"+'/'+dosage_id,
            success: function (data) {
                Toastr();
toastr.error("Row with ID "+  html_dosage_id  + " deleted successfully!!")
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>