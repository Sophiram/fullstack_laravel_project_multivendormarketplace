<?php

use Livewire\Volt\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod; // 👈 នាំចូល Model ដើម្បីចាប់យកទិន្នន័យ Gateway មករក្សាទុក
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

new class extends Component {
    // ព័ត៌មានអតិថិជន និងអាសយដ្ឋាន
    public $fullName = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $city = 'Phnom Penh';
    public $note = '';

    // ទិន្នន័យហិរញ្ញវត្ថុទាញពី Session
    public $cartItems = [];
    public $paymentMethodId = null; // រក្សាទុក ID នៃ Payment Method ពី Database
    public $shipping = 0.0;
    public $discount = 0.0;
    public $subtotal = 0.0;
    public $total = 0.0;

    // មុខងារហៅមកដំណើរការមុនគេពេលបើកទំព័រ (Mount)
    public function mount()
    {
        $this->cartItems = session()->get('cart', []);

        if (count($this->cartItems) === 0) {
            return redirect()->route('cart');
        }

        // ចាប់យក ID នៃវិធីសាស្ត្រទូទាត់ដែលបានជ្រើសរើសពីទំពើរកន្ត្រក
        $this->paymentMethodId = session()->get('selected_payment_method_id');
        $this->shipping = session()->get('cart_shipping', 5.0);
        $this->discount = session()->get('cart_discount', 0.0);

        $this->subtotal = collect($this->cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        $this->total = $this->subtotal + $this->shipping - $this->discount;
        if ($this->total < 0) {
            $this->total = 0;
        }

        if (auth()->check()) {
            $this->fullName = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    // ទាញយកព័ត៌មានលម្អិតនៃវិធីសាស្ត្រទូទាត់ដែលបានជ្រើសរើសដើម្បីបង្ហាញលើ UI
    public function getSelectedMethodProperty()
    {
        return PaymentMethod::find($this->paymentMethodId) ?? PaymentMethod::where('status', true)->first();
    }

    // ដំណើរការរក្សាទុកការបញ្ជាទិញ (Place Order)
    public function placeOrder()
    {
        $this->validate([
            'fullName' => 'required|string|min:3',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'address' => 'required|string',
            'city' => 'required',
        ]);

        $order = DB::transaction(function () {
            // ទាញយកឈ្មោះ Gateway ទៅរក្សាទុកក្នុងតារាង Order (សម្រាប់ជាប្រវត្តិ)
            $methodName = $this->selectedMethod ? $this->selectedMethod->name : 'Unknown';

            $newOrder = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $this->total,
                'status' => 'pending',
                'shipping_address' => $this->address . ', ' . $this->city,
                'payment_method' => $methodName, // រក្សាទុកឈ្មោះ Gateway
                'note' => $this->note,
            ]);

            foreach ($this->cartItems as $productId => $item) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            Payment::create([
                'order_id' => $newOrder->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'amount' => $this->total,
                'status' => 'pending',
                'payment_method_id' => $this->selectedMethod ? $this->selectedMethod->id : null, // តភ្ជាប់ ID ទៅតារាង Payment
            ]);

            return $newOrder;
        });

        session()->forget(['cart', 'selected_payment_method_id', 'cart_shipping', 'cart_discount', 'applied_coupon']);

        session()->flash('success', 'Your order has been placed successfully!');

        return redirect()->route('receipt', ['order' => $order->id]);
    }
}; ?>

<div class="checkout-wrapper py-5">
    <!-- Google Fonts & Style Infrastructure -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .checkout-wrapper {
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

        /* Form Inputs Premium Styling */
        .premium-form .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .premium-form .form-control,
        .premium-form .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 0.92rem;
            color: #0f172a;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
        }

        .premium-form .form-control:focus,
        .premium-form .form-select:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .premium-form .is-invalid {
            border-color: #ef4444 !important;
            background-image: none !important;
        }

        /* Selected Payment Method Notice Row */
        .active-gateway-notice {
            background: linear-gradient(to right, #ffffff, #f8fafc);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px;
        }

        .gateway-logo-badge {
            height: 38px;
            max-width: 120px;
            object-fit: contain;
            display: flex;
            align-items: center;
        }

        /* Order Review Item Line Architecture */
        .review-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #f1f5f9;
        }

        .review-item-row:last-child {
            border-bottom: none;
        }

        .review-product-img {
            width: 46px;
            height: 46px;
            background: #f8fafc;
            border-radius: 10px;
            object-fit: contain;
            padding: 2px;
            border: 1px solid #e2e8f0;
        }

        .style-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .style-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .summary-price-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .place-order-btn-premium {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 14px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.25);
        }

        .place-order-btn-premium:hover:not([disabled]) {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
            transform: translateY(-2px);
        }
    </style>

    <div class="container animate__animated animate__fadeIn">
        <div class="row g-4">

            <!-- 📝 ផ្នែកខាងឆ្វេង៖ Form បំពេញព័ត៌មានដឹកជញ្ជូន (Shipping Information) -->
            <div class="col-lg-7">
                <div class="premium-card p-4 premium-form">
                    <h5 class="fw-bold text-dark mb-1 premium-heading">
                        <i class="bi bi-truck me-2 text-primary"></i>Shipping Information
                    </h5>
                    <p class="text-muted small mb-4">Provide secure destination address details for accurate dispatch
                        courier</p>

                    <form wire:submit.prevent="placeOrder" id="checkoutForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="fullName"
                                    class="form-control @error('fullName') is-invalid @enderror"
                                    placeholder="e.g. John Doe">
                                @error('fullName')
                                    <div class="invalid-feedback fw-semibold small mt-1"><i
                                            class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" wire:model="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="e.g. 012345678">
                                @error('phone')
                                    <div class="invalid-feedback fw-semibold small mt-1"><i
                                            class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="text-muted text-capitalize"
                                        style="font-size:0.7rem;">(Optional)</span></label>
                                <input type="email" wire:model="email" class="form-control"
                                    placeholder="e.g. john@example.com">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">City / Province <span class="text-danger">*</span></label>
                                <select wire:model="city" class="form-select">
                                    <option value="Phnom Penh">Phnom Penh</option>
                                    <option value="Siem Reap">Siem Reap</option>
                                    <option value="Sihanoukville">Sihanoukville</option>
                                    <option value="Battambang">Battambang</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Delivery Address <span class="text-danger">*</span></label>
                                <textarea wire:model="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                    placeholder="House number, Street name, Sangkat, Khan..."></textarea>
                                @error('address')
                                    <div class="invalid-feedback fw-semibold small mt-1"><i
                                            class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Order Notes <span class="text-muted text-capitalize"
                                        style="font-size:0.7rem;">(Optional)</span></label>
                                <textarea wire:model="note" rows="2" class="form-control"
                                    placeholder="Special delivery descriptions or drop-off requests..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- 💳 ផ្នែកបង្ហាញវិធីសាស្ត្រទូទាត់ متحرក (Dynamic Gateway Summary) -->
                <div class="premium-card p-4 mt-4 animate__animated animate__fadeInUp">
                    <h5 class="fw-bold text-dark mb-1 premium-heading">
                        <i class="bi bi-credit-card-2-front me-2 text-primary"></i>Selected Payment Gateway
                    </h5>
                    <p class="text-muted small mb-3">Your transaction routing point chosen from the shopping cart phase
                    </p>

                    <div class="active-gateway-notice d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success-subtle p-2 text-success d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px;">
                                <i class="bi bi-shield-check-fill fs-5"></i>
                            </div>
                            <div>
                                @if ($this->selectedMethod && $this->selectedMethod->logo)
                                    <div class="gateway-logo-badge">
                                        <img src="{{ asset('storage/' . $this->selectedMethod->logo) }}"
                                            alt="{{ $this->selectedMethod->name }}"
                                            style="max-height: 100%; max-width: 100%;">
                                    </div>
                                @else
                                    <span class="fw-bold text-dark text-uppercase font-monospace fs-6">
                                        {{ $this->selectedMethod ? $this->selectedMethod->name : 'Standard Pay' }}
                                    </span>
                                @endif
                                <p class="text-muted mb-0" style="font-size: 0.72rem;">Secured End-to-End Processing
                                    Network</p>
                            </div>
                        </div>
                        <a href="/cart"
                            class="btn btn-sm btn-outline-secondary px-3 fw-bold rounded-3 text-decoration-none"
                            style="font-size: 0.8rem; border-radius: 10px!important;">
                            Modify
                        </a>
                    </div>
                </div>
            </div>

            <!-- 📊 ផ្នែកខាងស្តាំ៖ សេចក្ដីសង្ខេបការបញ្ជាទិញ (Order Metrics Summary Box) -->
            <div class="col-lg-5">
                <div class="premium-card p-4 position-sticky animate__animated animate__fadeIn" style="top: 24px;">
                    <h5 class="mb-3 fw-bold text-dark premium-heading">Review Your Order</h5>

                    <!-- បញ្ជីទំនិញសង្ខេបស្អាតប្រណីត -->
                    <div class="order-items-list mb-4 style-scroll pe-1" style="max-height: 220px; overflow-y: auto;">
                        @foreach ($cartItems as $item)
                            <div class="review-item-row">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ !empty($item['image']) ? asset('storage/' . $item['image']) : 'https://placehold.co/50x50?text=No+Img' }}"
                                        class="review-product-img">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small text-truncate"
                                            style="max-width: 170px;">{{ $item['name'] }}</h6>
                                        <small class="text-muted fw-semibold"
                                            style="font-family:'Outfit'; font-size:0.75rem;">Qty:
                                            {{ $item['quantity'] }}</small>
                                    </div>
                                </div>
                                <span
                                    class="fw-bold text-dark small summary-price-text">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- ផ្នែកគណនាតម្លៃសរុប -->
                    <div class="d-flex justify-content-between mb-2.5 small">
                        <span class="text-muted">Subtotal</span>
                        <span
                            class="fw-semibold text-dark summary-price-text">${{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2.5 small">
                        <span class="text-muted">Voucher Discount</span>
                        <span
                            class="text-success fw-semibold summary-price-text">-${{ number_format($this->discount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Courier Shipping</span>
                        <span
                            class="fw-semibold text-dark summary-price-text">${{ number_format($this->shipping, 2) }}</span>
                    </div>

                    <hr class="my-3.5" style="border-color: #e2e8f0;">

                    <div class="d-flex justify-content-between mb-4 align-items-center">
                        <span class="fw-bold text-dark fs-6">Grand Total</span>
                        <span
                            class="fw-extrabold text-danger summary-price-text fs-4">${{ number_format($this->total, 2) }}</span>
                    </div>

                    <!-- ប៊ូតុងបញ្ជាក់ការកុម្ម៉ង់លោត Loading Form Action -->
                    <button wire:click="placeOrder" wire:loading.attr="disabled" type="button"
                        class="btn btn-primary place-order-btn-premium w-100 mb-2.5 fw-bold py-2.5 text-white d-flex align-items-center justify-content-center gap-2">
                        <span wire:loading wire:target="placeOrder" class="spinner-border spinner-border-sm"></span>
                        <i wire:loading.remove wire:target="placeOrder" class="bi bi-shield-lock-fill fs-6"></i>
                        <span wire:loading.remove wire:target="placeOrder">Authorize & Place Order</span>
                        <span wire:loading wire:target="placeOrder">Validating Order Details...</span>
                    </button>

                    <a href="/cart"
                        class="btn btn-light w-100 py-2 rounded-3 text-secondary border fw-semibold small"
                        style="border-radius: 12px!important;">
                        Return to Shopping Cart
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
