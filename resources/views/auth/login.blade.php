@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User Login') }}</div>

                <div class="card-body">
                <form action="{{ route('user.loginWithOTP') }}" id="loginform" method="post">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                     
                      

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">

                            <!-- <a href="{{ route('admin.login')}}" class="btn btn-danger">
                                    {{ __('Admin Login') }}
</a> -->
                                
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <!-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection




@section('content')
    <form action="{{ route('user.loginWithOTP') }}" id="loginform" method="post">
        @csrf

        @if (session('status'))
            <div class="alert alert-success m-t-10">
                {{ session('status') }}
            </div>
        @endif
        <div class="form-group mb-3">
            <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                   placeholder="@lang('auth.email')" value="{{ old('email') }}" autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>
        
        

        <div class="row">
            <!-- <div class="col-sm-6">
                <div class="checkbox icheck">
                    <label>
                        <div class="icheckbox_flat-green" aria-checked="false" aria-disabled="false" style="position: relative;">
                            <input  type="checkbox" {{ old('remember') ? 'checked' : '' }}  name="remember_me" id="remember_me" class="flat-red"  style="position: absolute; opacity: 0;">
                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                        </div>
                        @lang('auth.rememberMe')
                    </label>
                </div>
            </div> -->

            <!-- <div class="col-sm-6 text-right">
                <a href="#" id="to-recover">@lang('app.forgotPassword')</a>
            </div> -->

            <!-- /.col -->
            <div class="col-sm-12 mt-4">
                <button type="submit" id="save-form" class="btn btn-primary btn-block">@lang('auth.login')</button>
            </div>
            <!-- /.col -->
        </div>
      

    </form>

    
@endsection