<div class="tab-pane fade show active" id="custom-tabs-three-overview"
     role="tabpanel" aria-labelledby="custom-tabs-three-overview-tab">
    <!-- this is overview tab -->


    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-gray" id="data">
            <div class="card-header">
                <h2 class="card-title">Variation Evaluation Overview</h2>
                <div class="card-tools bootstrap4">


                    <a href="#" class="btn btn-sm btn-primary no-print" onclick="print_tab_overview()"
                       id="print_overview_btn"><i class="fas fa-print "></i>
                        Print Overview</a>


                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-8 ">
                        <div class="row">  {{--the three status boxes--}}
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-gray-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Variation Reference Number</span>
                                        <span
                                                class="info-box-number text-center text-muted mb-0">{{ $variation->variation_reference_number }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-gray-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Deadline</span>
                                        <span class="info-box-number text-center text-muted mb-0">
                                           {{$variation->deadline}}

                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-gray-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Evaluation Mode </span>
                                        <span class="info-box-number text-center text-muted mb-0">

                                                @if($variation->application_type==2)
                                                Fast Track
                                            @else
                                                Standard
                                            @endif
                                            {{--<input type="hidden" id='application_type_id' value="{{$dossier_evaluation_details->application_type}}" />--}}
                                            </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{---------------------------------------------------------------------------------------------}}
                                <h4>General Information</h4>
                                <div class="post">
                                    <div class="user-block-sm">
                                        {{--<img class="img-circle img-bordered-sm"
                                             src="../../dist/img/user1-128x128.jpg" alt="user image">--}}
                                        <span class="username">
                                        Assessor: <b
                                                    class="text-blue">{{ $variation->first_name }} {{ $variation->middle_name }}</b>
                                        </span>
                                        <br/>
                                        <span class="description text-sm">{{ $variation->assessor_email }} </span>
                                    </div>
                                    <!-- /.user-block -->
                                    <br/>
                                    <table class="table table-sm table-borderless table-condensed">
                                        <tbody>
                                        <tr>
                                            <td width="25%">Date Assigned</td>
                                            <td class="text-left">
                                                <b>{{ $variation->assigned_datetime }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Supervisor</td>
                                            <td class="text-left">
                                                <b>{{ $variation->name }} {{ $variation->m_name }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Registration Number</td>
                                            <td class="text-left">
                                                <b>{{ $variation->registration_number }}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                {{---------------------------------------------------------------------------------------------}}
                                <h4>Applicant Information</h4>
                                <div class="post clearfix">
                                    <div class="user-block-sm">
                                        {{--<img class="img-circle img-bordered-sm"
                                             src="../../dist/img/user7-128x128.jpg" alt="Company Logo Image">--}}
                                        <span class="username">
                                         <b class="text-blue">{{ $company->trade_name }}</b>
                                        </span>
                                        <br/>
                                        <span class="description text-sm"> {{$company->city}}, {{$company->state }}</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <br/>
                                    <table class="table table-sm table-borderless table-condensed">
                                        <tbody>
                                        <tr>
                                            <td width="25%">Website</td>
                                            <td class="text-left">
                                                <b>{{ $company->webiste_url }} </b>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Phone</td>
                                            <td class="text-left">
                                                <b>{{ $company->telephone }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td class="text-left">
                                                <b>{{ $company->email }}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                {{---------------------------------------------------------------------------------------------}}

                                <h4>Agent Information</h4>
                                <div class="post clearfix">
                                    <div class="user-block-sm">
                                        {{-- <img class="img-circle img-bordered-sm"
                                              src="../../dist/img/user7-128x128.jpg" alt="Agent Image">--}}
                                        <span class="username">

                          <b class="text-blue">{{ $agent->trade_name }}</b>
                        </span> <br/>
                                        <span class="description text-sm"> {{$agent->city}}, {{$agent->state}}</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <br/>
                                    <table class="table table-sm table-borderless table-condensed">
                                        <tbody>
                                        <tr>
                                            <td width="25%">Contact Person</td>
                                            <td class="text-left">
                                                <b>{{ $agent_contact_person->first_name }} {{ $agent_contact_person->last_name }}</b>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Contact Person Phone</td>
                                            <td class="text-left">
                                                <b>{{ $agent_contact_person->telephone }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Contact Person Email</td>
                                            <td class="text-left">
                                                <b>{{ $agent_contact_person->email }}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                {{---------------------------------------------------------------------------------------------}}
                                <h4>Product Information</h4>
                                <div class="post">
                                    <div class="user-block-sm">
                                        {{--<img class="img-circle img-bordered-sm"
                                             src="../../dist/img/user1-128x128.jpg" alt="user image">--}}
                                        <span class="username">
                          <b class="text-blue">{{ $application->medicinal_product->medicine->product_name}}</b>
                        </span><br/>
                                        <span
                                                class="description text-sm">{{ $application->medicinal_product->product_trade_name }}</span>
                                    </div>
                                    <!-- /.user-block -->

                                    <br/>
                                    <table class="table table-sm table-borderless table-condensed">
                                        <tbody>
                                        <tr>
                                            <td width="25%">Dosage Form</td>
                                            <td class="text-left">
                                                <b>{{ $variation->dosage_name }}</b>
                                            </td>
                                        </tr>

                                        {{--<tr>
                                            <td>Strength</td>
                                            <td class="text-left">
                                                <b>{{ $application->medicinal_product->strength_amount_strength_unit}}</b>
                                            </td>
                                        </tr>--}}

                                        </tbody>
                                    </table>
                                </div>
                                {{---------------------------------------------------------------------------------------------}}


                            </div>
                        </div> {{--the Information paragraphes--}}
                    </div>
                    {{------------------------- PROGRESS ------------------------------}}

                    <div class="col-12 col-md-8 col-lg-4 text-muted">
                        <div class="post">
                        <h4 class="text-primary"><i class="fas fa-project-diagram"></i> Progress</h4>
                       
                            <table class="table table-sm table-borderless table-condensed">
                                <tbody>
                                <tr>
                                    <td width="40%">Date Started</td>
                                    <td><b>{{$main_task->start_time}}</b></td>
                                </tr>

                                <tr>
                                    <td>Status </td>
                                    <td><span class="badge badge-secondary"> {{$main_task->task_status}}</span></td>
                                </tr>


                                </tbody>
                            </table>

                        </div> {{--end class post / will create horiz. line--}}


                        {{--<div class="post">

                        <h4>Variation Home Directory</h4>

                        <p> {{ $attachment->path }}</p>

                        </div>--}}
 <div class="post">
                        <h4>Variation Document</h4>
                        <ul class="list-unstyled">
                        
                            <li>
                                <a href="{{ asset($variation_document->path) }}" target="_blank" class="btn-link text-lg text-danger"><i class="far fa-fw fa-file-pdf"></i>
                                    Cover Letter</a>
                            </li>
                            <li>
                                <a href="{{ asset($attachment->path) }}" target="_blank" class="btn-link text-lg text-danger"><i class="far fa-fw fa-file-archive"></i>
                                    Variation Document</a>
                            </li>
                         
                        </ul>

                        </div>
                        <div class="post">
                        <h4>Assessment Report</h4>
                        <ul class="list-unstyled">
                        @if(isset($variation->assessment_report_document_id) and $current_user_id!=$variation->applicant_id )
                            <li>
                                <a href="{{ asset($assessment_report->path) }}" target="_blank" class="btn-link text-lg text-danger"><i class="far fa-fw fa-file-pdf"></i>
                                    Assessment Report</a>
                            </li>
                            @endif
                         
                        </ul>

                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>



    <script src="{{asset('plugins/word_converter/printThis.js') }}"></script>
    <script>

        function print_tab_overview() {
            document.getElementById('print_overview_btn').hidden = true;
            $('#data').printThis({});
            document.getElementById('print_overview_btn').hidden = false;

        }
    </script>
    <!-- /.content -->


</div>
