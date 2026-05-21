@extends('layouts.app')
@section('title', 'Create Batch')
@section('page-title', 'Create Batch')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Create New Batch</div>
        <div class="page-header-sub">Set up a new batch for a course with seat allocation</div>
    </div>
    <a href="{{ route('admin.batches.index') }}" class="btn-outline-secondary btn btn-sm">
        <i class="fa-arrow-left fas"></i> Back
    </a>
</div>

<div style="max-width:720px;">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="fa-layer-group fas"></i> Batch Details</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.batches.store') }}" method="POST" novalidate>
                @csrf

                <div class="mb-form">
                    <label class="form-label">Course <span style="color:var(--danger)">*</span></label>
                    <select name="course_id" class="form-select {{ $errors->has('course_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="row">
                    <div class="mb-form col-4">
                        <label class="form-label">Course Code <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="course_code"
                               class="form-control {{ $errors->has('course_code') ? 'is-invalid' : '' }}"
                               value="{{ old('course_code') }}"
                               placeholder="e.g. WD, CS, DBA" style="text-transform:uppercase;">
                        @error('course_code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-form col-4">
                        <label class="form-label">Year <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="year"
                               class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}"
                               value="{{ old('year', date('Y')) }}"
                               min="2000" max="{{ date('Y') + 5 }}">
                        @error('year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-form col-4">
                        <label class="form-label">Batch No. <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="batch_no"
                               class="form-control {{ $errors->has('batch_no') ? 'is-invalid' : '' }}"
                               value="{{ old('batch_no', 1) }}" min="1">
                        @error('batch_no')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-form col-6">
                        <label class="form-label">Total Seats <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="total_seats"
                               class="form-control {{ $errors->has('total_seats') ? 'is-invalid' : '' }}"
                               value="{{ old('total_seats') }}" min="1" placeholder="e.g. 30">
                        @error('total_seats')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-form col-6">
                        <label class="form-label">Status <span style="color:var(--danger)">*</span></label>
                        <select name="status" class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <option value="open"        {{ old('status','open') === 'open'        ? 'selected' : '' }}>Open</option>
                            <option value="full"        {{ old('status') === 'full'        ? 'selected' : '' }}>Full</option>
                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed"      {{ old('status') === 'closed'      ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-form col-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date"
                               class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                               value="{{ old('start_date') }}">
                        @error('start_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-form col-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date"
                               class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                               value="{{ old('end_date') }}">
                        @error('end_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="mb-form">
                    <label class="form-label">Notes <span style="color:var(--text-muted); font-weight:400;">(optional)</span></label>
                    <textarea name="notes" rows="3"
                              class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                              placeholder="Any internal notes about this batch...">{{ old('notes') }}</textarea>
                    @error('notes')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div style="display:flex; gap:0.8rem; margin-top:1.5rem; padding-top:1.2rem; border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Batch
                    </button>
                    <a href="{{ route('admin.batches.index') }}" class="btn-outline-secondary btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection