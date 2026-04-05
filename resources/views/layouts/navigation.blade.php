{{-- 1. Trang chủ - Dùng chung cho mọi User --}}
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active-link' : '' }}" href="{{ route('dashboard') }}">
        <i class="fas fa-home me-1"></i> Dashboard
    </a>
</li>

{{-- 2. Đăng ý tưởng - Dùng cho Staff (Thường là role_id = 3 hoặc mọi user đã login) --}}
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('ideas.create') ? 'active-link' : '' }}" href="{{ route('ideas.create') }}">
        <i class="fas fa-lightbulb me-1"></i> Submit Idea
    </a>
</li>

{{-- 3. Menu dành cho ADMIN (role_id = 1) --}}
@if(auth()->user()->role_id == 1)
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('users.index') ? 'active-link' : '' }}" href="{{ route('users.index') }}">
        <i class="fas fa-users-cog me-1"></i> User Management
    </a>
</li>
@endif

{{-- 4. Menu dành cho QA MANAGER (role_id = 2) --}}
@if(auth()->user()->role_id == 2)
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('categories.index') ? 'active-link' : '' }}" href="{{ route('categories.index') }}">
        <i class="fas fa-folder me-1"></i> Category Management
    </a>
</li>
<li class="nav-item border-start ms-2 ps-2">
    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-chart-line me-1"></i> Statistics
    </a>
</li>
@endif

{{-- 5. Thông tin User & Nút Đăng xuất --}}
<li class="nav-item dropdown ms-lg-3">
    <a class="nav-link dropdown-toggle fw-bold text-dark border rounded-pill px-3" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
        <i class="fas fa-user-circle me-1 text-primary"></i> {{ auth()->user()->name }}
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger fw-bold">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</li>