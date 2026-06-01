<?php

use Livewire\Volt\Component;
use App\Models\PaymentMethod; // 👈 នាំចូល Model ដើម្បីទាញទិន្នន័យវិធីសាស្ត្រទូទាត់

new class extends Component {
    public $paymentMethod = ''; // ទុកឱ្យទទេរដើម្បីចាំចាប់យក ID នៃ Gateway ដំបូងគេ
    public $promoCode = '';
    public $discount = 0.0;
    public $shipping = 5.0;
    public $appliedCoupon = null;

    // ចាប់ផ្តើមទាញយកវិធីសាស្ត្រទូទាត់ដំបូងគេដែល Active មកធ្វើជា Default
    public function mount()
    {
        $defaultMethod = PaymentMethod::where('status', true)->first();
        if ($defaultMethod) {
            $this->paymentMethod = $defaultMethod->id;
        }
    }

    // ទាញយកវិធីសាស្ត្រទូទាត់ទាំងអស់ដែល Active ពី Database ទៅកាន់ UI
    public function getActivePaymentMethodsProperty()
    {
        return PaymentMethod::where('status', true)->get();
    }

    public function getCartItemsProperty()
    {
        return session()->get('cart', []);
    }

    public function getSubtotalProperty()
    {
        return collect($this->cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getTotalProperty()
    {
        if (count($this->cartItems) === 0) {
            return 0;
        }
        $total = $this->subtotal + $this->shipping - $this->discount;
        return $total > 0 ? $total : 0;
    }

    public function updateQuantity($id, $change)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $change;

            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
                session()->flash('info', 'Item removed from cart.');
            } else {
                session()->put('cart', $cart);
            }

            if ($this->appliedCoupon) {
                $this->applyPromo();
            }
        }
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            session()->flash('info', 'Item removed from cart.');

            if (count($cart) === 0) {
                $this->removePromo();
            } elseif ($this->appliedCoupon) {
                $this->applyPromo();
            }
        }
    }

    public function applyPromo()
    {
        if (empty($this->promoCode)) {
            session()->flash('error', 'Please enter a promo code.');
            return;
        }

        $code = strtoupper(trim($this->promoCode));

        // 🔍 1. ស្វែងរកកូដ Coupon នៅក្នុង Database ដែលមានស្ថានភាព Active (status = 1)
        // លោកអ្នកអាចបន្ថែមលក្ខខណ្ឌពិនិត្យកាលបរិច្ឆេទ (Expiry Date) ប្រសិនបើមាន
        $coupon = \App\Models\Discount::where('code', $code)->where('status', 1)->first();

        if ($coupon) {
            // 💰 2. ពិនិត្យលក្ខខណ្ឌទឹកប្រាក់ទិញអប្បបរមា (Minimum Requirement) ពី Admin
            if ($this->subtotal < $coupon->min_requirement) {
                $this->discount = 0.0;
                $this->appliedCoupon = null;
                session()->flash('error', 'Your order does not meet the minimum spend of $' . number_format($coupon->min_requirement, 2) . ' for this coupon.');
                return;
            }

            $this->appliedCoupon = $code;

            // 🧮 3. គណនាចំនួនទឹកប្រាក់ដែលត្រូវបញ្ចុះ (Percentage % ឬ Fixed Amount $)
            if ($coupon->type === 'percentage') {
                $this->discount = ($this->subtotal * $coupon->value) / 100;
            } else {
                $this->discount = $coupon->value;
            }

            // ការពារកុំឱ្យតម្លៃបញ្ចុះតម្លៃ លើសពីតម្លៃទំនិញសរុប
            if ($this->discount > $this->subtotal) {
                $this->discount = $this->subtotal;
            }

            session()->flash('success', 'Promo code "' . $code . '" applied successfully!');
        } else {
            // ❌ ករណីរកមិនឃើញ ឬ កូដត្រូវបានហួសសុពលភាព
            $this->discount = 0.0;
            $this->appliedCoupon = null;
            session()->flash('error', 'Invalid, expired, or deactivated promo code.');
        }
    }

    public function removePromo()
    {
        $this->discount = 0.0;
        $this->promoCode = '';
        $this->appliedCoupon = null;
        session()->flash('info', 'Promo code removed.');
    }

    public function proceedToCheckout()
    {
        if (count($this->cartItems) === 0) {
            return;
        }

        session()->put('selected_payment_method_id', $this->paymentMethod); // រក្សាទុក ID នៃ Gateway ដែលបានជ្រើសរើស
        session()->put('cart_shipping', $this->shipping);
        session()->put('cart_discount', $this->discount);
        session()->put('applied_coupon', $this->appliedCoupon);

        return redirect()->route('checkout');
    }
}; ?>

