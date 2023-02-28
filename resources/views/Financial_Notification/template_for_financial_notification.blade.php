@extends('layouts.app')
@section('content')  

        <!-- Table row -->

                    
<!-- check_list_js.blade.php -->

<meta name="csrf-token" content="{{ csrf_token() }}">


<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" >
  <!-- CodeMirror -->
  <link rel="stylesheet" href="{{ asset('plugins/codemirror/codemirror.css') }} ">
  <link rel="stylesheet" href="{{ asset('plugins/codemirror/theme/monokai.css') }}">
  <!-- SimpleMDE -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
 
<!--   
plugins -->
<script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href={{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}>



<link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" >
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!--<link rel="stylesheet" href="{{ asset('3.3.6/bootstrap.min.css')}}" >-->
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >
    <!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}" >



<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->
<script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<script src="{{ asset('dist/js/demo.js')}}" ></script>


<div class="row" >
          <div class="col-12">
<!-- Content Wrapper. Contains page content -->
  <div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h6>Financial Notification </h6>
          
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


<textarea id="summernote" name="template_for_notification" > 


  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12" id="letter_acknowledgement">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
               <div class="container">
                  <img src="../../../images/nmfa_header.png"  class="img-responsive" style="width: 100%;height: auto;"  alt="image" height="140" width="800"/>
                </div>
              </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->

  <!-- Main content -->
  <div class='invoice'>
      <!-- title row -->
      <div class='row'>
        <div class='col-12'>
   
          <h4>
           
@foreach($applications as $apps)   @endforeach
<input type="hidden" value="{{  $id }}" id="application_id" name="" />
<span hidden> <i class='fas fa-globe'>  </i>  </span>
<small class='float-right' style='position: absolute;left: 80%; '>Date: <span id="current_date"> @php $t=time(); echo date("d-m-Y",$t); @endphp </span></small>

<br> <h2  style='position: absolute;left: 28%; ' > Financial Notification </h2> </h4>
</div>
</div>


<br><br> <br><br>    <br><br> <br><br> 

<div class='row invoice-info' style='position: absolute;left: 0%; top: 15%;'>
<div class='col-lg-12'    >
<!-- <b> Customer’s Name :</b> <span id="fullname_contact"> {{ $apps->cfname." ".$apps->cmname." ".$apps->clname }}  </span> <br> -->

<b> Customer’s Name :</b> <span id="fullname_contact"> {{ $apps->trade_name }}  </span> <br>


<b>Ministry of Finance receipt number:</b> <span id="receipt_number"> {{  $apps->receipt_number }}   </span> <br>
<b>Date of order:</b>  <span id="financial_notification_date_of_order">{{ $apps->date_of_order}}  </span> <br>
</div>
<!-- /.col -->
</div>



<p> The payment for registration fee has been completed successfully. The details of the payment is provided below. </p>

 <div class='row' id="financial_notification">
        <div class='col-12'>
        <br/> 

<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 50px;
}
</style>

           <table class='table table-striped table-bordered table-hover table-condensed' id="examplee">
              <thead>
              <tr>
                <th width="1%">No</th>
                <th width="10%">Purpose: Application Registration </th>
                <th width="8%">Unit price (USD)</th>
                </tr>
              </thead>
              <tbody>
              <tr>
           <td > </td>
           <td > </td>
           <td > </td>
       
           </tr>
           <tr>
           <td > </td>
           <td > </td>
           <td > </td>
        
           </tr>

               <tr>
           <td > </td>
         
                <td  > Total Amount (USD) </td>
                <td > Xxx USD</td>
           </tr>


           <tr>
           <td > </td>
         
                <td width="20%">Total Amount equivalent in NAKFA</td>
                <td > Xxx NKF
              </td>
           </tr>
</tbody>
</table>
<p> Amount in words: Xxx US DOLLARS ONLY, equivalent to Xxx NAKFA ONLY.  </p>
<br/><br/>

  <p id="nmfa_info">
  Issued by: Iyassu Bahta 
  <br>
  Director, National Medicines and Food Administration
  <br>
  Ministry of Health
  <br>
  Asmara, Eritrea
  <br> 
  Cc: Department of Administration and Finance, MoH, Eritrea 
  </p>
  <br><br><br>
  </div>    
 <!-- <button id="addRow" class="btn btn-success btn-sm"><i class="fas fa-plus"> </i></button> -->
 </div>
          <!-- /.col -->
