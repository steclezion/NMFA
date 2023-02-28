@extends('layouts.app_app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- start: Css -->

  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>



    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Timeline</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="#"> Home </a></li>
              <li class="breadcrumb-item active"> Timeline </li> -->
            </ol>
          </div>
        </div>
      </div>
    <!-- /.container-fluid -->
   </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Timelime example  -->
        <div class="row">
          <div class="col-md-12">
            <!-- The time line -->
            <div class="timeline">
              <!-- timeline time label -->



              <div class="time-label">
                <!-- <span class="bg-red">10 Feb. 2014</span> -->
              </div>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <div>
                <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Timeline </h3>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <!-- The time line -->
                    <div class="timeline">
                     

                        @foreach($tasks as $task)
                      
                        <div>
                            @if($task->task_category =='Applying')
                            <span class="fa fa-newspaper bg-blue"></span>
                            @elseif($task->task_category=='dossier')
                            <span class="fa fa-suitcase bg-green"></span>
                            @elseif($task->task_category=='query')
                            <i class="fas fa-envelope bg-red"></i>
                            @elseif($task->task_category=='certificate')
                            <span class="fa fa-certificate bg-yellow"></span>
                            @elseif($task->task_category=='post-market')
                            <span class="fa fa-shopping-basket bg-gradient-success"></span>
                            @else
                            <i class="fas fa-user bg-green"></i>
                            @endif
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> <B>
                                        <?php
                                            $date = new DateTime($task->start_time);
                                        echo(($date->format ('d-m-Y')));
                                        $date_started = $date->format ('d-m-Y H:i:s');
                                        ?>
                                    </B>
                                </span>
                                <span class="bg-yellow round-tabs">{{$date_started}} </span>
                                <h3 class="timeline-header"><a
                                        href="{{$task->route_link}}">{{$task->task_activity_title}}</a>
                                </h3>

                                @if($task->content_detail===null || $task->content_detail==="")
                                @else
                                <div class="timeline-body">
                                    {{$task->content_detail}} <br />
                                    @if($task->document_id===null || $task->document_id==="")
                                    @else
                                    <br /> <i class="fas fa-link mr-1"></i> File &nbsp;&nbsp;&nbsp; Attachment is
                                    available
                                    for this task.
                                    <a href="{{$doc_link = asset($task->document_id)}}"
                                        class="btn-default rounded-top rounded"> Open </a>
                                    @endif

                                </div>
                                @endif

                                <div class="timeline-footer">
                                    <a class="btn btn-primary btn-sm" href="{{ url('main_task/show/'.$task->id)}}">Read more</a>
                                </div>
                            </div>
                        </div>
                        @endforeach




                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>

                    </div>
                </div>
                <!-- /.col -->
                </div>
                </div>
                <!-- /.col -->
                </div>
                </div>
                <!-- /.col -->
                </div>
                </div>
                <!-- /.col -->
                </div>
                </div>
                <!-- /.col -->

 




  @endsection


