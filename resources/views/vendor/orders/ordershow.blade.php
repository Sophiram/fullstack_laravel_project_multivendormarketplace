@extends('vendor.layouts.layout')

@section('vendor_page_title')
    Order Details #{{ $order->order_number }}
@endsection

@section('vendor_layout')
    <style>
        .order-details-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
        }

        .card {
            border-radius: 16px !important;
        }

        .btn-premium {
            background: linear-gradient(135deg, #4f46e5, #3730a3) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
        }

        .form-control-custom {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 14px;
        }

        .form-control-custom:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15) !important;
        }
    </style>

    <div class="order-details-wrapper">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Order Details</h3>
                    <p class="text-muted small">Order Number: <span
                            class="fw-bold text-primary">#{{ $order->order_number }}</span></p>
                </div>
                <a href="{{ route('vendor.orders.history') }}" class="btn px-3 py-2 fw-semibold border-0 shadow-sm"
                    style="background-color: #f1f5f9; color: #475569; border-radius: 10px;">
                    <i class="fa-solid fa-arrow-left-long me-2"></i> Back to History
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Items in this Order
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3">Product</th>
                                        <th>Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-4">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-3 bg-light border p-1"
                                                        style="width: 50px; height: 50px;">
                                                        @if ($item->product?->images?->first())
                                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                                                class="w-100 h-100 object-fit-cover rounded">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">
                                                            {{ $item->product->product_name ?? 'Product Deleted' }}</div>
                                                        <small class="text-muted">Store:
                                                            {{ $item->product->store->store_name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">${{ number_format($item->price, 2) }}</td>
                                            <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold text-dark pe-4">
                                                ${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <!-- Customer Summary Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="font-family: 'Outfit', sans-serif;">Order Summary</h5>
                        <div class="mb-3"><label class="text-muted small d-block">Customer Name</label><span
                                class="fw-bold text-dark">{{ $order->user->name ?? 'Unknown' }}</span></div>
                        <div class="mb-3"><label class="text-muted small d-block">Status</label>
                            <span
                                class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'processing' ? 'info' : 'warning') }}-subtle text-{{ $order->status == 'completed' ? 'success' : ($order->status == 'processing' ? 'info' : 'warning') }} rounded-pill px-3 py-1">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="font-family: 'Outfit', sans-serif;">Shipping Information</h5>

                        @if ($order->shipping)
                            <div class="p-3 bg-light rounded-3">
                                <div class="mb-2">
                                    <label class="text-muted small d-block">Shipping Company</label>
                                    {{-- ប្រើ Relationship shippingCompany ដើម្បីបង្ហាញឈ្មោះ --}}
                                    <span
                                        class="fw-bold text-dark">{{ $order->shipping->shippingCompany->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <label class="text-muted small d-block">Tracking Number</label>
                                    <span class="fw-bold text-primary">{{ $order->shipping->tracking_number }}</span>
                                </div>
                                <div class="mt-2">
                                    <label class="text-muted small d-block">Shipping Cost</label>
                                    <span
                                        class="fw-bold text-success">${{ number_format($order->shipping->shipping_cost, 2) }}</span>
                                </div>
                            </div>
                        @elseif ($order->status == 'processing')
                            <form action="{{ route('vendor.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="shipped">

                                <div class="mb-3">
                                    <label class="small fw-bold mb-1">Shipping Company</label>
                                    <select name="shipping_company_id" class="form-select form-control-custom" required
                                        onchange="updateCost(this)">
                                        <option value="">-- Select Company --</option>
                                        @foreach ($shippingCompanies as $company)
                                            <option value="{{ $company->id }}" data-fee="{{ $company->shipping_fee }}">
                                                {{ $company->name }} (${{ number_format($company->shipping_fee, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold mb-1">Tracking Number</label>
                                    <input type="text" name="tracking_number" class="form-control form-control-custom"
                                        placeholder="Enter tracking number" required>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold mb-1">Shipping Cost ($)</label>
                                    <input type="number" name="shipping_cost" id="shipping_cost"
                                        class="form-control form-control-custom" step="0.01" readonly required>
                                </div>

                                <button type="submit" class="btn btn-premium w-100 py-2 fw-bold">Confirm Shipping</button>
                            </form>

                            <script>
                                function updateCost(select) {
                                    const costInput = document.getElementById('shipping_cost');
                                    const selectedOption = select.options[select.selectedIndex];
                                    const fee = selectedOption.getAttribute('data-fee');
                                    costInput.value = fee ? parseFloat(fee).toFixed(2) : '0.00';
                                }
                            </script>
                        @else
                            <p class="text-muted small">No shipping information available yet.</p>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
