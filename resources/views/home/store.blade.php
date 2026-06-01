@extends('layouts.user')

@section('home')
    {{-- 🔗 បញ្ចូល Google Fonts, Animate.css និង Bootstrap Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="store-page-wrapper py-5 bg-gradient-light">
        <div class="container mt-4">

            {{-- 🏢 ផ្នែក Header & Title --}}
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <span
                        class="badge bg-success-subtle text-success-dominant px-3 py-2 rounded-pill fw-bold text-uppercase tracking-wider mb-2 animate__animated animate__fadeInDown"
                        style="font-size: 0.75rem; letter-spacing: 1px;">
                        <i class="bi bi-shop me-1"></i> Our Verified Partner Stores
                    </span>
                    <h1 class="fw-extrabold text-dark display-5 page-main-title animate__animated animate__fadeIn">Discover
                        Our Stores</h1>
                    <p class="text-muted mx-auto sub-title-text" style="max-width: 580px;">
                        Explore our network of trusted premium partner stores and vendors providing top-tier products near
                        you.
                    </p>
                </div>
            </div>

            {{-- 🗺️ ផ្នែកបង្ហាញបញ្ជីហាង (Grid Layout) --}}
            <div class="row g-4 mb-5">
                @forelse ($stores as $store)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div
                            class="store-premium-card h-100 d-flex flex-column justify-content-between overflow-hidden animate__animated animate__fadeInUp">

                            {{-- 🖼️ ផ្នែករូបភាព/ឡូហ្គោហាង --}}
                            <div
                                class="position-relative store-image-wrapper d-flex align-items-center justify-content-center p-4">
                                <img src="{{ !empty($store->logo) ? asset('storage/' . $store->logo) : 'https://placehold.co/600x400?text=' . urlencode($store->store_name) }}"
                                    alt="{{ $store->store_name }}"
                                    class="w-100 h-100 object-fit-contain transition-transform">

                                <span
                                    class="position-absolute top-0 start-0 m-3 badge bg-dark bg-opacity-75 backdrop-blur px-3 py-2 rounded-2 fw-semibold shadow-sm small">
                                    <i class="bi bi-patch-check-fill text-info me-1"></i> Verified Store
                                </span>
                            </div>

                            {{-- 📝 ផ្នែកព័ត៌មានលម្អិតរបស់ហាង (Card Body) --}}
                            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                                <div class="mb-4">
                                    <h5 class="fw-bold text-dark mb-2 store-title text-truncate">{{ $store->store_name }}
                                    </h5>
                                    <p class="text-muted small line-clamp-2 mb-3">{{ $store->details }}</p>

                                    <div class="d-flex flex-column gap-2.5 text-muted small">
                                        {{-- អាសយដ្ឋាន --}}
                                        @if ($store->address)
                                            <div class="d-flex align-items-start gap-2">
                                                <i class="bi bi-geo-alt text-secondary mt-0.5 fs-6"></i>
                                                <span class="lh-sm line-clamp-2">{{ $store->address }}</span>
                                            </div>
                                        @endif

                                        {{-- លេខទូរស័ព្ទ --}}
                                        @if ($store->store_phone)
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-telephone text-secondary fs-6"></i>
                                                <a href="tel:{{ $store->store_phone }}"
                                                    class="text-decoration-none text-muted hover-primary">{{ $store->store_phone }}</a>
                                            </div>
                                        @endif

                                        {{-- អ៊ីមែល --}}
                                        @if ($store->store_email)
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-envelope text-secondary fs-6"></i>
                                                <a href="mailto:{{ $store->store_email }}"
                                                    class="text-decoration-none text-muted hover-primary text-truncate">{{ $store->store_email }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- ប៊ូតុងសកម្មភាពសម្រាប់ចូលមើលហាង --}}
                                <div class="pt-3 border-top">
                                    <a href="{{ route('home.store.details', $store->slug) }}"
                                        class="btn btn-outline-success w-100 rounded-3 py-2 fw-bold d-inline-flex align-items-center justify-content-center gap-2 store-action-btn">
                                        <i class="bi bi-bag-dash"></i>
                                        <span>Visit Store Shop</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    {{-- ករណីមិនទាន់មានទិន្នន័យហាង --}}
                    <div class="col-12 text-center py-5">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-shop text-muted fs-2"></i>
                        </div>
                        <h5 class="text-muted fw-bold">No Stores Available</h5>
                        <p class="text-muted small">Currently, there are no approved active stores on our platform.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection

<style>
    /* -----------------------------------------
       🎨 PREMIUM MODERN STORE PAGE STYLES
    -------------------------------------------- */
    .store-page-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
    }

    .fw-extrabold {
        font-weight: 750;
    }

    .bg-gradient-light {
        background: radial-gradient(circle at top right, #f0fdf4 0%, #f8fafc 100%);
    }

    .text-success-dominant {
        color: #16a34a !important;
    }

    .backdrop-blur {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .page-main-title {
        font-family: 'Space Grotesk', sans-serif;
        letter-spacing: -1.5px;
        background: linear-gradient(135deg, #1e1b4b 0%, #16a34a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* 🏪 PREMIUM STORE CARD DESIGN */
    .store-premium-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .store-premium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(22, 163, 74, 0.12);
        border-color: rgba(34, 197, 94, 0.4);
    }

    /* 🖼️ Image Zoom Effect */
    .store-image-wrapper {
        height: 200px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .store-premium-card:hover .store-image-wrapper img {
        transform: scale(1.05);
    }

    .transition-transform {
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .store-title {
        font-size: 1.25rem;
        letter-spacing: -0.3px;
    }

    .hover-primary:hover {
        color: #16a34a !important;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* 🛠️ Outline Button Hover Styling */
    .store-action-btn {
        border-color: #16a34a;
        color: #16a34a;
        transition: all 0.2s ease;
    }

    .store-action-btn:hover {
        background-color: #16a34a;
        border-color: #16a34a;
        color: #ffffff;
    }

    @media (max-width: 575.98px) {
        .page-main-title {
            font-size: 2.2rem;
        }

        .store-image-wrapper {
            height: 180px;
        }
    }
</style>
