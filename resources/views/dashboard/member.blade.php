@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $myUrlCount ?? count($shortUrls) }}</h3>
                    <p>Your Short URLs</p>
                </div>
                <div class="icon">
                    <i class="fas fa-link"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ auth()->user()->role->value }}</h3>
                    <p>Your Current Role</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box bg-secondary shadow-sm">
                <div class="inner">
                    <h3>{{ auth()->user()->company->name ?? 'Company' }}</h3>
                    <p>Your Company</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Create Short URL card (Sales or Manager) -->
        <div class="col-md-5">
            @if ($canCreate)
                <div class="card card-success card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-plus-circle mr-1"></i> Create Short URL
                        </h3>
                    </div>
                    <form method="POST" action="{{ route('short-urls.store') }}">
                        @csrf
                        <div class="card-body">
                            <p class="text-muted small">
                                As a <strong>{{ auth()->user()->role->value }}</strong>, you are authorized to shorten long web URLs.
                            </p>

                            <div class="form-group">
                                <label for="original_url">URL to Shorten</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input id="original_url" type="url" name="original_url" class="form-control" value="{{ old('original_url') }}" placeholder="https://example.com/long-page-link" required>
                                </div>
                                <small class="form-text text-muted mt-2">
                                    <i class="fas fa-info-circle text-info mr-1"></i> Must be a valid web URL starting with http:// or https://.
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-scissors mr-1"></i> Shorten URL Now
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="card card-warning card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold text-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Creation Restricted
                        </h3>
                    </div>
                    <div class="card-body text-muted">
                        <p class="mb-0">
                            Your role (<strong>{{ auth()->user()->role->value }}</strong>) cannot create short urls. Only <strong>Sales</strong> and <strong>Manager</strong> roles are permitted to generate new short links.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Short URLs Table card -->
        <div class="col-md-7">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-link mr-1"></i> Your Short URLs
                    </h3>
                    <div class="card-tools ml-auto">
                        <span class="badge badge-primary">{{ count($shortUrls) }} Created</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Short Code</th>
                                    <th>Original URL</th>
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
                                            <a href="{{ $shortUrl->original_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 220px;" title="{{ $shortUrl->original_url }}">
                                                {{ $shortUrl->original_url }}
                                            </a>
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
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-link fa-2x mb-2 d-block text-secondary"></i>
                                            No short urls created yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
