@extends('layouts.app')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
@endsection
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Time Line</h3>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <!-- The time line -->
                                <div class="timeline">
                                    <!-- timeline time label -->

                                    <!-- /.timeline-label -->
                                    <!-- timeline item -->

                                    @foreach($tasks as $task)
                                        <div>
                                            @if($task->task_category=='application')
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
                                                        $date = new DateTime($task->task_started);
                                                    echo(($date->format ('d-m-Y')));
                                                    $date_started = $date->format ('d-m-Y ');
                                                    ?>
                                                    </B>
                                                </span>
                                                <span class="bg-yellow round-tabs">{{$date_started}}</span><h3 class="timeline-header"><a href="{{$task->route_link}}">{{$task->task_tracker_title}}</a>
                                                </h3>

                                                @if($task->content_detail===null || $task->content_detail==="")
                                                @else
                                                <div class="timeline-body">
                                                    {{$task->content_detail}} <br/>
                                                    @if($task->document_id===null || $task->document_id==="")
                                                    @else
                                                       <br/> <i class="fas fa-link mr-1"></i> File  &nbsp;&nbsp;&nbsp; Attachment is available for this task.
                                                        <a href="{{$doc_link = asset($task->document_id)}}" class="btn-default rounded-top rounded"> Open </a>
                                                    @endif



                                                </div>
                                                @endif

                                                <div class="timeline-footer">
                                                    <a class="btn btn-primary btn-sm" href="{{$task->route_link}}">Read more</a>
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
                </div>
            </div>
        </div>
    </section>
    </section>
@endsection
@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
