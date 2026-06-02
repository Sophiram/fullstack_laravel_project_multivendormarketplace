@extends('vendor.layouts.layout')

@section('vendor_page_title')
    Order History - Vendor Panel
@endsection

@section('vendor_layout')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-1">Order History</h3>
            <p class="text-muted small">Manage and track all customer orders placed from your stores.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div
                    class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap g-2">
                    <h5 class="card-title mb-0 fw-bold text-dark small text-uppercase tracking-wider">
                        Recent Orders
                    </h5>
                    <div class="col-auto">
                        <form action="{{ route('vendor.orders.history') }}" method="GET">
                            <input type="text" name="search" class="form-control form-control-sm shadow-none"
                                value="{{ request('search') }}" placeholder="Search Order ID...">
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light text-secondary small text-uppercase font-weight-bold">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 120px;">Order ID</th>
                                    <th class="py-3">Customer</th>
                                    <th class="py-3">Store Name</th>
                                    <th class="py-3">Your Sales</th>
                                    <th class="py-3">Net Earnings (-Comm.)</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Date Ordered</th>
                                    <th class="pe-4 py-3 text-end" style="width: 140px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="small text-dark">
                                @forelse ($orders as $order)
                                    @php
                                        // 🟢 គណនាទឹកប្រាក់លក់សរុប និង ចំណូលសុទ្ធរបស់ Vendor ម្នាក់នេះក្នុង Order នេះ
                                        $vendorSales = $order->items->sum(function ($item) {
                                            return $item->price * $item->quantity;
                                        });
                                        $vendorNet = $order->items->sum('vendor_net_amount');
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">#{{ $order->order_number }}</td>

                                        <td>
                                            <div class="fw-semibold">{{ $order->user->name ?? 'Unknown Customer' }}</div>
                                            <div class="text-muted" style="font-size: 11px;">{{ $order->user->email ?? '' }}
                                            </div>
                                        </td>

                                        <td>
                                            @php
                                                // ទាញយកឈ្មោះហាងទាំងអស់របស់ Vendor នេះដែលមាននៅក្នុង Order នេះ
                                                $stores = $order->items
                                                    ->map(function ($item) {
                                                        return $item->product->store->store_name ?? null;
                                                    })
                                                    ->filter()
                                                    ->unique();
                                            @endphp

                                            <!-- 🟢 បន្ថែម d-flex flex-wrap និង gap-1 ដើម្បីឱ្យវាហូរតាមជួរអេក្រង់ដោយស្វ័យប្រវត្តិ និងមានគម្លាតស្អាត -->
                                            <div class="d-flex flex-wrap gap-1"
                                                style="max-width: 200px; white-space: normal;">
                                                @forelse($stores as $storeName)
                                                    <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                                        {{ $storeName }}
                                                    </span>
                                                @empty
                                                    <span
                                                        class="badge bg-light text-muted border fw-medium px-2 py-1">N/A</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <!-- 🟢 បង្ហាញតម្លៃលក់សរុបជារបស់ Vendor នេះ -->
                                        <td class="fw-bold text-secondary">${{ number_format($vendorSales, 2) }}</td>

                                        <!-- 🟢 បង្ហាញទឹកប្រាក់សុទ្ធដែលទទួលបាន (ក្រោយកាត់ % ក្រុមហ៊ុនរួច) -->
                                        <td class="fw-bold text-success">${{ number_format($vendorNet, 2) }}</td>

                                        <td>
                                            @if ($order->status == 'completed')
                                                <span
                                                    class="badge bg-soft-success text-success border border-success-subtle px-2 py-1 rounded-pill">Completed</span>
                                            @elseif($order->status == 'pending')
                                                <span
                                                    class="badge bg-soft-warning text-warning border border-warning-subtle px-2 py-1 rounded-pill">Pending</span>
                                            @elseif($order->status == 'processing')
                                                <span
                                                    class="badge bg-soft-info text-info border border-info-subtle px-2 py-1 rounded-pill">Processing</span>
                                            @elseif($order->status == 'shipped')
                                                <span
                                                    class="badge bg-soft-primary text-primary border border-primary-subtle px-2 py-1 rounded-pill">Shipped</span>
                                            @else
                                                <span
                                                    class="badge bg-soft-danger text-danger border border-danger-subtle px-2 py-1 rounded-pill">Cancelled</span>
                                            @endif
                                        </td>

                                        <td class="text-muted">
                                            {{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}
                                        </td>

                                        <!-- 🟢 កែប្រែដោយបន្ថែម d-flex justify-content-end លើយុថ្កា (A tag) ឬ TD -->
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end align-items-center gap-2">
                                                <!-- View Details Button -->
                                                <a href="{{ route('vendor.ordershow', $order->id) }}"
                                                    class="btn btn-sm btn-light border shadow-none d-flex align-items-center justify-content-center"
                                                    title="View Details" style="width: 32px; height: 32px;">
                                                    <i class="align-middle text-secondary" data-feather="eye"
                                                        style="width: 15px; height: 15px;"></i>
                                                </a>

                                                <!-- Edit Status Dropdown Form -->
                                                <form action="{{ route('vendor.orders.updateStatus', $order->id) }}"
                                                    method="POST" class="m-0">
                                                    @csrf
                                                    <select name="status" class="form-select form-select-sm shadow-none"
                                                        onchange="this.form.submit()" style="width: 120px;">
                                                        <option value="pending"
                                                            {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                                        </option>
                                                        <option value="processing"
                                                            {{ $order->status == 'processing' ? 'selected' : '' }}>
                                                            Processing</option>
                                                        <option value="shipped"
                                                            {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                                        </option>
                                                        <option value="completed"
                                                            {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                                        </option>
                                                        <option value="cancelled"
                                                            {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                                        </option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>

                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="align-middle d-block mb-2 fs-3 text-secondary"
                                                data-feather="inbox"></i>
                                            No orders found yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap g-2 small text-muted">
                        <div>
                            @if (method_exists($orders, 'firstItem'))
                                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of
                                {{ $orders->total() ?? 0 }} entries
                            @else
                                Showing {{ $orders->count() }} entries
                            @endif
                        </div>
                        <div>
                            @if (method_exists($orders, 'links'))
                                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-success {
            background-color: #e8f5e9 !important;
        }

        .bg-soft-warning {
            background-color: #fff3e0 !important;
        }

        .bg-soft-info {
            background-color: #e0f7fa !important;
        }

        .bg-soft-primary {
            background-color: #e3f2fd !important;
        }

        .bg-soft-danger {
            background-color: #ffebee !important;
        }

        .text-success {
            color: #2e7d32 !important;
        }

        .text-warning {
            color: #ef6c00 !important;
        }

        .text-info {
            color: #00838f !important;
        }

        .text-primary {
            color: #0d47a1 !important;
        }

        .text-danger {
            color: #c62828 !important;
        }

        .pagination {
            margin-bottom: 0;
            font-size: 13px;
        }
    </style>
@endsection
