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

  function Toastr()
  
  {
          toastr.options.closeButton = true;
          toastr.options.timeOut = 10000; // How long (in milisec) the toast will display without user interaction
          toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it
          toastr.options.progressBar = true;
}
</script>


<script>

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  }); 



$('#submit_to_supervisor_section_two').click(function () {

var application_id   =  document.getElementById('app_id').value;
var supervisor_hold_assessor_progress = ',1,2';


if(document.getElementById('update_section_two').style.display=='none')
{

     var Toast = Swal.mixin({
      toast: true,
      position: 'top-center',
      showConfirmButton: true,
      timer: 6000
    });

$("#save_section_two").toggleClass("btn-danger");
  // alert("Save your status for general section then proceed futher with submitting");
  Toast.fire({
        icon: 'error',
        color:'red',
        title: 'Save status for General Requirements (section two) and proceed with submitting.'
      })

  document.getElementById('save_section_two').focus();
  return false;

}

$.ajax({
      
      data:{ 
          application_id:application_id,
          supervisor_hold_assessor_progress:supervisor_hold_assessor_progress,
            },
     url: "{{   url('/submit_section_two_checklist/save')   }}",
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
                  
              document.getElementById('submit_to_supervisor_section_two').disabled = true;

              document.getElementById('send_message_to_supervisor').disabled = false;
          
Toastr();
toastr.success("Section two (General Requirements) sumbitted successfuly.")
             
}
else 
                         {
                          
                Toastr();
               toastr.error(data.item)
               
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


});



//submit_to_supervisor_section_four

$('#submit_to_supervisor_section_four').click(function () {
  var application_id   =  document.getElementById('app_id').value;
var supervisor_hold_assessor_progress = ',4';

// if(document.getElementById('sample_product_yes').checked != true && document.getElementById('sample_product_no').checked != true  && document.getElementById('sample_product_not_applicable').checked != true)
// {
// document.getElementById('sample_product_yes').focus(); return false;
// }

// if(document.getElementById('sample_scheduled_yes').checked != true && document.getElementById('sample_scheduled_no').checked != true )
// {
// document.getElementById('sample_scheduled_yes').focus(); return false;
// }



// if(document.getElementById('availability_packages_yes').checked != true && document.getElementById('availability_packages_no').checked != true  )
// {
// document.getElementById('availability_packages_yes').focus(); return false;
// }

// if(document.getElementById('sample_shelf_life_yes').checked != true && document.getElementById('sample_shelf_life_no').checked != true  )
// {
// document.getElementById('sample_shelf_life_yes').focus(); return false;
// }


// if(document.getElementById('manufacturing_premises_yes').checked != true && document.getElementById('manufacturing_premises_no').checked != true )
// {
// document.getElementById('manufacturing_premises_yes').focus(); return false;
// }

// if(document.getElementById('availability_certificate_analysis_yes').checked != true &&  document.getElementById('availability_certificate_analysis_no').checked != true  )
// {
// document.getElementById('availability_certificate_analysis_yes').focus(); return false;
// }







if(document.getElementById('update_section_two').style.display=='none')
{



     var Toast = Swal.mixin({
      toast: true,
      position: 'top-center',
      showConfirmButton: true,
      timer: 6000
    });

  // alert("Save your status for general section then proceed futher with submitting");
  $("#save_section_two").toggleClass("btn-info");
  Toast.fire({
        icon: 'error',
        color:'red',
        title: 'Unable to save entry because section two ( General Requirements) is not submitted.'
      })

  document.getElementById('save_section_two').focus();
  return false;

}
else{
$.ajax({
      
      data:{ 
          application_id:application_id,
          supervisor_hold_assessor_progress:supervisor_hold_assessor_progress,
            },
     url: "{{   url('/submit_section_four_checklist/save')   }}",
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
  document.getElementById('submit_to_supervisor_section_four').disabled = true;
Toastr();
 toastr.success("Section 4: Sample details submitted successfully.")
             
}

else if(data.Message == 'incorrect')
{


  $("#update_section_four").toggleClass("btn-danger");
  document.getElementById('update_section_four').focus();
  Toast.fire({
        icon: 'error',
        color:'red',
        title: 'Section 4: Sample Details is not saved.'
      })
             
}

else 
                         {
                  var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000
                  });

               toastr.error(data.item)
               
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });

}



});


