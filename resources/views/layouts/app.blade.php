<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Booking Service - Honda')</title>

    {{-- Fonts: Poppins & Inter for Modern Look --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        :root {
            --honda-red: #CC0000; /* Warna Merah Honda Official */
            --honda-dark: #1a1a1a;
            --bg-light: #f3f4f6;
            --sidebar-width: 80px;
            --navbar-height: 70px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: #333;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Poppins', sans-serif;
        }

        /* ================= NAVBAR STYLING ================= */
        .navbar {
            height: var(--navbar-height);
            background: linear-gradient(90deg, var(--honda-red) 0%, #a30000 100%);
            box-shadow: 0 4px 12px rgba(204, 0, 0, 0.15);
            z-index: 1050;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }

        .honda-logo {
            height: 40px;
            margin-right: 12px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        /* ================= SIDEBAR (ADMIN) ================= */
        .admin-wrapper {
            display: flex;
            min-height: calc(100vh - var(--navbar-height));
            margin-top: var(--navbar-height);
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: #ffffff;
            border-right: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: var(--navbar-height);
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.3s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }

        .sidebar .nav-link {
            color: #6c757d;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 18px 0;
            transition: all 0.2s ease;
            position: relative;
            font-size: 0.75rem; /* Text label size */
            font-weight: 500;
        }

        .sidebar .nav-link i {
            font-size: 1.4rem;
            margin-bottom: 4px;
            transition: transform 0.2s;
        }

        .sidebar .nav-link:hover {
            color: var(--honda-red);
            background-color: #fff5f5;
        }

        .sidebar .nav-link:hover i {
            transform: translateY(-2px);
        }

        .sidebar .nav-link.active {
            color: var(--honda-red);
            background-color: #fff0f0;
            border-right: 3px solid var(--honda-red);
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid #eee;
            padding: 10px 0;
        }

        /* ================= MAIN CONTENT AREA ================= */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width); /* Offset for sidebar */
            padding: 2rem;
            width: 100%;
        }

        /* Card Modernization Helper */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            transition: transform 0.2s;
        }
        
        /* Tombol Modern */
        .btn-primary {
            background-color: var(--honda-red);
            border-color: var(--honda-red);
        }
        .btn-primary:hover {
            background-color: #a30000;
            border-color: #990000;
        }

        /* ================= CUSTOMER / MOBILE ADJUSTMENTS ================= */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 240px; /* Lebar penuh di mobile */
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar .nav-link {
                flex-direction: row;
                justify-content: flex-start;
                padding: 15px 25px;
                font-size: 1rem;
            }
            .sidebar .nav-link i {
                margin-bottom: 0;
                margin-right: 15px;
                width: 24px;
                text-align: center;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
        
        /* Layout untuk Customer (Tanpa Sidebar Kiri) */
        .customer-layout {
            padding-top: calc(var(--navbar-height) + 20px);
            min-height: 100vh;
        }
    </style>
</head>
<body>

    {{-- ================= LOGIKA ADMIN ================= --}}
    @auth
        @if(Auth::user()->role === 'admin')
            
            {{-- NAVBAR ADMIN --}}
            <nav class="navbar navbar-expand navbar-dark fixed-top">
                <div class="container-fluid px-4">
                    {{-- Toggle Mobile --}}
                    <button class="btn btn-link text-white d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <i class="fas fa-bars fs-4"></i>
                    </button>

                    {{-- BRAND + LOGO HONDA --}}
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                        {{-- Logo Honda Wing (Putih) --}}
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Honda.svg/2560px-Honda.svg.png" 
                             alt="Honda Logo" 
                             class="honda-logo"
                             style="filter: brightness(0) invert(1);"> 
                        <span>Admin Panel</span>
                    </a>

                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="bg-white text-danger rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="d-none d-sm-block fw-medium">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user-cog me-2 text-muted"></i> Profile</a></li>
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
                        </ul>
                    </div>
                </div>
            </nav>

            {{-- WRAPPER ADMIN --}}
            <div class="admin-wrapper">
                
                {{-- SIDEBAR --}}
                <div class="sidebar collapse d-md-flex" id="sidebarMenu">
                    <div class="nav flex-column w-100 pt-3">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                            <i class="fas fa-th-large"></i>
                            <span class="d-md-block d-none small mt-1">Home</span>
                            <span class="d-md-none">Dashboard</span>
                        </a>
                        
                        <a href="{{ route('booking.index') }}" class="nav-link {{ Route::is('booking.index') || Route::is('booking.show') ? 'active' : '' }}" title="Booking Data" data-bs-toggle="tooltip" data-bs-placement="right">
                            <i class="fas fa-calendar-check"></i>
                            <span class="d-md-block d-none small mt-1">Bookings</span>
                            <span class="d-md-none">Data Booking</span>
                        </a>

                        @if(Route::has('booking.queue'))
                        <a href="{{ route('booking.queue') }}" class="nav-link {{ Route::is('booking.queue') ? 'active' : '' }}" title="Antrian" data-bs-toggle="tooltip" data-bs-placement="right">
                            <i class="fas fa-stopwatch"></i>
                            <span class="d-md-block d-none small mt-1">Antrian</span>
                            <span class="d-md-none">Antrian</span>
                        </a>
                        @endif

                        <a href="{{ route('customers.index') }}" class="nav-link {{ Route::is('customers.index') ? 'active' : '' }}" title="Pelanggan" data-bs-toggle="tooltip" data-bs-placement="right">
                            <i class="fas fa-users"></i>
                            <span class="d-md-block d-none small mt-1">User</span>
                            <span class="d-md-none">Pelanggan</span>
                        </a>

                        @if(Route::has('advisor.create'))
                        <a href="{{ route('advisor.create') }}" class="nav-link {{ Route::is('advisor.create') ? 'active' : '' }}" title="Advisor" data-bs-toggle="tooltip" data-bs-placement="right">
                            <i class="fas fa-user-tie"></i>
                            <span class="d-md-block d-none small mt-1">Advisor</span>
                            <span class="d-md-none">Service Advisor</span>
                        </a>
                        @endif
                    </div>
                    
                    {{-- Sidebar Footer (Logout Mobile Only) --}}
                    <div class="sidebar-footer d-md-none px-3">
                        <form action="{{ route('logout') }}" method="POST">
                             @csrf
                             <button class="btn btn-outline-danger w-100"><i class="fas fa-power-off me-2"></i> Logout</button>
                        </form>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="main-content">
                    @yield('content')
                </div>
            </div>

        {{-- ================= LOGIKA CUSTOMER ================= --}}
        @elseif(Auth::user()->role === 'customer')
            
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
                <div class="container">
                    {{-- LOGO HONDA UNTUK CUSTOMER --}}
                    <a class="navbar-brand" href="{{ route('customers.dashboard') }}">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Honda.svg/2560px-Honda.svg.png" 
                             alt="Honda Logo" 
                             class="honda-logo"
                             style="filter: brightness(0) invert(1);"> 
                        Booking Service
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#custNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="custNav">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-medium">
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('customers.dashboard') ? 'active' : '' }}" href="{{ route('customers.dashboard') }}">Dashboard</a>
                            </li>
                            @if(Route::has('booking.create'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('booking.create') ? 'active' : '' }}" href="{{ route('booking.create') }}">Booking Baru</a>
                            </li>
                            @endif
                            @if(Route::has('profile'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('profile') ? 'active' : '' }}" href="{{ route('profile') }}">Profil</a>
                            </li>
                            @endif
                        </ul>
                        <form action="{{ route('logout') }}" method="POST" class="d-flex ms-lg-3">
                            @csrf
                            <button class="btn btn-light text-danger fw-bold rounded-pill px-4" type="submit">Keluar</button>
                        </form>
                    </div>
                </div>
            </nav>

            <main class="customer-layout container">
                @yield('content')
            </main>

        @endif

    {{-- ================= HALAMAN TAMU (Login/Register) ================= --}}
    @else
        <main>
            @yield('content')
        </main>
    @endauth

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    @yield('scripts')
</body>
</html>