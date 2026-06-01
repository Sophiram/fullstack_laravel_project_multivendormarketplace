@extends('layouts.user')

@section('home')
    {{-- 🔗 បញ្ចូល Google Fonts និង Animate.css សម្រាប់បង្កើនសោភ័ណភាព --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="gift-page-wrapper py-5 bg-gradient-light">
        <div class="container mt-4">

            {{-- 🎁 ផ្នែក Header & Title --}}
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <span
                        class="badge bg-warning-subtle text-warning-dominant px-3 py-2 rounded-pill fw-bold text-uppercase tracking-wider mb-2 animate__animated animate__fadeInDown"
                        style="font-size: 0.75rem; letter-spacing: 1px;">
                        <i class="bi bi-gift-fill me-1"></i> Curated Gift Boxes
                    </span>
                    <h1 class="fw-extrabold text-dark display-5 page-main-title animate__animated animate__fadeIn">The Gift
                        Collections</h1>
                    <p class="text-muted mx-auto sub-title-text" style="max-width: 580px;">
                        Discover handpicked, premium gift sets thoughtfully curated for your special occasions and loved
                        ones.
                    </p>
                </div>
            </div>

            {{-- 🛍️ ផ្នែកបង្ហាញបញ្ជី Gift Collections (Grid Layout) --}}
            <div class="row g-4 mb-5">
                @forelse ($giftCollections as $collection)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div
                            class="gift-premium-card h-100 d-flex flex-column justify-content-between overflow-hidden animate__animated animate__fadeInUp">

                            {{-- 🖼️ ផ្នែករូបភាព Gift Collection --}}
                            <div class="position-relative gift-image-wrapper">
                                <img src="{{ !empty($collection->image) ? asset('storage/' . $collection->image) : 'https://placehold.co/600x400?text=Premium+Gift+Box' }}"
                                    alt="{{ $collection->name }}" class="w-100 h-100 object-fit-cover transition-transform">

                                {{-- Badge បង្ហាញប្រភេទ ឬលក្ខណៈពិសេស --}}
                                @if ($collection->is_featured)
                                    <span
                                        class="position-absolute top-0 start-0 m-3 badge bg-danger px-3 py-2 rounded-2 fw-semibold shadow-sm">
                                        <i class="bi bi-star-fill me-1"></i> Best Seller
                                    </span>
                                @endif
                            </div>

                            {{-- 📝 ផ្នែកព័ត៌មានលម្អិត (Card Body) --}}
                            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-primary small fw-bold text-uppercase tracking-wide">
                                            {{ $collection->category ?? 'Special Set' }}
                                        </span>
                                        <div class="text-warning small">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2 gift-title">{{ $collection->name }}</h5>
                                    <p class="text-muted small line-clamp-2 mb-3">{{ $collection->description }}</p>
                                </div>

                                {{-- តម្លៃ និងប៊ូតុងសកម្មភាព --}}
                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small d-block">Price Set</span>
                                        <span
                                            class="fw-extrabold text-dark fs-4 price-text">${{ number_format($collection->price, 2) }}</span>
                                    </div>
                                    <a href="{{ route('gift.show', $collection->id) }}"
                                        class="btn btn-primary rounded-3 px-3.5 py-2 fw-bold d-inline-flex align-items-center gap-2 view-details-btn">
                                        <span>View Box</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    {{-- ករណីមិនទាន់មានទិន្នន័យពី Admin --}}
                    <div class="col-12 text-center py-5">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-gift text-muted fs-2"></i>
                        </div>
                        <h5 class="text-muted fw-bold">No Gift Collections Available</h5>
                        <p class="text-muted small">Our team is currently crafting new seasonal premium gift collections!
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection

<style>
    /* -----------------------------------------
       🎨 PREMIUM MODERN GIFT COLLECTION STYLES
    -------------------------------------------- */
    .gift-page-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
    }

    .fw-extrabold {
        font-weight: 750;
    }

    .price-text {
        font-family: 'Space Grotesk', sans-serif;
    }

    .bg-gradient-light {
        background: radial-gradient(circle at top right, #fffbeb 0%, #f8fafc 100%);
    }

    .text-warning-dominant {
        color: #b45309 !important;
    }

    .page-main-title {
        font-family: 'Space Grotesk', sans-serif;
        letter-spacing: -1.5px;
        background: linear-gradient(135deg, #1e1b4b 0%, #b45309 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* 🎁 PREMIUM GIFT CARD DESIGN */
    .gift-premium-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gift-premium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(180, 83, 9, 0.12);
        border-color: rgba(251, 191, 36, 0.4);
    }

    /* 🖼️ Image Zoom Effect */
    .gift-image-wrapper {
        height: 240px;
        background-color: #f1f5f9;
    }

    .gift-premium-card:hover .gift-image-wrapper img {
        transform: scale(1.06);
    }

    .transition-transform {
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gift-title {
        font-size: 1.2rem;
        letter-spacing: -0.3px;
        line-height: 1.3;
    }

    /* ✂️ Text Line Clamp (កាត់អក្សរវែងៗឱ្យសល់ ២ជួរ) */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* 🛒 Action Button Customization */
    .view-details-btn {
        background: linear-gradient(135deg, #1e1b4b, #312e81);
        border: none;
        transition: all 0.25s ease;
    }

    .view-details-btn:hover {
        background: linear-gradient(135deg, #b45309, #d97706);
        transform: translateX(2px);
    }

    @media (max-width: 575.98px) {
        .page-main-title {
            font-size: 2.2rem;
        }

        .gift-image-wrapper {
            height: 200px;
        }
    }
</style>
