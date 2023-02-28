<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title> PERU | Product Registration System</title>

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
  @include('layouts.footer_2')





  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@include('layouts.js_libs')
@yield('scripts')

<script>
    Echo.private('assignedTo.{{auth()->user()->id}}')
        .listen('.dossier.assignment', (e) => {
            console.log(e)
           // alert(e.message)

          toastr.success(e.message)

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
