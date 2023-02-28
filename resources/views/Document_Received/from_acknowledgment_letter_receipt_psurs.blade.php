@extends('layouts.app')
@section('stylesheets')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<script rel="stylesheet" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>

@endsection
@section('content')
    
<div class="container">
       <!-- /.card -->
       <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i>
              Received Documents
            </h3>
          </div>
          <div class="card-body">

             <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a href="{{route('doc.index') }}" class="nav-link" id="custom-content-below-home-tab"  role="tab" aria-controls="custom-content-below-home" aria-selected="true">Acknowledgment letters </a>
              </li>
          


                 <li class="nav-item">
                <a class="nav-link" href="{{route('documents_invoice.invoice_received') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"> Invoices  </a>
              </li>


            <li class="nav-item">
            <a class="nav-link"  href="{{route('documents_financial_notification') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Financial Notifications</a>
            </li>

              
              <li class="nav-item">
                <a class="nav-link active" href="{{route('documents_psurs') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">PSURS</a>
              </li>


    <li class="nav-item">
                <a class="nav-link" href="{{route('documents_nmfa_alerts') }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Directors Alert Notification</a>
              </li>
            
            </ul>
           
            
                
    <!--<a class="btn btn-success" href="javascript:void(0)" id="createNewBook"> Create New Book</a>-->
    <table class="table table-bordered data-table">
        <thead>
                    <tr>
                    <th>ID</th>
                    <th>Registration Number</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Application Status</th>
                    <th > Application Type </th>
                    <th>Uploaded Date </th>
                    <th width="10%">Action</th>
                    </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   
</div>
            </div>
    
    
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>



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
         ajax: "{{ route('documents_psurs') }}",
       
        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'regnumber', name: 'regnumber','visible': true},
            {data: 'product_name', name: 'product_name','visible': true},
            {data: 'product_trade_name', name: 'product_trade_name ','visible': true},
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


  @endsection
  @section('scripts')
  <!-- DataTables  & Plugins -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>



@endsection