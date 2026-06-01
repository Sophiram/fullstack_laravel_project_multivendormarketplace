@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Discount - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manage Discounts</h4>
                <p class="text-muted small mb-0">Track, edit, and manage all your store promotional coupons and offers.</p>
            </div>

            <div class="d-flex">
                <a href="{{ route('admin.discount.create') }}"
                    class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold w-100 w-md-auto d-inline-flex align-items-center justify-content-center gap-1.5 shadow-sm">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Create New Discount
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-light-subtle">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <i data-lucide="ticket" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block mb-0"
                                style="font-size: 0.72rem; letter-spacing: 0.5px;">Active Coupons</small>
                            <h4 class="fw-bold text-dark mb-0">12 Codes</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-light-subtle">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <i data-lucide="trending-up" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block mb-0"
                                style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Revenue Saved</small>
                            <h4 class="fw-bold text-dark mb-0">$3,450.20</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-light-subtle">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <i data-lucide="clock" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block mb-0"
                                style="font-size: 0.72rem; letter-spacing: 0.5px;">Expiring Soon</small>
                            <h4 class="fw-bold text-dark mb-0">3 Codes</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <div class="row g-2 align-items-center justify-content-between">
                    <div class="col-12 col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i data-lucide="search" style="width: 15px; height: 15px;"></i>
                            </span>
                            <input type="text" class="form-control bg-light border-start-0"
                                placeholder="Search by coupon code or title...">
                        </div>
                    </div>
                    <div class="col-12 col-md-auto d-flex gap-2">
                        <select class="form-select form-select-sm bg-light text-secondary">
                            <option value="">Filter Type</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed_amount">Fixed Amount</option>
                            <option value="free_shipping">Free Shipping</option>
                        </select>
                        <select class="form-select form-select-sm bg-light text-secondary">
                            <option value="">Filter Status</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold">Title & Code</th>
                            <th class="py-3 text-muted fw-bold">Type</th>
                            <th class="py-3 text-muted fw-bold">Value</th>
                            <th class="py-3 text-muted fw-bold">Usage Limit</th>
                            <th class="py-3 text-muted fw-bold">Duration</th>
                            <th class="py-3 text-muted fw-bold">Status</th>
                            <th class="pe-4 py-3 text-end text-muted fw-bold" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($discounts as $discount)
                            <tr>
                                <td class="ps-4">
                                    <span class="d-block fw-semibold text-dark mb-0.5">{{ $discount->title }}</span>
                                    <span
                                        class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 font-monospace text-uppercase"
                                        style="font-size: 0.7rem;">{{ $discount->code }}</span>
                                </td>
                                <td class="text-secondary">{{ str_replace('_', ' ', ucfirst($discount->type)) }}</td>
                                <td class="fw-semibold text-dark">
                                    {{ $discount->value }}{{ $discount->type == 'percentage' ? '%' : '$' }} Off
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium">
                                        {{ $discount->usage_count ?? 0 }} / {{ $discount->usage_limit_total ?? '∞' }}
                                    </div>
                                </td>
                                <td>
                                    <small class="d-block text-secondary">Start: {{ $discount->start_date }}</small>
                                    <small class="d-block text-muted style-numeric" style="font-size: 0.75rem;">End:
                                        {{ $discount->end_date ?? 'Unlimited' }}</small>
                                </td>
                                <td>{!! $discount->status_badge !!}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1.5">
                                        <button type="button"
                                            class="btn btn-light btn-sm border rounded-2 text-primary p-2 d-inline-flex align-items-center edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editDiscountModal"
                                            data-route="{{ route('admin.discount.update', $discount->id) }}"
                                            data-title="{{ $discount->title }}" data-code="{{ $discount->code }}"
                                            data-value="{{ $discount->value }}" data-status="{{ $discount->status }}"
                                            title="Edit Promo">
                                            <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                        </button>

                                        <form action="{{ route('admin.discount.destroy', $discount->id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-light btn-sm border rounded-2 text-danger p-2 d-inline-flex align-items-center delete-btn"
                                                title="Delete Promo">
                                                <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted small">No discounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="card-footer bg-white py-3 border-top border-light d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
                <small class="text-muted">
                    Showing {{ $discounts->firstItem() ?? 0 }} to {{ $discounts->lastItem() ?? 0 }} of
                    {{ $discounts->total() }} entries
                </small>
                <nav aria-label="Discount pagination">
                    {{ $discounts->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDiscountModal" tabindex="-1" aria-labelledby="editDiscountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <form id="editDiscountForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom border-light px-4 py-3">
                        <h6 class="modal-title fw-bold text-dark" id="editDiscountModalLabel">Modify Promotional Discount
                            Configuration</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Discount Title</label>
                                <input type="text" name="title" id="edit_title" class="form-control rounded-3"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Coupon Code</label>
                                <input type="text" name="code" id="edit_code"
                                    class="form-control rounded-3 font-monospace text-uppercase" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Discount Value Rate</label>
                                <input type="number" step="0.01" name="value" id="edit_value"
                                    class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Operational Status</label>
                                <select name="status" id="edit_status" class="form-select rounded-3">
                                    <option value="1">Active</option>
                                    <option value="0">Expired / Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-light px-4 py-2.5 bg-light-subtle">
                        <button type="button"
                            class="btn btn-light border btn-sm rounded-3 px-3 fw-semibold text-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold">Update
                            Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Initialize Lucide Icons Vector Engine
            lucide.createIcons();

            // Edit Modal Event Handler Matrix Mapping
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('editDiscountForm').action = this.getAttribute(
                        'data-route');
                    document.getElementById('edit_title').value = this.getAttribute('data-title');
                    document.getElementById('edit_code').value = this.getAttribute('data-code');
                    document.getElementById('edit_value').value = this.getAttribute('data-value');
                    document.getElementById('edit_status').value = this.getAttribute('data-status');
                });
            });

            // SweetAlert Confirmation Monitoring Engine
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This removal action cannot be undone and will revoke this code instantly!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
