<x-guest-layout>
    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 min-vh-100">

            <!-- 🌌 ផ្នែកខាងឆ្វេង៖ រូបភាព និង Branding -->
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between p-5 text-white position-relative"
                style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.95), rgba(59, 130, 246, 0.9)), url('https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=1632&auto=format&fit=crop') no-repeat center center; background-size: cover;">

                <div class="brand-wrapper">
                    <a href="/" class="brand-link-side">
                        <i class="fa-solid fa-bag-shopping text-success"></i> Quick<span>Cart</span>
                    </a>
                </div>

                <div class="my-auto max-w-md animate__animated animate__fadeInLeft">
                    <h1 class="display-5 fw-extrabold mb-3 text-white">Security First</h1>
                    <p class="lead text-white-50 mb-4">This is a secure area of the application. Please confirm your password before continuing to protect your account data.</p>
                    <div class="d-flex gap-3 align-items-center bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                        <div class="bg-success p-2 rounded-3 text-white">
                            <i class="fa-solid fa-user-shield fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">Identity Verification</h6>
                            <small class="text-white-50">Ensuring that only you can access this section.</small>
                        </div>
                    </div>
                </div>

                <div class="footer-side-text">
                    <p class="m-0 text-white-50">&copy; {{ date('Y') }} QuickCart. All rights reserved.</p>
                </div>
            </div>

            <!-- 🔐 ផ្នែកខាងស្តាំ៖ ទម្រង់ Confirm Form -->
            <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center bg-white p-4 p-sm-5 position-relative">

                <div class="login-box-width">
                    <div class="text-center mb-4 d-lg-none">
                        <a href="/" class="brand-link">
                            <i class="fa-solid fa-bag-shopping"></i> Quick<span>Cart</span>
                        </a>
                    </div>

                    <div class="mb-4">
                        <h2 class="fw-bold text-dark mb-2">Confirm Password</h2>
                        <p class="text-muted">Please confirm your password before continuing.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.confirm') }}" id="confirmForm">
                        @csrf

                        <!-- Password Input -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Password</label>
                            <div class="input-group custom-input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password" autofocus>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn" class="btn btn-gradient-primary w-100 py-3 mb-4">
                            <i class="fa-solid fa-shield me-2"></i>Confirm
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- 🎨 Custom CSS -->
    <style>
        body { background-color: #ffffff !important; }
        .login-box-width { width: 100%; max-width: 420px; }
        .brand-link-side { font-size: 2rem; font-weight: 800; color: #ffffff; text-decoration: none; letter-spacing: -0.5px; }
        .brand-link-side span { color: #10b981; }
        .brand-link { font-size: 2.2rem; font-weight: 800; color: #4f46e5; text-decoration: none; }
        .brand-link span { color: #10b981; }
        .custom-input-group { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; transition: all 0.2s ease; overflow: hidden; }
        .custom-input-group:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12); background-color: #ffffff; }
        .custom-input-group .input-group-text { background: transparent; border: none; padding-left: 16px; color: #9ca3af; }
        .custom-input-group .form-control { border: none !important; background: transparent !important; padding: 14px 16px 14px 10px; font-size: 0.95rem; box-shadow: none !important; color: #1f2937; }
        .btn-gradient-primary { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none; color: white; border-radius: 14px; font-weight: 600; font-size: 1rem; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); }
        .btn-gradient-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35); color: white; }
        .backdrop-blur { backdrop-filter: blur(8px); }
        .fw-extrabold { font-weight: 800; }
    </style>

    <!-- 🛠️ JavaScript Loading -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const confirmForm = document.getElementById('confirmForm');
            if (confirmForm) {
                confirmForm.addEventListener('submit', function() {
                    if (this.checkValidity()) {
                        const btn = document.getElementById('submitBtn');
                        if (btn) {
                            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Confirming...';
                            btn.disabled = true;
                        }
                    }
                });
            }
        });
    </script>
</x-guest-layout>
