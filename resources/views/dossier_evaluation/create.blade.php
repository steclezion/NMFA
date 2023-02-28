@extends('layouts.app')

@section('content')


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <strong> Dossier Evaluation Tasks </strong>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        {{--START TAB NAME LIST--}}
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link " id="custom-tabs-three-overview-tab"
                                                   data-toggle="pill" href="#custom-tabs-three-overview" role="tab"
                                                   aria-controls="custom-tabs-three-overview"
                                                   onclick="change_tab_num('custom-tabs-three-overview')"
                                                   aria-selected="true">
                                                    <i class="fas fa-list"></i>
                                                    Overview</a>
                                            </li>
                                            <input type=hidden value='{{$dossier_evaluation_details->dossier_ass_id}}'
                                                   id='hidden_dossier_assign_id'>
                                            <input type=hidden value='{{$dossier_evaluation_details->current_tab_id}}'
                                                   id='hidden_tab_id'>

                                            <li class="nav-item">
                                                <a class="nav-link " id="custom-tabs-three-dossier-tab"
                                                   data-toggle="pill"
                                                   href="#custom-tabs-three-dossier" role="tab"
                                                   onclick="change_tab_num('custom-tabs-three-dossier')"
                                                   aria-controls="custom-tabs-three-dossier" aria-selected="false">
                                                    <i class="fas fa-book"></i>
                                                    Dossier</a>
                                            </li>

                                            @if($dossier_evaluation_details->application_type==1)
                                                <li class="nav-item">
                                                    <a class="nav-link " id="custom-tabs-three-qc-tab"
                                                       data-toggle="pill"
                                                       href="#custom-tabs-three-qc" role="tab"
                                                       aria-controls="custom-tabs-three-qc" aria-selected="false"
                                                       onclick="change_tab_num('custom-tabs-three-qc')">
                                                        <i class="fas fa-eyedropper"></i>
                                                        Sample Testing</a>
                                                </li>
                                            @endif

                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-assessment-tab"
                                                   data-toggle="pill"
                                                   href="#custom-tabs-three-assessment" role="tab"
                                                   onclick="change_tab_num('custom-tabs-three-assessment')"
                                                   aria-controls="custom-tabs-three-assessment"
                                                   aria-selected="false">
                                                    <i class="fas fa-file-upload"></i>
                                                    Assessment Report</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-issue-tab" data-toggle="pill"
                                                   href="#custom-tabs-three-issue" role="tab"
                                                   onclick="change_tab_num('custom-tabs-three-issue')"
                                                   aria-controls="custom-tabs-three-issue" aria-selected="false">
                                                    <i class="fas fa-envelope"></i>
                                                    Issue Query</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-timeline-tab"
                                                   data-toggle="pill"
                                                   href="#custom-tabs-three-timeline" role="tab"
                                                   onclick="change_tab_num('custom-tabs-three-timeline')"
                                                   aria-controls="custom-tabs-three-timeline" aria-selected="false">
                                                    <i class="fas fa-timeline"></i>
                                                    Timeline
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-progress-tab"
                                                   onclick="change_tab_num('custom-tabs-three-progress')"
                                                   data-toggle="pill" href="#custom-tabs-three-progress" role="tab"
                                                   aria-controls="custom-tabs-three-progress"
                                                   aria-selected="false">
                                                    <i class="fas fa-check-square"></i>
                                                    Progress</a>
                                            </li>

                                        </ul>
                                        {{--END TAB NAME LIST--}}
                                    </div>

                                    <div class="card-body">

                                        @if($main_task->task_status =='Locked')
                                        <div class="alert alert-default-danger">

                                            <h5><i class="icon fas fa-exclamation-circle"></i> Dossier Evaluation Locked !</h5>

                                            The evaluation days duration for this Dossier ({{$main_task->task_duration_days_plan}} days) has expired.
                                            All Dossier Evaluation Commands are LOCKED.
                                            <br>
                                            To Continue Evaluation process, please send deadline extension request to
                                            your supervisor.
                                            <br>

                                            To request extension:
                                            <ol>
                                                <li> Expand dossier evaluations (from left side bar).</li>
                                                <li> Click on ongoing evaluations.</li>
                                                <li>  Find the desired dossier and click the deadline extension button.</li>
                                            </ol>


                                        </div>
                                        @endif


                                        <div class="tab-content" id="custom-tabs-three-tabContent">


                                            {{--------------------START OVERVIEW  ------------------}}
                                            @include('dossier_evaluation.tab_overview')
                                            {{--------------------END OVERVIEW  ------------------}}

                                            {{-----------  START DOSSIER ----------------------}}
                                            @include('dossier_evaluation.tab_dossier')
                                            {{--------------------END DOSSIER  ------------------}}

                                            @if($dossier_evaluation_details->application_type==1)

                                                {{--------------------START QC REPORT ------------------}}
                                                @include('dossier_evaluation.tab_qc_report')
                                                {{--------------------END QC REPORT ------------------}}

                                            @endif

                                            {{-----------  START ASSESSMENT REPORT----------------------}}
                                            @include('dossier_evaluation.tab_assessment_report')
                                            {{--------------------END ASSESSEMENT REPORT ------------------}}

                                            {{---------------- START OF ISSUE QUERY------------}}
                                            @include('dossier_evaluation.tab_issue_query')
                                            {{---------------- END OF ISSUE QUERY------------}}

                                            {{-----------  START Timeline ----------------------}}
                                            @include('dossier_evaluation.tab_timeline')
                                            {{--------------------END Timeline  ------------------}}

                                            {{-----------  START Progress ----------------------}}
                                            @include('dossier_evaluation.tab_progress')
                                            {{--------------------END progress  ------------------}}


                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                                <a href="{{url()->previous()}}"  class="btn btn-secondary">
                                    <i class="fas fa-arrow-circle-left"></i> Back</a>
                            </div>

                        </div>

                    </div>
                    <!-- /.card-body -->

                </div>
            </div>

        </div>

    </section>


