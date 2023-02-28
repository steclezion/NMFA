<script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-tokenn"]').attr('content')
          }
    });



    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
         ajax: "{{ route('doc.index') }}",
       
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'app_number', name: 'app_number','visible': true},
            {data: 'product_name', name: 'product_name','visible': true},
            {data: 'product_trade_name', name: 'product_trade_name','visible': true},
            {data: 'cs_tradename', name: 'cs_tradename','visible': true},
            {data: 'application_status', name:'application_status','visible': false},
            {data: 'application_typee', name: 'application_typee', orderable: true, searchable: true},
            {data: 'ackdate', name: 'ackdate', orderable: true, searchable: true},

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
      var application_number = $(this).data('id');
    //   $.get("{{ route('assignment.index') }}" +'/' + application_number +'/edit', function (data) {
          $('#modelHeading').html("Assigning To");
          $('#app_id').html(application_number);
          $('#saveAssign').val("Assign");
          $('#ajaxModel').modal('show');
        //   $('#application_id').val(data.id);
        //   $('#title').val(data.title);
        //   $('#author').val(data.author);
    //   })
   });
   


   
    $('#saveAssign').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
        var assigned_To = document.getElementById('user_id').value;
        var assigned_By = document.getElementById('user_idd').innerHTML;
        var application_id = document.getElementById('app_id').innerHTML;


        $.ajax({
        //   data: $('#assignForm').serialize(),
        data:{      assigned_To:assigned_To,
                    assigned_By:assigned_By,
                    application_id:application_id,
                              },
          url: "{{ route('assignment.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
   
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