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
                              <h3 class="card-title">Kanban Board</h3>
                            </div>

                        <div class="content-wrapper kanban m-lg-5 m-md-5 p-2" style="min-height: 428px;">

                            <section class="content pb-3">

                                <div class="container-fluid h-100" style="width: 100%;">

                                    <div class="card card-row card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Assigned
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            @foreach($assigned as $assigned_task)
                                                <div class="card card-primary card-outline">
                                                    <div class="card-header">
                                                        <h5 class="card-title"><B>{{$assigned_task->task_name}}</B></h5>
                                                        <div class="card-tools">
                                                            <a href="#" class="btn btn-tool">
                                                                <i class="fa fa-tasks"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <p>
                                                            Appln Id:123; Assigned: Simon
                                                            <span class="icon-check-square"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>

                                    <div class="card card-row card-default">
                                        <div class="card-header bg-info">
                                            <h3 class="card-title">
                                                In Progress
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            @foreach($in_progress as $in_progress_task)
                                                <div class="card card-primary card-outline">
                                                    <div class="card-header">
                                                        <h5 class="card-title"><B>{{$in_progress_task->task_name}}</B></h5>
                                                        <div class="card-tools">
                                                            <a href="#" class="btn btn-tool">
                                                                <i class="fas fa-recycle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>


                                    <div class="card card-row card-success">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Done
                                            </h3>
                                        </div>
                                        <div class="card-body">

                                            @foreach($complete as $complete_task)
                                                <div class="card card-primary card-outline">
                                                    <div class="card-header">
                                                        <h5 class="card-title">{{$complete_task->task_name}}</h5>
                                                        <div class="card-tools">
                                                            <a href="#" class="btn btn-tool">
                                                                <i class="fa fa-calendar-check"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                            <!-- /.card-body -->
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
