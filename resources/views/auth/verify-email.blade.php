<x-guest-layout>
    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 min-vh-100">

            <!-- 🌌 ផ្នែកខាងឆ្វេង៖ រូបភាព និង Branding -->
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between p-5 text-white position-relative"
                style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.95), rgba(59, 130, 246, 0.9)), url('https://images.unsplash.com/photo-1596524430615-b46475ddff6e?q=80&w=1632&auto=format&fit=crop') no-repeat center center; background-size: cover;">

                <div class="brand-wrapper">
                    <a href="/" class="brand-link-side">
                        <i class="fa-solid fa-bag-shopping text-success"></i> Quick<span>Cart</span>
                    </a>
                </div>

                <div class="my-auto max-w-md animate__animated animate__fadeInLeft">
                    <h1 class="display-5 fw-extrabold mb-3 text-white">Verify Your Email</h1>
                    <p class="lead text-white-50 mb-4">Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to you.</p>
                </div>

                <div class="footer-side-text">
                    <p class="m-0 text-white-50">&copy; {{ date('Y') }} QuickCart. All rights reserved.</p>
                </div>
            </div>

            <!-- ✉️ ផ្នែកខាងស្តាំ៖ ទម្រង់ Verify Content -->
            <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center bg-white p-4 p-sm-5 position-relative">

                <div class="login-box-width">
                    <div class="text-center mb-4 d-lg-none">
                        <a href="/" class="brand-link">
                            <i class="fa-solid fa-bag-shopping"></i> Quick<span>Cart</span>
                        </a>
                    </div>

                    <div class="text-center mb-4">
                        <div class="bg-light-primary text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-regular fa-envelope-open fa-2x"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-2">Check Your Mail</h2>
                        <p class="text-muted">We've sent a verification link to your email address. If you didn't receive it, we can gladly send you another.</p>
                    </div>

                    <!-- ស្ថានភាពផ្ញើ Link ជោគជ័យ -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 text-sm" style="background-color: #ecfdf5; color: #065f46;">
                            <i class="fa-solid fa-circle-check me-2"></i> {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <div class="d-flex flex-column gap-2">
                        <!-- Form ផ្ញើ Email សារជាថ្មី -->
                        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                            @csrf
                            <button type="submit" id="resendBtn" class="btn btn-gradient-primary w-100 py-3">
                                <i class="fa-solid fa-paper-plane me-2"></i>Resend Verification Email
                            </button>
                        </form>

                        <!-- Form ចាកចេញ (Log Out) -->
                        <form method="POST" action="{{ route('logout') }}" class="text-center mt-2">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted text-decoration-none hover-underline text-sm fw-medium">
                                <i class="fa-solid fa-right-from-bracket me-1"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- 🎨 Custom CSS -->
    <style>
        body { background-color: #ffffff !important; }
        .login-box-width { width: 100%; max-width: 440px; }
        .brand-link-side { font-size: 2rem; font-weight: 800; color: #ffffff; text-decoration: none; letter-spacing: -0.5px; }
        .brand-link-side span { color: #10b981; }
        .brand-link { font-size: 2.2rem; font-weight: 800; color: #4f46e5; text-decoration: none; }
        .brand-link span { color: #10b981; }
        .bg-light-primary { background-color: rgba(79, 70, 229, 0.1); }
        .text-primary { color: #4f46e5 !important; }
        .btn-gradient-primary { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none; color: white; border-radius: 14px; font-weight: 600; font-size: 1rem; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); }
        .btn-gradient-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35); color: white; }
        .fw-extrabold { font-weight: 800; }
        .hover-underline:hover { text-decoration: underline !important; }
    </style>

    <!-- 🛠️ JavaScript Loading -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resendForm = document.getElementById('resendForm');
            if (resendForm) {
                resendForm.addEventListener('submit', function() {
                    const btn = document.getElementById('resendBtn');
                    if (btn) {
                        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending...';
                        btn.disabled = true;
                    }
                });
            }
        });
    </script>
</x-guest-layout>
