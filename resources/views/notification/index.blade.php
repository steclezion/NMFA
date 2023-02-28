@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3 class="card-title">Notifications</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example1" class="table table-striped dataTable no-footer dtr-inline" role="grid" aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending">S.N</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" >From</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" >Subject</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Country: activate to sort column ascending" >Type</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Country: activate to sort column ascending" >Details</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" >Time</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" >Status</th>                                      </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($notifications)>0)
                                    @php($i=1)

                                    @foreach($notifications as $notification)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td tabindex="0">{{$notification->data['from_user']}}</td>
                                            <td>{{$notification->data['subject']}} </td>
                                            <td>{{$notification->data['type']}}</td>
                                            <td>{{$notification->data['data']}}</td>
                                            <td>{{$notification->created_at->diffForHumans()}}</td>
                                            <td>
                                                <a href="{{ route('notification_show',$notification->id)}}" class="btn-sm btn-info"><i class="fas fa-list"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

