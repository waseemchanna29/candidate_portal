<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Candidate Portal')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="auth-wrapper">
    <!-- Brand Panel -->
    <div class="auth-brand-panel">
        <div class="auth-brand-logo">Candidate<span>Portal</span></div>
        <p class="auth-brand-tagline">Streamlined candidate registration and verification system</p>
        <ul class="auth-brand-features">
            <li><i class="fas fa-user-graduate"></i> Submit Your Application</li>
            <li><i class="fas fa-file-alt"></i> Upload Documents</li>
            <li><i class="fas fa-check-circle"></i> Admin Verification</li>
            <li><i class="fas fa-id-card"></i> Get Unique Code</li>
        </ul>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-panel">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>
</body>
</html>