<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class Forgot_password_questions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is your spouse’s name?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is your nickname?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
// DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What was the name of your high school principal?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
// DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is the name of your favorite uncle?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is the name of your highschool principle name?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is the name of your uncle?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is your pet’s name?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
// DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'When did you acquire your Master\'s Degree?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);
// DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is the name of the best products your company ever sold?', 'Question_Type' => '1','created_at'=>now(),'updated_at'=>now() ]);


DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What was the name of your kindergarten school?', 'Question_Type' => '2','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is the name of your best friend during childhood?', 'Question_Type' => '2','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'When did you graduate from middle school?', 'Question_Type' => '2','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What was your favorite cartoon movie?', 'Question_Type' => '2','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What was your favorite childhood game?', 'Question_Type' => '2','created_at'=>now(),'updated_at'=>now() ]);


DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is your favorite hobby?', 'Question_Type' => '3','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What music genre do you like the most?', 'Question_Type' => '3','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is your favorite sport?', 'Question_Type' => '3','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is your favorite book?', 'Question_Type' => '3','created_at'=>now(),'updated_at'=>now() ]);
DB::table('forgotpasswordquestions')->insert(['Question_Name'=>'What is your favorite movie?', 'Question_Type' => '3','created_at'=>now(),'updated_at'=>now() ]);


    }

}
