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
            @foreach($check_list as $checked) @endforeach
            <a href="{{ url('/psur_acknowledgment_list') }}" <span type="button" class="btn btn-primary float-md-right">
                <i class="fas fa-arrow-alt-circle-left"></i> Back
                </span>
            </a

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        <span hidden id="application_number"> {{ $checked->application_number}}   </span>

           <div class="col-sm-12">
                            <h1> Acknowledgement letter for the receipt of PSUR</h1>

                        </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


<textarea id="summernote" name="template_for_acknowledgment_letter" > 


<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 2px solid #ddd;
}
</style>

<section class="content" >
      <div class="container-fluid">
        <div class="row">
          <div class="col-12" id="letter_acknowledgement">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
            
            @foreach($check_list as $checked) @endforeach @foreach($country_contact_info as $country) @endforeach
            <input hidden id="application_id" value="{{$checked->application_id}} " />

            <input hidden id="psur_refrence_number" value="{{$psur_reference_number->psur_refrence_number}} " />
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


  <div class='row' id="letter_psur_acknowledgment">
        <div class='col-12'>
<div class='row invoice-info' style='position: absolute;left: 0%; top: 15%;'  >
@foreach($check_list as $checked) @endforeach @foreach($country_contact_info as $country) @endforeach

</div>


  <p class="list-group"  >
  <div class="panel panel-default">

  <div class="panel-heading"> Date: <span id="current_date"> @php $t=time(); echo date("d-m-Y",$t); @endphp</span>    </div>
  <br/>
  <div class="panel-body"> Ref: <span id="RL_squential_number"> {{ $random_application_RL_squential_number }} </span>  </div>
  <br/>
  <div class="panel-body">
 To: <span id="applicant_name"> {{ $checked->trade_name}}   </span>  <br/>
 <ul>
    <ol> <span id="state_plot_number"> {{ $checked->address_line_one}} , {{ $checked->address_line_two}}  </span> </ol> <br/>
    <ol> <span id="country"> {{ $country->country_name}} </span> </ol>  </br>
  <!-- <li> country  </li>    </br> -->
  </ul>
</div>
</div>
  </p>

     

@foreach ($dosage_forms as $dosage_formss)  @endforeach

<b> Subject: Acknowledgement Letter for the receipt of a Periodic Safety Update Report (PSUR) of  </b> <br>
  {{ $checked->product_name }} {{ $checked->dname }},  {{ $checked->product_trade_name   }}
<br/><br/>
<block style="text-align: justify;">
<p>Dear Sir/Madam or  <span id="contact_person_name"> {{ $checked->first_name}} {{ $checked->middle_name}} {{ $checked->last_name}}  </span> ,</p>
<br/>


Reference is made to your letter dated @php echo date("d-m-Y",$t); @endphp (ref.<b> xxxxxxxxx </b>  ) regarding the Product safety updates review (PSUR)
 of {{ $checked->product_name }} {{ $checked->dname }}, {{ $checked->product_trade_name   }}.
Accordingly, the National Medicines and Food Administration (NMFA), Ministry of Health (MOH) confirms that the PSUR has been received. 
Please be informed that the NMFA may request further information if deemed necessary.
<br><br>
<p> The continuous updates in regards to your product is well appreciated. </p>

</block>

<br/><br/>
<p> Best regards,  </p>

Iyassu Bahta <br>
Director, National Medicines and Food Administration<br>
Ministry of Health <br>
Asmara, Eritrea <br>

          <!-- /.col -->
</div>
</div>

<div class="container">
<img src="../../../images/nmfa_footer.png"  class="img-responsive" alt="image" style="width: 100%;height: auto;"/>     
</div>  
</p>        
</div>
</div>
    <!-- /.content -->
</div>




 </textarea>

  
  <div class="col-12">
        @if($path == '')
        <button type="button" class="btn btn-primary float-md-left" style="margin-left: 15px;" title="save acknowledgment letter" id="acknowledgment_letter_psur">
                <i class="fas fa-save"></i> Save
                </button>

        <div id="actions_to_applicant" style="float-right;display:none">
            <a href="" id="get_path" rel="noopener" target="_blank" class="btn btn-warning"><i class="fas fa-download"></i> Download </a> &nbsp; &nbsp; &nbsp;

            <button type="button" class="btn btn-info float-right" 
            id="upload_acknowledgment_letter_psur">
                  <i class="fas fa-upload"></i> Send To Applicant  ( {{ $checked->first_name}} {{ $checked->middle_name}} {{ $checked->last_name}}  )
                  </button> 
                  
                  @else



            <div id="actions_to_applicant" style="float-right;display:block">
                <a href="{{ $path }}" id="get_path" rel="noopener" target="_blank" class="btn btn-warning"><i class="fas fa-download"></i> Download </a> &nbsp; &nbsp; &nbsp;

                <button type="button" id="upload_acknowledgment_letter_psur" class="btn btn-info"> <i class="fas fa-upload"></i> Send To Applicant ( {{ $checked->first_name}} {{ $checked->middle_name}} {{ $checked->last_name}}
                  </button> @endif
</div> 

<br>  <br>  <br>


        </div>
        </div>




       </div>
        </div>
        </div>
</div>



<script>
    $(function() {
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





        </section>
 




@include('layouts.acknowledgement_list_js') 

@include('layouts.modal_upload_acknowledgment_letter_psur')

 @endsection