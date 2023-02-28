<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="bookForm" name="bookForm" class="form-horizontal">
                   <input type="hidden" name="application_id" id="application_id">

                    <div class="form-group">
                   <label class="">Application Number</label>
                   <div class="col-sm-12">
                   <input type="text" class="form-control" id="number_application"   readonly name="number_application" placeholder="Applicant"  required>

                   <input type="hidden" class="form-control" id="applicantt_id"   readonly name="applicantt_id" placeholder="Applicant"  required>

                   </div>
                   </div>
                <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Invoice Number</label>
                        <div class="row">
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="invoice_id" name="invoice_id" placeholder="Invoice" value="" maxlength="50" required="">
                       </div>
                       <div class="col-sm-3">
                       <span    class="btn btn-warning  btn-sm" id="generate_invoice" >Generate Invoice</span>
                       </div>
                       </div>
                       </div>





 <div class="form-group">
        <label class="col-sm-2 control-label">Total Amount</label>
        <div class="col-sm-12">
        <input id="amount_value"  type="number" min="500" onkeyup="AllowonlyText_Tele(event,'amount_value')" name="amount" required="" placeholder="Amount" class="form-control">
</div>

<br>


 <div class="form-group">
        <label class="col-sm-2 control-label">Remark</label>
        <div class="col-sm-12">
        <textarea id="remark" name="remark" required="" placeholder="Remark" class="form-control"></textarea>
        </div>
                    </div>

&nbsp;
<b> <hr>   </b>
<br>
                      <div class="form-group">

                      <textarea id='summernote' name='template_for_invoice' > 
                       <div id="rendered_template">

                       </div>
                       </textarea> 
                       
                       <!--id='print_inv' -->
                      
                       <a href=""  type="submit" style="display:none"   id='print_inv'  class='btn btn-warning float-right '><i class='fas fa-print'></i> Print/Generate To PDF</a>

                        </div>

<div  id="box_parent" style="border:1px solid green;margin:10px;width:400px;display:none">
<div id="box" style="background:#98bf21;height:50px;width:1px;border:1px solid green;"></div>
</div>

<p id="demo"></p>
                    <div class="col-md-offset-2 col-sm-10">
                     <button style="display:none"  type="submit" class="btn btn-primary" id="saveBtn" value="create">Save Invoice
                     </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ajaxModel_showinvoices" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="InvoiceForm" name="invoiceForm" class="form-horizontal">
                   <input type="hidden" name="application_id" id="application_id">
                   <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" />

                    <div class="form-group">
                   <label class="col-sm-2 control-label">Applicant ID</label>
                   <div class="col-sm-12">
                   <input type="text" class="form-control" id="applicantt_id"  disabled   name="applicantt_id" placeholder="Applicant"  required>

                   </div>
                   </div>
                <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Invoice Number</label>
                        <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button>
                   </div>


 <div class="form-group">
                        <label class="col-sm-2 control-label">Remark</label>
                        <div class="col-sm-12">
                            <textarea id="remark" name="remark" required="" placeholder="Remark" class="form-control"></textarea>
                        </div>
                    </div>

         <div class="col-md-offset-2 col-sm-10">
                     <button style="display:none"  type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
    </div>
</div>


<script>
  $(function () {
    // Summernote
     $('#summernote').summernote();
 

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  })
</script>







