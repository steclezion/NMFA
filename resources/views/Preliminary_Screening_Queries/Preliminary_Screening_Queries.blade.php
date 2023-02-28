@extends('layouts.app')
@section('content')  

<!-- check_list_js.blade.php -->

<meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- DataTables -->

  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" >

 
<!--   
plugins -->
<!-- <script rel="javascript" src="{{ asset('/app/lib/ajax/jquery/1.9.1/jquery.js')}}" ></script> -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>



  <!-- SweetAlert2 -->
  <!-- <link rel="stylesheet" href={{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}> -->



<!-- <link rel="stylesheet" href="{{ asset('/app/lib/twitter-bootstrap/4.1.3/css/bootstrap.min.css')}}" >
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}"> -->
<!-- <script rel="javascript" src="{{ asset('/app/lib/1.10.16/js/jquery.dataTables.min.js')}}" ></script> -->
<!-- <script rel="javascript" src="{{ asset('/app/lib/4.1.3/js/bootstrap.min.js')}}" ></script> -->
<!-- <script rel="javascript" src="{{ asset('/app/lib/1.10.19/js/dataTables.bootstrap4.min.js')}}" ></script> -->
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>
<!-- Select2 -->

<!-- <script rel="javascript" src="{{ asset('/app/lib/ajax/jquery-validate/1.19.0/jquery.validate.js')}}" ></script> -->
<script rel="stylesheet" src="{{ asset('plugins/select2/js/select2.full.min.js')}}" ></script>
<script src="{{ asset('dist/js/demo.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>

<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}" >



<!--<link rel="stylesheet" href="{{ asset('3.3.6/bootstrap.min.css')}}" >-->
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.16/css/jquery.dataTables.min.css')}}" >
<link rel="stylesheet" href="{{ asset('/app/lib/1.10.19/css/dataTables.bootstrap4.min.css')}}" >

<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}" >
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">




<!-- Select2 -->

<div class="row" >
<div class="col-12">
<!-- Content Wrapper. Contains page content -->
  <div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Preliminary Screening Queries</h1>
          
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12" id="letter_acknowledgement">
            <!-- <div class="callout callout-info"> -->
              <!-- <h5><i class="fas fa-info"></i> Note:</h5>
             <img src="../../../images/nmfs.jpg"  alt="image" height="190" width="auto"/> -->
            <!-- </div> -->

<form method="POST" enctype="multipart/form-data" id="issue_preliminary_queries"     method="POST" name="issue_preliminary_queries"    accept-charset="utf-8" class="form-horizontal"  >
@csrf 

<textarea id="queries" type="text" class="form-control">  
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                 
                  <img src="../../../images/nmfa_header.png"  alt="image" style="width: 100%;height: auto;" />
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
  <div class="row invoice-info">
  
@foreach ($dosage_forms as $dosage_formss)  @endforeach
@foreach($issue_query as $checked) @endforeach

@foreach($country_contact_info as $country) @endforeach
<input hidden id="application_id" value="{{$checked->application_id}} " />
<input hidden id="application_number" value="{{ $checked->application_number  }} " />

<div class="container" id="template_redesigned" >
<div class="container"> 
 

  <p class="list-group">
  <div class="panel panel-default">

  <div class="panel-heading"  >Date: <span id="current_date"> @php $t=time(); echo date("d-m-Y",$t); @endphp</span> </div>
  <br/>
  <div class="panel-body"  >Ref: <span id="PS_squential_number"> {{ $random_application_RL_squential_number }} </span>  </div>
  <br/>
  <div class="panel-body">
 To: <span id="applicant_name"> {{ $checked->trade_name}} </span>  <br/>
 <ul>
    <li> <span id="state_plot_number"> {{ @$checked->cus_addline_one}} , {{ @$checked->cus_addline_two}}  </span> </li> <br/>
    <li> <span id="country"> {{ $country->country_name}} </span> </li>  </br>
  <!-- <li> [country]  </li>    </br> -->
  </ul>
</div>
</div>
  </p>

<style>
p,block {
    text-align: justify;
}
</style>
     


