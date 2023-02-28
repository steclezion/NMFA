@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Rejected Application Details</strong>
                            </h3>


                        </div>
                        <!-- /.card-header -->


                        <form method="POST" action="{{ route('update_reject_decision') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$decision->id}}" name="decision_id">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12 col-sm-6">

                                        {{------------------------------------start Decision Details-------------------}}
                                        <table class="table table-condensed table-borderless">
                                            <tbody>
                                            <tr>
                                                <td class="text-muted" width="30%">Application Number</td>
                                                <td class="text-left">
                                                    {{$application_details->application_number}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted" width="30%">Product Name</td>
                                                <td class="text-left">
                                                    {{$application_details->product_trade_name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Applicant Name</td>
                                                <td class="text-left">
                                                    {{$application_details->company_name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Route Administration</td>
                                                <td class="text-left">
                                                    {{$application_details->route_administration_name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Application Start</td>
                                                <td class="text-left">
                                                    <span class="badge badge-success">{{$application_details->app_created_at}}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-muted">Decision Date</td>
                                                <td class="text-left">
                                                    {{$decision->decision_date}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Decision Status:</td>
                                                <td class="text-left">
                                                    @if($decision->decision_status=='Accepted')
                                                        <span class="badge badge-success"> {{$decision->decision_status}}</span>
                                                    @elseif($decision->decision_status=='Rejected')
                                                        <span class="badge badge-danger"> {{$decision->decision_status}}</span>
                                                    @elseif($decision->decision_status=='Deferred')
                                                        <span class="badge badge-warning"> {{$decision->decision_status}}</span>
                                                    @else
                                                        <span class="badge badge-secondary"> Decision Not Given</span>
                                                    @endif
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                        {{------------------------------------ // End Decision Details-------------------}}

                                    </div>


                                    <div class="col-12 col-sm-6">

                                        <table class="table table-condensed table-borderless">
                                            <tbody>


                                            <tr>
                                                <td class="text-muted"><strong>Sealed</strong> Decision Letter</td>

                                                @if(isset($decision->sealed_document_id))
                                                    <td class="text-left">
                                                        <a href="{{asset($decision->downloaded_document_path)}}"
                                                           type="button"
                                                           target="_blank" title="View and Download Deferral Letter"
                                                           class="btn btn-success btn-sm"><i
                                                                    class="fas fa-download"></i>
                                                            View and Download</a>

                                                    </td>
                                                @endif
                                            </tr>


                                            @if($decision->attachments==1)
                                                <tr>
                                                    <td class="text-muted">View Attachments</td>
                                                    <td class="text-left">
                                                        <a href="{{asset($attachment->path)}}" data-toggle="tooltip"
                                                           class="btn btn-success btn-sm" data-placement="top"
                                                           title="Download the Attachment"><i
                                                                    class="fas fa-paperclip"></i>
                                                            Download
                                                        </a>
                                                    </td>
                                                </tr>


                                            @endif

                                            </tbody>
                                        </table>

                                        @if($decision->appeal_letter_id != null)
                                                <table class="table table-condensed table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-muted" width="50%" value="Yes">
                                                            Appeal
                                                        </td>
                                                        <td class="text-left">
                                                            <input type="checkbox" checked disabled/>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-muted"> Appeal Decision</td>
                                                        <td class="text-left">
                                                            @if($decision->appeal_status=='Accepted')
                                                                <span class="badge badge-success">{{ $decision->appeal_status}}</span>
                                                            @else
                                                                <span class="badge badge-danger"> {{$decision->appeal_status}}</span>

                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted"> View Appeal Decision Letter:</td>
                                                        <td class="text-left">
                                                            <a href="{{asset($decision->appeal_letter_path)}}"
                                                               type="button" target="_blank"
                                                               title="View the Appeal Letter"
                                                               class="btn btn-info btn-sm"><i
                                                                        class="fas fa-eye "></i> View</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                        @else
                                            <div class="alert alert-default-danger">

                                                <h5><i class="icon fas fa-exclamation-circle"></i>Issuing Appeal Letter.
                                                </h5>
                                                The application for the product registration of
                                                <b> {{$application_details->product_trade_name}} </b> has been <b>Rejected</b>.
                                                If you are not satisfied with the Decision, Please Contact the Eritrean
                                                Ministry of Health (MOH) for an Appeal.
                                                <ul>

                                                    {{--todo: uncomment below when variations is included--}}
                                                    {{--@if($evaluation_document_progress->variation_assessment_submitted > 2)
                                                        <li>Variations Evaluation</li>
                                                    @endif--}}
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-footer justify-content-between">
                                            <a href="{{route('applicant_decision_index')}}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-circle-left"></i> Back </a>


                                        </div>
                                    </div>


                                </div>

                                <!-- /.col -->

                            </div>


                            <!-- /.card -->

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    </div>
@endsection