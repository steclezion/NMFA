
<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
            <table id="example" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th {{ $i=0 }}>ID</th>
                    <th>Application Number</th>
                    <th>Reference Letter Number</th>
                    <th>Brand Name</th>
                    <th>Applicant Name</th>
                    <th>Dosage Form</th>
                    <!-- <th>Strength</th> -->
                    <th>Application Status</th>
                    <th>Action</th>


                </tr>
                </thead>
                <tbody {{ $i=1 }}>
                @foreach($applications as $application)
                    <tr>
                        <td width="10">{{ $i++ }}</td>
                        <td width="10"   id="application_number_{{ $application->application_number  }}">{{ $application->application_number }}</td>
                        <td width="10">{{  $application->PS_squential_number }}</td>

                        <td width="10" id="contact_person_name_{{ $application->PS_squential_number }}">
                        @php  echo str_ireplace(" ,","",$application->Name_of_the_product); @endphp
                        </td>

                        <td width="10" >
                        @php echo $application->cs_tradename; @endphp
                        </td>

                        <td>@php  echo str_ireplace(" ,","",$application->dosage_form ); @endphp</td> <td>


@php

if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; }
elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }

 @endphp
                            <span class="{{  $badge }}"> {{ $application->application_status }} </span>
                        </td>
                        <td>


                            <a href="javascript:void(0)" title="Download Query already Issued from Assessor"
                               data-toggle="tooltip" id="query_download"
                               data-id="{{ $application->PS_squential_number  }}"
                               data-original-title="Edit" class="edit btn btn-success btn-sm uploaded_assessor"> <i
                                        class="fas fa-download"></i> </a>


                            <a href="javascript:void(0)" data-toggle="tooltip" id="query"
                               title="Upload Response to Assessor"
                               data-id="{{ $application->PS_squential_number  }}"
                               data-application_number="{{ $application->application_number   }}"
                               data-original-title="Edit"
                               class="edit btn btn-primary btn-sm editquery">
                                <i class="fas fa-upload"></i> </a>

                        </td>

                    </tr>

                @endforeach
                </tbody>
                <tfoot>


                </tfoot>
            </table>
        </div>
