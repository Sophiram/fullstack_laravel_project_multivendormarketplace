@extends('layouts.user')

@section('home')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="gift-detail-wrapper py-5 bg-white" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="container mt-5">
            <div class="row g-5">

                {{-- 📸 ផ្នែករូបភាពខាងឆ្វេង --}}
                <div class="col-12 col-lg-6">
                    <div class="gift-image-container p-3 rounded-4 bg-light border text-center overflow-hidden shadow-sm">
                        <img src="{{ !empty($gift->image) ? asset('storage/' . $gift->image) : 'https://placehold.co/600x600?text=Premium+Gift+Box' }}"
                             alt="{{ $gift->name }}" class="img-fluid rounded-4 object-fit-cover w-100" style="max-height: 500px;">
                    </div>
                </div>

                {{-- 📝 ផ្នែកព័ត៌មានលម្អិតខាងស្តាំ --}}
                <div class="col-12 col-lg-6 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-2 fw-bold text-uppercase mb-3" style="font-size: 0.75rem;">
                            {{ $gift->category ?? 'Special Collection' }}
                        </span>

                        <h2 class="fw-extrabold text-dark mb-2" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: -1px;">
                            {{ $gift->name }}
                        </h2>

                        <div class="d-flex align-items-center gap-2 mb-4">
                            <h3 class="fw-bold text-danger m-0" style="font-family: 'Space Grotesk', sans-serif;">
                                ${{ number_format($gift->price, 2) }}
                            </h3>
                            <span class="text-muted small">| Tax included</span>
                        </div>

                        <hr class="my-4 text-muted opacity-20">

                        <h5 class="fw-bold text-dark mb-2">What's Inside This Box?</h5>
                        <p class="text-muted lh-lg" style="font-size: 0.95rem;">
                            {{ $gift->description ?? 'No description available for this premium curated gift set.' }}
                        </p>
                    </div>

                    {{-- 🛒 ប៊ូតុងបញ្ជាទិញ ឬបញ្ចូលកន្ត្រក --}}
                    <div class="mt-5 action-area p-4 rounded-4 bg-light border d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <div>
                            <small class="text-muted d-block mb-1 fw-medium">Guaranteed Delivery</small>
                            <span class="text-success small fw-bold"><i class="bi bi-shield-check me-1"></i> Premium Packaging Included</span>
                        </div>

                        {{-- 💡 ត្រង់នេះអ្នកអាចភ្ជាប់ជាមួយប្រព័ន្ធ Cart របស់អ្នក (ឧទាហរណ៍៖ លីងទៅកាន់ទំព័រទិញ ឬ Livewire Add to Cart) --}}
                        <button class="btn btn-dark btn-lg px-4 py-3 rounded-3 fw-bold d-inline-flex align-items-center gap-2 shadow-sm border-0 custom-buy-btn">
                            <i class="bi bi-bag-plus-fill"></i>
                            <span>Add To Shopping Cart</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

<style>
    .fw-extrabold { font-weight: 800; }
    .bg-primary-subtle { background-color: #e0e7ff !important; }

    .custom-buy-btn {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        transition: all 0.3s ease;
    }
    .custom-buy-btn:hover {
        background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
        transform: translateY(-2px);
    }
</style>
