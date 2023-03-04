<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\model_has_roles;
use App\Http\Controllers\DashboardController;

class LoginController extends Controller
{
    public $email,$password,$remember_token, $array_roles = [];
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
      protected $redirectTo='/Home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $array_roles = [];
    }



    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $this->password= $credentials['password'];
       $this->email = $credentials['email'];
  
     //@$this->remember_token  =  $credentials['remember_token'];
    //dd($this->remember_token.$this->password. $this->email);
   // if(  $this->remember_token == 'on'   ) {   $this->remember_token='1';   } else {    $this->remember_token=0;   }
  //dd($this->remember_token);
        
     if (Auth::attempt($credentials)) {
         //if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember_token)) {
         $request->session()->regenerate();
         $notifications=auth()->user()->notifications;
         $notification_count=count($notifications);
//         return redirect()->intended('/')->with('notifications',$notifications);

        @$roles_names = $this->get_roles_names(auth()->user()->id) ;
       $dashboard_obj=new  DashboardController();
       $assessor_ongoing_evaluation_count=$dashboard_obj->assessor_ongoing_dossier(auth()->user()->id);
         $assessor_ongoing_variation=$dashboard_obj->assessor_ongoing_variation(auth()->user()->id);
         $assessor_ongoing_prelimunary_application=$dashboard_obj->assessor_ongoing_prelimunary_application(auth()->user()->id);
         $assessor_ongoing_dossier_section_count=$dashboard_obj->assessor_ongoing__dossier_section(auth()->user()->id);





         $inprogress_applications=$dashboard_obj->inprogress_applications(auth()->user()->id);
         $completed_applications=$dashboard_obj->completed_applications(auth()->user()->id);
         $mah_applications=$dashboard_obj->MAH_applications(auth()->user()->id);
         $due_products=$dashboard_obj->due_products(auth()->user()->id);


         $unassigned_dossiers=$dashboard_obj->unassigned_dossiers(auth()->user()->id);
         $unassigened_preliminary=$dashboard_obj->unassigened_preliminary(auth()->user()->id);
         $deadline_requests=$dashboard_obj->deadline_requests(auth()->user()->id);


         $assigned_psur=$dashboard_obj->assigned_psur(auth()->user()->id);
         $meeting_invitation=$dashboard_obj->meeting_invitation(auth()->user()->id);

         $inspection_sample_test_request_count=$dashboard_obj->inspection_sample_test_request(auth()->user()->id);
         $QC_sample_test_request_count=$dashboard_obj->QC_sample_test_request(auth()->user()->id);

         $NMFA_Director_MAH_applications=$dashboard_obj->NMFA_Director_MAH_applications();

         $ongoing_dossier_evalutions=$dashboard_obj->supervisor_ongoing_dossier(auth()->user()->id);

         $nmfa_director_pusr_alert=$dashboard_obj->NMFA_Director_notificaitons();



        $request->session()->put('roles_names', $roles_names);

         $notifications=auth()->user()->notifications;
         $notification_count=count($notifications);

         return view('dashboard',[
            'roles_names' => @$roles_names,
             'notifications'=>$notifications,
             'notification_count'=>$notification_count,
             'assessor_ongoing_evaluation_count'=>$assessor_ongoing_evaluation_count,
             'assessor_ongoing_dossier_section_count'=>$assessor_ongoing_dossier_section_count,
             'assessor_ongoing_variation'=>$assessor_ongoing_variation,
             'assessor_ongoing_prelimunary_application'=>$assessor_ongoing_prelimunary_application,
             'inprogress_applications'=>$inprogress_applications,
             'completed_applications'=>$completed_applications,
             'mah_applications'=>$mah_applications,
             'due_products'=>$due_products,
             'unassigened_preliminary' =>$unassigened_preliminary,
             'unassigned_dossiers' =>$unassigned_dossiers,
             'deadline'=>$deadline_requests,
             'assigned_psur'=>$assigned_psur,
             'meeting_invitation'=>$meeting_invitation,
             'QC_sample_test_request_count'=>$QC_sample_test_request_count,
             'inspection_sample_test_request_count'=>$inspection_sample_test_request_count,
             'notifications'=>$notifications,
             'notification_count'=>$notification_count,
             'NMFA_Director_MAH_applications'=>$NMFA_Director_MAH_applications,
             'ongoing_dossier_evalutions'=>$ongoing_dossier_evalutions,
             'nmfa_director_pusr_alert'=>$nmfa_director_pusr_alert

           

         ]);
           // }
           }

        // return back()->withErrors([
        //     'email' => 'Your Credential Email Doesnot Match ',
        // ]);

        return back()
        ->with('danger','Invalid email or password.');


    }



    public function get_roles_names($user_id)
    {
$choose_option_dashs = model_has_roles::join('roles','roles.id','model_has_roles.role_id')
                      ->where('model_id',$user_id)
                      ->distinct()
                      ->orderBy('role_id','ASC')
                      ->get();


foreach ($choose_option_dashs  as $user)
{ 
array_push($this->array_roles,$user->name);
}




    return $this->array_roles ;
}




    
    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    $request->session()->reflash();

    return redirect('/login');
}



}
