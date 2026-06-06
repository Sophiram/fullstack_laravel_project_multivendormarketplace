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
                                    <th class="py-3">Shipping Info</th>
                                    <th class="py-3">Your Sales</th>
                                    <th class="py-3">Net Earnings</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Date Ordered</th>
                                    <th class="pe-4 py-3 text-end" style="width: 140px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="small text-dark">
                                @forelse ($orders as $order)
                                    @php
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
                                                $stores = $order->items
                                                    ->map(fn($item) => $item->product->store->store_name ?? null)
                                                    ->filter()
                                                    ->unique();
                                            @endphp
                                            <div class="d-flex flex-wrap gap-1"
                                                style="max-width: 200px; white-space: normal;">
                                                @forelse($stores as $storeName)
                                                    <span
                                                        class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $storeName }}</span>
                                                @empty
                                                    <span
                                                        class="badge bg-light text-muted border fw-medium px-2 py-1">N/A</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>
                                            @if ($order->shipping)
                                                <div class="small">
                                                    <strong>{{ $order->shipping->shipping_company }}</strong><br>
                                                    <span
                                                        class="text-primary">{{ $order->shipping->tracking_number }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted small">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-secondary">${{ number_format($vendorSales, 2) }}</td>
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
                                            {{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <!-- ជំនួសកន្លែង Action នេះ -->
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end align-items-center gap-2">
                                                <a href="{{ route('vendor.ordershow', $order->id) }}"
                                                    class="btn btn-sm btn-light border shadow-none d-flex align-items-center justify-content-center"
                                                    title="View Details" style="width: 32px; height: 32px;">
                                                    <i class="align-middle text-secondary" data-feather="eye"
                                                        style="width: 15px; height: 15px;"></i>
                                                </a>

                                                <form action="{{ route('vendor.orders.updateStatus', $order->id) }}"
                                                    method="POST" class="m-0">
                                                    @csrf
                                                    <select name="status" class="form-select form-select-sm shadow-none"
                                                        onchange="handleStatusChange(this, '{{ $order->id }}')"
                                                        style="width: 120px;">
                                                        <option value="pending"
                                                            {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                                        </option>
                                                        <option value="processing"
                                                            {{ $order->status == 'processing' ? 'selected' : '' }}>
                                                            Processing</option>
                                                        <option value="shipped"
                                                            {{ $order->status == 'shipped' ? 'selected' : '' }}
                                                            {{ $order->shipping ? 'disabled' : '' }}>Shipped</option>
                                                        <option value="completed"
                                                            {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                                        </option>
                                                        <option value="cancelled"
                                                            {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                                        </option>
                                                    </select>
                                                </form>

                                                <div class="modal fade" id="shipModal{{ $order->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form
                                                            action="{{ route('vendor.orders.updateStatus', $order->id) }}"
                                                            method="POST" class="modal-content">
                                                            @csrf
                                                            <input type="hidden" name="status" value="shipped">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Shipping Details</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <select name="shipping_company_id" class="form-select"
                                                                    required
                                                                    onchange="updateShippingCost(this, '{{ $order->id }}')">
                                                                    <option value="">-- Select Shipping Company --
                                                                    </option>
                                                                    @foreach ($shippingCompanies as $company)
                                                                        <option value="{{ $company->id }}">
                                                                            {{ $company->name }}
                                                                            (${{ number_format($company->shipping_fee, 2) }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <!-- បន្ថែម onclick ឱ្យបញ្ជូន orderId ចូល -->
                                                                <input name="tracking_number"
                                                                    id="tracking_input_{{ $order->id }}"
                                                                    class="form-control mt-2" placeholder="Tracking Number">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    onclick="generateTracking('{{ $order->id }}')">Generate
                                                                    Auto</button>


                                                                <input type="number" name="shipping_cost"
                                                                    class="form-control mt-2" placeholder="Shipping Cost"
                                                                    step="0.01">
                                                                <textarea name="notes" class="form-control mt-2" placeholder="Notes..."></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Confirm
                                                                    Shipping</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>



                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <!-- 🟢 កែតម្រូវត្រង់នេះទៅជា 9 ដើម្បីឱ្យត្រូវនឹងជួរឈរសរុប -->
                                        <td colspan="9" class="text-center py-5 text-muted">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#0d6efd',
                    timer: 3000
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc3545',
                });
            @endif
        });

        function showShipModal(orderId) {
            var myModal = new bootstrap.Modal(document.getElementById('shipModal' + orderId));
            myModal.show();
        }

        function handleStatusChange(selectElement, orderId) {
            if (selectElement.value === 'shipped') {
                var myModal = new bootstrap.Modal(document.getElementById('shipModal' + orderId));
                myModal.show();
            } else {
                selectElement.form.submit();
            }
        }

        // បន្ថែមមុខងារនេះ ដើម្បី reset select វិញ បើអ្នកប្រើបិទ Modal
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                // រក select ដែលទាក់ទងនឹង order ID នេះ ហើយកំណត់វាត្រឡប់ទៅតម្លៃដើម
                location.reload(); // ងាយស្រួលបំផុតគឺ refresh ដើម្បីឱ្យ select ត្រឡប់ទៅ status ដើម
            });
        });

        function generateTracking(orderId) {
            const randomCode = 'TRK-' + Math.random().toString(36).substr(2, 9).toUpperCase();
            // ជ្រើសរើស input តាម ID ឱ្យចំ order នោះ
            document.getElementById('tracking_input_' + orderId).value = randomCode;
        }
        const shippingFees = {
            @foreach ($shippingCompanies as $company)
                "{{ $company->id }}": {{ $company->shipping_fee }},
            @endforeach
        };

        function updateShippingCost(selectElement, orderId) {
            const costInput = document.querySelector(`#shipModal${orderId} input[name="shipping_cost"]`);
            const selectedId = selectElement.value;

            // បញ្ចូលតម្លៃពី Object ទៅក្នុង Input
            if (shippingFees[selectedId]) {
                costInput.value = shippingFees[selectedId].toFixed(2);
            } else {
                costInput.value = '';
            }
        }
    </script>
@endsection
