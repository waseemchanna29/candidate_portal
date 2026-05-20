@extends('layouts.app')
@section('title', 'Pricing Model Details')
@section('page-title', 'Pricing Model Details')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">{{ $pricingModel->name }}</div>
        <div class="page-header-sub">Pricing model details and assigned courses</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.pricing-models.edit', $pricingModel) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.pricing-models.index') }}" class="btn-outline-secondary btn btn-sm">
            <i class="fa-arrow-left fas"></i> Back
        </a>
    </div>
</div>

<div class="mb-3 card">
    <div class="card-body">
        <div class="profile-meta-grid">
            <div class="profile-meta-item">
                <span class="profile-meta-label">Model Name</span>
                <span class="profile-meta-value">{{ $pricingModel->name }}</span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Fixed Price</span>
                <span class="profile-meta-value" style="color:var(--accent); font-size:1.1rem;">
                    {{ $pricingModel->formatted_price }}
                </span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Status</span>
                <span class="profile-meta-value">
                    <span class="badge {{ $pricingModel->is_active ? 'badge-approved' : 'badge-rejected' }}">
                        {{ $pricingModel->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </span>
            </div>
            <div class="profile-meta-item">
                <span class="profile-meta-label">Assigned Courses</span>
                <span class="profile-meta-value">{{ $pricingModel->courses_count }}</span>
            </div>
        </div>
        @if($pricingModel->description)
            <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid var(--border);">
                <span class="profile-meta-label">Description</span>
                <p style="margin-top:4px; color:var(--text-dark);">{{ $pricingModel->description }}</p>
            </div>
        @endif
    </div>
</div>

<div class="mb-3 card">
    <div class="card-header">
        <div class="card-header-title"><i class="fas fa-book-open"></i> Assigned Courses</div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pricingModel->courses as $course)
                <tr>
                    <td><strong>{{ $course->name }}</strong></td>
                    <td>{{ $course->duration_label }}</td>
                    <td>
                        <span class="badge {{ $course->is_active ? 'badge-approved' : 'badge-rejected' }}">
                            {{ $course->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.courses.show', $course) }}" class="btn-outline-primary btn btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--text-muted); padding:2rem;">
                        No courses assigned to this pricing model yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="border-color:rgba(220,53,69,0.25);">
    <div class="card-header" style="background:rgba(220,53,69,0.04);">
        <div class="card-header-title" style="color:var(--danger);">
            <i class="fas fa-exclamation-triangle"></i> Danger Zone
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between" style="flex-wrap:wrap; gap:1rem;">
            <div>
                <strong style="color:var(--danger);">Delete this pricing model</strong>
                <div style="color:var(--text-muted); font-size:0.88rem; margin-top:2px;">
                    Cannot delete if assigned to courses. Unassign from all courses first.
                </div>
            </div>
            <form action="{{ route('admin.pricing-models.destroy', $pricingModel) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Permanently delete \'{{ addslashes($pricingModel->name) }}\'?')">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection