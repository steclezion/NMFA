@extends('layouts.app')
@section('stylesheets')
@endsection
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="col-md-7 col-lg-8 col-sm-7 offset-2">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Task Details</h3>
                    </div>

                    <table class="table table-condensed">
                        <tbody>
                        <tr>
                            <td class="text-muted" width="25%">Date</td>
                            <td class="text-left">{{$task->start_time}}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Task Category</td>
                            <td class="text-left">{{$task->task_category}}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Content</td>
                            <td class="text-left">{{$task->content_detail}}</td>
                        </tr>
                        @if($document!=null)
                        <tr>
                            <td class="text-muted">Related Document</td>
                            <td class="text-left">
                                <a id="received_view" href="{{asset($document->path)}}" target="_blank"
                                   data-toggle="tooltip" class="btn btn-info btn-sm"
                                   data-placement="top" title="View the file"><i class="fas fa-book-open"></i></a><br>
                            </td>
                        </tr>
                            @endif
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
        </div>

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
