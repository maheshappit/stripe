@extends('layouts.app') 
@section('content')

<style>

.button-container {
    display: flex;
    justify-content: space-between;
}

.formpanel {
    margin: 0; /* Remove any default margin on the form panels */
}
</style>

<!-- Header end -->
<div class="authpages">
  <div class="container">
    <div class="row">
      <div class="col-lg-5">
        <div class="useraccountwrap">
          <div class="userccount whitebg">
              <div class="formpanel mt-2">
               
            <div class="button-container">
            <div class="socialLogin">
                  <h5>{{__('Verify OTP')}}</h5>
                </div>
 
    <form class="form-horizontal" method="POST" action="{{ route('user.postVerifyOTP') }}">
        {{ csrf_field() }}
        <div class="formpanel">
            <div class="formrow{{ $errors->has('otp') ? ' has-error' : '' }}">
                <input id="otp" type="text" class="form-control" name="otp" value="" autofocus placeholder="{{__('OTP')}}">
                @if ($errors->has('otp'))
                    <span class="help-block mt-4">
                        <strong>{{ $errors->first('otp') }}</strong>
                    </span>
                @endif

                @if (session('otp_sent_success'))
                    <div class="text-success mt-4">
                        {{ session('otp_sent_success') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="formpanel">
            <input type="submit" class="btn btn-success" value="{{__('Verify')}}">
        </div>
    </form>

    <form class="mt-2" method="POST" action="{{ route('user.resndOTP') }}">
        {{ csrf_field() }}
        <div class="formpanel">
            <input type="submit" class="btn btn-info" value="{{__('Resend')}}">
        </div>
    </form>
</div>

                <!-- sign up form -->
                <div class="newuser">
                    <div class="newuser"><i class="fas fa-user" aria-hidden="true"></i> {{__('Have Account')}}? <a href="{{route('login')}}">{{__('Sign in')}}</a></div>
                </div>
                <!-- sign up form end-->
              </div>
            </div>
            <!-- login form -->
        </div>
      </div>
      <div class="col-lg-7">
        <div class="loginpageimg">
          <img src="{{asset('/')}}images/login-page-banner.png" alt="">
        </div>
      </div>
    </div>
  </div>
</div> 
@endsection