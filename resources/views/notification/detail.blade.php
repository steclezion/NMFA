@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="col-md-7 col-lg-8 col-sm-7 offset-2">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Notification Details</h3>
                    </div>
                    <!-- /.card-header -->

                    <table class="table table-condensed">
                        <tbody>
                        <tr>
                            <td class="text-muted" width="23%">From</td>
                            <td class="text-left">{{$notification->from_user}}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Subject</td>
                            <td class="text-left">{{$notification->subject}}</td>
                        </tr>
                       {{-- @if($notification->alert_level != null)
                            <tr>
                                <td class="text-muted">Priority</td>
                                <td class="text-left">{{$notification->alert_level}}</td>
                            </tr>
                        @endif--}}
                        <tr>
                            <td class="text-muted">Detail</td>
                            <td class="text-left">{{$notification->data}}</td>
                        </tr>

                        @if($document!=null)
                            <tr>
                                <td class="text-muted">Related Document</td>
                                <td class="text-left">
                                    <a href="{{asset($document->path)}}" target="_blank"
                                       data-toggle="tooltip"
                                       class="btn btn-info btn-sm"
                                       data-placement="top"
                                       title="View the file"><i class="fas fa-book-open"></i></a>

                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-muted">Time Sent</td>
                            <td class="text-left">{{$data->created_at}}
                                &nbsp;&nbsp;&nbsp;({{$data->created_at->diffForHumans()}}) </td>
                        </tr>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td>
                                <a href="{{url()->previous()}}" class="btn btn-secondary" role="button">
                                    <i class="fas fa-arrow-circle-left" ></i>  Back</a>
                            </td>

                            <td align="right">
                                <a href="{{url('/Notifications')}}" class="btn btn-info" role="button">
                                    <i class="fas fa-list" ></i>  All Notifications</a>
                            </td>
                        </tr>
                        </tfoot>
                    </table>


                </div>


            </div>
        </div>
        </div>

    </section>

@endsection

