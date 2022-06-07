@extends('layouts.auth.default_no_logo')
@section('content')
<div class="login-logo">
    <img src=" {{asset('images/mail.png')}}" alt="mail icon">
</div>
<div class="card-body login-card-body">
    <div class="card-body login-card-body">
        <!-- <p class="login-box-msg">{{__('auth.login_title')}}</p> -->
        <h1 class="mb-1 text-center">Verify Your E-mail</h1>
        <p></p>
        <p class="mb-1 text-center">
            Please, check your email. We have sent you a reset password link to <b>{{$email}}</b> <a href="{{$url}}">Verification Link</a>
        </p>
        <p></p>
        <a href="{{url('/login')}}" class="btn btn-block btn-success"> {{__('auth.continue')}}
        </a>
    </div>
</div>
@endsection