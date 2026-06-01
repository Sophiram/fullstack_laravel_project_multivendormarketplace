@extends('layouts.user')

@section('home')
    {{-- 🔗 បញ្ចូល Google Fonts និង Animate.css សម្រាប់បង្កើនភាពទាក់ទាញ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <div class="discount-page-wrapper py-5 bg-gradient-light">
        <div class="container mt-4">

            {{-- 🎁 ផ្នែក Header & Title --}}
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <span
                        class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold text-uppercase tracking-wider mb-2 animate__animated animate__fadeInDown"
                        style="font-size: 0.75rem; letter-spacing: 1px;">
                        <i class="fa-solid fa-fire me-1"></i> Mega Savings Hub
                    </span>
                    <h1 class="fw-extrabold text-dark display-5 page-main-title animate__animated animate__fadeIn">Exclusive
                        Deals & Offers</h1>
                    <p class="text-muted mx-auto sub-title-text" style="max-width: 580px;">
                        Unlock massive savings with our verified store-wide discount codes and limited-time promotional
                        vouchers.
                    </p>
                </div>
            </div>

            {{-- 🎫 ផ្នែកបង្ហាញប័ណ្ណបញ្ចុះតម្លៃ (Active Discount Coupons Grid) --}}
            <div class="row g-4 mb-5">
                @forelse ($discounts as $discount)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div
                            class="coupon-premium-card position-relative overflow-hidden h-100 d-flex flex-column justify-content-between">
                            {{-- រង្វង់កាត់សងខាងបែបសំបុត្រកុន (Ticket Punch Hole Effect) --}}
                            <div class="punch-hole hole-left"></div>
                            <div class="punch-hole hole-right"></div>

                            {{-- ផ្នែកខាងលើនៃ Coupon --}}
                            <div class="p-4 border-dashed-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="discount-badge-value">
                                        <span class="display-6 fw-black text-primary">
                                            {{ number_format($discount->value, 0) }}{{ $discount->type == 'percentage' ? '%' : '$' }}
                                        </span>
                                        <span class="d-block small text-muted fw-bold text-uppercase">OFF</span>
                                    </div>
                                    <span
                                        class="badge {{ $discount->status == 1 ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-2.5 py-1.5 rounded-2 small fw-semibold">
                                        {{ $discount->status == 1 ? 'Verified Active' : 'Expired' }}
                                    </span>
                                </div>

                                <h5 class="fw-bold text-dark mb-1 coupon-title">{{ $discount->title }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="fa-solid fa-basket-shopping me-1"></i> Min. Spend:
                                    <span
                                        class="fw-semibold text-dark">${{ number_format($discount->min_requirement ?? 0, 2) }}</span>
                                </p>
                            </div>

                            {{-- ផ្នែកខាងក្រោមនៃ Coupon (កន្លែង Copy Code) --}}
                            <div class="p-4 bg-light-subtle d-flex flex-column gap-3">
                                <div
                                    class="d-flex align-items-center justify-content-between bg-white border rounded-3 p-2 shadow-inner">
                                    <span class="font-monospace fw-bold text-uppercase text-primary ps-2 tracking-wide"
                                        style="font-size: 1.05rem;">
                                        {{ $discount->code }}
                                    </span>
                                    <button
                                        class="btn btn-primary btn-sm rounded-2 px-3 fw-semibold copy-btn d-inline-flex align-items-center gap-1.5"
                                        onclick="copyCouponCode('{{ $discount->code }}', this)">
                                        <i class="fa-regular fa-copy"></i> <span>Copy</span>
                                    </button>
                                </div>

                                <div class="d-flex align-items-center justify-content-between text-muted"
                                    style="font-size: 0.78rem;">
                                    <span><i class="fa-regular fa-calendar-check me-1"></i> Starts:
                                        {{ \Carbon\Carbon::parse($discount->start_date)->format('M d, Y') }}</span>
                                    <span class="fw-medium text-danger">
                                        <i class="fa-regular fa-clock me-1"></i> Ends:
                                        {{ $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('M d, Y') : 'Lifetime' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 animate__animated animate__bounceIn"
                            style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-ticket-simple text-muted fs-2"></i>
                        </div>
                        <h5 class="text-muted fw-bold">No Active Coupons Available</h5>
                        <p class="text-muted small">Check back soon for upcoming holiday promotions and updates!</p>
                    </div>
                @endforelse
            </div>

            <hr class="border-light my-5">

            {{-- 🛍️ ផ្នែក Livewire Product Filter (ទាញយកផលិតផលមកបង្ហាញបន្ត) --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h3 class="fw-bold text-dark mb-0 font-space">Recommended For You</h3>
                        <span class="badge bg-primary rounded-pill px-2.5 py-1 small">Trending Now</span>
                    </div>
                    <p class="text-muted small">Apply your copied coupon codes at checkout on these hot items.</p>
                </div>
            </div>

            <div>
                @livewire('home-product-filter-component')
            </div>

        </div>
    </div>
@endsection

<style>
    /* -----------------------------------------
       🎨 PREMIUM MODERN COUPON & DISCOUNT STYLES
    -------------------------------------------- */
    .discount-page-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
    }

    .font-space {
        font-family: 'Space Grotesk', sans-serif;
    }

    .fw-black {
        font-weight: 800;
    }

    .fw-extrabold {
        font-weight: 750;
    }

    .bg-gradient-light {
        background: radial-gradient(circle at top right, #fdf8f6 0%, #f8fafc 100%);
    }

    .page-main-title {
        font-family: 'Space Grotesk', sans-serif;
        letter-spacing: -1.5px;
        background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* 🎫 TICKET COUPON CARD STRUCTURAL DESIGN */
    .coupon-premium-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .coupon-premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(67, 56, 202, 0.12);
        border-color: rgba(99, 102, 241, 0.3);
    }

    /* ✂️ Border Dashed Divider */
    .border-dashed-bottom {
        border-bottom: 2px dashed #e2e8f0;
        position: relative;
    }

    /* 🕳️ Ticket Punch Holes */
    .punch-hole {
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: #f8fafc;
        /* ត្រូវរត់ស៊ីគ្នាជាមួយពណ៌ Background ខាងក្រៅ */
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .hole-left {
        left: -11px;
        border-left: transparent;
    }

    .hole-right {
        right: -11px;
        border-right: transparent;
    }

    .discount-badge-value {
        line-height: 1;
    }

    .coupon-title {
        font-size: 1.15rem;
        letter-spacing: -0.3px;
    }

    .shadow-inner {
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.03);
    }

    /* 📱 Responsive Adjustment */
    @media (max-width: 575.98px) {
        .page-main-title {
            font-size: 2rem;
        }

        .coupon-premium-card {
            border-radius: 20px;
        }
    }
</style>

{{-- 🔔 SweetAlert2 & Copy Clipboard Logic --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyCouponCode(code, button) {
        // 📋 ប្រព័ន្ធ Copy ទៅកាន់ Clipboard
        navigator.clipboard.writeText(code).then(() => {
            // ប្តូរអក្សរលើប៊ូតុងបណ្តោះអាសន្ន
            const originalText = button.innerHTML;
            button.innerHTML = `<i class="fa-solid fa-check"></i> <span>Copied!</span>`;
            button.classList.remove('btn-primary');
            button.classList.add('btn-success');

            // 🔔 បង្ហាញ Toast Notification ផ្អែមល្ហែម
            Swal.fire({
                title: 'Code Copied Successfully!',
                text: `Use promo code "${code}" at checkout.`,
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1e293b',
                iconColor: '#10b981'
            });

            // រំលាយប៊ូតុងមកសភាពដើមវិញក្រោយ 2 វិនាទី
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }
</script>
