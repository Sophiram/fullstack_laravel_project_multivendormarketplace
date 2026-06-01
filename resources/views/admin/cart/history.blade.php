@extends('admin.layouts.layout')

@section('admin_page_title', 'History Cart - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">History Cart Management</h4>
                <p class="text-muted small mb-0">Review and manage customer cart history and abandoned checkouts.</p>
            </div>

            <div class="d-flex">
                <a href="{{ route('admin.cart.export', request()->query()) }}"
                    class="btn btn-success btn-sm rounded-3 w-100 w-md-auto d-inline-flex align-items-center justify-content-center gap-1.5 fw-semibold shadow-sm">
                    <i data-lucide="file-spreadsheet" style="width: 15px; height: 15px;"></i> Export Excel
                </a>
            </div>
        </div>

        {{-- Stats Cards Matrices Panel --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <small class="text-muted fw-bold text-uppercase d-block mb-1"
                        style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Abandoned</small>
                    <h4 class="fw-bold text-danger mb-0">{{ number_format($totalAbandoned) }}</h4>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <small class="text-muted fw-bold text-uppercase d-block mb-1"
                        style="font-size: 0.72rem; letter-spacing: 0.05em;">Converted to Order</small>
                    <h4 class="fw-bold text-success mb-0">{{ number_format($totalConverted) }}</h4>
                </div>
            </div>
        </div>

        {{-- Data Filter Control Architecture --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <form action="{{ route('admin.cart.history') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted px-2.5">
                                <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                            </span>
                            <input type="text" name="search"
                                class="form-control bg-light border-start-0 small rounded-end-3 py-1.5"
                                placeholder="Search customer records..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-auto">
                        <select name="status"
                            class="form-select form-select-sm bg-light text-muted rounded-3 py-1.5 fw-medium"
                            onchange="this.form.submit()">
                            <option value="">All Matrix Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Shopping
                                Cart</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Converted
                                Checkout</option>
                            <option value="abandoned" {{ request('status') == 'abandoned' ? 'selected' : '' }}>Abandoned
                                Sessions</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- Responsive Core Data Table Grid --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold">Cart ID</th>
                            <th class="py-3 text-muted fw-bold">Customer Profile</th>
                            <th class="py-3 text-muted fw-bold">Items Count</th>
                            <th class="py-3 text-muted fw-bold">Total Valuation</th>
                            <th class="py-3 text-muted fw-bold">Status</th>
                            <th class="py-3 text-muted fw-bold">Last Updated</th>
                            <th class="pe-4 py-3 text-end text-muted fw-bold" style="width: 130px;">Action Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carts as $cart)
                            @php
                            $statusClass = [
                            'converted' => 'bg-success-subtle text-success border border-success-subtle',
                            'abandoned' => 'bg-danger-subtle text-danger border border-danger-subtle',
                            'active' => 'bg-warning-subtle text-warning border border-warning-subtle',
                            ][$cart->status] ?? 'bg-secondary-subtle text-secondary border border-secondary-subtle';
                            @endphp
                            <tr>
                                <td class="ps-4 fw-bold font-monospace text-secondary">#CRT-{{ $cart->id }}</td>
                                <td>
                                    <span
                                        class="d-block fw-semibold text-dark">{{ $cart->user->name ?? 'Guest Session' }}</span>
                                    <small class="text-muted d-block"
                                        style="font-size: 0.75rem;">{{ $cart->user->email ?? 'N/A' }}</small>
                                </td>
                                <td class="text-secondary fw-medium">{{ $cart->items_count }} Items</td>
                                <td class="fw-bold text-dark">${{ number_format($cart->total_amount, 2) }}</td>
                                <td>
                                    <span
                                        class="badge rounded-pill px-2.5 py-1.5 text-uppercase font-monospace fw-semibold {{ $statusClass }}"
                                        style="font-size: 0.72rem;">
                                        {{ $cart->status }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $cart->updated_at->format('d M, Y') }}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1.5">
                                        <button type="button"
                                            class="btn btn-light btn-sm border rounded-2 text-warning p-2 d-inline-flex align-items-center"
                                            onclick="openEditModal('{{ $cart->id }}', '{{ $cart->status }}')"
                                            title="Edit Status">
                                            <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                        </button>

                                        <form action="{{ route('admin.cart.delete', $cart->id) }}" method="POST"
                                            id="delete-form-{{ $cart->id }}" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-light btn-sm border rounded-2 text-danger p-2 d-inline-flex align-items-center"
                                                onclick="confirmDelete('{{ $cart->id }}')" title="Purge Record">
                                                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted small">No structural cart logs
                                    available inside system records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top border-light py-3">
                {{ $carts->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Edit Operational Status Modal Component --}}
    <div class="modal fade" id="editCartModal" tabindex="-1" aria-labelledby="editCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editCartForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-bottom border-light px-4 py-3">
                        <h6 class="modal-title fw-bold text-dark" id="editCartModalLabel">Edit Session Lifecycle Status</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-1">
                            <label class="form-label small fw-bold text-secondary">Lifecycle Pipeline Status</label>
                            <select name="status" id="edit-status" class="form-select rounded-3" required>
                                <option value="active">Active Shopping Cart</option>
                                <option value="completed">Converted Checkout Logs</option>
                                <option value="abandoned">Abandoned Session Data</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-light px-4 py-2.5 bg-light-subtle">
                        <button type="button"
                            class="btn btn-light border btn-sm rounded-3 px-3 fw-semibold text-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold">Save
                            Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Render Structural Vector Graphics Icons Engine
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Execution Handler for Destructive Delete Sequences
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you absolute sure?',
                text: "This process cannot be reverted via database nodes!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, purge records!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Trigger Modal Open Execution Context Runtime
        function openEditModal(id, currentStatus) {
            const form = document.getElementById('editCartForm');
            form.action = '/admin/cart/update/' + id; // Ensures configuration matches matching routes

            document.getElementById('edit-status').value = currentStatus;
            new bootstrap.Modal(document.getElementById('editCartModal')).show();
        }

        // Catch and process success operations response pipelines
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Operation Successful!',
                text: "{!! session('success') !!}",
                confirmButtonColor: '#3b82f6',
                timer: 2500
            });
        @endif
    </script>
@endsection
