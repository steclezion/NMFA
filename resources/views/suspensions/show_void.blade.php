<?php

use App\Http\Controllers\UtilsController as Utils;

?>

@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 ml-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h1 class="card-title"><span style="font-weight:bold">{{$application->product_name}} </span>
                            </h1>
                            <div class="card-tools">
                                <a href="{{ url()->previous() }}" class="btn btn-info"><i
                                            class="fas fa-arrow-circle-left"></i> Back </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">

                                <table class="table table-condensed">
                                    <tr>
                                        <td>Registration Number</td>
                                        <td>{{$application->registration_number}}</td>
                                    </tr>
                                <!--		  <tr><td>Application Number</td> <td>{{$application->application_id}}</td></tr> -->
                                    <tr>
                                        <td>Generic Name</td>
                                        <td> {{$application->product_name}}  </td>
                                    </tr>
                                    <tr>
                                        <td>Brand Name</td>
                                        <td>{{$application->product_trade_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Company/Supplier</td>
                                        <td>{{$application->trade_name}}</td>
                                    </tr>
                                    <tr style="font-weight:bold;">
                                        <td>Market Authorization Status</td>
                                        <td>{{$application->market_status}}</td>
                                    </tr>
                                    <tr>
                                        <td>Open Application Details</td>
                                        <td>
                                            <a href="{{ route('supervisor_track_application_status.application', [$application->application_id]) }}"
                                               class="btn btn-primary"><i class="fas fa-eye"></i> Open</a></td>
                                    </tr>
                                </table>

                                <div class="card card-outline card-success collapsed-card col-md-11 col-lg-11">
                                    <div class="card-header">
                                        <h3 class="card-title" style="font-weight:bold; color:red; font-size:16pt;">
                                            Action Taken: </h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool"
                                                    data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body" style="display: none;">
                                        <table class="table table-condensed">

                                            <form class='form-horizontal' role='form' method='post'
                                                  enctype="multipart/form-data"
                                                  action="{{route('suspensions.update')}}">
                                                @csrf
                                                <input type="hidden" value="{{$application->id}}"
                                                       name="application_id"/>
                                                <input type="hidden" value="{{$application->suspension_id}}"
                                                       name="suspension_id"/>
                                                <input type="hidden" value="{{$application->market_status}}"
                                                       name="market_status"/>

                                                <tr style="font-weight:bold;">
                                                    <td>Action Taken:</td>
                                                    <td>{{$application->action_taken}}</td>
                                                </tr>
                                                <tr style="font-weight:bold;">
                                                    <td>Date Action Taken:</td>
                                                    <td>{{$application->action_date}}</td>
                                                </tr>
                                                <tr style="font-weight:bold;">
                                                    <td>Decision Attachment File:</td>
                                                    <td><a href="{{asset($application->suspension_document)}}"
                                                           target="_blank" class="btn btn-info btn-sm"><i
                                                                    class="far fa-fw fa-file-pdf"></i> Open</a></td>
                                                </tr>


                                                @if(($application->market_status=='Suspended'))
                                                    <tr style="font-weight:bold;">
                                                        <td>Suspended till date:</td>
                                                        <td><input type="date" name="suspended_till_date"
                                                                   value="{{$application->suspended_till_date}}"
                                                                   disabled/></td>
                                                    </tr>
                                                    <tr style="font-weight:bold;">
                                                        <td>Decision Response Letter:</td>
                                                        <td>

                                                            @if(($application->decision_response_letter!=""))
                                                                <a href="{{asset($application->decision_response_letter)}}"
                                                                   target="_blank" class="btn btn-info btn-sm"><i
                                                                            class="far fa-fw fa-file-pdf"></i> Open </a>
                                                            @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr style="font-weight:bold;">
                                                        <td>Appeal Issued</td>
                                                        <td>
                                                            @if(($application->appeal_status=='None' || $application->appeal_status=='' || $application->appeal_status==NULL))
                                                                No &nbsp;&nbsp;
                                                            @else
                                                                Appealed&nbsp;&nbsp;  <a
                                                                        href="{{asset($application->appeal_document_user)}}"
                                                                        target="_blank" class="btn btn-info btn-sm"><i
                                                                            class="far fa-fw fa-file-pdf"></i> Open
                                                                    Appeal Doc</a>


                                                    <tr>
                                                        <td>Appeal description:</td>
                                                        <td> {{$application->appeal_description_user}} </td>
                                                    </tr>

                                                    @if($application->appeal_document_moh!=NULL)
                                                        <tr>
                                                            <td>MOH Appeal Decision Document:</td>
                                                            <td><a href="{{asset($application->appeal_document_moh)}}"
                                                                   target="_blank" class="btn btn-info btn-sm"><i
                                                                            class="far fa-fw fa-file-pdf"></i> Open </a>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                @endif

                                                <tr>
                                                    <td>Description/Remark</td>
                                                    <td><textarea class="form-control" name="description" cols="16" disabled
                                                                  rows="2"> {{$application->suspension_description}}</textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-danger">Cancel</button>
                                                    </td>
                                                </tr>
                                            </form>
                                        </table>
                                    </div>
</div>

                               <div class="card card-outline card-blue collapsed-card col-md-11 col-lg-11">
                                    <div class="card-header">
                                        <h3 class="card-title" style="font-weight:bold; color:grey; font-size:14pt;"><i
                                                    class="fas fa-clock"> </i> Time Line</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool"
                                                    data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body" style="display: none;">

                                        <div class="col-12">
                                            <div class="card">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <!-- The time line -->
                                                        <div class="timeline">

                                                            @if($tasks!=null)

                                                                @foreach($tasks as $task)
                                                                    <div>
                                                                        <span class="fa fa-user-clock bg-gradient-success"></span>
                                                                        <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i>
                                    <b>
                                        <?php
                                        $date = new DateTime($task->start_time);
                                        echo(($date->format('d-m-Y')));
                                        $date_started = $date->format('d-m-Y H:i:s');
                                        ?>
                                    </b>
                                </span>
                                                                            <span class="bg-yellow round-tabs">{{$date_started}} </span>
                                                                            <h3 class="timeline-header">

                                                                                <b class="text-blue">{{$task->task_activity_title}}</b>

                                                                            </h3>

                                                                            @if($task->content_detail===null || $task->content_detail==="")
                                                                            @else
                                                                                <div class="timeline-body">

                                                                                    {{-- content details are inserted to db as a paragraph separated by fullstop.
                                                                                    split (explode) them into sentences,  and write one sentence in one line
                                                                                    (terminated by fullstop--}}
                                                                                    <?php
                                                                                    $sentences = explode('.', $task->content_detail);

                                                                                    ?>
                                                                                    @foreach($sentences as $sentence)
                                                                                        @if($sentence != null)
                                                                                            <span>{{$sentence.'.'}}</span>
                                                                                            <br/>
                                                                                        @endif
                                                                                    @endforeach


                                                                                    {{--<br />
                                                                                    @if($task->document_id===null || $task->document_id==="")
                                                                                    @else
                                                                                    <br /> <i class="fas fa-link mr-1"></i> File Attachment is
                                                                                    available for this task.
                                                                                    <a href="{{$doc_link = asset($task->document_id)}}"
                                                                                        class="btn-default rounded-top rounded"> Open </a>
                                                                                    @endif--}}

                                                                                </div>
                                                                            @endif

                                                                            <div class="timeline-footer">
                                                                                {{-- <a class="btn btn-primary btn-sm" href="{{ url('main_task/show/'.$task->id)}}">Read more</a>--}}

                                                                                <table class="table">

                                                                                    <tbody>
                                                                                    <tr data-widget="expandable-table"
                                                                                        aria-expanded="false">
                                                                                        <td>
                                                                                            <a class="btn btn-primary btn-sm">Read
                                                                                                more</a>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="expandable-body">
                                                                                        <td>


                                                                                            <div class="timeline-body">

                                                                                                <table class="table table-borderless table-sm">
                                                                                                    <tbody>
                                                                                                    {{-- @if($task->content_detail!=null || $task->content_detail!="")
                                                                                                         <tr>
                                                                                                             <td class="text-muted" width="20%">Detail</td>
                                                                                                             <td class="text-left text-bold">{{$task->content_detail}}</td>
                                                                                                         </tr>
                                                                                                     @endif--}}
                                                                                                    @if( $main_task->related_task !=null)
                                                                                                        <tr>
                                                                                                            <td class="text-muted"
                                                                                                                width="20%">
                                                                                                                Phase
                                                                                                            </td>
                                                                                                            <td class="text-left text-bold">{{$main_task->related_task}}</td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                    @if( $task->task_category !=null)
                                                                                                        <tr>
                                                                                                            <td class="text-muted">
                                                                                                                Task
                                                                                                                Category
                                                                                                            </td>
                                                                                                            <td class="text-left text-bold">{{$task->task_category}}</td>
                                                                                                        </tr>
                                                                                                    @endif

                                                                                                    @if( $task->created_at !=null)
                                                                                                        <tr>
                                                                                                            <td class="text-muted">
                                                                                                                Task
                                                                                                                Created
                                                                                                                at
                                                                                                            </td>
                                                                                                            <td class="text-left text-bold">{{$task->created_at}}</td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                    @if( $task->updated_at !=null)
                                                                                                        <tr>
                                                                                                            <td class="text-muted">
                                                                                                                Task
                                                                                                                Updated
                                                                                                                at
                                                                                                            </td>
                                                                                                            <td class="text-left text-bold">{{$task->created_at}}</td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                    @if($task->activity_status !=null)
                                                                                                        <tr>
                                                                                                            <td class="text-muted">
                                                                                                                Status
                                                                                                            </td>
                                                                                                            <td class="text-left text-bold">
                                                                                                                @if($task->activity_status == 'Inprogress')
                                                                                                                    <span class="badge bg-secondary">In-progress</span>
                                                                                                                @else
                                                                                                                    <span class="badge bg-secondary">{{$task->activity_status}}</span>
                                                                                                                @endif
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endif

                                                                                                    {{-- @if($task->uploaded_document_id!=null)
                                                                                                         <tr>
                                                                                                             <td class="text-muted">Document</td>
                                                                                                             <td class="text-left text-bold">
                                                                                                                 <a href="{{asset($document->path)}}"
                                                                                                                    class="btn-default rounded-top rounded">
                                                                                                                     Open </a>
                                                                                                             </td>
                                                                                                         </tr>
                                                                                                     @endif--}}

                                                                                                    </tbody>
                                                                                                </table>


                                                                                            </div>


                                                                                        </td>


                                                                                    </tr>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif


                                                            <div>
                                                                <i class="fas fa-clock bg-gray"></i>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

									
									




    </section>



@endsection

@section('scripts')

@endsection
