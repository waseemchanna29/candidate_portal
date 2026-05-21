@extends('layouts.app')
@section('title', 'Edit Batch')
@section('page-title', 'Edit Batch')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Edit Batch</div>
        <div class="page-header-sub">{{ $batch->batch_label }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.batches.show', $batch) }}" class="btn-outline-secondary btn btn-sm">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('admin.batches.index') }}" class="btn-outline-secondary btn btn-sm">
            <i class="fa-arrow-left fas"></i> Back
        </a>
    </div>
</div>

<div style="max-width:720px;">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="fas fa-edit"></i> Batch Details</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.batches.update', $batch) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-form">
                    <label class="form-label">Course</label>
                    <input type="text" class="form-control" value="{{ $batch->course->name }}" disabled>
                    <small style="color:var(--text-muted); font-size:0.8rem;">Course cannot be changed after creation.</small>
                </div>

                <div class="row">
                    <div class="mb-form col-4">
                        <label class="form-label">Course Code <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="course_code"
                               class="form-control {{ $errors->has('course_code') ? 'is-invalid' : '' }}"
                               value="{{ old('course_code', $batch->course_code) }}"
                               style="text-transform:uppercase;">
                        @error('course_code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-form col-4">
                        <label class="form-label">Year <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="year"
                               class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}"
                               value="{{ old('year', $batch->year) }}"
                               min="2000" max="{{ date('Y') + 5 }}">
                        @error('year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-form col-4">
                        <label class="form-label">Batch No. <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="batch_no"
                               class="form-control {{ $errors->has('batch_no') ? 'is-invalid' : '' }}"
                               value="{{ old('batch_no', $batch->batch_no) }}" min="1">
                        @error('batch_no')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-form col-6">
                        <label class="form-label">Total Seats <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="total_seats"
                               class="form-control {{ $errors->has('total_seats') ? 'is-invalid' : '' }}"
                               value="{{ old('total_seats', $batch->total_seats) }}" min="1">
                        @error('total_seats')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-form col-6">
                        <label class="form-label">Status <span style="color:var(--danger)">*</span></label>
                        <select name="status" class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <option value="open"        {{ old('status', $batch->status) === 'open'        ? 'selected' : '' }}>Open</option>
                            <option value="full"        {{ old('status', $batch->status) === 'full'        ? 'selected' : '' }}>Full</option>
                            <option value="in_progress" {{ old('status', $batch->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed"      {{ old('status', $batch->status) === 'closed'      ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-form col-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date"
                               class="form-control"
                               value="{{ old('start_date', $batch->start_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-form col-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date"
                               class="form-control"
                               value="{{ old('end_date', $batch->end_date?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="mb-form">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="3" class="form-control"
                              placeholder="Any internal notes...">{{ old('notes', $batch->notes) }}</textarea>
                </div>

                <div style="display:flex; gap:0.8rem; margin-top:1.5rem; padding-top:1.2rem; border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.batches.index') }}" class="btn-outline-secondary btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Seats Card -->
    <div class="card" style="margin-top:1.2rem; border-top:3px solid var(--success);">
        <div class="card-header">
            <div class="card-header-title" style="color:var(--success);">
                <i class="fas fa-plus-circle"></i> Add More Seats
            </div>
        </div>
        <div class="card-body">
            <div style="margin-bottom:0.8rem; color:var(--text-muted); font-size:0.9rem;">
                Current: <strong>{{ $batch->seats_filled }}</strong> filled /
                <strong>{{ $batch->total_seats }}</strong> total —
                <strong style="color:var(--success);">{{ $batch->seats_available }} available</strong>
            </div>
            <form action="{{ route('admin.batches.add-seats', $batch) }}" method="POST" style="display:flex; gap:0.8rem; align-items:flex-end;">
                @csrf
                <div style="flex:1;">
                    <label class="form-label">Seats to Add</label>
                    <input type="number" name="seats" class="form-control" placeholder="e.g. 10" min="1" max="500">
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Seats
                </button>
            </form>
        </div>
    </div>
</div>
@endsection