@extends('layouts.app')

@section('title', 'Log In')
@section('full_page', true)

@section('content')
<div class="login-box" style="width: 400px; max-width: 90%;">
    <div class="login-logo mb-3">
        <a href="#" class="font-weight-bold text-dark">
            <i class="fas fa-scissors text-warning mr-2"></i><b>URL</b> Shortener
        </a>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header text-center bg-white py-3">
            <h4 class="mb-0 font-weight-bold">Sign In to Dashboard</h4>
            <small class="text-muted">Enter your account credentials to continue</small>
        </div>
        <div class="card-body login-card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show text-sm mb-3" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show text-sm mb-3" role="alert">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group mb-3">
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email address" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope text-secondary"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock text-secondary"></span>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center mb-3">
                    <div class="col-8">
                        <div class="icheck-primary d-flex align-items-center">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" class="ml-2 mb-0 font-weight-normal text-secondary">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            Sign In <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer bg-light text-center py-3">
            <div class="small text-muted">
                <div><strong>SuperAdmin:</strong> <code>superadmin@example.com</code> / <code>password</code></div>
                <div><strong>Company Admin:</strong> <code>admin@example.com</code> / <code>password</code></div>
            </div>
        </div>
    </div>
</div>
@endsection
