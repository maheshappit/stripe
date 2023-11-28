<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Conference;
use App\Http\Requests\UserFrontLoginVerifyOTPFormRequest;
use App\Http\Requests\UserFrontLoginWithOTPFormRequest;
use App\Models\admin_verification_codes;
use Carbon\Carbon;
use App\Mail\LoginOTP;
use App\Models\Admin;
use Mail;


// use Validator;
use League\Csv\Reader;
use App\Models\User;


// use App\Http\Controllers\Controller;
// use App\Providers\RouteServiceProvider;
// use App\Models\User;
// use Illuminate\Foundation\Auth\RegistersUsers;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class AdminController extends Controller
{
    //todo: admin login form
    public function login_form()
    {
        return view('admin.login-form');
    }

    //todo: admin login functionality
    public function login_functionality(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);

        if (Auth::guard('superadmin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('superadmin.dashboard');
        }
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard');
        }else{
            Session::flash('error-message','Invalid Email or Password');
            return back();
        }
    }

    
    public function loginWithOTP(UserFrontLoginWithOTPFormRequest $request)
    {
        $user = Admin::where('email', $request->email)->first();

        if($user){
            return $this->generateNewOTP($user);
        }else{
            return back()->withErrors(['email' => ['This Email is not exists.']]);
        }
    }


    public function resndOTP()
    {
        if(!request()->session()->get('login_user_id')){
            return redirect()->route('login');
        }

        $user = User::where('id', request()->session()->get('login_user_id'))->first();

        if($user){
            $verification = $this->generateOTP($user);
            if($verification){
                return redirect()->route('admin.getVerifyOTP')->with('otp_sent_success', 'OTP has been sent to your email. Valid for 5 minutes');
            }else{
                abort(404);
            }
        }else{
            return back()->withErrors(['email' => ['This Email is not exists.']]);
        }
    }

    protected function redirectTo()
    {
        $user = Auth::user();
        
        if($user) {
            return 'admin/dashboard';
        }
        return '/home';
    }

    public function getVerifyOTP()
    {


        if(!request()->session()->get('login_user_id')){
            return redirect()->route('admin.login');
        }

        
        return view('admin.verify_otp', [
            
        ]);
    }

  

    public function postVerifyOTP(UserFrontLoginVerifyOTPFormRequest $request)
    {

        // dd(request()->session()->get('login_user_id'));

        $verification = admin_verification_codes::where([
            'user_id'   => request()->session()->get('login_user_id'),
            'otp'       => $request->otp
        ])->where('expire_at', '>', Carbon::now())->first();

        if(!$verification){
            return redirect()->route('admin.getVerifyOTP')->withErrors(['otp' => ['Invalid OTP']]);
        }
        else{

            $user = User::where('id', request()->session()->get('login_user_id'))->first();

        \Auth::login($user);

        $verification->delete();

        request()->session()->forget('login_user_id');



        return redirect($this->redirectTo());


        }

       






       
    
    }

    public function generateNewOTP($user)
    {
        $verification = $this->generateOTP($user);
        if($verification){
            return redirect()->route('admin.getVerifyOTP')->with('otp_sent_success', 'OTP has been sent to your email. Valid for 5 minutes');
        }else{
            abort(404);
        }
    }


    public function generateOTP($user)
    {
        $otp = rand(100000, 999999);
  
        $verification = admin_verification_codes::where([
            'user_id'   => $user->id
        ])->first();

        if(!$verification){
            $verification = new admin_verification_codes();
            $verification->user_id = $user->id;
        }

        $verification->expire_at = Carbon::now()->addMinutes(5);
        $verification->otp = $otp;
        $verification->save();

        $data['from_name'] = config('mail.from.name');
        $data['from_email'] = config('mail.from.address');
        $data['subject'] = 'OTP Confirmation';
        $data['otp'] = $otp;
        $data['to_email'] = $user->email;
        $data['to_name'] = $user->name;


        Mail::send(new LoginOTP($data));

        request()->session()->put('login_user_id', $user->id);

        return $verification;

    }

    public function dashboard()
    {
        $users_data=Conference::latest()->paginate(10);

        $countries = Conference::distinct()->pluck('country',)->toArray();

        return view('admin.dashboard',compact('countries'));
        
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function create(Request $request)
    {
        // Validate the request data
        $request->validate(User::$rules);

        // Check if the user already exists
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'User already exists.');
        }

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }



    public function update(Request $request){


        $user= Conference::find($request->id);
        $user->update([
            'create_date'=>$request->create_date,
            'email_sent_date'=>$request->email_sent_date,
            'company_source'=>$request->company_source,
            'contact_source'=>$request->contact_source,
            'database_creator_name'=>$request->database_creator_name,
            'technology'=>$request->technology,
            'client_speciality'=>$request->client_speciality,
            'client_name'=>$request->client_name,
            'street'=>$request->street,
            'city'=>$request->city,
            'state'=>$request->state,
            'zip_code'=>$request->zip_code,
            'country'=>$request->country,
            'website'=>$request->website,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'designation'=>$request->designation,
            'email'=>$request->email,
            'email_response_1'=>$request->email_response_1,
            'email_response_2'=>$request->email_response_2,
            'rating'=>$request->rating,
            'followup'=>$request->followup,
            'linkedin_link'=>$request->linkedin_link,
            'employee_count'=>$request->employee_count,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User Updated Successfully.');

    }

    public function delete(Request $request){
        $user=Conference::find($request->id);
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User Deleted Successfully.');

    }

    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048', 
        ]);

        if ($validator->fails()) {
            return redirect('home')
                        ->withErrors($validator)
                        ->withInput();
        }else{

            $file = $request->file('file');
        $path = $file->getRealPath();
    
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
    
        foreach ($csv as $row) {
            // dd($row);


            $email = $row['Email']; // Assuming the email column in the CSV is named 'email'

        // Try to find a record with the same email in the database
        $existingRecord = Conference::where('email', $email)->first();

        if ($existingRecord) {
            // If a record with the same email exists, update it
            $existingRecord->update([
                'create_date'=>$row['Create Date'],
                'email_sent_date'=>$row['Email sent Date'],
                'company_source'=>$row['Company Source'],
                'contact_source'=>$row['Contact Source'],
                'database_creator_name'=>$row['Database Creator Name'],
                'technology'=>$row['Technology'],
                'client_speciality'=>$row['Client Speciality'],
                'client_name'=>$row['Client Name'],
                'street'=>$row['Street'],
                'city'=>$row['City'],
                'state'=>$row['State'],
                'zip_code'=>$row['Zip Code'],
                'country'=>$row['Country'],
                'website'=>$row['Website'],
                'first_name'=>$row['First Name'],
                'last_name'=>$row['Last Name'],
                'designation'=>$row['Designation'],
                'email'=>$row['Email'],
                'email_response_1'=>$row['Response 1'],
                'email_response_2'=>$row['Response 2'],
                'rating'=>$row['Rating'],
                'followup'=>$row['FollowUp'],
                'linkedin_link'=>$row['LinkedIn Link'],
                'employee_count'=>$row['Employee Count']
            ]);
        } else {
            // If no record with the same email exists, insert a new record
            Conference::create([
                'create_date'=>$row['Create Date'],
                'email_sent_date'=>$row['Email sent Date'],
                'company_source'=>$row['Company Source'],
                'contact_source'=>$row['Contact Source'],
                'database_creator_name'=>$row['Database Creator Name'],
                'technology'=>$row['Technology'],
                'client_speciality'=>$row['Client Speciality'],
                'client_name'=>$row['Client Name'],
                'street'=>$row['Street'],
                'city'=>$row['City'],
                'state'=>$row['State'],
                'zip_code'=>$row['Zip Code'],
                'country'=>$row['Country'],
                'website'=>$row['Website'],
                'first_name'=>$row['First Name'],
                'last_name'=>$row['Last Name'],
                'designation'=>$row['Designation'],
                'email'=>$row['Email'],
                'email_response_1'=>$row['Response 1'],
                'email_response_2'=>$row['Response 2'],
                'rating'=>$row['Rating'],
                'followup'=>$row['FollowUp'],
                'linkedin_link'=>$row['LinkedIn Link'],
                'employee_count'=>$row['Employee Count']


            ]);
        }
        }
    
        return redirect()->route('admin.dashboard')->with('success', 'CSV file uploaded and processed successfully.');

        }
    
        
    }


    public function logout(Request $request){

        $user = Auth::user();

        if ($user) {
        Auth::logout(); // Log the user out

        request()->session()->forget('login_user_id');
       
        return redirect(route('login'));
    }

    // Handle the case where no user is authenticated
    return redirect('/')->with('error', 'No user is currently authenticated.');

    }
    public function edit(Request $request){
        $user=Conference::find($request->id);

        return view('admin.edit',compact('user'));

    }
}