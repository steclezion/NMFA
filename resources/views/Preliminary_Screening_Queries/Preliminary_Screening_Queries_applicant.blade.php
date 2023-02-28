@extends('layouts.app')
@section('stylesheets')
<!-- <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}"> -->
@endsection

@section('content')



<meta name="csrf-token" content="{{ csrf_token() }}">

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
                <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Queries and Responses</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                              <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>Application Number</th>
                    <th> Letter reference Number</th>
                    <th>Brand Name</th>
                    <th>Dosage Form</th>
                    <!-- <th>Strength</th> -->
                    <th>Application Status</th>
                    <th>Action</th>
                    
                
                  </tr>
                  </thead>
                  <tbody {{ $i=1 }}>
                  @foreach($applications as $application)
               <tr>
                    <td width="10" >{{ $i++ }}</td>
                    <td  width="10" >{{ $application->application_number }}</td>
                    <td   width="10" >{{  $application->PS_squential_number }}</td>
                    <td   width="10"  id="contact_person_name_{{ $application->PS_squential_number }}">{{ $application->Name_of_the_product}}</td>
                    <td>{{ $application->dosage_form }}</td>
                   <!-- <td>{{ $application->strength }} </td> -->
                   <td> 
                   @php
              if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
              elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; $application->application_status= 'Dossier Evaluation in progress'; }
              elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
              
                    @endphp 
                   
                    <span class="{{  $badge }}"> {{ $application->application_status }} </span>
                    </td>
                   <td>   
             

      <a href="javascript:void(0)" title="Download Query already Issued from Assessor" data-toggle="tooltip" id="query_download" data-id="{{ $application->PS_squential_number  }}" 
      data-original-title="Edit"  class="edit btn btn-success btn-sm uploaded_assessor"> <i class="fas fa-download"></i>  </a>
                
                  
            


      <a href="javascript:void(0)" data-toggle="tooltip" id="query"
      title="Upload Query to Assessor"
              data-id="{{ $application->PS_squential_number  }}" 
              data-original-title="Edit" 
              class="edit btn btn-primary btn-sm editquery">
              <i class="fas fa-upload"></i>  </a>

                  </td>

                  </tr>
        
                  @endforeach
                  </tbody>
                  <tfoot>
                
                  
                  </tfoot>
                </table>
                            </div>
                           </div>
                         </div>
                            <!-- /.card-body -->
                      </div>
                 </div>
           </div>
      </div>
  </section>

  @endsection
  @section('scripts')
  <!-- DataTables  & Plugins -->

<script>

</script>




























































      


@include('layouts.modal_upload_issued_query_from_applicant')

@include('layouts.modal_upload_issued_query_from_asessor')



@endsection