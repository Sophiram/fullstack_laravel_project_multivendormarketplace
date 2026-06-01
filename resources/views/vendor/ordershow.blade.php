@extends('vendor.layouts.layout')

@section('vendor_page_title')
    Order Details #{{ $order->order_number }}
@endsection

@section('vendor_layout')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1">Order Details</h3>
                <p class="text-muted small">Order Number: <span class="fw-bold text-primary">#{{ $order->order_number }}</span></p>
            </div>
            <div>
                <a href="{{ route('vendor.orders.history') }}" class="btn btn-sm btn-light border shadow-none">
                    <i class="align-middle me-1" data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back to History
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- 🛒 ផ្នែកបញ្ជីមុខទំនិញ (Order Items) -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold small text-uppercase tracking-wider">Items in this Order</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 text-nowrap">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Product</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3 text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded bg-light border d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; overflow: hidden;">
                                                    @if($item->product && $item->product->images && $item->product->images->first())
                                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                                    @else
                                                        <i data-feather="package" class="text-muted" style="width: 20px; height: 20px;"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark text-wrap" style="max-width: 250px;">{{ $item->product->product_name ?? 'Product Deleted' }}</div>
                                                    <div class="text-muted" style="font-size: 11px;">Store: {{ $item->product->store->store_name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold text-dark pe-4">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 👤 ផ្នែកព័ត៌មានអតិថិជន និងការដឹកជញ្ជូន (Customer & Shipping Summary) -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold small text-uppercase tracking-wider">Customer & Status</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <label class="text-muted d-block mb-1">Customer Name</label>
                        <div class="fw-bold text-dark">{{ $order->user->name ?? 'Unknown' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block mb-1">Email Address</label>
                        <div class="text-dark">{{ $order->user->email ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block mb-1">Order Status</label>
                        <div>
                            @if ($order->status == 'completed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fw-medium">Completed</span>
                            @elseif($order->status == 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill fw-medium">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill fw-medium">Processing</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill fw-medium">Cancelled</span>
                            @endif
                        </div>
                    </div>
                    <hr class="text-muted my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-medium">Order Date:</span>
                        <span class="text-dark fw-bold">{{ $order->created_at ? $order->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
