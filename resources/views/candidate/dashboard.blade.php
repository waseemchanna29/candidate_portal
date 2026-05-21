@extends('layouts.app')
@section('title', 'My Application')
@section('page-title', 'My Application Status')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-header-title">Welcome, {{ Auth::user()->name }}</div>
            <div class="page-header-sub">Here is the status of your submitted application</div>
        </div>
    </div>

    @if ($candidate)
        <!-- Status Banner -->
        <div class="mb-3 card"
            style="border-left: 5px solid
        {{ $candidate->status === 'approved' ? 'var(--success)' : ($candidate->status === 'rejected' ? 'var(--danger)' : 'var(--accent)') }};">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between" style="flex-wrap:wrap; gap:1rem;">
                    <div>
                        <span
                            style="font-size:0.82rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; font-weight:600;">
                            Application Status
                        </span>
                        <div style="margin-top:6px;">
                            <span class="badge {{ $candidate->getStatusBadgeClass() }}"
                                style="font-size:1rem; padding:0.5rem 1.2rem;">
                                @if ($candidate->status === 'approved')
                                    <i class="fas fa-check-circle"></i> Approved
                                @elseif($candidate->status === 'rejected')
                                    <i class="fas fa-times-circle"></i> Rejected
                                @else
                                    <i class="fas fa-hourglass-half"></i> Pending Review
                                @endif
                            </span>
                        </div>
                    </div>

                    @if ($candidate->status === 'approved' && $candidate->unique_code)
                        <div>
                            <span
                                style="font-size:0.82rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; font-weight:600;">
                                Your Unique Code
                            </span>
                            <div style="margin-top:6px;">
                                <div class="unique-code-display">
                                    <i class="fas fa-id-badge"></i>
                                    {{ $candidate->unique_code }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($candidate->admin_notes)
                    <div class="alert alert-info" style="margin-top:1rem; margin-bottom:0;">
                        <i class="fas fa-comment-alt"></i>
                        <div><strong>Admin Note:</strong> {{ $candidate->admin_notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Personal Info -->
        <div class="mb-3 card">
            <div class="card-header">
                <div class="card-header-title"><i class="fas fa-user"></i> Personal Information</div>
            </div>
            <div class="card-body">
                <div class="profile-meta-grid">
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">Full Name</span>
                        <span class="profile-meta-value">{{ $candidate->full_name }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">Phone</span>
                        <span class="profile-meta-value">{{ $candidate->phone }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">CNIC</span>
                        <span class="profile-meta-value">{{ $candidate->cnic }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">City</span>
                        <span class="profile-meta-value">{{ $candidate->city ?? '—' }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">Email</span>
                        <span class="profile-meta-value">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">Applied On</span>
                        <span class="profile-meta-value">{{ $candidate->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
                @if ($candidate->course)
                    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid var(--border);">
                        <span class="profile-meta-label">Enrolled Course</span>
                        <div style="margin-top:6px; display:inline-flex; align-items:center; gap:0.8rem; flex-wrap:wrap;">
                            <strong style="color:var(--primary); font-size:1rem;">{{ $candidate->course->name }}</strong>
                            <span class="course-meta-chip duration">
                                <i class="fas fa-clock"></i> {{ $candidate->course->duration_label }}
                            </span>
                            @if ($candidate->course->pricingModel)
                                <span class="course-meta-chip price">
                                    <i class="fas fa-tag"></i> {{ $candidate->course->pricingModel->formatted_price }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($candidate->batch)
                    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid var(--border);">
                        <span class="profile-meta-label">Batch</span>
                        <div style="margin-top:6px; display:flex; align-items:center; gap:0.8rem; flex-wrap:wrap;">
                            <strong style="font-family:monospace; font-size:1.05rem; color:var(--primary);">
                                {{ $candidate->batch->batch_label }}
                            </strong>
                            <span class="badge {{ $candidate->batch->status_badge_class }}">
                                {{ ucfirst(str_replace('_', ' ', $candidate->batch->status)) }}
                            </span>
                            @if ($candidate->batch->start_date)
                                <span style="color:var(--text-muted); font-size:0.88rem;">
                                    <i class="fas fa-calendar-alt"></i>
                                    Starts {{ $candidate->batch->start_date->format('d M, Y') }}
                                </span>
                            @endif
                        </div>
                        @if ($candidate->is_waitlisted)
                            <div class="alert alert-warning" style="margin-top:0.8rem; margin-bottom:0;">
                                <i class="fas fa-hourglass-half"></i>
                                <div>
                                    <strong>You are on the waiting list</strong> for this batch.
                                    You will be notified when a seat becomes available.
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Education -->
        <div class="mb-3 card">
            <div class="card-header">
                <div class="card-header-title"><i class="fas fa-graduation-cap"></i> Educational History</div>
            </div>
            <div class="card-body">
                @forelse($candidate->educations as $edu)
                    <div style="padding:0.8rem 0; border-bottom:1px solid var(--border);">
                        <div class="d-flex align-items-center justify-content-between" style="flex-wrap:wrap; gap:0.5rem;">
                            <div>
                                <strong>{{ $edu->degree }}</strong> in {{ $edu->field_of_study }}
                                <div style="color:var(--text-muted); font-size:0.88rem;">
                                    {{ $edu->institution }} &bull;
                                    {{ $edu->start_year }} – {{ $edu->end_year ?? 'Present' }}
                                </div>
                            </div>
                            @if ($edu->grade)
                                <span class="badge badge-approved">Grade: {{ $edu->grade }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No education records found.</p>
                @endforelse
            </div>
        </div>

        <!-- Experience -->
        @if ($candidate->experiences->count())
            <div class="mb-3 card">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-briefcase"></i> Work Experience</div>
                </div>
                <div class="card-body">
                    @foreach ($candidate->experiences as $exp)
                        <div style="padding:0.8rem 0; border-bottom:1px solid var(--border);">
                            <strong>{{ $exp->job_title }}</strong> at {{ $exp->company_name }}
                            <div style="color:var(--text-muted); font-size:0.88rem;">
                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} –
                                {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                            </div>
                            @if ($exp->description)
                                <div style="margin-top:4px; font-size:0.9rem;">{{ $exp->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Payment Receipt -->
        @if ($candidate->paymentReceipt)
            <div class="mb-3 card">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-receipt"></i> Payment Receipt</div>
                </div>
                <div class="card-body">
                    <div class="profile-meta-grid">
                        <div class="profile-meta-item">
                            <span class="profile-meta-label">Receipt No.</span>
                            <span class="profile-meta-value">{{ $candidate->paymentReceipt->receipt_number }}</span>
                        </div>
                        <div class="profile-meta-item">
                            <span class="profile-meta-label">Amount</span>
                            <span class="profile-meta-value">PKR
                                {{ number_format($candidate->paymentReceipt->amount, 2) }}</span>
                        </div>
                        <div class="profile-meta-item">
                            <span class="profile-meta-label">Bank</span>
                            <span class="profile-meta-value">{{ $candidate->paymentReceipt->bank_name }}</span>
                        </div>
                        <div class="profile-meta-item">
                            <span class="profile-meta-label">Payment Date</span>
                            <span
                                class="profile-meta-value">{{ \Carbon\Carbon::parse($candidate->paymentReceipt->payment_date)->format('d M, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No application found for your account. Please contact support.
        </div>
    @endif
@endsection
