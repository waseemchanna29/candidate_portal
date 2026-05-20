@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-header-title">Overview</div>
            <div class="page-header-sub">Candidate registration management system</div>
        </div>
        <a href="{{ route('admin.candidates.index', ['status' => 'pending']) }}" class="btn btn-accent">
            <i class="fas fa-clock"></i> Review Pending
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="fas fa-users"></i></div>
            <div>
                <span class="stat-card-value">{{ $stats['total'] }}</span>
                <span class="stat-card-label">Total Candidates</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon yellow"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <span class="stat-card-value">{{ $stats['pending'] }}</span>
                <span class="stat-card-label">Pending Review</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <span class="stat-card-value">{{ $stats['approved'] }}</span>
                <span class="stat-card-label">Approved</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon red"><i class="fas fa-times-circle"></i></div>
            <div>
                <span class="stat-card-value">{{ $stats['rejected'] }}</span>
                <span class="stat-card-label">Rejected</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="fas fa-book-open"></i></div>
            <div>
                <span class="stat-card-value">{{ $stats['courses'] }}</span>
                <span class="stat-card-label">Active Courses</span>
            </div>
        </div>
    </div>

    <!-- Recent Candidates -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="fas fa-user-clock"></i> Recent Applications</div>
            <a href="{{ route('admin.candidates.index') }}" class="btn-outline-primary btn btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>CNIC</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCandidates as $candidate)
                            <tr>
                                <td><strong>{{ $candidate->full_name }}</strong></td>
                                <td>{{ $candidate->user->email }}</td>
                                <td>{{ $candidate->cnic }}</td>
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
                                <td colspan="6" style="text-align:center; color:var(--text-muted); padding:2rem;">
                                    No candidates yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
