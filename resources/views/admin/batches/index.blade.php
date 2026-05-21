@extends('layouts.app')
@section('title', 'Batches')
@section('page-title', 'Batches')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Batch Management</div>
        <div class="page-header-sub">Create and manage course batches and seat allocation</div>
    </div>
    <a href="{{ route('admin.batches.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Create Batch
    </a>
</div>

@if($batches->isEmpty())
    <div class="card">
        <div class="card-body" style="text-align:center; padding:4rem 2rem;">
            <i class="fa-layer-group fas" style="font-size:3.5rem; color:var(--border); margin-bottom:1rem; display:block;"></i>
            <h3 style="color:var(--text-muted); margin-bottom:0.5rem;">No Batches Yet</h3>
            <p style="color:var(--text-muted); margin-bottom:1.5rem;">Create batches to manage candidate seat allocation per course.</p>
            <a href="{{ route('admin.batches.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Create First Batch
            </a>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Batch Label</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Seats</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                        @php
                            $filled  = $batch->seats_filled;
                            $pct     = $batch->total_seats > 0 ? ($filled / $batch->total_seats) * 100 : 0;
                            $barClass = $pct >= 90 ? 'high' : ($pct >= 60 ? 'medium' : 'low');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong style="font-family:monospace; font-size:0.95rem; color:var(--primary);">
                                    {{ $batch->batch_label }}
                                </strong>
                            </td>
                            <td>{{ $batch->course->name }}</td>
                            <td>{{ $batch->year }}</td>
                            <td style="min-width:140px;">
                                <div style="font-size:0.85rem; font-weight:600;">
                                    {{ $filled }} / {{ $batch->total_seats }}
                                    <span style="color:var(--text-muted); font-weight:400;">
                                        ({{ $batch->seats_available }} left)
                                    </span>
                                </div>
                                <div class="batch-seats-bar-wrap">
                                    <div class="batch-seats-bar {{ $barClass }}" style="width:{{ min($pct,100) }}%;"></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $batch->status_badge_class }}">
                                    {{ ucfirst(str_replace('_', ' ', $batch->status)) }}
                                </span>
                            </td>
                            <td>{{ $batch->start_date ? $batch->start_date->format('d M, Y') : '—' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.batches.show', $batch) }}" class="btn-outline-primary btn btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.batches.edit', $batch) }}" class="btn-outline-primary btn btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.batches.destroy', $batch) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete batch {{ addslashes($batch->batch_label) }}?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($batches->hasPages())
                <div style="padding:1rem 1.4rem; border-top:1px solid var(--border);">
                    {{ $batches->links() }}
                </div>
            @endif
        </div>
    </div>
@endif
@endsection