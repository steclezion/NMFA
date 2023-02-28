@extends('layouts.app')

@section('content')


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <strong> Variation Evaluation Tasks </strong>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        {{--START TAB NAME LIST--}}
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link show active " id="custom-tabs-three-overview-tab"
                                                   data-toggle="pill" href="#custom-tabs-three-overview" role="tab"
                                                   aria-controls="custom-tabs-three-overview"
                                                   onclick="change_tab_num('custom-tabs-three-overview')"
                                                   aria-selected="true">
                                                    <i class="fas fa-list"></i>
                                                    Overview</a>
                                            </li>
                                            <input type=hidden value='{{$variation->id}}'
                                                   id='hidden_variation_id' name='hidden_variation_id'>
                                            <input type=hidden value='{{$variation->id}}'
                                                   id='hidden_tab_id'>

                                           

                                             <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-three-issue-tab" data-toggle="pill"
                                                   href="#custom-tabs-three-issue" role="tab"
                                                   onclick="change_tab_num('custom-tabs-three-issue')"
                                                   aria-controls="custom-tabs-three-issue" aria-selected="false">
                                                    <i class="fas fa-envelope"></i>
                                                    Issue Query & Assessment Report</a>
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
                                          

                                        </ul>
                                        {{--END TAB NAME LIST--}}
                                    </div>

                                    <div class="card-body">

                                        @if($main_task->task_status =='Locked')
                                        <div class="alert alert-default-danger">

                                            <h5><i class="icon fas fa-exclamation-circle"></i> Variation Evaluation Locked !</h5>

                                            The evaluation days duration for this Dossier ({{$main_task->task_duration_days_plan}} days) has expired.
                                            All Dossier Evaluation Commands are LOCKED.
                                            <br>
                                            To Continue Evaluation process, please send deadline extension request to
                                            your supervisor.
                                            <br>

                                           {{-- To request extension:
                                            <ol>
                                                <li> Expand dossier evaluations (from left side bar).</li>
                                                <li> Click on ongoing evaluations.</li>
                                                <li>  Find the desired dossier and click the deadline extension button.</li>
                                            </ol>--}}


                                        </div>
                                        @endif


                                        <div class="tab-content" id="custom-tabs-three-tabContent">


                                            {{--------------------START OVERVIEW  ------------------}}
                                            @include('variations.tab_overview')
                                            {{--------------------END OVERVIEW  ------------------}}

                                          
                                          

                                            {{-----------  START ASSESSMENT REPORT----------------------}}
                                            {{--  @include('variations.tab_assessment_report')
                                            {{--------------------END ASSESSEMENT REPORT ------------------}}

                                            {{---------------- START OF ISSUE QUERY------------}}
                                            @include('variations.tab_issue_query')
                                            {{---------------- END OF ISSUE QUERY------------}}

                                            {{-----------  START Timeline ----------------------}}
                                            @include('variations.tab_timeline')
                                            {{--------------------END Timeline  ------------------}}

                                            {{-----------  START Progress ----------------------}}
                                            {{--  @include('variations.tab_progress')  --}}
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


      

        $(function () {
            bsCustomFileInput.init();
        });
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

      




    </script>
@endsection
