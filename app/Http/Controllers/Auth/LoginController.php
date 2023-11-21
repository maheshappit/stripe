<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserFrontLoginVerifyOTPFormRequest;
use App\Http\Requests\UserFrontLoginWithOTPFormRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Froiden\Envato\Traits\AppBoot;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\FrontCmsHeader;
use App\ThemeSetting;
use App\GlobalSetting;
use Carbon\Carbon;
use App\Mail\LoginOTP;
use Mail;
use App\Models\User;
use App\Providers\RouteServiceProvider;




class LoginController extends Controller
{
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }


    public function loginWithOTP(UserFrontLoginWithOTPFormRequest $request)
    {
        $user = User::where('email', $request->email)->first();

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
                return redirect()->route('user.getVerifyOTP')->with('otp_sent_success', 'OTP has been sent to your email. Valid for 5 minutes');
            }else{
                abort(404);
            }
        }else{
            return back()->withErrors(['email' => ['This Email is not exists.']]);
        }
    }

    public function getVerifyOTP()
    {
        if(!request()->session()->get('login_user_id')){
            return redirect()->route('login');
        }

        
        return view('auth.verify_otp', [
            
        ]);
    }

    public function postVerifyOTP(UserFrontLoginVerifyOTPFormRequest $request)
    {

        $verification = VerificationCode::where([
            'user_id'   => request()->session()->get('login_user_id'),
            'otp'       => $request->otp
        ])->where('expire_at', '>', Carbon::now())->first();

        if(!$verification){
            return redirect()->route('user.getVerifyOTP')->withErrors(['otp' => ['Invalid OTP']]);
        }

        $user = User::where('id', request()->session()->get('login_user_id'))->first();

        \Auth::login($user);

        $verification->delete();

        request()->session()->forget('login_user_id');

        return redirect($this->redirectTo());
    }

    public function generateNewOTP($user)
    {
        $verification = $this->generateOTP($user);
        if($verification){
            return redirect()->route('user.getVerifyOTP')->with('otp_sent_success', 'OTP has been sent to your email. Valid for 5 minutes');
        }else{
            abort(404);
        }
    }


    public function generateOTP($user)
    {
        $otp = rand(100000, 999999);
  
        $verification = VerificationCode::where([
            'user_id'   => $user->id
        ])->first();

        if(!$verification){
            $verification = new VerificationCode();
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


    public function showLoginForm()
    {
        /* if (!$this->isLegal()) {
            return redirect('verify-purchase');
        } */

        if(auth()->check()) {
            // if(auth()->user()->is_superadmin) {
            //     return redirect(route('superadmin.dashboard.index'));
            // }
            return redirect('home');
        }

        // $setting = $this->global;
        // $global = $this->global;
        // $frontTheme = ThemeSetting::whereNull('company_id')->first();

        // $headerData = FrontCmsHeader::first();

        return view('auth.login');
    }

    protected function credentials(\Illuminate\Http\Request $request)
    {

        return [
            'email' => $request->{$this->username()},
            'password' => $request->password,
            'status' => 'active'
        ];
    }

    protected function validateLogin(\Illuminate\Http\Request $request)
    {

        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ];

        // User type from email/username
        $user = User::where($this->username(), $request->{$this->username()})->first();


        // if (module_enabled('Subdomain')) {
        //     $rules = $this->rulesValidate($user);
        // }

        $this->rulesValidate($user);
        // $this->validate($request, $rules);
    }

    protected function redirectTo()
    {
        $user = auth()->user();
        if($user->is_superadmin) {
            return 'super-admin/dashboard';
        }
        return '/home';
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $this->guard()->logout();

        $request->session()->invalidate();

        

        return redirect(route('login'));
    }

    private function rulesValidate($user){
        if (Str::contains(url()->previous(),'super-admin-login')) {
            $rules = [
                $this->username() => [
                    'required',
                    'string',
                    Rule::exists('users', 'email')->where(function ($query) {
                        $query->where('is_superadmin', '1');
                    })
                ],
                'password' => 'required|string',
            ];
        }else{

            $rules = [
                $this->username() => [
                    'required',
                    'string',
                    Rule::exists('users', 'email')->where(function ($query) use ($company) {
                        $query->where('company_id', $company->id);
                    })
                ],
                'password' => 'required|string',

            ];
        }
        return $rules;
    }

    private function get_domain()
    {
        $host = $_SERVER['HTTP_HOST'];
        $myhost = strtolower(trim($host));
        $count = substr_count($myhost, '.');
        if ($count === 2) {
            if (strlen(explode('.', $myhost)[1]) > 3) $myhost = explode('.', $myhost, 2)[1];
        } else if ($count > 2) {
            $myhost = get_domain(explode('.', $myhost, 2)[1]);
        }
        return $myhost;
    }
}