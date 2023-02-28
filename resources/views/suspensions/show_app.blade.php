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
                            <h1 class="card-title"><span
                                        style="font-weight:bold">{{$application->product_name}}</span>
                            </h1>
                            <div class="card-tools">
                                <a href="{{ url()->previous() }}" class="btn btn-warning"><i
                                            class="fas fa-arrow-circle-left"></i> Back </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">

                                <table class="table table-condensed">
                                    <tr>
                                        <td>Registration Number</td>
                                        <td>{{$application->registration_number}} <a
                                                    href="{{ route('supervisor_track_application_status.application', [$application->application_id]) }}"
                                                    class="btn btn-sm btn-default"><i class="fas fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Name</td>
                                        <td> {{$application->product_name}} [{{$application->product_trade_name}}]</td>
                                    </tr>
                                    <tr>
                                        <td>Applicant</td>
                                        <td>{{$application->trade_name}}</td>
                                    </tr>
                                    <tr style="font-weight:bold;">
                                        <td>Market Authorization Status</td>
                                        <td>{{$application->market_status}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            @can('application-list')
                                                @if($application->market_status=="Active")
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal" data-target="#modal-withdraw"> Withdraw
                                                        From Market
                                                    </button>
                                    @endif
                                    @endcan

                                </table>

                                <?php
                                if($application->market_status == 'Active' ){
                                ?>

                                <div class="card card-outline card-red collapsed-card col-md-12 col-lg-12">
                                    <div class="card-header">
                                        <h3 class="card-title" style="font-weight:bold; color:grey; font-size:14pt;"><i
                                                    class="fas fa-eyedropper"> </i>New Action</h3>

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


                                            @can('supervisor_roles')
                                                <form class='form-horizontal' role='form' method='post'
                                                      enctype="multipart/form-data"
                                                      action="{{route('suspensions.store')}}">
                                                    @csrf
                                                    <input type="hidden" value="{{$application->id}}"
                                                           name="application_id"/>
                                                    <input type="hidden" value="{{$application->market_status}}"
                                                           name="market_status"/>

                                                    <tr style="font-weight:bold; font-size:14pt;">
                                                        <td>Take Action:</td>
                                                        <td><select name="action_taken" id="choice"
                                                                    onchange="getValue(this);"
                                                                    class="form-control is-warning" required>
                                                                <option value=""></option>
                                                                <option value="Ceased">Cease</option>
                                                                <option value="Suspended">Suspend</option>
                                                            </select></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Action Date</td>
                                                        <td>
                                                            <div class="input-group date" id="reservationdate"
                                                                 data-target-input="nearest">
                                                                <input type="date" class="form-control"
                                                                       name="action_date" required ></div>
                                                        </td>
                                                    </tr>
                                                    <tr id="suspended_till_date" style="visibility:hidden;">
                                                        <td>Suspended till date</td>
                                                        <td>
                                                            <div class="input-group date" data-target-input="nearest">
                                                                <input type="date" class="form-control"
                                                                       name="suspended_till_date"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Attach Supporting document</td>
                                                        <td><input type="file" class="form-control"
                                                                   name="suspension_file" required></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Description/Remark</td>
                                                        <td><textarea class="form-control" name="description" cols="20"
                                                                      rows="2"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a href="{{ url()->previous() }}" class="btn btn-danger"><i
                                                                        class="fas fa-arrow-circle-left"></i> Cancel
                                                            </a>
                                                            <button type="submit" class="btn btn-primary"><i
                                                                        class="fas fa-save"></i> Save
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </form>
                                            @endcan
                                        </table>
                                        </form>

                                    </div>
                                </div>


                                <!-- Timeline -->
                                <div class="card card-outline card-blue collapsed-card col-md-12 col-lg-12">
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

                            <?php
                            }
                            ?>


                            <!-- Modal Appeal Details -->
                                <div class="modal fade" id="modal-default">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class='form-horizontal' role='form' method='post'
                                                  enctype="multipart/form-data"
                                                  action="{{route('suspensions.store_appeal_moh')}}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Appeal Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="suspension_id"
                                                           value="{{$application->suspension_id}}"/>
                                                    <input type="hidden" name="application_id"
                                                           value="{{$application->id}}"/>

                                                    <span style="font-weight:bold;">Appeal Description: </span> <input
                                                            type="text" name="appeal_description_moh"
                                                            class="form-control"> <br/>
                                                    <span style="font-weight:bold;">MOH appeal document: </span><input
                                                            type="file" class="form-control" required
                                                            name="appeal_document_moh"> <br/>
                                                    <span style="font-weight:bold;">Description/Remark:</span><textarea
                                                            class="form-control" name="description_moh" cols="20"
                                                            rows="2"></textarea>

                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                                class="fas fa-history"></i> Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary"><i
                                                                class="fas fa-save"></i> Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->


                                <!-- Modal Withdraw -->
                                <div class="modal fade" id="modal-withdraw">
                                    <form class='form-horizontal' role='form' method='post'
                                          enctype="multipart/form-data"
                                          action="{{route('withdrawals.store_withdrawal')}}">
                                        @csrf
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Withdraw Product from Market</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="suspension_id"
                                                           value="{{$application->suspension_id}}"/>
                                                    <input type="hidden" name="application_id"
                                                           value="{{$application->id}}"/>
                                                    <input type="hidden" name="action_taken" value="Withdrawn"/>

                                                    <span style="font-weight:bold;">Withdrawal Reason: </span> <input
                                                            type="text" name="withdrawal_request_reason"
                                                            class="form-control"/> <br/>
                                                    <span style="font-weight:bold;">Withdrawal Date: </span><input
                                                            type="date" class="form-control" required
                                                            name="withdrawal_date_requested"/> <br/><br/>
                                                    <span style="font-weight:bold;">Withdrawal document: </span><input
                                                            type="file" class="form-control" required
                                                            name="withdrawal_request_attachment"/> <br/>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal-content -->


                        <!-- Modal Suspend to Cease -->

                        <div class="modal fade" id="suspend_to_cease">
                            <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                                  action="{{route('suspensions.suspend_to_cease')}}">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Cease Suspended Product</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="suspension_id"
                                                   value="{{$application->suspension_id}}"/>
                                            <input type="hidden" name="application_id" value="{{$application->id}}"/>
                                            <input type="hidden" name="action_taken" value="Ceased"/>

                                            <span style="font-weight:bold;">Cease Date: </span><input type="date"
                                                                                                      class="form-control"
                                                                                                      required
                                                                                                      name="action_date"/>
                                            <br/><br/>
                                            <span style="font-weight:bold;">Cease Reason: </span> <input type="text"
                                                                                                         name="description"
                                                                                                         class="form-control"/>
                                            <br/>
                                            <span style="font-weight:bold;">Cease document: </span><input type="file"
                                                                                                          class="form-control"
                                                                                                          required
                                                                                                          name="suspension_file"/>
                                            <br/>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                        class="fas fa-history"></i> Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                                Save
                                            </button>
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->


                <!-- Modal Store Sealed Letter  of Cease and Suspend-->

                <div class="modal fade" id="modal_store_sealed">
                    <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                          action="{{route('suspensions.store_sealed_letter')}}">
                        @csrf
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">
                                        @if(($application->market_status=='Suspended'))
                                            Upload Sealed Suspension Letter
                                        @else
                                            Upload Sealed Ceasation Letter
                                        @endif
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="suspension_id" value="{{$application->suspension_id}}"/>
                                    <input type="hidden" name="application_id" value="{{$application->id}}"/>
                                    <input type="hidden" name="action_taken" value="Withdrawn"/>

                                    <span style="font-weight:bold;">Sealed document: </span><input type="file"
                                                                                                   class="form-control"
                                                                                                   required
                                                                                                   name="sealed_letter"/>
                                    <br/>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                class="fas fa-history"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->


        <!-- Modal User Appeal -->

        <div class="modal fade" id="user-appeal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                          action="{{route('suspensions.store_appeal')}}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Appeal Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="suspension_id" value="{{$application->suspension_id}}"/>
                            <input type="hidden" name="application_id" value="{{$application->id}}"/>

                            <span style="font-weight:bold;">Appeal Description: </span> <input type="text"
                                                                                               name="appeal_description_user"
                                                                                               class="form-control">
                            <br/>
                            <span style="font-weight:bold;">Appeal document: </span><input type="file"
                                                                                           class="form-control" required
                                                                                           name="appeal_document_user">
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fas fa-history"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->


        <!-- Modal Void Decision -->
        <div class="modal fade" id="modal_void">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                          action="{{route('suspensions.void_decision')}}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Void Decision</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="suspension_id" value="{{$application->suspension_id}}"/>
                            <input type="hidden" name="application_id" value="{{$application->id}}"/>

                            <span style="font-weight:bold;">Void Reason: </span>
                            <textarea name="void_reason" class="form-control">
            </textarea>
                            <br/>
                            <span style="font-weight:bold;">Void Document: </span><input type="file" required
                                                                                         class="form-control"
                                                                                         name="appeal_document_user">
                            <br/>

                            <span style="font-weight:bold;">Remark: </span>
                            <textarea name="void_remark" class="form-control">
            </textarea>
                            <br/>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fas fa-history"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->


        <!-- Modal Decision Response from user  -->
        <div class="modal fade" id="modal_response">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                          action="{{route('suspensions.store_response_letter')}}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Upload Decision Response</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="suspension_id" value="{{$application->suspension_id}}"/>
                            <input type="hidden" name="application_id" value="{{$application->id}}"/>

                            <span style="font-weight:bold;">Decision Response: </span>
                            <textarea name="decision_response" class="form-control">
            </textarea>
                            <br/>
                            <span style="font-weight:bold;">Response Document: </span><input type="file" required
                                                                                             class="form-control"
                                                                                             name="decision_response_letter">
                            <br/>

                            <span style="font-weight:bold;">Remark/ Description: </span>
                            <textarea name="response_remark" class="form-control">
            </textarea>
                            <br/>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fas fa-history"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->


        <!-- Modal Extend Suspension Deadline -->
        <div class="modal fade" id="modal_extend_deadline">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                          action="{{route('suspensions.update_suspension_deadline')}}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Extend Suspension Deadline</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="suspension_id" value="{{$application->suspension_id}}"/>
                            <input type="hidden" name="application_id" value="{{$application->id}}"/>

                            <span style="font-weight:bold;">Current Deadline: {{$application->suspended_till_date}} </span>
                            <br/>
                            <span style="font-weight:bold;">Extended Deadline: </span><input type="date" required
                                                                                             class="form-control"
                                                                                             name="new_deadline">
                            <br/>

                            <span style="font-weight:bold;">Remark/ Description: </span>
                            <textarea name="deadline_update_reason" class="form-control">
            </textarea>
                            <br/>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fas fa-history"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->


        <!-- Modal Extend Suspension deadline request -->
        <div class="modal fade" id="modal_extend_deadline_request">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                          action="{{route('suspensions.request_suspension_deadline_extension')}}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Request Suspension Deadline</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="suspension_id" value="{{$application->suspension_id}}"/>
                            <input type="hidden" name="application_id" value="{{$application->id}}"/>

                            <span style="font-weight:bold;">Current Deadline: {{$application->suspended_till_date}} </span>
                            <br/>
                            <span style="font-weight:bold;">Extend Deadline to: </span><input type="date" required
                                                                                              class="form-control"
                                                                                              name="deadline_extension_requested">
                            <br/>

                            <span style="font-weight:bold;">Extension Reason: </span>
                            <textarea name="request_deadline_extension_reason" class="form-control">
            </textarea>
                            <br/>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fas fa-history"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->


        <!-- Modal Unsuspend or Uncease decision -->
        <div class="modal fade" id="modal-revoke">
            <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data"
                  action="{{route('suspensions.revoke_decision')}}">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                @if(($application->market_status=='Suspended'))
                                    Unsuspend Market Authorization
                                @else
                                    Uncease Market Authorization
                                @endif
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="suspension_id" value="{{$application->suspension_id}}"/>
                            <input type="hidden" name="application_id" value="{{$application->id}}"/>
                            @if(($application->market_status=='Suspended'))
                                <input type="hidden" value="Unsuspended" name="decision_taken">
                            @else
                                <input type="hidden" value="Unceased" name="decision_taken">
                            @endif

                            <span style="font-weight:bold;">
			@if(($application->market_status=='Suspended'))
                                    Unsuspension Reason
                                @else
                                    Uncease Reason
                                @endif
 : </span> <input type="text" name="revoke_description" class="form-control"> <br/>
                            <span style="font-weight:bold;">MOH document: </span><input type="file" class="form-control"
                                                                                        required name="revoke_document">
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fas fa-history"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
            </form>
        </div>
        </div>
        <!-- /.modal-content -->


    </section>

    <script type="text/javascript">

        function getValue(option) {
            if (option.value == 'Suspended') {
                document.getElementById("suspended_till_date").style.visibility = "visible";
            }

        }
    </script>



@endsection

@section('scripts')

@endsection
