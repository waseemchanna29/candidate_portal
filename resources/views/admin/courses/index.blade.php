@extends('layouts.app')
@section('title', 'Courses')
@section('page-title', 'Courses')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-header-title">Course Management</div>
            <div class="page-header-sub">Add and manage courses available for candidate registration</div>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add New Course
        </a>
    </div>

    @if ($courses->isEmpty())
        <div class="card">
            <div class="card-body" style="text-align:center; padding:4rem 2rem;">
                <i class="fas fa-book-open"
                    style="font-size:3.5rem; color:var(--border); margin-bottom:1rem; display:block;"></i>
                <h3 style="color:var(--text-muted); margin-bottom:0.5rem;">No Courses Yet</h3>
                <p style="color:var(--text-muted); margin-bottom:1.5rem;">Create your first course so candidates can enroll
                    during registration.</p>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Create First Course
                </a>
            </div>
        </div>
    @else
        <div class="course-cards-grid">
            @foreach ($courses as $course)
                <div class="course-card">
                    <div class="course-card-accent {{ $course->is_active ? '' : 'inactive' }}"></div>
                    <div class="course-card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="course-card-name">{{ $course->name }}</div>
                            <span class="badge {{ $course->is_active ? 'badge-approved' : 'badge-rejected' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        @if ($course->description)
                            <div class="course-card-description">{{ Str::limit($course->description, 90) }}</div>
                        @endif

                        <div class="course-card-meta">
                            <span class="course-meta-chip duration">
                                <i class="fas fa-clock"></i> {{ $course->duration_label }}
                            </span>
                            @if ($course->pricingModel)
                                <span class="course-meta-chip price">
                                    <i class="fas fa-tag"></i> {{ $course->pricingModel->formatted_price }}
                                </span>
                            @endif
                            <span class="course-meta-chip enrolled">
                                <i class="fas fa-user-graduate"></i> {{ $course->candidates_count }}
                                {{ $course->candidates_count === 1 ? 'Enrolled' : 'Enrolled' }}
                            </span>
                        </div>
                    </div>

                    <div class="course-card-footer">
                        <!-- Toggle Active -->
                        <form action="{{ route('admin.courses.toggle', $course) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm {{ $course->is_active ? 'btn-outline-secondary' : 'btn-outline-primary' }}"
                                onclick="return confirm('{{ $course->is_active ? 'Deactivate' : 'Activate' }} this course?')">
                                <i class="fas fa-{{ $course->is_active ? 'pause' : 'play' }}"></i>
                                {{ $course->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.courses.show', $course) }}" class="btn-outline-primary btn btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn-outline-primary btn btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete course \'{{ addslashes($course->name) }}\'? This cannot be undone.')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($courses->hasPages())
            <div class="pagination-wrapper">{{ $courses->links() }}</div>
        @endif
    @endif
@endsection
