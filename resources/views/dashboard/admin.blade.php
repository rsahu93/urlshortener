@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $totalCompanyUrls ?? count($shortUrls) }}</h3>
                    <p>Company Short URLs</p>
                </div>
                <div class="icon">
                    <i class="fas fa-link"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $totalCompanyUsers ?? 0 }}</h3>
                    <p>Company Team Members</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner text-white">
                    <h3>{{ isset($invitations) ? count($invitations) : 0 }}</h3>
                    <p class="text-white">Company Invitations</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Invite team member form -->
        <div class="col-md-4">
            <div class="card card-info card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-plus mr-1"></i> Invite User to Company
                    </h3>
                </div>
                <form method="POST" action="{{ route('invitations.company') }}">
                    @csrf
                    <div class="card-body">
                        <p class="text-muted small">
                            Admin cannot create short urls, but can see every short url created within your company (<strong>{{ auth()->user()->company->name ?? 'Company' }}</strong>).
                        </p>

                        <div class="form-group">
                            <label for="email">Invitee Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="colleague@company.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                </div>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="Sales">Sales</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle text-info mr-1"></i> Admin can only invite <strong>Sales</strong> or <strong>Manager</strong> — not another Admin, and not a Member.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-paper-plane mr-1"></i> Send Invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Short URLs Table card -->
        <div class="col-md-8">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-list mr-1"></i> All Short URLs in Your Company
                    </h3>
                    <div class="card-tools ml-auto">
                        <span class="badge badge-primary">{{ count($shortUrls) }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Short Code</th>
                                    <th>Original URL</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shortUrls as $shortUrl)
                                    @php $fullShortUrl = url($shortUrl->short_code); @endphp
                                    <tr>
                                        <td>
                                            <span class="badge badge-dark short-code-badge px-2 py-1">
                                                {{ $shortUrl->short_code }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ $shortUrl->original_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 260px;" title="{{ $shortUrl->original_url }}">
                                                {{ $shortUrl->original_url }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border">
                                                <i class="fas fa-user text-muted mr-1"></i> {{ $shortUrl->creator->name ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $shortUrl->created_at ? $shortUrl->created_at->format('M d, Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-outline-primary" onclick="copyToClipboard('{{ $fullShortUrl }}', this)">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                            <a href="{{ $fullShortUrl }}" target="_blank" class="btn btn-xs btn-outline-success ml-1">
                                                <i class="fas fa-external-link-alt"></i> Test
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-link fa-2x mb-2 d-block text-secondary"></i>
                                            No short urls created yet in your company.
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
                        <i class="fas fa-paper-plane mr-1"></i> Company Invitations Sent
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover table-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Invitee Email</th>
                                    <th>Role</th>
                                    <th>Accept Link</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invitations as $invitation)
                                    <tr>
                                        <td class="font-weight-bold">{{ $invitation->email }}</td>
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
