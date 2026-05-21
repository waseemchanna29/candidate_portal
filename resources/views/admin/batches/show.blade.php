@extends('layouts.app')
@section('title', 'Batch Details')
@section('page-title', 'Batch Details')

@section('content')
@php
    $filled  = $batch->seats_filled;
    $pct     = $batch->total_seats > 0 ? ($filled / $batch->total_seats) * 100 : 0;
    $barClass = $pct >= 90 ? 'high' : ($pct >= 60 ? 'medium' : 'low');
@endphp

<div class="page-header">
    <div>
        <div class="page-header-title" style="font-family:monospace; font-size:1.5rem; color:var(--primary);">
            {{ $batch->batch_label }}
        </div>
        <div class="page-header-sub">{{ $batch->course->name }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.batches.edit', $batch) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.batches.index') }}" class="btn-outline-secondary btn btn-sm">
            <i class="fa-arrow-left fas"></i> Back
        </a>
    </div>
</div>

<!-- Batch Info -->
<div class="mb-3 card">
    <div class="card-body">
        <div class="profile-meta-grid">
            <div class="profile-meta-item">
                <span class="profile-meta-label">Batch Label</span>
                <span class="profile-meta-value" style="font-family:monospace; font-size:1.05rem;">{{ $batch->batch_label }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Course</span>
                <span class="profile-meta-value">{{ $batch->course->name }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Status</span>
                <span class="badge {{ $batch->status_badge_class }}" style="font-size:0.85rem;">
                    {{ ucfirst(str_replace('_', ' ', $batch->status)) }}
                </span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Start Date</span>
                <span class="profile-meta-value">{{ $batch->start_date ? $batch->start_date->format('d M, Y') : '—' }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">End Date</span>
                <span class="profile-meta-value">{{ $batch->end_date ? $batch->end_date->format('d M, Y') : '—' }}</span>
            </div>
        </div>

        <!-- Seats Bar -->
        <div style="margin-top:1.2rem; padding-top:1rem; border-top:1px solid var(--border);">
            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span style="font-weight:700; color:var(--primary);">Seat Occupancy</span>
                <span style="font-size:0.88rem; color:var(--text-muted);">
                    {{ $filled }} filled / {{ $batch->total_seats }} total —
                    <strong style="color:var(--success);">{{ $batch->seats_available }} available</strong>
                </span>
            </div>
            <div class="batch-seats-bar-wrap" style="height:12px;">
                <div class="batch-seats-bar {{ $barClass }}" style="width:{{ min($pct,100) }}%;"></div>
            </div>
        </div>

        @if($batch->notes)
        <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid var(--border);">
            <span class="profile-meta-label">Notes</span>
            <p style="margin-top:4px;">{{ $batch->notes }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Enrolled Candidates -->
<div class="mb-3 card">
    <div class="card-header">
        <div class="card-header-title">
            <i class="fas fa-user-check"></i> Enrolled Candidates
            <span style="font-weight:400; color:var(--text-muted); font-size:0.85rem;">({{ $enrolled->count() }})</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>CNIC</th><th>Code</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($enrolled as $c)
                <tr>
                    <td><strong>{{ $c->full_name }}</strong></td>
                    <td>{{ $c->user->email }}</td>
                    <td>{{ $c->cnic }}</td>
                    <td><code>{{ $c->unique_code }}</code></td>
                    <td>
                        <a href="{{ route('admin.candidates.show', $c) }}" class="btn-outline-primary btn btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; color:var(--text-muted); padding:2rem;">No enrolled candidates yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Waiting List -->
<!-- Waiting List -->
@if($waitlisted->count())
<div class="mb-3 card" style="border-top:3px solid var(--warning);">
    <div class="card-header">
        <div class="card-header-title" style="color:#856404;">
            <i class="fas fa-hourglass-half"></i> Waiting List
            <span style="font-weight:400; font-size:0.85rem;">({{ $waitlisted->count() }})</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>CNIC</th><th>Seats Available</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($waitlisted as $c)
                <tr>
                    <td><strong>{{ $c->full_name }}</strong></td>
                    <td>{{ $c->user->email }}</td>
                    <td>{{ $c->cnic }}</td>
                    <td>
                        @if($batch->seats_available > 0)
                            <span class="badge badge-approved">{{ $batch->seats_available }} available</span>
                        @else
                            <span class="badge badge-rejected">No seats</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.candidates.show', $c) }}" class="btn-outline-primary btn btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($batch->seats_available > 0)
                            <form action="{{ route('admin.batches.promote', [$batch, $c]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Promote {{ addslashes($c->full_name) }} from waiting list?')">
                                    <i class="fas fa-arrow-up"></i> Promote
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Pending in this batch -->
@if($pending->count())
<div class="mb-3 card">
    <div class="card-header">
        <div class="card-header-title">
            <i class="fas fa-clock"></i> Pending Applications
            <span style="font-weight:400; color:var(--text-muted); font-size:0.85rem;">({{ $pending->count() }})</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($pending as $c)
                <tr>
                    <td><strong>{{ $c->full_name }}</strong></td>
                    <td>{{ $c->user->email }}</td>
                    <td>
                        <a href="{{ route('admin.candidates.show', $c) }}" class="btn-outline-primary btn btn-sm">
                            <i class="fas fa-eye"></i> Review
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection