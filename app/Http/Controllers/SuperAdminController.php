<?php

namespace App\Http\Controllers;

use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use App\Models\User;

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
                        'password'=>'',

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
                        'password'=>'',


                    ]);
                }

                return response()->json(['message' => 'Admin  Created successfully']);
            }
        }
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
     * Display the specified resource.
     */
    public function show(string $id)
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
