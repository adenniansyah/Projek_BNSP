<!DOCTYPE html>
<html lang="id" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookStore Arjuanda')</title>
    <title>@yield('title', 'BookStore Arjuanda')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📚</text></svg>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* CSS Agar Footer Selalu Di Bawah (Sticky Footer) */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1; /* Memaksa konten utama mengisi ruang kosong */
        }
        .hover-scale:hover { transform: translateY(-3px); transition: all 0.2s ease; }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-book-open me-2"></i>BookStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('books.index') }}">Koleksi Buku</a></li>
                
                <!-- LINK HUBUNGI ADMIN (Hanya muncul jika sudah login) -->
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact.create') }}">Hubungi Admin</a></li>
                @endauth
                
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Tentang Kami</a></li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <!-- Menu Khusus Admin di Navbar Utama -->
                        <li class="nav-item">
                            <a class="nav-link text-warning fw-bold" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i> Dashboard Admin
                            </a>
                        </li>
                    @else
                        <!-- Menu Khusus User -->
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i> Keranjang
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">Pesanan Saya</a>
                        </li>
                    @endif

                    <!-- ✅ DROPDOWN PROFIL (SUDAH DIPERBAIKI) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Tampilkan Avatar jika ada, jika tidak pakai ikon default -->
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                     class="rounded-circle me-2 border border-2 border-white" 
                                     style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle me-1 fs-5"></i>
                            @endif
                            {{ auth()->user()->name }}
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                            
                            <!-- ✅ LINK EDIT PROFIL (Muncul untuk SEMUA Role) -->
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user-edit me-2 text-primary"></i> Edit Profil
                                </a>
                            </li>

                            <!-- Link Khusus User -->
                            @if(auth()->user()->role === 'user')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.messages') }}">
                                        <i class="fas fa-envelope-open-text me-2 text-info"></i> Riwayat Pesan Saya
                                    </a>
                                </li>
                            @endif

                            <!-- Link Khusus Admin -->
                            @if(auth()->user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-warning fw-bold" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Tombol Logout -->
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
                    <!-- Jika Belum Login -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm ms-2 fw-bold text-primary" href="{{ route('register') }}">Daftar</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="py-4">
    <div class="container">
        <!-- Notifikasi Flash Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-auto">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} Book Store Arjuanda. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>