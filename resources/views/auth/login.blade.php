@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-form-header">
    <h2>Welcome Back</h2>
    <p>Sign in to your account to continue</p>
</div>

<form action="{{ route('login.post') }}" method="POST" novalidate>
    @csrf

    <div class="mb-form">
        <label class="form-label" for="email">Email Address</label>
        <input type="email" id="email" name="email"
               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email') }}"
               placeholder="you@example.com" autocomplete="email">
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-form">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password"
               class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
               placeholder="Enter your password" autocomplete="current-password">
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-form" style="display:flex; align-items:center; gap:8px;">
        <input type="checkbox" name="remember" id="remember" value="1"
               {{ old('remember') ? 'checked' : '' }}
               style="width:16px; height:16px; cursor:pointer;">
        <label for="remember" style="cursor:pointer; color: var(--text-muted); font-size:0.9rem;">
            Remember me
        </label>
    </div>

    <button type="submit" class="btn-block btn btn-primary btn-lg" style="margin-bottom:1.2rem;">
        <i class="fas fa-sign-in-alt"></i> Sign In
    </button>

    <p style="text-align:center; color: var(--text-muted); font-size:0.9rem;">
        Don't have an account?
        <a href="{{ route('register') }}" style="font-weight:600;">Apply Now</a>
    </p>
</form>
@endsection