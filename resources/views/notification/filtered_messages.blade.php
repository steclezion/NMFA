@extends('layouts.app')
@section('stylesheets')
@endsection
@section('content')

<section class="content">
      <div class="container-fluid">
        <div class="col-md-6 col-lg-6 col-sm-7">

            <div class="card card-primary card-outline direct-chat direct-chat-primary shadow-none">
                <div class="card-header">
                    <h3 class="card-title">Conversation with {{$notifications[0]->first_name}}</h3>

                    <div class="card-tools">
                        <span title="3 New Messages" class="badge bg-primary">3</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                            <i class="fas fa-comments"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Conversations are loaded here -->

                    @foreach($notifications as $notification)
                        @if($notification->from_user!==1)
                            <div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">{{$notification->first_name}}</span>
                                    <span class="direct-chat-timestamp float-right">{{$notification->created_at->diffForHumans()}}</span>
                                </div>
                                <!-- /.direct-chat-infos -->
                                <i class="fa fa-users direct-chat-img"></i>
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    {{$notification->detail}}
                    </div>
                                <!-- /.direct-chat-text -->
                            </div>
                        @elseif($notification->from_user==1)
                                <div class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right">{{$notification->first_name}}</span>
                                    <span class="direct-chat-timestamp float-left">{{$notification->created_at->diffForHumans()}}</span>
                                </div>
                                    <i class="fa fa-user direct-chat-img "></i>
                                            <div class="direct-chat-text">
                                                {{$notification->detail}}
                                    </div>
                                            <!-- /.direct-chat-text -->
                                        </div>
                                        <!-- /.direct-chat-msg -->
                                    </div>
                        @endif
                  @endforeach

                    <!-- Contacts are loaded here -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
                <!-- /.card-footer-->
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

