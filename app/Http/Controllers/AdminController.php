<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ConferenceDetails;
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

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard');
        }else{
            Session::flash('error-message','Invalid Email or Password');
            return back();
        }
    }

    public function dashboard()
    {
        $users_data=ConferenceDetails::latest()->paginate(10);


        $dba_names = ConferenceDetails::distinct()->pluck('database_creator_name',)->toArray();

        $countries = ConferenceDetails::distinct()->pluck('country',)->toArray();
        $client_names = ConferenceDetails::distinct()->pluck('client_name',)->toArray();

        return view('admin.dashboard',compact('countries','client_names','dba_names'));
        
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


        $user= ConferenceDetails::find($request->id);
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

        // dd($request);


        $user=ConferenceDetails::find($request->id);
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
        $existingRecord = ConferenceDetails::where('email', $email)->first();

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
            ConferenceDetails::create([
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


    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('message', 'User Deleted Successfully.');
    }
    public function edit(Request $request){
        $user=ConferenceDetails::find($request->id);

        return view('admin.edit',compact('user'));

    }
}