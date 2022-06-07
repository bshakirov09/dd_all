@extends('layouts.auth.default_no_logo')
@section('content')
<div class="login-logo">
    <img src=" {{asset('images/mail.png')}}" alt="mail icon">
</div>
<div class="card-body login-card-body">
        <h1 class="mb-1 text-center">Forgot Password</h1>
        <p></p>
        <p class="mb-1 text-center">
            Please, enter your email to receive a reset link
        </p>
        <p></p>

    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <form method="post" action="{{ url('login/password/email') }}">
        {!! csrf_field() !!}

        <div class="input-group mb-3">
            <input value="{{ old('email') }}" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="{{__('auth.email')}}" aria-label="{{__('auth.email')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            </div>
            @if ($errors->has('email'))
            <div class="invalid-feedback">
                {{ $errors->first('email') }}
            </div>
            @endif
        </div>
        <div class="row mb-3 ">
            <!-- /.col -->
            <div class="col-12 m-auto">
                <button type="submit" class="btn btn-success btn-block">{{__('auth.send_password')}}</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
    <p class="mb-0 text-center">
        <a href="{{ url('/login') }}" class="text-center">{{__('auth.remember_password')}}</a>
    </p>
</div>
@endsection