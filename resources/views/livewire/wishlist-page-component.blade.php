<?php

use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    protected $listeners = [
        'wishlist-updated' => '$refresh',
        'wishlistUpdated' => '$refresh',
    ];

    public function getWishlistItemsProperty()
    {
        return session()->get('wishlist', []);
    }

    public function removeFromWishlist($productId)
    {
        $wishlist = session()->get('wishlist', []);

        if (isset($wishlist[$productId])) {
            unset($wishlist[$productId]);
            session()->put('wishlist', $wishlist);

            $this->dispatch('wishlist-updated');
            $this->dispatch('notify', [
                'title' => 'Successfully removed from wishlist',
                'type' => 'error',
            ]);
        }
    }

    public function addToCart($data)
    {
        $productId = $data['productId'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        $wishlist = session()->get('wishlist', []);

        if (isset($wishlist[$productId])) {
            $item = $wishlist[$productId];
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'quantity' => $quantity,
                ];
            }

            session()->put('cart', $cart);

            $this->dispatch('cart-updated');
            $this->dispatch('notify', [
                'title' => 'Added ' . $quantity . ' item(s) to cart successfully!',
                'type' => 'success',
            ]);
        }
    }

    public function render(): mixed
    {
        return view('livewire.wishlist-page-component', [
            'wishlistItems' => $this->wishlistItems,
        ])->layout('layouts.user');
    }
};
?>

