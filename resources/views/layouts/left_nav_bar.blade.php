<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/')}}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="NMFA logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">PERU</span>
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(Auth::user()->avatar_path == '' )

                    @if(Auth::user()->prefixes == 'Mr.' )
                        <img src="{{ asset('dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">

                    @else
                        <img src="{{ asset('dist/img/avatar2.png') }}" class="img-circle elevation-2" alt="User Image">

                    @endif

                @else
                    <img src="{{asset(Auth::user()->avatar_path) }}" class="img-circle elevation-2" alt="User Image">

                @endif


            </div>
            <div class="info">

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('user_profile') }}" class="d-block">
                            {{ Auth::user()->user_name  }}
                        </a>
                    @endauth
                @endif

            </div>
        </div>
        <span hidden id="user_idd"> {{ Auth::user()->id }} </span>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">


                <!-- <li class="nav-item">
                                         <a href="#" class="nav-link">
                                             <i class="nav-icon far fa-envelope"></i>
                                             <p> Mailbox <i class="fas fa-angle-left right"></i> </p>
                                         </a>

                                         <ul class="nav nav-treeview">
                                             <li class="nav-item">
                                                 <a href="../mailbox/mailbox.html" class="nav-link active">
                                                     <i class="far fa-circle nav-icon"></i>
                                                     <p>Inbox</p>
                                                 </a>
                                             </li>
                                             <li class="nav-item">
                                                 <a href="../mailbox/compose.html" class="nav-link">
                                                     <i class="far fa-circle nav-icon"></i>
                                                     <p>Compose</p>
                                                 </a>
                                             </li>
                                             <li class="nav-item">
                                                 <a href="../mailbox/read-mail.html" class="nav-link">
                                                     <i class="far fa-circle nav-icon"></i>
                                                     <p>Read</p>
                                                 </a>
                                             </li>
                                         </ul>
                                     </li> -->


                @can('nmfa_director')
                    <li class="nav-item  menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p> Applications <i class="right fas fa-angle-left"></i></p>
                        </a>

                        <ul class="nav nav-treeview">
                    

                            <li class="nav-item">
                                <a href="{{route('nmfa_registered_application')}}" class="nav-link">
                                    <i class="fas fa-lightbulb nav-icon"></i>
                                    <p>Issue Alert Notifications</p>
                                </a>
                            </li>
                  
                                <li class="nav-item">
                                    <a href="{{ route('suspensions.index') }}" class="nav-link">
                                        <i class="fa fa-check-circle nav-icon"></i>
                                        <p>Authorized Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.suspended_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-down nav-icon"></i>
                                        <p>Suspended Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.ceased_index') }}" class="nav-link">
                                        <i class="fa fa-circle-minus nav-icon"></i>
                                        <p>Ceased Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_requests') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawal Requests</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawn Products</p>
                                    </a>
                                </li>

                              <li class="nav-item">
                                    <a href="{{ route('suspensions.index_history') }}" class="nav-link">
                                        <i class="fa fa-history nav-icon"></i>
                                        <p>Post Market History</p>
                                    </a>
                                </li>

                    </li>
                   

                @endcan


                @can('application-list')
                    <li class="nav-item menu-open">
                @elsecan('application-list')
                    <li class="nav-item">
                        @endcan


                        @can('application-list')
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Applications
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                        @endcan

                        <ul class="nav nav-treeview">
                        @can('application-list')
                            <!-- <li class="nav-item">
                <a href="{{ route('application_reception') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
           <p>Application Reception </p>
                </a>
              </li> -->
                            @endcan
                            @can('application-status-list')
                                <li class="nav-item">
                                    <a href="{{route('application_reception')}}" class="nav-link">
                                        <i class="fas fa-file nav-icon"></i>
                                        <p>Start New Application</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('application_in_processing') }}" class="nav-link">
                                        <i class="fas fa-file-text nav-icon"></i>
                                        <p>In-progress</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{route('applicant_decision_index')}}" class="nav-link">
                                        <i class="fas fa-check-circle nav-icon"></i>
                                        <p>Completed</p>
                                    </a>
                                </li>
                            @endcan

                            @can('application-list')
                                <li class="nav-item">
                                    <a href="{{ route('dossier_sample_status') }}" class="nav-link">
                                        <i class="fas fa-book nav-icon"></i>
                                        <p>Submit Dossier</p>
                                    </a>
                                </li>



                                <li class="nav-item">
                                    <a href="{{ route('swift_payment') }}" class="nav-link">
                                        <i class="fas fa-book nav-icon"></i>
                                        <p>Facilitate Payment</p>
                                    </a>
                                </li>



                            @endcan


                            @can('application-list')
                                {{--<li class="nav-item">
                                  <a href="{{ route('preliminary_screening_query_handle_applicant') }}" class="nav-link">
                                    <i class="fas fa-stream nav-icon"></i>
                                    <p>Queries/Responses</p>
                                  </a>
                                </li>--}}

                                <!-- <li class="nav-item">
                                    <a href="{{ route('all_queries') }}" class="nav-link">
                                        <i class="fas fa-question-circle nav-icon"></i>
                                        <p>Queries/Responses</p>
                                    </a>
                                </li> -->
                            @endcan


                            @can('application-list')
                                <li class="nav-item">
                                    <a href="{{ url('documents_checked_from_assessor_perc_nmfa_director') }}" class="nav-link">
                                        <i class="fas fa-file-arrow-down nav-icon"></i>
                                        <p>Received Documents</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                    @can('application-list')

                     <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-question-circle"></i>
                                <p>
                                    Queries
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('all_queries') }}" class="nav-link">
                                        <i class="fa fa-question-circle nav-icon"></i>
                                        <p> Queries/Responses</p>
                                    </a>
                                </li>
                            </ul>
                    <li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-shopping-cart"></i>
                                <p>
                                    Post Market
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('suspensions.index') }}" class="nav-link">
                                        <i class="fa fa-check-circle nav-icon"></i>
                                        <p>Authorized Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.suspended_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-down nav-icon"></i>
                                        <p>Suspended Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.ceased_index') }}" class="nav-link">
                                        <i class="fa fa-circle-minus nav-icon"></i>
                                        <p>Ceased Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_requests') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawal Requests</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawn Products</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                       


                    @endcan


                    @can('supervisor_roles')

                        {{--------------------Start Screening--------------------}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-search"></i>
                                <p>
                                    Preliminary Screening
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ url('un-assigned') }}" class="nav-link">
                                        <i class="fas fa-pencil nav-icon"></i>
                                        <p>Assign for screening</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('Application_submitted') }}" class="nav-link">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p> All Applications</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('SupervisorToAssessorController') }}" class="nav-link">
                                        <i class="fas fa-bars-progress nav-icon"></i>
                                        <p>Assessor's Progress</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('app_deadline_index')}}" class="nav-link">
                                        <i class="far fa-clock nav-icon"></i>
                                        <p>Extend Deadline</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ route('SupervisorTimeline') }}" class="nav-link">
                                        <i class="fas fa-timeline nav-icon"></i>
                                        <p>Timeline</p>
                                    </a>
                                </li>


                            </ul>
                        </li>
                        {{--------------------END Screening--------------------}}

                        {{--------------------Start Dossier Evaluations--------------------}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book-open-reader"></i>
                                <p>
                                    Dossier Evaluations
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('dossier_tab')}}" class="nav-link">
                                        <i class="fas fa-chart-pie nav-icon"></i>
                                        <p>Dossier Assignments</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('assessment_report_index')}}" class="nav-link">
                                        <i class="far fa-comment nav-icon"></i>
                                        <p>Comment Requests</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('completed_assessment_report_index')}}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Completed Evaluations</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('deadline_index')}}" class="nav-link">
                                        <i class="far fa-clock nav-icon"></i>
                                        <p>Deadline Extension</p>

                                    </a>
                                </li>
                            </ul>
                        {{--------------------END Dossier Evaluations--------------------}}

                        {{--------------------Start Certification--------------------}}

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-certificate"></i>
                                <p>
                                    Certification
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('meeting_index')}}" class="nav-link">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>Meetings</p>

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('decision_index')}}" class="nav-link">
                                        <i class="fas fa-file-lines nav-icon"></i>
                                        <p>Decision</p>

                                    </a>
                                </li>
                            </ul>
                        {{--------------------END Certification--------------------}}

                        {{--------------------Start  Post Market--------------------}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-shopping-cart"></i>
                                <p>
                                    Post Market
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('variation_index')}}" class="nav-link">
                                        <i class="fa fa-plus-circle nav-icon"></i>
                                        <p>Variations</p>

                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ url('psur_acknowledgment_list') }}" class="nav-link">
                                        <i class="fas fa-envelopes-bulk nav-icon"></i>
                                        <p> PSUR </p>
                                    </a>
                                </li>

                                <!-- <li class="nav-item">
                                    <a href="{{ url('un_assigned_psur') }}" class="nav-link">
                                        <i class="fas fa-pencil nav-icon"></i>
                                        <p> PSUR Assignment</p>
                                    </a>
                                </li>

                                     <li class="nav-item">
                                    <a href="{{ url('psur_reviewed_report') }}" class="nav-link">
                                        <i class="fas fa-reply-all nav-icon"></i>
                                        <p> PSUR Reviewed Report</p>
                                    </a>
                                </li> -->

                                       <li class="nav-item">
                                    <a href="{{ url('alert_from_nmfa_director') }}" class="nav-link">
                                        <i class="fas fa-allergies nav-icon"></i>
                                        <p> NMFA Director's Alert</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('app_deadline_index')}}" class="nav-link">
                                        <i class="far fa-clock nav-icon"></i>
                                        <p>PSUR Deadline Extension</p>
                                    </a>
                                </li>


                            <!-- <li class="nav-item">
                                        <a href="{{ route('SupervisorTimeline') }}" class="nav-link">
                                            <i class="fas fa-timeline nav-icon"></i>
                                            <p> PSUR Timeline</p>
                                            </a>
                                        </li> -->


                                <li class="nav-item">
                                    <a href="{{ route('suspensions.index') }}" class="nav-link">
                                        <i class="fa fa-check-circle nav-icon"></i>
                                        <p>Authorized Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.suspended_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-down nav-icon"></i>
                                        <p>Suspended Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.ceased_index') }}" class="nav-link">
                                        <i class="fa fa-circle-minus nav-icon"></i>
                                        <p>Ceased Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_requests') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawal Requests</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawn Products</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('suspensions.index_history') }}" class="nav-link">
                                        <i class="fa fa-history nav-icon"></i>
                                        <p>Post Market History</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('reregister_request_index')}}" class="nav-link">
                                        <i class="fa fa-code-pull-request nav-icon"></i>
                                        <p>Renewal Requests</p>

                                    </a>
                                </li>

                            </ul>

                        {{--------------------END  Post Market--------------------}}

                        {{--------------------STARt  Reports--------------------}}

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    Reports
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('assessor_tasks_report')}} " class="nav-link">
                                        <i class="fas fa-user nav-icon"></i>
                                        <p title="Summary of Tasks by Assessor, date and Status">Assessor Tasks</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('applications_received_report')}} " class="nav-link">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p title="Applications Received By Assessor for screening">Num. of Applications
                                            Received</p>

                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ route('applications_processed_report')}} " class="nav-link">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p title="Summary of Tasks by Assessor, date and Status">Num. of Applications
                                            Processed</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('application_eval_status_report_index')}} " class="nav-link">
                                        <i class="fas fa-bars-progress nav-icon"></i>
                                        <p title="Summary of Tasks by Assessor and Status">Application Eval. Status</p>

                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ route('assessor_tasks_timelapse_index')}} " class="nav-link">
                                        <i class="fas fa-user-clock nav-icon"></i>
                                        <p title="Summary of Completed Tasks by Assessor and Elapsed Time">Assessor Task
                                            Time Lapse</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('regulatory_time_taken_index')}} " class="nav-link">
                                        <i class="fas fa-clock nav-icon"></i>
                                        <p title="Summary of Completed Tasks by Assessor and Elapsed Time">Regulatory Time-Taken</p>

                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ route('get_variation_assessment_time_taken_index')}} " class="nav-link">
                                        <i class="fas fa-clock nav-icon"></i>
                                        <p title="Summary of Completed Tasks by Assessor and Elapsed Time">Variations Time-Taken</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('sample_test_report_index')}} " class="nav-link">
                                        <i class="fas fa-eye-dropper nav-icon"></i>
                                        <p title="Summary of Sample Tests Sent to QC">Sample Test Report</p>

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('meeting_report_index')}} " class="nav-link">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p title="Summary of Meetings by, date and Type">PERC Meeting Stats</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('appeal_report_index')}}" class="nav-link">
                                        <i class="fas fa-stream nav-icon"></i>
                                        <p title="Summary of appeals received">Appeals Received </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('suspensions.show_report')}}" class="nav-link">
                                        <i class="fas fa-stream nav-icon"></i>
                                        <p title="Market Authoriziation Status">MA Status </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        {{--------------------END  Reports--------------------}}

                        {{--------------------START  Management--------------------}}

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('template_index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Templates</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('document_types.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Categories</p>

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('dossier.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Dossier</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{route('all_index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Upload Guidelines</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('users.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('roles.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Roles</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        {{--------------------END  Management--------------------}}

                        {{--------------------START  Settings--------------------}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tasks-alt"></i>
                                <p>
                                    Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('enlm')}} " class="nav-link">
                                        <i class="fas fa-list nav-icon"></i>

                                        <p title="Eritrean National Medicine List">ENLM</p>

                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{url('dosageforms')}}" class="nav-link">
                                        <i class="fas fa-stream nav-icon"></i>
                                        <p title="Dosage Forms">Dosage Forms</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('route_of_administration')}}" class="nav-link">
                                        <i class="fas fa-pie-chart nav-icon"></i>
                                        <p title="Route of Admin">Route of Admin</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('country_list')}}" class="nav-link">
                                        <i class="fas fa-pie-chart nav-icon"></i>
                                        <p title="Country">Country</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        {{--------------------END  Settings--------------------}}
                       

                    @endcan

                    @can('assessor_roles')
                        <li class="nav-item  menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-search"></i>
                                <p>
                                    Preliminary Screening
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a method="post" href="{{ route('check_list.index') }}" class="nav-link">
                                        <i class="fas fa-list-check nav-icon"></i>
                                        <p>Checklists</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ route('generate_invoices') }}" class="nav-link">
                                        <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                        <p>Issue Invoice</p>
                                    </a>


                                <li class="nav-item">
                                    <a href="{{ route('received_swift_payments') }}" class="nav-link">

                                        <i class="fas fa-rouble nav-icon"></i>
                                        <p>Received Payment Bills</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ route('receipts') }}" class="nav-link">
                                        <i class="fas fa-receipt nav-icon"></i>
                                        <p>Attach Receipts</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a method="post" href="{{ route('generating_financial_notifications') }}"
                                       class="nav-link">
                                        <i class="fas fa-mobile-alt nav-icon"></i>
                                        <p>Financial Notification</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a method="post"
                                       href="{{ route('preliminary_screening_query_handle_assesor') }}"
                                       class="nav-link">
                                        <i class="far fa-question-circle nav-icon"></i>
                                        <p>Queries and Responses</p>
                                    </a>
                                </li>
                                </li>

                                <!-- <li class="nav-item">
                                    <a method="post"
                                       href="{{ route('received_swift_payments') }}"
                                       class="nav-link">
                                        <i class="fas fa-dollar nav-icon"></i>
                                        <p>Swift Receive Payments</p>
                                    </a>
                                </li> -->

                                
                                </li>

   <li class="nav-item">
                                
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ url('AssessorTimeline') }}" class="nav-link"> <i
                                                class="fas fa-timeline  nav-icon"></i>
                                        <p>Timeline</p>
                                    </a>
                                </li>

                            </ul>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book-open-reader"></i>
                                <p>
                                    Dossier Evaluation
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('dossier_evaluation_index')}}" class="nav-link">
                                        <i class="far fa-circle-play nav-icon"></i>
                                        <p>Ongoing Evaluations</p>

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('completed_dossier_evaluation_index')}}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Completed Evaluations</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('dossier_section_assign_index')}}" class="nav-link">
                                        <i class="fas fa-pie-chart nav-icon"></i>
                                        {{-- <p>Dossier Sec. Assig.</p>--}}
                                        <p>Assigned Dossier Sections</p>


                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('finished_dossier_section_assign_index')}}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Evaluated Dossier Sections</p>

                                    </a>
                                </li>

                            {{--    <li class="nav-item">
                                    <a href="{{route('notification_index')}}" class="nav-link">
                                        <i class="far fa-bell nav-icon"></i>
                                        <p>Notifications</p>

                                    </a>
                                </li>--}}

                            </ul>
                        </li>


                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book-open-reader"></i>
                                <p>
                                    Post Market
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('variation_evaluation_index')}}" class="nav-link">
                                        <i class="far fa-circle-play nav-icon"></i>
                                        <p>Variations</p>

                                    </a>
                                </li>
                                <li class="nav-item">
                                <a href="{{route('perc_psur_status_list')}}" class="nav-link">
                                        <i class="fas fa-list-ol nav-icon"></i>
                                        <p>PSUR List</p>

                                    </a>
                                    </li>
                                <li class="nav-item">
                                    <a href="{{ route('suspensions.index') }}" class="nav-link">
                                        <i class="fa fa-check-circle nav-icon"></i>
                                        <p>Authorized Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.suspended_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-down nav-icon"></i>
                                        <p>Suspended Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('suspensions.ceased_index') }}" class="nav-link">
                                        <i class="fa fa-circle-minus nav-icon"></i>
                                        <p>Ceased Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_requests') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawal Requests</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('withdrawals.withdrawn_index') }}" class="nav-link">
                                        <i class="fa fa-circle-arrow-left nav-icon"></i>
                                        <p>Withdrawn Products</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('suspensions.index_history') }}" class="nav-link">
                                        <i class="fa fa-history nav-icon"></i>
                                        <p>Post Market History</p>
                                    </a>
                                </li>


                            </ul>
                        </li>
                       

                    @endcan




                    @can('inspection_roles')

                        <li class="nav-item">
                            <a href="{{route('inspection_request_index')}}" class="nav-link">
                                <i class="far fa-question-circle nav-icon"></i>
                                <p>Sample Test Requests</p>

                            </a>
                        </li>
                       


                    @endcan


                    @can('qc_roles')

                        <li class="nav-item">
                            <a href="{{route('qc_request_index')}}" class="nav-link">
                                <i class="far fa-question-circle nav-icon"></i>
                                <p>Sample Test Requests</p>

                            </a>
                        </li>

                       
                    @endcan
                    @can('perc_roles')

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    PERC
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('dossier_section_assign_index')}}" class="nav-link">
                                        <i class="fas fa-pie-chart nav-icon"></i>
                                        {{-- <p>Dossier Sec. Assig.</p>--}}
                                        <p>Assigned Dossier Sections</p>


                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('finished_dossier_section_assign_index')}}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Evaluated Dossier Sections</p>

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('perc_meeting_index')}}" class="nav-link">
                                        <i class="far fa-clock nav-icon"></i>
                                        <p>Meeting</p>

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route('perc_psur_status_list')}}" class="nav-link">
                                        <i class="fas fa-list-ol nav-icon"></i>
                                        <p>PSUR List</p>

                                    </a>
                                </li>

                            </ul>
                        </li>

                    @endcan

                    <li class="nav-item">
                        <a href="{{route('notification_index')}}" class="nav-link">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>
                                Notifications
                            </p>
                        </a>
                    </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>
