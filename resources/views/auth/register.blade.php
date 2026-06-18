<x-guest-layout>
    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 min-vh-100">

            <!-- 🌌 ផ្នែកខាងឆ្វេង៖ រូបភាព និង អត្ថបទផ្សព្វផ្សាយ -->
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between p-5 text-white position-relative"
                style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.95), rgba(59, 130, 246, 0.9)), url('https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=1632&auto=format&fit=crop') no-repeat center center; background-size: cover;">

                <!-- Logo ផ្នែកខាងលើ -->
                <div class="brand-wrapper">
                    <a href="/" class="brand-link-side">
                        <i class="fa-solid fa-bag-shopping text-success"></i> Quick<span>Cart</span>
                    </a>
                </div>

                <!-- អត្ថបទផ្សព្វផ្សាយចំកណ្តាល -->
                <div class="my-auto max-w-md animate__animated animate__fadeInLeft">
                    <h1 class="display-5 fw-extrabold mb-3 text-white">Start Your Journey With Us</h1>
                    <p class="lead text-white-50 mb-4">Create an account to unlock personalized shopping, track your
                        orders, or start selling your premium products today.</p>

                    <div
                        class="d-flex gap-3 align-items-center bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur mb-3">
                        <div class="bg-success p-2 rounded-3 text-white">
                            <i class="fa-solid fa-bolt fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">Fast & Easy Registration</h6>
                            <small class="text-white-50">Set up your profile in less than a minute.</small>
                        </div>
                    </div>
                </div>

                <!-- Footer ផ្នែកខាងក្រោម -->
                <div class="footer-side-text">
                    <p class="m-0 text-white-50">&copy; {{ date('Y') }} QuickCart. All rights reserved.</p>
                </div>
            </div>

            <!-- 📝 ផ្នែកខាងស្តាំ៖ ទម្រង់ Register Form -->
            <div
                class="col-12 col-lg-6 d-flex align-items-center justify-content-center bg-white p-4 p-sm-5 position-relative">

                <!-- ប៊ូតុងត្រឡប់ទៅ Home -->
                <a href="{{ url('/') }}" class="btn-back-home d-none d-sm-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Home
                </a>

                <div class="register-box-width py-4">
                    <!-- Logo សម្រាប់បង្ហាញលើទូរស័ព្ទ -->
                    <div class="text-center mb-4 d-lg-none">
                        <a href="/" class="brand-link">
                            <i class="fa-solid fa-bag-shopping"></i> Quick<span>Cart</span>
                        </a>
                    </div>

                    <div class="mb-4">
                        <h2 class="fw-bold text-dark mb-2">Create Account</h2>
                        <p class="text-muted">Join us today! Create your shopping or vendor account.</p>
                    </div>

                    <!-- បង្ហាញ Error ទិន្នន័យ -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- 💡 បានថែម id="registerForm" នៅត្រង់នេះ -->
                    <form method="POST" action="{{ route('register') }}" class="needs-validation" id="registerForm">
                        @csrf

                        <!-- Full Name Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Full Name</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="John Doe"
                                    required autofocus>
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com"
                                    required>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Password</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••"
                                    required>
                            </div>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Confirm Password</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-solid fa-shield-halved"></i></span>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Register As (Role Select) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Register As</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
                                <select name="role" class="form-control form-select-custom" required>
                                    <option value="user">Customer (អ្នកទិញទំនិញ)</option>
                                    <option value="vendor">Vendor (អ្នកលក់ទំនិញ)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button | 💡 បានថែម id="submitBtn" នៅត្រង់នេះ -->
                        <button type="submit" id="submitBtn" class="btn btn-gradient-primary w-100 py-3 mb-4">
                            <i class="fa-solid fa-user-plus me-2"></i>Register
                        </button>
                    </form>

                    <!-- Link ទៅកាន់ទំព័រ Login វិញ -->
                    <div class="text-center">
                        <span class="text-muted">Already registered? </span>
                        <a href="{{ route('login') }}"
                            class="text-primary fw-bold text-decoration-none hover-underline">Log in</a>
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

        .register-box-width {
            width: 100%;
            max-width: 440px;
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

        .form-select-custom {
            cursor: pointer;
            appearance: none;
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
    </style>

    <!-- 🛠️ ផ្នែក JavaScript សម្រាប់ចាប់ដំណើរការ Loading ពេលចុច Register -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // មុខងារ Loading នៅពេលទម្រង់ Register ត្រូវបាន Submit
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                registerForm.addEventListener('submit', function() {
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

            // SweetAlert សម្រាប់ Success Notification
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#0d6efd',
                    timer: 3000
                });
            @endif

            // SweetAlert សម្រាប់ Error Notification
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
