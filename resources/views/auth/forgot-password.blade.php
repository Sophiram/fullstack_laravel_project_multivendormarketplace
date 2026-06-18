<x-guest-layout>
    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 min-vh-100">

            <!-- 🌌 ផ្នែកខាងឆ្វេង៖ រូបភាព និង Branding -->
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between p-5 text-white position-relative"
                style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.95), rgba(59, 130, 246, 0.9)), url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1632&auto=format&fit=crop') no-repeat center center; background-size: cover;">

                <!-- Logo ផ្នែកខាងលើ -->
                <div class="brand-wrapper">
                    <a href="/" class="brand-link-side">
                        <i class="fa-solid fa-bag-shopping text-success"></i> Quick<span>Cart</span>
                    </a>
                </div>

                <!-- อត្ថបទផ្សព្វផ្សាយចំកណ្តាល -->
                <div class="my-auto max-w-md animate__animated animate__fadeInLeft">
                    <h1 class="display-5 fw-extrabold mb-3 text-white">Lockout Resolution</h1>
                    <p class="lead text-white-50 mb-4">Don't worry! It happens to the best of us. Just let us know your
                        email address and we will send you a secure link to reset your password.</p>

                    <div
                        class="d-flex gap-3 align-items-center bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur mb-3">
                        <div class="bg-success p-2 rounded-3 text-white">
                            <i class="fa-solid fa-key fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">Secure Reset Link</h6>
                            <small class="text-white-50">We will send an encrypted verification link to your
                                inbox.</small>
                        </div>
                    </div>
                </div>

                <!-- Footer ផ្នែកខាងក្រោម -->
                <div class="footer-side-text">
                    <p class="m-0 text-white-50">&copy; {{ date('Y') }} QuickCart. All rights reserved.</p>
                </div>
            </div>

            <!-- 🔐 ផ្នែកខាងស្តាំ៖ ទម្រង់ Forgot Password Form -->
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
                        <h2 class="fw-bold text-dark mb-2">Forgot Password?</h2>
                        <p class="text-muted">No problem. Enter your email and we'll help you choose a new one.</p>
                    </div>

                    <!-- បង្ហាញ Session Status (សារផ្ញើ Link ជោគជ័យ) -->
                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 p-3"
                            style="background-color: #ecfdf5; color: #065f46;">
                            <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    <!-- បង្ហាញ Error ករណីបញ្ចូលអ៊ីមែលខុស -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- 💡 ថែម id="forgotForm" នៅត្រង់នេះ -->
                    <form method="POST" action="{{ route('password.email') }}" class="needs-validation"
                        id="forgotForm">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com"
                                    value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <!-- Submit Button | 💡 ថែម id="submitBtn" -->
                        <button type="submit" id="submitBtn" class="btn btn-gradient-primary w-100 py-3 mb-4">
                            <i class="fa-regular fa-paper-plane me-2"></i>Email Password Reset Link
                        </button>
                    </form>

                    <!-- Link ត្រឡប់ទៅទំព័រ Login វិញ -->
                    <div class="text-center">
                        <a href="{{ route('login') }}"
                            class="text-primary fw-bold text-decoration-none hover-underline">
                            <i class="fa-solid fa-arrow-left-long me-2"></i>Back to Log in
                        </a>
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

    <!-- 🎨 Modern Professional Custom CSS (ដូចទំព័រមុនៗបេះបិទ) -->
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
    </style>

    <!-- 🛠️ JavaScript សម្រាប់ចាប់ដំណើរការ Loading -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // បង្ហាញ Loading នៅពេលចុចប៊ូតុងផ្ញើ Link ទៅកាន់ Email
            const forgotForm = document.getElementById('forgotForm');
            if (forgotForm) {
                forgotForm.addEventListener('submit', function() {
                    if (this.checkValidity()) {
                        const btn = document.getElementById('submitBtn');
                        if (btn) {
                            btn.innerHTML =
                                '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending link...';
                            btn.disabled = true;
                        }
                    }
                });
            }

            // បង្ហាញ SweetAlert ប្រសិនបើផ្ញើ Link ជោគជ័យ
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Link Sent!',
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#0d6efd'
                });
            @endif

            // បង្ហាញ SweetAlert ករណីមានកំហុស (Error Validation)
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