</div>
</div>

<br><br><br>


<div class="container">
<img src="../../../images/nmfa_footer.png"  class="img-responsive" alt="image" style="width: 100%;height: auto;"/>     
</div>  
</p>        
</div>
</div>
    <!-- /.content -->
</div>




 </textarea>

  
        <div class='row'>
        <div class='col-12' table-responsive  >

<button type="button" class="btn btn-primary float-md-left" style="margin-right: 5px;" 
id="financial_notification_save"> <i class="fas fa-save"></i> Save  </button>

<a href="{{ url()->previous() }}" type="button" class="btn btn-info float-md-right" style="margin-right: 5px;" 
id="acknowledgment_letter"> <i class="fas fa-arrow-circle-left"></i> Back </a>
          

<br>  <br>  <br>


        </div>
        </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->



       </div>
        </div>
        </div>

        </div>
        </div>

        </div>
        </div>

          <!-- /.col -->
        </div>
             </div>
          <!-- /.col -->
        </div>
</div>
<!-- ./wrapper -->

<div>

<script>

$(document).ready(function() {
    var t = $('#examplee').DataTable();
    var counter = 1;
 
    $('#addRow').on( 'click', function () {
        t.row.add( [
           counter ,'<input type="text" id="par" placeholder="" name="par_"> ','<input type="number" id="xxx_usd" placeholder="" name=""> '

            
            
           
           
        ] ).draw( false );
 
        counter++;
    } );
 
    // Automatically add a first row of data
    $('#addRow').click();
} );


</script>






<script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });


  $("#financial_notification").click(function(){
  $('#financial_notification_date_of_order').css("background-color", "#ffffff");
  });



$('#financial_notification_save').click(function () {
        //alert("hellow Eyoba");
  if (confirm("Are you sure you want to save this letter of financial Notification."+
             "Changes will not be reverted.") == true) {
  
   var To_be_rendered = document.getElementById('financial_notification').innerHTML ; 
   var nmfa_info = document.getElementById('nmfa_info').innerHTML; 
   var application_id = document.getElementById('application_id').value;
   var financial_notification_date_of_order = document.getElementById('financial_notification_date_of_order').innerHTML;
   var fullname_contact = document.getElementById('fullname_contact').innerHTML;
   var receipt_number = document.getElementById('receipt_number').innerHTML ;
   var current_date = document.getElementById('current_date').innerHTML ;
  
  
  document.getElementById('financial_notification_save').disabled = true;



     $.ajax({

url: "{{ route('save_financial_notification')  }}",
type:'POST',
data: {
  To_be_rendered:To_be_rendered,
  nmfa_info:nmfa_info,
  application_id:application_id,
   financial_notification_date_of_order:financial_notification_date_of_order,
  fullname_contact:fullname_contact,
  receipt_number: receipt_number,
  current_date:current_date,
},
processData: true,
success: (data) => {
if(data.Message==true)  
{
//document.getElementById('table_upload_cv').innerHTML = data.Data_returned;


var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 6000
                  }); 
                  
    

 toastr.success("Financial notification saved successfully")
 var id = setInterval(finance_not, 2000);
              function finance_not() {
              window.location = "/generating_financial_notifications";
              clearInterval(id);
        }

} 
else
{
    
this.reset();
 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    }); 


$('#UploadData').html('Save changes');
document.getElementById('UploadData').disabled = false;
var contact_person  = document.getElementById('contact_person_name').innerHTML;
$('#app_name').val(contact_person.toUpperCase().trim());
toastr.error('Allowed Files Type is only .PDF (PDF Document)')

           }
},

error: function(data){
console.log(data);
}

});

}

  else { return false;}


});








  });
</script>


<script>
  $(function () {
    // Summernote
     $('#summernote').summernote();
    // $('#summernotee').summernote();
    // $('#summernote_Remark_section_four').summernote();

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  })
</script>


@endsection