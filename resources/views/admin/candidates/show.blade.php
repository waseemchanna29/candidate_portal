@extends('layouts.app')
@section('title', 'Candidate Details')
@section('page-title', 'Candidate Details')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">{{ $candidate->full_name }}</div>
        <div class="page-header-sub">Application submitted {{ $candidate->created_at->format('d M, Y') }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.candidates.index') }}" class="btn-outline-secondary btn btn-sm">
            <i class="fa-arrow-left fas"></i> Back
        </a>
        <span class="badge {{ $candidate->getStatusBadgeClass() }}" style="font-size:0.9rem; padding:0.5rem 1.1rem;">
            {{ ucfirst($candidate->status) }}
        </span>
    </div>
</div>

<!-- Approve / Reject Forms -->
@if($candidate->status === 'pending')
<div class="mb-3 row">
    <div class="col-6">
        <div class="card" style="border-top:3px solid var(--success);">
            <div class="card-header">
                <div class="card-header-title" style="color:var(--success);">
                    <i class="fas fa-check-circle"></i> Approve Application
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.candidates.approve', $candidate) }}" method="POST">
                    @csrf
                    <div class="mb-form">
                        <label class="form-label">Assign to Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">-- No batch assignment --</option>
                            @foreach(\App\Models\Batch::where('course_id', $candidate->course_id)
                                ->whereIn('status', ['open', 'full'])
                                ->orderBy('year')->orderBy('batch_no')->get() as $batch)
                                <option value="{{ $batch->id }}">
                                    {{ $batch->batch_label }} —
                                    {{ $batch->seats_available }} seats left
                                    {{ $batch->seats_available <= 0 ? '(will be waitlisted)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color:var(--text-muted); font-size:0.8rem;">
                            Selecting a full batch will place the candidate on the waiting list.
                            Leave empty to approve without batch assignment.
                        </small>
                    </div>
                    <div class="mb-form">
                        <label class="form-label">Admin Notes (optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="3"
                                  placeholder="Any note for the candidate..."></textarea>
                    </div>
                    <button type="submit" class="btn-block btn btn-success"
                            onclick="return confirm('Approve this application?')">
                        <i class="fas fa-check"></i> Approve & Assign Code
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card" style="border-top:3px solid var(--danger);">
            <div class="card-header">
                <div class="card-header-title" style="color:var(--danger);">
                    <i class="fas fa-times-circle"></i> Reject Application
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.candidates.reject', $candidate) }}" method="POST">
                    @csrf
                    <div class="mb-form">
                        <label class="form-label">Rejection Reason <span style="color:var(--danger)">*</span></label>
                        <textarea name="admin_notes"
                                  class="form-control {{ $errors->has('admin_notes') ? 'is-invalid' : '' }}"
                                  rows="3" placeholder="Reason for rejection (required)..." required></textarea>
                        @error('admin_notes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn-block btn btn-danger"
                            onclick="return confirm('Reject this application?')">
                        <i class="fas fa-times"></i> Reject Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@if($candidate->status === 'approved')
<div class="alert alert-success" style="margin-bottom:1.4rem;">
    <i class="fas fa-check-circle"></i>
    <div>
        This application is <strong>Approved</strong>.
        Unique Code:
        <strong style="letter-spacing:2px; font-size:1.05rem; margin-left:8px;">
            {{ $candidate->unique_code }}
        </strong>
        @if($candidate->admin_notes)
            <br><em style="font-size:0.88rem;">Note: {{ $candidate->admin_notes }}</em>
        @endif
    </div>
</div>
@endif

@if($candidate->status === 'rejected')
<div class="alert alert-danger" style="margin-bottom:1.4rem;">
    <i class="fas fa-times-circle"></i>
    <div>
        This application was <strong>Rejected</strong>.
        @if($candidate->admin_notes)
            <br><em style="font-size:0.88rem;">Reason: {{ $candidate->admin_notes }}</em>
        @endif
    </div>
</div>
@endif

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
                <span class="profile-meta-label">Email</span>
                <span class="profile-meta-value">{{ $candidate->user->email }}</span>
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
                <span class="profile-meta-label">Address</span>
                <span class="profile-meta-value">{{ $candidate->address ?? '—' }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Course</span>
                <span class="profile-meta-value">
                    @if($candidate->course)
                        <a href="{{ route('admin.courses.show', $candidate->course) }}" style="font-weight:700;">
                            {{ $candidate->course->name }}
                        </a>
                        <span style="color:var(--text-muted); font-size:0.85rem; display:block;">
                            {{ $candidate->course->duration_label }}
                        </span>
                    @else
                        <span style="color:var(--text-muted);">No course selected</span>
                    @endif
                </span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Pricing Model</span>
                <span class="profile-meta-value">
                    @if($candidate->course && $candidate->course->pricingModel)
                        {{ $candidate->course->pricingModel->name }}
                        <span style="color:var(--accent); font-weight:700; display:block;">
                            {{ $candidate->course->pricingModel->formatted_price }}
                        </span>
                    @else
                        <span style="color:var(--text-muted);">—</span>
                    @endif
                </span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Batch</span>
                <span class="profile-meta-value">
                    @if($candidate->batch)
                        <a href="{{ route('admin.batches.show', $candidate->batch) }}"
                           style="font-family:monospace; font-weight:700;">
                            {{ $candidate->batch->batch_label }}
                        </a>
                        @if($candidate->is_waitlisted)
                            <span class="waitlist-badge" style="display:inline-flex; margin-top:4px;">
                                <i class="fas fa-hourglass-half"></i> Waiting List
                            </span>
                        @endif
                    @else
                        <span style="color:var(--text-muted);">Not assigned</span>
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Education -->
<div class="mb-3 card">
    <div class="card-header">
        <div class="card-header-title"><i class="fas fa-graduation-cap"></i> Educational History</div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Degree</th>
                    <th>Field</th>
                    <th>Institution</th>
                    <th>Year</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidate->educations as $edu)
                <tr>
                    <td><strong>{{ $edu->degree }}</strong></td>
                    <td>{{ $edu->field_of_study }}</td>
                    <td>{{ $edu->institution }}</td>
                    <td>{{ $edu->start_year }} – {{ $edu->end_year ?? 'Present' }}</td>
                    <td>{{ $edu->grade ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:var(--text-muted);">No records.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Experience -->
<div class="mb-3 card">
    <div class="card-header">
        <div class="card-header-title"><i class="fas fa-briefcase"></i> Work Experience</div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Job Title</th>
                    <th>Duration</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidate->experiences as $exp)
                <tr>
                    <td><strong>{{ $exp->company_name }}</strong></td>
                    <td>{{ $exp->job_title }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} –
                        {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                    </td>
                    <td>{{ $exp->description ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--text-muted);">No experience listed.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Payment Receipt -->
@if($candidate->paymentReceipt)
<div class="mb-3 card">
    <div class="card-header">
        <div class="card-header-title"><i class="fas fa-receipt"></i> Payment Receipt</div>
    </div>
    <div class="card-body">
        <div class="profile-meta-grid">
            <div class="profile-meta-item">
                <span class="profile-meta-label">Transaction ID</span>
                <span class="profile-meta-value">{{ $candidate->paymentReceipt->receipt_number }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Amount</span>
                <span class="profile-meta-value">PKR {{ number_format($candidate->paymentReceipt->amount, 2) }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Payment Method</span>
                <span class="profile-meta-value">{{ $candidate->paymentReceipt->bank_name }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Payment Date</span>
                <span class="profile-meta-value">
                    {{ \Carbon\Carbon::parse($candidate->paymentReceipt->payment_date)->format('d M, Y') }}
                </span>
            </div>
        </div>

        <div style="margin-top:1.2rem;">
            <label class="profile-meta-label">Receipt File</label>
            @php $path = $candidate->paymentReceipt->receipt_image; @endphp
            @if(str_ends_with(strtolower($path), '.pdf'))
                <a href="{{ Storage::url($path) }}" target="_blank" class="btn-outline-primary btn btn-sm">
                    <i class="fas fa-file-pdf"></i> View PDF Receipt
                </a>
            @else
                <div class="receipt-preview" style="max-width:350px; margin-top:0.5rem;">
                    <img src="{{ Storage::url($path) }}" alt="Payment Receipt">
                </div>
                <a href="{{ Storage::url($path) }}" target="_blank" class="mt-1 btn-outline-primary btn btn-sm">
                    <i class="fas fa-external-link-alt"></i> Open Full Image
                </a>
            @endif
        </div>
    </div>
</div>
@endif
@endsection