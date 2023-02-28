<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script rel="javascript" src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script>
<script rel="javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}" ></script>


  <link rel="stylesheet" href="../../maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="../../ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="../../maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

  
<div  class="modal fade" id="ajax_model_show_details" aria-hidden="true" data-backdrop="static" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading"></h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
    
<div class="container">

  <div class="panel-group">
    <div class="panel panel-default">
      <div class="panel-heading"> <h5>  Application letter  </h5> </div>
<div class="panel-body">
<p> Please attach here an official dated and signed letter from the manufacturer/license holder of the medical product. The letter should be addressed to the National Medicines and Food Administration.</p></div>
</div>

<div class="panel panel-primary">
<div class="panel-heading"> <h5>  Application Form  </h5>  </div>
<div class="panel-body">
<h6> 1.1 Name and address of applicant  </h6>
<h6> Contact person   </h6>
<p>
Please ensure that the contact person you have listed is an active employee of the company. The contact person will be responsible for all the communications between the NMFA and the company. 
</p>


<h6> 1.2 Name and address of the local agent   </h6>
<p>
Please ensure that the Local agent you have appointed is an agent authorized by the Eritrean National Medicines and Food Administration. 
</p>

<p>If you currently don’t have a local agent, please visit the list of Local Authorized Agents in Eritrea along with their full company addresses.  </p>

<p> Local Agent contact person </p>

<p> Please ensure that the contact person you have listed is an active employee of the company.   </p>

<p>  Visit Guidance Document for the Authorization of Local Agents.   </p>


<h6> 3.3 Product Manufacturer  </h6>
<h6> 3.3.1 Name and complete address(es) of the manufacturer(s) of the FPP  </h6>
<p>
Where different activities of manufacture of the given product are carried out at different manufacturing sites, the above particulars shall be provided for each site and the activity carried out at the particular site shall be stated. 
</p>

<h6> 3.3.2 Name and complete address(es) of the manufacturer(s) of the API(s)  </h6>
<p>
Please state the name and complete address of each facility where manufacture (synthesis, production) of API occurs, including contractors.
</p>
<p> Please describe the activities of each proposed production site or facility involved in manufacturing and testing, and include any alternative manufacturers. </p>

<h6> Availability of Product in the Eritrean National List of Medicines(ENLM) </h6>
<p>
Please ensure that the pharmaceutical product to be registered is listed in the Eritrean National List of Medicines (ENLM). 
</p>

<p>
If the product is not listed in the ENLM, please provide the generic name, dosage form and strength of the product.
</p>

<h6> Dossiers  </h6>
<p>
Please visit Link xxx before you submit your dossiers.
Please select an option for dossier submission.
Options: Online or Other such as USB drive, CD/DVD…etc.
Please 'zip' the entire dossier and upload it here as a single attachment
</p>


<h6> Samples  </h6>
<p>
Please visit link xxx for information on how to send your samples.
Please attach the way bill of the sent samples to the NMFA. 

</p>

<h6> Payment of registration fees  </h6>
<p>
Please visit link xxx (with bank details) for information on how to pay for the registration fees.
Please attach the payment swift copy here.

</p>

      </div>
    </div>


  </div>
</div>


           
        </div>
    </div>

        </div>
    </div>










<script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });


    
    $('#modal_show_detail_information').click(function () {
       
        $('#modelHeading').html("Field Instructions for the Submission of Electronic Application forms ");
        $('#ajax_model_show_details').modal('show');
    });




  });
</script>




