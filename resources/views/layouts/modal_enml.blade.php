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
                <form id="EnlmForm" name="EnlmForm" class="form-horizontal">
                   <input type="hidden" name="enlm_id" id="enlm_id">
                    <div class="form-group">
                        <label  class="">Medicine Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Medicine Name" value="" maxlength="50" required="">
                        </div>
                    </div>
     
                    <div class="form-group">
                        <label class="">Medcine ID</label>
                        <div classS="col-sm-12">
                            <!-- <textarea id="medicine_id" name="medicine_id" required="" placeholder="Medicine ID" class="form-control"></textarea> -->
                            <input type="number"  id="medicine_id" name="medicine_id" required  placeholder="Medicine ID" class="form-control" maxlength="50" required="">

                        </div>
                    </div>

                           <div class="form-group">
                        <label class="">Description</label>
                        <div classS="col-sm-12">
                            <textarea id="product_description" name="product_description" required  placeholder="Description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="">Is ENML</label>
                        <div classS="col-sm-12">
                            <!-- <textarea id="is_enlm" name="is_enlm" required="" placeholder="Enml" class="form-control"></textarea> -->
                            <input type="checkbox"  id="is_enlm" name="is_enlm" required  placeholder="Is ENML" class="form-control"/>

                        </div>
                    </div>

                           <div class="form-group">
                        <label class="">Is Approved</label>
                        <div classS="col-sm-12">
                            <!-- <textarea id="is_approved" name="is_approved" required="" placeholder="Is Approved" class="form-control"></textarea> -->
                            <input type="checkbox"  id="is_approved" name="is_approved" required="" placeholder="Is Approved" class="form-control"/>
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
        ajax: "{{ route('enlm.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'product_name', name: 'product_name'},
            {data: 'medicine_id', name: 'medicine_id'},
            {data: 'product_description', name: 'product_description'},
            {data: 'Is Enlm', name: 'is_enlm'},
            {data: 'Is Approved', name: 'is_approved'},
            {data: 'action', name: 'action', orderable: true, searchable: true},
        ]
    });


    
    $('#createNewEml').click(function () {
        //alert("hellow Eyoba");
        $('#saveBtn').val("create-book");
        $('#enlm_id').val('');
        $('#EnlmForm').trigger("reset");
        $('#modelHeading').html("Create New Medicines");
        $('#ajaxModel').modal('show');
        $('#medicine_id').removeClass("blue");
    });



    $('body').on('click', '.editEnlm', function () {
      var enlm_id = $(this).data('id');
      $('#medicine_id').removeClass("blue");
      $.get("{{ route('enlm.index') }}" +'/' + enlm_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Medicine");
          $('#saveBtn').val("edit-enlm");
          $('#ajaxModel').modal('show');
          $('#enlm_id').val(data.id);
          $('#product_name').val(data.product_name);
         

          $('#saveBtn').html("<i class='fas fa-edit'>  </i>Update");
          $("#saveBtn").addClass("btn btn-success");

          if(data.is_approved ==1) { document.getElementById('is_approved').checked =true; } else if(data.is_approved == 0) { document.getElementById('is_approved').checked =false; } 
          if(data.is_enlm ==1) { document.getElementById('is_enlm').checked =true; } else if(data.is_enlm == 0) { document.getElementById('is_enlm').checked =false; } 

      })
   });
   
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        if( $('#medicine_id').val() =='') { document.getElementById('medicine_id').focus();
            $('#medicine_id').addClass("blue");
        return false;}
        $(this).html('Save');
    
        $.ajax({
          data: $('#EnlmForm').serialize(),
          url: "{{ route('enlm.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
   
              $('#EnlmForm').trigger("reset");
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
    



    $('body').on('click', '.deleteenlm', function () {
     
        var enlm_id = $(this).data("id");
        var html_enlm_id = "<span style='color:yellow'> "+ enlm_id + "</span>";
        {if(confirm("Are You sure You Want To Delete This Row")){}else{return false;}}
        $.ajax({
            type: "DELETE",
            url: "{{ route('enlm.store') }}"+'/'+enlm_id,
            success: function (data) {
                Toastr();
toastr.error("Row with ID "+  html_enlm_id  + " deleted successfully!!")
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>