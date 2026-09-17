@extends('layouts.app')

@section('title', 'Accept Invitation')
@section('full_page', true)

@section('content')
<div class="register-box" style="width: 440px; max-width: 90%;">
    <div class="register-logo mb-3">
        <a href="#" class="font-weight-bold text-dark">
            <i class="fas fa-scissors text-warning mr-2"></i><b>URL</b> Shortener
        </a>
    </div>

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header text-center bg-white py-3">
            <h4 class="mb-0 font-weight-bold text-success"><i class="fas fa-user-check mr-1"></i> You've Been Invited!</h4>
            <small class="text-muted">Complete your profile setup below to activate your account</small>
        </div>
        <div class="card-body">
            <!-- Invitation summary box -->
            <div class="callout callout-info mb-4 bg-light py-2">
                <div class="small">
                    <div><strong>Email:</strong> {{ $invitation->email }}</div>
                    <div><strong>Assigned Role:</strong> <span class="badge badge-info">{{ $invitation->role->value }}</span></div>
                    <div><strong>Company:</strong> <span class="badge badge-secondary">{{ $invitation->company->name ?? 'N/A' }}</span></div>
                </div>
            </div>

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

            <form method="POST" action="{{ url()->full() }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="name" class="font-weight-normal text-secondary">Your Full Name</label>
                    <div class="input-group">
                        <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user text-secondary"></span></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="font-weight-normal text-secondary">Set Password</label>
                    <div class="input-group">
                        <input id="password" type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock text-secondary"></span></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmation" class="font-weight-normal text-secondary">Confirm Password</label>
                    <div class="input-group">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Re-type password" required minlength="8">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-check-double text-secondary"></span></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-block font-weight-bold py-2">
                    <i class="fas fa-check-circle mr-1"></i> Create Account & Join Company
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
