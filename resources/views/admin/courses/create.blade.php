@extends('layouts.app')
@section('title', 'Add Course')
@section('page-title', 'Add Course')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-header-title">Add New Course</div>
            <div class="page-header-sub">Create a course that candidates can enroll in during registration</div>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="btn-outline-secondary btn btn-sm">
            <i class="fa-arrow-left fas"></i> Back to Courses
        </a>
    </div>

    <div style="max-width: 680px;">
        <div class="card">
            <div class="card-header">
                <div class="card-header-title"><i class="fas fa-book-open"></i> Course Details</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.courses.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-form">
                        <label class="form-label">Course Name <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="name"
                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}"
                            placeholder="e.g. Web Development Fundamentals">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="mb-form col-6">
                            <label class="form-label">Duration (Months) <span style="color:var(--danger)">*</span></label>
                            <input type="number" name="duration_months"
                                class="form-control {{ $errors->has('duration_months') ? 'is-invalid' : '' }}"
                                value="{{ old('duration_months') }}" placeholder="e.g. 6" min="1" max="120">
                            @error('duration_months')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-form">
                        <label class="form-label">Description <span
                                style="color:var(--text-muted); font-weight:400;">(optional)</span></label>
                        <textarea name="description" rows="4" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                            placeholder="Brief overview of what this course covers...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-form">
                        <label class="form-label">Pricing Model <span
                                style="color:var(--text-muted); font-weight:400;">(optional)</span></label>
                        <select name="pricing_model_id"
                            class="form-select {{ $errors->has('pricing_model_id') ? 'is-invalid' : '' }}">
                            <option value="">-- No pricing model --</option>
                            @foreach ($pricingModels as $pm)
                                <option value="{{ $pm->id }}"
                                    {{ old('pricing_model_id') == $pm->id ? 'selected' : '' }}>
                                    {{ $pm->name }} — {{ $pm->formatted_price }}
                                </option>
                            @endforeach
                        </select>
                        @error('pricing_model_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-form"
                        style="display:flex; align-items:center; gap:12px; padding:1rem; background:var(--light-bg); border-radius:var(--radius-sm);">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                            style="width:18px; height:18px; cursor:pointer; accent-color:var(--primary);">
                        <label for="is_active" style="cursor:pointer; margin:0;">
                            <strong>Active</strong>
                            <span style="color:var(--text-muted); font-size:0.85rem; display:block;">
                                Active courses are shown to candidates during registration
                            </span>
                        </label>
                    </div>

                    <div
                        style="display:flex; gap:0.8rem; margin-top:1.5rem; padding-top:1.2rem; border-top:1px solid var(--border);">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Course
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="btn-outline-secondary btn">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