<div class="cart-wrapper py-5">
    <!-- Google Fonts & Dynamic UI Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .cart-wrapper {
            background-color: #f8fafc;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .premium-heading {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.5px;
        }

        .premium-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 25px -15px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        /* Product Card Item Styles */
        .cart-item-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.7);
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cart-item-card:hover {
            transform: translateY(-3px);
            border-color: #cbd5e1;
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.06);
        }

        .product-image-box {
            height: 95px;
            width: 100%;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 14px;
            overflow: hidden;
        }

        /* Quantity Incrementor Elements */
        .quantity-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #f1f5f9;
            border: none;
            font-weight: 700;
            color: #475569;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .quantity-input {
            width: 45px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .remove-btn {
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 8px;
            border-radius: 50%;
            background: #f8fafc;
        }

        .remove-btn:hover {
            color: #ef4444;
            background: #fee2e2;
            transform: scale(1.05);
        }

        /* Dynamic Payment Grid Selection Cards */
        .dynamic-payment-card {
            background: #ffffff;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            display: flex;
            flex-column: column;
            align-items: center;
            justify-content: center;
            min-height: 82px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dynamic-payment-card:hover {
            border-color: #cbd5e1 !important;
            background-color: #f8fafc;
            transform: translateY(-2px);
        }

        .btn-check:checked+.dynamic-payment-card {
            border-color: #7c3aed !important;
            background: linear-gradient(to bottom right, #ffffff, #f5f3ff) !important;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
        }

        .payment-logo-container {
            width: 100%;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Summary Right Sticky Section */
        .summary-price-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .checkout-btn-premium {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 14px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.25);
        }

        .checkout-btn-premium:hover:not([disabled]) {
            background: linear-gradient(135deg, #f97316, #ea580c);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.35);
            transform: translateY(-2px);
        }

        .border-dashed {
            border-style: dashed !important;
        }
    </style>

    <div class="container">
        {{-- System Alerts Pipeline Notice Feedback --}}
        <div class="row">
            <div class="col-12">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-4 p-3 animate__animated animate__fadeInDown"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-4 p-3 animate__animated animate__shakeX"
                        role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                @if (session()->has('info'))
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4 rounded-4 p-3 animate__animated animate__fadeIn"
                        role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Side Core Content Elements -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark premium-heading">Shopping Cart</h4>
                        <p class="text-muted small mb-0">Review your products before entering secure checkout system</p>
                    </div>
                    <span class="badge bg-white border text-dark px-3 py-2 rounded-pill fw-semibold shadow-sm"
                        style="font-family: 'Outfit';">
                        {{ count($this->cartItems) }} {{ count($this->cartItems) > 1 ? 'Items' : 'Item' }}
                    </span>
                </div>

                <!-- Product Loop Architecture -->
                <div class="d-flex flex-column gap-3">
                    @forelse($this->cartItems as $id => $item)
                        <div class="cart-item-card p-3 animate__animated animate__fadeInUp">
                            <div class="row align-items-center g-3">
                                <!-- Image Module Asset -->
                                <div class="col-4 col-sm-3 col-md-2">
                                    <div class="product-image-box d-flex align-items-center justify-content-center">
                                        <img src="{{ !empty($item['image']) ? asset('storage/' . $item['image']) : 'https://placehold.co/100x100?text=No+Img' }}"
                                            alt="{{ $item['name'] }}" class="w-100 h-100 object-fit-contain p-1">
                                    </div>
                                </div>

                                <!-- Core Details Block -->
                                <div class="col-8 col-sm-9 col-md-4">
                                    <h6 class="mb-1 fw-bold text-dark text-truncate" style="font-size: 0.95rem;">
                                        {{ $item['name'] }}</h6>
                                    <p class="text-muted mb-1 small" style="font-family: 'Outfit';">Unit Price:
                                        ${{ number_format($item['price'], 2) }}</p>
                                    @if (isset($item['discount']) && $item['discount'] > 0)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"
                                            style="font-size: 0.65rem; font-weight: 700;">{{ $item['discount'] }}%
                                            OFF</span>
                                    @endif
                                </div>

                                <!-- Incrementor Quantity Interface Controls -->
                                <div class="col-6 col-md-3">
                                    <div class="d-flex align-items-center bg-light p-1 rounded-3 d-inline-flex border">
                                        <button type="button" wire:click="updateQuantity('{{ $id }}', -1)"
                                            class="quantity-btn">-</button>
                                        <input type="number" class="quantity-input text-dark"
                                            value="{{ $item['quantity'] }}" readonly>
                                        <button type="button" wire:click="updateQuantity('{{ $id }}', 1)"
                                            class="quantity-btn">+</button>
                                    </div>
                                </div>

                                <!-- Product Row Subtotal -->
                                <div class="col-4 col-md-2 text-md-end">
                                    <span
                                        class="fw-bold text-dark summary-price-text fs-5">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>

                                <!-- Garbage Action Controller -->
                                <div class="col-2 col-md-1 text-end">
                                    <i wire:click="removeItem('{{ $id }}')"
                                        class="bi bi-trash3 remove-btn fs-5" title="Remove item"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="premium-card text-center py-5 border-0 shadow-sm animate__animated animate__fadeIn">
                            <i class="bi bi-basket3 display-3 text-muted mb-3 opacity-30 d-block"></i>
                            <h5 class="fw-bold text-secondary mb-1">Your cart is empty</h5>
                            <p class="text-muted small mb-0 px-4">Go back to shop to discover and select premium
                                products.</p>
                        </div>
                    @endforelse
                </div>

                <!-- 💳 ផ្នែកជ្រើសរើសវិធីសាស្ត្រទូទាត់រត់តាម Admin (Dynamic Gateways Interface) -->
                @if (count($this->cartItems) > 0)
                    <div class="premium-card p-4 mt-4 animate__animated animate__fadeInUp">
                        <h5 class="fw-bold mb-1 text-dark premium-heading">
                            <i class="bi bi-shield-check me-2 text-primary"></i>Secure Payment Gateway
                        </h5>
                        <p class="text-muted small mb-4">Please select your preferred secure transaction network option
                        </p>

                        <div class="row g-2.5">
                            @forelse($this->activePaymentMethods as $method)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <label class="w-100 m-0">
                                        <input type="radio" wire:model="paymentMethod" value="{{ $method->id }}"
                                            class="btn-check" name="payment_option">
                                        <div class="dynamic-payment-card">
                                            <div class="payment-logo-container">
                                                @if ($method->logo)
                                                    <img src="{{ asset('storage/' . $method->logo) }}"
                                                        alt="{{ $method->name }}"
                                                        style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @else
                                                    <span class="fw-bold text-dark font-monospace text-truncate"
                                                        style="font-size: 0.85rem; font-family: 'Outfit'!important;">
                                                        {{ $method->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <small
                                                class="text-muted mt-1.5 font-monospace text-center d-block w-100 text-truncate"
                                                style="font-size: 0.65rem; letter-spacing: -0.2px;">
                                                {{ ucfirst(str_replace('_', ' ', $method->type)) }}
                                            </small>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning border-0 small m-0 rounded-3">
                                        <i class="bi bi-exclamation-triangle me-1"></i> No active payment gateway
                                        methods configured by administrator.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar Panel Order Summary Box -->
            <div class="col-lg-4">
                <div class="premium-card p-4 position-sticky animate__animated animate__fadeIn" style="top: 24px;">
                    <h5 class="mb-4 fw-bold text-dark premium-heading">Order Summary</h5>

                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Subtotal</span>
                        <span
                            class="fw-semibold text-dark summary-price-text">${{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Coupon Discount</span>
                        <span
                            class="text-success fw-semibold summary-price-text">-${{ number_format($this->discount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Estimated Shipping</span>
                        <span
                            class="fw-semibold text-dark summary-price-text">${{ number_format($this->shipping, 2) }}</span>
                    </div>

                    <hr class="my-3.5" style="border-color: #e2e8f0;">

                    <div class="d-flex justify-content-between mb-4 align-items-center">
                        <span class="fw-bold text-dark fs-6">Total Amount</span>
                        <span
                            class="fw-extrabold text-danger summary-price-text fs-4">${{ number_format($this->total, 2) }}</span>
                    </div>

                    <!-- Promo Voucher Ticket Input UI Component -->
                    <div class="mb-4">
                        @if (!$appliedCoupon)
                            <div class="input-group bg-light p-1 rounded-3 border" style="overflow: hidden;">
                                <input type="text" wire:model="promoCode"
                                    class="form-control bg-transparent border-0 shadow-none py-2 small ps-2"
                                    placeholder="Try 'DISCOUNT10'" style="font-size: 0.85rem;">
                                <button wire:click="applyPromo" class="btn btn-dark px-3 fw-bold border-0 small"
                                    type="button"
                                    style="border-radius: 8px !important; font-size: 0.85rem;">Apply</button>
                            </div>
                        @else
                            <div
                                class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border border-dashed border-success bg-success-subtle bg-opacity-10 animate__animated animate__pulse">
                                <div class="small">
                                    <span class="fw-bold text-success"><i
                                            class="bi bi-ticket-perforated-fill me-1"></i>{{ $appliedCoupon }}</span>
                                    <span class="text-muted small ms-1">(Active Voucher)</span>
                                </div>
                                <button type="button" wire:click="removePromo"
                                    class="btn btn-sm btn-link text-danger text-decoration-none fw-bold p-0 small">Remove</button>
                            </div>
                        @endif
                    </div>

                    @if (count($this->cartItems) > 0)
                        <button wire:click="proceedToCheckout"
                            class="btn btn-primary checkout-btn-premium w-100 mb-3 fw-bold py-2.5 text-white d-flex align-items-center justify-content-center gap-2">
                            <span>Proceed to Checkout</span><i class="bi bi-arrow-right-short fs-5"></i>
                        </button>
                    @else
                        <button class="btn btn-secondary w-100 mb-3 fw-bold py-2.5 rounded-3 opacity-40" disabled
                            style="border-radius: 14px !important;">
                            Cart is Empty
                        </button>
                    @endif

                    <div class="d-flex justify-content-center gap-2 align-items-center opacity-70">
                        <i class="bi bi-patch-check text-success fs-6"></i>
                        <small class="text-muted fw-semibold" style="font-size: 0.72rem;">Secure infrastructure
                            powered by Livewire</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
