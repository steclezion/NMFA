<script>
  function Toastr()
  
  {
          toastr.options.closeButton = true;
          toastr.options.timeOut = 10000; // How long (in milisec) the toast will display without user interaction
          toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it
          toastr.options.progressBar = true;
}

$(function () {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  }); 




$('#letter_reject_acknowledgment').click(function () {


var letter_acknowledgement = document.getElementById('letter_acknowledgement').innerHTML;
var application_id = document.getElementById('application_id').value;
var current_date =  document.getElementById('current_date').innerHTML;
var  RL_squential_number  =  document.getElementById('RL_squential_number').innerHTML;
var applicant_name =  document.getElementById('applicant_name').innerHTML;
var state_plot_number  =  document.getElementById('state_plot_number').innerHTML;
var country  =  document.getElementById('country').innerHTML;
var contact_person_name    =  document.getElementById('contact_person_name').innerHTML;
var application_number =  document.getElementById('application_nummber').value;


if (confirm("Are you sure you want to save this letter of rejection."+
             "Action will not be reverted.") == true) 
              {


var acknowledegment_template  = document.getElementById('template_redesigned').innerHTML;
document.getElementById('letter_reject_acknowledgment').disabled=true;
document.getElementById('letter_reject_acknowledgment').innerHTML='Saving...';



$.ajax({
      
      data:{ 
        application_id: application_id,
        current_date:current_date,
        RL_squential_number:RL_squential_number,
        applicant_name:applicant_name,
        state_plot_number:state_plot_number,
        country:country,
        contact_person_name:contact_person_name,
        acknowledegment_template:acknowledegment_template ,
        application_number:application_number,
           },

     url: "{{   url('/save_letter_reject_acknowledgment/save')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.Message == true)
{

var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 6000
                  }); 
                  
document.getElementById('letter_reject_acknowledgment').disabled = true;
$('#letter_reject_acknowledgment').hide();
$('#actions_to_applicant').show('100');
$('#get_path').attr("href", data.Download_link);


Toastr();
toastr.success("Letter of rejection saved successfully .")
             
}
else 
                         {
                  var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000
                  });

               Toastr();
               toastr.error("Rejection Letter already produced")
               
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


}
else { return false;}



})




$('#acknowledgment_letter').click(function () {


var letter_acknowledgement = document.getElementById('letter_acknowledgement').innerHTML;
var application_id = document.getElementById('application_id').value;
var current_date =  document.getElementById('current_date').innerHTML;
var  RL_squential_number  =  document.getElementById('RL_squential_number').innerHTML;
var applicant_name =  document.getElementById('applicant_name').innerHTML;
var state_plot_number  =  document.getElementById('state_plot_number').innerHTML;
var country  =  document.getElementById('country').innerHTML;
var contact_person_name    =  document.getElementById('contact_person_name').innerHTML;
var application_number =  document.getElementById('application_number').innerHTML;
var number_days_receipts =  document.getElementById('days_of_receipt').value;

if(number_days_receipts=='' ||   number_days_receipts <= 0){document.getElementById('days_of_receipt').focus(); return false;}


if (confirm("Are you sure you want to save this letter of acknowledgment."+
             "Actions will not be reverted.") == true) 
              {

document.getElementById('num_day_html').innerHTML = number_days_receipts;
var acknowledegment_template  = document.getElementById('template_redesigned').innerHTML;
document.getElementById('acknowledgment_letter').disabled=true;
document.getElementById('acknowledgment_letter').innerHTML='Saving...';



$.ajax({
      
      data:{ 
        application_id: application_id,
        current_date:current_date,
        RL_squential_number:RL_squential_number,
        applicant_name:applicant_name,
        state_plot_number:state_plot_number,
        country:country,
        contact_person_name:contact_person_name,
        number_days_receipts:number_days_receipts,
        application_number:application_number,
        acknowledegment_template:acknowledegment_template ,
           },

     url: "{{   url('/save_acknowledgment_letter/save')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.Message == true)
{

var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 6000
                  }); 
                  
document.getElementById('acknowledgment_letter').disabled = true;
$('#acknowledgment_letter').hide();
$('#actions_to_applicant').show('100');
$('#get_path').attr("href", data.Download_link);

Toastr();
toastr.success("Acknowledgement Letter Saved Successfully.")
toastr.info("Application Dossier Home Directory Created.")
toastr.success("Application Preliminary screening completed Successfully.")
             
}
else 
                         {
                  var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000
                  });

               Toastr();
               toastr.error("Acknowledgement Letter already produced")
               
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


}
else { return false;}



});


