<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('admin_page_title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireStyles
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .wrapper {
            display: flex;
            overflow-x: hidden;
            transition: all 0.3s;
        }

        /* Sidebar Premium Style */
        .sidebar {
            width: 260px;
            background: #0f172a;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
            color: #94a3b8;
            border-right: 1px solid #1e293b;
        }

        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar-header {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 1.5rem 1.5rem 0.5rem;
            color: #64748b;
            font-weight: 700;
        }

        .sidebar-link {
            color: #94a3b8;
            padding: 0.7rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .sidebar-link i,
        .sidebar-link svg {
            width: 18px;
            height: 18px;
            color: #64748b;
            transition: color 0.2s ease;
        }

        .sidebar-link:hover {
            background: #1e293b;
            color: #f8fafc;
        }

        .sidebar-link:hover i,
        .sidebar-link:hover svg {
            color: #3b82f6;
        }

        /* Active State */
        .sidebar-item.active>.sidebar-link {
            background: #1e293b;
            color: #3b82f6;
            border-left: 4px solid #3b82f6;
        }

        .sidebar-item.active>.sidebar-link i,
        .sidebar-item.active>.sidebar-link svg {
            color: #3b82f6;
        }

        /* Dropdown Submenu */
        .sidebar-submenu {
            background: #0b0f19;
            list-style: none;
            padding-left: 0 !important;
            transition: all 0.3s ease;
        }

        .sidebar-submenu .sidebar-link {
            padding-left: 3rem;
            font-size: 0.825rem;
        }

        .sidebar-submenu .sidebar-item.active .sidebar-link {
            color: #3b82f6;
            background: transparent;
            border-left: none;
        }

        .sidebar-link .arrow {
            margin-left: auto;
            transition: transform 0.2s;
            width: 16px;
            height: 16px;
        }

        .sidebar-link[aria-expanded="true"] .arrow {
            transform: rotate(90deg);
        }

        /* Navbar */
        .navbar {
            background: #fff !important;
            border-bottom: 1px solid #e2e8f0;
            height: 70px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .main {
            margin-left: 260px;
            transition: all 0.3s ease;
            width: calc(100% - 260px);
        }

        .wrapper.toggled .sidebar {
            margin-left: -260px;
        }

        .wrapper.toggled .main {
            margin-left: 0;
            width: 100%;
        }

        .content {
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }

        #sidebarToggleBtn {
            background: #f1f5f9;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }

        #sidebarToggleBtn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 998;
            display: none;
        }

        @media (max-width: 991px) {
            .sidebar {
                margin-left: -260px;
            }

            .sidebar.active {
                margin-left: 0 !important;
            }

            .sidebar.active~.sidebar-overlay {
                display: block !important;
            }

            .main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        /* Global Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(2px);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        <nav class="sidebar" id="sidebar">
            <a class="sidebar-brand" href="{{ route('admin') }}">
                <i data-lucide="shield-check" style="color: #3b82f6;"></i> Admin Control
            </a>

            <ul class="list-unstyled mb-4">
                <li class="sidebar-header">Overview</li>
                <li class="sidebar-item {{ request()->routeIs('admin') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin') }}">
                        <i data-lucide="layout-dashboard"></i> Dashboard
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.reports.index') }}">
                        <i data-lucide="bar-chart-2"></i> Reports
                    </a>
                </li>

                <li class="sidebar-header">Product Catalog</li>

                <li
                    class="sidebar-item {{ request()->routeIs(['category.create', 'category.manage']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['category.create', 'category.manage']) ? '' : 'collapsed' }}"
                        href="#categorySubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['category.create', 'category.manage']) ? 'true' : 'false' }}">
                        <i data-lucide="folder-tree"></i>
                        <span>Categories</span>
                        <i data-lucide="chevron-right" class="arrow"></i>
                    </a>
                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['category.create', 'category.manage']) ? 'show' : '' }}"
                        id="categorySubmenu">
                        <li class="{{ request()->routeIs('category.create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('category.create') }}"><i data-lucide="plus"></i>
                                Create Category</a>
                        </li>
                        <li class="{{ request()->routeIs('category.manage') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('category.manage') }}"><i data-lucide="menu"></i>
                                Manage Categories</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="sidebar-item {{ request()->routeIs(['subcategory.create', 'subcategory.manage']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['subcategory.create', 'subcategory.manage']) ? '' : 'collapsed' }}"
                        href="#subCategorySubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['subcategory.create', 'subcategory.manage']) ? 'true' : 'false' }}">
                        <i data-lucide="git-merge"></i>
                        <span>Sub Categories</span>
                        <i data-lucide="chevron-right" class="arrow"></i>
                    </a>
                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['subcategory.create', 'subcategory.manage']) ? 'show' : '' }}"
                        id="subCategorySubmenu">
                        <li class="{{ request()->routeIs('subcategory.create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('subcategory.create') }}"><i data-lucide="plus"></i>
                                Create Sub</a>
                        </li>
                        <li class="{{ request()->routeIs('subcategory.manage') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('subcategory.manage') }}"><i data-lucide="menu"></i>
                                Manage Subs</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->routeIs('product.manage') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('product.manage') }}">
                        <i data-lucide="shopping-bag"></i> Products
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.reviews.manage') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.reviews.manage') }}">
                        <i data-lucide="star"></i> Reviews
                    </a>
                </li>

                <li class="sidebar-header">Operations & Rules</li>

                <li
                    class="sidebar-item {{ request()->routeIs(['productattribute.create', 'productattribute.manage']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['productattribute.create', 'productattribute.manage']) ? '' : 'collapsed' }}"
                        href="#attributeSubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['productattribute.create', 'productattribute.manage']) ? 'true' : 'false' }}">
                        <i data-lucide="sliders"></i>
                        <span>Attributes</span>
                        <i data-lucide="chevron-right" class="arrow"></i>
                    </a>
                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['productattribute.create', 'productattribute.manage']) ? 'show' : '' }}"
                        id="attributeSubmenu">
                        <li class="{{ request()->routeIs('productattribute.create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('productattribute.create') }}"><i
                                    data-lucide="plus"></i> Add Attribute</a>
                        </li>
                        <li class="{{ request()->routeIs('productattribute.manage') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('productattribute.manage') }}"><i
                                    data-lucide="menu"></i> Manage</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="sidebar-item {{ request()->routeIs(['admin.discount.create', 'admin.discount.manage']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['admin.discount.create', 'admin.discount.manage']) ? '' : 'collapsed' }}"
                        href="#discountSubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['admin.discount.create', 'admin.discount.manage']) ? 'true' : 'false' }}">
                        <i data-lucide="percent"></i>
                        <span>Discounts</span>
                        <i data-lucide="chevron-right" class="arrow"></i>
                    </a>
                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['admin.discount.create', 'admin.discount.manage']) ? 'show' : '' }}"
                        id="discountSubmenu">
                        <li class="{{ request()->routeIs('admin.discount.create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.discount.create') }}"><i
                                    data-lucide="plus"></i> Create Code</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.discount.manage') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.discount.manage') }}"><i
                                    data-lucide="ticket"></i> Manage Codes</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="sidebar-item {{ request()->routeIs(['admin.manage.vendors', 'admin.manage.users', 'admin.manage.stores']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['admin.manage.vendors', 'admin.manage.users', 'admin.manage.stores']) ? '' : 'collapsed' }}"
                        href="#manageSubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['admin.manage.vendors', 'admin.manage.users', 'admin.manage.stores']) ? 'true' : 'false' }}">
                        <i data-lucide="users"></i>
                        <span>User & Stores</span>
                        <i data-lucide="chevron-right" class="arrow"></i>
                    </a>
                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['admin.manage.vendors', 'admin.manage.users', 'admin.manage.stores']) ? 'show' : '' }}"
                        id="manageSubmenu">
                        <li class="{{ request()->routeIs('admin.manage.vendors') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.manage.vendors') }}"><i
                                    data-lucide="store"></i> Vendors</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.manage.users') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.manage.users') }}"><i
                                    data-lucide="user"></i> Users</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.manage.stores') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.manage.stores') }}"><i
                                    data-lucide="home"></i> Stores</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-header">Sales & Financials</li>
                <li class="sidebar-item {{ request()->routeIs('admin.manage.commission') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.manage.commission') }}">
                        <i data-lucide="coins"></i> Commissions
                    </a>
                </li>

                <li
                    class="sidebar-item {{ request()->routeIs(['admin.cart.history', 'admin.order.history']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['admin.cart.history', 'admin.order.history']) ? '' : 'collapsed' }}"
                        href="#historySubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['admin.cart.history', 'admin.order.history']) ? 'true' : 'false' }}">
                        <i data-lucide="history"></i>
                        <span>Logs & History</span>
                        <i data-lucide="chevron-right" class="arrow"></i>
                    </a>
                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['admin.cart.history', 'admin.order.history']) ? 'show' : '' }}"
                        id="historySubmenu">
                        <li class="{{ request()->routeIs('admin.cart.history') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.cart.history') }}"><i
                                    data-lucide="shopping-cart"></i> Cart Logs</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.order.history') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.order.history') }}"><i
                                    data-lucide="file-spreadsheet"></i> Order Logs</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="sidebar-item {{ request()->routeIs(['admin.payment.add', 'admin.payment.manage']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['admin.payment.add', 'admin.payment.manage']) ? '' : 'collapsed' }}"
                        href="#paymentSubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['admin.payment.add', 'admin.payment.manage']) ? 'true' : 'false' }}">
                        <i data-lucide="credit-card"></i>
                        <span>Payments</span>
                        <i data-lucide="chevron-right" class="arrow"></i>
                    </a>
                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['admin.payment.add', 'admin.payment.manage']) ? 'show' : '' }}"
                        id="paymentSubmenu">
                        <li class="{{ request()->routeIs('admin.payment.add') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.payment.add') }}"><i
                                    data-lucide="plus-circle"></i> Add Method</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.payment.manage') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.payment.manage') }}"><i
                                    data-lucide="settings-2"></i> Manage Methods</a>
                        </li>
                        <!-- បន្ថែមនៅក្រោម Payment Submenu -->
                        <li class="sidebar-item {{ request()->routeIs('admin.payouts') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('admin.payouts') }}">
                                <i data-lucide="dollar-sign"></i> <span>Payout Requests</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- System Controls Group -->
                <li class="sidebar-header">System Controls</li>

                <li
                    class="sidebar-item {{ request()->routeIs(['admin.pending', 'admin.manage.profile', 'admin.settings']) ? 'active' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs(['admin.pending', 'admin.manage.profile', 'admin.settings']) ? '' : 'collapsed' }}"
                        href="#systemSubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->routeIs(['admin.pending', 'admin.manage.profile', 'admin.settings']) ? 'true' : 'false' }}">
                        <i data-lucide="settings"></i> <span>System Controls</span> <i data-lucide="chevron-right"
                            class="arrow"></i>
                    </a>

                    <ul class="collapse sidebar-submenu {{ request()->routeIs(['admin.pending', 'admin.manage.profile', 'admin.settings']) ? 'show' : '' }}"
                        id="systemSubmenu">

                        <li>
                            <a class="sidebar-link" href="{{ route('admin.pending') }}">
                                <i data-lucide="user-check"></i> Pending Vendors
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{ route('admin.manage.profile') }}">
                                <i data-lucide="user"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{ route('admin.settings') }}">
                                <i data-lucide="sliders-horizontal"></i> Settings
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
        </nav>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="main">
            <nav
                class="navbar px-4 d-flex justify-content-between align-items-center bg-white border-bottom border-light">
                <button type="button" id="sidebarToggleBtn"
                    class="btn p-0 border-0 text-secondary d-flex align-items-center">
                    <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                </button>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <a href="{{ url('/') }}" target="_blank"
                        class="text-decoration-none text-secondary small fw-medium d-flex align-items-center gap-1.5 transition-all hover-text-primary">
                        <i data-lucide="globe" style="width: 16px; height: 16px;"></i>
                        <span class="d-none d-sm-inline">Visit Site</span>
                    </a>

                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown"
                            class="d-flex align-items-center text-decoration-none">
                            <img src="{{ Auth::user()->image ? asset('upload/admin_images/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D6EFD&color=fff' }}"
                                class="rounded-circle shadow-sm border border-2 border-white object-cover avatar"
                                style="width: 35px; height: 35px; object-fit: cover;" alt="Admin Avatar">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 p-1.5 rounded-3"
                            style="min-width: 160px; font-size: 0.875rem;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 text-dark fw-medium"
                                    href="{{ route('admin.manage.profile') }}">
                                    <i data-lucide="user" class="text-muted" style="width: 16px; height: 16px;"></i>
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider opacity-25 my-1">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 rounded-2 fw-medium">
                                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="content">
                @yield('admin_layout')

                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: "{{ session('success') }}",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    </script>
                @endif
            </main>
        </div>
    </div>
    <!-- Page Load Spinner -->
    <div id="pageLoader" class="loading-overlay">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @livewireScripts

    <script>
        const loader = document.getElementById('pageLoader');

        // ១. លាក់ Loading វិញនៅពេលទំព័រដំបូងត្រូវបាន Load ជោគជ័យ (Normal Page Load)
        window.addEventListener('load', function() {
            if (loader) loader.style.setProperty('display', 'none', 'important');
        });

        // ២. គ្រប់គ្រង Loading សម្រាប់ Livewire (Real-time AJAX)
        document.addEventListener('livewire:init', () => {
            // កូដនេះដំណើរការសម្រាប់ Livewire Version 3
            Livewire.hook('commit', ({
                component,
                commit,
                respond,
                succeed,
                fail
            }) => {
                // បង្ហាញ Loading ពេលចាប់ផ្តើម Request
                if (loader) loader.style.display = 'flex';

                succeed(({
                    snapshot,
                    effect
                }) => {
                    // លាក់ Loading វិញពេលជោគជ័យ
                    if (loader) loader.style.setProperty('display', 'none', 'important');
                });

                fail(() => {
                    // លាក់ Loading វិញទោះបីជាមានកំហុស (Error) ក៏ដោយ
                    if (loader) loader.style.setProperty('display', 'none', 'important');
                });
            });
        });

        // សម្រាប់គាំទ្រ Livewire Version 2 (ក្នុងករណីអ្នកកំពុងប្រើជំនាន់ចាស់)
        document.addEventListener("DOMContentLoaded", () => {
            if (window.Livewire) {
                Livewire.hook('message.sent', () => {
                    if (loader) loader.style.display = 'flex';
                });
                Livewire.hook('message.processed', () => {
                    if (loader) loader.style.setProperty('display', 'none', 'important');
                });
                Livewire.hook('message.failed', () => {
                    if (loader) loader.style.setProperty('display', 'none', 'important');
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const wrapper = document.querySelector('.wrapper');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    wrapper.classList.toggle('toggled');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    wrapper.classList.remove('toggled');
                });
            }
        });

        // Re-initialize Lucide on submenu collapse to prevent visual bugs
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(item => {
            item.addEventListener('click', () => {
                setTimeout(() => {
                    lucide.createIcons();
                }, 250);
            });
        });
    </script>
</body>

</html>
