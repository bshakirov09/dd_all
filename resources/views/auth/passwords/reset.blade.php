@extends('layouts.auth.default_no_logo')
@section('content')
    <div class="card-body login-card-body">
        <h3 class="mb-1 text-center" >Create a <span class="text-success">new password</span></h3>
        <p></p>
        <form method="POST" action="{{url('login/password/reset') }}">
            {!! csrf_field() !!}

            <input type="hidden" name="token" value="{{ $token }}">

            <input value="{{ $email }}" name="email" type="hidden">

            <div class="input-group mb-3">
                <input value="{{ old('password') }}" type="password" class="form-control  {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('auth.password_new')}}" aria-label="{{__('auth.password_new')}}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
                @if ($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="input-group mb-3">
                <input value="{{ old('password_confirmation') }}" type="password" class="form-control  {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" placeholder="{{__('auth.password_confirmation')}}" aria-label="{{__('auth.password_confirmation')}}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
                @if ($errors->has('password_confirmation'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                @endif
            </div>
            @include('flash::message')
            <div class="row mb-2">
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-block">{{__('auth.submit')}}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <p class="mb-1 text-center">
            <a href="{{ url('/login') }}" class="text-center">{{__('auth.remember_password')}}</a>
        </p>
    </div>
    <!-- /.login-card-body -->
@endsection
