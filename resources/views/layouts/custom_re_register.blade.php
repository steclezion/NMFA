<script>
function Toastr()
{
          toastr.options.closeButton = true;
          toastr.options.timeOut = 10000; // How long (in milisec) the toast will display without user interaction
          toastr.options.extendedTimeOut = 30000; // How long (in milisec) the toast will display after a user hovers over it
          toastr.options.progressBar = true;
}


function Toastr_validation()
{
          toastr.options.closeButton = true;
          toastr.options.timeOut = 7000; // How long (in milisec) the toast will display without user interaction
          toastr.options.extendedTimeOut = 3000; // How long (in milisec) the toast will display after a user hovers over it
          toastr.options.progressBar = true;
}



$('body').on('click', '.editmanufacturer_api', function () 
{

var _id = $(this).data("di");

document.getElementById('id_for_update_api').innerHTML = _id;
var updated = document.getElementById('product_manufacturer_api_update');
var saved = document.getElementById('product_manufacturer_api_save');

$.ajax({
          url: "{{ route('manufacturer_api_.retreive') }}",
          type: "POST",
          data:
          {
           id:_id,
          },
          success: function (data) {
       
            if( data.Message == true )
{ 
    

saved.style.display = "none";
updated.style.display = "block";
document.getElementById('manufacturer_api_name').focus();
document.getElementById('manufacturer_api_name').value=data.name;
document.getElementById('manufacturer_api_postal_code').value=data.postal_code;
document.getElementById('manufacturer_api_tele').value=data.telephone;
document.getElementById('manufacturer_api_city').value=data.city;
document.getElementById('manufacturer_api_state').value=data.state;
document.getElementById('manufacturer_api_add_line_one').value=data.addressline_one;
document.getElementById('manufacturer_api_add_line_two').value=data.addressline_two;
document.getElementById('manu_api_response_tele').innerHTML=data.country_code;
document.getElementById('manufacturer_api_country').value = data.country_id;
document.getElementById('manufacturer_api_name_api').value=data.api_name;
document.getElementById('manufacturer_api_unit').value=data.unit;
document.getElementById('manufacturer_api_block').value=data.block;


return false;




}

},

          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });

});





$('body').on('click', '.editmanufacturer', function () {

var _id = $(this).data("di");

document.getElementById('id_for_update').innerHTML = _id;
var updated = document.getElementById('product_manufacturer_update');
var saved = document.getElementById('product_manufacturer_save');

$.ajax({
          url: "{{ route('manufacturer.retreive') }}",
          type: "POST",
          data:
          {
           id:_id,
          },
          success: function (data) {
       
            if( data.Message == true )
            { 

saved.style.display = "none";
updated.style.display = "block";

document.getElementById('manufacturer_country').value = data.country_id;
//document.getElementById('manufacturer_country').selected = true;


document.getElementById('manufacturer_name').value=data.name;
document.getElementById('manufacturer_postal_code').value=data.postal_code;
document.getElementById('manufacturer_tele').value=data.telephone;
document.getElementById('manufacturer_city').value=data.city;
document.getElementById('manufacturer_state').value=data.state;
document.getElementById('manufacturer_add_line_one').value=data.addressline_one;
document.getElementById('manufacturer_add_line_two').value=data.addressline_two;
document.getElementById('manufacturer_activity').value=data.activity;
document.getElementById('manu_response_tele').innerHTML=data.country_code;
// document.getElementById('manufacturer_country').value = data.country_id;
//document.getElementById('manufacturer_country').selected=true;
document.getElementById('manufacturer_unit').value=data.unit;
document.getElementById('manufacturer_block').value=data.block;
document.getElementById('manufacturer_name').focus();


            }

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });

});


$('body').on('click', '.editcomposition', function () {

var _id = $(this).data("di");

$.ajax({
          url: "{{ route('composition.retreive') }}",
          type: "POST",
          data:
          {
           id:_id,
          },
          success: function (data) {
       
            if( data.Message == true )
            { 
                  //alert(data.product_composition);

                  document.getElementById('name_inn_text_composition').value=data.product_composition;
                  document.getElementById('quantity_composition').value=data.quantity;
                  document.getElementById('reason_inclusion_composition').value=data.reason;
                  document.getElementById('reference_standard_composition').value=data.reference_standard;
                  document.getElementById('composition_type_composition').value = data.type;
                  document.getElementById('createNewCompostion_save').style.display = "none";
                  document.getElementById('createNewCompostion_update').style.display = "block";
                  document.getElementById('composition_type_composition').focus();
                  document.getElementById('id_update_compostion').innerHTML = _id ;
            }

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });

});





// $('body').on('click', '.editmanufacturer', function () {

// var _id = $(this).data("di");

// $.ajax({
//           url: "{{ route('manufacturer.retreive') }}",
//           type: "POST",
//           data:
//           {
//            id:_id,
//           },
//           success: function (data) {
       
//             if( data.Message == true )
//             { 
//         //  alert(data.product_composition);
//                   document.getElementById('name_inn_text_composition').value=data.product_composition;
//                   document.getElementById('quantity_composition').value=data.quantity;
//                   document.getElementById('reason_inclusion_composition').value=data.reason;
//                   document.getElementById('reference_standard_composition').value=data.reference_standard;
//                   document.getElementById('composition_type_composition').value = data.type;
//                   document.getElementById('createNewCompostion_save').style.display = "none";
//                   document.getElementById('createNewCompostion_update').style.display = "block";
//                   document.getElementById('composition_type_composition').focus();
//                   document.getElementById('id_update_compostion').innerHTML = _id ;
//             }

//           },
//           error: function (data) {
//               console.log('Error:', data);
//               $('#saveBtn').html('Save Changes');
//           }
//       });

// });



function check_strength_password(password)
{

document.getElementById('confirm_password').value="";
            if( password != '')
            {
      
        $.ajax({
          data:{ password: password,},
          url: "{{   route('check_password')   }}",
          type: "GET",
          dataType: 'json',
          success: function (data) {
            if(data.Message == true)
            {
            
                var id = setInterval(delay_refresh_page, 6000);

function delay_refresh_page() {
               jQuery('#pass_response_email_warning').hide('100');
                jQuery('#submit_Save').show('100');
                jQuery('#pass_email_success').show('100');
clearInterval(id);

}

delay_refresh_page();
                document.getElementById('pass_email_success').innerHTML =  data.result ;

             
          }
          else if (data.Message == false)
          {
            

            var id = setInterval(delay_refresh_page, 6000);

function delay_refresh_page() {
    jQuery('#submit_Save').hide('100');
             jQuery('#pass_email_success').hide('100');
             jQuery('#pass_response_email_warning').show('100');
clearInterval(id);

}

             delay_refresh_page();

             document.getElementById('pass_response_email_warning').innerHTML =  data.result ;
          

         }
                             },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

        
      });
            }
            else

            {

            document.getElementById('pass_email_success').style.display = "none";
            document.getElementById('pass_response_email_warning').style.display = "none";
            }

}





function check_strength_password_confirm(password,confirm_password)

{
  
            if( confirm_password != '')
            {
      
        $.ajax({
          data:{ password: confirm_password,},
          url: "{{   route('check_password')   }}",
          type: "GET",
          dataType: 'json',
          success: function (data) {
            if(data.Message == true)
            {
           
             

var id = setInterval(delay_refresh_page, 6000);
function delay_refresh_page() {
                jQuery('#pass_response_email_warning').hide('100');
                jQuery('#submit_Save').show('100');
                jQuery('#pass_email_success').show('100');
                jQuery('#pass_response_email_danger').hide('100');
                document.getElementById('pass_email_success').innerHTML =  data.result ;
clearInterval(id);

}

             delay_refresh_page();



                  if(confirm_password != password)
                 {
 var id = setInterval(delay_refresh_page, 6000);

function delay_refresh_page() {
    jQuery('#pass_response_email_warning').hide('100');
                jQuery('#submit_Save').hide('100');
                jQuery('#pass_email_success').hide('100');
                jQuery('#pass_response_email_danger').show('100');
                document.getElementById('pass_response_email_danger').innerHTML =  "Password not match" ;
clearInterval(id);

}

delay_refresh_page() ;

                } 

             
          }
          else if (data.Message == false)
          {
            
            


             var id = setInterval(delay_refresh_page, 6000);

function delay_refresh_page() {
             jQuery('#submit_Save').hide('100');
             jQuery('#pass_email_success').hide('100');
             jQuery('#pass_response_email_warning').show('100');
             jQuery('#pass_response_email_danger').hide('100');
            document.getElementById('pass_response_email_warning').innerHTML =  data.result ;
clearInterval(id);

}

delay_refresh_page() ;
           

         }
                     
         
                     
                     
                     
                     
                             },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

        
      });
            }
            else

            {

            document.getElementById('pass_email_success').style.display = "none";
            document.getElementById('pass_response_email_warning').style.display = "none";
            }

}








function log_out()
                {

                  
         if (confirm("You are Logging out!") == true)
               { window.location="{{   route('logout')   }}" } 
              else { return false;}



                }



function   fetch_tele(tele,response_id,url) {
//  alert(tele);
if (tele >= 0) {
   jQuery(document).ready(function() {
      $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
              });

     jQuery.ajax({
            url: url,
            method: 'post',
            data: {
                tele: tele,
            },
            success: function(result) {
            // alert(result.Code);
  document.getElementById(response_id).innerHTML = result.Code;
  // jQuery('#'+response_id).val(result.Code);

                    }

           
        });

    });

} else {

    jQuery('#danger').hide('100');
    jQuery('#warning').hide();
    jQuery('#success').hide('100');
}

}


function get_other_value(value,response_id)
{

 if(value == 919)
 {
document.getElementById('international_name').style.display= "block";
document.getElementById('strength_unit').style.display= "block";

 }
 else {
document.getElementById('international_name').style.display= "none";
document.getElementById('strength_unit').style.display= "none";

 }

}

	 $('#print_dec').click(function () {
         var divName = 'print_decleration';
        
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
        // window.open(printContents);
         window.print();
         document.body.innerHTML = originalContents;
	    // window.location="{{ url('/invoice') }}";

          
});







