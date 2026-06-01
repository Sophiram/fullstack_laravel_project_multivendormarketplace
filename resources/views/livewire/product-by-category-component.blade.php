<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

new class extends Component {
    use WithPagination;

    // កំណត់ប្រើប្រាស់ Bootstrap សម្រាប់លីងទំព័រ Pagination
    protected $paginationTheme = 'bootstrap';

    // បង្កើត Properties សម្រាប់ចាប់ទិន្នន័យពី View
    public $category_name;
    public $price_range = 1000;
    public $sort_by = 'default';

    public $selected_stars = [];

    // មុខងាររត់ដំបូងគេដើម្បីចាប់យក category_name ពី Route URL
    public function mount($category_name)
    {
        $this->category_name = $category_name;
    }

    // មុខងារ Reset ទំព័រ Pagination មកលេខ ១ វិញភ្លាមៗពេលអ្នកប្រើប្រាស់ផ្លាស់ប្តូរ Filter
    public function updating($property)
    {
        if (in_array($property, ['price_range', 'sort_by', 'selected_stars', 'category_name'])) {
            $this->resetPage();
        }
    }

    public function changeCategory($name)
    {
        $this->category_name = $name;
        $this->resetPage();
    }

    public function toggleWishlist($productId)
    {
        $wishlist = session()->get('wishlist', []);

        if (isset($wishlist[$productId])) {
            unset($wishlist[$productId]);
            $this->dispatch('wishlistUpdated');
            $this->dispatch('notify', [
                'title' => 'Successfully removed from wishlist',
                'type' => 'error',
            ]);
        } else {
            $product = Product::with('images')->findOrFail($productId);
            $productImage = $product->images->first() ? $product->images->first()->image_path : null;

            $wishlist[$productId] = [
                'name' => $product->product_name,
                'price' => $product->discounted_price ?? $product->regular_price,
                'image' => $productImage,
            ];

            $this->dispatch('wishlistUpdated');
            $this->dispatch('notify', [
                'title' => 'Successfully added to wishlist',
                'type' => 'success',
            ]);
        }

        session()->put('wishlist', $wishlist);
    }

    // ផ្នែក Render - បានបញ្ចូលលក្ខខណ្ឌចម្រោះរបស់ Admin
    public function render(): mixed
    {
        $category = Category::where('category_name', $this->category_name)->firstOrFail();
        $categories = Category::all();

        // 💡 ប្រើប្រាស់ productReviews ឱ្យដូចគ្នានៅគ្រប់កន្លែង
        $query = Product::with(['images', 'vendor.stores', 'productReviews'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->where('regular_price', '<=', $this->price_range);

        // ឆែកលក្ខខណ្ឌ Rating (ប្រើប្រាស់លុះត្រាតែ Model Product មាន Relation productReviews)
        if (!empty($this->selected_stars) && method_exists(Product::class, 'productReviews')) {
            $query->whereHas('productReviews', function ($q) {
                $q->select('product_id')
                    ->groupBy('product_id')
                    ->havingRaw('FLOOR(AVG(rating)) IN (' . implode(',', array_map('intval', $this->selected_stars)) . ')');
            });
        }

        if ($this->sort_by === 'price_low_high') {
            $query->orderBy('regular_price', 'asc');
        } elseif ($this->sort_by === 'price_high_low') {
            $query->orderBy('regular_price', 'desc');
        } elseif ($this->sort_by === 'latest') {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(9);

        return view('livewire.product-by-category-component', [
            'products' => $products,
            'category' => $category,
            'categories' => $categories,
        ]);
    }
};
?>

<div>
    <div class="container py-3 py-md-5">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap"
            rel="stylesheet">

        <style>
            .category-page-wrapper {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .category-banner {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #f97316 100%) !important;
                border-radius: 24px;
                padding: 40px;
                color: #ffffff;
                margin-bottom: 30px;
                box-shadow: 0 15px 35px -10px rgba(124, 58, 237, 0.3);
            }

            .filter-card {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(241, 245, 249, 0.9) 100%) !important;
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-radius: 22px;
                border: 1px solid rgba(226, 232, 240, 0.8);
                padding: 24px;
                position: sticky;
                top: 110px;
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.03);
            }

            .product-card {
                background: #ffffff !important;
                border-radius: 20px;
                border: 1px solid rgba(226, 232, 240, 0.8);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                overflow: hidden;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .product-card:hover {
                transform: translateY(-6px);
                border-color: #7c3aed;
                box-shadow: 0 20px 35px -10px rgba(124, 58, 237, 0.15);
            }

            .img-wrapper {
                background: #f8fafc;
                position: relative;
                overflow: hidden;
                padding-top: 100%;
            }

            .img-wrapper img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: contain;
                padding: 12px;
                transition: transform 0.5s ease;
            }

            .product-card:hover .img-wrapper img {
                transform: scale(1.05);
            }

            .product-info-block {
                padding: 12px;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .product-title-text {
                font-family: 'Outfit', sans-serif;
                font-size: 0.9rem;
                font-weight: 600;
                color: #0f172a !important;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                height: 2.4rem;
                line-height: 1.2rem;
                margin-bottom: 6px;
            }

            .premium-stepper-container {
                display: flex;
                flex-direction: column;
                gap: 8px;
                width: 100%;
                margin-top: auto;
            }

            .stepper-input-group {
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                overflow: hidden;
                background-color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                height: 36px;
            }

            .btn-stepper {
                border: none;
                background: #f1f5f9;
                color: #334155;
                width: 36px;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                cursor: pointer;
                transition: background-color 0.15s ease;
            }

            .btn-stepper:hover {
                background-color: #e2e8f0;
            }

            .qty-inline-input {
                border: none !important;
                background-color: transparent !important;
                color: #1e293b !important;
                font-weight: 700;
                font-size: 0.85rem;
                text-align: center;
                flex-grow: 1;
                width: 30px;
                padding: 0 !important;
                box-shadow: none !important;
            }

            .btn-premium-inline-cart {
                background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
                color: #ffffff !important;
                font-weight: 600;
                font-size: 0.8rem;
                border: none !important;
                border-radius: 10px !important;
                width: 100%;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                transition: all 0.2s ease-in-out !important;
            }

            .btn-premium-inline-cart:hover {
                background: linear-gradient(135deg, #f97316, #ea580c) !important;
                transform: translateY(-1px);
            }

            @media (min-width: 768px) {
                .product-info-block {
                    padding: 16px;
                }

                .product-title-text {
                    font-size: 0.95rem;
                }

                .premium-stepper-container {
                    flex-direction: row;
                    align-items: center;
                }

                .stepper-input-group {
                    width: 95px;
                    flex-shrink: 0;
                }

                .btn-premium-inline-cart {
                    flex-grow: 1;
                    width: auto;
                }
            }

            .current-price {
                font-size: 1rem;
                font-weight: 800;
                color: #ef4444;
                font-family: 'Outfit';
            }

            .old-price {
                font-size: 0.75rem;
                color: #94a3b8;
                text-decoration: line-through;
                font-family: 'Outfit';
            }

            .stock-status-badge {
                font-size: 0.65rem;
                color: #10b981;
                font-weight: 600;
                background: #ecfdf5;
                padding: 2px 6px;
                border-radius: 6px;
            }

            .vendor-premium-badge {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(4px);
                color: #1e293b;
                font-weight: 600;
                font-size: 0.65rem;
                border-radius: 8px !important;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            }

            .category-filter-btn {
                font-size: 0.9rem;
                text-align: left;
                transition: all 0.2s ease;
                border-radius: 10px !important;
                margin-bottom: 4px;
            }

            .category-filter-btn.active {
                background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
                color: #ffffff !important;
                font-weight: 600;
            }

            @media (max-width: 575.98px) {
                .category-banner {
                    padding: 24px 20px;
                    border-radius: 16px;
                }

                .category-banner h1 {
                    font-size: 1.8rem;
                }

                .current-price {
                    font-size: 0.9rem;
                }
            }
        </style>

        <div class="category-page-wrapper">
            {{-- Banner --}}
            <div class="category-banner animate__animated animate__fadeIn">
                <span class="badge bg-white text-dark mb-2 px-3 py-2 rounded-pill fw-bold text-uppercase shadow-sm"
                    style="font-size: 0.7rem; font-family: 'Outfit';">Collection</span>
                <h1 class="display-6 fw-extrabold m-0" style="font-family: 'Outfit', sans-serif; letter-spacing: -1px;">
                    {{ $category_name }}</h1>
                <p class="text-white-50 m-0 mt-2 small">Discover the best premium items under "{{ $category_name }}"
                    category.</p>
            </div>

            <div class="row g-3 g-md-4">
                {{-- 🖥️ Sidebar Filters (Desktop Only) --}}
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="filter-card border-0">
                        <h5 class="fw-bold mb-4 d-flex align-items-center justify-content-between text-dark"
                            style="font-family: 'Outfit';">
                            <span><i class="fa-solid fa-sliders text-primary me-2"></i>Filter Products</span>
                            @if (!empty($selected_stars) || $price_range < 2000 || $sort_by !== 'default')
                                <button class="btn btn-link p-0 text-muted small text-decoration-none"
                                    wire:click="$set('selected_stars', []); $set('price_range', 2000); $set('sort_by', 'default');"
                                    style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-rotate-left"></i> Reset
                                </button>
                            @endif
                        </h5>

                        {{-- 💰 1. PRICE RANGE FILTER --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark text-uppercase small"
                                style="letter-spacing: 0.5px;">Price Range</label>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">$10</span>
                                <span class="text-primary fw-bold px-2 py-1 rounded bg-light"
                                    style="font-family: 'Outfit'; font-size: 0.9rem;">Max: ${{ $price_range }}</span>
                            </div>
                            <input type="range" class="form-range" min="10" max="2000" step="10"
                                wire:model.live="price_range">
                        </div>

                        <hr class="my-4" style="border-color: rgba(0,0,0,0.06);">

                        {{-- 🌟 2. PRODUCT RATING FILTER --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark text-uppercase small"
                                style="letter-spacing: 0.5px;">Product Rating</label>
                            <div class="d-flex flex-column gap-2 mt-2">
                                @for ($rating = 5; $rating >= 1; $rating--)
                                    <div class="form-check d-flex align-items-center gap-2 m-0 py-1">
                                        <input class="form-check-input mt-0" type="checkbox" value="{{ $rating }}"
                                            id="star_{{ $rating }}" wire:model.live="selected_stars"
                                            style="cursor: pointer; width: 17px; height: 17px;">
                                        <label class="form-check-label text-warning d-flex align-items-center gap-1"
                                            for="star_{{ $rating }}" style="cursor:pointer;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="{{ $i <= $rating ? 'fa-solid fa-star' : 'fa-regular fa-star text-muted opacity-40' }}"></i>
                                            @endfor
                                            @if ($rating < 5)
                                                <span class="text-secondary small ms-1" style="font-size: 0.8rem;">&
                                                    up</span>
                                            @endif
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: rgba(0,0,0,0.06);">

                        {{-- 📁 3. PRODUCT CATEGORIES FILTER --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark text-uppercase small mb-2"
                                style="letter-spacing: 0.5px;">Product Categories</label>
                            <div class="d-flex flex-column">
                                @foreach ($categories as $cat)
                                    <button type="button" wire:click="changeCategory('{{ $cat->category_name }}')"
                                        class="btn btn-light category-filter-btn border-0 py-2 px-3 text-start {{ $category_name === $cat->category_name ? 'active' : 'text-secondary bg-transparent' }}">
                                        <i
                                            class="fa-solid {{ $category_name === $cat->category_name ? 'fa-folder-open' : 'fa-folder' }} me-2 opacity-70"></i>
                                        {{ $cat->category_name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Grid Section --}}
                <div class="col-lg-9 col-12">
                    {{-- Toolbar --}}
                    <div class="d-flex flex-row justify-content-between align-items-center p-3 rounded-4 border mb-4 gap-2"
                        style="background: #ffffff; border-color: #e2e8f0 !important;">
                        <div class="text-muted small fw-semibold" style="font-family: 'Outfit';">
                            Showing <span class="text-dark fw-bold">{{ $products->count() }}</span> of <span
                                class="text-dark fw-bold">{{ $products->total() }}</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <select class="form-select toolbar-select rounded-3 small fw-semibold text-secondary py-1.5"
                                style="width: auto; min-width: 140px; font-size: 0.85rem;" wire:model.live="sort_by">
                                <option value="default">Sorting</option>
                                <option value="latest">Latest</option>
                                <option value="price_low_high">Price: Low-High</option>
                                <option value="price_high_low">Price: High-Low</option>
                            </select>
                            <button class="btn btn-light d-lg-none rounded-3 border-light-subtle py-1.5" type="button"
                                data-bs-toggle="collapse" data-bs-target="#mobileFilterCollapse">
                                <i class="fa-solid fa-filter"></i>
                            </button>
                        </div>
                    </div>

                    {{-- 📱 Mobile Filter Dropdown --}}
                    <div class="collapse d-lg-none mb-4" id="mobileFilterCollapse">
                        <div class="p-3 rounded-4 border bg-white shadow-sm animate__animated animate__fadeIn">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small text-uppercase">Price Range
                                    (${{ $price_range }})</label>
                                <input type="range" class="form-range" min="10" max="2000" step="10"
                                    wire:model.live="price_range">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small text-uppercase">Rating</label>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @for ($rating = 5; $rating >= 1; $rating--)
                                        <div
                                            class="form-check bg-light px-2 py-1.5 rounded-3 border d-inline-flex align-items-center gap-2 m-0">
                                            <input class="form-check-input m-0" type="checkbox"
                                                value="{{ $rating }}" id="m_star_{{ $rating }}"
                                                wire:model.live="selected_stars">
                                            <label
                                                class="form-check-label text-warning small fw-bold d-flex align-items-center gap-1"
                                                for="m_star_{{ $rating }}">
                                                {{ $rating }} <i class="fa-solid fa-star"></i>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label class="form-label fw-bold text-dark small text-uppercase">Categories</label>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach ($categories as $cat)
                                        <button type="button"
                                            wire:click="changeCategory('{{ $cat->category_name }}')"
                                            class="btn btn-sm btn-light border py-1 px-2.5 rounded-pill {{ $category_name === $cat->category_name ? 'bg-primary text-white border-primary' : 'text-secondary' }}">
                                            {{ $cat->category_name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Grid --}}
                    <div class="row g-2 g-md-3">
                        @forelse($products as $product)
                            <div class="col-6 col-md-4 animate__animated animate__fadeInUp">
                                <div class="product-card">
                                    {{-- Image Wrapper --}}
                                    <div class="img-wrapper">
                                        <a href="{{ route('product.details', ['productId' => $product->id]) }}"
                                            class="d-block w-100 h-100">
                                            <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://placehold.co/400x400?text=No+Image' }}"
                                                class="card-img-top" alt="{{ $product->product_name }}">
                                        </a>

                                        {{-- Wishlist Button --}}
                                        <button wire:click="toggleWishlist({{ $product->id }})"
                                            class="btn p-0 border-0 shadow-none position-absolute top-0 end-0 mt-2 me-2"
                                            style="z-index: 10;">
                                            @if (isset(session()->get('wishlist', [])[$product->id]))
                                                <i
                                                    class="fa-solid fa-heart fs-5 text-danger animate__animated animate__bounceIn"></i>
                                            @else
                                                <i class="fa-regular fa-heart fs-5 text-secondary"></i>
                                            @endif
                                        </button>

                                        <span
                                            class="badge position-absolute top-0 start-0 m-2 px-2 py-1 vendor-premium-badge d-none d-sm-inline-block">
                                            <i class="fa-solid fa-circle-check me-1 text-primary"></i> Verified
                                        </span>
                                    </div>

                                    {{-- Product Details Block --}}
                                    <div class="product-info-block">
                                        <div>
                                            <span class="text-muted text-uppercase fw-bold d-block"
                                                style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                                {{ $product->vendor && $product->vendor->stores->first() ? $product->vendor->stores->first()->store_name : 'Official Store' }}
                                            </span>

                                            <h5 class="product-title-text" title="{{ $product->product_name }}">
                                                <a href="{{ route('product.details', ['productId' => $product->id]) }}"
                                                    class="text-dark text-decoration-none">
                                                    {{ $product->product_name }}
                                                </a>
                                            </h5>

                                            {{-- 🌟 Rating Stars (កែសម្រួលឱ្យប្រើ productReviews ទាំងអស់) --}}
                                            @php
                                                $avgRating =
                                                    $product->productReviews && $product->productReviews->count() > 0
                                                        ? $product->productReviews->avg('rating')
                                                        : 0;
                                                $fullStars = floor($avgRating);
                                                $halfStar = $avgRating - $fullStars >= 0.5 ? 1 : 0;
                                                $totalReviews = $product->productReviews
                                                    ? $product->productReviews->count()
                                                    : 0;
                                            @endphp
                                            <div class="d-flex align-items-center text-warning gap-1 mb-2"
                                                style="font-size: 0.72rem;">
                                                <div class="d-flex gap-0.5">
                                                    @if ($avgRating > 0)
                                                        @for ($i = 0; $i < $fullStars; $i++)
                                                            <i class="fa-solid fa-star"></i>
                                                        @endfor
                                                        @if ($halfStar)
                                                            <i class="fa-solid fa-star-half-stroke"></i>
                                                        @endif
                                                        @for ($i = 0; $i < 5 - $fullStars - $halfStar; $i++)
                                                            <i class="fa-regular fa-star text-muted opacity-40"></i>
                                                        @endfor
                                                    @else
                                                        @for ($i = 0; $i < 5; $i++)
                                                            <i class="fa-regular fa-star text-muted opacity-30"></i>
                                                        @endfor
                                                    @endif
                                                </div>
                                                @if ($totalReviews > 0)
                                                    <span class="text-muted"
                                                        style="font-size: 0.68rem;">({{ $totalReviews }})</span>
                                                @endif
                                            </div>

                                            {{-- Price & Stock --}}
                                            <div class="d-flex align-items-center justify-content-between gap-1 mb-3">
                                                <div class="d-flex align-items-baseline flex-wrap gap-1">
                                                    <span
                                                        class="current-price">${{ number_format($product->discounted_price ?? $product->regular_price, 2) }}</span>
                                                    @if (isset($product->discounted_price) && $product->discounted_price < $product->regular_price)
                                                        <span
                                                            class="old-price">${{ number_format($product->regular_price, 2) }}</span>
                                                    @endif
                                                </div>
                                                <div class="stock-status-badge d-none d-sm-block">
                                                    {{ $product->stock_status ?? 'In Stock' }}</div>
                                            </div>
                                        </div>

                                        {{-- 🛒 Responsive Stepper + Add To Cart Button --}}
                                        <div class="premium-stepper-container" x-data="{ quantity: 1, maxStock: {{ $product->stock_quantity ?? 10 }} }">
                                            {{-- Stepper (- / +) --}}
                                            <div class="stepper-input-group">
                                                <button type="button" class="btn-stepper"
                                                    @click="if(quantity > 1) quantity--">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                                <input type="number" x-model="quantity"
                                                    class="form-control qty-inline-input" readonly>
                                                <button type="button" class="btn-stepper"
                                                    @click="if(quantity < maxStock) quantity++">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>

                                            {{-- Add to Cart Button --}}
                                            <button type="button"
                                                @click="$dispatch('addToCartFromAnywhere', { productId: {{ $product->id }}, quantity: quantity })"
                                                class="btn btn-premium-inline-cart">
                                                <i class="fa-solid fa-basket-shopping"></i>
                                                <span>Add to Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="p-5 rounded-5 border max-width-md mx-auto"
                                    style="background: rgba(255,255,255,0.6); backdrop-filter: blur(10px);">
                                    <i class="fa-solid fa-box-open text-muted mb-3 display-4"></i>
                                    <h4 class="fw-bold text-dark" style="font-family: 'Outfit';">No Products Found
                                    </h4>
                                    <p class="text-muted small">We couldn't find any items matching your current
                                        filters in this category.</p>
                                    <button class="btn btn-primary rounded-3 px-4 py-2 mt-2"
                                        wire:click="$set('selected_stars', []); $set('price_range', 2000);">Reset
                                        Filter</button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('notify', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            Swal.fire({
                title: data.title || 'Success!',
                icon: data.type || 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1e293b',
                iconColor: data.type === 'success' ? '#10b981' : '#ef4444',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        });
    });
</script>