<b> Subject: Preliminary Screening Queries</b> 
<br/><br/>
<block style="text-align: justify;">
<p>Dear Sir/Madam or  <span id="contact_person_name"> {{ $checked->fullname_contact}} </span> ,</p>
<br/>

This is to inform you that preliminary assessment of 
<u> 
<b>
<input type="hidden" value="0" id="stregnth"  placeholder="strength" />
<span id="product_name"> {{ $checked->product_name   }} , </span>
<span id="dosage_forms"> {{  @$dosage_formss->name   }}, </span>
<span id="product_trade_name"> {{ $checked->product_trade_name  }} </span>
</b>
</u>

has been completed. The assessment of your application indicates the deficiencies listed
 below which you are requested to address for further processing: 
<br> &nbsp;&nbsp;&nbsp;
 
<br><br><br>

<br>
Note that if your response is not received within

 @if($number_days_receipts == '')@else
<!-- {{  $number_days_receipts }} -->
@endif
<span id="num_queries"><input type="number" min="0" width="10" hieght="10"   placeholder ="number" id="days_of_receipt" /> </span> days of receipt of this notification, the application will be considered closed and you may be required to re-apply
if you wish to continue with the application. 
The evaluation process will not start until the above-mentioned queries are addressed.



</block>
<br/><br/>

<p> Best regards,  </p>

<p> Iyassu Bahta,   </p>
<br>
 Director, National Medicines and Food Administration  <br>
Ministry of Health <br>
Asmara, Eritrea

                </div>
                <br><br>
    <p>
 </div>
<img src="../../../images/nmfa_footer.png"  alt="image" style="width: 100%;height: auto;"  />       
</p>        
                </div>
               
                </div>
                </textarea>
                <!-- /.col -->
                </form>
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
   <button type="button" class="btn btn-primary float-md-left" style="position:relative;left:15%;" 
                id="preliminary_screening">
                <i class="fas fa-save"></i> Save
                </button>

                 <!-- <a type="button" href="{{ url()->previous() }}"  class="btn btn-primary float-md-right" style="position:relative;left:100px;" >
                <i class="fas fa-arrow-circle-left"></i> Back
                </a> -->
                <br>  <br>  <br>
   <div id="actions_to_assessor"  style="float-right;display:none"> 
                  <a href=""  id="get_path" rel="noopener" target="_blank"   style="position:relative;left:15%;" class="btn btn-warning"><i class="fas fa-download"></i> Download </a>
                  &nbsp;  &nbsp;  &nbsp;

                  @if($path_uploaded_query == '')
                  <!-- <button type="button" class="btn btn-info float-right"   id="upload_query">
                  <i class="fas fa-upload"></i> Send To Applicant  ( {{ $checked->fullname_contact }})
                 </button> -->

                   <a type="button" href="{{ url()->previous() }}"  class="btn btn-primary float-md-right" style="position:relative;left:100px;" >
                <i class="fas fa-arrow-circle-left"></i> Back
                </a>

                 
                 <a href=""  style="display:none" id="get_path_uploaded" rel="noopener" target="_blank" class="btn btn-warning"><i class="fas fa-download"></i> Download Uploaded Doc </a>

                  @else
                  <!-- <a href="{{ $path_uploaded_query }}"  id="get_path_uploaded" rel="noopener" target="_blank" class="btn btn-warning"><i class="fas fa-download"></i> Download Uploaded Doc </a> -->
                  

                    <a type="button" href="{{ url()->previous() }}"  class="btn btn-primary float-md-right" style="position:relative;left:100px;" >
                <i class="fas fa-arrow-circle-left"></i> Back
                </a>
                   @endif






<br>
    </div>
                  <!-- <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button> -->
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>





  
</div>
<!-- ./wrapper -->


<!-- <script src="{{ asset('dist/js/demo.js"></script> -->
<!-- Page specific script -->
<script>
  $(function () {
    // Summernote
    $('#queries').summernote();
    $('#summernotee').summernote();
    $('#summernote_Remark_section_four').summernote();

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  })
</script>
        
@include('layouts.preliminary_screening_js')

@include('layouts.modal_upload_issued_query')


@endsection