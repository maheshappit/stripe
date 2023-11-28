
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form class="form-horizontal" method="POST" action="{{ route('superadmin.postVerifyOTP') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="otp">OTP</label>
                    <input id="otp" type="text" class="form-control{{ $errors->has('otp') ? ' is-invalid' : '' }}" name="otp" value="" autofocus placeholder="{{__('OTP')}}">
                    @if ($errors->has('otp'))
                        <div class="invalid-feedback">
                            {{ $errors->first('otp') }}
                        </div>
                    @endif

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
                <button type="submit" class="btn btn-success">Verify</button>
                <button type="submit" class="btn btn-info ml-2" formaction="{{route('superadmin.resndOTP')}}" >Resend</button>
            </form>
        </div>
    </div>
</div>

@endsection


