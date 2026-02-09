<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aspirasi Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-active {
            background-color: #3b82f6;
            color: white;
        }

        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translateY(0); }
            40%, 43% { transform: translateY(-10px); }
            70% { transform: translateY(-5px); }
            90% { transform: translateY(-2px); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .slide-in-left {
            animation: slideInLeft 0.5s ease-out;
        }

        .slide-in-right {
            animation: slideInRight 0.5s ease-out;
        }

        .pulse-on-hover:hover {
            animation: pulse 0.3s ease-in-out;
        }

        .bounce-on-hover:hover {
            animation: bounce 0.5s ease-in-out;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .btn-hover {
            transition: all 0.2s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .sidebar-item {
            transition: all 0.2s ease;
        }

        .sidebar-item:hover {
            background-color: #1e40af;
            transform: translateX(5px);
        }

        .status-badge {
            transition: all 0.2s ease;
        }

        .status-badge:hover {
            transform: scale(1.1);
        }

        /* Loading animation */
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Success animation */
        .success-checkmark {
            animation: fadeIn 0.5s ease-out;
        }

        /* Smooth transitions */
        * {
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        }
    </style>
</head>
<body class="bg-gray-100">
    @auth
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold">Aspirasi SL</h2>
            </div>

            <nav class="mt-8">
                @if(auth()->user()->role === 'siswa')
                    <!-- Siswa Navigation -->
                    <a href="{{ route('siswa.dashboard') }}" class="sidebar-item block px-4 py-3 {{ request()->routeIs('siswa.dashboard') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('siswa.laporan.create') }}" class="sidebar-item block px-4 py-3 {{ request()->routeIs('siswa.laporan.create') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-plus-circle mr-2"></i> Kirim Aspirasi
                    </a>
                    <a href="{{ route('siswa.laporan.index') }}" class="sidebar-item block px-4 py-3 {{ request()->routeIs('siswa.laporan.*') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-list mr-2"></i> Semua Laporan
                    </a>
                    <a href="{{ route('siswa.laporan-saya') }}" class="sidebar-item block px-4 py-3 {{ request()->routeIs('siswa.laporan-saya') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-user-edit mr-2"></i> Laporan Saya
                    </a>
                @else
                    <!-- Admin Navigation -->
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item block px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="sidebar-item block px-4 py-3 {{ request()->routeIs('admin.laporan.*') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-clipboard-list mr-2"></i> Semua Laporan
                    </a>
                    <a href="{{ route('admin.siswa.index') }}" class="sidebar-item block px-4 py-3 {{ request()->routeIs('admin.siswa.*') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-users mr-2"></i> Data Siswa
                    </a>
                @endif

                <!-- Profile Navigation -->
                <div class="absolute bottom-0 w-64">
                    <a href="{{ auth()->user()->role === 'siswa' ? route('siswa.profile') : route('admin.profile') }}"
                       class="sidebar-item block px-4 py-3 {{ request()->routeIs('*.profile') ? 'sidebar-active' : '' }}">
                        <i class="fas fa-user mr-2"></i> Edit Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="sidebar-item w-full text-left px-4 py-3 hover:bg-red-600">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                 alt="Profile" class="w-10 h-10 rounded-full">
                        @else
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @else
    @yield('content')
    @endauth

    <!-- JavaScript untuk Animasi -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi fade-in untuk cards
            const cards = document.querySelectorAll('.bg-white, .bg-gray-50');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animasi untuk sidebar items
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease-out';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 50);
            });

            // Animasi untuk buttons
            const buttons = document.querySelectorAll('.btn-hover');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Animasi untuk status badges
            const statusBadges = document.querySelectorAll('.status-badge');
            statusBadges.forEach(badge => {
                badge.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1)';
                });
                badge.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Animasi untuk form inputs
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
                });
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = 'none';
                });
            });

            // Animasi untuk success/error messages
            const messages = document.querySelectorAll('.bg-green-100, .bg-red-100, .bg-blue-100');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    message.style.transition = 'all 0.5s ease-out';
                    message.style.opacity = '1';
                    message.style.transform = 'translateY(0)';
                }, 100);

                // Auto hide setelah 5 detik
                setTimeout(() => {
                    message.style.transition = 'all 0.5s ease-out';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        message.remove();
                    }, 500);
                }, 5000);
            });

            // Animasi untuk table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease-out';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, index * 50);
            });

            // Animasi untuk pagination links
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Smooth scroll untuk anchor links
            const anchorLinks = document.querySelectorAll('a[href^="#"]');
            anchorLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Loading animation untuk form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton) {
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = '<div class="loading-spinner inline-block mr-2"></div> Loading...';
                        submitButton.disabled = true;

                        // Reset setelah 3 detik (jika ada error)
                        setTimeout(() => {
                            submitButton.innerHTML = originalText;
                            submitButton.disabled = false;
                        }, 3000);
                    }
                });
            });

            // Animasi untuk dashboard stats
            const statCards = document.querySelectorAll('.grid > div');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });

        // Fungsi untuk animasi notifikasi
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 fade-in ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${
                        type === 'success' ? 'fa-check-circle' :
                        type === 'error' ? 'fa-exclamation-circle' :
                        'fa-info-circle'
                    } mr-2"></i>
                    ${message}
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 3000);
        }
    </script>
</body>
</html>
