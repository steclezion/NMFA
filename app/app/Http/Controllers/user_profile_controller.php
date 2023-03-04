<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Auth;
use App\Models\User;
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
use App\Models\Country;
use Spatie\Permission\Models\Permission;

class user_profile_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */

     
 public function __construct()

 {

     $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);

     $this->middleware('permission:role-create', ['only' => ['create','store']]);

     $this->middleware('permission:role-edit', ['only' => ['edit','update']]);

     $this->middleware('permission:role-delete', ['only' => ['destroy']]);

 }



    public function index()
    {
        //

    }


    public function check_validity_strength(Request $request)
    {

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

    protected function user_profile()
    {
      

        $user = User::find(auth()->user()->id);
        $countries = Country::all()->sortBy('country_name');
        $countries_id = Country::pluck('country_name','id');
        $dataa = User::where('id', auth()->user()->id)->orderBy('id','ASC')->get();

        $user_upload_cv= User::join('documents','documents.id','users.upload_cv_id')
        ->select('documents.*','users.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
        ->where('users.id','=',auth()->user()->id)
        ->where('documents.document_type','=',15)
        ->get();

//dd( $dataa );

return view('userprofile.profile',compact('user','countries','countries_id','dataa','user_upload_cv'));
        
        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update_user(Request $request, $id)
    {
        //
//dd( $request->all());
        $input =  $request->all();
      
 $this->validate($request, [

    'first_name' =>  'required',
    //'middle_name' => 'required',
    'last_name' =>   'required',
    'email'      =>  'required|email|unique:users,email,'.$id,
    'password'   =>  'same:confirm_password',
    'file' => 'mimes:JPG,PNG,GIF,SVG,jpg,png,jpeg,gif,svg,ico|max:204800',

      ]);

$user_selected= User::where('id', auth()->user()->id)->orderBy('id','ASC')->first();

if($input['password'] != '' && $input['password'] == $input['confirm_password'] )
{

    if($request->has('file')) {

    $name = @$request->file('file')->getClientOriginalName();
    $time=time();
    $path = public_path('storage/avatar');
    $fileName = $name.$time.".".$request->file('file')->extension();;
    $filePath = $request->file('file')->storeAs('avatar', $fileName, 'public');

    $input['avatar_path'] = 'storage/avatar/'.$fileName;
    $input['avatar_path'] = $user_selected['avatar_path'];
    $input['password'] = Hash::make($input['password']);
    $user = User::find($id);
    $user->update($input);
    return redirect(route('user_profile'))-> with('success','Profile updated successfully!');
    }
    else
    {
        $input['password'] = Hash::make($input['password']);
        $user = User::find($id);
        $user->update($input);
        return redirect(route('user_profile'))-> with('success','Profile updated successfully!');

    }
    
}

else

{

    if($request->has('file')) {

    $name = @$request->file('file')->getClientOriginalName();
    $time=time();
    $path = public_path('storage/avatar');
    $fileName = $name.$time.".".$request->file('file')->extension();;
    $filePath = $request->file('file')->storeAs('avatar', $fileName, 'public');

    $input['avatar_path'] = 'storage/avatar/'.$fileName;
    $input['password'] = $user_selected->password;
    $user = User::find($id);
    $user->update($input);
    return redirect(route('user_profile'))-> with('success','Profile updated successfully!');
    }
    else
    {

        $input['password'] = $user_selected->password;
        $user = User::find($id);
        $user->update($input);
        return redirect(route('user_profile'))-> with('success','Profile updated successfully!');
    }

}


return redirect(route('user_profile'))-> with('success','Profile updated successfully!');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
