<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('user_page_title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --sidebar-width: 260px;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Architecture Layouts */
        #sidebar {
            background: var(--primary-gradient) !important;
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            min-height: 100vh;
            color: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(79, 70, 229, 0.15);
            z-index: 1040;
        }

        .sidebar-brand {
            color: #fff !important;
            font-weight: 700;
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.3rem;
            text-decoration: none;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-link {
            color: rgba(255, 255, 255, 0.75) !important;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
            margin: 4px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff !important;
            transform: translateX(4px);
        }

        .sidebar-item.active .sidebar-link {
            background: #ffffff !important;
            color: #4f46e5 !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Top Navigation Navbar Custom Design rules */
        .navbar {
            background: #ffffff !important;
            padding: 0.85rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar-brand-text {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
        }

        .nav-btn-toggle {
            background: #f1f5f9;
            border: none;
            padding: 8px;
            border-radius: 8px;
            color: #64748b;
            transition: all 0.2s;
        }

        .nav-btn-toggle:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .dropdown-menu {
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .main-wrapper {
            min-width: 0;
            width: 100%;
        }

        /* Modernized Custom Notification Scrollbar */
        .notification-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .notification-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .notification-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Mobile Layout Viewport Breakpoints */
        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                left: calc(-1 * var(--sidebar-width));
                height: 100vh;
            }

            #sidebar.show-mobile {
                left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(15, 23, 42, 0.3);
                backdrop-filter: blur(4px);
                z-index: 1030;
                display: none;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Desktop Layout Scaling Extensions */
        @media (min-width: 992px) {
            #sidebar.collapsed {
                margin-left: calc(-1 * var(--sidebar-width));
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="wrapper d-flex">
        <nav id="sidebar">
            <a class="sidebar-brand" href="{{ url('/') }}">
                <i data-lucide="layout-grid" style="width: 24px; height: 24px;"></i>
                <span>User Panel</span>
            </a>
            <ul class="nav flex-column mt-4">
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('dashboard') }}">
                        <i data-lucide="layout-dashboard" style="width: 18px; height: 18px;"></i> Dashboard
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('user.order.history') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('user.order.history') }}">
                        <i data-lucide="shopping-bag" style="width: 18px; height: 18px;"></i> Order History
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('user.payment') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('user.payment') }}">
                        <i data-lucide="credit-card" style="width: 18px; height: 18px;"></i> Payment
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('user.affiliate') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('user.affiliate') }}">
                        <i data-lucide="users" style="width: 18px; height: 18px;"></i> Affiliate
                    </a>
                </li>
                <li
                    class="sidebar-item {{ request()->routeIs('user.profile') || request()->routeIs('user.password.edit') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('user.profile') }}">
                        <i data-lucide="user-circle" style="width: 18px; height: 18px;"></i> My Profile
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-wrapper d-flex flex-column flex-grow-1">
            <nav class="navbar navbar-expand align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <button class="nav-btn-toggle" id="sidebarToggle" aria-label="Toggle Navigation">
                        <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                    </button>
                    <span class="navbar-brand-text d-none d-sm-inline-block">
                        Welcome back, <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </span>
                </div>

                <div class="navbar-nav align-items-center gap-2">
                    <div class="dropdown me-2">
                        <a class="nav-link text-secondary position-relative p-2 rounded-circle bg-light d-flex align-items-center justify-content-center"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="width: 38px; height: 38px;">
                            <i data-lucide="bell" style="width: 18px; height: 18px;" class="text-dark"></i>

                            @if (($unreadNotificationsCount = Auth::user()->unreadNotifications->count()) > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white d-flex align-items-center justify-content-center"
                                    style="font-size: 10px; padding: 0.35em 0.5em; min-width: 18px; min-height: 18px; transform: translate(-35%, -15%) !important;">
                                    {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2" style="width: 320px;">
                            <li
                                class="px-3 py-2 fw-bold border-bottom d-flex justify-content-between align-items-center">
                                <span class="text-dark">Notifications</span>
                                @if ($unreadNotificationsCount > 0)
                                    <span
                                        class="badge bg-primary-subtle text-primary rounded-pill small">{{ $unreadNotificationsCount }}
                                        New</span>
                                @endif
                            </li>

                            <div class="notification-scroll" style="max-height: 280px; overflow-y: auto;">
                                @forelse(Auth::user()->notifications as $notification)
                                    <li class="dropdown-item text-wrap p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light-subtle fw-medium' }}"
                                        style="border-left: 3px solid {{ $notification->read_at ? 'transparent' : '#4f46e5' }}">
                                        <div class="small text-dark mb-1">
                                            {{ isset($notification->data['message']) ? $notification->data['message'] : json_encode($notification->data) }}
                                        </div>
                                        <div class="text-muted d-flex align-items-center" style="font-size: 11px;">
                                            <i data-lucide="clock" class="me-1"
                                                style="width: 12px; height: 12px;"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </li>
                                @empty
                                    <li class="dropdown-item text-center text-muted py-4">
                                        <i data-lucide="bell-off" class="d-block mx-auto text-light mb-2"
                                            style="width: 28px; height: 28px;"></i>
                                        <span class="small">No new notifications available</span>
                                    </li>
                                @endforelse
                            </div>

                            @if (Auth::user()->notifications->count() > 0)
                                <li>
                                    <a href="{{ route('user.notifications.readAll') }}"
                                        class="dropdown-item text-center text-primary small fw-semibold py-2 border-top btn-light">
                                        Mark all as read
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <a class="btn btn-light btn-sm d-flex align-items-center gap-1 border-0 px-2 px-sm-3 py-2 rounded-3 text-secondary fw-medium"
                        href="{{ url('/') }}" style="height: 38px;">
                        <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                        <span class="d-none d-md-inline">Home</span>
                    </a>

                    <div class="dropdown">
                        <a class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 px-2 px-sm-3 py-2 rounded-3 text-dark fw-semibold dropdown-toggle"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="height: 38px;">

                            @if (Auth::user()->image)
                                <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                    alt="{{ Auth::user()->name }}" class="rounded-circle"
                                    style="width: 24px; height: 24px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                    style="width: 24px; height: 24px; font-size: 11px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif

                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 mt-2">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 text-secondary py-2"
                                    href="{{ route('user.profile') }}">
                                    <i data-lucide="settings" style="width: 16px; height: 16px;"></i> Profile Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider opacity-50">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item text-danger d-flex align-items-center gap-2 py-2"
                                        style="background: none; width: 100%;">
                                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="content p-3 p-md-4 flex-grow-1">
                @yield('user_layout')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Place this right before your closing </body> tag in your layout files -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Render all Lucide vector assets instantly
            lucide.createIcons();

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            // Intelligently control structural view scaling parameters
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle('show-mobile');
                    overlay.classList.toggle('active');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });

            // Dismiss responsive mobile overlays automatically upon safe clickaway
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show-mobile');
                overlay.classList.remove('active');
            });
        });
    </script>

</body>

</html>
