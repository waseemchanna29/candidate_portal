@extends('layouts.app')
@section('title', 'Course Details')
@section('page-title', 'Course Details')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-header-title">{{ $course->name }}</div>
            <div class="page-header-sub">Course details and enrolled candidates</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.courses.index') }}" class="btn-outline-secondary btn btn-sm">
                <i class="fa-arrow-left fas"></i> Back
            </a>
        </div>
    </div>

    <!-- Hero Block -->
    <div class="course-detail-hero">
        <div>
            <div class="d-flex align-items-center gap-2" style="margin-bottom:0.6rem;">
                <div class="course-detail-hero-title">{{ $course->name }}</div>
                <span class="badge {{ $course->is_active ? 'badge-approved' : 'badge-rejected' }}"
                    style="font-size:0.85rem;">
                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            @if ($course->description)
                <div class="course-detail-hero-desc">{{ $course->description }}</div>
            @endif
            @if ($course->pricingModel)
                <div class="course-hero-stat">
                    <span class="course-hero-stat-value">{{ $course->pricingModel->formatted_price }}</span>
                    <span class="course-hero-stat-label">{{ $course->pricingModel->name }}</span>
                </div>
            @endif
            <div style="margin-top:1rem;">
                <form action="{{ route('admin.courses.toggle', $course) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm"
                        style="background:rgba(255,255,255,0.15); color:var(--white); border-color:rgba(255,255,255,0.3);"
                        onclick="return confirm('{{ $course->is_active ? 'Deactivate' : 'Activate' }} this course?')">
                        <i class="fas fa-{{ $course->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                        {{ $course->is_active ? 'Deactivate Course' : 'Activate Course' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="course-detail-hero-stats">
            <div class="course-hero-stat">
                <span class="course-hero-stat-value">{{ $course->duration_label }}</span>
                <span class="course-hero-stat-label">Duration</span>
            </div>
            @if ($course->pricingModel)
                <div class="course-hero-stat">
                    <span class="course-hero-stat-value">{{ $course->pricingModel->formatted_price }}</span>
                    <span class="course-hero-stat-label">{{ $course->pricingModel->name }}</span>
                </div>
            @endif
            <div class="course-hero-stat">
                <span class="course-hero-stat-value">{{ $course->candidates_count }}</span>
                <span class="course-hero-stat-label">Enrolled</span>
            </div>
        </div>
    </div>

    <!-- Enrolled Candidates -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="fas fa-user-graduate"></i>
                Enrolled Candidates
                <span style="font-weight:400; color:var(--text-muted); font-size:0.85rem;">(last 10)</span>
            </div>
            @if ($course->candidates_count > 0)
                <a href="{{ route('admin.candidates.index', ['course_id' => $course->id]) }}"
                    class="btn-outline-primary btn btn-sm">
                    View All
                </a>
            @endif
        </div>
        <div class="card-body" style="padding:0;">
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
                    @forelse($course->candidates as $candidate)
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
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:var(--text-muted); padding:2.5rem;">
                                No candidates enrolled in this course yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card" style="margin-top:1.4rem; border-color:rgba(220,53,69,0.25);">
        <div class="card-header" style="background:rgba(220,53,69,0.04);">
            <div class="card-header-title" style="color:var(--danger);">
                <i class="fas fa-exclamation-triangle"></i> Danger Zone
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between" style="flex-wrap:wrap; gap:1rem;">
                <div>
                    <strong style="color:var(--danger);">Delete this course</strong>
                    <div style="color:var(--text-muted); font-size:0.88rem; margin-top:2px;">
                        This action is permanent. Courses with enrolled candidates cannot be deleted.
                    </div>
                </div>
                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Permanently delete \'{{ addslashes($course->name) }}\'?')">
                        <i class="fas fa-trash-alt"></i> Delete Course
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
