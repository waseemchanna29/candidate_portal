@extends('layouts.auth')

@section('title', 'Candidate Registration')

@section('content')
    <div class="auth-form-header">
        <h2>Candidate Registration</h2>
        <p>Fill in all details below to submit your application</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Please correct the following:</strong>
                <ul style="margin:0.4rem 0 0 1rem; padding:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- ====== COURSE SELECTION ====== -->
        <div class="register-section-title" style="margin-top:1rem;">
            <i class="fas fa-book-open"></i> Select a Course <span style="color:var(--danger)">*</span>
        </div>

        @if ($courses->isEmpty())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                No courses are currently available. Please contact the administration office.
            </div>
        @else
            <div class="course-select-grid">
                @foreach ($courses as $course)
                    <div class="course-select-option">
                        <input type="radio" name="course_id" id="course_{{ $course->id }}" value="{{ $course->id }}"
                            {{ old('course_id') == $course->id ? 'checked' : '' }}>
                        <label class="course-select-label" for="course_{{ $course->id }}">
                            <div class="course-select-label-name">
                                {{ $course->name }}
                                <span class="course-select-check"><i class="fas fa-check"></i></span>
                            </div>
                            <div class="course-select-chips">
                                <span class="course-meta-chip duration">
                                    <i class="fas fa-clock"></i> {{ $course->duration_label }}
                                </span>
                                @if ($course->pricingModel)
                                    <span class="course-meta-chip price">
                                        <i class="fas fa-tag"></i> {{ $course->pricingModel->formatted_price }}
                                    </span>
                                @endif
                            </div>
                            @if ($course->description)
                                <div
                                    style="margin-top:0.5rem; font-size:0.82rem; color:var(--text-muted); line-height:1.45;">
                                    {{ Str::limit($course->description, 70) }}
                                </div>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
            @error('course_id')
                <span class="invalid-feedback" style="display:block; margin-top:-0.5rem; margin-bottom:0.8rem;">
                    {{ $message }}
                </span>
            @enderror
        @endif

        {{-- Pricing Model Info (shown after course selection) --}}
        <div id="pricing-info" style="display:none; margin-top:0.8rem; margin-bottom:1rem;">
            <div class="alert alert-info" id="pricing-details">
                <i class="fas fa-tag"></i>
                <div id="pricing-text"></div>
            </div>
        </div>

        <script>
            // Build pricing map from PHP
            const coursePricing = {
                @foreach ($courses as $course)
                    @if ($course->pricingModel)
                        "{{ $course->id }}": {
                            name: "{{ $course->pricingModel->name }}",
                            price: "{{ $course->pricingModel->formatted_price }}",
                            description: "{{ addslashes($course->pricingModel->description ?? '') }}"
                        },
                    @endif
                @endforeach
            };

            document.querySelectorAll('input[name="course_id"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const pricing = coursePricing[this.value];
                    const box = document.getElementById('pricing-info');
                    const text = document.getElementById('pricing-text');
                    if (pricing) {
                        text.innerHTML = '<strong>' + pricing.name + '</strong>: ' + pricing.price +
                            (pricing.description ? '<br><span style="font-size:0.85rem;">' + pricing
                                .description + '</span>' : '');
                        box.style.display = 'block';
                    } else {
                        box.style.display = 'none';
                    }
                });
            });

            // Show on page load if old value exists
            @if (old('course_id'))
                document.addEventListener('DOMContentLoaded', function() {
                    const radio = document.querySelector('input[name="course_id"][value="{{ old('course_id') }}"]');
                    if (radio) radio.dispatchEvent(new Event('change'));
                });
            @endif
        </script>
        <!-- ====== ACCOUNT INFORMATION ====== -->
        <div class="register-section-title">
            <i class="fas fa-user-circle"></i> Account Information
        </div>

        <div class="row">
            <div class="mb-form col-6">
                <label class="form-label">Full Name <span style="color:var(--danger)">*</span></label>
                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name') }}" placeholder="As per CNIC">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Email Address <span style="color:var(--danger)">*</span></label>
                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}" placeholder="you@email.com">
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Min. 8 characters">
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Confirm Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password">
            </div>
        </div>

        <!-- ====== PERSONAL INFORMATION ====== -->
        <div class="register-section-title" style="margin-top:1rem;">
            <i class="fas fa-id-card"></i> Personal Information
        </div>

        <div class="row">
            <div class="mb-form col-6">
                <label class="form-label">Full Name (Official) <span style="color:var(--danger)">*</span></label>
                <input type="text" name="full_name"
                    class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                    value="{{ old('full_name') }}" placeholder="Full legal name">
                @error('full_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Phone Number <span style="color:var(--danger)">*</span></label>
                <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                    value="{{ old('phone') }}" placeholder="03XX-XXXXXXX">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">CNIC <span style="color:var(--danger)">*</span></label>
                <input type="text" name="cnic" class="form-control {{ $errors->has('cnic') ? 'is-invalid' : '' }}"
                    value="{{ old('cnic') }}" placeholder="XXXXX-XXXXXXX-X">
                @error('cnic')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                    placeholder="e.g. Karachi">
            </div>
            <div class="mb-form col-12">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                    placeholder="Full address">
            </div>
        </div>

        <!-- ====== EDUCATION ====== -->
        <div class="register-section-title" style="margin-top:1rem;">
            <i class="fas fa-graduation-cap"></i> Educational History
        </div>

        <div id="education-container">
            <div class="repeater-item">
                <div class="repeater-item-header">
                    <span class="repeater-item-label"><i class="fas fa-university"></i> Education #1</span>
                </div>
                <div class="row">
                    <div class="mb-form col-6">
                        <label class="form-label">Degree / Qualification <span
                                style="color:var(--danger)">*</span></label>
                        <select name="education[0][degree]" class="form-select">
                            <option value="">-- Select Degree --</option>
                            <option value="Matric">Matric (10th)</option>
                            <option value="Intermediate">Intermediate (12th)</option>
                            <option value="Bachelor">Bachelor's Degree</option>
                            <option value="Master">Master's Degree</option>
                            <option value="PhD">PhD</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-form col-6">
                        <label class="form-label">Field of Study <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="education[0][field_of_study]" class="form-control"
                            placeholder="e.g. Computer Science">
                    </div>
                    <div class="mb-form col-12">
                        <label class="form-label">Institution / University <span
                                style="color:var(--danger)">*</span></label>
                        <input type="text" name="education[0][institution]" class="form-control"
                            placeholder="Name of school/college/university">
                    </div>
                    <div class="mb-form col-4">
                        <label class="form-label">Start Year <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="education[0][start_year]" class="form-control"
                            placeholder="e.g. 2018" min="1950" max="{{ date('Y') }}">
                    </div>
                    <div class="mb-form col-4">
                        <label class="form-label">End Year</label>
                        <input type="number" name="education[0][end_year]" class="form-control" placeholder="e.g. 2022"
                            min="1950" max="{{ date('Y') + 5 }}">
                    </div>
                    <div class="mb-form col-4">
                        <label class="form-label">Grade / GPA</label>
                        <input type="text" name="education[0][grade]" class="form-control"
                            placeholder="e.g. A, 3.5/4.0">
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn-add-repeater" id="add-education">
            <i class="fas fa-plus-circle"></i> Add Another Education
        </button>

        <!-- ====== EXPERIENCE ====== -->
        <div class="register-section-title" style="margin-top:1.5rem;">
            <i class="fas fa-briefcase"></i> Work Experience <span
                style="font-size:0.85rem; font-weight:400; color:var(--text-muted); font-family:var(--font-main);">(optional)</span>
        </div>

        <div id="experience-container"></div>

        <button type="button" class="btn-add-repeater" id="add-experience">
            <i class="fas fa-plus-circle"></i> Add Work Experience
        </button>

        <!-- ====== PAYMENT RECEIPT ====== -->
        <div class="register-section-title" style="margin-top:1.5rem;">
            <i class="fas fa-receipt"></i> Payment Receipt
        </div>

        <!-- Payment Instructions -->
        <div class="alert alert-info" style="margin-bottom:1.4rem;">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Send payment to one of the following accounts:</strong>
                <div style="margin-top:0.8rem; display:flex; gap:1.2rem; flex-wrap:wrap;">
                    <div
                        style="background:rgba(255,255,255,0.6); border-radius:var(--radius-sm); padding:0.7rem 1rem; min-width:200px;">
                        <div style="font-weight:700; color:var(--primary); margin-bottom:3px;">
                            <i class="fas fa-mobile-alt"></i> EasyPaisa
                        </div>
                        <div style="font-size:0.9rem;"><strong>Name:</strong> Waseem Iqbal</div>
                        <div style="font-size:0.9rem;"><strong>Account:</strong> 03322693529</div>
                    </div>
                    <div
                        style="background:rgba(255,255,255,0.6); border-radius:var(--radius-sm); padding:0.7rem 1rem; min-width:200px;">
                        <div style="font-weight:700; color:var(--primary); margin-bottom:3px;">
                            <i class="fas fa-mobile-alt"></i> JazzCash
                        </div>
                        <div style="font-size:0.9rem;"><strong>Name:</strong> Waseem Iqbal</div>
                        <div style="font-size:0.9rem;"><strong>Account:</strong> 03322693529</div>
                    </div>
                </div>
                <div style="margin-top:0.8rem; font-size:0.85rem; color:#055160;">
                    <i class="fas fa-exclamation-circle"></i>
                    After sending payment, fill in the transaction details below and upload your receipt screenshot.
                </div>
            </div>
        </div>

        <div class="row">
            <div class="mb-form col-6">
                <label class="form-label">Payment Method <span style="color:var(--danger)">*</span></label>
                <select name="bank_name" class="form-select {{ $errors->has('bank_name') ? 'is-invalid' : '' }}">
                    <option value="">-- Select Method --</option>
                    <option value="EasyPaisa" {{ old('bank_name') === 'EasyPaisa' ? 'selected' : '' }}>EasyPaisa</option>
                    <option value="JazzCash" {{ old('bank_name') === 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                </select>
                @error('bank_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Transaction ID <span style="color:var(--danger)">*</span></label>
                <input type="text" name="receipt_number"
                    class="form-control {{ $errors->has('receipt_number') ? 'is-invalid' : '' }}"
                    value="{{ old('receipt_number') }}" placeholder="e.g. TXN1234567890">
                @error('receipt_number')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Amount Paid (PKR) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="amount"
                    class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" value="{{ old('amount') }}"
                    placeholder="e.g. 5000" min="1">
                @error('amount')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Payment Date <span style="color:var(--danger)">*</span></label>
                <input type="date" name="payment_date"
                    class="form-control {{ $errors->has('payment_date') ? 'is-invalid' : '' }}"
                    value="{{ old('payment_date') }}" max="{{ date('Y-m-d') }}">
                @error('payment_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-form col-12">
                <label class="form-label">Receipt Screenshot <span style="color:var(--danger)">*</span></label>
                <input type="file" name="receipt_image"
                    class="form-control {{ $errors->has('receipt_image') ? 'is-invalid' : '' }}"
                    accept=".jpg,.jpeg,.png,.pdf">
                <small style="color:var(--text-muted); font-size:0.8rem;">
                    Upload screenshot of your EasyPaisa/JazzCash transaction. Accepted: JPG, PNG, PDF. Max: 2MB
                </small>
                @error('receipt_image')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>

    <script>
        // Education repeater
        let eduCount = 1;
        document.getElementById('add-education').addEventListener('click', function() {
            const idx = eduCount++;
            const html = `
    <div class="repeater-item" id="edu-${idx}">
        <div class="repeater-item-header">
            <span class="repeater-item-label"><i class="fas fa-university"></i> Education #${idx + 1}</span>
            <button type="button" class="btn-remove-repeater" onclick="document.getElementById('edu-${idx}').remove()">
                <i class="fas fa-trash-alt"></i> Remove
            </button>
        </div>
        <div class="row">
            <div class="mb-form col-6">
                <label class="form-label">Degree / Qualification *</label>
                <select name="education[${idx}][degree]" class="form-select">
                    <option value="">-- Select Degree --</option>
                    <option value="Matric">Matric (10th)</option>
                    <option value="Intermediate">Intermediate (12th)</option>
                    <option value="Bachelor">Bachelor's Degree</option>
                    <option value="Master">Master's Degree</option>
                    <option value="PhD">PhD</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Field of Study *</label>
                <input type="text" name="education[${idx}][field_of_study]" class="form-control" placeholder="e.g. Computer Science">
            </div>
            <div class="mb-form col-12">
                <label class="form-label">Institution *</label>
                <input type="text" name="education[${idx}][institution]" class="form-control">
            </div>
            <div class="mb-form col-4">
                <label class="form-label">Start Year *</label>
                <input type="number" name="education[${idx}][start_year]" class="form-control" placeholder="e.g. 2018">
            </div>
            <div class="mb-form col-4">
                <label class="form-label">End Year</label>
                <input type="number" name="education[${idx}][end_year]" class="form-control">
            </div>
            <div class="mb-form col-4">
                <label class="form-label">Grade / GPA</label>
                <input type="text" name="education[${idx}][grade]" class="form-control">
            </div>
        </div>
    </div>`;
            document.getElementById('education-container').insertAdjacentHTML('beforeend', html);
        });

        // Experience repeater
        let expCount = 0;
        document.getElementById('add-experience').addEventListener('click', function() {
            const idx = expCount++;
            const html = `
    <div class="repeater-item" id="exp-${idx}">
        <div class="repeater-item-header">
            <span class="repeater-item-label"><i class="fas fa-building"></i> Experience #${idx + 1}</span>
            <button type="button" class="btn-remove-repeater" onclick="document.getElementById('exp-${idx}').remove()">
                <i class="fas fa-trash-alt"></i> Remove
            </button>
        </div>
        <div class="row">
            <div class="mb-form col-6">
                <label class="form-label">Company / Organization *</label>
                <input type="text" name="experience[${idx}][company_name]" class="form-control">
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Job Title *</label>
                <input type="text" name="experience[${idx}][job_title]" class="form-control">
            </div>
            <div class="mb-form col-6">
                <label class="form-label">Start Date *</label>
                <input type="date" name="experience[${idx}][start_date]" class="form-control">
            </div>
            <div class="mb-form col-6">
                <label class="form-label">End Date</label>
                <input type="date" name="experience[${idx}][end_date]" class="form-control">
            </div>
            <div class="mb-form col-12">
                <label class="form-label">Description</label>
                <textarea name="experience[${idx}][description]" class="form-control" rows="2"
                          placeholder="Brief description of your role..."></textarea>
            </div>
        </div>
    </div>`;
            document.getElementById('experience-container').insertAdjacentHTML('beforeend', html);
        });
    </script>
@endsection
