<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\forgot_password_questions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


use AppProvinces;
use AppRegencies;
use AppDistricts;
use AppVillages;


class CountryController extends Controller
{
    //
 public function country(){
    $countries = Country::all();
    $forgotpassword_one = forgot_password_questions::where('Question_Type','1')->get();
    $forgotpassword_two = forgot_password_questions::where('Question_Type','2')->get();
    $forgotpassword_three = forgot_password_questions::where('Question_Type','3')->get();
    return view('auth.register', compact('countries','forgotpassword_one','forgotpassword_two','forgotpassword_three'));
    //return redirect()->back()->with('message','Image Uploaded.');
      }


      public function forgotpassword(Request $request)
      {

//dd($request->session()->all());

        $countries = Country::all();
        $forgotpassword_one = forgot_password_questions::where('Question_Type','1')->get();
        $forgotpassword_two = forgot_password_questions::where('Question_Type','2')->get();
        $forgotpassword_three = forgot_password_questions::where('Question_Type','3')->get();
        
        return view('auth.forgot_password_page_verification', compact('countries','forgotpassword_one','forgotpassword_two','forgotpassword_three'));
        //return redirect()->back()->with('message','Image Uploaded.');

      }


}
