@extends('layouts.app')
@section('title', 'Pricing Models')
@section('page-title', 'Pricing Models')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Pricing Models</div>
        <div class="page-header-sub">Create and manage pricing plans assigned to courses</div>
    </div>
    <a href="{{ route('admin.pricing-models.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add Pricing Model
    </a>
</div>

@if($pricingModels->isEmpty())
    <div class="card">
        <div class="card-body" style="text-align:center; padding:4rem 2rem;">
            <i class="fas fa-tags" style="font-size:3.5rem; color:var(--border); margin-bottom:1rem; display:block;"></i>
            <h3 style="color:var(--text-muted); margin-bottom:0.5rem;">No Pricing Models Yet</h3>
            <p style="color:var(--text-muted); margin-bottom:1.5rem;">Create pricing models and assign them to courses.</p>
            <a href="{{ route('admin.pricing-models.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Create First Model
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
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Assigned Courses</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pricingModels as $pm)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $pm->name }}</strong></td>
                            <td><span class="course-meta-chip price"><i class="fas fa-tag"></i> {{ $pm->formatted_price }}</span></td>
                            <td style="color:var(--text-muted); font-size:0.88rem;">
                                {{ $pm->description ? Str::limit($pm->description, 60) : '—' }}
                            </td>
                            <td>
                                <span class="course-meta-chip enrolled">
                                    <i class="fas fa-book-open"></i> {{ $pm->courses_count }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $pm->is_active ? 'badge-approved' : 'badge-rejected' }}">
                                    {{ $pm->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.pricing-models.toggle', $pm) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-outline-secondary btn btn-sm"
                                                onclick="return confirm('{{ $pm->is_active ? 'Deactivate' : 'Activate' }} this model?')">
                                            <i class="fas fa-{{ $pm->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.pricing-models.edit', $pm) }}" class="btn-outline-primary btn btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.pricing-models.destroy', $pm) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete \'{{ addslashes($pm->name) }}\'?')">
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
            @if($pricingModels->hasPages())
                <div style="padding:1rem 1.4rem; border-top:1px solid var(--border);">
                    {{ $pricingModels->links() }}
                </div>
            @endif
        </div>
    </div>
@endif
@endsection