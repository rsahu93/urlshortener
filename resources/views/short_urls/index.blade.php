@extends('layouts.app')

@section('title', 'Short URLs')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-link mr-1"></i> Short URLs List
                    </h3>
                    <div class="card-tools ml-auto">
                        <span class="badge badge-primary">{{ $shortUrls->total() }} Total Links</span>
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
                                            <a href="{{ $shortUrl->original_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 350px;" title="{{ $shortUrl->original_url }}">
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
                                                <i class="fas fa-copy"></i> Copy Link
                                            </button>
                                            <a href="{{ $fullShortUrl }}" target="_blank" class="btn btn-xs btn-outline-success ml-1">
                                                <i class="fas fa-external-link-alt"></i> Test Redirect
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-link fa-3x mb-3 d-block text-secondary"></i>
                                            <h5>No short urls available</h5>
                                            <p class="small text-muted mb-0">No short urls match your view permissions.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($shortUrls->hasPages())
                    <div class="card-footer bg-white d-flex justify-content-center">
                        {{ $shortUrls->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
