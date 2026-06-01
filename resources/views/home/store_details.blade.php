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

    <div class="store-details-wrapper py-5 bg-gradient-light">
        <div class="container mt-4">

            {{-- 🔙 ប៊ូតុងត្រឡប់ក្រោយ --}}
            <div class="mb-4 animate__animated animate__fadeIn">
                <a href="{{ route('home.stores') }}"
                    class="btn btn-light border rounded-3 px-3 py-2 text-secondary btn-sm back-to-stores-btn">
                    <i class="bi bi-arrow-left me-1"></i> Back to All Stores
                </a>
            </div>

            {{-- 🏪 ផ្ទាំងព័ត៌មានលម្អិតរបស់ហាង (Store Hero Profile) --}}
            <div
                class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 store-hero-card animate__animated animate__fadeInUp">
                <div class="row align-items-center g-4">
                    {{-- 🖼️ ឡូហ្គោហាង --}}
                    <div class="col-12 col-md-3 text-center text-md-start">
                        <div
                            class="store-profile-logo-wrapper shadow-sm mx-auto mx-md-0 d-flex align-items-center justify-content-center p-3 bg-white rounded-4">
                            <img src="{{ !empty($store->logo) ? asset('storage/' . $store->logo) : 'https://placehold.co/400x400?text=' . urlencode($store->store_name) }}"
                                alt="{{ $store->store_name }}" class="img-fluid object-fit-contain w-100 h-100">
                        </div>
                    </div>

                    {{-- 📝 ព័ត៌មានទំនាក់ទំនង និងការពិពណ៌នា --}}
                    <div class="col-12 col-md-9 border-start-md ps-md-4">
                        <div
                            class="d-flex flex-wrap align-items-center gap-2 mb-2 justify-content-center justify-content-md-start">
                            <h2 class="fw-extrabold text-dark mb-0 store-main-name">{{ $store->store_name }}</h2>
                            <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill small fw-semibold">
                                <i class="bi bi-patch-check-fill me-1"></i> Verified Partner
                            </span>
                        </div>

                        <p class="text-muted mb-4 text-center text-md-start store-desc-text">{{ $store->details }}</p>

                        {{-- Grid ព័ត៌មានទំនាក់ទំនង --}}
                        <div class="row g-3 text-muted small store-meta-grid">
                            @if ($store->address)
                                <div class="col-12 col-sm-6 d-flex align-items-start gap-2">
                                    <i class="bi bi-geo-alt-fill text-success fs-6"></i>
                                    <span>{{ $store->address }}</span>
                                </div>
                            @endif
                            @if ($store->store_phone)
                                <div class="col-12 col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bi bi-telephone-fill text-success fs-6"></i>
                                    <a href="tel:{{ $store->store_phone }}"
                                        class="text-decoration-none text-muted hover-success">{{ $store->store_phone }}</a>
                                </div>
                            @endif
                            @if ($store->store_email)
                                <div class="col-12 col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bi bi-envelope-fill text-success fs-6"></i>
                                    <a href="mailto:{{ $store->store_email }}"
                                        class="text-decoration-none text-muted hover-success text-truncate">{{ $store->store_email }}</a>
                                </div>
                            @endif
                            <div class="col-12 col-sm-6 d-flex align-items-center gap-2">
                                <i class="bi bi-percent text-success fs-6"></i>
                                <span>Platform Commission: <span
                                        class="fw-bold text-dark">{{ number_format($store->commission_rate, 0) }}%</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 📦 ផ្នែកបង្ហាញផលិតផលរបស់ហាង (Store Products Section) --}}
            <div class="products-section animate__animated animate__fadeInUp animate__delay-1s">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Available Products</h4>
                        <p class="text-muted small mb-0">Browse and shop items directly listed by {{ $store->store_name }}.
                        </p>
                    </div>
                    <span class="badge bg-dark rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.85rem;">
                        Total: {{ $store->products->count() }} Items
                    </span>
                </div>

                {{-- 🛍️ Grid បង្ហាញផលិតផល --}}
                <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 g-sm-4">
                    @forelse($store->products as $product)
                        <div class="col d-flex align-items-stretch">
                            <div
                                class="card w-100 border-0 custom-product-card position-relative overflow-hidden d-flex flex-column">

                                <!-- 📸 Product Image Container (មានរូបភាព និងភ្ជាប់ Link ទៅកាន់ទំព័រលម្អិត) -->
                                <div class="position-relative product-img-container">
                                    <a href="{{ route('product.details', ['productId' => $product->id]) }}"
                                        class="d-block w-100 h-100">
                                        {{-- ទាញយករូបភាពទីមួយពី Database បើគ្មានទេ បង្ហាញរូបភាពលំនាំដើម --}}
                                        <img src="{{ $product->images && $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://placehold.co/300x300?text=No+Image' }}"
                                            class="card-img-top product-main-img" alt="{{ $product->product_name }}">
                                    </a>

                                    {{-- បង្ហាញ Badge ស្ថានភាពផលិតផលនៅលើរូបភាព --}}
                                    @if ($product->stock_status == 'outofstock' || $product->stock_quantity <= 0)
                                        <span
                                            class="position-absolute top-0 start-0 m-2 m-md-3 badge bg-secondary rounded-2 small fw-bold text-white"
                                            style="z-index: 10;">Out of Stock</span>
                                    @elseif($product->discounted_price)
                                        <span
                                            class="position-absolute top-0 start-0 m-2 m-md-3 badge bg-danger rounded-2 small fw-bold text-white"
                                            style="z-index: 10;">Sale</span>
                                    @endif

                                    <span class="position-absolute top-0 end-0 m-2 m-md-3 px-2 py-1 vendor-premium-badge"
                                        style="z-index: 10;">
                                        <i class="bi bi-patch-check-fill text-primary"></i> Verified
                                    </span>
                                </div>

                                <!-- Card Body -->
                                <div
                                    class="card-body p-2.5 p-md-3.5 d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        {{-- 🔗 ភ្ជាប់ Link ទៅកាន់ទំព័រលម្អិតផលិតផលត្រង់ចំណងជើងផងដែរ --}}
                                        <h5 class="product-title mb-1" title="{{ $product->product_name }}">
                                            <a href="{{ route('product.details', ['productId' => $product->id]) }}"
                                                class="text-dark text-decoration-none hover-primary">
                                                {{ $product->product_name }}
                                            </a>
                                        </h5>
                                        <p class="product-short-desc mb-2 mb-md-3">
                                            {{ $product->description ?? 'No description available for this premium item.' }}
                                        </p>
                                    </div>

                                    <div class="mt-auto">
                                        <div
                                            class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-2 mb-md-3">
                                            <div class="price-section">
                                                <span class="price-label"
                                                    style="font-size: 0.75rem; color: #94a3b8; display: block;">Price</span>
                                                <div class="d-flex align-items-baseline gap-1">
                                                    <span class="current-price"
                                                        style="font-size: 1.2rem; font-weight: 800; color: #ef4444;">
                                                        ${{ number_format($product->discounted_price ?? $product->regular_price, 2) }}
                                                    </span>
                                                    @if ($product->discounted_price)
                                                        <span class="old-price"
                                                            style="font-size: 0.825rem; color: #94a3b8; text-decoration: line-through;">
                                                            ${{ number_format($product->regular_price, 2) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="stock-status-badge {{ $product->stock_status == 'outofstock' ? 'bg-danger-subtle text-danger' : '' }}"
                                                style="font-size: 0.75rem; color: #10b981; font-weight: 600; background: #ecfdf5; padding: 4px 10px; border-radius: 6px;">
                                                <i class="bi bi-box small d-none d-sm-inline"></i>
                                                {{ $product->stock_status ?? 'In Stock' }}
                                            </div>
                                        </div>

                                        <!-- ✨ ផ្នែកប្រអប់លេខបង្កើនបន្ថយ (Quantity Stepper) និងប៊ូតុងទិញ -->
                                        <div x-data Bled="{ quantity: 1, maxStock: {{ $product->stock_quantity ?? 99 }} }"
                                            class="mt-3 px-1 pb-2">
                                            <div class="responsive-cart-container">

                                                <!-- 🔢 Quantity Stepper -->
                                                <div class="custom-qty-stepper">
                                                    <button type="button" @click="if(quantity > 1) quantity--"
                                                        class="qty-control-btn">-</button>
                                                    <input type="number" x-model.number="quantity"
                                                        @input="if(quantity < 1) quantity = 1; if(quantity > maxStock) quantity = maxStock;"
                                                        min="1" class="qty-modern-input" aria-label="Quantity">
                                                    <button type="button" @click="if(quantity < maxStock) quantity++"
                                                        class="qty-control-btn">+</button>
                                                </div>

                                                <!-- 🛒 Premium Add to Cart Button -->
                                                <button type="button"
                                                    @click="$dispatch('notify', { title: 'Successfully added ' + quantity + ' item(s) to cart!', type: 'success' })"
                                                    class="btn-premium-inline-cart"
                                                    {{ $product->stock_status == 'outofstock' ? 'disabled' : '' }}>
                                                    <i class="bi bi-basket-fill"></i>
                                                    <span>Add to Cart</span>
                                                </button>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        {{-- ករណីហាងនេះមិនទាន់មានផលិតផលលក់ --}}
                        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border border-light">
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-box-seam text-muted fs-3"></i>
                            </div>
                            <h6 class="text-muted fw-bold">No Products Listed Yet</h6>
                            <p class="text-muted small mb-0" style="max-width: 400px; margin: 0 auto;">This store hasn't
                                uploaded any items to our shop catalog yet. Check back later!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

<style>
    /* -----------------------------------------
       🎨 PREMIUM STORE DETAILS DESIGN SYSTEM
    -------------------------------------------- */
    .store-details-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
    }

    .fw-extrabold {
        font-weight: 800;
    }

    .bg-gradient-light {
        background: radial-gradient(circle at top right, #f4fbf7 0%, #f8fafc 100%);
    }

    .store-main-name {
        font-family: 'Space Grotesk', sans-serif;
        letter-spacing: -1px;
    }

    /* 🏪 HERO CARD DESIGN */
    .store-hero-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .store-profile-logo-wrapper {
        width: 160px;
        height: 160px;
        border: 1px solid #f1f5f9;
    }

    .hover-success:hover {
        color: #16a34a !important;
    }

    .back-to-stores-btn {
        transition: all 0.2s ease;
    }

    .back-to-stores-btn:hover {
        background-color: #f1f5f9;
        color: #111827 !important;
        transform: translateX(-3px);
    }

    /* Responsive Borders */
    @media (min-width: 768px) {
        .border-start-md {
            border-left: 1px solid #e2e8f0 !important;
        }
    }

    @media (max-width: 767.98px) {
        .store-profile-logo-wrapper {
            width: 130px;
            height: 130px;
        }

        .store-main-name {
            font-size: 1.6rem;
        }
    }

    /* -----------------------------------------
       🛍️ MODERN PRODUCT PREMIUM CARD DESIGN
    -------------------------------------------- */
    .responsive-cart-container {
        display: flex;
        gap: 8px;
        align-items: center;
        width: 100%;
    }

    .custom-qty-stepper {
        display: flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #f8fafc;
        height: 40px;
        flex-shrink: 0;
    }

    .qty-control-btn {
        border: none;
        background: transparent;
        width: 28px;
        height: 100%;
        font-weight: 700;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }

    .qty-control-btn:hover {
        background: #cbd5e1;
        color: #0f172a;
    }

    .qty-modern-input {
        width: 35px !important;
        border: none !important;
        background: transparent !important;
        text-align: center;
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        padding: 0 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .qty-modern-input::-webkit-outer-spin-button,
    .qty-modern-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .qty-modern-input {
        -moz-appearance: textfield;
    }

    .btn-premium-inline-cart {
        flex-grow: 1;
        height: 40px;
        background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.85rem;
        border: none !important;
        border-radius: 12px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.25s ease-in-out !important;
        white-space: nowrap;
    }

    .btn-premium-inline-cart:hover:not(:disabled) {
        background: linear-gradient(135deg, #4f46e5, #3730a3) !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        transform: translateY(-1px);
    }

    .btn-premium-inline-cart:disabled {
        background: #cbd5e1 !important;
        color: #94a3b8 !important;
        cursor: not-allowed;
    }

    .custom-product-card {
        background: #ffffff;
        border-radius: 20px !important;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(226, 232, 240, 0.5) !important;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(99, 102, 241, 0.12);
    }

    .product-img-container {
        height: 220px;
        /* កម្ពស់ប្រអប់រូបភាព */
        background-color: #f8fafc;
        border-radius: 20px 20px 0 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .product-main-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        /* រក្សារាងរូបភាពកុំឱ្យទាញខូចទ្រង់ទ្រាយ */
        transition: transform 0.5s ease;
    }

    .custom-product-card:hover .product-main-img {
        transform: scale(1.06);
    }

    .vendor-premium-badge {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        color: #1e293b;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 8px !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .product-title {
        font-size: 0.975rem;
        font-weight: 700;
        line-height: 1.4;
        height: 2.8rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hover-primary {
        transition: color 0.2s ease-in-out;
    }
    .hover-primary:hover {
        color: #4f46e5 !important;
        text-decoration: underline !important;
    }
    .product-short-desc {
        font-size: 0.825rem;
        color: #64748b;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.3rem;
        line-height: 1.4;
    }

    /* 📱 Responsive Design */
    @media (max-width: 991.98px) {
        .product-img-container {
            height: 180px !important;
        }

        .product-title {
            font-size: 0.9rem;
            height: 2.6rem;
        }

        .custom-qty-stepper,
        .btn-premium-inline-cart {
            height: 38px;
        }
    }

    @media (max-width: 575.98px) {
        .responsive-cart-container {
            flex-direction: column;
            gap: 6px;
        }

        .custom-qty-stepper {
            width: 100% !important;
            justify-content: space-between;
        }

        .qty-control-btn {
            width: 40px;
        }

        .btn-premium-inline-cart {
            width: 100% !important;
        }

        .product-img-container {
            height: 150px !important;
        }
    }
</style>
<!-- 🔔 Alpine.js និង SweetAlert2 Setup -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ចាប់ស្តាប់ព្រឹត្តិការណ៍ពី Alpine.js នៅពេលចុច Add to Cart
        window.addEventListener('notify', (e) => {
            const data = e.detail;
            Swal.fire({
                title: data.title || 'Success!',
                icon: data.type || 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1e293b',
                iconColor: data.type === 'success' ? '#10b981' : '#ef4444'
            });
        });
    });
</script>
