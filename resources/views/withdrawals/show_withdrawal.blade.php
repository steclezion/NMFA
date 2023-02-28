<?php
use App\Http\Controllers\UtilsController as Utils;
?>

@extends('layouts.app')
@section('content')
  
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-9 ml-5">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h1 class="card-title"><span style="font-weight:bold">Withdrawal  of :- {{$application->product_trade_name}} [{{$application->product_name}}]</span></h1>
                            <div class="card-tools">
							
                            <a href="{{ url()->previous() }}" class="btn btn-warning"><i
                                    class="fas fa-arrow-circle-left"></i> Back </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
		  
		  <table class="table table-condensed col-md-10 col-lg-10">		  
          <tr><td>Registration Number</td> <td>{{$application->registration_number}}  &nbsp;&nbsp;<a href="{{ route('supervisor_track_application_status.application', [$application->application_id]) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a></td></tr>
		  <tr><td>Product Name</td> <td> {{$application->product_trade_name}} [{{$application->product_name}}]</td></tr>
          <tr><td>Applicant</td> <td>{{$application->trade_name}}</td></tr>
 		  <tr style="font-weight:bold;"><td>Market Authorization Status</td> <td>{{$application->market_status}}</td></tr>
		  @can('application-list')
		  @if($application->market_status=="Active")
@if(($application->action_taken!="Withdrawal Requested"))
		  <tr><td></td><td>
<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-withdraw"> Withdraw From Market</button>
@endif
@endif
@endcan
</td>
</tr>

<tr style="font-weight:bold;"><td>Action Taken: </td> <td>{{$application->action_taken}}</td></tr>
<tr style="font-weight:bold;"><td>Withdrawal Request Date: </td> <td>{{$application->withdrawal_date_requested}}</td></tr>
<tr style="font-weight:bold;"><td>Withdrawal Attachment File: </td> <td><a href="{{asset($application->withdrawal_request_attachment)}}" target="_blank" class="btn btn-default btn-sm"><i class="far fa-fw fa-file-pdf"></i> Open</a></td></tr>
@if(($application->action_taken=="Withdrawal Requested"))

@can('supervisor_roles')
@if(($application->withdrawal_decision==NULL))
<tr>
<td></td> 
	<td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_approve"><i class="fas fa-check-circle"></i> Approve Withdrawal</button>
	<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal_reject"><i class="fas fa-level-down"></i> Reject Withdrawal</button>
	</td>
</tr>
@endif
@endcan

@endif

@if(($application->withdrawal_decision!=NULL))
	<tr style="font-weight:bold;"><td>Withdrawal Decision: </td> <td>{{$application->withdrawal_decision}}</td></tr>
	<tr style="font-weight:bold;"><td>Withdrawal Decision Date: </td> <td>{{$application->withdrawal_decision_date}}</td></tr>
	<tr style="font-weight:bold;"><td>Withdrawal Decision Reason: </td> <td>{{$application->withdrawal_decision_reason}}</td></tr>
	<tr style="font-weight:bold;"><td>Withdrawal Decision Document: </td> <td><a href="{{asset($application->withdrawal_decision_document)}}" target="_blank" class="btn btn-default btn-sm"><i class="far fa-fw fa-file-pdf"></i> Open</a></td></tr>
@endif


<tr>
<td> 
			  <a  href="{{ route('withdrawals.withdrawn_index') }}" class="button btn btn-warning"> <i class="fas fa-history"></i> Cancel </a>
             <!-- <button type="submit" class="btn btn-primary"> <i class="fas fa-save"></i> Save</button> -->
</td>
<td></td>
</tr>
</table>
</div>


<div class="card card-outline card-blue collapsed-card col-md-12 col-lg-12" >
          <div class="card-header">
              <h3 class="card-title"style="font-weight:bold; color:grey; font-size:14pt;"> <i class="fas fa-clock"> </i> Time Line</h3>

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
                                                <span>{{$sentence.'.'}}</span><br/>
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
                                            <tr data-widget="expandable-table" aria-expanded="false">
                                                <td>
                                                    <a class="btn btn-primary btn-sm">Read more</a>
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
                                                                    <td class="text-muted" width="20%">Phase</td>
                                                                    <td class="text-left text-bold">{{$main_task->related_task}}</td>
                                                                </tr>
                                                            @endif
                                                            @if( $task->task_category !=null)
                                                                <tr>
                                                                    <td class="text-muted">Task Category</td>
                                                                    <td class="text-left text-bold">{{$task->task_category}}</td>
                                                                </tr>
                                                            @endif

                                                            @if( $task->created_at !=null)
                                                                <tr>
                                                                    <td class="text-muted">Task Created at</td>
                                                                    <td class="text-left text-bold">{{$task->created_at}}</td>
                                                                </tr>
                                                            @endif
                                                            @if( $task->updated_at !=null)
                                                                <tr>
                                                                    <td class="text-muted">Task Updated at</td>
                                                                    <td class="text-left text-bold">{{$task->created_at}}</td>
                                                                </tr>
                                                            @endif
                                                            @if($task->activity_status !=null)
                                                                <tr>
                                                                    <td class="text-muted">Status</td>
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
		  
         <div class="modal fade" id="modal_approve">
          <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data" action="{{route('withdrawals.withdrawal_decision')}}">
          @csrf
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Approve Product Withdrawal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="withdrawal_id" value="{{$application->withdrawal_id}}" />
            <input type="hidden" name="application_id" value="{{$application->id}}"/>
            <input type="hidden" name="withdrawal_decision" value="Accepted"/>

			<span style="font-weight:bold;">Approval Date: </span><input type="date" class="form-control" required name="withdrawal_decision_date" />			<br/><br/>
			<span style="font-weight:bold;">Approval Reason: </span> <input type="text" name="withdrawal_decision_reason" class="form-control" /> <br/>
			<span style="font-weight:bold;">Approval document: </span><input type="file" required name="withdrawal_decision_document" />		<br/>	
			
			
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
              </div>
           </form>
          </div>
          </div>
          </div>
          <!-- /.modal-content -->

		  
        <div class="modal fade" id="modal_reject">
          <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data" action="{{route('withdrawals.withdrawal_decision')}}">
          @csrf
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reject Product Withdrawal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="withdrawal_id" value="{{$application->withdrawal_id}}" />
            <input type="hidden" name="application_id" value="{{$application->id}}"/>
            <input type="hidden" name="withdrawal_decision" value="Rejected"/>

			<span style="font-weight:bold;">Rejection Date: </span><input type="date" class="form-control" required name="withdrawal_decision_date" />			<br/><br/>
			<span style="font-weight:bold;">Rejection Reason: </span> <input type="text" name="withdrawal_decision_reason" class="form-control" /> <br/>
			<span style="font-weight:bold;">Rejection document: </span><input type="file" required name="withdrawal_decision_document" />		<br/>	
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
              </div>
           </form>
          </div>
          </div>
          </div>
          <!-- /.modal-content -->

    </section>

  
@endsection

@section('scripts')

@endsection
