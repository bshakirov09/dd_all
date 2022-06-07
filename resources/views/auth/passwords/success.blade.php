@extends('layouts.auth.default_no_logo')
@section('content')
<div class="login-logo">
    <img src=" {{asset('images/checked.png')}}" alt="mail icon">
</div>
<div class="card-body login-card-body">
    <div class="card-body login-card-body">
        <!-- <p class="login-box-msg">{{__('auth.login_title')}}</p> -->
        <h3 class="mb-1 text-center">Your password has been changed successfully</h3>
        <p></p>
        <a href="{{url('/login')}}" class="btn btn-block btn-success"> {{__('auth.sign_in')}}
        </a>
    </div>
</div>
@endsection