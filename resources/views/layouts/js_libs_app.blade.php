<!-- jQuery -->
{{--@cannot('assessor-invoice-list')--}}
<!-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> -->
<!-- jQuery UI 1.11.4 -->
<!-- <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script> -->
{{--@endcannot--}}


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  // $.widget.bridge('uibutton', $.ui.button);
  // fadeTo(speed in ms, opacity)
  $("#alert-messages").fadeTo(15000, 1000).slideUp(500, function(){
      $("#alert-messages").slideUp(500);
  });
</script>

<!-- <script src="{{asset('plugins/jquery/plugins/jquery.min.js')}}"></script> -->
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>


<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
  $(function () {
    $("#example").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
  });
  

  function AllowonlyText_exceptional(event,id_name)
{
console.log(event.keyCode);
if (  event.keyCode ==222 || event.keyCode ==16   ||  event.keyCode ==16   ||   event.keyCode ==13 || event.keyCode ==20 || event.keyCode ==37 ||
event.keyCode ==38 ||  event.keyCode ==8 || event.keyCode ==39 || event.keyCode == 40 ||  event.keyCode == 17 || event.keyCode == 18   || event.keyCode ==32 ){ }

else if(  event.keyCode <=64 || event.keyCode >=91   ||  event.keyCode ==186  )

{ var s = document.getElementById(id_name).value; var sliced_value = s.substring(0,s.length-1); document.getElementById(id_name).value=sliced_value;}
}






function AllowonlyText(event,id_name) 
{
console.log(event.keyCode);


if (  event.keyCode ==222 || event.keyCode ==16   ||  event.keyCode ==16   ||   event.keyCode ==13 || event.keyCode ==20 || event.keyCode ==37 || 
event.keyCode ==38 ||  event.keyCode ==8 || event.keyCode ==39 || event.keyCode == 40 ||  event.keyCode == 17 || event.keyCode == 18   || event.keyCode ==32 ){ }

else if(  event.keyCode <=64 || event.keyCode >=91   ||  event.keyCode ==186  )

{
     var s = document.getElementById(id_name).value; var sliced_value = s.substring(0,s.length-1); document.getElementById(id_name).value=sliced_value;}
}


function AllowonlyText_Tele(event,id_name) 
{
console.log(event.keyCode);
// if (  event.keyCode ==16   ||   event.keyCode ==13 || event.keyCode ==20 || event.keyCode ==37 || event.keyCode ==38 ||  event.keyCode ==8 || event.keyCode ==39 || event.keyCode == 40 ||  event.keyCode == 17 || event.keyCode == 18 ){ }
if(  event.keyCode == 189 || event.keyCode == 187 || event.keyCode == 188 )
{   var s = document.getElementById(id_name).value; 
    var sliced_value = s.substring(0,s.length-1); 
    //alert(s);
    document.getElementById(id_name).value=sliced_value;}
}


    </script>
<script type="text/javascript">
$(function () {
  $('.demo-form').parsley().on('form:validate', function (formInstance) {
    var ok = formInstance.isValid({group: 'block1', force: true}) || formInstance.isValid({group: 'block2', force: true});
    $('.invalid-form-error-message')
      .html(ok ? '' : 'You must correctly fill *at least one of these two blocks!')
      .toggleClass('filled', !ok);
    if (!ok)
      formInstance.validationResult = false;
  });
});
</script>




