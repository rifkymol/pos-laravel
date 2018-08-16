@extends('layouts.auth')

@section('title')
    <title>Login</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to Start Your Session</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                @if (session('error'))
                    @alert(['type' => 'danger'])
                        {{ session('error') }}
                    @endalert
                @endif
                <div class="form-group has-feedback">
                    <span class="fa fa-envelope form-control-feedback">{{ $errors->first('email') }}</span>
                    <input type="email"
                        name="email"
                        class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                        placeholder="{{ __('Email Address') }}"
                        value="{{ old('email') }}">
                </div>
                <div class="form-group has-feedback">
                    <span class="fa fa-lock form-control-feedback"> {{ $errors->first('password') }}</span>
                    <input type="password"
                        name="password"
                        class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                        placeholder="{{ __('Password') }}">
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} > {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary btn-block btn-flat" type="submit">Sign In</button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center mb-3">
                <p>OR</p>
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fa fa-facebook mr-2"> Sign In Using Facebook</i>
                </a>
                <a href="#" class="btn btn-block btn-danger">
                        <i class="fa fa-google-plus mr-2"> Sign In Using Google+</i>
                    </a>
            </div>

            <p class="mb-1">
                <a href="#">I forgot My Password</a>
            </p>
            <p class="mb-0">
                <a href="#" class="text-center">Register New Membership</a>
            </p>
        </div>
    </div>
@endsection