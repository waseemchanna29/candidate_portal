@extends('layouts.app')
@section('title', 'Edit Pricing Model')
@section('page-title', 'Edit Pricing Model')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Edit Pricing Model</div>
        <div class="page-header-sub">Update "{{ $pricingModel->name }}"</div>
    </div>
    <a href="{{ route('admin.pricing-models.index') }}" class="btn-outline-secondary btn btn-sm">
        <i class="fa-arrow-left fas"></i> Back
    </a>
</div>

<div style="max-width:680px;">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="fas fa-edit"></i> Pricing Model Details</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pricing-models.update', $pricingModel) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-form">
                    <label class="form-label">Model Name <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $pricingModel->name) }}"
                           placeholder="e.g. Standard Fee">
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-form">
                    <label class="form-label">Fixed Price (PKR) <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="price"
                           class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                           value="{{ old('price', $pricingModel->price) }}"
                           min="0" step="0.01">
                    @error('price')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-form">
                    <label class="form-label">Description <span style="color:var(--text-muted); font-weight:400;">(optional)</span></label>
                    <textarea name="description" rows="3"
                              class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              placeholder="Any notes about this pricing plan...">{{ old('description', $pricingModel->description) }}</textarea>
                    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-form" style="display:flex; align-items:center; gap:12px; padding:1rem; background:var(--light-bg); border-radius:var(--radius-sm);">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $pricingModel->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                           style="width:18px; height:18px; cursor:pointer; accent-color:var(--primary);">
                    <label for="is_active" style="cursor:pointer; margin:0;">
                        <strong>Active</strong>
                        <span style="color:var(--text-muted); font-size:0.85rem; display:block;">
                            Active models can be assigned to courses
                        </span>
                    </label>
                </div>

                <div style="display:flex; gap:0.8rem; margin-top:1.5rem; padding-top:1.2rem; border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.pricing-models.index') }}" class="btn-outline-secondary btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection