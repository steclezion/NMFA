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






$('#preliminary_screening').click(function () {
   
var application_id = document.getElementById('application_id').value;
var current_date =  document.getElementById('current_date').innerHTML;
var PS_squential_number  =  document.getElementById('PS_squential_number').innerHTML;

// alert(PS_squential_number);

var applicant_name =  document.getElementById('applicant_name').innerHTML;
var state_plot_number  =  document.getElementById('state_plot_number').innerHTML;
var country  =  document.getElementById('country').innerHTML;
var contact_person_name    =  document.getElementById('contact_person_name').innerHTML; 
var application_number =  document.getElementById('application_number').innerHTML;
var number_days_receipts =  document.getElementById('days_of_receipt').value;
// var stregnth  = document.getElementById('stregnth').value;
var product_name  =      document.getElementById('product_name').innerHTML;
var dosage_forms  =     document.getElementById('dosage_forms').innerHTML;
var product_trade_name  = document.getElementById('product_trade_name').innerHTML;
var application_number = document.getElementById('application_number').value;


// var remark =        document.getElementById('remark').value;
// var name_director =        document.getElementById('name_director').value;
// var director_info =        document.getElementById('director_info').value;
// if(stregnth  =='' ||   stregnth  <= 0){document.getElementById('stregnth').focus(); return false;}
// if(remark  =='' ||  remark <= 0){ alert("Remark Section is Empty!!");document.getElementById('remark').focus(); return false;}




if(number_days_receipts=='' ||   number_days_receipts <= 0){document.getElementById('days_of_receipt').focus(); return false;}

if (confirm("Are you sure you want to save this letter of issuing query."+
             "Actions will not be reverted.") == true) 
              {

document.getElementById('num_queries').innerHTML = number_days_receipts;
var query_template  = document.getElementById('template_redesigned').innerHTML;
document.getElementById('preliminary_screening').disabled=true;
document.getElementById('preliminary_screening').innerHTML='Saving...';


$.ajax({
      
      data:{ 
        application_id: application_id,
        current_date:current_date,
        PS_squential_number:PS_squential_number,
        applicant_name:applicant_name,
        state_plot_number:state_plot_number,
        country:country,
        contact_person_name:contact_person_name,
        number_days_receipts:number_days_receipts,
        application_number:application_number,
        product_name:product_name,
        dosage_forms:dosage_forms,
        product_trade_name:product_trade_name,
        application_number:application_number,
        query_template:query_template,

           },

     url: "{{ url('/save_preliminary_screening/save')   }} ",
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
                
$('#actions_to_assessor').show('100');
$('#preliminary_screening').hide('100');
$('#get_path').attr("href", data.Download_link);
$('#sequence_number').val(data.sequence_number);

Toastr();
toastr.success(" Query Successfully Saved!!")
             
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
               toastr.error("Query already produced")
               
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



});


</script>