<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public $email,$password,$remember_token;
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
         return view('dashboard',[
         'notifications'=>$notifications,
         'notification_count'=>$notification_count
         ]);
           // }
           }

        // return back()->withErrors([
        //     'email' => 'Your Credential Email Doesnot Match ',
        // ]);

        return back()
        ->with('danger','Your Credential Email Doesnot Match.');

        $notifications=auth()->user()->notifications;
        $notification_count=count($notifications);
        return view('dashboard',[
            'notifications'=>$notifications,
            'notification_count'=>$notification_count
            ]);
    }





    
    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login');
}



}