@endsection

@section('scripts')
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="{{asset('plugins/word_converter/printThis.js') }}"></script>
    <script>


        var tab_hidden_id = document.getElementById('hidden_tab_id').value
        var a_element = document.getElementById(tab_hidden_id + '-tab')
        a_element.className += ' active'
        var b_element = document.getElementById(tab_hidden_id)
        b_element.className += ' show active'

        function print_tab_overview() {
            document.getElementById('print_overview_btn').hidden = true;
            $('#data').printThis({});
            document.getElementById('print_overview_btn').hidden = false;

        }



        function show_upload() {
            if (document.getElementById('assessment_report_submit').hidden == false) {
                document.getElementById('assessment_report_submit').hidden = true;

            } else {
                document.getElementById('assessment_report_submit').hidden = false;
            }
        }

        $(function () {
            bsCustomFileInput.init();
        });
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });


        // Delete Record Modal to confirm before deletion
        $('#deleteRecordModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var action = button.data('action');
            var modal = $(this);
            modal.find('form').attr('action', action);
        });

        function change_tab_num(tab_id) {
            let dossier_assign_id = document.getElementById('hidden_dossier_assign_id').value
            var tab_id = tab_id;


            $.ajax({

                type: 'GET',

                url: "{{ route('update_dossier_tab') }}",

                data: {tab_id: tab_id, dossier_assign_id: dossier_assign_id},

                success: function (data) {


                },
                error: function (data) {

                    console.log(data);
                }
            });

        }

    function update_QOS_status(o)
     {
        let id = o.value;
        // if checkbox is checked, update qos status in db from 0 to 1
        if (o.checked) {

            $.ajax({

                type: 'GET',
                url: "{{ route('update_qos_status') }}",
                data: {id: id},

                success: function (data) {
                    
                   o.checked = true;
                    o.disabled = true;
                    //document.getElementById('progress_status_span_id').innerHTML = data.data +'% Complete' ;
                    console.log(data.data);
                    document.getElementById('progress_status_id').value = data.data;

                },
                error: function (data) {
                    console.log(data);

                }
            });
        }
        /*else {  // unchecked
            //document.getElementById('qos_status').checked = false;
            //document.getElementById('qos_status').disabled = false;
        }*/

    }



    </script>
@endsection
