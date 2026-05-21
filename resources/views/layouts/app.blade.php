<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Candidate Portal</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="portal-layout">

        <!-- Sidebar -->
        <aside class="portal-sidebar" id="sidebar">
            <div class="sidebar-brand">
                <span class="sidebar-brand-name">Candidate<span>Portal</span></span>
                <span class="sidebar-brand-sub">Management System</span>
            </div>

            <nav class="sidebar-nav">
                @if (Auth::user()->isAdmin())
                    <span class="sidebar-nav-label">Admin Panel</span>
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.candidates.index') }}"
                        class="sidebar-nav-link {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> All Candidates
                    </a>
                    <a href="{{ route('admin.courses.index') }}"
                        class="sidebar-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i> Courses
                    </a>
                    <a href="{{ route('admin.pricing-models.index') }}"
                        class="sidebar-nav-link {{ request()->routeIs('admin.pricing-models.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i> Pricing Models
                    </a>
                    <a href="{{ route('admin.batches.index') }}"
                        class="sidebar-nav-link {{ request()->routeIs('admin.batches.*') ? 'active' : '' }}">
                        <i class="fa-layer-group fas"></i> Batches
                    </a>
                    <a href="{{ route('admin.candidates.index', ['status' => 'pending']) }}"
                        class="sidebar-nav-link {{ request()->query('status') === 'pending' ? 'active' : '' }}">
                        <i class="fas fa-clock"></i> Pending Review
                    </a>
                    <a href="{{ route('admin.candidates.index', ['status' => 'approved']) }}" class="sidebar-nav-link">
                        <i class="fas fa-check-circle"></i> Approved
                    </a>
                    <a href="{{ route('admin.candidates.index', ['status' => 'rejected']) }}" class="sidebar-nav-link">
                        <i class="fas fa-times-circle"></i> Rejected
                    </a>
                @else
                    <span class="sidebar-nav-label">My Portal</span>
                    <a href="{{ route('candidate.dashboard') }}"
                        class="sidebar-nav-link {{ request()->routeIs('candidate.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> My Dashboard
                    </a>
                @endif
            </nav>

            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ Auth::user()->name }}</span>
                    <span class="sidebar-user-role">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="portal-main">
            <!-- Topbar -->
            <header class="portal-topbar">
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-actions">
                    <span style="font-size:0.85rem; color: var(--text-muted);">
                        {{ Auth::user()->email }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="topbar-logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <div class="portal-content">
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Please fix the following errors:</strong>
                            <ul style="margin:0.4rem 0 0 1rem; padding:0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>
</body>

</html>
