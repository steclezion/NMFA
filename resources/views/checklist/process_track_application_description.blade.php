@extends('layouts.app')

@section('content')  





<meta name="csrf-token" content="{{ csrf_token() }}">

   
   <div class="row">
          <div class="col-12" id="print_checklist" >
            <!-- <div class="card"  > -->
              <div class="card-header">
                <h3>Completed Application Details </h3><br><br>
                <!-- <h3 class="pull-left"> Preliminary Screening Checklist </h3> -->
                <!-- <div class="card"> -->
        
              <div class="container-fluid" >
        <!-- Section one product details  -->
        <div class="card card-outline  card-primary  collapsed-card " >
          <div class="card-header">
          <h3 class="card-title">Section 1: Application Type</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" disabled value= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
            </div>
          </div>
          <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                    <!-- <tr>
                    <th>Lists</th>
                      <th> </th>
                      <!-- <th style="width: 40px">check Status</th> -
                    </tr> -->
                  </thead>
                  <tbody>
                  <!-- <tr>
            <td>Application ID</td>
                <td>
                @foreach($check_list as $application_id )
               {{  $application_id->application_id }}
               @break
               @endforeach
                </td>
                <td><span class="badge bg-success"><i class="fa fa-check"></i></span></td>
                   </tr> -->
                    <tr>
                    <td>Application Number</td>
                      <td >{{ $font_product_name='' }}
                      @foreach( $check_list as $product_name )
                     {{  $product_name->application_number }}
                       @break
                      @endforeach
                      </td>
                      <!-- <td> @if($product_name->product_name == '') 
                     $font_product_name='<span class="badge bg-danger"><i class="fa fa-minus"></i></span>'
                      @else 
                     <span class="badge bg-success"><i class="fa fa-check"></i></span> 
                     @endif </td> -->
                    </tr>
                    <tr>
                      <td>Application Type</td>
                      <td>
                      @foreach($check_list as $product_trade_name )
                       @if($product_trade_name->application_type   == 1) Standard Mode @else Fast Track Mode / {{ $product_trade_name->fast_track_details }} @endif
                      @break
                      @endforeach
                      </td>
                      </tr>
                      </tbody>
                </table>
              </div>
            
            </div>
               </div>
              <!-- /.card-header -->
              <style>
                  th,td {padding: 15px;text-align: left;border: 0.2px solid grey;border-bottom: 1px solid #ddd;}
                  tr { border: 1px dashed black;}
               </style>



<!-- Section Tw o  Company Supplier information -->


          
           
              <div class="container-fluid" >
        <!-- Section one product details  -->
        <div class="card card-outline  card-primary  collapsed-card" >
          <div class="card-header">
          <h3 class="card-title">Section 2: Company Supplier Information</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" disabled disabledvalue= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
            </div>
          </div>
          <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                    <!-- <tr>
                    <th>Lists</th>
                      <th> </th>
                      <!-- <th style="width: 40px">check Status</th> -
                    </tr> -->
                  </thead>
                  <tbody>
                  <!-- <tr>
            <td>Application ID</td>
                <td>
                @foreach($check_list as $application_id )
               {{  $application_id->application_id }}
               @break
               @endforeach
                </td>
                <td><span class="badge bg-success"><i class="fa fa-check"></i></span></td>
                   </tr> -->
            <tr>
                    <td>Application Trade Name</td>
                      <td >{{ $font_product_name='' }}
                      @foreach( $check_list as $product_name )
                     {{  $product_name->tname}}
                       @break
                      @endforeach
                      </td>
            </tr>

                    <tr>
                      <td>Country</td>
                      <td>
                      @foreach($company_supplier_info_country  as $country_supplier )
                   {{ $country_supplier->country_name }}
                      @break
                      @endforeach
                      </td>
            </tr>
            <tr>
                      <td>State</td>
                      <td>
                      @foreach($check_list as $customer_state )
                           {{ $customer_state->company_supplier_state }}
                      @break
                      @endforeach
                      </td>  </tr>


                      <tr><td>Address Line One</td>
                      <td>
                      @foreach($check_list as $customer_address_line_one )
                           {{ $customer_address_line_one->company_supplier_address_line_one }}
                      @break
                      @endforeach
                      </td>  </tr>

                      <tr><td>Address Line Two</td>
                      <td>
                      @foreach($check_list as $customer_address_line_one )
                           {{ $customer_address_line_one->company_supplier_address_line_two }}
                      @break
                      @endforeach
                      </td>  </tr>

                      <tr><td>Institutional Email</td> 
                      <td>
                      @foreach($check_list as $product_trade_name )
                      {{ $customer_address_line_one->cs_email }}
                      @break
                      @endforeach
                      </td>  </tr>


                        <tr> <td>Postal Address</td>
                      <td>
                      @foreach($check_list as $product_trade_name )
                      {{ $customer_address_line_one->cs_postal_code }}
                      @break
                      @endforeach
                      </td>   </tr>


                      <tr><td>Web URL</td>
                      <td>
                          @foreach($check_list as $cs_web )
                        {{ $cs_web->cs_webiste_url }}
                             @break
                           @endforeach
                      </td>  </tr>


                      <tr><td>Contact First Name</td>
                      <td>
                      @foreach($check_list as $name)
                      {{ $name->con_first_name }}                    
                        @break
                      @endforeach
                      </td>  </tr>

                    <tr><td>contact Middle Name</td>
                      <td>
                      @foreach($check_list as $name )
                      {{ $name->con_middle_name }}  
                    @break
                      @endforeach
                      </td>  </tr>

                    <tr><td>Contact Last Name</td>
                      <td>
                      @foreach($check_list as $name )
                      {{ $name->con_last_name }} 
                      @break
                      @endforeach
                      </td>  </tr>

                     <tr><td>Contact's  Position</td>
                      <td>
                      @foreach($check_list as $position )
                      {{ $position->con_position }}
                      @break
                      @endforeach
                      </td>  </tr>

                      <tr><td>Contact's City</td>
                      <td>
                      @foreach($check_list as $city_ )
                      {{ $city_->con_city }}

                      @break
                      @endforeach
                      </td>  </tr>

                      <tr><td>Contact's Address Line One</td>
                      <td>
                      @foreach($check_list as $contacts_address_line )
                      {{ $contacts_address_line->contacts_address_line_one }}
                      @break
                      @endforeach
                      </td>  </tr>

                    <tr><td>Contact's Address Line Two</td>
                      <td>
                      @foreach($check_list as $contacts_address_line )
                      {{ $contacts_address_line->contacts_address_line_two }}
                      @break
                      @endforeach
                      </td>  </tr>

                      <tr>
                      <td>Contact's Telephone</td>
                      <td>
                      @foreach($check_list as $contacts  )
                      
                      {{ $contacts->contacts_telephone}}

                      @break
                      @endforeach
                      </td> 
                      </tr>



                      </tr>
                      </tbody>
                </table>
              </div>

               </div>
            



            <!--       Section 3               -->

              <div class="card card-outline  card-primary  collapsed-card" >
          <div class="card-header">
          <h3 class="card-title">Section 3: Agent Information</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" disabled disabledvalue= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
            </div>
          </div>
          <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                    <!-- <tr>
                    <th>Lists</th>
                      <th> </th>
                      <!-- <th style="width: 40px">check Status</th> -
                    </tr> -->
                  </thead>
                  <tbody>

                  @foreach( $agent_contact_info as $agent_contact_info_list ) @endforeach
            
            <tr><td>Applicant Name</td><td >{{ $agent_contact_info_list->ag_trade_name }}</td></tr>
            <tr><td>State</td><td >{{ $agent_contact_info_list->ag_state }}</td></tr>
            <tr><td>Address Line One</td><td >{{ $agent_contact_info_list->ag_address_line_one }}</td></tr>
            <tr><td>Address Line Two</td><td >{{ $agent_contact_info_list->ag_address_line_two }}</td></tr>
            <tr><td>City</td><td >{{ $agent_contact_info_list->ag_city }}</td></tr>
            <tr><td>Postal Address</td><td >{{ $agent_contact_info_list->ag_postal_code  }}</td></tr>
            <tr><td>Telephone</td><td >{{ $agent_contact_info_list->ag_country_code."".$agent_contact_info_list->ag_telephone }}
            </td></tr>
            <tr><td>Web Address</td><td >{{ $agent_contact_info_list->ag_webiste_url }}</td></tr>
           
           
           
            <tr><td>Instituional Email</td><td >{{ $agent_contact_info_list->ag_email }}</td></tr>
            <tr><td>Contacts Full Name</td><td >{{ $agent_contact_info_list->con_first_name ." ".$agent_contact_info_list->con_middle_name." ".$agent_contact_info_list->con_last_name }}</td></tr>
            <tr><td>Contact's City</td><td >{{ $agent_contact_info_list->con_city }}</td></tr>
            <tr><td>Contact's Position</td><td >{{ $agent_contact_info_list->con_position }}</td></tr>
            <tr><td>Contacts Address Line One</td><td >{{ $agent_contact_info_list->con_address_line_one }}</td></tr>
            <tr><td>Contacts Address Line Two</td><td >{{ $agent_contact_info_list->con_address_line_two }}</td></tr>
            <tr><td>Contacts Telephone</td><td >{{ $agent_contact_info_list->con_telephone }}</td></tr>


                    

                  
                      </tbody>
                </table>
              </div>
              </div>




<!---------------------------------- Section 4 -->

            <!--       Section 4               -->

              <div class="card card-outline  card-primary  collapsed-card" >
          <div class="card-header">
          <h3 class="card-title">Section 4: Product Details</h3>
         
               <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
             
            </div>
          </div>
          <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
               
                  <tbody>
                  @foreach($check_list as $application_id )
          <input hidden type="text" value= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
          </div>
              <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                  <thead>
                 
                  </thead>
                  <tbody>

            <tr><td>Generic Name</td><td > {{ $font_product_name='' }}@foreach( $check_list as $product_name ) {{  $product_name->product_name }} @break @endforeach </td></tr>
            <tr><td>Brand Name</td><td>    @foreach($check_list as $product_trade_name ) {{  $product_trade_name->product_trade_name     }} @break @endforeach</td></tr>
            <!-- <tr><td>Strength</td><td>@foreach( $check_list as $product_name )<input name="strength"  class="form-control" value="{{$product_name->product_name }}" type="" />@break @endforeach </td> </tr> -->
            <tr><td {{ $i=1 }}>Pharmaceutical form </td> <td>@foreach ($dosage_forms as $dosage_formss)@endforeach{{  $dosage_formss->name   }}</td></tr>
            <tr><td {{ $i=1 }}>Manufacturer/Market Authorization Holder   </td> <td> @foreach ($applicant_contact_info as $supplier_name){{  $supplier_name->first_name." ". $supplier_name->middle_name ." ".$supplier_name->last_name   }}<br> @endforeach </td></tr>
          


            <tr><td {{ $i=1 }}>Route of Administration </td> <td>@foreach ($dosage_forms as $dosage_formss){{  $dosage_formss->name   }}<br>@endforeach</td></tr>
            <tr><td {{ $i=1 }}>Storage Condition   </td> <td> @foreach ($applicant_contact_info as $supplier_name){{  $supplier_name->first_name." ". $supplier_name->middle_name ." ".$supplier_name->last_name   }}<br> @endforeach </td></tr>
          
            <tr><td {{ $i=1 }}>Shelf life Amount </td> <td>@foreach ($dosage_forms as $dosage_formss){{  $dosage_formss->name   }}<br>@endforeach</td></tr>
            <tr><td {{ $i=1 }}>Pharmacotherapeutic Classification (Anatomic-Therapeutic Classification system):   </td> <td> @foreach ($applicant_contact_info as $supplier_name){{  $supplier_name->first_name." ". $supplier_name->middle_name ." ".$supplier_name->last_name   }}<br> @endforeach </td></tr>
          
            <tr><td {{ $i=1 }}>Proposed Shelf Life Amount </td> <td>@foreach ($dosage_forms as $dosage_formss){{  $dosage_formss->name   }}<br>@endforeach</td></tr>
            <tr><td {{ $i=1 }}>Proposed Shelf Life After Reconstitution Amount   </td> <td> @foreach ($applicant_contact_info as $supplier_name){{  $supplier_name->first_name." ". $supplier_name->middle_name ." ".$supplier_name->last_name   }}<br> @endforeach </td></tr>
          



            <tr><td {{ $i=1 }}>Visual Description </td> <td>@foreach ($dosage_forms as $dosage_formss){{  $dosage_formss->name   }}<br>@endforeach</td></tr>
            <tr><td {{ $i=1 }}>Commercial Presentation   </td> <td> @foreach ($applicant_contact_info as $supplier_name){{  $supplier_name->first_name." ". $supplier_name->middle_name ." ".$supplier_name->last_name   }}<br> @endforeach </td></tr>
          

            <tr><td {{ $i=1 }}>Container, closure and administration devices: </td> <td>@foreach ($dosage_forms as $dosage_formss){{  $dosage_formss->name   }}<br>@endforeach</td></tr>
            <tr><td {{ $i=1 }}>Packaging and pack size   </td> <td> @foreach ($applicant_contact_info as $supplier_name){{  $supplier_name->first_name." ". $supplier_name->middle_name ." ".$supplier_name->last_name   }}<br> @endforeach </td></tr>
          

            <tr><td {{ $i=1 }}>Category of Use </td> <td>@foreach ($dosage_forms as $dosage_formss){{  $dosage_formss->name   }}<br>@endforeach</td></tr>
          

              </tbody>
              </table>
              </div>

              </div>

   <!--       Section 5               -->
          <div class="card card-outline  card-primary  collapsed-card" >
          <div class="card-header">
          <h3 class="card-title">Section 5: Product Composition</h3>
  
@foreach($check_list as $application_id ) <input hidden type="text" disabled disabledvalue= "{{ $application_id->application_id }}"  id="app_id"/> @endforeach
            <div class="card-tools">
    <button type="button" class="btn btn-tool" data-card-widget="collapse">
    <i class="fas fa-plus"></i> </button></div>
          </div>
          <div class="card-body">
<table class="table table-bordered  table-condensed table-striped">
<thead></thead> <tbody>


<tr>
<td>Composition Name</td> 
<td>Quantity</td>
<td>Reason</td>
<td>Reference Standard</td>
<td>Type</td>
</tr>

@foreach($product_composition_info as $compose)
<tr>
<td >{{ $compose->composition_name }}</td>
<td>{{ $compose->quantity }}</td>
<td> {{ $compose->reason }}</td>
<td> {{ $compose->reference_standard}}</td>
<td> {{ $compose->type}}</td>

</tr>
@endforeach

</tbody>
                </table>
              </div>
              </div>








            <!--       Section 6               -->

              <div class="card card-outline  card-primary  collapsed-card" >
          <div class="card-header">
          <h3 class="card-title">Section 6: Product Manufacturers</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" disabled disabledvalue= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
            </div>
          </div>
          <div class="card-body">
                
<table class="table table-bordered  table-condensed table-striped">
<tbody>
<tr>
<td>Manufactures Name</td> 
<td>City</td>
<td>State</td>
<td>Address</td>
<td>Postal Address</td>
<td>Telephone</td>
<td>Activity </td>
<td>Block </td>
<td> Unit  </td>
</tr>

@foreach($check_list as $manu_info) @endforeach
<tr>
<td >  {{ $manu_info->manufacturer_name }}    </td>
<td>   {{  $manu_info->manufacturer_city }}    </td>
<td>   {{ $manu_info->manufacturer_state }}   </td>
<td>   {{ $manu_info->manufacturer_address_line_one ." ". $manu_info->manufacturer_address_line_two }}</td>
<td>   {{ $manu_info->manufacturer_postal_code }} </td>
<td>   {{ $manu_info->manufacturer_telephone }} </td>
<td>   {{ $manu_info->manufacturer_activity }}  </td>
<td>   {{ $manu_info->manufacturer_block }}  </td>
<td>   {{ $manu_info->manufacturer_unit}}  </td>
</tr>

</tbody>
</table>
</div>
</div>



            <!--       Section 7               -->

              <div class="card card-outline  card-primary  collapsed-card" >
          <div class="card-header">
          <h3 class="card-title">Section 7: API Product Manufacturers</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" disabled disabledvalue= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
            </div>
          </div>
          <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                 
                   
                  <tbody>
      
    <tr>
<td> API Product Manufatures</td> 
<td>City</td>
<td>State</td>
<td>Address</td>
<td>Postal Address</td>
<td>Telephone</td>

</tr>

@foreach($api_manufacturers_info  as $api_manu_info)
<tr>
<td >  {{ $api_manu_info->manufacturer_name }}    </td>
<td >  {{ $api_manu_info->city }}    </td>
<td >  {{ $api_manu_info->state }}    </td>
<td >  {{ $api_manu_info->addressline_one."".$api_manu_info->addressline_two }}    </td>
<td >  {{ $api_manu_info->postal_code }}    </td>
<td >  {{ $api_manu_info->telephone }}    </td>

</tr>

@endforeach


                      </tbody>
                </table>
              </div>
              </div>








            <!--       Section 6               -->

              <div class="card card-outline  card-primary  collapsed-card" >
          <div class="card-header">
          <h3 class="card-title">Section 8: Declaration</h3>
          @foreach($check_list as $application_id )
          <input hidden type="text" disabled disabledvalue= "{{ $application_id->application_id }}"  id="app_id"/>
               @endforeach
         
             <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button> -->
            </div>
          </div>
          <div class="card-body">
                
                <table class="table table-bordered  table-condensed table-striped">
                 
                  <tbody>
                
           
                  </tbody>

                  <div class="card-body">
<p class="decleration"> 

        @foreach (  $product_details as $key => $value_name) @endforeach
        @foreach ($dosage_forms as $key => $value_dosage) @endforeach
        @foreach($company_suppliers as $value_company) @endforeach


<p class="decleration"> 
I, the undersigned certify that all the information in this form and all accompanying documentation submitted to Eritrea for the
registration of ({{ $value_name->product_name }}, {{ $value_name->strength_amount_strength_unit }} and {{ $value_dosage->name }})
manufactured at ({{ $value_company->trade_name }} , {{ $value_company->address_line_one }} and  {{ $value_company->address_line_two }}) is true and correct. I further certify that I have examined the following statements and I attest to their correctness:- 


</P>

<p class="decleration">
1.	The current edition of the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products or equivalent guideline is applied in full in all premises involved in the manufacture of this medicine. 
<br/>
2.	The formulation per dosage form correlates with the master formula and with the batch manufacturing record. 
<br/>
3.	The manufacturing procedure is exactly as specified in the master formula and batch manufacturing record.
<br/>
4.	Each batch of all starting materials is either tested or certified (in accompanying certificate of analysis for that batch) against the full specifications in the accompanying documentation and must comply fully with those specifications before it is released for manufacturing purposes. 
<br/>
5.	All batches of the active pharmaceutical ingredient(s) are obtained from the source(s) specified in the accompanying documentation. 
<br/>
6.	No batch of active pharmaceutical ingredient(s) will be used unless a copy of the batch certificate established by the manufacturer is available. 
<br/>
7.	Each batch of the container/closure system is tested or certified against the full specifications in the accompanying documentation and complies fully with those specifications before released for the manufacturing purposes. 
<br/>
8.	Each batch of the finished product is either tested, or certified (in an accompanying certificate of analysis for that batch), against the full specifications in the accompanying documentation and complies fully with release specifications before released for sale. 
<br/>
9.	The person releasing the product is an authorized person as defined by the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products
<br/>
10.	The procedures for control of the finished product have been validated. The assay method has been validated for accuracy, precision, specificity and linearity. 
<br/>
11.	All the documentation referred to in this application is available for review during GMP inspection. 
<br/>
12.	Clinical trials (where applicable) were conducted in accordance with ICH, WHO or equivalent guidelines for Good Clinical Practice, 
<br/>
I also agree that: 
<br/>
13.	As a holder of marketing authorization/registration of the product I will adhere to Eritrean National Pharmacovigilance Policy requirements for handling adverse reactions. 
<br/>
14.	As holder of registration I will adhere to Eritrean requirements for handling batch recalls of the products.
<br/>
            </p>
          
            <div class="row">
            <div class="col-sm-3">
            <form>
                <div class="card-body">
            <div class="form-check">
         
            <label class="form-check-label" for="exampleCheck1"><b> I agree  </b></label>
            <input type="checkbox"  checked style="position:relative;left:18%" class="form-check-input" id="customCheckbox1">
                  </div>
                  </div>
            </div>
         
         <div class="col-sm-3 no-print">
<!-- <label>I agree <input style="width:20px;position:float-center;" class="form-control" id="customCheckbox1" type="checkbox" name="customCheckbox1"   /></label>   -->
        
        </div>

         </div> </div>


@php

foreach($decleration_info as $decleration){}

$decleration_name = $decleration->decname;
$decleration_Qualification = $decleration->qualification;
$decleration_position = $decleration->position;
$decleration_date = $decleration->date;


@endphp


                     <div class="col-12 col-md-6" style="position:relative;left:0%" >
       <label> Declaration Name </label> : <input value="{{ $decleration_name }}" disabled class="form-control" id="decleration_name" type="text" name="decleration_name" placeholder="Name:"  />
       <label> Qualification </label> :  <input value="{{ $decleration_Qualification }}" disabled class="form-control" id="qualification" type="text" name="qualification"  placeholder="Qualification:" />
       <label> Position </label> :<input value="{{$decleration_position}}"  disabled class="form-control" id="position_in_the_company" type="text" name="Position_in_the_company" Placeholder="Position in the company"  />
        <!-- <input class="form-control" id="Signature" type="text" name="Signature"  Placeholder="Signature" /> -->
        <label>  Date </label> : <input value="{{ $decleration_date }}"  disabledclass="form-control" id="Date_decleration" type="date" name="Date"  />
        <!-- <textarea class="form-control" id="OfficialSeal: " type="text" name=": " Placeholder="Officialstamp" /></textarea> -->
    </div>
        </div>
        <!-- /.card-body -->

     
     
                </table>
              </div>
              </div>

</div>


            </div>
               </div>


    
              </div>
              </div>
              </div>
              
  </div>
              </div>

              <!-- /.card-body -->


              
            </div>
            <!-- /.card -->
          </div>
        </div>
        </div>
        



        

@endsection