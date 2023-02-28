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
<!-- content wrapper -->
    <div class="content-wrapper">

        <!-- server ip address -->
        <input type="hidden" id="server_ip" value="{{asset('')}}">
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
@include('layouts.js_libs')
@yield('scripts')

<script>

    function delay_refresh() {

        var id = setInterval(delay_refresh_page, 5000);

        function delay_refresh_page() {
//location.reload(true);
            clearInterval(id);
        }


    }

    var Toast;

    Echo.private('assignedTo.{{auth()->user()->id}}')
        .listen('.dossier.assignment', (e) => {
            console.log(e)

            // auto-hide toastr when close btn is clicked or ...
            // after 'timeOut' mili seconds
            toastr.options.closeButton = true;
            toastr.options.timeOut = 20000; // How long (in milisec) the toast will display without user interaction
            toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it
            toastr.options.progressBar = true;

            toastr.success(e.message)

            delay_refresh();


            $.ajax({

                type: 'GET',

                url: "{{ route('retrieve_notifications') }}",

                data: {},

                success: function (data) {

                    document.getElementById('notification_count').innerHTML = data.notification_count;
                    document.getElementById('notification_count_header').innerHTML = data.notification_count;
                    document.getElementById('notification_contents').innerHTML = data.notifications;

                },
                error: function (data) {

                    console.log(data);
                }
            });

            // document.getElementById('event_msg').innerHTML = e.message;
        }); //listen

    Echo.private('notifyTo.{{auth()->user()->id}}')
        .listen('.application.receiption', (e) => {
            console.log(e)


            toastr.options.closeButton = true;
            toastr.options.timeOut = 20000; // How long (in milisec) the toast will display without user interaction
            toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it
            toastr.options.progressBar = true;

            toastr.success(e.message)

            delay_refresh();

            $.ajax({

                type: 'GET',

                url: "{{ route('retrieve_notifications') }}",

                data: {},

                success: function (data) {

                    document.getElementById('notification_count').innerHTML = data.notification_count;
                    document.getElementById('notification_count_header').innerHTML = data.notification_count;
                    document.getElementById('notification_contents').innerHTML = data.notifications;

                },
                error: function (data) {

                    console.log(data);
                }
            });

            // document.getElementById('event_msg').innerHTML = e.message;
        }); //listen

    // for this reminder notification,the input should be in the format ...
    // type_of_notification:the_content (separated by |)
    // eg. event( new DossierEvaluationRemindersEvent(1, 'error:this is the message'))
    // Type is one of the following ...
    // reminder,error/danger,warning,info,success
    Echo.private('ReminderTo.{{auth()->user()->id}}')
        .listen('.reminder', (e) => {
            console.log('notification--  ' + e.message);

            let user_message = e.message;   // String separated by :
            let message_array = user_message.split("|");

            let type = message_array[0];
            let content = message_array[1];

            console.log('notification-type ' + type);

            // auto-hide toastr when close btn is clicked or ...
            // after 'timeOut' mili seconds
            toastr.options.closeButton = true;
            toastr.options.timeOut = 20000; // How long (in milisec) the toast will display without user interaction
            toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it

            //to prevent auto-hidding set timeOut=0, and extendedTimeOut=0
            // toastr.options.timeOut = 0;
            // toastr.options.extendedTimeOut = 0;

            toastr.options.progressBar = true;

            if (type == 'Reminder' || type == 'reminder') {
                toastr.error(content, "Reminder: ");
            } else if (type == 'error' || type == 'danger') {
                toastr.error(content, "Error: ");
            } else if (type == 'warning') {
                toastr.warning(content, "Warning: ");
            } else if (type == 'success') {
                toastr.success(content, "Success: ");
            } else if (type == 'info') {
                toastr.info(content, "Info: ");
            } else {
                console.log('type not found -- ' + type);
            }


            /*$(document).Toasts('create', {
                class: 'bg-warning',  //bg-maroon
                title: 'Reminder: ',
                subtitle: 'System Reminder',
                icon: 'fas fa-exclamation fa-lg',
                autohide:. true,
                delay: 10000,  // milisecs
                //position: 'bottomLeft',  //'topLeft'
                //image: '../../dist/img/user3-128x128.jpg',
                body: e.message
            })*/

            /*Toast.fire({
                icon: 'error',
                title: e.message
            })*/


            $.ajax({

                type: 'GET',

                url: "{{ route('retrieve_notifications') }}",

                data: {},

                success: function (data) {

                    document.getElementById('notification_count').innerHTML = data.notification_count;
                    document.getElementById('notification_count_header').innerHTML = data.notification_count;
                    document.getElementById('notification_contents').innerHTML = data.notifications;

                },
                error: function (data) {

                    console.log(data);
                }
            });

            // document.getElementById('event_msg').innerHTML = e.message;
        }); //listen
    //this code is ajax for loading notifications

    $.ajax({

        type: 'GET',

        url: "{{ route('retrieve_notifications') }}",

        data: {},

        success: function (data) {

            document.getElementById('notification_count').innerHTML = data.notification_count;
            document.getElementById('notification_count_header').innerHTML = data.notification_count;
            document.getElementById('notification_contents').innerHTML = data.notifications;

        },
        error: function (data) {

            console.log(data);
        }
    });

    $(function () {
        Toast = Swal.mixin({
            toast: true,  // false will show big toast(modal) that will block the whole page
            position: 'top-end',   //or center etc

        });
    });
</script>
</body>
</html>
