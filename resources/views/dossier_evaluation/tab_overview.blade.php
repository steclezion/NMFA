<div class="tab-pane fade" id="custom-tabs-three-overview"
     role="tabpanel" aria-labelledby="custom-tabs-three-overview-tab">
    <!-- this is overview tab -->


    <!-- Main content -->
    <section class="content" >

        <!-- Default box -->
        <div class="card card-outline card-gray" id="data">
            <div class="card-header">
                <h2 class="card-title">Dossier Evaluation Overview</h2>
                <div class="card-tools bootstrap4">


                        <a href="#" class="btn btn-sm btn-primary no-print" onclick="print_tab_overview()"
                           id="print_overview_btn"><i class="fas fa-print "></i>
                            Print Overview</a>


                </div>

            </div>
            <div class="card-body" >
                <div class="row">
                    <div class="col-8 col-md-8 col-lg-8 ">
                        <div class="row">  {{--the three status boxes--}}
                            <div class="col-4 col-sm-4">
                                <div class="info-box bg-gray-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Dossier Ref. Num.</span>
                                        <span
                                                class="info-box-number text-center text-muted mb-0">{{ $dossier_evaluation_details->dossier_ref_num }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4">
                                <div class="info-box bg-gray-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Remaining Days</span>
                                        <span class="info-box-number text-center text-muted mb-0">
                                           {{$remaining_evaluation_days}}

                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4">
                                <div class="info-box bg-gray-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Evaluation Mode </span>
                                        <span class="info-box-number text-center text-muted mb-0">

                                                @if($dossier_evaluation_details->application_type==2)
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
                                        <span class="username">
                                        Assessor: <b
                                                    class="text-blue">{{ $dossier_evaluation_details->first_name }} {{ $dossier_evaluation_details->middle_name }}</b>
                                        </span>
                                        <br/>
                                        <span class="description text-sm">{{ $dossier_evaluation_details->assessor_email }} </span>
                                    </div>
                                    <!-- /.user-block -->
                                    <br/>
                                    <table class="table table-sm table-borderless table-condensed">
                                        <tbody>
                                        <tr>
                                            <td width="25%">Assigned Date</td>
                                            <td class="text-left">
                                                <b>{{ $dossier_evaluation_details->assigned_datetime }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Supervisor</td>
                                            <td class="text-left">
                                                <b>{{ $dossier_evaluation_details->name }} {{ $dossier_evaluation_details->m_name }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Product</td>
                                            <td class="text-left">
                                                <b>{{ $dossier_evaluation_details->product_trade_name }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Application Number</td>
                                            <td class="text-left">
                                                <b>{{ $dossier_evaluation_details->application_number }}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                {{---------------------------------------------------------------------------------------------}}
                                <h4>Applicant Information</h4>
                                <div class="post clearfix">
                                    <div class="user-block-sm">
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
                                                <b>{{ $dossier_evaluation_details->dosage_name }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="25%">Route of Administration</td>
                                            <td class="text-left">
                                                <b>{{ $dossier_evaluation_details->route_of_admin }}</b>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                {{---------------------------------------------------------------------------------------------}}


                            </div>
                        </div> {{--the Information paragraphes--}}
                    </div>
                    {{------------------------- PROGRESS ------------------------------}}

                    <div class="col-4 col-md-4 col-lg-4 text-muted">
                        <div class="post">
                        <h4 class="text-primary"><i class="fas fa-project-diagram"></i> Progress</h4>
                        <br>

                            @if($main_task->task_status=='pause')

                                <div class="progress">
                                    <div class="progress-bar bg-warning progress-bar-striped"
                                         role="progressbar"  aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{  $dossier_evaluation_details->progress_percentage }}%">
                                        <span id="progress_status_span_id">{{  $dossier_evaluation_details->progress_percentage }}% Complete</span>
                                    </div>
                                </div>

                                {{--<canvas width="90" height="90"></canvas>
                                <input type="text" class="knob" id="progress_status_id"
                                       value="{{  $dossier_evaluation_details->progress_percentage }}%"
                                       data-width="90" data-height="90" data-fgcolor="#ffc107"
                                       data-readonly="true" readonly="readonly" style="width: 49px; height: 30px;
                                           position: absolute; vertical-align: middle; margin-top: 30px; margin-left: -69px;
                                           border: 0px; background: none; font: bold 20px Arial; text-align: center;
                                           color: rgb(245, 105, 84); padding: 0px; appearance: none;">--}}
                            @else
                           {{--     <canvas width="90" height="90"></canvas>
                                <input type="text" class="knob" id="progress_status_id"
                                       value="{{  $dossier_evaluation_details->progress_percentage }}%"
                                       data-width="90" data-height="90" data-fgcolor="#3c8dbc"
                                       data-readonly="true" readonly="readonly" style="width: 49px; height: 30px;
                                           position: absolute; vertical-align: middle; margin-top: 30px; margin-left: -69px;
                                           border: 0px; background: none; font: bold 20px Arial; text-align: center;
                                           color: rgb(245, 105, 84); padding: 0px; appearance: none;">
--}}
                                <div class="progress">
                                    <div class="progress-bar bg-info progress-bar-striped"
                                         role="progressbar"  aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{  $dossier_evaluation_details->progress_percentage }}%">
                                        <span id="progress_status_span_id">{{  $dossier_evaluation_details->progress_percentage }}% Complete</span>
                                    </div>
                                </div>
                            @endif

                            <br>
                            <table class="table table-sm table-borderless table-condensed">
                                <tbody>
                                <tr>
                                    <td width="40%">Date Started</td>
                                    <td><b>{{$main_task->start_time}}</b></td>
                                </tr>
                                <tr>
                                    <td>Due Date</td>
                                    <td><b>{{$main_task->end_time}}</b></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td class="text-left text-sm">
                                        @if($main_task->task_status=='pause')
                                            <span class="badge bg-warning">{{ $main_task->task_status }}</span>
                                            {{-- <label style="color: red">{{ $main_task->task_status }}</label>--}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pause Reason</td>
                                    <td class="text-left text-sm">
                                        <label>{{ $main_task->stopping_reason }} </label>
                                    @elseif($main_task->task_status=='Inprogress')
                                        <span class="badge bg-primary">In-progress</span>
                                    @elseif($main_task->task_status=='Locked')
                                        <span class="badge bg-danger">{{ $main_task->task_status }}</span>
                                    @elseif($main_task->task_status=='completed')
                                        <span class="badge bg-success">{{ $main_task->task_status }}</span>
                                    @elseif($main_task->task_status=='Decision')
                                        <span class="badge bg-success">{{ $decision->status }}</span>
                                    @endif
                                    </td>

                                </tr>
                                </tbody>
                            </table>

                        </div> {{--end class post / will create horiz. line--}}


                        <div class="post">

                        <h4>Dossier Home Directory</h4>

                        <p> {{ $dossier_path }}</p>

                        </div>

                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- /.card -->






        </div>
    </section>
    <!-- /.content -->


</div>