$('#submit_to_supervisor_section_three').click(function () {

var application_id   =  document.getElementById('app_id').value;
var supervisor_hold_assessor_progress = ',3';


if(document.getElementById('update_section_two').style.display=='none')
{



     var Toast = Swal.mixin({
      toast: true,
      position: 'top-center',
      showConfirmButton: true,
      timer: 6000
    });

  // alert("Save your status for general section then proceed futher with submitting");
  $("#save_section_two").toggleClass("btn-info");
  Toast.fire({
        icon: 'error',
        color:'red',
        title: 'Entry unable to save due to section two preliminary screening is not submitted!!'
      })

  document.getElementById('save_section_two').focus();
  return false;

}
else{
$.ajax({
      
      data:{ 
          application_id:application_id,
          supervisor_hold_assessor_progress:supervisor_hold_assessor_progress,
            },
     url: "{{   url('/submit_section_three_checklist/save')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.Message == true)
{


              document.getElementById('submit_to_supervisor_section_two').disabled = true;
              Toastr();
 toastr.success("Section three preliminary screening submitted successfuly.")
             
}

else if(data.Message == 'incorrect')
{

 var Toast = Swal.mixin({
      toast: true,
      position: 'top-center',
      showConfirmButton: true,
      timer: 6000
    });
  
  $("#update_section_three").toggleClass("btn-danger");
  document.getElementById('update_section_three').focus();
  Toast.fire({
        icon: 'error',
        color:'red',
        title: 'Section 3: Specific requirements for fast-track registration is not saved!!'
      })
             
}

else 
                         {
                          Toastr();
               toastr.error(data.item)
               
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });

}


});













$('#save_section_two').click(function () {

var application_id   =  document.getElementById('app_id').value;
var Remark_step_two  =  document.getElementById('summernote').value;

if(document.getElementById('Presence_of_application_letter_yes').checked == true) { var application_letter = 1;}
else if(document.getElementById('Presence_of_application_letter_no').checked == true) { var application_letter = 0;}
if(document.getElementById('manufacturer_information_yes').checked == true) { var manufacturer_information = 1;}
else if(document.getElementById('manufacturer_information_no').checked == true) { var manufacturer_information = 0;}

if(document.getElementById('local_agent_yes').checked == true) { var local_agent = 1;}
else if(document.getElementById('local_agent_no').checked == true) { var local_agent = 0;}
else if(document.getElementById('local_agent_not_applicable').checked == true) { var local_agent = 'no_app';}

if(document.getElementById('enlm_yes').checked == true) { var enml = 1;}
else if(document.getElementById('enlm_no').checked == true) { var emnl= 0;}

if(document.getElementById('dossier_ctd_yes').checked == true) { var dossier_ctd = 1; }
else if(document.getElementById('dossier_ctd_no').checked == true) { var dossier_ctd= 0;}

if(document.getElementById('module_one_yes').checked == true) { var module_one= 1;}
else if(document.getElementById('module_one_no').checked == true) { var module_one = 0;}
if(document.getElementById('module_two_yes').checked == true) { var module_two= 1;}
else if(document.getElementById('module_two_no').checked == true) { var module_two = 0;}
if(document.getElementById('module_three_yes').checked == true) { var module_three = 1;}
else if(document.getElementById('module_three_no').checked == true) { var module_three = 0;}
if(document.getElementById('module_four_yes').checked == true) { var module_four= 1;}
else if(document.getElementById('module_four_no').checked == true) { var module_four = 0;}
if(document.getElementById('module_five_yes').checked == true) { var module_five= 1;}
else if(document.getElementById('module_five_no').checked == true) { var module_five = 0;}




if(document.getElementById('Presence_of_application_letter_yes').checked != true &&  document.getElementById('Presence_of_application_letter_no').checked != true )
{
document.getElementById('Presence_of_application_letter_yes').focus(); return false;
}


if(document.getElementById('manufacturer_information_yes').checked != true && document.getElementById('manufacturer_information_no').checked != true )
{
document.getElementById('manufacturer_information_yes').focus(); return false;
}


if(document.getElementById('local_agent_yes').checked != true && document.getElementById('local_agent_no').checked != true &&   document.getElementById('local_agent_not_applicable').checked != true )
{
document.getElementById('local_agent_yes').focus(); return false;
}


if(document.getElementById('enlm_yes').checked != true && document.getElementById('enlm_no').checked != true  )
{
document.getElementById('enlm_yes').focus(); return false;
}
//dossier_ctd_yes

if(document.getElementById('dossier_ctd_yes').checked != true && document.getElementById('dossier_ctd_no').checked != true  )
{
document.getElementById('dossier_ctd_yes').focus(); return false;
}


if(document.getElementById('module_one_yes').checked != true && document.getElementById('module_one_no').checked != true  )
{
document.getElementById('module_one_yes').focus(); return false;
}


if(document.getElementById('module_two_yes').checked != true && document.getElementById('module_two_no').checked != true  )
{
document.getElementById('module_two_yes').focus(); return false;
}


if(document.getElementById('module_three_yes').checked != true && document.getElementById('module_three_no').checked != true  )
{
document.getElementById('module_three_yes').focus(); return false;
}



if(document.getElementById('module_four_yes').checked != true && document.getElementById('module_four_no').checked != true  )
{
document.getElementById('module_four_yes').focus(); return false;
}



if(document.getElementById('module_five_yes').checked != true && document.getElementById('module_five_no').checked != true  )
{
document.getElementById('module_five_yes').focus(); return false;
}

// alert( dossier_ctd);

$.ajax({
      
      data:{ 
          application_id:application_id,
          application_letter:application_letter,
          manufacturer_information:manufacturer_information,
          local_agent:local_agent,
          enml:enml,
          module_one:module_one,
          module_two:module_two,
          module_three:module_three,
          module_four:module_four,
          module_five:module_five,
          Remark_step_two:Remark_step_two,
          dossier_ctd:dossier_ctd,
           },

      url: "{{   url('/save_section_two_checklist/save')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.Message == true)
{


              
                  jQuery('#save_section_two').hide('100');
                  jQuery('#update_section_two').show('100');
                  Toastr();
                  toastr.success("Section two checklist saved successfuly.")
             
}
else 
                         {
Toastr();
toastr.error(data.Message.errorInfo[2])

              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


}); 



	 $('#print_check').click(function () {
         var divName = 'print_checklist';
        
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
        // window.open(printContents);
         //window.print();
         window.addEventListener("load", window.print())
         window.location="{{ route('check_list.index')  }}";
         //document.body.innerHTML = originalContents;
	    // window.location="{{ url('/invoice') }}";

          
});



$('#update_section_two').click(function () {

var application_id   =  document.getElementById('app_id').value;
var Remark_step_two  =  document.getElementById('summernote').value;

if(document.getElementById('Presence_of_application_letter_yes').checked == true) { var application_letter = 1;}
else if(document.getElementById('Presence_of_application_letter_no').checked == true) { var application_letter = 0;}
if(document.getElementById('manufacturer_information_yes').checked == true) { var manufacturer_information = 1;}
else if(document.getElementById('manufacturer_information_no').checked == true) { var manufacturer_information = 0;}


if(document.getElementById('local_agent_yes').checked == true) { var local_agent = 1;}
else if(document.getElementById('local_agent_no').checked == true) { var local_agent = 0;}
else if (document.getElementById('local_agent_not_applicable').checked == true) { var local_agent = 'no_app';}



if(document.getElementById('enlm_yes').checked == true) { var enml = 1;}
else if(document.getElementById('enlm_no').checked == true) { var emnl= 0;}

if(document.getElementById('dossier_ctd_yes').checked == true) { var dossier_ctd = 1; }
else if(document.getElementById('dossier_ctd_no').checked == true) { var dossier_ctd= 0;}


if(document.getElementById('module_one_yes').checked == true) { var module_one= 1;}
else if(document.getElementById('module_one_no').checked == true) { var module_one = 0;}
if(document.getElementById('module_two_yes').checked == true) { var module_two= 1;}
else if(document.getElementById('module_two_no').checked == true) { var module_two = 0;}
if(document.getElementById('module_three_yes').checked == true) { var module_three = 1;}
else if(document.getElementById('module_three_no').checked == true) { var module_three = 0;}
if(document.getElementById('module_four_yes').checked == true) { var module_four= 1;}
else if(document.getElementById('module_four_no').checked == true) { var module_four = 0;}
if(document.getElementById('module_five_yes').checked == true) { var module_five= 1;}
else if(document.getElementById('module_five_no').checked == true) { var module_five = 0;}




if(document.getElementById('Presence_of_application_letter_yes').checked != true &&  document.getElementById('Presence_of_application_letter_no').checked != true )
{
document.getElementById('Presence_of_application_letter_yes').focus(); return false;
}


if(document.getElementById('manufacturer_information_yes').checked != true && document.getElementById('manufacturer_information_no').checked != true )
{
document.getElementById('manufacturer_information_yes').focus(); return false;
}


if(document.getElementById('local_agent_yes').checked != true && document.getElementById('local_agent_no').checked != true && document.getElementById('local_agent_not_applicable').checked != true )
{
document.getElementById('local_agent_yes').focus(); return false;
}


if(document.getElementById('enlm_yes').checked != true && document.getElementById('enlm_no').checked != true  )
{
document.getElementById('enlm_yes').focus(); return false;
}


//dossier_ctd_yes

if(document.getElementById('dossier_ctd_yes').checked != true && document.getElementById('dossier_ctd_no').checked != true  )
{
document.getElementById('dossier_ctd_yes').focus(); return false;
}


if(document.getElementById('module_one_yes').checked != true && document.getElementById('module_one_no').checked != true  )
{
document.getElementById('module_one_yes').focus(); return false;
}


if(document.getElementById('module_two_yes').checked != true && document.getElementById('module_two_no').checked != true  )
{
document.getElementById('module_two_yes').focus(); return false;
}


if(document.getElementById('module_three_yes').checked != true && document.getElementById('module_three_no').checked != true  )
{
document.getElementById('module_three_yes').focus(); return false;
}



if(document.getElementById('module_four_yes').checked != true && document.getElementById('module_four_no').checked != true  )
{
document.getElementById('module_four_yes').focus(); return false;
}



if(document.getElementById('module_five_yes').checked != true && document.getElementById('module_five_no').checked != true  )
{

document.getElementById('module_five_yes').focus(); return false;

}


//alert(local_agent );

$.ajax({
      
      data:{ 
          application_id:application_id,
          application_letter:application_letter,
          manufacturer_information:manufacturer_information,
          local_agent:local_agent,
          enml:enml,
          module_one:module_one,
          module_two:module_two,
          module_three:module_three,
          module_four:module_four,
          module_five:module_five,
          Remark_step_two:Remark_step_two,
          dossier_ctd:dossier_ctd,
           },

      url: "{{   url('/update_section_two_checklist/update')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.section_two_update == true)
{
  Toastr();
  toastr.success("Section two checklist updated successfully.")             
}

else if(data.section_two_update ==false)
{
  Toastr();
  toastr.info("Section Two Checklist  :No update is made.")
             
}
else 
                         {
Toastr();
toastr.error("Internal Error : Consult Admin Please ")

              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


}); 





$('#update_section_three').click(function () {

var application_id   =  document.getElementById('app_id').value;
var Remark_step_three  =  document.getElementById('summernotee').value;



if(document.getElementById('Presence_of_the_full_inspection_reports_yes').checked == true) { var    Presence_of_the_full_inspection_reports = 1;}
else if(document.getElementById('Presence_of_the_full_inspection_reports_no').checked == true) { var Presence_of_the_full_inspection_reports = 0;}
if(document.getElementById('valid_marketing_authorization_yes').checked == true) { var    valid_marketing_authorization = 1;}
else if(document.getElementById('valid_marketing_authorization_no').checked == true) { var valid_marketing_authorization = 0;}
if(document.getElementById('qis_prequalified_products_yes').checked == true) { var qis_prequalified_products = 1;}
else if(document.getElementById('manufacturer_information_no').checked == true) { var qis_prequalified_products = 0;}
if(document.getElementById('Presence_of_full_assessment_report_yes').checked == true) { var Presence_of_full_assessment_report = 1;}
else if(document.getElementById('Presence_of_full_assessment_report_no').checked == true) { var Presence_of_full_assessment_report = 0;}
if(document.getElementById('Product_Characteristics_yes').checked == true) { var Product_Characteristics = 1;}
else if(document.getElementById('Product_Characteristics_no').checked == true) { var Product_Characteristics= 0;}
if(document.getElementById('information_patient_user_yes').checked == true) { var information_patient_user = 1;}
else if(document.getElementById('information_patient_user_no').checked == true) { var information_patient_user = 0;}







if(document.getElementById('valid_marketing_authorization_yes').checked != true && document.getElementById('valid_marketing_authorization_no').checked != true )
{
document.getElementById('valid_marketing_authorization_yes').focus(); return false;
}

if(document.getElementById('qis_prequalified_products_yes').checked != true && document.getElementById('qis_prequalified_products_no').checked != true )
{
document.getElementById('qis_prequalified_products_yes').focus(); return false;
}

if(document.getElementById('Presence_of_full_assessment_report_yes').checked != true && document.getElementById('Presence_of_full_assessment_report_no').checked != true )
{
document.getElementById('Presence_of_full_assessment_report_yes').focus(); return false;
}

if(document.getElementById('Presence_of_the_full_inspection_reports_yes').checked != true && document.getElementById('Presence_of_the_full_inspection_reports_no').checked != true  )
{
document.getElementById('Presence_of_the_full_inspection_reports_yes').focus(); return false;
}

if(document.getElementById('Product_Characteristics_yes').checked != true && document.getElementById('Product_Characteristics_no').checked != true  )
{
document.getElementById('Product_Characteristics_yes').focus(); return false;
}


if(document.getElementById('Presence_of_the_full_inspection_reports_yes').checked != true && document.getElementById('Presence_of_the_full_inspection_reports_no').checked != true )
{
document.getElementById('Presence_of_the_full_inspection_reports_yes').focus(); return false;
}

if(document.getElementById('information_patient_user_yes').checked != true && document.getElementById('information_patient_user_no').checked != true  )
{
document.getElementById('information_patient_user_yes').focus(); return false;
}




$.ajax({
      
      data:{ 
          application_id:application_id,
          valid_marketing_authorization:valid_marketing_authorization,
          qis_prequalified_products:qis_prequalified_products,
          Presence_of_full_assessment_report:Presence_of_full_assessment_report,
          Product_Characteristics:Product_Characteristics,
          information_patient_user:information_patient_user,
          Presence_of_the_full_inspection_reports:Presence_of_the_full_inspection_reports,
          Remark_step_three:Remark_step_three,
         
           },

      url: "{{   url('/update_section_three_checklist/update')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.section_three_update == true)
{
  Toastr();
  toastr.success("Section Three Checklist: updated successfuly. ")
             
}

else if(data.section_three_update ==false)
{
  Toastr();
  toastr.info("Section Three Checklist  :No update is made.")         
}
else 
                         {
Toastr();
toastr.error("Internal Error : Consult Admin Please ")
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


}); 







$('#update_section_four').click(function () 
{

  var application_id   =  document.getElementById('app_id').value;
  var Remark_step_four  =  document.getElementById('section_four_remark').value;
  var Number_of_sample_received = document.getElementById('Number_of_sample_received').value;
  var net_sample_weight = document.getElementById('sampling_net_weight').value;
  var section_four_remark_netweight=document.getElementById('section_four_remark_netweight').value;
  var summernote_Remark_section_four = document.getElementById('summernote_Remark_section_four').value;
  var sample_received_date =  document.getElementById('sample_received_date').value;


  if(document.getElementById('sample_product_yes').checked != true && document.getElementById('sample_product_no').checked != true  && document.getElementById('sample_product_not_applicable').checked != true)
   {document.getElementById('sample_product_yes').focus(); return false;}

if(document.getElementById('sample_product_no').checked == true)
  {
//sample_product 
if(document.getElementById('sample_product_yes').checked == true) { var sample_product = 1;}
else if(document.getElementById('sample_product_no').checked == true) { var sample_product_not_applicable = 0;}
else if(document.getElementById('sample_product_not_applicable').checked == true) { var sample_product= 'Not-Applicable';}

//sample_scheduled_yes
if(document.getElementById('sample_scheduled_yes').checked == true) { var sample_scheduled = 1;}
else if(document.getElementById('sample_scheduled_no').checked == true) { var sample_scheduled = 0;}
// else if(document.getElementById('sample_scheduled_not_applicable').checked == true) { var sample_product= 'Not-Applicable';}



//Availabilty of Packages
if(document.getElementById('availability_packages_yes').checked == true) { var  availability_packages = 1;}
else if(document.getElementById('availability_packages_yes').checked == true) { var  availability_packages = 0;}


// sample_shelf_life_yes
if(document.getElementById('sample_shelf_life_yes').checked == true) { var  sample_shelf_life = 1;}
else if(document.getElementById('sample_shelf_life_no').checked == true) { var  sample_shelf_life = 0;}
else if(document.getElementById('sample_shelf_life_not_applicable').checked == true) { var  sample_shelf_life = 'no_app';}


// availability_certificate_analysis_yes  
if(document.getElementById('availability_certificate_analysis_yes').checked == true) { var availability_certificate_analysis= 1;}
else if(document.getElementById('availability_certificate_analysis_no').checked == true) { var  availability_certificate_analysis = 0;}


// manufacturing_premises_yes  
if(document.getElementById('manufacturing_premises_yes').checked == true) { var      manufacturing_premises= 1;}
else if(document.getElementById('manufacturing_premises_no').checked == true) { var  manufacturing_premises = 0;}




                 }

                 else if(document.getElementById('sample_product_no').checked == false)
                 {

//sample_product 
if(document.getElementById('sample_product_yes').checked == true) { var sample_product = 1;}
else if(document.getElementById('sample_product_no').checked == true) { var sample_product_not_applicable = 0;}
else if(document.getElementById('sample_product_not_applicable').checked == true) { var sample_product= 'Not-Applicable';}


// if(Number_of_sample_received =='') {document.getElementById('Number_of_sample_received').focus(); return  false;}



//sample_scheduled_yes
if(document.getElementById('sample_scheduled_yes').checked == true) { var sample_scheduled = 1;}
else if(document.getElementById('sample_scheduled_no').checked == true) { var sample_scheduled = 0;}
// else if(document.getElementById('sample_scheduled_not_applicable').checked == true) { var sample_product= 'Not-Applicable';}



//Availabilty of Packages
if(document.getElementById('availability_packages_yes').checked == true) { var  availability_packages = 1;}
else if(document.getElementById('availability_packages_yes').checked == true) { var  availability_packages = 0;}


// sample_shelf_life_yes
if(document.getElementById('sample_shelf_life_yes').checked == true) { var  sample_shelf_life = 1;}
else if(document.getElementById('sample_shelf_life_no').checked == true) { var  sample_shelf_life = 0;}
else if(document.getElementById('sample_shelf_life_not_applicable').checked == true) { var  sample_shelf_life = 'no_app';}




// manufacturing_premises_yes  
if(document.getElementById('manufacturing_premises_yes').checked == true) { var      manufacturing_premises= 1;}
else if(document.getElementById('manufacturing_premises_no').checked == true) { var  manufacturing_premises = 0;}


// availability_certificate_analysis_yes  
if(document.getElementById('availability_certificate_analysis_yes').checked == true) { var availability_certificate_analysis= 1;}
else if(document.getElementById('availability_certificate_analysis_no').checked == true) { var  availability_certificate_analysis = 0;}



if(document.getElementById('sample_product_yes').checked != true && document.getElementById('sample_product_no').checked != true  && document.getElementById('sample_product_not_applicable').checked != true)
{
document.getElementById('sample_product_yes').focus(); return false;
}

if(document.getElementById('sample_scheduled_yes').checked != true && document.getElementById('sample_scheduled_no').checked != true )
{
document.getElementById('sample_scheduled_yes').focus(); return false;
}



if(document.getElementById('availability_packages_yes').checked != true && document.getElementById('availability_packages_no').checked != true  )
{
document.getElementById('availability_packages_yes').focus(); return false;
}

if(document.getElementById('sample_shelf_life_yes').checked != true && document.getElementById('sample_shelf_life_no').checked != true && document.getElementById('sample_shelf_life_not_applicable').checked != true  )
{
document.getElementById('sample_shelf_life_yes').focus(); return false;
}


if(document.getElementById('manufacturing_premises_yes').checked != true && document.getElementById('manufacturing_premises_no').checked != true )
{
document.getElementById('manufacturing_premises_yes').focus(); return false;
}

if(document.getElementById('availability_certificate_analysis_yes').checked != true &&  document.getElementById('availability_certificate_analysis_no').checked != true  )
{
document.getElementById('availability_certificate_analysis_yes').focus(); return false;
}


if(net_sample_weight =='') {document.getElementById('sampling_net_weight').focus(); return  false;}



if(sample_received_date =='') {document.getElementById('sample_received_date').focus(); return  false;
}

}

if(document.getElementById('availability_certificate_analysis_yes').checked != true &&  document.getElementById('availability_certificate_analysis_no').checked != true  )
{
document.getElementById('availability_certificate_analysis_yes').focus(); return false;
}
  


$.ajax({
      
      data:{ 
    application_id:application_id,
    sample_product:sample_product,
    sample_scheduled:sample_scheduled,
    net_sample_weight:net_sample_weight,
    sample_shelf_life:sample_shelf_life,
    manufacturing_premises:manufacturing_premises,
    availability_packages:availability_packages,
    availability_certificate_analysis: availability_certificate_analysis,
    Number_of_sample_received:Number_of_sample_received,
    Remark_step_four:Remark_step_four,
    Sample_net_volume_or_weight_remark:section_four_remark_netweight,
    Availability_of_an_official_of_analysis_remark:summernote_Remark_section_four,
    section_four_remark_netweight:section_four_remark_netweight,
    summernote_Remark_section_four:summernote_Remark_section_four,
    sample_received_date:sample_received_date,
    

         
           },

      url: "{{   url('/update_section_four_checklist/update')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.section_four_update == true)
{
  Toastr(); 
  toastr.success("Section Four Checklist: updated successfuly. ")
             
}

else if(data.section_four_update ==false)
{
  Toastr();
  toastr.info("Section Four Checklist  :No update is made.")         
}
else 
                         {
Toastr();
toastr.error("Internal Error : Consult Admin Please ")
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


}); 





$('#update_section_five').click(function () {

var application_id   =  document.getElementById('app_id').value;

var Generated_Invoice_Number =  document.getElementById('Generated_Invoice_Number').value;
var Application_Receipt_Number  =  document.getElementById('Application_Receipt_Number').value;
let checking_application_fee_yes = document.getElementById('checking_application_fee_yes');

if(Generated_Invoice_Number == '') { Toastr();  toastr.warning("Invoice Number is Empty"); return false;}


if(Application_Receipt_Number == '' || Application_Receipt_Number == 'NMFA/------') { Toastr(); toastr.warning("Application receipt number is empty");  return false; }


if(checking_application_fee_yes.checked == false) { Toastr(); toastr.warning("Application Check Fee is not selected"); document.getElementById('checking_application_fee_yes').focus(); return false; }



//checking_application_fee_yes
if(document.getElementById('checking_application_fee_yes').checked == true) { var checking_application_fee = 1;}
else if(document.getElementById('checking_application_fee_no').checked == true) { var checking_application_fee = 0;}

//Payment   
var application_receipt_number = document.getElementById('Application_Receipt_Number').value;

//Generated_Invoice_Number_yes
var Generated_Invoice_Number = document.getElementById('Generated_Invoice_Number').value;

//Remark_Sectionfive

var Remark_section_five = document.getElementById('Remark_section_five').value;


//Over All comments
var over_all_comment = document.getElementById('over_all_comment').value;




if(document.getElementById('checking_application_fee_yes').checked != true &&  document.getElementById('checking_application_fee_no').checked != true  )
{
document.getElementById('checking_application_fee_yes').focus(); return false;
}







$.ajax({
      
      data:{ 
        application_id:application_id,
        Application_Receipt_Number:application_receipt_number,
        checking_application_fee:checking_application_fee,
        Generated_Invoice_Number:Generated_Invoice_Number,
        Remark_section_five:Remark_section_five,
        over_all_comment:over_all_comment,

         
           },

      url: "{{   url('/update_section_five_checklist/update')   }}",
      type: "POST",
      dataType: 'json',
      success: function (data) {
if(data.section_five_update == true)
{
 
    Toastr();   
    toastr.success("Section Five Checklist: updated successfully.")
             
}

else if(data.section_five_update ==false)
{
  Toastr();
  toastr.info("Section Five Checklist  :No update is made.")
             
}
else 
                         {
                          Toastr();

               toastr.error("Internal Error : Consult Admin Please ")
              }

      },
      error: function (data) {
          console.log('Error:', data);
          $('#saveBtn').html('Save Changes');
      }

    
  });


}); 






















  //Presence_of_sample_product
 $('#sample_product_yes').click(function () {
if( document.getElementById('sample_product_yes').checked==true) 
{ 
   document.getElementById('sample_product_yes').disabled =false;
  document.getElementById('sample_product_no').checked =false;
  document.getElementById('sample_product_not_applicable').checked =false;

}
 });

 $('#sample_product_no').click(function () {
if( document.getElementById('sample_product_no').checked==true) 
{ document.getElementById('sample_product_yes').disabled =false;
  document.getElementById('sample_product_yes').checked =false;
  document.getElementById('sample_product_not_applicable').checked =false;


}
 });


  $('#sample_product_not_applicable').click(function () {
if( document.getElementById('sample_product_not_applicable').checked==true) 
{ 
  document.getElementById('sample_product_yes').disabled =false;
  document.getElementById('sample_product_yes').checked =false;
  document.getElementById('sample_product_no').checked =false;
}
 });


//Presence_of_application_letter
 $('#Presence_of_application_letter_yes').click(function () {
if( document.getElementById('Presence_of_application_letter_yes').checked==true) 
{ document.getElementById('Presence_of_application_letter_no').checked =false}
 });

 $('#Presence_of_application_letter_no').click(function () {
if( document.getElementById('Presence_of_application_letter_no').checked==true) 
{ document.getElementById('Presence_of_application_letter_yes').checked =false}
 });

 //applicable_appendices
 $('#applicable_appendices_yes').click(function () {
if( document.getElementById('applicable_appendices_yes').checked==true) 
{ document.getElementById('applicable_appendices_no').checked =false}
 });

 $('#applicable_appendices_no').click(function () {
if( document.getElementById('applicable_appendices_no').checked==true) 
{ document.getElementById('applicable_appendices_yes').checked =false}
 });


    //local_agent
    $('#applicable_appendices_yes').click(function () {
if( document.getElementById('local_agent_yes').checked==true) 
{ document.getElementById('local_agent_no').checked =false;}
 });

 $('#local_agent_no').click(function () {
if( document.getElementById('local_agent_no').checked==true) 
{ document.getElementById('local_agent_yes').checked =false;
  document.getElementById('local_agent_yes').disabled =false;
  document.getElementById('local_agent_not_applicable').checked =false;
  }
 });


  $('#local_agent_not_applicable').click(function () {
if( document.getElementById('local_agent_not_applicable').checked==true) 
{ document.getElementById('local_agent_yes').checked =false;
  document.getElementById('local_agent_yes').disabled =false;
  document.getElementById('local_agent_no').checked =false;}
 });



   $('#local_agent_yes').click(function () {
if( document.getElementById('local_agent_yes').checked==true) 
{ 
  document.getElementById('local_agent_no').checked =false;
  document.getElementById('local_agent_yes').disabled =false;
  document.getElementById('local_agent_not_applicable').checked =false;

  }
 });


    //manufacturer_information
    $('#manufacturer_information_yes').click(function () {
if( document.getElementById('manufacturer_information_yes').checked==true) 
{ document.getElementById('manufacturer_information_no').checked =false;
  document.getElementById('manufacturer_information_yes').disabled=false;}

 });

 $('#manufacturer_information_no').click(function () {
if( document.getElementById('manufacturer_information_no').checked==true) 
{ document.getElementById('manufacturer_information_yes').checked =false;
  document.getElementById('manufacturer_information_yes').disabled=false;}
 });



 //manufacturer_information
 $('#valid_marketing_authorization_yes').click(function () {
if( document.getElementById('valid_marketing_authorization_yes').checked==true) 
{ document.getElementById('valid_marketing_authorization_no').checked =false;
}
 });

 $('#valid_marketing_authorization_no').click(function () {
if( document.getElementById('valid_marketing_authorization_no').checked==true) 
{ document.getElementById('valid_marketing_authorization_yes').checked =false}
 });



//reference_stringent_regulatory
$('#reference_stringent_regulatory_yes').click(function () {
if( document.getElementById('reference_stringent_regulatory_yes').checked==true) 
{ document.getElementById('reference_stringent_regulatory_no').checked =false;
}
 });

 $('#reference_stringent_regulatory_no').click(function () {
if( document.getElementById('reference_stringent_regulatory_no').checked==true) 
{ document.getElementById('reference_stringent_regulatory_yes').checked =false}
 });



  //Enml Lists 
$('#enlm_yes').click(function () {
if( document.getElementById('enlm_yes').checked==true) 
{ document.getElementById('enlm_no').checked =false;
  document.getElementById('enlm_yes').disabled=false;}
 });

 $('#enlm_no').click(function () {
if( document.getElementById('enlm_no').checked==true) 
{ 
  document.getElementById('enlm_yes').checked =false;
  document.getElementById('enlm_yes').disabled=false;
  
  }
 });

 //dossier_ctd Presence of the submitted dossier in CTD format as per the requested format4

 $('#dossier_ctd_yes').click(function () {
if( document.getElementById('dossier_ctd_yes').checked==true) 
{ document.getElementById('dossier_ctd_no').checked =false;
  document.getElementById('dossier_ctd_yes').disabled=false;}
 });

 $('#dossier_ctd_no').click(function () {
if( document.getElementById('dossier_ctd_no').checked==true) 
{ 
  document.getElementById('dossier_ctd_yes').checked =false;
  document.getElementById('dossier_ctd_yes').disabled=false;
  
  }
 });


     //Module Dossier
$('#module_one_yes').click(function () {
if( document.getElementById('module_one_yes').checked==true) 
{ document.getElementById('module_one_no').checked =false;
}
 });

 $('#module_one_no').click(function () {
if( document.getElementById('module_one_no').checked==true) 
{ document.getElementById('module_one_yes').checked =false}
 });



    //Module Dossier
$('#module_two_yes').click(function () {
if( document.getElementById('module_two_yes').checked==true) 
{ document.getElementById('module_two_no').checked =false;
}
 });

 $('#module_two_no').click(function () {
if( document.getElementById('module_two_no').checked==true) 
{ document.getElementById('module_two_yes').checked =false;
}
 });



//Module Dossier
$('#module_three_yes').click(function () {
if( document.getElementById('module_three_yes').checked==true) 
{ document.getElementById('module_three_no').checked =false}
 });

 $('#module_three_no').click(function () {
if( document.getElementById('module_three_no').checked==true) 
{ document.getElementById('module_three_yes').checked =false}
 });


   //Module Dossier
$('#module_four_yes').click(function () {
if( document.getElementById('module_four_yes').checked==true) 
{ document.getElementById('module_four_no').checked =false;
}
 });

 $('#module_four_no').click(function () {
if( document.getElementById('module_four_no').checked==true) 
{ document.getElementById('module_four_yes').checked =false}
 });

   //Module Dossier
   $('#module_five_yes').click(function () {
if( document.getElementById('module_five_yes').checked==true) 
{ document.getElementById('module_five_no').checked =false;
}
 });

 $('#module_five_no').click(function () {
if( document.getElementById('module_five_no').checked==true) 
{ document.getElementById('module_five_yes').checked =false}
 });





   //Module Dossier ctd formats
   $('#dossier_ctd_format_yes').click(function () {
if( document.getElementById('dossier_ctd_format_yes').checked==true) 
{ document.getElementById('dossier_ctd_format_no').checked =false;
}
 });

 $('#dossier_ctd_format_no').click(function () {
if( document.getElementById('dossier_ctd_format_no').checked==true) 
{ document.getElementById('dossier_ctd_format_yes').checked =false;
}
 });



  // Any other country yes
$('#any_other_country_yes').click(function () {
if( document.getElementById('any_other_country_yes').checked==true) 
{ document.getElementById('any_other_country_no').checked =false;
}
 });


    // Any other country yes
$('#any_other_country_no').click(function () {
if( document.getElementById('any_other_country_no').checked==true) 
{ document.getElementById('any_other_country_yes').checked =false;
}
 });

 $('#dossier_ctd_format_yes').click(function () {
if( document.getElementById('dossier_ctd_format_no').checked==true) 
{ document.getElementById('dossier_ctd_format_yes').checked =false}
 });



      //qis_prequalified_products
      $('#qis_prequalified_products_yes').click(function () {
if( document.getElementById('qis_prequalified_products_yes').checked==true) 
{ document.getElementById('qis_prequalified_products_no').checked =false;
}
 });

 $('#qis_prequalified_products_no').click(function () {
if( document.getElementById('qis_prequalified_products_no').checked==true) 
{ document.getElementById('qis_prequalified_products_yes').checked =false}
 });



       //qis_sra_crp
$('#qis_sra_crp_yes').click(function () {
if( document.getElementById('qis_sra_crp_yes').checked==true) 
{ document.getElementById('qis_sra_crp_no').checked =false;
}
 });

 $('#qis_sra_crp_no').click(function () {
if( document.getElementById('qis_sra_crp_no').checked==true) 
{ document.getElementById('qis_sra_crp_yes').checked =false}
 });


$('#Presence_of_full_assessment_report_yes').click(function () {
if( document.getElementById('Presence_of_full_assessment_report_yes').checked==true) 
{ document.getElementById('Presence_of_full_assessment_report_no').checked =false;
}
 });

 $('#Presence_of_full_assessment_report_no').click(function () {
if( document.getElementById('Presence_of_full_assessment_report_no').checked==true) 
{ document.getElementById('Presence_of_full_assessment_report_yes').checked =false}
 });




$('#Presence_of_the_full_inspection_reports_yes').click(function () {
if( document.getElementById('Presence_of_the_full_inspection_reports_yes').checked==true) 
{ document.getElementById('Presence_of_the_full_inspection_reports_no').checked =false;
}
 });

 $('#Presence_of_the_full_inspection_reports_no').click(function () {
if( document.getElementById('Presence_of_the_full_inspection_reports_no').checked==true) 
{ document.getElementById('Presence_of_the_full_inspection_reports_yes').checked =false}
 });

  $('#healthcare_professionals_smpc_yes').click(function () {
if( document.getElementById('healthcare_professionals_smpc_yes').checked==true) 
{ document.getElementById('healthcare_professionals_smpc_no').checked =false;
}
 });

 $('#healthcare_professionals_smpc_no').click(function () {
if( document.getElementById('healthcare_professionals_smpc_no').checked==true) 
{ document.getElementById('healthcare_professionals_smpc_yes').checked =false;}
 });


 $('#information_patient_user_yes').click(function () {
if( document.getElementById('information_patient_user_yes').checked==true) 
{ document.getElementById('information_patient_user_no').checked =false;
  document.getElementById('information_patient_user_not_applicable').checked=false;
}
 });

 $('#information_patient_user_no').click(function () {
if( document.getElementById('information_patient_user_no').checked==true) 
{ 
  document.getElementById('information_patient_user_yes').checked =false;
  document.getElementById('information_patient_user_not_applicable').checked=false;
}
 });




//information_patient_user_not_applicable
$('#information_patient_user_not_applicable').click(function () {
if( document.getElementById('information_patient_user_not_applicable').checked==true) 
{ document.getElementById('information_patient_user_yes').checked =false;
   document.getElementById('information_patient_user_no').checked =false;

}
 });


   $('#ifPresence_of_application_letter_yes').click(function () {
if( document.getElementById('ifPresence_of_application_letter_yes').checked==true) 
{ document.getElementById('ifPresence_of_application_letter_no').checked =false;
}
 });

 $('#ifPresence_of_application_letter_no').click(function () {
if( document.getElementById('ifPresence_of_application_letter_no').checked==true) 
{ document.getElementById('ifPresence_of_application_letter_yes').checked =false}
 });



   $('#public_assessment_inspection_yes').click(function () {
if( document.getElementById('public_assessment_inspection_yes').checked==true) 
{ document.getElementById('public_assessment_inspection_no').checked =false;
}
 });

 $('#public_assessment_inspection_no').click(function () {
if( document.getElementById('public_assessment_inspection_no').checked==true) 
{ document.getElementById('public_assessment_inspection_yes').checked =false;
}
 });

$('#Authority_Information_yes').click(function () {
if( document.getElementById('Authority_Information_yes').checked==true) 
{ document.getElementById('Authority_Information_no').checked =false;
  document.getElementById('Authority_Information_yes').disabled=false;
  }
 });

 $('#Authority_Information_no').click(function () {
if( document.getElementById('Authority_Information_no').checked==true) 
{ document.getElementById('Authority_Information_yes').checked =false;
  document.getElementById('Authority_Information_yes').disabled=false;}
 });


   $('#Generated_Invoice_Number_yes').click(function () {
if( document.getElementById('Generated_Invoice_Number_yes').checked==true) 
{ document.getElementById('Generated_Invoice_Number_no').checked =false;
  document.getElementById('Generated_Invoice_Number_yes').disabled=false;
  }
 });

 $('#Generated_Invoice_Number_no').click(function () {
if( document.getElementById('Generated_Invoice_Number_no').checked==true) 
  { 
  document.getElementById('Generated_Invoice_Number_yes').checked =false;
  document.getElementById('Generated_Invoice_Number_yes').disabled = false;
  }
 });




$('#checking_application_fee_yes').click(function () {
if( document.getElementById('checking_application_fee_yes').checked==true) 
{ 
  
  document.getElementById('checking_application_fee_no').checked =false;
  document.getElementById('checking_application_fee_yes').disabled = false;
  }
 });

 $('#checking_application_fee_no').click(function () {
if( document.getElementById('checking_application_fee_no').checked==true) 
{ document.getElementById('checking_application_fee_yes').checked =false;
  document.getElementById('checking_application_fee_yes').disabled = false;}
 });



$('#Application_Receipt_Number_yes').click(function () {
if( document.getElementById('Application_Receipt_Number_yes').checked==true) 
{      document.getElementById('Application_Receipt_Number_no').checked =false;
       document.getElementById('Application_Receipt_Number_yes').disabled =false;
  }
 });

 $('#Application_Receipt_Number_no').click(function () {
if( document.getElementById('Application_Receipt_Number_no').checked==true) 
{ document.getElementById('Application_Receipt_Number_yes').checked =false;
  document.getElementById('Application_Receipt_Number_yes').disabled =false;}
 });




   $('#required_bridging_yes').click(function () {
if( document.getElementById('required_bridging_yes').checked==true) 
{ document.getElementById('required_bridging_no').checked =false;
}
 });

 $('#required_bridging_no').click(function () {
if( document.getElementById('required_bridging_no').checked==true) 
{ document.getElementById('required_bridging_yes').checked =false}
 });



      $('#Product_Characteristics_yes').click(function () {
if( document.getElementById('Product_Characteristics_yes').checked==true) 
{ document.getElementById('Product_Characteristics_no').checked =false;
}
 });

 $('#Product_Characteristics_no').click(function () {
if( document.getElementById('Product_Characteristics_no').checked==true) 
{ document.getElementById('Product_Characteristics_yes').checked =false}
 });
 

   
   $('#sample_recieved_yes').click(function () {
if( document.getElementById('sample_recieved_yes').checked==true) 
{ document.getElementById('sample_received_no').checked =false;
}
 });

 $('#sample_received_no').click(function () {
if( document.getElementById('sample_received_no').checked==true) 
{ document.getElementById('sample_recieved_yes').checked =false;
}
 });
                    

$('#sample_scheduled_yes').click(function () {
if( document.getElementById('sample_scheduled_yes').checked==true) 
{ document.getElementById('sample_scheduled_no').checked = false;
  // document.getElementById('sample_scheduled_not_applicable').checked =false;
;
}
 });

 $('#sample_scheduled_no').click(function () {
if( document.getElementById('sample_scheduled_no').checked==true) 
{ document.getElementById('sample_scheduled_yes').checked =false;
  // document.getElementById('sample_scheduled_not_applicable').checked = false;
  }
 });

  $('#sample_scheduled_not_applicable').click(function () {
if( document.getElementById('sample_scheduled_not_applicable').checked = true) 
{ document.getElementById('sample_scheduled_yes').checked =false;
  document.getElementById('sample_scheduled_no').checked = false;}
 });
                    
$('#labelinfo_yes').click(function () {
if( document.getElementById('labelinfo_yes').checked==true) 
{ document.getElementById('labelinfo_no').checked =false;
}
 });

 $('#labelinfo_no').click(function () {
if( document.getElementById('labelinfo_no').checked==true) 
{ document.getElementById('labelinfo_yes').checked =false}
 });


$('#availability_packages_yes').click(function () {
if( document.getElementById('availability_packages_yes').checked==true) 
{ document.getElementById('availability_packages_no').checked =false;
}
 });

 $('#availability_packages_no').click(function () {
if( document.getElementById('availability_packages_no').checked==true) 
{ document.getElementById('availability_packages_yes').checked =false}
 });

                    

$('#manufacturing_premises_yes').click(function () {
if( document.getElementById('manufacturing_premises_yes').checked==true) 
{ document.getElementById('manufacturing_premises_no').checked =false;
}
 });

 $('#manufacturing_premises_no').click(function () {
if( document.getElementById('manufacturing_premises_no').checked==true) 
{ document.getElementById('manufacturing_premises_yes').checked =false;
}
 });



                    
  $('#sample_shelf_life_yes').click(function () {
if( document.getElementById('sample_shelf_life_yes').checked==true) 
{ document.getElementById('sample_shelf_life_no').checked =false;
  document.getElementById('sample_shelf_life_not_applicable').checked=false;
}
 });

 $('#sample_shelf_life_no').click(function () {
if( document.getElementById('sample_shelf_life_no').checked==true) 
{ 
  document.getElementById('sample_shelf_life_yes').checked =false;
  document.getElementById('sample_shelf_life_not_applicable').checked=false;

}
 });


  $('#sample_shelf_life_not_applicable').click(function () {
if( document.getElementById('sample_shelf_life_not_applicable').checked==true) 
{ 
  document.getElementById('sample_shelf_life_yes').checked =false;
  document.getElementById('sample_shelf_life_no').checked =false;
  

}
 });


    $('#availability_certificate_analysis_yes').click(function () {
if( document.getElementById('availability_certificate_analysis_yes').checked==true) 
{ document.getElementById('availability_certificate_analysis_no').checked =false;
}
 });

 $('#availability_certificate_analysis_no').click(function () {
if( document.getElementById('availability_certificate_analysis_no').checked==true) 
{ document.getElementById('availability_certificate_analysis_yes').checked =false}
 });


//   $('#sample_volume_yes').click(function () { 
// if( document.getElementById('sample_volume_yes').checked==true) 
// { document.getElementById('sample_volume_no').checked =false;
//   document.getElementById('sampling_net_weight').disabled = false;
// }
//  });


 
$('#save_processed_check_list').click(function () {

        });
});

</script>

<!-- <script src="{{ asset('dist/js/demo.js"></script> -->
<!-- Page specific script -->
<script>
  $(function () {
    // Summernote
    // $('#summernote').summernote();
    // $('#summernotee').summernote();
    // $('#summernote_Remark_section_four').summernote();
    // $('#Remark_section_five').summernote();
    // $('#over_all_comment').summernote();

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  })
</script>