$('#acknowledgment_letter_psur').click(function () {

var letter_acknowledgement = document.getElementById('letter_psur_acknowledgment').innerHTML;
var application_id = document.getElementById('application_id').value;
var psur_refrence_number = document.getElementById('psur_refrence_number').value;
var current_date =  document.getElementById('current_date').innerHTML;
var RL_squential_number  =  document.getElementById('RL_squential_number').innerHTML;
var applicant_name =  document.getElementById('applicant_name').innerHTML;
var state_plot_number  =  document.getElementById('state_plot_number').innerHTML;
var country  =  document.getElementById('country').innerHTML;
var contact_person_name    =  document.getElementById('contact_person_name').innerHTML;
var application_number =  document.getElementById('application_number').innerHTML;
var editable_html_summernote =  document.getElementById('letter_psur_acknowledgment').innerHTML;

   //alert(editable_html_summernote);


if (confirm("Are you sure you want to save this Acknowledgement Letter for the receipt of a Periodic Safety Update Report (PSUR)  "+
             "  Action will not be reverted.") == true) 
              {

document.getElementById('acknowledgment_letter_psur').disabled= true;
document.getElementById('acknowledgment_letter_psur').innerHTML= 'Saving....';


$.ajax({

      data:{
        application_id:application_id,
        psur_refrence_number:psur_refrence_number,
        current_date:current_date,
        RL_squential_number:RL_squential_number,
        applicant_name:applicant_name,
        state_plot_number:state_plot_number,
        country:country,
        contact_person_name:contact_person_name,
        edited_html_file :editable_html_summernote,
        application_number:application_number,
           },

     url: "{{   url('/save_acknowledgment_letter_psur/save')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.Message == true)
{

var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 6000
                  });

document.getElementById('acknowledgment_letter_psur').disabled = true;
$('#acknowledgment_letter_psur').hide();
$('#actions_to_applicant').show('100');
$('#get_path').attr("href", data.Download_link);

Toastr();
toastr.success("Acknowledgement letter for PSUR saved succesffuly.")


}
else
                         {
                  var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000
                  });

               Toastr();
               toastr.error("Error saving cosult the administration please!")

              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }


  });

}
else { return false;}

});





$('body').on('click', '.deleteFile', function () {

var application_id = $(this).data("id");
var document_id = $(this).data("document_id");


{if(confirm("Are You sure You Want To Delete This File")){}else{return false;}}
$.ajax({
    type: "DELETE",
    data:{
      application_id:application_id,
      document_id : document_id,
    },
    url: "{{ route('delete_file_uploaded_acknowledgment_letter.remove') }}",
    success: function (data) {
       // table.draw();
       Toastr();
       toastr.error("Acknowledgment letter Deleted Successfully")

       $('#table_upload_acknowledgement_letter').html(data.Data_returned);
       $('#UploadData').html("Upload");
       //document.getElementById('table_upload_invoice_letter').innerHTML = data.Data_returned;

    },
    error: function (data) {
        console.log('Error:', data);
    }
});
});



$('body').on('click', '.deleteFile_psur', function () {

var application_id        =    $(this).data("id");
var document_id           =    $(this).data("document_id");
var psur_reference_number =    $(this).data("psur_reference_number");


{if(confirm("Are You sure You Want To Delete This File")){}else{return false;}}
$.ajax({
    type: "DELETE",
    data:{
      application_id:application_id,
      document_id : document_id,
      psur_reference_number:psur_reference_number,
    },
    url: "{{ route('delete_file_uploaded_acknowledgment_letter_pusr.remove') }}",
    success: function (data) {
       // table.draw();
       Toastr();
       toastr.error("Acknowledgment letter for PSUR  Deleted Successfully")

       $('#table_upload_acknowledgement_letter_psur').html(data.Data_returned);
       $('#UploadData').html("Upload");
       //document.getElementById('table_upload_invoice_letter').innerHTML = data.Data_returned;

    },
    error: function (data) {
        console.log('Error:', data);
    }
});


});







});


</script>