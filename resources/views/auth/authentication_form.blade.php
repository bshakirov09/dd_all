@extends('layouts.auth.default')
@section('content')
<div class="card-body login-card-body">
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{__('auth.login_title')}}</p>

        <form action="{{ url('/login/authenticate') }}" method="post">
            {!! csrf_field() !!}

            <div class="input-group mb-3">
                <input value="{{ old('email') }}" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="{{ __('auth.email') }}" aria-label="{{ __('auth.email') }}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>

            </div>

            <div class="input-group mb-3">
                <input value="{{ old('password') }}" type="password" class="form-control  {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('auth.password')}}" aria-label="{{__('auth.password')}}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
            </div>
            @include('flash::message')
            <div class="row mb-2">
                <div class="col-8">
                    <div class="checkbox icheck">
                        <label><a href="{{ url('/password/reset') }}">{{__('auth.forgot_password')}}</a>
                        </label>
                    </div>
                </div>
            </div>

            <div class="social-auth-links text-center mb-3">
                <button type="submit" class="btn btn-success btn-block">{{__('auth.sign_in')}}</button>
            </div>
        </form>
        <!-- /.social-auth-links -->

        <p class="mb-0 text-center">
            <a href="{{ url('/register') }}" class="text-center">{{__('auth.register')}}</a>
        </p>
    </div>
</div>
@endsection