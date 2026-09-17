@extends('layouts.app')

@section('title', 'SuperAdmin Dashboard')

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $totalCompanies ?? count($companies) }}</h3>
                    <p>Total Companies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $totalUsers ?? 0 }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner text-white">
                    <h3>{{ $totalShortUrls ?? 0 }}</h3>
                    <p class="text-white">Total Short URLs</p>
                </div>
                <div class="icon">
                    <i class="fas fa-link"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ isset($invitations) ? count($invitations) : 0 }}</h3>
                    <p>Invitations Sent</p>
                </div>
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Invite to new company form card -->
        <div class="col-md-5">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-plus mr-1"></i> Invite to New Company
                    </h3>
                </div>
                <form method="POST" action="{{ route('invitations.new-company') }}">
                    @csrf
                    <div class="card-body">
                        <p class="text-muted small">
                            SuperAdmin cannot create short urls or see company short url lists. Use this form to invite someone to create a brand-new company.
                        </p>

                        <div class="form-group">
                            <label for="company_name">New Company Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <input id="company_name" type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="e.g. Acme Corp" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Invitee Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="user@company.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                </div>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="Member">Member</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle text-info mr-1"></i> Admin is not selectable — SuperAdmin cannot invite an Admin into a new company.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-paper-plane mr-1"></i> Send Company Invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Companies Table card -->
        <div class="col-md-7">
            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-building mr-1"></i> Registered Companies
                    </h3>
                    <div class="card-tools ml-auto">
                        <span class="badge badge-info">{{ count($companies) }} Companies</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Company Name</th>
                                    <th class="text-center">Active Users</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($companies as $index => $company)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="font-weight-bold">
                                            <i class="fas fa-building text-secondary mr-2"></i>{{ $company->name }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-pill badge-primary px-3 py-1">
                                                <i class="fas fa-users mr-1"></i> {{ $company->users_count }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $company->created_at ? $company->created_at->format('M d, Y H:i') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-building fa-2x mb-2 d-block text-secondary"></i>
                                            No companies registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Invitations sent card -->
            @if(isset($invitations) && count($invitations) > 0)
            <div class="card card-outline card-info shadow-sm mt-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-history mr-1"></i> Sent Invitations History
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover table-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Invitee Email</th>
                                    <th>Company</th>
                                    <th>Role</th>
                                    <th>Accept Link</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invitations as $invitation)
                                    <tr>
                                        <td class="font-weight-bold">{{ $invitation->email }}</td>
                                        <td>{{ $invitation->company->name ?? 'N/A' }}</td>
                                        <td><span class="badge badge-info">{{ $invitation->role->value }}</span></td>
                                        <td>
                                            @php $acceptUrl = $invitation->getAcceptUrl(); @endphp
                                            <button type="button" class="btn btn-xs btn-outline-primary" onclick="copyToClipboard('{{ $acceptUrl }}', this)">
                                                <i class="fas fa-copy"></i> Copy Link
                                            </button>
                                            <a href="{{ $acceptUrl }}" target="_blank" class="btn btn-xs btn-outline-secondary ml-1">
                                                <i class="fas fa-external-link-alt"></i> Open
                                            </a>
                                        </td>
                                        <td>
                                            @if($invitation->isAccepted())
                                                <span class="badge badge-success"><i class="fas fa-check"></i> Accepted</span>
                                            @elseif($invitation->isExpired())
                                                <span class="badge badge-danger"><i class="fas fa-times"></i> Expired</span>
                                            @else
                                                <span class="badge badge-warning text-white"><i class="fas fa-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
