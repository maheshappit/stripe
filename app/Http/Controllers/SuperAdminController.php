<?php

namespace App\Http\Controllers;

use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ConferencesData;
use App\Models\Conference;
use DataTables;
use League\Csv\Reader;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UserFrontLoginVerifyOTPFormRequest;
use App\Http\Requests\UserFrontLoginWithOTPFormRequest;
use App\Models\SuperAdminVerificationCodes;
use Carbon\Carbon;
use App\Mail\LoginOTP;
use App\Models\Admin;
use Mail;
use Validator;


class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login_form()
    {
        return view('superadmin.login-form');
    }

    public function showReport()
    {
        $all_users = User::all();
        $users_count=User::where('role','user')->get()->count();
        $inserted_count = Conference::whereNotNull('user_created_at')->count();
        $updated_count = Conference::whereNotNull('user_updated_at')->count();
        $download_count = Conference::whereNotNull('download_count')->count();
        return view('superadmin.reports', compact('all_users','users_count','inserted_count','updated_count','download_count'));    }
    public function upload(Request $request)
    {

        $now = Carbon::now();


        $currentDateTime = $now->toDateString();



        $userID = Auth::id();
        // dd($userID);
        $request->validate([
            'csvFile' => 'required|mimes:csv,txt|max:10000000',
            'conference' => 'required',
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
                    'conference' => $request->conference,
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

    public function AllUsers(){

        $all_users = User::paginate(10);
        return view('superadmin.all-users',compact('all_users'));

    }

    public function UserShow($id)
    {
        try {
            // Your logic to fetch data for a specific ID
            $data = user::findOrFail($id);

            return response()->json($data);
        } catch (\Exception $e) {
            // Handle errors, for example, return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


  

    public function userUpdate(Request $request)
    {

        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        $user->save();
        return response()->json([
            'message' => 'User  Updated Successfully',
            'status_code' => '200'

        ], 200);
    }


    public function userDelete(Request $request)
    {




        $user = User::findOrFail($request->id);

        $user->delete();
        return response()->json([
            'message' => 'User  Deleted Successfully',
            'status_code' => '200'

        ], 200);
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

    public function allClients(Request $request, $id)
    {

        if ($request->id === 'All') {
            // If 'All' is selected, fetch all client names
            $conferenceNames = Conference::distinct()->pluck('conference')->toArray();
        } else {
            // Fetch client names based on the selected country ID
            $conferenceNames = Conference::where('country', $id)->distinct()->pluck('conference')->toArray();
        }


        $encodedClientNames = array_map('utf8_encode', $conferenceNames);
        return response()->json(['conferenceNames' => $encodedClientNames]);
    }


    public function allTopics(Request $request, $id)
    {

        if ($request->id === 'All') {
            // If 'All' is selected, fetch all client names
            $topicNames = Conference::distinct()->pluck('article')->toArray();
        } else {
            // Fetch client names based on the selected country ID
            $topicNames = Conference::where('conference', $id)->distinct()->pluck('article')->toArray();
        }

        $encodedClientNames = array_map('utf8_encode', $topicNames);
        return response()->json(['topicNames' => $encodedClientNames]);
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

            $query->whereNotNull('conference');
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
            $query->whereNotNull('country')->whereNotNull('conference')->whereNotNull('article')->whereNotNull('user_id');
        }


        //for all country,articles,users,created,updated dates and particular conference
        if ($request->country == 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  != '' && $request->user_updated_at == null) {

            // dd($request->user_created_at);
            $query->where('user_created_at', $request->user_created_at);
        }


        if ($request->country == 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at != '') {

            // dd($request->user_created_at);
            $query->where('user_updated_at', $request->user_updated_at);
        }

        //for all country,articles,users,created,updated dates and particular artcile
        if ($request->country == 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  != '' && $request->user_updated_at != '') {

            // dd($request->user_created_at);
            $query->where('user_created_at', $request->user_created_at)->where('user_updated_at', $request->user_updated_at);
        }


        if ($request->country == 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  != '' && $request->user_updated_at == '') {

            // dd($request->user_created_at);
            $query->where('user_created_at', $request->user_created_at)->where('conference', $request->conference);
        }


        if ($request->country == 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  != '' && $request->user_updated_at != '') {

            // dd($request->user_created_at);
            $query->where('user_created_at', $request->user_created_at)->where('conference', $request->conference)->where('user_created_at', $request->user_created_at);
        }





        //particular country and all-->conferences,articles,users,created,updated dates
        if ($request->country != 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            $query->where('country', $request->country)->whereNotNull('conference');
        }

        //particular country,conference and all-->conferences,articles,users,created,updated dates

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference);
        }


        if ($request->country != 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user != 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->where('user_id', $request->user);
        }

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->where('user_id', $request->user)->where('user_created_at', $request->user_created_at);
        }



        //particular country,conference,article, all users,all dates
        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->where('article', $request->article)->whereNotNull('user_id')->orderBy('created_at', 'desc');
        }


        //particular country,conference,article, users,all dates
        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country', $request->country)->where('conference', $request->conference)->whereNotNull('article')->where('user_id', $request->user)->orderBy('created_at', 'desc');
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
        if ($request->country == 'All' && $request->conference != 'All' && $request->article != 'All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at != '' && $request->user_created_at) {
            // dd($request);
            $query->whereNotNull('country')->where('conference', $request->conference)->where('article', $request->article)->where('user_id', $request->user)->where('user_created_at', $request->user_created_at)->where('user_updated_at', $request->user_updated_at)->orderBy('created_at', 'desc');
        }




        if ($request->country != 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  != '' && $request->user_updated_at == '') {
            // dd($request);

            $query->where('country', $request->country)->where('conference', $request->conference)->where('user_created_at', $request->user_created_at);
        }

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  != '' && $request->user_updated_at != '') {
            // dd($request);

            $query->where('country', $request->country)->where('conference', $request->conference)->where('user_created_at', $request->user_created_at)->where('user_updated_at', $request->user_updated_at);
        }

        if ($request->country != 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user != 'All' && $request->user_created_at  != '') {

            $query->where('country', $request->country)->where('user_id', $request->user)->where('user_created_at', $request->user_created_at);
        }









        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('posted_by', function ($row) {
                return $row->postedby->name ?? '';
            })
            ->rawColumns(['posted_by'])
            ->make(true);
    }

    public function createAdmin(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'role' => 'required',

            ],

        );


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {


            // dd($user);

            if ($request->role == 'user') {

                $user = User::where('email', $request->email)->first();
                if ($user) {

                    return response()->json(['errors' => [['Email Already Exists']]], 422);
                } else {

                    User::create([

                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => "user",
                        'password' => '',

                    ]);
                }

                return response()->json(['message' => 'User  Created successfully']);
            } else if ($request->role == 'admin') {

                $admin = User::where('email', $request->email)->first();

                if ($admin) {

                    return response()->json(['errors' => [['Email Already Exists']]], 422);
                } else {

                    User::create([

                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => "admin",
                        'password' => '',


                    ]);
                }

                return response()->json(['message' => 'Admin  Created successfully']);
            }
        }
    }


    public function conferences(Request $request)
    {

        $conferences = ConferencesData::all();
        $countries = Conference::distinct()->pluck('country',)->toArray();
        $users = User::all();

        return view('superadmin.conferences', compact('conferences', 'countries', 'users'));
    }

    public function show()
    {

        $conferences = ConferencesData::all();

        return view('superadmin.upload', compact('conferences'));
    }


    public function getVerifyOTP()
    {


        if (!request()->session()->get('login_user_id')) {
            return redirect()->route('superadmin.login');
        }


        return view('superadmin.verify_otp', []);
    }

    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user) {
            return 'superadmin/dashboard';
        }
        return '/home';
    }

    public function loginWithOTP(UserFrontLoginWithOTPFormRequest $request)
    {
        $user = SuperAdmin::where('email', $request->email)->first();

        if ($user) {
            return $this->generateNewOTP($user);
        } else {
            return back()->withErrors(['email' => ['This Email is not exists.']]);
        }
    }

    public function postVerifyOTP(UserFrontLoginVerifyOTPFormRequest $request)
    {

        // dd(request()->session()->get('login_user_id'));

        $verification = SuperAdminVerificationCodes::where([
            'user_id'   => request()->session()->get('login_user_id'),
            'otp'       => $request->otp
        ])->where('expire_at', '>', Carbon::now())->first();

        if (!$verification) {
            return redirect()->route('superadmin.getVerifyOTP')->withErrors(['otp' => ['Invalid OTP']]);
        } else {

            $user = SuperAdmin::where('id', request()->session()->get('login_user_id'))->first();

            \Auth::login($user);

            $verification->delete();

            request()->session()->forget('login_user_id');



            return redirect($this->redirectTo());
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


    public function generateNewOTP($user)
    {
        $verification = $this->generateOTP($user);
        if ($verification) {
            return redirect()->route('superadmin.getVerifyOTP')->with('otp_sent_success', 'OTP has been sent to your email. Valid for 5 minutes');
        } else {
            abort(404);
        }
    }

    public function generateOTP($user)
    {
        $otp = rand(100000, 999999);

        $verification = SuperAdminVerificationCodes::where([
            'user_id'   => $user->id
        ])->first();

        if (!$verification) {
            $verification = new SuperAdminVerificationCodes();
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
        return view('superadmin.dashboard');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Auth::logout(); // Log the user out

            request()->session()->forget('login_user_id');

            return redirect(route('superadmin.login'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
