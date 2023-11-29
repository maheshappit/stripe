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
use DataTables;


use App\Models\ConferencesData;


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
    public function login_functionality(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('superadmin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('superadmin.dashboard');
        }
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard');
        } else {
            Session::flash('error-message', 'Invalid Email or Password');
            return back();
        }
    }


    public function showReport()
    {


        $all_users = User::all();
        return view('admin.reports', compact('all_users'));
    }

    public function downloadReport(Request $request)
    {

        // dd($request->all())

        $all_users = User::all();


        $f_date = Carbon::parse($request->from_date);
        $startDate = $f_date->format('Y-m-d');
        // dd($startDate);
        $t_date = Carbon::parse($request->to_date);
        $endDate = $t_date->format('Y-m-d');

        $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',

        ]);



        $query = Conference::query();

        $query->join('users', 'users.id', '=', 'conferences.user_id');
        $query->whereBetween('conferences.user_created_at', [$startDate, $endDate]);

        if ($request->user_id == 'All') {
            $query->select('users.id', 'users.name', 'users.created_at');
            $query->selectRaw(
                '
            COUNT(DISTINCT CASE WHEN conferences.user_created_at IS NOT NULL THEN conferences.id END) as inserted_count,
            COUNT(DISTINCT CASE WHEN conferences.user_updated_at IS NOT NULL THEN conferences.id END) as updated_count,
            SUM(conferences.download_count) as download_count'
            );
            $query->whereNotNull('conferences.user_created_at'); // Only count inserted records
            $query->groupBy('users.id', 'users.name', 'users.created_at');
        } else {
            $query->where('conferences.user_id', $request->user_id);
            $query->select('users.id', 'users.name', 'users.created_at');

            $query->selectRaw(
                '
            users.id, users.name,
            SUM(CASE WHEN conferences.user_created_at IS NOT NULL THEN 1 ELSE 0 END) as inserted_count,
            SUM(CASE WHEN conferences.user_updated_at IS NOT NULL THEN 1 ELSE 0 END) as updated_count,
            SUM(conferences.download_count) as download_count'
            );
            $query->groupBy('users.id', 'users.name', 'users.created_at');
        }


        $result = $query->get();

        return DataTables::of($result)
            ->make(true);
    }



    public function loginWithOTP(UserFrontLoginWithOTPFormRequest $request)
    {
        $user = Admin::where('email', $request->email)->first();

        if ($user) {
            return $this->generateNewOTP($user);
        } else {
            return back()->withErrors(['email' => ['This Email is not exists.']]);
        }
    }


    public function resndOTP()
    {
        if (!request()->session()->get('login_user_id')) {
            return redirect()->route('login');
        }

        $user = User::where('id', request()->session()->get('login_user_id'))->first();

        if ($user) {
            $verification = $this->generateOTP($user);
            if ($verification) {
                return redirect()->route('admin.getVerifyOTP')->with('otp_sent_success', 'OTP has been sent to your email. Valid for 5 minutes');
            } else {
                abort(404);
            }
        } else {
            return back()->withErrors(['email' => ['This Email is not exists.']]);
        }
    }

    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user) {
            return 'admin/dashboard';
        }
        return '/home';
    }

    public function show()
    {

        $conferences = ConferencesData::all();

        return view('admin.upload', compact('conferences'));
    }

    public function getVerifyOTP()
    {


        if (!request()->session()->get('login_user_id')) {
            return redirect()->route('admin.login');
        }


        return view('admin.verify_otp', []);
    }



    public function postVerifyOTP(UserFrontLoginVerifyOTPFormRequest $request)
    {

        // dd(request()->session()->get('login_user_id'));

        $verification = admin_verification_codes::where([
            'user_id'   => request()->session()->get('login_user_id'),
            'otp'       => $request->otp
        ])->where('expire_at', '>', Carbon::now())->first();

        if (!$verification) {
            return redirect()->route('admin.getVerifyOTP')->withErrors(['otp' => ['Invalid OTP']]);
        } else {

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
        if ($verification) {
            return redirect()->route('admin.getVerifyOTP')->with('otp_sent_success', 'OTP has been sent to your email. Valid for 5 minutes');
        } else {
            abort(404);
        }
    }


    public function generateOTP($user)
    {
        $otp = rand(100000, 999999);

        $verification = admin_verification_codes::where([
            'user_id'   => $user->id
        ])->first();

        if (!$verification) {
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
        $users_data = Conference::latest()->paginate(10);

        $countries = Conference::distinct()->pluck('country',)->toArray();

        return view('admin.dashboard', compact('countries'));
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



    public function update(Request $request)
    {


        $user = Conference::find($request->id);
        $user->update([
            'create_date' => $request->create_date,
            'email_sent_date' => $request->email_sent_date,
            'company_source' => $request->company_source,
            'contact_source' => $request->contact_source,
            'database_creator_name' => $request->database_creator_name,
            'technology' => $request->technology,
            'client_speciality' => $request->client_speciality,
            'client_name' => $request->client_name,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'website' => $request->website,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'designation' => $request->designation,
            'email' => $request->email,
            'email_response_1' => $request->email_response_1,
            'email_response_2' => $request->email_response_2,
            'rating' => $request->rating,
            'followup' => $request->followup,
            'linkedin_link' => $request->linkedin_link,
            'employee_count' => $request->employee_count,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User Updated Successfully.');
    }

    public function delete(Request $request)
    {
        $user = Conference::find($request->id);
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User Deleted Successfully.');
    }

    public function upload(Request $request)
    {

        $now = Carbon::now();


        $currentDateTime = $now->toDateString();



        $userID = Auth::id();
        // dd($userID);
        $request->validate([
            'csvFile' => 'required|mimes:csv,txt|max:10000000',
        ]);



        $file = $request->file('csvFile');
        $path = $file->getRealPath();

        $csv = Reader::createFromPath($path, 'r');
        $headers = $csv->fetchOne();

        // dd($headers);

        $csv->setHeaderOffset(0);

        //if upload from file upload and move to public uploads

        // $file = $request->file('csvFile');

        // $filePath = $file->move(public_path('uploads'), $file->getClientOriginalName()); // Move the file to 'public/uploads' directory

        // $csv = Reader::createFromPath($filePath, 'r');
        // $csv->setHeaderOffset(0); // Set the CSV header row


        $update_count = 0;
        $errorCount = 0;
        $insertcount = 0;




        foreach ($csv as $row) {
            $email = $row['Email'];
            $conference = $row['Conference'];
            $article = $row['Article'];


            // Check if the record exists based on the email
            $model = Conference::where('email', $email)->where('conference', $request->conference)->where('article', $article)->first();

            if ($model) {
                // If the record exists, update it
                $model->update([
                    'name' => $row['Name'],
                    // 'email' => $row['Email'],
                    // 'article' => $row['Article'],
                    // 'conference' => $row['Conference'],
                    'country' => $row['Country'],

                    'user_id' => $request->user()->id,

                    'user_updated_at' => $currentDateTime,
                    // 'updated_at'=>'',
                ]);
                $update_count++;
            } else {
                // If the record doesn't exist, create a new one

                Conference::create([

                    'name' => $row['Name'],
                    'email' => $row['Email'],
                    'article' => $row['Article'],
                    // 'conference' => $row['Conference'],
                    'conference'=>$request->conference,
                    'country' => $row['Country'],
                    'user_id' => $request->user()->id,

                    'user_created_at' => $currentDateTime,
                    // 'updated_at'=>'',

                ]);
                $insertcount++;
            }
        }


        //if upload from uploads
        // if (file_exists($filePath)) {
        //     unlink($filePath);
        // } 


        return response()->json([
            'inserted_count' => 'Inserted Records Count: ' . $insertcount,
            'updated_count' => 'Updated Records Count: ' . $update_count,
            'message' => 'Data Uploaded Successfully',
        ]);
    }

    public function users(Request $request)
    {



        $query = Conference::query();

        if ($request->search) {
            $query->where('country', 'like', '%' . $request->search . '%');
        } else {
            $query->whereNotNull('country')->orderBy('created_at', 'desc');
        }

        if ($request->country == 'All') {

            $query->whereNotNull('country')->orderBy('created_at', 'desc');
        } else {
            $query->where('country', 'like', '%' . $request->country . '%')->orderBy('created_at', 'desc');
        }

        if ($request->conference == 'All') {

            $query->whereNotNull('conference')->orderBy('created_at', 'desc');
        } else {
            $query->where('conference', 'like', '%' . $request->conference . '%')->orderBy('created_at', 'desc');
        }

        if ($request->article == 'All') {

            $query->whereNotNull('article')->orderBy('created_at', 'desc');
        } else {
            $query->where('article', 'like', '%' . $request->article . '%')->orderBy('created_at', 'desc');
        }


        if ($request->user == 'All') {

            $query->whereNotNull('email')->orderBy('created_at', 'desc');
        } else {
            $query->where('user_id', 'like', '%' . $request->user . '%')->orderBy('created_at', 'desc');
        }






        //for all country,conference,articles,users,created,updated dates
        if ($request->country == 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {

            $query->whereNotNull('country')->whereNotNull('conference')->whereNotNull('article')->whereNotNull('user_id')->orderBy('created_at', 'desc');
        }

        //particular country and all-->conferences,articles,users,created,updated dates
        if ($request->country != 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            $query->where('country', $request->country)->whereNotNull('conference')->whereNotNull('article')->whereNotNull('user_id')->orderBy('created_at', 'desc');
        }

        //particular country,conference and all-->conferences,articles,users,created,updated dates

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->whereNotNull('article')->whereNotNull('user_id')->orderBy('created_at', 'desc');
        }



        //particular country,conference,article, all users,all dates
        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->where('article', $request->article)->whereNotNull('user_id')->orderBy('created_at', 'desc');
        }


        //particular country,conference,article, users,all dates
        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->whereNotNull('article')->where('user_id', $request->user_id)->orderBy('created_at', 'desc');
        }


        //particular country,conference,article,user,user created date,all 

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->whereNotNull('article')->whereNotNull('user_id')->where('user_created_at', $request->user_created_at)->orderBy('created_at', 'desc');
        }


        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  == '' && $request->user_updated_at != '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->whereNotNull('article')->whereNotNull('user_id')->where('user_updated_at', $request->user_created_at)->orderBy('created_at', 'desc');
        }


        //particular country,conference,article,user,user created date,user updated date

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at != '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->whereNotNull('article')->whereNotNull('user_id')->where('user_created_at', $request->user_created_at)->where('user_updated_at', $request->user_updated_at)->orderBy('created_at', 'desc');
        }


        //country,conference,article,user,created,updated
        if ($request->country == 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at != '' && $request->user_created_at) {
            // dd($request);
            $query->whereNotNull('country')->where('conference', $request->conference)->where('article', $request->article)->where('user_id', $request->user_id)->where('user_created_at', $request->user_created_at)->where('user_updated_at', $request->user_updated_at)->orderBy('created_at', 'desc');
        }






        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('posted_by', function ($row) {
                return $row->postedby->name;
            })
            ->rawColumns(['posted_by'])
            ->make(true);
    }

    public function conferences(Request $request)
    {

        $conferences = ConferencesData::all();
        $countries = Conference::distinct()->pluck('country',)->toArray();
        $users = User::all();

        return view('admin.conferences', compact('conferences', 'countries', 'users'));
    }

    public function logout(Request $request)
    {

        $user = Auth::user();

        if ($user) {
            Auth::logout(); // Log the user out
            request()->session()->forget('login_user_id');
            return redirect(route('login'));
        }

        // Handle the case where no user is authenticated
        return redirect('/')->with('error', 'No user is currently authenticated.');
    }
    public function edit(Request $request)
    {
        $user = Conference::find($request->id);
        return view('admin.edit', compact('user'));
    }
}
