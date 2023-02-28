@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 ml-2">
                    <div class="card card-info">
                        <div class="card-header">
                            <h1 class="card-title"><span
                                        style="font-weight:bold">Cease/ Suspend/ Withdraw  {{$application->product_name}}</span>
                            </h1>
                            <div class="card-tools">
                                <a href="{{ url()->previous() }}" class="btn btn-info"><i
                                            class="fas fa-arrow-circle-left"></i> Back </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">

                                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                        <table id="example"
                                               class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                               role="grid" aria-describedby="example1_info">

								   <form class='form-horizontal' role='form' method='post'
                                          action="{{route('suspensions.store')}}">
                                        @csrf
                                        <input type="hidden" value="{{$application->id}}" name="application_id"/>
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
                                            Action Taken:</h3>

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

                                            <?php
                                            if($application->market_status != 'Active'){
                                            ?>
                                            <tr style="font-weight:bold;">
                                                <td>Action Taken:</td>
                                                <td>{{$application->market_status}}</td>
                                            </tr>
                                            <tr style="font-weight:bold;">
                                                <td>Date Action Taken:</td>
                                                <td>{{$application->action_date}}</td>
                                            </tr>
                                            @if(($application->market_status=='Suspended'))
                                                <tr style="font-weight:bold;">
                                                    <td>Suspended till date:</td>
                                                    <td>{{$application->suspended_till_date}}</td>
                                                </tr>
                                            @endif

                                            <tr style="font-weight:bold;">
                                                <td>Appeal Issued</td>
                                                <td>
                                                    @if(($application->appeal_status=='None' || $application->appeal_status=='' || $application->appeal_status==NULL))
                                                        No &nbsp;&nbsp;
                                            @endif

                                            <tr>
                                                <td>Description/Remark</td>
                                                <td><textarea class="form-control" name="description" cols="16"
                                                              rows="2"> {{$application->suspension_description}}</textarea>
                                                </td>
                                            </tr>

                                            <tr>
                                                @if(($application->market_status=='Suspended'))
                                                    <td>
                                                        <a href="{{route('view_html_template',['id'=>15,'dossier_asg_id'=>1])}}"
                                                           data-toggle="tooltip"
                                                           class="btn btn-info btn-sm"
                                                           data-placement="top"
                                                           title="View and Fill out the Template"><i
                                                                    class="fas fa-eye "></i> Create Suspension
                                                            Letter</a>
                                                    </td>
                                                @endif

                                                @if(($application->market_status=='Ceased'))
                                                    <td>
                                                        <a href="{{route('view_html_template',['id'=>14,'dossier_asg_id'=>1])}}"
                                                           data-toggle="tooltip"
                                                           class="btn btn-info btn-sm"
                                                           data-placement="top"
                                                           title="View and Fill out the Template"><i
                                                                    class="fas fa-eye "></i> Create Ceasation Letter</a>
                                                    </td>
                                                @endif


                                                <td>
                                                    @if(($application->appeal_status=='None' || $application->appeal_status=='' || $application->appeal_status==NULL))
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                                data-toggle="modal" data-target="#user-appeal"> Create
                                                            User Appeal
                                                        </button>
                                                    @endif

                                                    @if(($application->appeal_status=='None' || $application->appeal_status=='' || $application->appeal_status==NULL))
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                                data-toggle="modal" data-target="#modal-default"> Create
                                                            MOH Appeal
                                                        </button>
                                                    @endif

                                                    @if(($application->market_status=='Suspended' || $application->market_status=='Withdrawn' || $application->market_status=='Ceased'))
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                                data-toggle="modal" data-target="#modal-revoke"> Revoke
                                                            Action Taken
                                                        </button>
                                                    @endif

                                                    @if(($application->market_status=='Suspended' || $application->market_status=='Withdrawn' || $application->market_status=='Ceased'))
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                                data-toggle="modal" data-target="#modal_void"> Void
                                                            Action Taken
                                                        </button>
                                                    @endif

                                                </td>

                                            </tr>


                                            <?php
                                            }else{
                                            ?>
                                            <tr style="font-weight:bold; color:red; font-size:16pt;text-align:center;">
                                                <td>Take Action:</td>
                                                <td><select name="action_taken" class="form-control is-warning"
                                                            required>
                                                        <option value=""></option>
                                                        @can('supervisor_roles')
                                                            <option value="Ceased">Cease</option>
                                                            <option value="Suspended">Suspend</option>
                                                        @endcan

                                                        @can('application-list')
                                                            <option value="Withdrawn">Withdraw</option>
                                                        @endcan

                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td>Action Date</td>
                                                <td>
                                                    <div class="input-group date" id="reservationdate"
                                                         data-target-input="nearest">
                                                        <input type="date" class="form-control" name="action_date">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Suspended till date</td>
                                                <td>
                                                    <div class="input-group date" id="suspended_till_date"
                                                         data-target-input="nearest">
                                                        <input type="date" class="form-control"
                                                               name="suspended_till_date"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Attach Supporting document</td>
                                                <td><input type="file" name="attachment"></td>
                                            </tr>
                                            <tr>
                                                <td>Description/Remark</td>
                                                <td><textarea class="form-control" name="description" cols="20"
                                                              rows="2"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                            </tr>

                                            <?php
                                            }
                                            ?>
                                        </table>

                                        @if( ($application->market_status=='Suspended') && ($application->appeal_status!='' || $application->appeal_status!=NULL))

                                            <div class="card card-outline card-success collapsed-card col-md-9 col-lg-9">
                                                <div class="card-header">
                                                    <h3 class="card-title"><strong>Appeal Details</strong></h3>

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                                data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body" style="display: none;">
                                                    @if(($application->appeal_status=='Appealed'))
                                                        <table class="table table-condensed">
                                                            <tr>
                                                                <td>Appeal description:</td>
                                                                <td> {{$application->appeal_description}} </td>
                                                            <tr>
                                                                <td>Appeal document:</td>
                                                                <td>
                                                                    <a href="http://127.0.0.1:8000/{{$application->appeal_document}}"
                                                                       class="btn btn-sm btn-info"><i
                                                                                class="fas fa-download"></i>
                                                                        Download</a></td>
                                                        </table

                                                        @endif
                                                        @endif


                                                        </form>

                                                </div>

                                                <div class="modal fade" id="modal-default">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form class='form-horizontal' role='form' method='post'
                                                                  enctype="multipart/form-data"
                                                                  action="{{route('suspensions.store_appeal')}}">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Appeal Details</h4>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="suspension_id"
                                                                           value="{{$application->suspension_id}}"/>
                                                                    <input type="hidden" name="application_id"
                                                                           value="{{$application->id}}"/>

                                                                    <span style="font-weight:bold;">Appeal Description: </span>
                                                                    <input type="text" name="appeal_description"
                                                                           class="form-control"> <br/>
                                                                    <span style="font-weight:bold;">MOH appeal document: </span><input
                                                                            type="file" required
                                                                            name="appeal_document_user"> <br/>
                                                                    <span style="font-weight:bold;">Description/Remark:</span><textarea
                                                                            class="form-control" name="description"
                                                                            cols="20" rows="2"></textarea>

                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->

                                                <div class="modal fade" id="user-appeal">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form class='form-horizontal' role='form' method='post'
                                                                  enctype="multipart/form-data"
                                                                  action="{{route('suspensions.store_appeal')}}">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Appeal Details</h4>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="suspension_id"
                                                                           value="{{$application->suspension_id}}"/>
                                                                    <input type="hidden" name="application_id"
                                                                           value="{{$application->id}}"/>

                                                                    <span style="font-weight:bold;">Appeal Description: </span>
                                                                    <input type="text" name="appeal_description"
                                                                           class="form-control"> <br/>
                                                                    <span style="font-weight:bold;">Appeal document: </span><input
                                                                            type="file" required
                                                                            name="appeal_document_user">
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                    </button>
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
                                                            <form class='form-horizontal' role='form' method='post'
                                                                  enctype="multipart/form-data"
                                                                  action="{{route('suspensions.void_decision')}}">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Void Decision</h4>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="suspension_id"
                                                                           value="{{$application->suspension_id}}"/>
                                                                    <input type="hidden" name="application_id"
                                                                           value="{{$application->id}}"/>

                                                                    <span style="font-weight:bold;">Void Reason: </span>
                                                                    <textarea name="description" class="form-control">
            </textarea>
                                                                    <br/>
                                                                    <span style="font-weight:bold;">Appeal document: <br/></span><input
                                                                            type="file" required
                                                                            name="appeal_document_user">
                                                                    <br/>

                                                                    <span style="font-weight:bold;">Remark: </span>
                                                                    <textarea name="remark" class="form-control">
            </textarea>
                                                                    <br/>

                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->


                                                <div class="modal fade" id="modal-revoke">
                                                    <form class='form-horizontal' role='form' method='post'
                                                          enctype="multipart/form-data"
                                                          action="{{route('suspensions.revoke_decision')}}">
                                                        @csrf
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Revoke Action</h4>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="suspension_id"
                                                                           value="{{$application->suspension_id}}"/>
                                                                    <input type="hidden" name="application_id"
                                                                           value="{{$application->id}}"/>

                                                                    <span style="font-weight:bold;">Revoke Reason: </span>
                                                                    <input type="text" name="revoke_description"
                                                                           class="form-control"> <br/>
                                                                    <span style="font-weight:bold;">MOH revoke document: </span><input
                                                                            type="file" required name="revoke_document">
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                    </button>
                                                                </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->

    </section>




@endsection

@section('scripts')

@endsection
