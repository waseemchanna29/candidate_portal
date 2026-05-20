@extends('layouts.app')
@section('title', 'Candidates')
@section('page-title', 'Candidates')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">All Candidates</div>
        <div class="page-header-sub">Search, filter, and manage applicants</div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.candidates.index') }}">
    <div class="filter-bar">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control"
                   placeholder="Name, CNIC, Email, Code..." value="{{ request('search') }}">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div style="align-self:flex-end;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.candidates.index') }}" class="btn-outline-secondary btn">
                <i class="fas fa-times"></i> Clear
            </a>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>CNIC</th>
                        <th>Unique Code</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $candidate->full_name }}</strong></td>
                        <td>{{ $candidate->user->email }}</td>
                        <td>{{ $candidate->phone }}</td>
                        <td>{{ $candidate->cnic }}</td>
                        <td>
                            @if($candidate->unique_code)
                                <code style="background:var(--light-bg); padding:2px 8px; border-radius:4px; font-size:0.85rem;">
                                    {{ $candidate->unique_code }}
                                </code>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td>{{ $candidate->created_at->format('d M, Y') }}</td>
                        <td>
                            <span class="badge {{ $candidate->getStatusBadgeClass() }}">
                                {{ ucfirst($candidate->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.candidates.show', $candidate) }}"
                               class="btn-outline-primary btn btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center; color:var(--text-muted); padding:2.5rem;">
                            <i class="fas fa-user-slash" style="font-size:2rem; margin-bottom:0.5rem; display:block;"></i>
                            No candidates found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($candidates->hasPages())
        <div style="padding:1rem 1.4rem; border-top:1px solid var(--border);">
            {{ $candidates->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection