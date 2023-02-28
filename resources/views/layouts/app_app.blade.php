<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title> Medical Product Registration System</title>

 <!-- css scripts -->
@include('layouts.css_libs')
@yield('stylesheets')

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


@include('layouts.header_nav')
<!-- content wraper -->
<div class="content-wrapper">

  <br>
  @include('layouts.message')

    @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Sidebar Container -->
  @include('layouts.left_nav_bar')
  <div class='row no-print'>
  @include('layouts.footer_2')
</div>






  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@include('layouts.js_libs_app')
@yield('scripts')

<script>
function delay_refresh()
{

var id = setInterval(delay_refresh_page, 5000);
function delay_refresh_page() {
//location.reload(true);
clearInterval(id);
}


}

    Echo.private('assignedTo.{{auth()->user()->id}}')
        .listen('.dossier.assignment', (e) => {
            console.log(e)

          toastr.success(e.message)
          delay_refresh();

          $.ajax({

            type:'GET',

            url:"{{ route('retrieve_notifications') }}",

            data:{},

            success:function(data){

              document.getElementById('notification_count').innerHTML=data.notification_count;
              document.getElementById('notification_count_header').innerHTML=data.notification_count;
              document.getElementById('notification_contents').innerHTML=data.notifications;

            },
            error:function (data) {

              console.log(data);
            }
          });

           // document.getElementById('event_msg').innerHTML = e.message;
        }) //listen

        Echo.private('notifyTo.{{auth()->user()->id}}')
        .listen('.application.receiption', (e) => {
            console.log(e)

          toastr.success(e.message)
          delay_refresh();

          $.ajax({

            type:'GET',

            url:"{{ route('retrieve_notifications') }}",

            data:{},

            success:function(data){

              document.getElementById('notification_count').innerHTML=data.notification_count;
              document.getElementById('notification_count_header').innerHTML=data.notification_count;
              document.getElementById('notification_contents').innerHTML=data.notifications;

            },
            error:function (data) {

              console.log(data);
            }
          });

           // document.getElementById('event_msg').innerHTML = e.message;
        }) //listen
  //this code is ajax for loading notifications

    $.ajax({

      type:'GET',

      url:"{{ route('retrieve_notifications') }}",

      data:{},

      success:function(data){

        document.getElementById('notification_count').innerHTML=data.notification_count;
        document.getElementById('notification_count_header').innerHTML=data.notification_count;
        document.getElementById('notification_contents').innerHTML=data.notifications;

      },
      error:function (data) {

        console.log(data);
      }
    });
</script>
</body>
</html>