function Delete_composition(id)
                {

           var product_composition_id =  id;
           var application_id = document.getElementById('generated_application_id').value;
              if (confirm("Are sure you want to delete this row ") == true) {} else { return false;}


                      $.ajax({
            
                        data:{ 
                          id:id,
                          application_id:application_id,
                              },
                        url: "{{   url('/product_composition/delete')   }}",
                        type: "DELETE",
                        dataType: 'json',
                        success: function (data) {
                     if(data.Message == true)  
                         {
             
              document.getElementById('renderd_product_composition_table').innerHTML = data.renderd_product_composition_table;
                  document.getElementById('name_inn_text_composition').value='';
                  document.getElementById('quantity_composition').value='';
                  document.getElementById('reason_inclusion_composition').value='';
                  document.getElementById('reference_standard_composition').value='' ;
                  document.getElementById('composition_type_composition').value ='';
                 
                  document.getElementById('product_trade_name').value;
                  document.getElementById('createNewCompostion_save').style.display = "block";
                  document.getElementById('createNewCompostion_update').style.display = "none";

      Toastr();
      toastr.error("Product constituent  removed successfully")
                         } 
else if(data.Message == 0){
document.getElementById('next_composition').style.display = 'none';
document.getElementById('name_inn_text_composition').value='';
document.getElementById('quantity_composition').value='';
document.getElementById('reason_inclusion_composition').value='';
document.getElementById('reference_standard_composition').value='' ;
document.getElementById('composition_type_composition').value ='';
document.getElementById('product_trade_name').value;
document.getElementById('createNewCompostion_save').style.display = "block";
document.getElementById('createNewCompostion_update').style.display = "none";
document.getElementById('renderd_product_composition_table').innerHTML = data.renderd_product_composition_table;
Toastr();
toastr.error("Product constituent removed successfully")

                         }
                           },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
 }


////////////////////////////////////////////////////////////////

  function Delete_manufacture_api(id)
  {
           var product_manufacturer_api_id =  id;
           var application_id = document.getElementById('generated_application_id').value;
var updated = document.getElementById('product_manufacturer_api_update');
var saved =   document.getElementById('product_manufacturer_api_save');
              if (confirm("Are sure you want to delete this row ") == true) {} else { return false;}

           $.ajax({
                     data:{ 
                          id:id,
                          application_id:application_id,
                              },
                        url: "{{   url('/product_manufacturer_api/delete')   }}",
                        type: "DELETE",
                        dataType: 'json',
                        success: function (data) {
                          
                     if(data.Message == true)  
                         {

document.getElementById('manufacturer_api_name').value="";
document.getElementById('manufacturer_api_country').value="";
document.getElementById('manufacturer_api_postal_code').value="";
document.getElementById('manufacturer_api_tele').value="";
document.getElementById('manu_api_response_tele').innerHTML="";
document.getElementById('manufacturer_api_country').value="";
document.getElementById('manufacturer_api_city').value="";
document.getElementById('manufacturer_api_state').value="";
document.getElementById('manufacturer_api_add_line_one').value="";
document.getElementById('manufacturer_api_add_line_two').value="";
document.getElementById('manufacturer_api_name_api').value="";
document.getElementById('manufacturer_api_unit').value="";
document.getElementById('manufacturer_api_block').value="";
updated.style.display = "none";
saved.style.display = "block";
id_for_update.style.display = "none";


       document.getElementById('renderd_manufacturer_api_table').innerHTML = data.renderd_manufacturer_api_table;
      
       Toastr();
      toastr.error("Product manufacturer api removed successfully from your application.")
                 


                         } 
                         else if(data.Message == false)  
                         {
           document.getElementById('renderd_manufacturer_api_table').innerHTML = "";
           document.getElementById('product_manufacturer_api_next_button').style.display='none';
                          }
                           },
                        error: function (data) {
                         
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });



  }
  //////////////////////////////////////////////////////

  function Delete_Manufacturer(id)
  {


          var product_manufacturer_id =  id;
           var application_id = document.getElementById('generated_application_id').value;
              if (confirm("Are sure you want to delete this row ") == true) {} else { return false;}

           $.ajax({
                     data:{ 
                          id:id,
                          application_id:application_id,
                              },
                        url: "{{   url('/product_manufacturer/delete')   }}",
                        type: "DELETE",
                        dataType: 'json',
                        success: function (data) {
                          
                     if(data.Message == true)  
                         {
           
document.getElementById('renderd_manufacturer_table').innerHTML = data.renderd_product_manufacturer_table;
Toastr();
toastr.error("Product manufacturer removed successfully  from your application.")


var updated = document.getElementById('product_manufacturer_update');
var saved = document.getElementById('product_manufacturer_save');
document.getElementById('manufacturer_block').value="";
document.getElementById('manufacturer_name').value="";
document.getElementById('manufacturer_country').value=0;
document.getElementById('manufacturer_postal_code').value="";
document.getElementById('manufacturer_tele').value="";
document.getElementById('manufacturer_city').value="";
document.getElementById('manufacturer_state').value="";
document.getElementById('manufacturer_add_line_one').value="";
document.getElementById('manufacturer_add_line_two').value="";
document.getElementById('manufacturer_activity').value="";
document.getElementById('manu_response_tele').innerHTML="";

updated.style.display = "none";
saved.style.display = "block";
return false;
                 
                         } 
                         else if(data.Message == false)  
                         {
var updated = document.getElementById('product_manufacturer_update');
var saved = document.getElementById('product_manufacturer_save');

document.getElementById('product_manufacturer_next_button').style.display = 'none';


document.getElementById('manufacturer_block').value="";
document.getElementById('manufacturer_name').value="";
document.getElementById('manufacturer_country').value=0;
document.getElementById('manufacturer_postal_code').value="";
document.getElementById('manufacturer_tele').value="";
document.getElementById('manufacturer_city').value="";
document.getElementById('manufacturer_state').value="";
document.getElementById('manufacturer_add_line_one').value="";
document.getElementById('manufacturer_add_line_two').value="";
document.getElementById('manufacturer_activity').value="";
document.getElementById('manu_response_tele').innerHTML="";
updated.style.display = "none";
saved.style.display = "block";
document.getElementById('renderd_manufacturer_table').innerHTML = "";
Toastr();
toastr.error("Product manufacturer removed  successfully from your application.")
return false;
                            

                         }
                           },
                        error: function (data) {
                         
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });



  }

              
 function  check_trade_name(trade_name)
        {
    document.getElementById('css_country').disabled=false;
    document.getElementById('cs_city').disabled=false;
    document.getElementById('cs_state').disabled=false;
    document.getElementById('cs_address_line_one').disabled=false;
    document.getElementById('cs_address_line_two').disabled = false;
    document.getElementById('cs_email').disabled = false;
    document.getElementById('cs_tele').disabled = false;
    document.getElementById('cs_website_url').disabled = false;
     
     // Set Element  to Disabled  
    // document.getElementById('css_country').value='';
    // document.getElementById('cs_city').value = '';
    // document.getElementById('cs_state').value = '';
    // document.getElementById('cs_address_line_one').value = '';
    // document.getElementById('cs_address_line_two').value = '';
    // document.getElementById('cs_email').value = '';
    // document.getElementById('cs_tele').value = '';
    // document.getElementById('cs_website_url').value = '';


if(trade_name != '')
{
if(trade_name == 'Other')
{

document.getElementById('cs_tradename_other').style.display = 'block';
document.getElementById('trade_names_other').value = '';

}
else{
  document.getElementById('cs_tradename_other').style.display = 'none';
  document.getElementById('trade_names_other').value = '';
}

        //$(this).html('Save');
        $.ajax({
          //data: $('#bookForm').serialize(),
          data:{ trade_name: trade_name,},
          url: "{{   route('company_supplier')   }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
                  //document.getElementById('css_country').value =  data.country_id ;
                 //document.getElementById('css_country').selected =  true ;
                //document.getElementById('css_country').disabled = true;
               // document.getElementById('cs_city').value=data.city;
             //document.getElementById('cs_city').disabled=true;
               if(trade_name == 'Other')
               { document.getElementById('cs_state').value='';
               }
               else
               {
                document.getElementById('cs_response_tele').innerHTML=data.International_dialing;
                document.getElementById('css_country').value =  data.country_id;
                document.getElementById('css_country').selected =  data.country_id;
               }

             //document.getElementById('cs_state').disabled=true;
             //document.getElementById('cs_address_line_one').value=data.address_line_one;
             // document.getElementById('cs_address_line_one').disabled=true;
             // document.getElementById('cs_address_line_two').value = data.address_line_two;
             //  document.getElementById('cs_address_line_two').disabled = true;
             // document.getElementById('cs_email').value = data.email;
             //  document.getElementById('cs_email').disabled = true;
             //  document.getElementById('postal_code').value = data.postal_code;
              //document.getElementById('postal_code').disabled = true;
             //document.getElementById('cs_response_tele').innerHTML = data.International_dialing;
             //document.getElementById('cont_response_tele').disabled = true;
            //document.getElementById('cs_tele').value = data.telephone ;
             // document.getElementById('cs_tele').disabled = true;
             //document.getElementById('cs_website_url').value =data.webiste_url;
             // document.getElementById('cs_website_url').disabled = true;

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

        
      });
            }
  }



         
         function  check_trade_agent_name(trade_name)
        {
    document.getElementById('ag_country').disabled=false;
    document.getElementById('ag_city').disabled=false;
    document.getElementById('ag_state').disabled=false;
    document.getElementById('ag_address_line_one').disabled=false;
    document.getElementById('ag_address_line_two').disabled = false;
    document.getElementById('ag_email').disabled = false;
    document.getElementById('ag_tele').disabled = false;
    document.getElementById('ag_website_url').disabled = false;
     
     // Set Element  to Disabled  
    document.getElementById('ag_country').value='';
    document.getElementById('ag_city').value = '';
    document.getElementById('ag_state').value = '';
    document.getElementById('ag_address_line_one').value = '';
    document.getElementById('ag_address_line_two').value = '';
    document.getElementById('ag_email').value = '';
    document.getElementById('ag_tele').value = '';
    document.getElementById('ag_website_url').value = '';






            if(trade_name != '')
            {
        //$(this).html('Save');
        $.ajax({
          //data: $('#bookForm').serialize(),
          data:{ trade_name: trade_name,},
          url: "{{   route('agent_info')   }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
                document.getElementById('ag_country').value =  data.country_id ;
                document.getElementById('ag_country').disabled = true;
                document.getElementById('ag_city').value=data.city;
                document.getElementById('ag_city').disabled=true;
                document.getElementById('ag_state').value=data.state;
                document.getElementById('ag_state').disabled=true;
                document.getElementById('ag_address_line_one').value=data.address_line_one;
                document.getElementById('ag_address_line_one').disabled=true;
                document.getElementById('ag_address_line_two').value = data.address_line_two;
                document.getElementById('ag_address_line_two').disabled = true;
                document.getElementById('ag_email').value = data.email;
                document.getElementById('ag_email').disabled = true;
                document.getElementById('ag_postal_code').value = data.postal_code;
                document.getElementById('ag_postal_code').disabled = true;
                document.getElementById('age_response_tele').innerHTML = data.International_dialing;
                //document.getElementById('age_reponse_tele').disabled = true;
                document.getElementById('ag_tele').value = data.telephone ;
                document.getElementById('ag_tele').disabled = true;
                document.getElementById('ag_website_url').value =data.webiste_url;
                document.getElementById('ag_website_url').disabled = true;

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

        
      });
            }
  }



  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    }); 
    

  $('#DHL').click(function () {

if(document.getElementById('LINK').checked == true){ document.getElementById('LINK').checked=false;}
document.getElementById('dossier_sample_id').style.display="block"; 
document.getElementById('express_settings_name').style.display = 'block';
document.getElementById('express_settings_name_label').style.display = 'block'; 
document.getElementById('sample').style.display="none";
document.getElementById('dossier_id').style.display="none";
document.getElementById('lable_dossier_link').style.display="none";
//document.getElementById('sample_status').style.display="none";
});




  $('#LINK').click(function () {

if(document.getElementById('DHL').checked == true){ document.getElementById('DHL').checked=false;} else if(document.getElementById('DHL').checked == false) { }
document.getElementById('lable_dossier_link').style.display="block";
document.getElementById('express_settings_name').style.display = 'none';
document.getElementById('express_settings_name_label').style.display = 'none'; 
document.getElementById('dossier_sample_id').style.display="block"; 
document.getElementById('sample').style.display="block";
document.getElementById('dossier_id').style.display="block";
//document.getElementById('sample_status').style.display="block";
  });



$('#confirm_finish').click(function () {

      var application_id = document.getElementById('generated_application_id').value;


      $.ajax({
          //data: $('#bookForm').serialize(),
          data:{ 
            application_id:application_id,
                },
         // url: "{{  route('generate_re_new_application')   }}",
         url: "{{  route('generate.application_number')   }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            if(data.Message==true)  
     {

 Toastr();
toastr.success("Application Number Generated Successfully "+ data.application_number)

  var id = setInterval(confirm, 1000);
              function confirm() {
              window.location = "/dossier_sample_status";
              clearInterval(id);
              }

     }


     else
     {
Toastr();
toastr.error("Application is unable to generate: Delete dossier create in the path dossier/"+ data.application_number);

     }

                                  },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

      });


    });





 $('#new_applicant').click(function () {
    if( document.getElementById('new_applicant').checked==true) 
    {var m = true;console.log(m);}
    $('#General_Supplier').show();
    $('#cs_tradename_exits').hide();
    $('#cs_save').show();
    $('#cs_trade_name').show();
   

    
    //Set Value to Null
   document.getElementById('css_country').disabled=false;
    document.getElementById('cs_city').disabled=false;
    document.getElementById('cs_state').disabled=false;
    document.getElementById('cs_address_line_one').disabled=false;
    document.getElementById('cs_address_line_two').disabled = false;
    document.getElementById('cs_email').disabled = false;
    document.getElementById('postal_code').disabled = false;
    document.getElementById('cont_response_tele').disabled= false;
    document.getElementById('cs_tele').disabled = false;
    document.getElementById('cs_website_url').disabled = false;
     
     // Set Element  to Disabled  
    document.getElementById('css_country').value='';
    document.getElementById('cs_city').value = '';
    document.getElementById('cs_state').value = '';
    document.getElementById('cs_address_line_one').value = '';
    document.getElementById('cs_address_line_two').value = '';
    document.getElementById('cs_email').value = '';
    document.getElementById('postal_code').value=''
    document.getElementById('cont_response_tele').innerHTML= '';
    document.getElementById('cs_tele').value = '';
    document.getElementById('cs_website_url').value = '';



});  
    
    $('#old_applicant').click(function () 
    {
    $('#General_Supplier').show();
    $('#application_wizard').show();
    $('#cs_tradename_exits').show();
    $('#cs_trade_name').hide();
    $('#cs_save').show();



    });
    

    $('#product_composition').click(function () 
    {
 var renderd_product_composition_table = document.getElementById("renderd_product_composition_table").innerHTML;
 var name_inn_text_composition = document.getElementById("name_inn_text_composition").value;
      
     // alert(renderd_product_composition_table.length );

      if( (renderd_product_composition_table.length  == 16) || (renderd_product_composition_table.length  == 0)    )
{

return false;
}
else
{
   document.getElementById('composition_pro').style.display="block";
   document.getElementById('composition_pro').style.opacity="2";
   document.getElementById('product_manufacturers_api_wizard').style.display="none";
   document.getElementById('product_manufacturers_wizard').style.display="none";
   document.getElementById('Agent_wizard').style.display="none";
   document.getElementById('supplier_wizard').style.display="none";
   document.getElementById('product_details_wizard').style.display="none";
   document.getElementById('type_application').style.display="none";
  //  document.getElementById('dossier_sample_wizard').style.display="none";
   document.getElementById('decleration_wizard').style.display="none";
document.getElementById('success_wizard').style.display = "none";

}
  });

 

  $('#product_manufacturers_api').click(function () 
    {

 var renderd_manufacturer_api_table = document.getElementById("renderd_manufacturer_api_table").innerHTML;
 
      //alert(renderd_manufacturer_api_table.length );

      if( (renderd_manufacturer_api_table.length  == 10) || (renderd_manufacturer_api_table.length  == 0)    )
{

return false;
}

else
{
      document.getElementById('product_manufacturers_api_wizard').style.display = "block";
      document.getElementById('product_manufacturers_wizard').style.display="none";
      document.getElementById('Agent_wizard').style.display="none";
      document.getElementById('product_manufacturers_api_wizard').style.opacity="2";
      document.getElementById('decleration_wizard').style.display="none";
     // document.getElementById('dossier_sample_wizard').style.display="none";
      document.getElementById('type_application').style.display="none";
      document.getElementById('product_details_wizard').style.display="none";
      document.getElementById('supplier_wizard').style.display="none";
      document.getElementById('product_manufacturers_wizard').style.display="none";
      document.getElementById('composition_pro').style.display="none";
      document.getElementById('success_wizard').style.display = "none";

}
  });


$('#product_manufacturers').click(function () 
    {

 var renderd_manufacturer_table = document.getElementById("renderd_manufacturer_table").innerHTML;
 var name_inn_text_composition = document.getElementById("name_inn_text_composition").value;
      
    //  alert(renderd_manufacturer_table.length );

      if( (renderd_manufacturer_table.length  == 10) || (renderd_manufacturer_table.length  == 0)    )
{

return false;
}

else
{
   document.getElementById('product_manufacturers_wizard').style.display="block";
   document.getElementById('product_manufacturers_wizard').style.opacity="2";

   document.getElementById('composition_pro').style.display="none";
   document.getElementById('product_manufacturers_api_wizard').style.display="none";
   document.getElementById('Agent_wizard').style.display="none";
   document.getElementById('supplier_wizard').style.display="none";
   document.getElementById('product_details_wizard').style.display="none";
   document.getElementById('type_application').style.display="none";
  //  document.getElementById('dossier_sample_wizard').style.display="none";
   document.getElementById('decleration_wizard').style.display="none";
document.getElementById('success_wizard').style.display = "none";

}
  });
 


$('#Agent').click(function () 
    {
        var cont_ag_first_name = document.getElementById("cont_ag_first_name").value;
      
      if(cont_ag_first_name  != '')
{
   document.getElementById('Agent_wizard').style.display="block";
   document.getElementById('Agent_wizard').style.opacity="2";
   document.getElementById('product_manufacturers_wizard').style.display="none";
   document.getElementById('composition_pro').style.display="none";
   document.getElementById('product_manufacturers_api_wizard').style.display="none";
   document.getElementById('supplier_wizard').style.display="none";
   document.getElementById('product_details_wizard').style.display="none";
   document.getElementById('type_application').style.display="none";
  //document.getElementById('dossier_sample_wizard').style.display="none";
   document.getElementById('decleration_wizard').style.display="none";
document.getElementById('success_wizard').style.display = "none";
}

  });



  $('#supplier').click(function () 
    {
      document.getElementById('supplier_wizard').style.display="block";
      document.getElementById('supplier_wizard').style.opacity="2";
      document.getElementById('Agent_wizard').style.display="none";
      document.getElementById('product_manufacturers_wizard').style.display="none";
      document.getElementById('composition_pro').style.display="none";
      document.getElementById('product_manufacturers_api_wizard').style.display = "none";
      document.getElementById('product_details_wizard').style.display="none";
      document.getElementById('type_application').style.display="none";
      //  document.getElementById('dossier_sample_wizard').style.display="none";
      document.getElementById('decleration_wizard').style.display="none";
   document.getElementById('success_wizard').style.display = "none";
  });



  $('#product_details').click(function () 
    {   
        
        var dosage_form_id= document.getElementById("dosage_form_id").value;
      
      if( dosage_form_id  != '')
{

        document.getElementById('product_details_wizard').style.display="block";
      document.getElementById('product_details_wizard').style.opacity="2";
      document.getElementById('supplier_wizard').style.display="none";
      document.getElementById('Agent_wizard').style.display="none";
      document.getElementById('product_manufacturers_wizard').style.display="none";
       document.getElementById('composition_pro').style.display="none";
        document.getElementById('product_manufacturers_api_wizard').style.display = "none";
        document.getElementById('type_application').style.display="none";
  //  document.getElementById('dossier_sample_wizard').style.display="none";
   document.getElementById('decleration_wizard').style.display="none";
document.getElementById('success_wizard').style.display = "none";

}


  });


  $('#Application_Type').click(function () 
    {

        var x = document.getElementsByClassName("app_recep_next_companyinfo");
        //alert(x[0].innerHTML);
       

      document.getElementById('type_application').style.display="block";
      document.getElementById('type_application').style.opacity="2";
      document.getElementById('product_details_wizard').style.display="none";
      document.getElementById('supplier_wizard').style.display="none";
      document.getElementById('Agent_wizard').style.display="none";
      document.getElementById('product_manufacturers_wizard').style.display="none";
      document.getElementById('composition_pro').style.display="none";
      document.getElementById('product_manufacturers_api_wizard').style.display = "none";
      // document.getElementById('dossier_sample_wizard').style.display="none";
      document.getElementById('decleration_wizard').style.display="none";
   document.getElementById('success_wizard').style.display = "none";
  });



 $('#dossier_sample').click(function () 
    {
      
      // document.getElementById('dossier_sample_wizard').style.display="block";
      document.getElementById('dossier_sample_wizard').style.display="none";
      document.getElementById('dossier_sample_wizard').style.opacity="2";

      document.getElementById('product_details_wizard').style.display="none";
      document.getElementById('supplier_wizard').style.display="none";
      document.getElementById('Agent_wizard').style.display="none";
      document.getElementById('product_manufacturers_wizard').style.display="none";
      document.getElementById('composition_pro').style.display="none";
      document.getElementById('product_manufacturers_api_wizard').style.display = "none";
      document.getElementById('decleration_wizard').style.display="none";
   document.getElementById('success_wizard').style.display = "none";

  });



 $('#decleration').click(function () 
    {

                var decleration_name = document.getElementById("decleration_name").value;
      
      if( decleration_name  != '')
{


      document.getElementById('decleration_wizard').style.display="block";
          document.getElementById('decleration_wizard').style.opacity="2";
      // document.getElementById('dossier_sample_wizard').style.display="none";
      document.getElementById('type_application').style.display="none";
      document.getElementById('product_details_wizard').style.display="none";
      document.getElementById('supplier_wizard').style.display="none";
      document.getElementById('Agent_wizard').style.display="none";
      document.getElementById('product_manufacturers_wizard').style.display="none";
      document.getElementById('composition_pro').style.display="none";
      document.getElementById('product_manufacturers_api_wizard').style.display = "none";
   document.getElementById('success_wizard').style.display = "none";

}
    });






$('#save_agent_info').click(function () {
     //From New Supplier
    var ag_trade_name =  document.getElementById('ag_trade_name').value;
    var ag_country =     document.getElementById('ag_country').value ;
    var ag_postal_code = document.getElementById('ag_postal_code').value;
    var ag_city = document.getElementById('ag_city').value ;
    var ag_state = document.getElementById('ag_state').value ;
    var ag_address_line_one =  document.getElementById('ag_address_line_one').value ;
    var ag_address_line_two = document.getElementById('ag_address_line_two').value ;
    var ag_email = document.getElementById('ag_email').value ;
    var ag_tele = document.getElementById('ag_tele').value ;
    var ag_website_url = document.getElementById('ag_website_url').value ;
  
    //Contact Supplier Full Information
    var cont_ag_first_name  = document.getElementById('cont_ag_first_name').value ;
    var cont_ag_middle_name = document.getElementById('cont_ag_middle_name').value ;
    var cont_ag_last_name  = document.getElementById('cont_ag_last_name').value ;
    var cont_ag_country_id  = document.getElementById('cont_ag_country_id').value ;
    var cont_ag_city  = document.getElementById('cont_ag_city').value ;
    var cont_ag_address_line_one = document.getElementById('cont_ag_address_line_one').value ;
    var cont_ag_address_line_two = document.getElementById('cont_ag_address_line_two').value ;
    var cont_ag_email = document.getElementById('cont_ag_email').value ;
    var cont_ag_tele = document.getElementById('cont_ag_tele').value ;
    var cont_fax = document.getElementById('cont_ag_fax').value ;
    //var cont_ag_webiste_url = document.getElementById('cont_ag_webiste_url').value ;
    var cont_ag_position = document.getElementById('cont_ag_position').value ;
    var application_id = document.getElementById('generated_application_id').value;
    var user_id = document.getElementById('user_id').value ;

if(ag_trade_name == ''){$('#ag_trade_name').css("background-color", "#e6c1c7"); document.getElementById('ag_trade_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_postal_code == ''){$('#ag_postal_code').css("background-color", "#e6c1c7"); document.getElementById('ag_postal_code').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_city == ''){$('#ag_city').css("background-color", "#e6c1c7"); document.getElementById('ag_city').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(ag_state == ''){$('#ag_state').css("background-color", "#e6c1c7"); document.getElementById('ag_state').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_address_line_one == ''){$('#ag_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('ag_address_line_one').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(ag_address_line_two == ''){$('#ag_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('ag_address_line_two').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_email == ''){$('#ag_email').css("background-color", "#e6c1c7"); document.getElementById('ag_email').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_tele == ''){$('#ag_tele').css("background-color", "#e6c1c7"); document.getElementById('ag_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

//if(ag_website_url == ''){$('#ag_website_url').css("background-color", "#e6c1c7"); document.getElementById('ag_website_url').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}



if(cont_ag_first_name == ''){$('#cont_ag_first_name').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_first_name').focus(); /*Toastr_validation();toastr.error("Fill Contact's First name"); */return false;}
if(cont_ag_middle_name == ''){ /*$('#cont_ag_middle_name').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_middle_name').focus(); /*Toastr_validation();toastr.error("Fill Contact's Middle name"); return false; */}
if(cont_ag_last_name == ''){$('#cont_ag_last_name').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_last_name').focus(); /*Toastr_validation();toastr.error("Fill Contact's Last name"); */ return false;}
if(cont_ag_position == ''){$('#cont_ag_position').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_position').focus(); /*Toastr_validation();toastr.error("Fill Contact's Position"); */  return false;}
if(cont_ag_country_id == '' || cont_ag_country_id==0){$('#cont_ag_country_id').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_country_id').focus(); /*Toastr_validation();toastr.error("Fill Contact's Country"); */ return false;}
if(cont_ag_city == ''){$('#cont_ag_city').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_city').focus(); /*Toastr_validation();toastr.error("Fill Contact's City");*/  return false;}
if(cont_ag_address_line_one == ''){$('#cont_ag_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_address_line_one').focus(); /*Toastr_validation();toastr.error("Fill Contact's Address line two"); */ return false;}
if(cont_ag_address_line_two == ''){ /*$('#cont_ag_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_address_line_two').focus(); /*Toastr_validation();toastr.error("Fill Contact's Address line two");* return false; */}
if(cont_ag_email == ''){$('#cont_ag_email').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_email').focus(); /*Toastr_validation();toastr.error("Fill Contact's Email"); */  return false;}
if(cont_ag_tele  == ''){$('#cont_ag_tele').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_tele').focus(); /*Toastr_validation();toastr.error("Fill Contact's Telephone");*/  return false;}


 $.ajax({
          //data: $('#bookForm').serialize(),
          data:{ 
              trade_name: ag_trade_name,
              country_id: ag_country,
              city: ag_city,
              state: ag_state,
              address_line_one: ag_address_line_one,
              address_line_two: ag_address_line_two,
              country_code:+291,
              telephone:ag_tele,
              email:ag_email,
              webiste_url:ag_website_url,
              postal_code:12,
              user_id: user_id,
              application_id:application_id,

              //From Supplier Information
              first_name:cont_ag_first_name,
              middle_name:cont_ag_middle_name,
              last_name:cont_ag_last_name,
              country_id_contact:cont_ag_country_id,
              position:cont_ag_position ,
              city_contact:cont_ag_city,
              address_line_one_contact:cont_ag_address_line_one,
              address_line_two_contact:cont_ag_address_line_two,
              postal_code:12,
              telephone_contact:cont_ag_tele,
              fax:cont_fax,
             // webiste_url_contact:cont_ag_webiste_url,
              email_contact:cont_ag_email,
              contact_type:'Agent',
              
              },
          url: "{{  url('/agent_save')   }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {

           if(data.AgentInfo==true)  
           {

            $('#save_agent_info').hide();
            $('#update_agent_info').show();
            document.getElementById('agent_next_button').style.display = "block";
            
document.getElementById('agent_contact_id').value =data.Contact_ID;
document.getElementById('agent_id').value =data.Agent_ID;
     

Toastr();

toastr.success("New local agent and new contact person have been added")

           } 
           else
           {
 Toastr();
 toastr.error(data.AgentInfo.errorInfo[2])
}
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }

        
      });


    });






 $('#update_agent_info').click(function () {
     
     //From New Supplier

var ag_trade_name =  document.getElementById('ag_trade_name').value;
var ag_country =     document.getElementById('ag_country').value ;
var ag_postal_code = document.getElementById('ag_postal_code').value;
var ag_city = document.getElementById('ag_city').value ;
var ag_state = document.getElementById('ag_state').value ;
var ag_address_line_one =  document.getElementById('ag_address_line_one').value ;
var ag_address_line_two = document.getElementById('ag_address_line_two').value ;
var ag_email = document.getElementById('ag_email').value ;
var ag_tele = document.getElementById('ag_tele').value ;
var ag_website_url = document.getElementById('ag_website_url').value ;
var age_tele_code = document.getElementById('age_response_tele').innerHTML ;


  //Contact Supplier Full Information
var cont_ag_first_name  = document.getElementById('cont_ag_first_name').value ;
var cont_ag_middle_name = document.getElementById('cont_ag_middle_name').value ;
var cont_ag_last_name  = document.getElementById('cont_ag_last_name').value ;
var cont_ag_country_id  = document.getElementById('cont_ag_country_id').value ;
var cont_ag_city  = document.getElementById('cont_ag_city').value ;
var cont_ag_address_line_one = document.getElementById('cont_ag_address_line_one').value ;
var cont_ag_address_line_two = document.getElementById('cont_ag_address_line_two').value ;
var cont_ag_email = document.getElementById('cont_ag_email').value ;
var cont_ag_tele = document.getElementById('cont_ag_tele').value ;
//var cont_ag_webiste_url = document.getElementById('cont_ag_webiste_url').value ;
var cont_ag_position = document.getElementById('cont_ag_position').value ;
var application_id = document.getElementById('generated_application_id').value;

 var cont_fax = document.getElementById('cont_ag_fax').value ;

var contact_id =  document.getElementById('agent_contact_id').value ;
var agent_id=  document.getElementById('agent_id').value ;

//ag_tele = ag_tele+age_tele_code;
var cont_age_response_tele  = document.getElementById('cont_age_response_tele').innerHTML ;


if(ag_trade_name == ''){$('#ag_trade_name').css("background-color", "#e6c1c7"); document.getElementById('ag_trade_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_postal_code == ''){$('#ag_postal_code').css("background-color", "#e6c1c7"); document.getElementById('ag_postal_code').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_city == ''){$('#ag_city').css("background-color", "#e6c1c7"); document.getElementById('ag_city').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(ag_state == ''){$('#ag_state').css("background-color", "#e6c1c7"); document.getElementById('ag_state').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_address_line_one == ''){$('#ag_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('ag_address_line_one').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(ag_address_line_two == ''){$('#ag_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('ag_address_line_two').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_email == ''){$('#ag_email').css("background-color", "#e6c1c7"); document.getElementById('ag_email').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(ag_tele == ''){$('#ag_tele').css("background-color", "#e6c1c7"); document.getElementById('ag_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

//if(ag_website_url == ''){$('#ag_website_url').css("background-color", "#e6c1c7"); document.getElementById('ag_website_url').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}





if(cont_ag_first_name == ''){$('#cont_ag_first_name').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_first_name').focus(); /*Toastr_validation();toastr.error("Fill Contact's First name"); */return false;}
if(cont_ag_middle_name == ''){ /*$('#cont_ag_middle_name').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_middle_name').focus(); /*Toastr_validation();toastr.error("Fill Contact's Middle name"); return false; */}
if(cont_ag_last_name == ''){$('#cont_ag_last_name').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_last_name').focus(); /*Toastr_validation();toastr.error("Fill Contact's Last name"); */ return false;}
if(cont_ag_position == ''){$('#cont_ag_position').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_position').focus(); /*Toastr_validation();toastr.error("Fill Contact's Position"); */  return false;}
if(cont_ag_country_id == '' || cont_ag_country_id==0){$('#cont_ag_country_id').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_country_id').focus(); /*Toastr_validation();toastr.error("Fill Contact's Country"); */ return false;}
if(cont_ag_city == ''){$('#cont_ag_city').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_city').focus(); /*Toastr_validation();toastr.error("Fill Contact's City");*/  return false;}
if(cont_ag_address_line_one == ''){$('#cont_ag_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_address_line_one').focus(); /*Toastr_validation();toastr.error("Fill Contact's Address line two"); */ return false;}
if(cont_ag_address_line_two == ''){ /*$('#cont_ag_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_address_line_two').focus(); /*Toastr_validation();toastr.error("Fill Contact's Address line two");* return false; */}
if(cont_ag_email == ''){$('#cont_ag_email').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_email').focus(); /*Toastr_validation();toastr.error("Fill Contact's Email"); */  return false;}
if(cont_ag_tele  == ''){$('#cont_ag_tele').css("background-color", "#e6c1c7"); document.getElementById('cont_ag_tele').focus(); /*Toastr_validation();toastr.error("Fill Contact's Telephone");*/  return false;}



cont_ag_tele = cont_ag_tele+cont_age_response_tele;

$.ajax({
    //data: $('#bookForm').serialize(),
    data:{ 
        trade_name: ag_trade_name,
        country_id: ag_country,
        city: ag_city,
        state: ag_state,
        address_line_one: ag_address_line_one,
        address_line_two: ag_address_line_two,
        country_code:age_tele_code ,
        telephone:ag_tele,
        email:ag_email,
        webiste_url:ag_website_url,
        postal_code:12,
        contact_id:contact_id,
        application_id:application_id,

        //From  Contact Information
        first_name:cont_ag_first_name,
        middle_name:cont_ag_middle_name,
        last_name:cont_ag_last_name,
        country_id_contact:cont_ag_country_id,
        position:cont_ag_position ,
        city_contact:cont_ag_city,
        address_line_one_contact:cont_ag_address_line_one,
        address_line_two_contact:cont_ag_address_line_two,
        postal_code:12,
        telephone_contact:cont_ag_tele,
        fax:cont_fax,
        //webiste_url_contact:cont_ag_webiste_url,
        email_contact:cont_ag_email,
        contact_type:'Agent',
        agent_id:agent_id,
        
        },
    url: "{{  url('/agent_update')   }}",
    type: "POST",
    dataType: 'json',
    success: function (data) {

     if(data.message==true)  
     {

      $('#update_agent_info').hide();
      $('#update_agent_info').show();

      document.getElementById('agent_next_button').disabled = false;

Toastr();
toastr.success(data.AgentupdateInfo)

     } 

       else if(data.message == false)  
     {

      $('#ag_save').hide();
      $('#ag_update').show();
      document.getElementById('agent_next_button').disabled = false;

Toastr();
toastr.info(data.AgentupdateInfo)

     } 
     else
     {

Toastr();
toastr.error(data.AgentInfo.errorInfo[2])
}
    },
    error: function (data) {
        console.log('Error:', data);
        $('#saveBtn').html('Save Changes');
    }

  
});
});







$("#grad1").click(function(){
$('#trade_names_other').css("background-color", "#ffffff")

$('#trade_names').css("background-color", "#ffffff");
$('#ag_trade_name').css("background-color", "#ffffff");
$('#ag_postal_code').css("background-color", "#ffffff");
$('#ag_city').css("background-color", "#ffffff");
$('#ag_state').css("background-color", "#ffffff");
$('#ag_address_line_one').css("background-color", "#ffffff");
$('#ag_address_line_two').css("background-color", "#ffffff");
$('#ag_email').css("background-color", "#ffffff");
$('#ag_tele').css("background-color", "#ffffff");
$('#ag_website_url').css("background-color", "#ffffff");

$('#css_country').css("background-color", "#ffffff");
$('#cs_city').css("background-color", "#ffffff");
$('#cs_State').css("background-color", "#ffffff");
$('#cs_address_line_one').css("background-color", "#ffffff");
$('#cs_address_line_two').css("background-color", "#ffffff"); 
$('#cs_tele').css("background-color", "#ffffff");
$('#cs_email').css("background-color", "#ffffff");
$('#cs_website_url').css("background-color", "#ffffff");
$('#postal_code').css("background-color", "#ffffff");
$('#cont_first_name').css("background-color", "#ffffff");
$('#cont_middle_name').css("background-color", "#ffffff");
$('#cont_last_name').css("background-color", "#ffffff");
$('#cs_country').css("background-color", "#ffffff");
$('#cont_position').css("background-color", "#ffffff");
$('#cont_city').css("background-color", "#ffffff");
$('#cont_address_line_one').css("background-color", "#ffffff");
$('#cont_address_line_two').css("background-color", "#ffffff");
$('#cont_email').css("background-color", "#ffffff");
$('#cont_tele').css("background-color", "#ffffff");


$('#ag_trade_name').css("background-color", "#ffffff");
$('#cont_ag_first_name').css("background-color", "#ffffff");
$('#cont_ag_middle_name').css("background-color", "#ffffff");
$('#cont_ag_last_name ').css("background-color", "#ffffff")
$('#cont_ag_position').css("background-color", "#ffffff");
$('#cont_ag_country_id').css("background-color", "#ffffff");
$('#cont_ag_city').css("background-color", "#ffffff");
$('#cont_ag_address_line_one').css("background-color", "#ffffff");
$('#cont_ag_address_line_two').css("background-color", "#ffffff")
$('#cont_ag_email').css("background-color", "#ffffff");
$('#cont_ag_tele').css("background-color", "#ffffff");


$('#generic_approved_name').css("background-color", "#ffffff");
$('#generic_approved_name_other').css("background-color", "#ffffff");
$('#product_trade_name').css("background-color", "#ffffff");
$('#dosage_form_id').css("background-color", "#ffffff");
$('#route_administration_id').css("background-color", "#ffffff");
$('#description').css("background-color", "#ffffff");
$('#strength_unit_amount').css("background-color", "#ffffff");
$('#shelf_life_amount').css("background-color", "#ffffff");
$('#pharmaco_therapeutic_classification').css("background-color", "#ffffff");
$('#proposed_shelf_life_amount').css("background-color", "#ffffff");
$('#proposed_shelf_life_unit').css("background-color", "#ffffff");
$('#proposed_shelf_life_after_reconstitution_amount').css("background-color", "#ffffff");
$('#proposed_shelf_life_after_reconstitution_unit').css("background-color", "#ffffff");
$('#visual_description').css("background-color", "#ffffff");
$('#container').css("background-color", "#ffffff");
$('#packaging').css("background-color", "#ffffff");
$('#category_use').css("background-color", "#ffffff");
$('#storage_condition').css("background-color", "#ffffff");
$('#shelf_life_unit').css("background-color", "#ffffff");


$('#name_inn_text_composition').css("background-color", "#ffffff");
$('#quantity_composition').css("background-color", "#ffffff");
$('#reason_inclusion_composition').css("background-color", "#ffffff");
$('#reference_standard_composition').css("background-color", "#ffffff");
$('#composition_type_composition').css("background-color", "#ffffff");


$('#manufacturer_name').css("background-color", "#ffffff");
$('#manufacturer_postal_code').css("background-color", "#ffffff");
$('#manufacturer_country').css("background-color", "#ffffff");
$('#manufacturer_tele').css("background-color", "#ffffff");
$('#manufacturer_unit').css("background-color", "#ffffff");
$('#manufacturer_state').css("background-color", "#ffffff");
$('#manufacturer_add_line_one').css("background-color", "#ffffff");
$('#manufacturer_add_line_two').css("background-color", "#ffffff");
$('#manufacturer_activity').css("background-color", "#ffffff");
$('#manufacturer_block').css("background-color", "#ffffff");


$('#manufacturer_api_name').css("background-color", "#ffffff");
$('#manufacturer_api_postal_code').css("background-color", "#ffffff");
$('#manufacturer_api_tele').css("background-color", "#ffffff");
$('#manufacturer_api_country').css("background-color", "#ffffff");
$('#manufacturer_api_state').css("background-color", "#ffffff");
$('#manufacturer_api_add_line_one').css("background-color", "#ffffff");
$('#manufacturer_api_add_line_two').css("background-color", "#ffffff");
$('#manufacturer_api_city').css("background-color", "#ffffff");


});




   $('#save_supplier_info').click(function () {   

if( document.getElementById('new_applicant').checked == true  ) {    var trade_name  = document.getElementById('cs_trade_name').value;     }
else if( document.getElementById('old_applicant').checked == true) { var trade_name  = document.getElementById('trade_names').value;       }



  if( document.getElementById('new_applicant').checked == true  ) 
  {               

  //From New Supplier
  trade_name=trade_name;
  var trade_name_other  = document.getElementById('cs_trade_name').value; 
  var country = document.getElementById('css_country').value;
  var city =  document.getElementById('cs_city').value;
  var state =document.getElementById('cs_state').value ;
  var address_line_one = document.getElementById('cs_address_line_one').value;
  var address_line_two= document.getElementById('cs_address_line_two').value ;
  var email = document.getElementById('cs_email').value ;
  var tele =  document.getElementById('cs_tele').value ;
  var url = document.getElementById('cs_website_url').value ;
  var website_url = document.getElementById('cs_website_url').value ;
  //var institutional_email = document.getElementById('cs_institutional_email').value ;
  var postal_code = document.getElementById('postal_code').value
  var tele_code_supplier = document.getElementById('cs_response_tele').innerHTML ;
  //tele =  tele_code_supplier+tele;
     
  //Contact Supplier Full Information
  var contact_supplier_firstname  = document.getElementById('cont_first_name').value ;
  var contact_supplier_middlename = document.getElementById('cont_middle_name').value ;
  var contact_supplier_lastname  =  document.getElementById('cont_last_name').value ;
  var contact_supplier_country  =   document.getElementById('cont_country').value ;
  var contact_supplier_city  =      document.getElementById('cont_city').value ;
  var contact_supplier_address_line_one = document.getElementById('cont_address_line_one').value ;
  var contact_supplier_address_line_two = document.getElementById('cont_address_line_two').value ;
  var contact_supplier_tele =             document.getElementById('cont_tele').value ;
  var contact_supplier_email =            document.getElementById('cont_email').value ;
  //var contact_supplier_website_url =      document.getElementById('cont_webiste_url').value ;
  var contact_supplier_position =         document.getElementById('cont_position').value ;
  var application_id = document.getElementById('generated_application_id').value;
  
  var contact_Fax = document.getElementById('cont_fax').value;
 // var contact_supplier_postal_code = document.getElementById('postal_code').value
 var user_id = document.getElementById('user_id').value ;
 var tele_code_contact = document.getElementById('cont_response_tele').innerHTML ;
 //contact_supplier_tele = tele_code_contact + contact_supplier_tele;//+;





   


  $.ajax({
        //data: $('#bookForm').serialize(),
        data:{ 
            trade_name: trade_name,
            trade_name_other:trade_name_other,
            country_id: country,
            city:city,
            state:state,
            address_line_one:address_line_one,
            address_line_two:address_line_two,
            country_code:tele_code_supplier,
            telephone:tele,
            email:email,
            webiste_url:website_url,
            user_id:user_id,
            application_id:application_id,
            postal_code:postal_code,
           // institutional_email:institutional_email,
            

            //From Supplier Information
            first_name:contact_supplier_firstname,
            middle_name:contact_supplier_middlename,
            last_name:contact_supplier_lastname,
            country_id_contact:contact_supplier_country,
            position:contact_supplier_position,
            city_contact:contact_supplier_city,
            address_line_one_contact:contact_supplier_address_line_one,
            address_line_two_contact:contact_supplier_address_line_two,
            telephone_contact:contact_supplier_tele,
            contact_Fax :contact_Fax,
            //webiste_url_contact:contact_supplier_website_url,
            email_contact:contact_supplier_email,
            contact_type:'Supplier',

            
            },
        url: "{{   route('company_supplier_save')   }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {

         if(data.supplyInfo==true)  
         {

document.getElementById('next_application').style.dispaly = "block";
document.getElementById('contact_id').value = data.Contact_ID;
document.getElementById('supplier_id').value = data.Supplier_ID;       

$('#save_supplier_info').hide();
$('#update_supplier_info').show();




Toastr();
toastr.success("Applicant and contact person information saved successfully")

         } 
         else
         {
          Toastr();
toastr.error(data.supplyInfo.errorInfo[2])
}
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }

      
    });
  }

  else if( document.getElementById('old_applicant').checked == true) 
  { 
      
   trade_name=trade_name;
  var trade_name_other = document.getElementById('trade_names_other').value;
  var trade_names = document.getElementById('trade_names').value;
  
  var country = document.getElementById('css_country').value;

  var city =  document.getElementById('cs_city').value;
  var state =document.getElementById('cs_state').value ;
  var address_line_one = document.getElementById('cs_address_line_one').value;
  var address_line_two= document.getElementById('cs_address_line_two').value ;
  var email = document.getElementById('cs_email').value ;
  var tele =  document.getElementById('cs_tele').value ;
  var url = document.getElementById('cs_website_url').value ;
  var website_url = document.getElementById('cs_website_url').value ;
  //var institutional_email = document.getElementById('cs_institutional_email').value ;
  var postal_code = document.getElementById('postal_code').value

  var tele_code_supplier = document.getElementById('cs_response_tele').innerHTML ;

   //tele =  tele_code_supplier+tele;
     
      //Contact Supplier Full Information
  var contact_supplier_firstname  = document.getElementById('cont_first_name').value ;
  var contact_supplier_middlename = document.getElementById('cont_middle_name').value ;
  var contact_supplier_lastname  =  document.getElementById('cont_last_name').value ;
  var contact_supplier_country  =   document.getElementById('cont_country').value ;
  var contact_supplier_city  =      document.getElementById('cont_city').value ;
  var contact_supplier_address_line_one = document.getElementById('cont_address_line_one').value ;
  var contact_supplier_address_line_two = document.getElementById('cont_address_line_two').value ;
  var contact_supplier_tele =             document.getElementById('cont_tele').value ;
  var contact_supplier_email =            document.getElementById('cont_email').value ;
  //var contact_supplier_website_url =      document.getElementById('cont_webiste_url').value ;
  var contact_supplier_position =         document.getElementById('cont_position').value ;
  var application_id = document.getElementById('generated_application_id').value;
   var contact_Fax = document.getElementById('cont_fax').value;
 

 // var contact_supplier_postal_code = document.getElementById('postal_code').value
 var user_id = document.getElementById('user_id').value ;
 var tele_code_contact = document.getElementById('cont_response_tele').innerHTML ;


//  contact_supplier_tele = tele_code_contact + contact_supplier_tele;//+;

  contact_supplier_tele = contact_supplier_tele;//+;


 var  company_other = document.getElementById('cs_tradename_other').style.display;


if(company_other == 'block'){ var trade_names_others = document.getElementById('trade_names_other').value; if(trade_names_others == '' ){$('#trade_names').css("background-color", "#e6c1c7"); document.getElementById('trade_names_other').focus(); Toastr_validation();toastr.error('Fill Trade Name for other  ');  return false; }}
if(trade_names =='' || trade_names == 0 ){$('#trade_names').css("border-style", "solid"); document.getElementById('trade_names').focus();Toastr_validation();toastr.error('Fill Trade Name'); return false;}
if(country=='' || country== 0 ){$('#css_country').css("background-color", "#e6c1c7"); document.getElementById('css_country').focus(); /*Toastr_validation();toastr.error('Fill Country');*/ return false;}
if(city == ''){$('#cs_city').css("background-color", "#e6c1c7"); document.getElementById('cs_city').focus(); /*Toastr_validation();toastr.error('Fill City');*/ return false;}
//if(state == ''){$('#cs_state').css("background-color", "#e6c1c7"); document.getElementById('cs_state).focus(); /*Toastr_validation();toastr.error('Fill State');*/ return false;}
if(address_line_one == ''){$('#cs_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('cs_address_line_one').focus(); /* Toastr_validation();toastr.error('Fill Address line one');*/ return false;}
//if(address_line_two  == ''){$('#cs_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('cs_address_line_two').focus(); /* Toastr_validation();toastr.error('Fill Address line two'); */ return false;}
if(email   == ''){$('#cs_email').css("background-color", "#e6c1c7"); document.getElementById('cs_email').focus(); /* Toastr_validation();toastr.error('Fill Email'); */return false;}
if(tele   == ''){$('#cs_tele').css("background-color", "#e6c1c7"); document.getElementById('cs_tele').focus(); /*Toastr_validation();toastr.error('Fill Tele');*/ return false;}
if(postal_code  == ''){$('#postal_code').css("background-color", "#e6c1c7"); document.getElementById('postal_code').focus(); /* Toastr_validation();toastr.error('Fill Postal code'); */ return false;}

if(website_url   == ''){$('#cs_website_url').css("background-color", "#e6c1c7"); document.getElementById('cs_website_url').focus(); /* Toastr_validation();toastr.error('Fill Web site url');*/ return false;}

if(contact_supplier_firstname == ''){$('#cont_first_name').css("background-color", "#e6c1c7"); document.getElementById('cont_first_name').focus(); /* Toastr_validation();toastr.error('Fill Contact first name');*/ return false;}
if(contact_supplier_middlename == ''){ /* $('#cont_middle_name').css("background-color", "#e6c1c7"); document.getElementById('cont_middle_name').focus(); /*Toastr_validation();toastr.error('Fill Contact middle name'); return false; */}
if(contact_supplier_lastname == ''){$('#cont_last_name').css("background-color", "#e6c1c7"); document.getElementById('cont_last_name').focus();/* Toastr_validation();toastr.error('Fill Contact Last name');*/ return false;}
if(contact_supplier_country == ''  || contact_supplier_country == 0){$('#cont_country').css("background-color", "#e6c1c7"); document.getElementById('cont_country').focus(); /* Toastr_validation();toastr.error('Fill Contact Country'); */return false;}
if(contact_supplier_position == ''){$('#cont_position').css("background-color", "#e6c1c7"); document.getElementById('cont_position').focus(); /*Toastr_validation();toastr.error('Fill Contact Position');*/ return false;}
if(contact_supplier_city == ''){$('#cont_city').css("background-color", "#e6c1c7"); document.getElementById('cont_city').focus(); /* Toastr_validation();toastr.error('Fill Contact City');*/ return false;}
if(contact_supplier_address_line_one == ''){$('#cont_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('cont_address_line_one').focus(); /* Toastr_validation();toastr.error('Fill Contact address line one'); */ return false;}
//if(contact_supplier_address_line_two == ''){$('#cont_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('cont_address_line_two').focus(); /* Toastr_validation();toastr.error('Fill Contact address line two'); */ return false;}
if(contact_supplier_email == ''){$('#cont_email').css("background-color", "#e6c1c7"); document.getElementById('cont_email').focus(); /*Toastr_validation();toastr.error('Fill Contact Email');*/ return false;}
if(contact_supplier_tele == ''){$('#cont_tele').css("background-color", "#e6c1c7"); document.getElementById('cont_tele').focus(); /*Toastr_validation();toastr.error('Fill Contact Tele'); */return false;}


//if(contact_supplier_tele == ''){$('#cont_tele').css("background-color", "#e6c1c7"); document.getElementById('cont_tele').focus(); /*Toastr_validation();toastr.error('Fill Contact Tele'); */return false;}


 


  $.ajax({
        //data: $('#bookForm').serialize(),
        data:{ 
            trade_name: trade_name,
            trade_name_other:trade_name_other,
            country_id: country,
            city:city,
            state:state,
            address_line_one:address_line_one,
            address_line_two:address_line_two,
            country_code:tele_code_supplier,
            telephone:tele,
            email:email,
            webiste_url:website_url,
            user_id:user_id,
            application_id:application_id,
            postal_code:postal_code,
           // institutional_email:institutional_email,
          //From Supplier Information
            first_name:contact_supplier_firstname,
            middle_name:contact_supplier_middlename,
            last_name:contact_supplier_lastname,
            country_id_contact:contact_supplier_country,
            position:contact_supplier_position,
            city_contact:contact_supplier_city,
            address_line_one_contact:contact_supplier_address_line_one,
            address_line_two_contact:contact_supplier_address_line_two,
            telephone_contact:contact_supplier_tele,
            //webiste_url_contact:contact_supplier_website_url,
            email_contact:contact_supplier_email,
            contact_Fax:contact_Fax,
            contact_type:'Supplier',

            
            },
        url: "{{   route('company_supplier_save')   }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {

         if(data.supplyInfo==true)  
         {
           
document.getElementById('contact_id').value = data.Contact_ID;
document.getElementById('supplier_id').value = data.Supplier_ID;  


document.getElementById('company_supplier_template_id').value =  data.company_supplier_template_id;     
// document.getElementById('cs_next_button').disabled = false;
// $('#cs_save').hide();
// $('#cs_update').show();

$('#save_supplier_info').hide();
$('#update_supplier_info').show();
$('.app_recep_next_companyinfo').show();

var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 6000
  }); 

toastr.success("Applicant and contact person information saved successfully")

         } 
         else
         {

          Toastr();
toastr.error(data.supplyInfo.errorInfo[2])
}
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }

      
    });

  }
});



//Update Supplier Information 
$('#update_supplier_info').click(function () {
      
      if( document.getElementById('new_applicant').checked == true  ) 
      {                      
        var trade_name_other  = document.getElementById('trade_names_other').value; 
        var company_supplier_template_id  = document.getElementById('company_supplier_template_id').value;
       
       
             //From New Supplier
      var trade_name  = document.getElementById('cs_trade_name').value;
      var country = document.getElementById('css_country').value;
      var city =  document.getElementById('cs_city').value;
      var state =document.getElementById('cs_state').value ;
      var address_line_one = document.getElementById('cs_address_line_one').value;
      var address_line_two= document.getElementById('cs_address_line_two').value ;
      var email = document.getElementById('cs_email').value ;
      var tele =  document.getElementById('cs_tele').value ;
      //var tele_code =  document.getElementById('cs_response_tele').innerHTML ;
      var url = document.getElementById('cs_website_url').value ;
      var website_url = document.getElementById('cs_website_url').value ;
      var postal_code = document.getElementById('postal_code').value;
      //var institutional_email = document.getElementById('cs_institutional_email').value ;
  
      var tele_code_supplier = document.getElementById('cs_response_tele').innerHTML ;
     
         
         
          //Contact Supplier Full Information
      var contact_supplier_firstname  = document.getElementById('cont_first_name').value ;
      var contact_supplier_middlename = document.getElementById('cont_middle_name').value ;
      var contact_supplier_lastname  = document.getElementById('cont_last_name').value ;
      var contact_supplier_country  = document.getElementById('cont_country').value ;
      var contact_supplier_city  = document.getElementById('cont_city').value ;
      var contact_supplier_address_line_one = document.getElementById('cont_address_line_one').value ;
      var contact_supplier_address_line_two = document.getElementById('cont_address_line_two').value ;
      var contact_supplier_tele = document.getElementById('cont_tele').value ;
  
      var contact_supplier_email = document.getElementById('cont_email').value ;
      var contact_Fax = document.getElementById('cont_fax').value;
    
    //  var contact_supplier_website_url = document.getElementById('cont_webiste_url').value ;
      var contact_supplier_position = document.getElementById('cont_position').value ;
      var application_id = document.getElementById('generated_application_id').value;
      var tele_code_contact = document.getElementById('cont_response_tele').innerHTML ;
      contact_supplier_tele =  tele_code_contact+contact_supplier_tele;
     //Get ID
    var user_id = document.getElementById('user_id').value ;
    var contact_id =  document.getElementById('contact_id').value ;
    var supplier_id=  document.getElementById('supplier_id').value ;
  
  
  
     
  
  
      $.ajax({
            //data: $('#bookForm').serialize(),
            data:{ 
                trade_name: trade_name,
                company_supplier_template_id:company_supplier_template_id,
                trade_name_other:trade_name_other,
                country_id: country,
                city:city,
                state:state,
                address_line_one:address_line_one,
                address_line_two:address_line_two,
                country_code:tele_code_supplier ,
                telephone:tele,
                email:email,
                webiste_url:website_url,
                contact_id:contact_id,
                user_id :user_id ,
                application_id:application_id,
                //institutional_email :institutional_email ,
                
  
                //From Supplier Information
                first_name:contact_supplier_firstname,
                middle_name:contact_supplier_middlename,
                last_name:contact_supplier_lastname,
                country_id_contact:contact_supplier_country,
                position:contact_supplier_position,
                city_contact:contact_supplier_city,
                address_line_one_contact:contact_supplier_address_line_one,
                address_line_two_contact:contact_supplier_address_line_two,
                postal_code:postal_code,
                telephone_contact:contact_supplier_tele,
                webiste_url_contact:contact_supplier_website_url,
                email_contact:contact_supplier_email,
                contact_Fax :contact_Fax ,
                contact_type:'Supplier',
                supplier_id:supplier_id,
  
                
                },
            url: "{{   url('/company_supplier_update')   }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
  
             if(data.supplyInfo_updated==1)  
             {
  document.getElementById('contact_id').value = data.Contact_ID;
  document.getElementById('supplier_id').value = data.Supplier_ID;       
  // document.getElementById('cs_next_button').disabled = false;
  
  $('#save_supplier_info').hide();
  $('#update_supplier_info').show();
  $('.app_recep_next_companyinfo').show();
  
  document.getElementById('company_supplier_template_id').value =  data.company_supplier_template_id;   
  //$('#cs_save').hide();
  //$('#cs_update').show();
  
  Toastr();
  
    toastr.success("Applicant and contact person information saved successfully.")
  
             } 
  
      else if(data.supplyInfo_updated==0)  {
  
  Toastr();
  
  toastr.info("No update is made.")
  
             }
             
             else
             {
      Toastr();
      toastr.error(data.supplyInfo.errorInfo[2])
  }
            },
            error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save Changes');
            }
  
          
        });
      }
  
  
      else if( document.getElementById('old_applicant').checked == true) 
      { 
        
        
        var trade_name_other  = document.getElementById('trade_names_other').value; 
        var company_supplier_template_id  = document.getElementById('company_supplier_template_id').value;
        
        
  
        var trade_name  = document.getElementById('trade_names').value;
      var country = document.getElementById('css_country').value;
      var city =  document.getElementById('cs_city').value;
      var state =document.getElementById('cs_state').value ;
      var address_line_one = document.getElementById('cs_address_line_one').value;
      var address_line_two= document.getElementById('cs_address_line_two').value ;
      var email = document.getElementById('cs_email').value ;
      var tele =  document.getElementById('cs_tele').value ;
      //var tele_code =  document.getElementById('cs_response_tele').innerHTML ;
      var url = document.getElementById('cs_website_url').value ;
      var website_url = document.getElementById('cs_website_url').value ;
      var postal_code = document.getElementById('postal_code').value;
      //var institutional_email = document.getElementById('cs_institutional_email').value ;
  
      var tele_code_supplier = document.getElementById('cs_response_tele').innerHTML ;
     
         
         
          //Contact Supplier Full Information
      var contact_supplier_firstname  = document.getElementById('cont_first_name').value ;
      var contact_supplier_middlename = document.getElementById('cont_middle_name').value ;
      var contact_supplier_lastname  = document.getElementById('cont_last_name').value ;
      var contact_supplier_country  = document.getElementById('cont_country').value ;
      var contact_supplier_city  = document.getElementById('cont_city').value ;
      var contact_supplier_address_line_one = document.getElementById('cont_address_line_one').value ;
      var contact_supplier_address_line_two = document.getElementById('cont_address_line_two').value ;
      var contact_supplier_tele = document.getElementById('cont_tele').value ;
     
      var contact_supplier_email = document.getElementById('cont_email').value ;
    //  var contact_supplier_website_url = document.getElementById('cont_webiste_url').value ;
      var contact_supplier_position = document.getElementById('cont_position').value ;
      var application_id = document.getElementById('generated_application_id').value;
      var tele_code_contact = document.getElementById('cont_response_tele').innerHTML ;
      var contact_Fax = document.getElementById('cont_fax').value;
     
  
     //Get ID
    var user_id = document.getElementById('user_id').value ;
    var contact_id =  document.getElementById('contact_id').value ;
    var supplier_id=  document.getElementById('supplier_id').value ;
  
  
     var  company_other = document.getElementById('cs_tradename_other').style.display;
  
  if(company_other == 'block'){ var trade_names_others = document.getElementById('trade_names_other').value; if(trade_names_others == '' ){$('#trade_names').css("background-color", "#e6c1c7"); document.getElementById('trade_names_other').focus(); Toastr_validation();toastr.error('Fill Trade Name for other  ');  return false; }}
  if(trade_names =='' || trade_names == 0 ){$('#trade_names').css("border-style", "solid"); document.getElementById('trade_names').focus();Toastr_validation();toastr.error('Fill Trade Name'); return false;}
  if(country=='' || country== 0 ){$('#css_country').css("background-color", "#e6c1c7"); document.getElementById('css_country').focus(); /*Toastr_validation();toastr.error('Fill Country');*/ return false;}
  if(city == ''){$('#cs_city').css("background-color", "#e6c1c7"); document.getElementById('cs_city').focus(); /*Toastr_validation();toastr.error('Fill City');*/ return false;}
  //if(state == ''){$('#cs_state').css("background-color", "#e6c1c7"); document.getElementById('cs_state').focus(); /*Toastr_validation();toastr.error('Fill State');*/ return false;}
  if(address_line_one == ''){$('#cs_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('cs_address_line_one').focus(); /* Toastr_validation();toastr.error('Fill Address line one');*/ return false;}
  //if(address_line_two  == ''){$('#cs_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('cs_address_line_two').focus(); /* Toastr_validation();toastr.error('Fill Address line two'); */ return false;}
  if(email   == ''){$('#cs_email').css("background-color", "#e6c1c7"); document.getElementById('cs_email').focus(); /* Toastr_validation();toastr.error('Fill Email'); */return false;}
  if(tele   == ''){$('#cs_tele').css("background-color", "#e6c1c7"); document.getElementById('cs_tele').focus(); /*Toastr_validation();toastr.error('Fill Tele');*/ return false;}
  if(postal_code  == ''){$('#postal_code').css("background-color", "#e6c1c7"); document.getElementById('postal_code').focus(); /* Toastr_validation();toastr.error('Fill Postal code'); */ return false;}
  
  if(website_url   == ''){$('#cs_website_url').css("background-color", "#e6c1c7"); document.getElementById('cs_website_url').focus(); /* Toastr_validation();toastr.error('Fill Web site url');*/ return false;}
  if(contact_supplier_firstname == ''){$('#cont_first_name').css("background-color", "#e6c1c7"); document.getElementById('cont_first_name').focus(); /* Toastr_validation();toastr.error('Fill Contact first name');*/ return false;}
  if(contact_supplier_middlename == ''){ /* $('#cont_middle_name').css("background-color", "#e6c1c7"); document.getElementById('cont_middle_name').focus(); /*Toastr_validation();toastr.error('Fill Contact middle name'); return false; */}
  
  // if(contact_supplier_middlename == ''){$('#cont_middle_name').css("background-color", "#e6c1c7"); document.getElementById('cont_middle_name').focus(); /*Toastr_validation();toastr.error('Fill Contact middle name');*/ return false;}
  if(contact_supplier_lastname == ''){$('#cont_last_name').css("background-color", "#e6c1c7"); document.getElementById('cont_last_name').focus();/* Toastr_validation();toastr.error('Fill Contact Last name');*/ return false;}
  if(contact_supplier_country == ''  || contact_supplier_country == 0){$('#cont_country').css("background-color", "#e6c1c7"); document.getElementById('cont_country').focus(); /* Toastr_validation();toastr.error('Fill Contact Country'); */return false;}
  if(contact_supplier_position == ''){$('#cont_position').css("background-color", "#e6c1c7"); document.getElementById('cont_position').focus(); /*Toastr_validation();toastr.error('Fill Contact Position');*/ return false;}
  if(contact_supplier_city == ''){$('#cont_city').css("background-color", "#e6c1c7"); document.getElementById('cont_city').focus(); /* Toastr_validation();toastr.error('Fill Contact City');*/ return false;}
  if(contact_supplier_address_line_one == ''){$('#cont_address_line_one').css("background-color", "#e6c1c7"); document.getElementById('cont_address_line_one').focus(); /* Toastr_validation();toastr.error('Fill Contact address line one'); */ return false;}
  //if(contact_supplier_address_line_two == ''){$('#cont_address_line_two').css("background-color", "#e6c1c7"); document.getElementById('cont_address_line_two').focus(); /* Toastr_validation();toastr.error('Fill Contact address line two'); */ return false;}
  if(contact_supplier_email == ''){$('#cont_email').css("background-color", "#e6c1c7"); document.getElementById('cont_email').focus(); /*Toastr_validation();toastr.error('Fill Contact Email');*/ return false;}
  if(contact_supplier_tele == ''){$('#cont_tele').css("background-color", "#e6c1c7"); document.getElementById('cont_tele').focus(); /*Toastr_validation();toastr.error('Fill Contact Tele'); */return false;}
  
     
      //contact_supplier_tele =  tele_code_contact+contact_supplier_tele;
      //alert(contact_supplier_tele );
  
      $.ajax({
            //data: $('#bookForm').serialize(),
            data:{ 
                trade_name: trade_name,
                company_supplier_template_id:company_supplier_template_id,
                trade_name_other :trade_name_other ,
                country_id: country,
                city:city,
                state:state,
                address_line_one:address_line_one,
                address_line_two:address_line_two,
                country_code:tele_code_supplier ,
                telephone:tele,
                email:email,
                webiste_url:website_url,
                contact_id:contact_id,
                user_id :user_id ,
                application_id:application_id,
                //institutional_email :institutional_email ,
                
  
                //From Supplier Information
                first_name:contact_supplier_firstname,
                middle_name:contact_supplier_middlename,
                last_name:contact_supplier_lastname,
                country_id_contact:contact_supplier_country,
                position:contact_supplier_position,
                city_contact:contact_supplier_city,
                address_line_one_contact:contact_supplier_address_line_one,
                address_line_two_contact:contact_supplier_address_line_two,
                postal_code:postal_code,
                telephone_contact:contact_supplier_tele,
                //webiste_url_contact:contact_supplier_website_url,
                email_contact:contact_supplier_email,
                contact_Fax:contact_Fax,
                contact_type:'Supplier',
                supplier_id:supplier_id,
  
                
                },
            url: "{{   url('/company_supplier_update')   }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
  
             if(data.supplyInfo_updated==1)  
             {
  document.getElementById('contact_id').value = data.Contact_ID;
  document.getElementById('supplier_id').value = data.Supplier_ID;       
  // document.getElementById('cs_next_button').disabled = false;
  document.getElementById('company_supplier_template_id').value =  data.company_supplier_template_id;   
  
  //$('#cs_save').hide();
  //$('#cs_update').show();
  
  $('#save_supplier_info').hide();
  $('#update_supplier_info').show();
  $('.app_recep_next_companyinfo').show();
  
  
  Toastr();
  toastr.success("Applicant and  Contact Infomation updated successfully")
  
             } 
  
      else if(data.supplyInfo_updated==0)  {
  
  Toastr();
  toastr.info("No update is made.")
  
             }
  
              else if(data.supplyInfo_updated==-1)  {
  
  Toastr();
  toastr.error("Dear Applicant new company can not update previous selected company!! Start your application again!! ")
  
            }
             
             else
             {
      Toastr();
      toastr.error(data.supplyInfo.errorInfo[2])
  }
            },
            error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save Changes');
            }
  
          
        });
     
      }
  });
  
  
  

///Saving Private Rayan Saving Product Detial Info

$('#save_product_detail_info').click(function () {
                         
                         //From New Product Details
                 
                  var generic_approved_name = document.getElementById('generic_approved_name').value;
                  var generic_approved_name_other =  document.getElementById('generic_approved_name_other').value;
                  var proprietary_trade_name  = document.getElementById('product_trade_name').value;
                  var dosage_form_id =  document.getElementById('dosage_form_id').value;
                  var route_administration_id =document.getElementById('route_administration_id').value ;
                  var description = document.getElementById('description').value;
                  //var strength_amount = document.getElementById('strength_amount').value ;
                  //var strength_unit = document.getElementById('strength_unit').value ;
                  var strength_unit_amount = document.getElementById('strength_amount_unit').value ;
                  var storage_condition =  document.getElementById('storage_condition').value ;
                  var shelf_life_amount = document.getElementById('shelf_life_amount').value ;
                  var shelf_life_unit = document.getElementById('shelf_life_unit').value ;
                  var pharmaco_therapeutic_classification = document.getElementById('pharmaco_therapeutic_classification').value ;
                  var proposed_shelf_life_amount  = document.getElementById('proposed_shelf_life_amount').value ;
                  var proposed_shelf_life_unit = document.getElementById('proposed_shelf_life_unit').value ;
                  var proposed_shelf_life_after_reconstitution_amount  = document.getElementById('proposed_shelf_life_after_reconstitution_amount').value ;
                  var proposed_shelf_life_after_reconstitution_unit  = document.getElementById('proposed_shelf_life_after_reconstitution_unit').value ;
                  var visual_description  = document.getElementById('visual_description').value ;
                  var commercial_presentation = document.getElementById('commercial_presentation').value ;
                  var container = document.getElementById('container').value ;
                  var packaging = document.getElementById('packaging').value ;
                  var category_use = document.getElementById('category_use').value ;
                  var application_id = document.getElementById('generated_application_id').value;
              
              
              var  international_name = document.getElementById('international_name').style.display;
              if(generic_approved_name == '' || generic_approved_name==0 ){$('#generic_approved_name').css("background-color", "#e6c1c7"); document.getElementById('generic_approved_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(international_name == 'block'){ var generic_approved_name_other = document.getElementById('generic_approved_name_other').value; if(generic_approved_name_other == '' ){$('#generic_approved_name_other').css("background-color", "#e6c1c7"); document.getElementById('generic_approved_name_other').focus(); /*Toastr_validation();toastr.error('Fill Trade Name for other  '); */ return false; }}  
              if(proprietary_trade_name == ''){ $('#product_trade_name').css("background-color", "#e6c1c7"); document.getElementById('product_trade_name').focus(); return false; /*Toastr_validation();toastr.error('Fill Bussines Name'); return false; */}
              if(dosage_form_id== '' || dosage_form_id== 0){$('#dosage_form_id').css("background-color", "#e6c1c7"); document.getElementById('dosage_form_id').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(route_administration_id == '' || route_administration_id == 0){$('#route_administration_id').css("background-color", "#e6c1c7"); document.getElementById('route_administration_id').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              
              //if(description == ''){ /*$('#description').css("background-color", "#e6c1c7"); document.getElementById('description').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name'); return false;*/}
              //if(strength_unit_amount == ''){$('#strength_unit_amount').css("background-color", "#e6c1c7"); document.getElementById('strength_unit_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              
              if(storage_condition== ''){$('#storage_condition').css("background-color", "#e6c1c7"); document.getElementById('storage_condition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(shelf_life_amount == ''){$('#shelf_life_amount').css("background-color", "#e6c1c7"); document.getElementById('shelf_life_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(shelf_life_unit == ''){$('#shelf_life_unit').css("background-color", "#e6c1c7"); document.getElementById('shelf_life_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(pharmaco_therapeutic_classification == ''){$('#pharmaco_therapeutic_classification').css("background-color", "#e6c1c7"); document.getElementById('pharmaco_therapeutic_classification').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
           
           
              //if(proposed_shelf_life_amount == ''){$('#proposed_shelf_life_amount').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              //if(proposed_shelf_life_unit == ''){$('#proposed_shelf_life_unit').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              //if(proposed_shelf_life_after_reconstitution_amount== ''){$('#proposed_shelf_life_after_reconstitution_amount').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_after_reconstitution_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
             
              if(proposed_shelf_life_after_reconstitution_unit == ''){$('#proposed_shelf_life_after_reconstitution_unit').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_after_reconstitution_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(visual_description== ''){$('#visual_description').css("background-color", "#e6c1c7"); document.getElementById('visual_description').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(commercial_presentation== ''){$('#visual_description').css("background-color", "#e6c1c7"); document.getElementById('commercial_presentation').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(container== ''){$('#container').css("background-color", "#e6c1c7"); document.getElementById('container').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(packaging== ''){$('#visual_description').css("background-color", "#e6c1c7"); document.getElementById('packaging').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              if(category_use== '' || category_use== 0  ){$('#category_use').css("background-color", "#e6c1c7"); document.getElementById('category_use').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
              
              
              
              
              
              
                if(generic_approved_name != 919)
              { 
                var user_id = document.getElementById('user_id').value ;
                $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            product_trade_name: proprietary_trade_name,
                            medicine_id: generic_approved_name,
                            dosage_form_id:dosage_form_id,
                            route_administration_id:route_administration_id,
                          //   description:description,
                            //strength_amount:strength_amount,
                            strength_amount_strength_unit:'--',
                            pharmaco_therapeutic_classification:pharmaco_therapeutic_classification,
                            storage_condition:storage_condition,
                            shelf_life_amount:shelf_life_amount,
                            shelf_life_unit:shelf_life_unit,
                            proposed_shelf_life_amount:proposed_shelf_life_amount,
                            proposed_shelf_life_unit:proposed_shelf_life_unit,
                            proposed_shelf_life_after_reconstitution_amount : proposed_shelf_life_after_reconstitution_amount,
                            proposed_shelf_life_after_reconstitution_unit : proposed_shelf_life_after_reconstitution_unit,
                            visual_description:visual_description,
                            commercial_presentation :commercial_presentation,
                            container:container,
                            packaging:packaging,
                            category_use:category_use,
                            application_id:application_id,
                           
                            
                            },
                      url: "{{   url('/product_details/save')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
              document.getElementById('product_detail_master_id').value = data.productdetial_id;
              document.getElementById('product_detail_next_button').style.display = "block";
              
              document.getElementById('d_p_name').innerHTML =   data.product_name;
              // document.getElementById('d_stregth').innerHTML =  data.strength_name;
              document.getElementById('d_dname').innerHTML  =  data.dosage_form;
              
              
              
              
              
              $('#save_product_detail_info').hide();
              $('#update_product_detail_info').show();
              
              
              Toastr();
              
              toastr.success("Prodcut Detail Saved Successfully")
              
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
                  
                  }
              
              
               else{
              
                  var user_id = document.getElementById('user_id').value ;
                   $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                          user_id:user_id,
                            product_trade_name: proprietary_trade_name,
                            medicine_id: generic_approved_name,
                            dosage_form_id:dosage_form_id,
                            route_administration_id:route_administration_id,
                            description:description,
                           // strength_amount:strength_amount,
                            //strength_unit:strength_unit,
                            strength_amount_strength_unit:strength_unit_amount,
                            pharmaco_therapeutic_classification:pharmaco_therapeutic_classification,
                            storage_condition:storage_condition,
                            shelf_life_amount:shelf_life_amount,
                            shelf_life_unit:shelf_life_unit,
                            proposed_shelf_life_amount:proposed_shelf_life_amount,
                            proposed_shelf_life_unit:proposed_shelf_life_unit,
                            proposed_shelf_life_after_reconstitution_amount : proposed_shelf_life_after_reconstitution_amount,
                            proposed_shelf_life_after_reconstitution_unit : proposed_shelf_life_after_reconstitution_unit,
                            visual_description:visual_description,
                            commercial_presentation :commercial_presentation,
                            container:container,
                            packaging:packaging,
                            category_use:category_use,
                            application_id:application_id,
                            generic_approved_name_other:generic_approved_name_other,
                           
                            
                            },
                        url: "{{   url('/product_details/save/other')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
              
              $('#save_product_detail_info').hide();
              $('#update_product_detail_info').show();
              
              document.getElementById('product_detail_master_id').value = data.productdetial_id;
              document.getElementById('product_detail_next_button').disabled = false;
              document.getElementById('if_other').value = data.medicine_if_other;
              document.getElementById('product_detail_next_button').style.display = "block";
              $('#product_detail_save').hide();
              $('#product_detail_update').show();
              Toastr();
              
              
              toastr.success("Prodcut detail saved successfully.")
              
              
              document.getElementById('d_p_name').innerHTML =  generic_approved_name_other;
              document.getElementById('d_dname').innerHTML  =  data.dosage_form;
              
              
              
              
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
               }
              
              
                  });
              
                            
              

///Updating Private Rayan Updating Product Detial Info




///Updating Private Rayan Updating Product Detial Info

$('#update_product_detail_info').click(function () {
                         
                         //From New Product Details
        
         var generic_approved_name = document.getElementById('generic_approved_name').value;
         var generic_approved_name_other =  document.getElementById('generic_approved_name_other').value;
         var proprietary_trade_name  = document.getElementById('product_trade_name').value;
         var dosage_form_id =  document.getElementById('dosage_form_id').value;
         var route_administration_id =document.getElementById('route_administration_id').value ;
         var description = document.getElementById('description').value;
         //var strength_amount = document.getElementById('strength_amount').value ;
         //var strength_unit = document.getElementById('strength_unit').value ;
         var strength_unit_amount = document.getElementById('strength_amount_unit').value ;
         var storage_condition =  document.getElementById('storage_condition').value ;
         var shelf_life_amount = document.getElementById('shelf_life_amount').value ;
         var shelf_life_unit = document.getElementById('shelf_life_unit').value ;
         var pharmaco_therapeutic_classification = document.getElementById('pharmaco_therapeutic_classification').value ;
         var proposed_shelf_life_amount  = document.getElementById('proposed_shelf_life_amount').value ;
         var proposed_shelf_life_unit = document.getElementById('proposed_shelf_life_unit').value ;
         var proposed_shelf_life_after_reconstitution_amount  = document.getElementById('proposed_shelf_life_after_reconstitution_amount').value ;
         var proposed_shelf_life_after_reconstitution_unit  = document.getElementById('proposed_shelf_life_after_reconstitution_unit').value ;
         var visual_description  = document.getElementById('visual_description').value ;
         var commercial_presentation = document.getElementById('commercial_presentation').value ;
         var container = document.getElementById('container').value ;
         var packaging = document.getElementById('packaging').value ;
         var category_use = document.getElementById('category_use').value ;
         var application_id = document.getElementById('generated_application_id').value;
     
     
     var  international_name = document.getElementById('international_name').style.display;
     if(generic_approved_name == '' || generic_approved_name==0 ){$('#generic_approved_name').css("background-color", "#e6c1c7"); document.getElementById('generic_approved_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(international_name == 'block'){ var generic_approved_name_other = document.getElementById('generic_approved_name_other').value; if(generic_approved_name_other == '' ){$('#generic_approved_name_other').css("background-color", "#e6c1c7"); document.getElementById('generic_approved_name_other').focus(); /*Toastr_validation();toastr.error('Fill Trade Name for other  '); */ return false; }}  
     if(proprietary_trade_name == ''){ $('#product_trade_name').css("background-color", "#e6c1c7"); document.getElementById('product_trade_name').focus(); return false; /*Toastr_validation();toastr.error('Fill Bussines Name'); return false; */}
     if(dosage_form_id== '' || dosage_form_id== 0){$('#dosage_form_id').css("background-color", "#e6c1c7"); document.getElementById('dosage_form_id').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(route_administration_id == '' || route_administration_id == 0){$('#route_administration_id').css("background-color", "#e6c1c7"); document.getElementById('route_administration_id').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     
     //if(description == ''){ /*$('#description').css("background-color", "#e6c1c7"); document.getElementById('description').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name'); return false;*/}
     //if(strength_unit_amount == ''){$('#strength_unit_amount').css("background-color", "#e6c1c7"); document.getElementById('strength_unit_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     
     if(storage_condition== ''){$('#storage_condition').css("background-color", "#e6c1c7"); document.getElementById('storage_condition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(shelf_life_amount == ''){$('#shelf_life_amount').css("background-color", "#e6c1c7"); document.getElementById('shelf_life_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(shelf_life_unit == ''){$('#shelf_life_unit').css("background-color", "#e6c1c7"); document.getElementById('shelf_life_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(pharmaco_therapeutic_classification == ''){$('#pharmaco_therapeutic_classification').css("background-color", "#e6c1c7"); document.getElementById('pharmaco_therapeutic_classification').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(proposed_shelf_life_amount == ''){$('#proposed_shelf_life_amount').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
   
    // if(proposed_shelf_life_unit == ''){$('#proposed_shelf_life_unit').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
    // if(proposed_shelf_life_after_reconstitution_amount== ''){$('#proposed_shelf_life_after_reconstitution_amount').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_after_reconstitution_amount').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
    // if(proposed_shelf_life_after_reconstitution_unit == ''){$('#proposed_shelf_life_after_reconstitution_unit').css("background-color", "#e6c1c7"); document.getElementById('proposed_shelf_life_after_reconstitution_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
    
     if(visual_description== ''){$('#visual_description').css("background-color", "#e6c1c7"); document.getElementById('visual_description').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(commercial_presentation== ''){$('#visual_description').css("background-color", "#e6c1c7"); document.getElementById('commercial_presentation').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(container== ''){$('#container').css("background-color", "#e6c1c7"); document.getElementById('container').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(packaging== ''){$('#visual_description').css("background-color", "#e6c1c7"); document.getElementById('packaging').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     if(category_use== '' || category_use== 0  ){$('#category_use').css("background-color", "#e6c1c7"); document.getElementById('category_use').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
     
     
     
     
     
     
       if(generic_approved_name != 919)
     { 
       var user_id = document.getElementById('user_id').value ;
       $.ajax({
               //data: $('#bookForm').serialize(),
               data:{ 
                   user_id:user_id,
                   product_trade_name: proprietary_trade_name,
                   medicine_id: generic_approved_name,
                   dosage_form_id:dosage_form_id,
                   route_administration_id:route_administration_id,
                 //   description:description,
                   //strength_amount:strength_amount,
                   strength_amount_strength_unit:'--',
                   pharmaco_therapeutic_classification:pharmaco_therapeutic_classification,
                   storage_condition:storage_condition,
                   shelf_life_amount:shelf_life_amount,
                   shelf_life_unit:shelf_life_unit,
                   proposed_shelf_life_amount:proposed_shelf_life_amount,
                   proposed_shelf_life_unit:proposed_shelf_life_unit,
                   proposed_shelf_life_after_reconstitution_amount : proposed_shelf_life_after_reconstitution_amount,
                   proposed_shelf_life_after_reconstitution_unit : proposed_shelf_life_after_reconstitution_unit,
                   visual_description:visual_description,
                   commercial_presentation :commercial_presentation,
                   container:container,
                   packaging:packaging,
                   category_use:category_use,
                   application_id:application_id,
                  
                   
                   },
             url: "{{   url('/product_details/update')   }}",
               type: "POST",
               dataType: 'json',
               success: function (data) {
     
                if(data.Message == true)  
                {
     document.getElementById('product_detail_master_id').value = data.productdetial_id;
     document.getElementById('product_detail_next_button').style.display = "block";
     
     
     document.getElementById('d_p_name').innerHTML =   data.product_name;
     // document.getElementById('d_stregth').innerHTML =  data.strength_name;
     document.getElementById('d_dname').innerHTML  =  data.dosage_form;
     
     $('#save_product_detail_info').hide();
     $('#update_product_detail_info').show();
     
     
     Toastr();
     toastr.success("Prodcut detail updated succesfully.")
     
                } 
                else
                {
     Toastr();
     toastr.info(data.ProductInfo)
     
     }
               },
               error: function (data) {
                   console.log('Error:', data);
                   $('#saveBtn').html('Save Changes');
               }
     
             
           });
         
         }
     
     
      else{
     
         var user_id = document.getElementById('user_id').value ;
         var other_medicine_id = document.getElementById('if_other').value ;
     
     
          $.ajax({
               //data: $('#bookForm').serialize(),
               data:{ 
                   user_id:user_id,
                   product_trade_name: proprietary_trade_name,
                   medicine_id: generic_approved_name,
                   dosage_form_id:dosage_form_id,
                   route_administration_id:route_administration_id,
                   description:description,
                   other_medicine_id:other_medicine_id,
                  // strength_amount:strength_amount,
                   //strength_unit:strength_unit,
                   strength_amount_strength_unit:strength_unit_amount,
                   pharmaco_therapeutic_classification:pharmaco_therapeutic_classification,
                   storage_condition:storage_condition,
                   shelf_life_amount:shelf_life_amount,
                   shelf_life_unit:shelf_life_unit,
                   proposed_shelf_life_amount:proposed_shelf_life_amount,
                   proposed_shelf_life_unit:proposed_shelf_life_unit,
                   proposed_shelf_life_after_reconstitution_amount : proposed_shelf_life_after_reconstitution_amount,
                   proposed_shelf_life_after_reconstitution_unit : proposed_shelf_life_after_reconstitution_unit,
                   visual_description:visual_description,
                   commercial_presentation :commercial_presentation,
                   container:container,
                   packaging:packaging,
                   category_use:category_use,
                   application_id:application_id,
                   generic_approved_name_other:generic_approved_name_other,
                  
                   
                   },
               url: "{{   url('/product_details/update/other')   }}",
               type: "POST",
               dataType: 'json',
               success: function (data) {
     
                if(data.Message == true)  
                {
     
     $('#save_product_detail_info').hide();
     $('#update_product_detail_info').show();
     
     document.getElementById('product_detail_master_id').value = data.productdetial_id;
     document.getElementById('product_detail_next_button').disabled = false;
     document.getElementById('product_detail_next_button').style.display = "block";
     
     document.getElementById('d_p_name').innerHTML =  generic_approved_name_other;
     document.getElementById('d_dname').innerHTML  =  data.dosage_form;
     
     document.getElementById('if_other').value = data.medicine_id;
     
     
     $('#product_detail_save').hide();
     $('#product_detail_update').show();
     Toastr();
     toastr.success("Prodcut detail updated successfully.")
     
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
     
     
     
     
     
      }
     
     
                       });
     
          
     



//Product Manufacturer Data Saving wizard

$('#save_product_manufacturer_save').click(function () {
                         
                         //From New Product Details
                  var manufacturer_country_id  = document.getElementById('manufacturer_country').value;
                  var manufacturer_tele_code  = document.getElementById('manu_response_tele').innerHTML;
                  var manufacturer_name = document.getElementById('manufacturer_name').value;
                  var manufacturer_postal_code =  document.getElementById('manufacturer_postal_code').value;
                  var manufacturer_tele =document.getElementById('manufacturer_tele').value ;
                  var manufacturer_city = document.getElementById('manufacturer_city').value ;
                  var manufacturer_state =  document.getElementById('manufacturer_state').value ;
                  var manufacturer_add_line_one = document.getElementById('manufacturer_add_line_one').value ;
                  var manufacturer_add_line_two =  document.getElementById('manufacturer_add_line_two').value ;
                 // var manufacturer_url =  document.getElementById('manufacturer_url').value ;
                 // var manufacturer_email = document.getElementById('manufacturer_email').value ;
                  var manufacturer_activity = document.getElementById('manufacturer_activity').value ;
                  var user_id = document.getElementById('user_id').value ;
                  var manufacturer_unit = document.getElementById('manufacturer_unit').value ;
                  var manufacturer_block = document.getElementById('manufacturer_block').value ;
                  var product_detail_master_id = document.getElementById('product_detail_master_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;



if(manufacturer_name  == ''){$('#manufacturer_name').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_country_id  == '' || manufacturer_country_id  == 0){$('#manufacturer_country').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_country').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_city  == ''){$('#manufacturer_city').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_city').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_tele  == ''){$('#manufacturer_tele').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_postal_code  == ''){$('#manufacturer_postal_code').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_postal_code').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_add_line_one  == ''){$('#manufacturer_add_line_one').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_add_line_one').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_block  == ''){$('#manufacturer_block').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_block').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_unit  == ''){$('#manufacturer_unit').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_activity.trim()  == ''){$('#manufacturer_activity').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_activity').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(manufacturer_state  == ''){$('#manufacturer_state').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_state').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(manufacturer_add_line_two  == ''){$('#manufacturer_add_line_two').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_add_line_two').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}


            
            
                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            name: manufacturer_name,
                            country_id: manufacturer_country_id,
                            city:manufacturer_city,
                            state:manufacturer_state,
                            addressline_one:manufacturer_add_line_one,
                            addressline_two:manufacturer_add_line_two,
                            postal_code:manufacturer_postal_code,
                            telephone:manufacturer_tele,
                            country_code:manufacturer_tele_code,
                            //webiste_url: manufacturer_url,
                            activity:manufacturer_activity,
                            //email:manufacturer_email,
                            block:manufacturer_block,
                            unit:manufacturer_unit,
                            product_id: product_detail_master_id,
                            application_id:application_id,
                        
                              },
                        url: "{{   url('/product_manufacturer/save')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
                          
document.getElementById('product_manufacturer_next_button').style.display = "block";
document.getElementById('renderd_manufacturer_table').innerHTML = data.renderd_manufacturer_table;
document.getElementById('id_for_update').value = data.Manufacturer_id;



document.getElementById('manufacturer_name').value="";
document.getElementById('manufacturer_country').value="";
document.getElementById('manufacturer_postal_code').value="";
document.getElementById('manufacturer_tele').value="";
document.getElementById('manufacturer_state').value="";
document.getElementById('manufacturer_add_line_one').value="";
document.getElementById('manufacturer_add_line_two').value="";
document.getElementById('manufacturer_activity').value="";
document.getElementById('manufacturer_block').value="";
document.getElementById('manufacturer_unit').value="";
document.getElementById('manufacturer_city').value= "";

     Toastr();
     toastr.success("Product manufacturer saved successfully.")

document.getElementById('address_manufacture').innerHTML =  data.addressline_one + "," + data.addressline_two;
document.getElementById('d_manu_state').innerHTML =  data.name_manufactures;
              
                         } 
                         else
                         {

           Toastr();
           var str = data.Message.errorInfo[2];
           var res = data.Message.errorInfo[2].replace("Column", "Field: ");
          toastr.error(res)

              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });









//Update Product Manufaturer


//Product Manufacturer Data Saving wizard

$('#updates_product_manufacturer_update').click(function () {
                         
                         //From New Product Details
                  var manufacturer_country_id  = document.getElementById('manufacturer_country').value;
                  var manufacturer_tele_code  = document.getElementById('manu_response_tele').innerHTML;
                  var manufacturer_name = document.getElementById('manufacturer_name').value;
                  var manufacturer_postal_code =  document.getElementById('manufacturer_postal_code').value;
                  var manufacturer_tele =document.getElementById('manufacturer_tele').value ;
                  var manufacturer_city = document.getElementById('manufacturer_city').value ;
                  var manufacturer_state =  document.getElementById('manufacturer_state').value ;
                  var manufacturer_add_line_one = document.getElementById('manufacturer_add_line_one').value ;
                  var manufacturer_add_line_two =  document.getElementById('manufacturer_add_line_two').value ;
                 // var manufacturer_url =  document.getElementById('manufacturer_url').value ;
                 // var manufacturer_email = document.getElementById('manufacturer_email').value ;
                  var manufacturer_activity = document.getElementById('manufacturer_activity').value ;
                  var user_id = document.getElementById('user_id').value ;
                  var manufacturer_unit = document.getElementById('manufacturer_unit').value ;
                  var manufacturer_block = document.getElementById('manufacturer_block').value ;
                  var product_detail_master_id = document.getElementById('product_detail_master_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;

              var id_for_update = document.getElementById('id_for_update').innerHTML;
                   var Tele_code = manufacturer_tele_code +  manufacturer_tele;
            
if(manufacturer_name  == ''){$('#manufacturer_name').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_country_id  == '' || manufacturer_country_id  == 0){$('#manufacturer_country').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_country').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_city  == ''){$('#manufacturer_city').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_city').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_tele  == ''){$('#manufacturer_tele').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_postal_code  == ''){$('#manufacturer_postal_code').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_postal_code').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_add_line_one  == ''){$('#manufacturer_add_line_one').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_add_line_one').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_block  == ''){$('#manufacturer_block').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_block').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_unit  == ''){$('#manufacturer_unit').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_activity.trim()  == ''){$('#manufacturer_activity').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_activity').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(manufacturer_state  == ''){$('#manufacturer_state').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_state').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(manufacturer_add_line_two  == ''){$('#manufacturer_add_line_two').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_add_line_two').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}


                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            name: manufacturer_name,
                            country_id: manufacturer_country_id,
                            city:manufacturer_city,
                            state:manufacturer_state,
                            addressline_one:manufacturer_add_line_one,
                            addressline_two:manufacturer_add_line_two,
                            postal_code:manufacturer_postal_code,
                            telephone:manufacturer_tele,
                            country_code:manufacturer_tele_code,
                            activity:manufacturer_activity,
                            block:manufacturer_block,
                            unit:manufacturer_unit,
                            product_id: product_detail_master_id,
                            application_id:application_id,
                            id:id_for_update,
                        
                              },
                        url: "{{   url('/product_manufacturer/update')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
  document.getElementById('id_for_update').value = data.Manufacturer_id;
  //document.getElementById('product_detail_master_id').value = data.productdetial_id;
  document.getElementById('product_manufacturer_next_button').disabled = false;
  document.getElementById('renderd_manufacturer_table').innerHTML = data.renderd_manufacturer_table;




document.getElementById('address_manufacture').innerHTML =  data.addressline_one + "," + data.addressline_two;
document.getElementById('d_manu_state').innerHTML =  data.name_manufactures;

   
              
   Toastr();
    toastr.info("Product Manufacuturer updated successfully ")
              
                         } 
                         else
                         {
                Toastr();
               toastr.error(data.Message.errorInfo)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  }); 

$('#show_edit_composition').click(function () {

document.getElementById('composition_type_composition').focus();
return false;
});

$('#createNewCompostion_update').click(function () {
                         
                  //From New Product Composition
                  var name_inn_text_composition  = document.getElementById('name_inn_text_composition').value;
                  var quantity_composition = document.getElementById('quantity_composition').value;
                  var reason_inclusion_composition =  document.getElementById('reason_inclusion_composition').value;
                  var reference_standard_composition =document.getElementById('reference_standard_composition').value ;
                  var composition_type_composition = document.getElementById('composition_type_composition').value ;
                  var product_detail_master_id = document.getElementById('product_detail_master_id').value ;
                  var user_id =  document.getElementById('user_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;
                  var id_for_update = document.getElementById('id_update_compostion').innerHTML;

if(composition_type_composition  == ''){$('#composition_type_composition').css("background-color", "#e6c1c7"); document.getElementById('composition_type_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

if(name_inn_text_composition== ''){$('#name_inn_text_composition').css("background-color", "#e6c1c7"); document.getElementById('name_inn_text_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(quantity_composition  == ''){$('#quantity_composition').css("background-color", "#e6c1c7"); document.getElementById('quantity_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(reason_inclusion_composition == ''){$('#reason_inclusion_composition').css("background-color", "#e6c1c7"); document.getElementById('reason_inclusion_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(reference_standard_composition == ''){$('#reference_standard_composition').css("background-color", "#e6c1c7"); document.getElementById('reference_standard_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

                 
            
                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            composition_name: name_inn_text_composition,
                            reason: reason_inclusion_composition ,
                            reference_standard:reference_standard_composition,
                            type:composition_type_composition,
                            medical_product_id: product_detail_master_id,
                            quantity:quantity_composition,
                            composition_name:name_inn_text_composition,
                            application_id:application_id,
                            id:id_for_update,
                              },
                        url: "{{   url('/product_Composition/update')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
document.getElementById('next_composition').disabled = false;
document.getElementById('renderd_product_composition_table').innerHTML = data.renderd_product_composition_table;
document.getElementById('id_update_compostion').innerHTML = data.Compostion_id;
             // $('#product_detail_save').hide();
             // $('#product_detail_update').show();
             //document.getElementById('product_detail_master_id').value = data.productdetial_id;
             Toastr();
            toastr.success("Product composition updated successfully.")
              
                         } 
                         else
                         {
                Toastr();
               toastr.error(data.Message.errorInfo)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });



$('#show_edit_composition').click(function () {

document.getElementById('composition_type_composition').focus();
return false;
});


$('#createNewCompostion_update').click(function () {
                         
                  //From New Product Composition
                  var name_inn_text_composition  = document.getElementById('name_inn_text_composition').value;
                  var quantity_composition = document.getElementById('quantity_composition').value;
                  var reason_inclusion_composition =  document.getElementById('reason_inclusion_composition').value;
                  var reference_standard_composition =document.getElementById('reference_standard_composition').value ;
                  var composition_type_composition = document.getElementById('composition_type_composition').value ;
                  var product_detail_master_id = document.getElementById('product_detail_master_id').value ;
                  var user_id =  document.getElementById('user_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;
                  var id_for_update = document.getElementById('id_update_compostion').innerHTML;

if(composition_type_composition  == ''){$('#composition_type_composition').css("background-color", "#e6c1c7"); document.getElementById('composition_type_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

if(name_inn_text_composition== ''){$('#name_inn_text_composition').css("background-color", "#e6c1c7"); document.getElementById('name_inn_text_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(quantity_composition  == ''){$('#quantity_composition').css("background-color", "#e6c1c7"); document.getElementById('quantity_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(reason_inclusion_composition == ''){$('#reason_inclusion_composition').css("background-color", "#e6c1c7"); document.getElementById('reason_inclusion_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(reference_standard_composition == ''){$('#reference_standard_composition').css("background-color", "#e6c1c7"); document.getElementById('reference_standard_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

                 
            
                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            composition_name: name_inn_text_composition,
                            reason: reason_inclusion_composition ,
                            reference_standard:reference_standard_composition,
                            type:composition_type_composition,
                            medical_product_id: product_detail_master_id,
                            quantity:quantity_composition,
                            composition_name:name_inn_text_composition,
                            application_id:application_id,
                            id:id_for_update,
                              },
                        url: "{{   url('/product_Composition/update')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
document.getElementById('next_composition').disabled = false;
document.getElementById('renderd_product_composition_table').innerHTML = data.renderd_product_composition_table;
document.getElementById('id_update_compostion').innerHTML = data.Compostion_id;
             // $('#product_detail_save').hide();
             // $('#product_detail_update').show();
             //document.getElementById('product_detail_master_id').value = data.productdetial_id;
             Toastr();
            toastr.success("Product composition updated successfully.")
              
                         } 
                         else
                         {
                Toastr();
               toastr.error(data.Message.errorInfo)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });



$('#createNewCompostion_save').click(function () {
                         
                         //From New Product Details
                  var name_inn_text_composition  = document.getElementById('name_inn_text_composition').value;
                  var quantity_composition = document.getElementById('quantity_composition').value;
                  var reason_inclusion_composition =  document.getElementById('reason_inclusion_composition').value;
                  var reference_standard_composition =document.getElementById('reference_standard_composition').value ;
                  var composition_type_composition = document.getElementById('composition_type_composition').value ;
                  var product_detail_master_id = document.getElementById('product_detail_master_id').value ;
                  var user_id = document.getElementById('user_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;
                  var product_trade_name =  document.getElementById('product_trade_name').value;

if(composition_type_composition  == ''){$('#composition_type_composition').css("background-color", "#e6c1c7"); document.getElementById('composition_type_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

if(name_inn_text_composition== ''){$('#name_inn_text_composition').css("background-color", "#e6c1c7"); document.getElementById('name_inn_text_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(quantity_composition  == ''){   $('#quantity_composition').css("background-color", "#e6c1c7"); document.getElementById('quantity_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(reason_inclusion_composition == ''){  $('#reason_inclusion_composition').css("background-color", "#e6c1c7"); document.getElementById('reason_inclusion_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(reference_standard_composition == ''){$('#reference_standard_composition').css("background-color", "#e6c1c7"); document.getElementById('reference_standard_composition').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}


           

var oTable = document.getElementById('table_product_compostion'),rIndex;
var count=oTable.rows.length;
if(oTable.rows.length >= 1)
{
var rowLength = oTable.rows.length;
var check_number_duplications = 0;
//loops through rows    
for (i = 1; i < rowLength; i++){
//gets cells of current row  
var oCells = oTable.rows.item(i).cells;
//gets amount of cells of current row
var cellLength = oCells.length;
//loops through each cell in current row
for(var j = 0; j < cellLength; j++)
{
       var name_inn  = oCells.item(j).innerHTML;
       console.log(name_inn_text_composition+name_inn);
  

  if( name_inn.toString()  ==  name_inn_text_composition.toString() )
 {
    Toastr();
    var alert ='Name (INN) product compostion for the medical product '+name_inn_text_composition+' is duplicated.'
   toastr.warning(alert);
   check_number_duplications++;
}
}
}
}

if(check_number_duplications==0)
{

 $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            composition_name: name_inn_text_composition,
                            reason: reason_inclusion_composition ,
                            reference_standard:reference_standard_composition,
                            type:composition_type_composition,
                            medical_product_id: product_detail_master_id,
                            quantity:quantity_composition,
                            composition_name:name_inn_text_composition,
                          application_id:application_id,
                             
                              },
                        url: "{{   url('/product_composition/save')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
                          
// document.getElementById('product_detail_master_id').value = data.productdetial_id;
// $('#createNewCompostion_save').hide();
// $('#createNewCompostion_update').show();

document.getElementById('next_composition').style.display = "block";
document.getElementById('renderd_product_composition_table').innerHTML = data.renderd_product_composition_table;
document.getElementById('id_update_compostion').innerHTML = data.Compostion_id;
            
                
                  document.getElementById('name_inn_text_composition').value = " ";
                  document.getElementById('quantity_composition').value =" ";
                  document.getElementById('reason_inclusion_composition').value = " ";
                  document.getElementById('reference_standard_composition').value =" " ;
                  document.getElementById('composition_type_composition').value =" " ;
Toastr();
toastr.success("Product composition saved successfully.")
 } 
                         else
                         {
                
               Toastr();
               var str = data.Message.errorInfo[2];
               var res = data.Message.errorInfo[2].replace("Column", "Field: ");

               toastr.error(res);

              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
                  }



                  });

//add_new_api_product_manufacturer

$('#add_new_api_product_manufacturer').click(function () {
var updated = document.getElementById('product_manufacturer_api_update');
var saved =   document.getElementById('product_manufacturer_api_save');
document.getElementById('manufacturer_api_name').value="";
document.getElementById('manufacturer_api_country').value="";
document.getElementById('manufacturer_api_postal_code').value="";
document.getElementById('manufacturer_api_tele').value="";
document.getElementById('manu_api_response_tele').innerHTML="";
document.getElementById('manufacturer_api_country').value="";
document.getElementById('manufacturer_api_city').value="";
document.getElementById('manufacturer_api_state').value="";
document.getElementById('manufacturer_api_add_line_one').value="";
document.getElementById('manufacturer_api_add_line_two').value="";
document.getElementById('id_for_update_api').value='';

document.getElementById('manufacturer_api_name_api').value="";
document.getElementById('manufacturer_api_unit').value="";
document.getElementById('manufacturer_api_block').value="";


updated.style.display = "none";
saved.style.display = "block";
id_for_update.style.display = "none";

});
              




$('#add_new_composition').click(function () {
                  document.getElementById('name_inn_text_composition').value = "";
                  document.getElementById('quantity_composition').value ="";
                  document.getElementById('reason_inclusion_composition').value = "";
                  document.getElementById('reference_standard_composition').value ="";
                  document.getElementById('composition_type_composition').value ="";
var updated = document.getElementById('createNewCompostion_update');
var saved = document.getElementById('createNewCompostion_save');
updated.style.display = "none";
saved.style.display = "block";
});
              

$('#add_new_manufacturer').click(function () {

document.getElementById('manufacturer_name').value="";
document.getElementById('manufacturer_country').value = "0";
document.getElementById('manufacturer_postal_code').value="";
document.getElementById('manufacturer_tele').value="";
document.getElementById('manufacturer_state').value="";
document.getElementById('manufacturer_add_line_one').value="";
document.getElementById('manufacturer_add_line_two').value="";
document.getElementById('manufacturer_activity').value="";
document.getElementById('manufacturer_block').value="";
document.getElementById('manufacturer_unit').value="";
document.getElementById('manufacturer_city').value= "";

var updated = document.getElementById('product_manufacturer_update');
var saved = document.getElementById('product_manufacturer_save');

updated.style.display = "none";
saved.style.display = "block";


});

//Clear Product Compostion Fron End
//Api manifacturere Table Insert Wizard

$('#save_product_manufacturer_api_save').click(function () {
                         
                         //From New Product Details
                        
                  var manufacturer_api_name  = document.getElementById('manufacturer_api_name').value;
                  var api_name  = document.getElementById('manufacturer_api_name_api').value;
                  var manufacturer_api_country = document.getElementById('manufacturer_api_country').value;
                  var manufacturer_api_postal_code =  document.getElementById('manufacturer_api_postal_code').value;
                  var manufacturer_api_tele =document.getElementById('manufacturer_api_tele').value ;
                  var manu_api_response_tele =document.getElementById('manu_api_response_tele').innerHTML ;
                  var manufacturer_api_city = document.getElementById('manufacturer_api_city').value ;
                  var manufacturer_api_state= document.getElementById('manufacturer_api_state').value ;
                  var manufacturer_api_add_line_one= document.getElementById('manufacturer_api_add_line_one').value ;
                  var manufacturer_api_add_line_two= document.getElementById('manufacturer_api_add_line_two').value ;
                 // var manufacture_api_website_url= document.getElementById('manufacture_api_website_url').value ;
                  //var manufacturer_api_email= document.getElementById('manufacturer_api_email').value ;
                  var api_block = document.getElementById('manufacturer_api_block').value;
                  var api_unit  = document.getElementById('manufacturer_api_unit').value;

                  var user_id = document.getElementById('user_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;
                  




if(manufacturer_api_name   == ''){$('#manufacturer_api_name').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(api_name   == ''){$('#manufacturer_api_name_api').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_name_api').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_country  == '' || manufacturer_api_country  == 0){$('#manufacturer_api_country').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_country').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_postal_code  == ''){$('#manufacturer_api_postal_code').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_postal_code').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_tele  == '' || manufacturer_api_tele  == 0 ){$('#manufacturer_api_tele').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manu_api_response_tele  == ''){$('#manu_api_response_tele').css("background-color", "#e6c1c7"); document.getElementById('manu_api_response_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_city  == ''){$('#manufacturer_api_city').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_city').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(manufacturer_api_state  == ''){$('#manufacturer_api_state').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_state').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_add_line_one  == ''){$('#manufacturer_api_add_line_one').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_add_line_one').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

if(api_block   == ''){$('#manufacturer_api_name').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_block').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(api_unit   == ''){$('#manufacturer_api_unit').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

var manufacturer_api_tele = manu_api_response_tele + manufacturer_api_tele;
                  
                  $.ajax({
                    
                        data:{ 

                            user_id:user_id,
                            manufacturer_name: manufacturer_api_name,
                            country_id: manufacturer_api_country ,
                            city:manufacturer_api_city,
                            state:manufacturer_api_state,
                            addressline_one: manufacturer_api_add_line_one,
                            addressline_two:manufacturer_api_add_line_two,
                            telephone:manufacturer_api_tele,
                            postal_code:manufacturer_api_postal_code,
                            application_id :application_id,
                            block:api_block,
                            unit:api_unit,
                            api_name:api_name,

                              },
                        url: "{{   url('/save_product_manufacturer_api/save')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
                          
//document.getElementById('product_detail_master_id').value = data.productdetial_id;
document.getElementById('product_manufacturer_api_next_button').style.display = "block";
document.getElementById('renderd_manufacturer_api_table').innerHTML = data.renderd_manufacturer_api_table;

                   document.getElementById('manufacturer_api_name').value = '';
                   document.getElementById('manufacturer_api_country').value = '';
                   document.getElementById('manufacturer_api_postal_code').value = '';
                   document.getElementById('manufacturer_api_tele').value = '';
                   document.getElementById('manu_api_response_tele').innerHTML = '';
                   document.getElementById('manufacturer_api_city').value = '';
                   document.getElementById('manufacturer_api_state').value= '' ;
                   document.getElementById('manufacturer_api_add_line_one').value = '';
                   document.getElementById('manufacturer_api_add_line_two').value = '';
                   document.getElementById('manufacturer_api_name_api').value = '';
                   document.getElementById('manufacturer_api_block').value = '';
                   document.getElementById('manufacturer_api_unit').value = '';
                   Toastr();
                   toastr.success("API manufacturer saved successfully.")
           } 
                         else
                         {
              Toastr();

              var str = data.Message.errorInfo[2];
               var res = data.Message.errorInfo[2].replace("Column", "Field: ");

               toastr.error(res);
              // toastr.error(data.Message.errorInfo)

              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });


















$('#save_product_manufacturer_api_update').click(function () {
                         
                         //From New Product Details
                        
                  var manufacturer_api_name  = document.getElementById('manufacturer_api_name').value;
                  var api_name  = document.getElementById('manufacturer_api_name_api').value;
                  var manufacturer_api_country = document.getElementById('manufacturer_api_country').value;
                  var manufacturer_api_postal_code =  document.getElementById('manufacturer_api_postal_code').value;
                  var manufacturer_api_tele =document.getElementById('manufacturer_api_tele').value ;
                  var manu_api_response_tele =document.getElementById('manu_api_response_tele').innerHTML ;
                  var manufacturer_api_city = document.getElementById('manufacturer_api_city').value ;
                  var manufacturer_api_state= document.getElementById('manufacturer_api_state').value ;
                  var manufacturer_api_add_line_one= document.getElementById('manufacturer_api_add_line_one').value ;
                  var manufacturer_api_add_line_two= document.getElementById('manufacturer_api_add_line_two').value ;
                 // var manufacture_api_website_url= document.getElementById('manufacture_api_website_url').value ;
                  //var manufacturer_api_email= document.getElementById('manufacturer_api_email').value ;
                  var api_block = document.getElementById('manufacturer_api_block').value;
                  var api_unit  = document.getElementById('manufacturer_api_unit').value;




                  var user_id = document.getElementById('user_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;
                  var manufacturer_api_tele = manu_api_response_tele + manufacturer_api_tele;
                  console.log()
                  var id_for_update_api = document.getElementById('id_for_update_api').innerHTML;

                  var updated = document.getElementById('product_manufacturer_api_update');
                 var saved =   document.getElementById('product_manufacturer_api_save');
                  
 if(manufacturer_api_name   == ''){$('#manufacturer_api_name').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_name').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(api_name   == ''){$('#manufacturer_api_name_api').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_name_api').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_country  == '' || manufacturer_api_country  == 0){$('#manufacturer_api_country').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_country').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_postal_code  == ''){$('#manufacturer_api_postal_code').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_postal_code').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_tele  == '' || manufacturer_api_tele  == 0 ){$('#manufacturer_api_tele').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manu_api_response_tele  == ''){$('#manu_api_response_tele').css("background-color", "#e6c1c7"); document.getElementById('manu_api_response_tele').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_city  == ''){$('#manufacturer_api_city').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_city').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
//if(manufacturer_api_state  == ''){$('#manufacturer_api_state').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_state').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(manufacturer_api_add_line_one  == ''){$('#manufacturer_api_add_line_one').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_add_line_one').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(api_block   == ''){$('#manufacturer_api_name').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_block').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}
if(api_unit   == ''){$('#manufacturer_api_unit').css("background-color", "#e6c1c7"); document.getElementById('manufacturer_api_unit').focus(); /*Toastr_validation();toastr.error('Fill Bussines Name');*/ return false;}

                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            //email:manufacturer_api_email,
                            //webiste_url:manufacture_api_website_url,
                            manufacturer_name: manufacturer_api_name,
                            country_id: manufacturer_api_country ,
                            city:manufacturer_api_city,
                            state:manufacturer_api_state,
                            addressline_one: manufacturer_api_add_line_one,
                            addressline_two:manufacturer_api_add_line_two,
                            telephone:manufacturer_api_tele,
                            postal_code:manufacturer_api_postal_code,
                            application_id :application_id,
                            id_for_update_api:id_for_update_api,
                            block:api_block,
                            unit:api_unit,
                            api_name:api_name,
                                      },
                        url: "{{   url('/save_product_manufacturer_api/update')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
              
                         if(data.Message == true)  
                         {
                          
//document.getElementById('product_detail_master_id').value = data.productdetial_id;
updated.style.display= 'none';
saved.style.display= 'block';

document.getElementById('renderd_manufacturer_api_table').innerHTML = data.renderd_manufacturer_api_table;

                   document.getElementById('manufacturer_api_name').value = '';
                   document.getElementById('manufacturer_api_country').value = '';
                   document.getElementById('manufacturer_api_postal_code').value = '';
                   document.getElementById('manufacturer_api_tele').value = '';
                   document.getElementById('manu_api_response_tele').innerHTML = '';
                   document.getElementById('manufacturer_api_city').value = '';
                   document.getElementById('manufacturer_api_state').value= '' ;
                   document.getElementById('manufacturer_api_add_line_one').value = '';
                   document.getElementById('manufacturer_api_add_line_two').value = '';
                   document.getElementById('manufacturer_api_name_api').value = '';
                   document.getElementById('manufacturer_api_block').value = '';
                   document.getElementById('manufacturer_api_unit').value = '';
                
                   Toastr();
                   toastr.info("Product API updated successfully.")
              
                         } 
                         else
                         {
              Toastr();
               toastr.error(data.Message.errorInfo)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });


$('#update_application_info').click(function () {

var user_id = document.getElementById('user_id').value ;
var application_id = document.getElementById('app_id').innerHTML ;
var application_type = document.getElementById('new_application_mode').value;



if(application_type == 1)
{
  var fast_track_details  =  '';
    application_type = '1';
    var progress_percentage = '10';


}
else
{
 
  var fast_data=application_type.split('_');
    fast_track_details  =  fast_data[1];
    application_type = fast_data[0];
    var progress_percentage = '20';
}

           $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            application_id:application_id,
                            application_type:application_type,
                            fast_track_details:fast_track_details,
                            progress_percentage:progress_percentage,

                            },
                        url: "{{   url('/application/update')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
    if(data.Message == true)  
{
$('#applicaion_updatee').show('10');
$('#appicaiton_save').hide('10');

Toastr();
toastr.success("Application updated successfully.")

 } 
                         else
                         {
              Toastr();
               toastr.error(data.Message.item)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });

});












$('#save_application_info').click(function () {
                         
  //From New Product Details 

document.getElementById('save_application_info').disabled = true;
var application_number =  document.getElementById('stegnant_application_number').value;
var old_app_id =  document.getElementById('old_app_id').value;

  var generated_value = document.getElementById('generated_application_id').value;
     if(generated_value == 0) {} else {return false;}
                        
//  if(document.getElementById('app_new_application').checked ==true) 
//  {     // var application_type= 'NewApplication';
   
      var application_type = document.getElementById('new_application_mode').value;
      var fast_track_details =document.getElementById('new_application_mode').value;
      var fast_data=fast_track_details.split('_');
       fast_track_details  =  fast_data[1];
       application_type = fast_data[0];
//   }

 if(document.getElementById('app_renewal_application').checked ==true) 
  {   var application_type = document.getElementById('new_application_mode').value;
      var fast_track_details =document.getElementById('new_application_mode').value;
      var fast_data=fast_track_details.split('_');
       fast_track_details  =  fast_data[1];
       application_type = fast_data[0];
  }

 if(document.getElementById('app_fast_track_mode').checked ==true) 
 {
      var application_type = document.getElementById('new_application_mode').value;
      var fast_track_details =document.getElementById('new_application_mode').value;
      var fast_data=fast_track_details.split('_');
       fast_track_details  =  fast_data[1];
       application_type = fast_data[0];
 }

 if(document.getElementById('app_variations').checked ==true) 
 {   
     var application_type = document.getElementById('new_application_mode').value;
      var fast_track_details =document.getElementById('new_application_mode').value;
      var fast_data=fast_track_details.split('_');
       fast_track_details  =  fast_data[1];
       application_type = fast_data[0];
 }
                  
    var user_id = document.getElementById('user_id').value ;
    var registration_type = "Re-new";
    var application_id = document.getElementById('old_app_id').value ;
               
                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            old_application_id:application_id,
                            application_type:application_type,
                            fast_track_details:fast_track_details,
                            re_registration_number:true,
                            application_number:application_number,
                            old_app_id:old_app_id,
                            registration_type:registration_type,

                            },
                        url: "{{   url('/application/save_re')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
    if(data.Message == true)  
{
$('#applicaion_updatee').show('10');
$('#appicaiton_save').hide('10');
$('#update_application_info').show('10');
// $('#next_button_application').show();
$('.app_recep_next').show();
$('#app_id').html(data.application_id);

 
//  document.getElementsByClassName("app_recep").style.display ="block";

document.getElementById('next_button_application').disabled = false;
document.getElementById('generated_application_id').value = data.application_id;

Toastr();
//toastr.success("Application has been initiated for re-registration.")



              var id = setInterval(dossier, 2000);
              function dossier() {
              //window.location = "/dossier_sample_status";
              
              clearInterval(id);
              }
              
                         } 
                         else
                         {
              Toastr();
               toastr.error(data.Message.item)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });



  







$('#dossier_sample_save').click(function () {
                         
                         //From New Product Details
                  var user_id = document.getElementById('user_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;

                 if(document.getElementById('LINK').checked == true)
                 {
                   var dossier_url_express = document.getElementById('dossier_id').value;
                   if(dossier_url_express ==''){document.getElementById('dossier_id').focus(); return false;}
                  //if(document.getElementById('sample_status').checked ==true) {var sample_status= 1;} else {var sample_status=0;}

                   }


                  if(document.getElementById('DHL').checked == true)
                  { 
                    var dossier_url_express = document.getElementById('express_settings_name').value;
                     if(dossier_url_express ==''){document.getElementById('express_settings_name').focus(); return false;}
                
                  }
               
                
                  
               
                  $.ajax({
                       data:{ 
                            user_id:user_id,
                            dossier_url:dossier_url_express,
                            // sample_status:sample_status,
                            application_id:application_id,
                            },
                        url: "{{   url('/dossier_status/save')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
               if(data.Message == true)  
                         {
$('#dossier_update').show();
$('#dossier_sample_save').hide('10');
$('#next_button_application').show();
//document.getElementById('next_button_application').disabled = false;

      Toastr();
              
              toastr.success("Dossier submitted successfully")
              var id = setInterval(dossier, 2000);
              function dossier() {
              window.location = "/dossier_sample_status";
              clearInterval(id);
              }
              
                         } 
                         else
                         {
                Toastr();
               toastr.error(data.Message.item)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });  

//dossier_sample_update



$('#dossier_sample_update').click(function () {
                         
//                          //From New Product Details
//  if(document.getElementById('sample_status').checked ==true) {var sample_status= 1;} else {var sample_status=0;}
//                            //From New Product Details

                   var user_id = document.getElementById('user_id').value ;
                  var application_id = document.getElementById('generated_application_id').value;

                 if(document.getElementById('LINK').checked == true)
                 {
                   var dossier_url_express = document.getElementById('dossier_id').value;
                   if(dossier_url_express ==''){document.getElementById('dossier_id').focus(); return false;}
                  //if(document.getElementById('sample_status').checked ==true) {var sample_status= 1;} else {var sample_status=0;}

                   }


                  if(document.getElementById('DHL').checked == true)
                  { 
                    var dossier_url_express = document.getElementById('express_settings_name').value;
                     if(dossier_url_express ==''){document.getElementById('express_settings_name').focus(); return false;}
                
                  }

                   
               
                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            dossier_url:dossier_url_express,
                            // sample_status:sample_status,
                            application_id:application_id,
                            },
                        url: "{{   url('/dossier_status/update')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
               if(data.Message == true)  
                         {
$('#dossier_update').show();
$('#dossier_sample_save').hide('10');
$('#next_button_application').show();
//document.getElementById('next_button_application').disabled = false;
  $('#print_decla').attr("href", data.Download_Link);
            
              Toastr();
              toastr.info("Dossier  Updated Successfuly")

              var id = setInterval(dossier, 2000);
              function dossier() {
              window.location = "/dossier_sample_status";
              clearInterval(id);
              }
                         } 
                         else
                         {
               Toastr();
               toastr.error(data.Message.item)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });



$('#customCheckbox1').click(function () {

var i_agree = document.getElementById('customCheckbox1');
if(i_agree.checked==true)

{
  document.getElementById('decleration_id').style.display = 'block';
}
else if(i_agree.checked==false)

{
  document.getElementById('decleration_id').style.display = 'none';
}



});





$('#decleration_save').click(function () {
                         
                         //From New Product Details  decleration_name
                  var user_id     = document.getElementById('user_id').value ;
                  var qualification = document.getElementById('qualification').value;
                  var position = document.getElementById('position_in_the_company').value;
                  var date = document.getElementById('Date_decleration').value;
                  var name = document.getElementById('decleration_name').value;
                  var application_id = document.getElementById('generated_application_id').value;

               if(qualification=='' || position==''  || date=='' || name=='')
               {
                Toastr();
                toastr.error("Fill all Fields correctly")
          return false;
               }


                  $.ajax({
                        //data: $('#bookForm').serialize(),
                        data:{ 
                            user_id:user_id,
                            qualification:qualification,
                            position:position,
                            date:date,
                            name:name,
                            application_id:application_id,
                            },
                        url: "{{   url('/decleration_save/save')   }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
               if(data.Message == true)  
{
// $('#dossier_update').show(); 
// $('#dossier_sample_save').hide('10'); 
$('#next_button_application').show();
document.getElementById('next_button_application').disabled = false;


 $('#print_decla').attr("href", data.Download_Link_two);


Toastr();
toastr.success("Declaration  Saved Successfully")
              
         

              document.getElementById('decleration_id').style.display = 'none';
              document.getElementById('decleration_on__update').style.display = 'block';
              document.getElementById('next_button_dec').style.display = 'block';
              
              
                         } 
                         else
                         {
                           
              Toastr();
               toastr.error(data.Message.item)
              }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
              
                      
                    });
                  
              
                  });


$('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
    
        $.ajax({
          data: $('#bookForm').serialize(),
          url: "{{ route('books.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
   
              $('#bookForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });

  });


  $(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    $('.swalDefaultSuccess').click(function() {
      Toast.fire({
        icon: 'success',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultInfo').click(function() {
      Toast.fire({
        icon: 'info',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultError').click(function() {
      Toast.fire({
        icon: 'error',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultWarning').click(function() {
      Toast.fire({
        icon: 'warning',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultQuestion').click(function() {
      Toast.fire({
        icon: 'question',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });

    $('.toastrDefaultSuccess').click(function() {
      toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultInfo').click(function() {
      toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });

    $('.toastrDefaultError').click(function() {
      toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });

    $('.toastrDefaultWarning').click(function() {
      toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });

    $('.toastsDefaultDefault').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultTopLeft').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'topLeft',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultBottomRight').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'bottomRight',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultBottomLeft').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'bottomLeft',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultAutohide').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        autohide: true,
        delay: 750,
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultNotFixed').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        fixed: false,
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultFull').click(function() {
      $(document).Toasts('create', {
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        icon: 'fas fa-envelope fa-lg',
      })
    });
    $('.toastsDefaultFullImage').click(function() {
      $(document).Toasts('create', {
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        image: '../../dist/img/user3-128x128.jpg',
        imageAlt: 'User Picture',
      })
    });
    $('.toastsDefaultSuccess').click(function() {
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultInfo').click(function() {
      $(document).Toasts('create', {
        class: 'bg-info',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultWarning').click(function() {
      $(document).Toasts('create', {
        class: 'bg-warning',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultDanger').click(function() {
      $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultMaroon').click(function() {
      $(document).Toasts('create', {
        class: 'bg-maroon',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
  });


  function Email_Validate(Email,url,xmlxhttp_response_id,save_id,crr_email) {


var warning = xmlxhttp_response_id +"_warning";
var danger = xmlxhttp_response_id +"_danger";
var success = xmlxhttp_response_id +"_success";

if (Email != '') {



 jQuery(document).ready(function() {
     $.ajaxSetup({
           headers: {
           'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                                 });

                                // alert(url);
    jQuery.ajax({
           url: url,
           method: 'post',
           data: {
               Email: jQuery('#'+crr_email).val(),
           },
           success: function(result) {
             jQuery('#'+danger).hide('100');
               if (result.error == undefined) {
              var id = setInterval(validate, 20);
             function validate() {
             if ((Email.indexOf("@") < 1) || ((Email.indexOf("@") == (Email.length < 1)))) {
                           jQuery('#'+warning).show('100');
                           jQuery('#'+warning).html('Invalid Email Axerate Symbol is Absent');
                           //document.getElementById(crr_email).focus();
                           // jQuery('#'+save_id).addClass("error");
                           // jQuery('#'+save_id).text('Invalid Email Axerate Symbol is Absent');
                           document.getElementById(save_id).disabled = true;
                           jQuery('#'+save_id).hide('100');
                   
                           clearInterval(id);
                           return false
                       }

                       if (Email.indexOf("@") <= 2) {
                           jQuery('#'+warning).show('100');
                           jQuery('#'+warning).html('Email length should be at least 3 charcters long');
                          // document.getElementById(crr_email).focus();
                          // jQuery('#'+save_id).addClass("error");
                           jQuery('#'+save_id).hide('100');
                           document.getElementById(save_id).disabled = true;
                           clearInterval(id);
                           return false
                       }

                       if ((Email.indexOf(".") == -1)) {
                           document.getElementById(save_id).disabled = true;
                           jQuery('#'+save_id).hide('100');
                           jQuery('#'+warning).show('100');
                           jQuery('#'+warning).html('Invalid Email (.) Symbol is Absent');
                           //document.getElementById(crr_email).focus();
                           clearInterval(id);
                           return false
                       }

                       if ((Email.indexOf("@") > 1) || ((Email.indexOf("@") != (Email.length < 1)) || (Email.indexOf(".") != -1)))
                        {
                           document.getElementById(save_id).disabled = false;
                           jQuery('#'+save_id).show('100');
                           jQuery('#'+warning).hide('100');
                           //document.getElementById(save_id).focus();
                           clearInterval(id);
                       }



                   }

               } else {
                   jQuery('#'+danger).show('100');
                   jQuery('#'+danger).html(result.error);
                   $("#"+danger).animate({ right: '15px' });
                   jQuery('#'+success).hide('100');
                   jQuery('#'+warning).hide('100');
                   //document.getElementById(save_id).disabled = true;
                  // jQuery('#'+save_id).hide('100');


               }
           },
       });

   });

} else {

   //jQuery('#'+danger).hide('50');
   jQuery('#'+warning).hide('50');
   //jQuery('#'+success).hide('50');
}

}


  function isValidURL(string) {
   var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
   return (res !== null)
  };

  function validURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return !!pattern.test(str);
}



//Section To Validate URL 
function valdiate_url(url_string,xmlxhttp_response_id,url,action_button) {


 var warning = xmlxhttp_response_id +"_warning";
 var danger = xmlxhttp_response_id +"_danger";
 var success = xmlxhttp_response_id +"_success";


if (url_string != '') {
 

   url_string = validURL(url_string);

           

              var id = setInterval(validate, 500);
              function validate() {
           if(url_string == false)
            {
           //document.getElementById(response_id).innerHTML = result.Code;
           jQuery('#'+danger).show();
           jQuery('#'+success).hide();
           jQuery('#'+danger).html('Invalid Web URL(e.g www.google.com)');
           clearInterval(id);
           document.getElementById(action_button).disabled = true;
           jQuery('#'+action_button).hide('100');
            }

           else if(url_string == true)
            {
        //jQuery('#'+success).show();
        jQuery('#'+danger).hide();
      // jQuery('#'+success).html(result.success);
       clearInterval(id);
       document.getElementById(action_button).disabled = false;
       jQuery('#'+action_button).show('100');
            }

      
                    }
} 

else{
  jQuery('#'+danger).hide();
  jQuery('#'+success).hide();
  document.getElementById(action_button).disabled = false;
  jQuery('#'+action_button).show('100');

}

}




 $('#new_applicant_update').click(function () {
    if( document.getElementById('new_applicant_update').checked==true) 
    {var m = true;console.log(m);}
    $('#General_Supplier').show();
    $('#cs_tradename_exits').hide();
    $('#cs_save').show();
    $('#cs_trade_name').show();
   

    
    
    document.getElementById('css_country').disabled=false;
    document.getElementById('cs_city').disabled=false;
    document.getElementById('cs_state').disabled=false;
    document.getElementById('cs_address_line_one').disabled=false;
    document.getElementById('cs_address_line_two').disabled = false;
    document.getElementById('cs_email').disabled = false;
    document.getElementById('cs_tele').disabled = false;
    document.getElementById('cs_website_url').disabled = false;
    /*
     //Set Value to Null
     // Set Element  to Disabled  
    document.getElementById('css_country').value='';
    document.getElementById('cs_city').value = '';
    document.getElementById('cs_state').value = '';
    document.getElementById('cs_address_line_one').value = '';
    document.getElementById('cs_address_line_two').value = '';
    document.getElementById('cs_email').value = '';
    document.getElementById('cs_tele').value = '';
    document.getElementById('cs_website_url').value = '';
*/


});  
    
    $('#old_applicant_update').click(function () 
    {
    $('#application_wizard').show();
    $('#cs_tradename_exits').show();
    $('#cs_trade_name').hide();
    $('#cs_save').show();



    });


</script>



