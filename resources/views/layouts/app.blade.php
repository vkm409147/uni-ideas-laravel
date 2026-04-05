<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'University Ideas System') }}</title>

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root { 
            --primary: #4e73df; 
            --primary-dark: #224abe;
            --bg-light: #f8f9fc; 
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-light); 
            color: #333;
        }

        /* Navbar Styling */
        .navbar { 
            background: white !important; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            padding: 12px 0;
        }
        .navbar-brand { font-weight: 800; color: var(--primary) !important; font-size: 1.4rem; }
        
        .nav-link { font-weight: 500; color: #555 !important; transition: 0.3s; border-bottom: 2px solid transparent; }
        .nav-link:hover { color: var(--primary) !important; }
        
        /* Highlight trang hiện tại */
        .active-link {
            color: var(--primary) !important;
            font-weight: 700 !important;
            border-bottom: 2px solid var(--primary) !important;
        }

        /* UI Elements */
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-primary { background-color: var(--primary); border: none; border-radius: 8px; font-weight: 600; padding: 8px 20px; }
        .btn-primary:hover { background-color: var(--primary-dark); }

        .dropdown-menu { border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 10px; }
        .dropdown-item { padding: 10px 20px; transition: 0.2s; }
        .dropdown-item:hover { background-color: var(--bg-light); color: var(--primary); padding-left: 25px; }

        .alert { border-radius: 10px; border: none; animation: slideDown 0.4s ease-out; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            {{-- Logo --}}
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="fas fa-university me-2"></i>
                <span>UNI-IDEAS</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto ms-lg-4">
                    {{-- 1. TRANG CỘNG ĐỒNG: Ai cũng thấy --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ideas.*') ? 'active-link' : '' }}" href="{{ route('ideas.index') }}">
                            <i class="fas fa-lightbulb me-1"></i> Discover
                        </a>
                    </li>

                    {{-- 2. DASHBOARD: Admin, QA Manager, Coordinator --}}
                    @if(auth()->check() && in_array(auth()->user()->role_id, [1, 2, 3]))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-line me-1"></i> Dashboard
                        </a>
                    </li>
                    @endif

                    {{-- 3. MANAGEMENT: Chỉ dành cho Admin (1) và QA Manager (2) --}}
                    @if(auth()->check() && (auth()->user()->role_id == 1 || auth()->user()->role_id == 2))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-tasks me-1"></i> Management
                        </a>
                        <ul class="dropdown-menu mt-2">
                            {{-- Quyền của QA Manager --}}
                            @if(auth()->user()->role_id == 2)
                                <li><a class="dropdown-item" href="{{ route('categories.index') }}"><i class="fas fa-tags me-2"></i>Categories</a></li>
                                <li><a class="dropdown-item" href="{{ route('ideas.export') }}"><i class="fas fa-file-download me-2"></i>Export Data (CSV/ZIP)</a></li>
                            @endif

                            {{-- Quyền của Admin --}}
                            @if(auth()->user()->role_id == 1)
                                <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users-cog me-2"></i>User Accounts</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="fas fa-calendar-check me-2"></i>Closure Dates</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                </ul>

                {{-- PHẦN THÔNG TIN USER BÊN PHẢI --}}
<ul class="navbar-nav ms-auto align-items-center">
    @auth
    <li class="nav-item dropdown">
        <a class="btn btn-outline-primary dropdown-toggle rounded-pill px-3 fw-bold" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-1"></i> 
            {{ auth()->user()->name }}
            
            {{-- Badge hiện Role trực tiếp --}}
            @php
                $badgeClass = match(auth()->user()->role_id) {
                    1 => 'bg-danger',
                    2 => 'bg-success',
                    3 => 'bg-warning text-dark',
                    default => 'bg-info text-dark',
                };
            @endphp
            <span class="badge {{ $badgeClass }} ms-1" style="font-size: 0.7rem; vertical-align: middle;">
                {{ auth()->user()->role->name ?? 'Staff' }}
            </span>
        </a>
        
        <ul class="dropdown-menu dropdown-menu-end mt-3 border-0 shadow">
            <li class="px-4 py-2 small text-muted text-uppercase fw-bold text-center">
                User Profile
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </li>
    @else
    <li class="nav-item">
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
    </li>
    @endauth
</ul>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4 pb-5">
        {{-- Thông báo Success/Error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-start border-success border-5">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3 fs-4"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-5">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content') 
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>