@extends('user.layouts.layout')

@section('user_page_title', 'Affiliate - User Panel')

@section('user_layout')
    <div class="container-fluid px-2 px-md-4 py-3">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                    <i data-lucide="users" class="me-2 text-primary"></i>
                    Affiliate Program
                </h3>
                <p class="text-muted small mb-0">Invite your friends and earn commissions on every successful sale.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Affiliate Banner & Link Section -->
            <div class="col-12">
                <div class="card p-4 p-md-5 shadow-sm border-0 position-relative overflow-hidden"
                    style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);">

                    <h5 class="fw-bold text-dark mb-2 position-relative" style="z-index: 2;">Welcome to your Affiliate
                        Dashboard</h5>
                    <p class="text-secondary mb-4 position-relative" style="font-size: 15px; max-width: 650px; z-index: 2;">
                        Share your unique referral link with friends, family, or on your social media channels to start
                        earning competitive commissions.
                    </p>

                    <!-- Link & Action Buttons Container -->
                    <div class="p-3 bg-white shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative"
                        style="border: 1px solid #e2e8f0; border-radius: 14px; z-index: 2;">

                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <div class="p-2 rounded-3 bg-primary-subtle text-primary d-none d-sm-flex">
                                <i data-lucide="link" style="width: 20px; height: 20px;"></i>
                            </div>
                            <span id="referralLink" class="fw-semibold text-slate-700 text-truncate"
                                style="font-size: 15px; color: #334155;">
                                {{ url('/ref/' . Auth::user()->id) }}
                            </span>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 w-100" style="max-width: 300px;">
                            <button id="btnCopy" onclick="copyLink()"
                                class="btn btn-primary flex-grow-1 px-3 py-2 d-flex align-items-center justify-content-center gap-2 fw-medium transition-all"
                                style="border-radius: 10px;">
                                <i data-lucide="copy" style="width: 16px; height: 16px;"></i>
                                <span id="btnText">Copy</span>
                            </button>

                            <button id="btnShare" onclick="shareLink()"
                                class="btn btn-outline-success flex-grow-1 px-3 py-2 d-flex align-items-center justify-content-center gap-2 fw-medium transition-all"
                                style="border-radius: 10px; background-color: #f0f9ff; border-color: #bae6fd; color: #0369a1;">
                                <i data-lucide="share-2" style="width: 16px; height: 16px;"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>

                    <!-- Decorative Background Icon -->
                    <div class="position-absolute end-0 top-50 translate-middle-y text-primary opacity-10 d-none d-lg-block pe-4"
                        style="z-index: 1;">
                        <i data-lucide="network" style="width: 160px; height: 160px; stroke-width: 1;"></i>
                    </div>
                </div>
            </div>

            <!-- Steps Section -->
            <div class="col-12">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card p-4 border-0 shadow-sm card-hover h-100"
                            style="border-radius: 18px; background: #ffffff;">
                            <div class="p-3 rounded-3 bg-info-subtle text-info mb-3 d-inline-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i data-lucide="send" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">1. Share Your Link</h6>
                            <p class="text-muted small mb-0" style="line-height: 1.6;">Copy your link and post it on your
                                blog, social media
                                platforms, or send directly via message.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4 border-0 shadow-sm card-hover h-100"
                            style="border-radius: 18px; background: #ffffff;">
                            <div class="p-3 rounded-3 bg-warning-subtle text-warning mb-3 d-inline-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i data-lucide="user-plus" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">2. Friends Register</h6>
                            <p class="text-muted small mb-0" style="line-height: 1.6;">When someone signs up or completes a
                                purchase using your unique
                                link, they get tracked instantly.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4 border-0 shadow-sm card-hover h-100"
                            style="border-radius: 18px; background: #ffffff;">
                            <div class="p-3 rounded-3 bg-success-subtle text-success mb-3 d-inline-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i data-lucide="banknote" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">3. Earn Commission</h6>
                            <p class="text-muted small mb-0" style="line-height: 1.6;">Receive a percentage payout of the
                                generated income straight
                                into your wallet system.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Card Hover Effects */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1) !important;
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        @media (max-width: 767.98px) {
            .w-100 {
                max-width: 100% !important;
            }
        }
    </style>

    <script>
        // Re-init lucide icons
        lucide.createIcons();

        function copyLink() {
            // ទាញយកអត្ថបទពី Link Span
            const linkText = document.getElementById('referralLink').innerText.trim();
            const btnCopy = document.getElementById('btnCopy');
            const originalHTML =
                '<i data-lucide="copy" style="width: 16px; height: 16px;"></i><span id="btnText">Copy</span>';

            // មុខងារចម្លង (Copy to Clipboard)
            navigator.clipboard.writeText(linkText).then(() => {
                // កែប្រែ UI ជាបណ្តោះអាសន្នពេល Copy រួចរាល់
                btnCopy.innerHTML =
                    '<i data-lucide="check" style="width: 16px; height: 16px;"></i><span id="btnText">Copied!</span>';
                btnCopy.classList.remove('btn-primary');
                btnCopy.classList.add('btn-success');
                lucide.createIcons(); // Render the new check icon

                // កំណត់ឱ្យត្រឡប់មកស្ថានភាពដើមវិញក្រោយ ២ វិនាទី
                setTimeout(() => {
                    btnCopy.innerHTML = originalHTML;
                    btnCopy.classList.remove('btn-success');
                    btnCopy.classList.add('btn-primary');
                    lucide.createIcons();
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        function shareLink() {
            // ទាញយកអត្ថបទពី Link Span
            const linkUrl = document.getElementById('referralLink').innerText.trim();

            // ប្រើប្រាស់ Web Share API សម្រាប់ការចែករំលែកដោយផ្ទាល់
            if (navigator.share) {
                navigator.share({
                    title: 'Join our platform!',
                    text: 'Register using my referral link to get started:',
                    url: linkUrl
                }).catch(err => {
                    console.log('Error sharing:', err);
                });
            } else {
                // បើសិនជា Browser មិនគាំទ្រ Web Share API (ឧ. Desktop ខ្លះ)
                alert('Sharing is not supported on this browser. Please copy the link manually.');
            }
        }
    </script>
@endsection
