<x-guest-layout>
    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 min-vh-100">

            <!-- 🌌 ផ្នែកខាងឆ្វេង៖ រូបភាព និង Branding -->
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between p-5 text-white position-relative"
                style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.95), rgba(59, 130, 246, 0.9)), url('https://images.unsplash.com/photo-1557821552-17105176677c?q=80&w=1632&auto=format&fit=crop') no-repeat center center; background-size: cover;">

                <!-- Logo ផ្នែកខាងលើ -->
                <div class="brand-wrapper">
                    <a href="/" class="brand-link-side">
                        <i class="fa-solid fa-bag-shopping text-success"></i> Quick<span>Cart</span>
                    </a>
                </div>

                <!-- អត្ថបទផ្សព្វផ្សាយចំកណ្តាល -->
                <div class="my-auto max-w-md animate__animated animate__fadeInLeft">
                    <h1 class="display-5 fw-extrabold mb-3 text-white">Premium Marketplace for Everyone</h1>
                    <p class="lead text-white-50 mb-4">Discover verified local vendors, secure multi-payment systems,
                        and seamless shopping experiences instantly.</p>
                    <div class="d-flex gap-3 align-items-center bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                        <div class="bg-success p-2 rounded-3 text-white">
                            <i class="fa-solid fa-shield-halved fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">100% Secure Platform</h6>
                            <small class="text-white-50">Your data and transactions are protected.</small>
                        </div>
                    </div>
                </div>

                <!-- Footer ផ្នែកខាងក្រោម -->
                <div class="footer-side-text">
                    <p class="m-0 text-white-50">&copy; {{ date('Y') }} QuickCart. All rights reserved.</p>
                </div>
            </div>

            <!-- 🔐 ផ្នែកខាងស្តាំ៖ ទម្រង់ Login Form -->
            <div
                class="col-12 col-lg-6 d-flex align-items-center justify-content-center bg-white p-4 p-sm-5 position-relative">

                <!-- ប៊ូតុងត្រឡប់ទៅ Home -->
                <a href="{{ url('/') }}" class="btn-back-home d-none d-sm-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Home
                </a>

                <div class="login-box-width">
                    <!-- Logo សម្រាប់បង្ហាញលើទូរស័ព្ទ -->
                    <div class="text-center mb-4 d-lg-none">
                        <a href="/" class="brand-link">
                            <i class="fa-solid fa-bag-shopping"></i> Quick<span>Cart</span>
                        </a>
                    </div>

                    <div class="mb-4">
                        <h2 class="fw-bold text-dark mb-2">Welcome Back!</h2>
                        <p class="text-muted">Please sign in to access your account.</p>
                    </div>

                    <!-- បង្ហាញ Error ឱ្យស្អាតជាងមុន -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- 💡 ថែម id="loginForm" នៅត្រង់នេះ -->
                    <form method="POST" action="{{ route('login') }}" class="needs-validation" id="loginForm">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com"
                                    required autofocus>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-semibold text-secondary mb-0">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-decoration-none text-primary fw-medium"
                                        style="font-size: 0.85rem;">Forgot Password?</a>
                                @endif
                            </div>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••"
                                    required>
                            </div>
                        </div>

                        <!-- Remember Me Option -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label text-muted" for="remember_me">
                                Remember me on this device
                            </label>
                        </div>

                        <!-- Submit Button | 💡 ថែម id="submitBtn" នៅត្រង់នេះ -->
                        <button type="submit" id="submitBtn" class="btn btn-gradient-primary w-100 py-3 mb-4">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                        </button>
                    </form>

                    <!-- Link ទៅកាន់ការចុះឈ្មោះ -->
                    <div class="text-center">
                        <span class="text-muted">Don't have an account? </span>
                        <a href="{{ route('register') }}"
                            class="text-success fw-bold text-decoration-none hover-underline">Register Now</a>
                    </div>

                    <!-- ប៊ូតុង Back សម្រាប់ Mobile -->
                    <div class="text-center d-block d-sm-none mt-4">
                        <a href="{{ url('/') }}" class="text-muted text-decoration-none">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Home
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- 🎨 Modern Professional Custom CSS -->
    <style>
        body {
            background-color: #ffffff !important;
        }

        .login-box-width {
            width: 100%;
            max-width: 420px;
        }

        .brand-link-side {
            font-size: 2rem;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .brand-link-side span {
            color: #10b981;
        }

        .brand-link {
            font-size: 2.2rem;
            font-weight: 800;
            color: #4f46e5;
            text-decoration: none;
        }

        .brand-link span {
            color: #10b981;
        }

        .custom-input-group {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .custom-input-group:focus-within {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            background-color: #ffffff;
        }

        .custom-input-group .input-group-text {
            background: transparent;
            border: none;
            padding-left: 16px;
            color: #9ca3af;
        }

        .custom-input-group .form-control {
            border: none !important;
            background: transparent !important;
            padding: 14px 16px 14px 10px;
            font-size: 0.95rem;
            box-shadow: none !important;
            color: #1f2937;
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border: none;
            color: white;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-gradient-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
            color: white;
        }

        .btn-gradient-primary:active {
            transform: translateY(0);
        }

        .btn-back-home {
            position: absolute;
            top: 30px;
            right: 40px;
            padding: 10px 18px;
            background-color: #f3f4f6;
            color: #4b5563;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-back-home:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }

        .backdrop-blur {
            backdrop-filter: blur(8px);
        }

        .fw-extrabold {
            font-weight: 800;
        }

        .hover-underline:hover {
            text-decoration: underline !important;
        }

        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
    </style>

    <!-- 🛠️ ដាក់កូដ JavaScript នៅខាងក្រោមគេបង្អស់ត្រង់នេះ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ដំណើរការ Feather Icons ប្រសិនបើមានប្រើ
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // ដំណើរការ Loading នៅពេលដែលអ្នកប្រើប្រាស់ចុចប៊ូតុង Sign In
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    if (this.checkValidity()) {
                        const btn = document.getElementById('submitBtn');
                        if (btn) {
                            btn.innerHTML =
                                '<i class="fa-solid fa-spinner fa-spin me-2"></i> Please wait...';
                            btn.disabled = true;
                        }
                    }
                });
            }

            // បង្ហាញ SweetAlert Notification ករណីជោគជ័យ
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#0d6efd',
                    timer: 3000
                });
            @endif

            // បង្ហាញ SweetAlert Notification ករណីមានកំហុស (Error Validation)
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
</x-guest-layout>
