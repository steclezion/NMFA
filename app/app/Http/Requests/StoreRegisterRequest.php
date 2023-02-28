<?php
namespace App\Http\Controllers;
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterRequest extends FormRequest
{
    /* Determine if the user is authorized to make this request.
    *
    * @return bool
    */


   public function authorize()
   {
       return true;
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
       return [
           //
           
           'first_name' => 'required|max:10',
           'middle_name'=> 'required|max:10',
           'last_name' => 'required|max:10',
           'email' => 'required|max:50',
           
           
       ];
   }



   public function messages()
   {
       return [
           
           'first_name.max' => "FirstName Exceeds The Maximum Value",
           'middle_name.max'=> 'MiddleName Exceeds The Maximum Value',
           'last_name.max'=>  'Last Name Exceeds the Maximum Value ',
           'email.max' => 'Invalid Email Address',
           
       ];
   
   }

}
