<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Country;
use App\Models\user_forgot_password_answer_questions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StoreRegisterRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



use Illuminate\Support\Arr;



class RegisterController extends Controller
{

    public $decrypted,$Email,$status,$UserName='';



    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

   //use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }



    public function get_tele_code(Request $request)
        {

            $countries = new Country;
            $Check_tele = DB::select('select * from  countries  where id = ?', [$request['tele']]);
           foreach($Check_tele as $tele)
           {
             $this->tele = $tele->country_name;
             $this->code = $tele->International_dialing;
           }

        return response()->json(['Code'=> $this->code ]);

        }


    /**
     * 
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */ 
    protected function create(array $data)
    {
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);*/

      //  return view('shamot.indexx');
    }

    protected function user_profile(array $data)
    {
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);*/


        
        return view('userprofile.profile');
    }


    
    public function store(User $user,Request $request )
    {    

      

try
{
      $request['password'] = Hash::make($request['password']);
      $request['country_code']= $request['country_code'];
      $request['user_name'] = strtolower( $request['user_name']);
      $request['email'] =    strtolower( $request['email']);
     
     


       $email = strtolower($request['email']);
     
       $Check_Email = User::select('users.*')->where('users.email','=',$email)->first();


       $this->Email  = @$Check_Email->email;


       if( strtolower($this->Email) == strtolower($request['email']) )
       {
          $status = true;
        
             
        }
      
        else{
      
            $status = false;


            User::create($request->all());
         

            $user_email_id =  DB::select('select * from  users  where email = ?', [$request->email]);
            $user = User::find($user_email_id[0]->id);
            $user->assignRole('Applicant');
       
            $request['question_number_one'] = $request['personal_informations'];
            $request['question_number_two'] = $request['child_hood'];
            $request['question_number_three'] = $request['hobbies_select'];
            $request['answer_number_one'] = $request['personal_information'];
            $request['answer_number_two'] = $request['childhood_questions'];
            $request['answer_number_three'] = $request['hobbies'];
            $request['user_id'] = $user_email_id[0]->id;
     
            $Insert_query=user_forgot_password_answer_questions::create($request->all());
     


        }





       


        return response()->json([
            'Message'=>true,
           'UserName'=>$request->user_name,
           'email' => $status
           ]);



}
catch(Exception $e)
{

return response()->json(['Message'=>$e,'item'=>'error'.$e]);

}
 }

    
    public function validate_Register(User $user,Request $request  )
    {
        $this->Email="";
        $user = new User();
        //dd( $request['Email']);
        $email = strtolower($request['Email']);
       $Check_Email = DB::select('select * from users where email = ?',[$email ]);
       foreach ($Check_Email as $email) 
       {
         $this->Email= $email->email;
       }


 if( strtolower($this->Email) == strtolower($request['Email']) )
 {
 return response()->json(['error'=>"<i class='fa fa-exclamation-triangle fa-1'></i> Email Already Registered"]);
 //echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
 }
 else
 {
     return response()->json(['success'=>'Good To go']);
 
 }

 

      //  return view('user.index', ['users' => $users]);
    }



    public function validate_User(User $user,Request $request)
    {

     
        $this->UserName="";
        $user = new User();
        $user_name = strtolower($request['name']);
      
   $Check_Username = DB::select('select * from users where user_name = ?', [$user_name]);
       foreach ($Check_Username as $user) 
       {

         $this->UserName = $user->user_name;
       
       }

    


       if( strtolower($this->UserName) == strtolower($request['name']) && strlen($this->UserName) >=5 )
      {
      return response()->json(['error'=>"<u style='color:yellow'></u>&nbsp;Username already registered.Try other options"]);
      //echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
      }
      else
      {
     return response()->json(['success'=>true]);
      }
      }


    public function show(Request $request)
    {
        //
        $token = csrf_token();
        $user = new User();
        dd( $request['Email']);
      // return view('shamot.index',compact('user'));

    }



    
    public function check_validity_strength(Request $request)
    {

       // dd($request->all());
// Given password
$password = $request->password;

// Validate password strength
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    return response()->json(['Message'=>false,'result'=>"Password should be at least 8 characters in length  <br> and should include at least one upper case letter, <br> one number and one special character."]);
}else{
    
    return response()->json(['Message'=>true,'result'=>"Strong password" ]);

   
}

    }
}