<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
  $(function () {
    $("#example").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
  });
  
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper.col-md-6:eq(0)');
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

        //this function is for Unassigned dossers in Dossier assingment
        $(function () {
            $("#example3").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example3_wrapper.col-md-6:eq(0)');
            $('#example4').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        //this function is for assigned dossers in Dossier assingment
        $(function () {
            $("#example5").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example5_wrapper.col-md-6:eq(0)');
            $('#example6').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

 $(function() {
 $(document).ready(function(){
 var percent = $('#percent');
 var status = $('#status');
 
 $('form').ajaxForm({
 beforeSend: function() {
 status.empty();
 var percentVal = '0%';
 percent.html(percentVal);
 },
 uploadProgress: function(event, position, total, percentComplete) {
 var percentVal = percentComplete + '%';
 percent.html(percentVal);
 },
 complete: function(xhr) {
 status.html(xhr.responseText);
 }
 });
 });
 });




function filechangevalidation_pic(val,file_id,response_id)
{
if ($('input:submit').attr('disabled',false)){
	$('input:submit').attr('disabled',true);
    }
    
var ext = $('#'+file_id).val().split('.').pop().toLowerCase();
if ($.inArray(ext, ['jpeg','jpg','JPG','gif','ico','png','psd']) == -1){
	$('#error1').slideDown("slow");
    $('#error2').slideUp("slow");
    $('#error3').slideUp("slow");
    $('#'+response_id).hide('10');
	a=0;
	}else{
        $('#'+response_id).show('10');
    //var picsize = ($('#'+file_id).files[0].size);
    var picsize = ($('#'+file_id).get(0).files[0].size);
    console.log(picsize);
    var mb = picsize/1000000;

	if (picsize > 10000000){
    $('#error2').slideDown("slow");
    $('#'+response_id).hide('10');
    $('#error3').slideDown("slow");
    $('#file_size').html(Math.round(mb));


	a=0;
	}else{
	a=1;
    $('#error2').slideUp("slow");
    $('#'+response_id).show('10');
    $('#error3').slideUp("slow");
	}
	$('#error1').slideUp("slow");
	if (a==1){
        $('input:submit').attr('disabled',false);
        $('#'+response_id).show('10');
		}
}
}






$('input[type="submit"]').prop("disabled", true);
var a=0;
//binds to onchange event of your input field
function filechangevalidation(val,file_id,response_id)
{
if ($('input:submit').attr('disabled',false)){
	$('input:submit').attr('disabled',true);
    }
    
var ext = $('#'+file_id).val().split('.').pop().toLowerCase();
if ($.inArray(ext, ['pdf','oxps','docx','doc']) == -1){
	$('#error1').slideDown("slow");
    $('#error2').slideUp("slow");
    $('#error3').slideUp("slow");
    $('#'+response_id).hide('10');
	a=0;
	}else{
        $('#'+response_id).show('10');
    //var picsize = ($('#'+file_id).files[0].size);
    var picsize = ($('#'+file_id).get(0).files[0].size);
    console.log(picsize);
    var mb = picsize/1000000;

	if (picsize > 10000000){
    $('#error2').slideDown("slow");
    $('#'+response_id).hide('10');
    $('#error3').slideDown("slow");
    $('#file_size').html(Math.round(mb));


	a=0;
	}else{
	a=1;
    $('#error2').slideUp("slow");
    $('#'+response_id).show('10');
    $('#error3').slideUp("slow");
	}
	$('#error1').slideUp("slow");
	if (a==1){
        $('input:submit').attr('disabled',false);
        $('#'+response_id).show('10');
		}
}
}



//binds to onchange event of your input field
function filechangevalidation_zip(val,file_id,response_id)
{
if ($('input:submit').attr('disabled',false)){
	$('input:submit').attr('disabled',true);
    }

var ext = $('#'+file_id).val().split('.').pop().toLowerCase();
if ($.inArray(ext, ['rar','daa','zip','iso']) == -1){
	$('#error1').slideDown("slow");
    $('#error2').slideUp("slow");
    $('#error3').slideUp("slow");
    $('#'+response_id).hide('10');
	a=0;
	}else{
        $('#'+response_id).show('10');
    //var picsize = ($('#'+file_id).files[0].size);
    var picsize = ($('#'+file_id).get(0).files[0].size);
    console.log(picsize);
    var mb = picsize/1000000;

	if (picsize > 10000000){
    $('#error2').slideDown("slow");
    $('#'+response_id).hide('10');
    $('#error3').slideDown("slow");
    $('#file_size').html(Math.round(mb));


	a=0;
	}else{
	a=1;
    $('#error2').slideUp("slow");
    $('#'+response_id).show('10');
    $('#error3').slideUp("slow");
	}
	$('#error1').slideUp("slow");
	if (a==1){
        $('input:submit').attr('disabled',false);
        $('#'+response_id).show('10');
		}
}
}

//binds to onchange event of your input field
function filechangevalidation_doc(val,file_id,response_id)
{
if ($('input:submit').attr('disabled',false)){
	$('input:submit').attr('disabled',true);
    }

var ext = $('#'+file_id).val().split('.').pop().toLowerCase();
if ($.inArray(ext, ['docx','doc','docm','dotm','dotx','dot']) == -1){
	$('#error1').slideDown("slow");
    $('#error2').slideUp("slow");
    $('#error3').slideUp("slow");
    $('#'+response_id).hide('10');
	a=0;
	}else{
        $('#'+response_id).show('10');
    //var picsize = ($('#'+file_id).files[0].size);
    var picsize = ($('#'+file_id).get(0).files[0].size);
    console.log(picsize);
    var mb = picsize/1000000;

	if (picsize > 10000000){
    $('#error2').slideDown("slow");
    $('#'+response_id).hide('10');
    $('#error3').slideDown("slow");
    $('#file_size').html(Math.round(mb));


	a=0;
	}else{
	a=1;
    $('#error2').slideUp("slow");
    $('#'+response_id).show('10');
    $('#error3').slideUp("slow");
	}
	$('#error1').slideUp("slow");
	if (a==1){
        $('input:submit').attr('disabled',false);
        $('#'+response_id).show('10');
		}
}
}







    </script>
<script type="text/javascript">
$(function () {
  $('.demo-form').parsley().on('form:validate', function (formInstance) {
    var ok = formInstance.isValid({group: 'block1', force: true}) || formInstance.isValid({group: 'block2', force: true});
    $('.invalid-form-error-message')
      .html(ok ? '' : 'You must correctly fill *at least one of these two blocks!')
      .toggleClass('filled', !ok);
    if (!ok)
      formInstance.validationResult = false;
  });
});
</script>