<div class="container py-4 py-md-5">
    <!-- Google Fonts & Animate.css -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        .wishlist-page-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Header Banner Style */
        .wishlist-header-banner {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 35px;
        }

        .back-btn-custom {
            background-color: #ffffff;
            color: #4f46e5;
            border: 1px solid #e2e8f0 !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .back-btn-custom:hover {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            border-color: #4f46e5 !important;
            transform: translateX(-3px);
        }

        /* Product Card Glassmorphism and Premium Hover */
        .wishlist-card {
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 10px 25px -15px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            background: #ffffff !important;
        }

        .wishlist-card:hover {
            transform: translateY(-8px);
            border-color: #7c3aed !important;
            box-shadow: 0 20px 40px -15px rgba(124, 58, 237, 0.18);
        }

        /* Trash Floating Button */
        .trash-floating-btn {
            width: 36px;
            height: 36px;
            color: #94a3b8;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            z-index: 10;
            transition: all 0.25s ease;
        }

        .trash-floating-btn:hover {
            background-color: #fee2e2 !important;
            color: #ef4444 !important;
            transform: scale(1.1);
        }

        /* Image Wrapper & Zoom Hover */
        .wishlist-img-container {
            height: 200px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            overflow: hidden;
            position: relative;
        }

        .wishlist-img {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 16px;
        }

        .wishlist-card:hover .wishlist-img {
            transform: scale(1.08) rotate(1deg);
        }

        /* Text Styling */
        .product-title-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a !important;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.6rem;
            line-height: 1.3;
            transition: color 0.2s ease;
        }

        .wishlist-card:hover .product-title-text a {
            color: #7c3aed !important;
        }

        .price-tag {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #ef4444;
        }

        /* Stepper & Cart Buttons */
        .premium-stepper-container {
            width: 100%;
        }

        .stepper-input-group {
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            overflow: hidden;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            height: 40px;
            transition: border-color 0.2s;
        }

        .stepper-input-group:focus-within {
            border-color: #7c3aed;
        }

        .btn-stepper {
            border: none;
            background: #f1f5f9;
            color: #475569;
            width: 34px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-stepper:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        .qty-inline-input {
            border: none !important;
            background-color: transparent !important;
            color: #0f172a !important;
            font-weight: 700;
            font-size: 0.9rem;
            text-align: center;
            flex-grow: 1;
            width: 30px;
            padding: 0 !important;
            box-shadow: none !important;
            pointer-events: none;
        }

        .btn-premium-inline-cart {
            background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.8rem;
            border: none !important;
            border-radius: 12px !important;
            width: 100%;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            white-space: nowrap !important;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2);
        }

        .btn-premium-inline-cart:hover {
            background: linear-gradient(135deg, #f97316, #ea580c) !important;
            box-shadow: 0 6px 15px rgba(249, 115, 22, 0.35);
            transform: translateY(-2px);
        }

        /* Empty State Premium Styling */
        .empty-wishlist-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 32px;
            padding: 60px 20px;
            box-shadow: 0 15px 35px -15px rgba(0, 0, 0, 0.05);
        }

        .empty-icon-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }

        .empty-heart-icon {
            font-size: 5rem;
            color: #cbd5e1;
        }

        .empty-icon-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #ef4444;
            color: #ffffff;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            border: 3px solid #ffffff;
        }

        @media (max-width: 575.98px) {
            .wishlist-img-container {
                height: 150px;
            }

            .wishlist-header-banner {
                padding: 20px;
                border-radius: 18px;
            }

            .product-title-text {
                font-size: 0.85rem;
                height: 2.2rem;
            }

            .price-tag {
                font-size: 1.05rem;
            }
        }

        @media (max-width: 380px) {
            .btn-premium-inline-cart span {
                display: none;
            }

            .btn-premium-inline-cart {
                gap: 0;
            }
        }
    </style>

    {{-- Back Button --}}
    <div class="mb-4 animate__animated animate__fadeIn">
        <a href="{{ url()->previous() }}"
            class="btn back-btn-custom rounded-3 px-3 py-2 fw-medium d-inline-flex align-items-center">
            <i class="fa-solid fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="wishlist-page-wrapper">
        {{-- Header Banner --}}
        <div
            class="wishlist-header-banner d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 animate__animated animate__fadeIn">
            <div>
                <h3 class="fw-extrabold text-dark m-0"
                    style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
                    <i class="fa-solid fa-heart me-2 text-danger"></i> My Wishlist
                    <span class="fs-5 text-muted fw-normal">({{ count($wishlistItems) }} Items)</span>
                </h3>
                <p class="text-muted m-0 mt-1 small">Manage all the products you have saved for later</p>
            </div>
            <div>
                <span class="badge bg-dark text-white px-3 py-2 rounded-pill fw-semibold shadow-sm"
                    style="font-size: 0.75rem; font-family: 'Outfit';">
                    PREMIUM USER
                </span>
            </div>
        </div>

        @if (count($wishlistItems) > 0)
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2 g-md-3 g-lg-4">
                @foreach ($wishlistItems as $id => $item)
                    <div class="col animate__animated animate__fadeInUp">
                        <div class="card h-100 wishlist-card d-flex flex-column justify-content-between">

                            <div>
                                <!-- Floating Trash Button -->
                                <button wire:click="removeFromWishlist('{{ $id }}')"
                                    class="btn trash-floating-btn position-absolute top-0 end-0 m-2 d-flex align-items-center justify-content-center p-0 border-0 shadow-none rounded-circle">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>

                                <!-- Product Image Container -->
                                <a href="{{ route('product.details', ['productId' => $id]) }}"
                                    class="d-block text-decoration-none">
                                    <div
                                        class="wishlist-img-container d-flex align-items-center justify-content-center">
                                        <img src="{{ !empty($item['image']) ? asset('storage/' . $item['image']) : 'https://placehold.co/400x400?text=No+Image' }}"
                                            class="w-100 h-100 object-fit-contain wishlist-img"
                                            alt="{{ $item['name'] ?? 'Product' }}">
                                    </div>
                                </a>

                                <!-- Product Content Body -->
                                <div class="p-2 p-sm-3 pb-0">
                                    <span class="badge bg-light text-secondary mb-1 border d-none d-sm-inline-block"
                                        style="font-size: 0.6rem; font-weight: 700; letter-spacing: 0.3px;">SAVED
                                        ITEM</span>
                                    <h5 class="product-title-text mb-1" title="{{ $item['name'] ?? '' }}">
                                        <a href="{{ route('product.details', ['productId' => $id]) }}"
                                            class="text-dark text-decoration-none">
                                            {{ $item['name'] ?? 'Product Title' }}
                                        </a>
                                    </h5>
                                    <div class="my-1">
                                        <span class="price-tag">
                                            ${{ number_format($item['price'] ?? 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Stepper & Add to Cart Section -->
                            <div class="px-2 pb-2 px-sm-3 pb-sm-3 mt-auto">
                                <div x-data="{ quantity: 1, maxStock: 10 }" class="premium-stepper-container">
                                    <div class="row g-1.5 align-items-center">
                                        <!-- Interactive Stepper -->
                                        <div class="col-5">
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
                                        </div>
                                        <!-- Gradient Add to Cart Button -->
                                        <div class="col-7">
                                            <button type="button"
                                                @click="$wire.addToCart({ productId: '{{ $id }}', quantity: quantity })"
                                                class="btn btn-premium-inline-cart">
                                                <i class="fa-solid fa-basket-shopping"></i>
                                                <span>Add to Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Premium Empty State -->
            <div class="row justify-content-center py-5 animate__animated animate__fadeIn">
                <div class="col-md-6 text-center">
                    <div class="empty-wishlist-box">
                        <div class="empty-icon-wrapper">
                            <i class="fa-regular fa-heart empty-heart-icon"></i>
                            <div class="empty-icon-overlay">
                                <i class="fa-solid fa-times"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-2" style="font-family: 'Outfit', sans-serif;">Your Wishlist is
                            Empty</h4>
                        <p class="text-muted small mb-4 px-md-4">You haven't saved any products yet. Go back to the
                            store to discover and save your favorite premium products.</p>
                        <a href="/"
                            class="btn px-4 py-2 text-white fw-bold btn-premium-inline-cart d-inline-flex align-items-center justify-content-center mx-auto"
                            style="width: auto; max-width: 220px;">
                            <i class="fa-solid fa-arrow-left me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
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
                timer: 2000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1e293b',
                iconColor: data.type === 'success' ? '#10b981' : '#ef4444',
                customClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        });
    });
</script>
