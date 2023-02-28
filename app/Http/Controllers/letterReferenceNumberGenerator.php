<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class letterReferenceNumberGenerator extends Controller
{
    //
    public function generate_letter_reference_number()
        {


            $year_setting = AppSetting::where('name', 'current_year')->first();
            $year_in_db = $year_setting->value;
            $year_from_system = date('Y');

            if ($year_in_db == $year_from_system) {
                $letter_reference_number = AppSetting::where('name', 'letter_reference_number_counter')->first();
                $letter_reference_number_counter = $letter_reference_number->value;
                $zero_filled_counter = sprintf('%05d', $letter_reference_number_counter);
                $letter_ref_num =  'NMFA/RL'  .'/'. $year_from_system . '/' . $zero_filled_counter;
                //increment the counter
                AppSetting::where('name', 'letter_reference_number_counter')->update(
                    [
                        'value' => $letter_reference_number_counter + 1,
                    ]
                );



            }
             else {
                //update the current year in db to the system year
                AppSetting::where('name', 'current_year')->update(
                    [
                        'value' => $year_from_system,
                    ]
                );
                //reset the counter to 1
                $count = 1;
                AppSetting::where('name', 'dossier_ref_num_counter')->update(
                    [
                        'value' => $count ,
                    ]
                );
                 AppSetting::where('name', 'letter_reference_number_counters')->update(
                                    [
                                        'value' => $count + 1,
                                    ]
                                );
                 AppSetting::where('name', 'product_registration_counter')->update(
                     [
                         'value' => $count ,
                     ]
                 );


                $zero_filled_counter = sprintf('%05d', $count);
                $letter_ref_num = 'NMFA/RL'  .'/'. $year_from_system . '/' . $zero_filled_counter;

            }


            return $letter_ref_num;
        }

        public function get_tmep_ref_number()
        {
                        $year_from_system = date('Y');
                        $letter_reference_number = AppSetting::where('name', 'letter_reference_number_counter')->first();
                        $letter_reference_number_counter = $letter_reference_number->value;
                        $zero_filled_counter = sprintf('%05d', $letter_reference_number_counter);
                        $letter_ref_num =  'NMFA/RL/'. $year_from_system . '/' . $zero_filled_counter;

                        return $letter_ref_num;
        }


    public function next_RL_number()
    {


        $year_setting = AppSetting::where('name', 'current_year')->first();
        $year_in_db = $year_setting->value;
        $year_from_system = date('Y');

        if ($year_in_db == $year_from_system) {
            $letter_reference_number = AppSetting::where('name', 'Next_RL_count')->first();
            $letter_reference_number_counter = $letter_reference_number->value;
            $zero_filled_counter = sprintf('%05d', $letter_reference_number_counter);
            $letter_ref_num =  'NMFA/RL'  .'/'. $year_from_system . '/' . $zero_filled_counter;
            //increment the counter
            AppSetting::where('name', 'Next_RL_count')->update(
                [
                    'value' => $letter_reference_number_counter + 1,
                ]
            );



        }
        else {
            //update the current year in db to the system year
            AppSetting::where('name', 'current_year')->update(
                [
                    'value' => $year_from_system,
                ]
            );
            //reset the counter to 1
            $count = 1;
            AppSetting::where('name', 'dossier_ref_num_counter')->update(
                [
                    'value' => $count ,
                ]
            );
            AppSetting::where('name', 'letter_reference_number_counters')->update(
                [
                    'value' => $count ,
                ]
            );
            AppSetting::where('name', 'product_registration_counter')->update(
                [
                    'value' => $count ,
                ]
            );
            AppSetting::where('name', 'Next_RL_count')->update(
                [
                    'value' => $count+1 ,
                ]
            );



            $zero_filled_counter = sprintf('%05d', $count);
            $letter_ref_num = 'NMFA/RL'  .'/'. $year_from_system . '/' . $zero_filled_counter;

        }


                        return $letter_ref_num;
        }
}