<script type="text/javascript">


  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var user_id = document.getElementById('user_idd').innerHTML;
 
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
        data:  {

            sam_id:user_id,
               },
        ajax: "{{ route('invoices.index','sam_id' )}}",

          columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'id', name: 'id', 'visible': false},
            {data: 'application_number', name: 'application_number','visible': true},
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'user_id', name: 'user_id','visible': false},
            {data: 'product_name', name: 'product_name','visible':true},
            {data: 'product_trade_name', name: 'product_trade_name','visible':true},
            {data: 'cs_tradename', name: 'cs_tradename','visible':true},
            // {data: 'name', name: 'name','visible':false},
            {data: 'application_type', name: 'application_type'},
           
            {data: 'remark', name: 'remark'},
            {data: 'amount', name: 'amount'},
            {data: 'action', name: 'action', orderable: true, searchable: true},
        ]
    });


	//  $('#print_inv').click(function () {
    //      var divName = 'print_invoice';

    //  var printContents = document.getElementById(divName).innerHTML;
    //  var originalContents = document.body.innerHTML;
    //      document.body.innerHTML = printContents;
    //      window.print();
    //      document.body.innerHTML = originalContents;
	    // window.location="{{ url('/invoice') }}";
        // });


      $('#generate_invoice').click(function () {

        var application_id = document.getElementById('applicantt_id').value;
        var remark = document.getElementById('remark').value;
        var user_id = document.getElementById('user_id').value;
        var invoice_id = document.getElementById('invoice_id').value;
        var amount_value = document.getElementById('amount_value').value;
        document.getElementById('box_parent').style.display = 'block';
        document.getElementById('demo').style.display = 'block';

        document.getElementById('rendered_template').style.display = 'none';
        if ( amount_value  == '') { alert('Fill amount and remark please!!');document.getElementById('amount_value').focus();return false;}
        if ( amount_value  == '' || amount_value  < 500 ) { alert('The Amount value is lower than the minimum payment 500');document.getElementById('amount_value').focus();return false;}


        if ( application_id == '')

        {  document.getElementById('application_id').focus(); return false; }
        $.ajax({
          url: "{{ url('/invoice_generate') }}",
          type: "POST",
          data:
          {
           application_id:application_id,
           user_id:user_id,
           remark:remark,
           amount_value:amount_value,
          },
          success: function (data) {
            document.getElementById('rendered_template').style.display = 'block';
    $("#box").animate({
      width: "400px"
    }, {
      duration: 500,
      easing: "linear",
      step: function(x) {

        $("#demo").text(Math.round(x * 100 / 400)  + "%");
        if(x==400)
        {
       document.getElementById('invoice_id').value= data.invoice_generated;
       document.getElementById('rendered_template').innerHTML = data.rendered_template;
    //    document.getElementById('print_inv').style.display="block";
       document.getElementById('saveBtn').style.display="block";
       document.getElementById('saveBtn').focus();
       document.getElementById('box_parent').style.display = 'none';
       document.getElementById('demo').style.display = 'none';
    //    $('#print_inv').attr("href", data.generated_path);


        }

      }
    });

Toastr();
//toastr.success("Invoice Generated successully")





          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });

    });




//       $('#print_inv').click(function () {

// var application_id = document.getElementById('applicantt_id').value;
// var remark = document.getElementById('remark').value;
// var user_id = document.getElementById('user_id').value;
// var  invoice_id = document.getElementById('invoice_id').value;

// if ( application_id == '')

// {  document.getElementById('application_id').focus(); return false; }
// $.ajax({
//   data:
//   {
//    application_id:application_id,
//    user_id:user_id,
//    remark:remark,
//   },
//   xhrFields: {
//                 responseType: 'blob'
//     },
//   url: "{{ url('/generate_pdf_invoice') }}",
//   type: "POST",
//   success: function(response){
//                 var blob = new Blob([response]);
//                 var link = document.createElement('a');
//                 link.href = window.URL.createObjectURL(blob);
//                 link.download = "Sample.pdf";
//                 link.click();
//             },
//             error: function(blob){
//                 console.log(blob);
//             }
// });

// });








    $('#createNewBook').click(function () {
        //alert("hellow Eyoba");
        $('#saveBtn').val("create-book");
        $('#application_id').val('');
        $('#bookForm').trigger("reset");
        $('#modelHeading').html("Generate Invoice");
        $('#ajaxModel').modal('show');
    });



    $('body').on('click', '.editBook', function () {

      var application_id = $(this).data('id');
      var application_number = $(this).data('app_number');

          $('#modelHeading').html("Generate Invoice");
          $('#saveBtn').val("edit-book");
          $('#applicantt_id').val(application_id);
          $('#number_application').val(application_number);

          $('#ajaxModel').modal('show');
          $('#application_id').val(application_id);
         document.getElementById('rendered_template').innerHTML = "";





   });




   $('body').on('click', '.showInvoices', function () {
      var application_id = $(this).data('id');


          $('#modelHeading').html(application_id);
          $('#saveBtn').val("edit-book");
          $('#applicantt_id').val(application_id);
          $('#ajaxModel_showinvoices').modal('show');
          $('#application_id').val(application_id);
         document.getElementById('rendered_template').innerHTML = "";


        });





    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');

        var  application_id = document.getElementById('applicantt_id').value;
        var  remark = document.getElementById('remark').value;
        var  user_id = document.getElementById('user_id').value;
        var  invoice_id = document.getElementById('invoice_id').value;
        var  amount_value = document.getElementById('amount_value').value;
        var  rendered_template = document.getElementById('rendered_template').innerHTML;
        var  invoice_generated_now =  document.getElementById('invoice_generated_now').innerHTML;
        var date_order = document.getElementById('date_order').innerHTML;

      if ( amount_value  == '') { alert('Fill amount and remark please!!');document.getElementById('amount_value').focus();return false;}
      if ( amount_value  == '' || amount_value  < 500 ) { alert('The Amount value is lower than the minimum payment 500');document.getElementById('amount_value').focus();return false;}

        // var cc = document.getElementById('cc').value;
        // var federal = document.getElementById('federal').value;
        // var accounts = document.getElementById('accounts').value;
        // var a_c = document.getElementById('a_c').value;
        // var director = document.getElementById('director').value;
        // var issued_by = document.getElementById('issued_by').value;

        document.getElementById('saveBtn').disabled= true;

        if ( application_id == '')

        {  document.getElementById('application_id').focus(); return false; }
        $.ajax({
          url: "{{ route('invoices.save_invoices_now') }}",
          type: "POST",
          data:
          {
           application_id:application_id,
           user_id:user_id,
           remark:remark,
           invoice_number:invoice_id,
           amount:amount_value,
           rendered_template:rendered_template,
           invoice_generated_now:invoice_generated_now,
           date_of_order:date_order,
        //    cc:cc,
        //    federal:federal,
        //    accounts:accounts,
        //    a_c:a_c,
        //    director:director,
        //    issued_by:issued_by,

          },
          success: function (data) {
            if( data.Message == true )
            {
              $('#bookForm').trigger("reset");
              $('#ajaxModel').modal('hide');
               table.draw();
      var Toast = Swal.mixin({
      toast: true,
      position: 'top-center',
      showConfirmButton: false,
      timer: 6000
       });
   document.getElementById('saveBtn').disabled= false;
   document.getElementById('saveBtn').innerHTML= 'Saving Changes';
   Toastr();
    toastr.success("Invoice Generated successully");
    table.draw();

            }

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
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






































