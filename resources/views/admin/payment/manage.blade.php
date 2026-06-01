@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Payment - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Payment Methods & Gateways</h4>
                <p class="text-muted small mb-0">Configure and manage your e-commerce transaction options and api integration
                    modules.</p>
            </div>
            <div>
                <a href="{{ route('admin.payment.add') }}"
                    class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm w-100">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Add Payment Method
                </a>
            </div>
        </div>

        <h6 class="fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">All Active
            & Inactive Gateways</h6>

        <div class="row g-3 mb-4">
            @foreach ($paymentMethods as $method)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div
                        class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white d-flex flex-column justify-content-between border-top border-3 border-{{ $method->status ? 'success' : 'light' }}">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-light p-1.5 rounded-3 border d-flex align-items-center justify-content-center bg-white shadow-inner"
                                    style="width: 60px; height: 38px; overflow: hidden;">
                                    @if ($method->logo)
                                        <img src="{{ asset('storage/' . $method->logo) }}" alt="Logo"
                                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    @else
                                        <span class="fw-bold text-primary font-monospace small"
                                            style="font-size: 0.75rem;">{{ strtoupper(substr($method->name, 0, 3)) }}</span>
                                    @endif
                                </div>
                                <div class="form-check form-switch p-0 m-0">
                                    <input class="form-check-input ms-0 shadow-none cursor-not-allowed" type="checkbox"
                                        role="switch" {{ $method->status ? 'checked' : '' }} disabled>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $method->name }}">{{ $method->name }}
                            </h6>
                            <p class="text-muted font-monospace mb-0" style="font-size: 0.75rem;">
                                {{ ucfirst(str_replace('_', ' ', $method->type)) }}</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top border-light pt-2.5 mt-3">
                            <span
                                class="badge rounded-pill px-2 py-1 font-monospace fw-semibold bg-{{ $method->status ? 'success' : 'secondary' }}-subtle text-{{ $method->status ? 'success' : 'secondary' }} border border-{{ $method->status ? 'success' : 'secondary' }}-subtle"
                                style="font-size: 0.68rem;">
                                {{ $method->status ? 'Active' : 'Disabled' }}
                            </span>
                            <button type="button"
                                class="btn btn-sm btn-light border rounded-2 text-secondary px-2.5 py-1.5 d-inline-flex align-items-center gap-1 small fw-medium"
                                onclick="editPayment('{{ $method->id }}', '{{ $method->name }}', '{{ $method->type }}')">
                                <i data-lucide="settings" style="width: 13px; height: 13px;"></i> Configure
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-footer bg-transparent border-0 py-2 mb-4">
            {{ $paymentMethods->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

        <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                    <form id="editPaymentForm" method="POST" enctype="multipart/form-data" class="m-0">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-light border-bottom py-3 px-4">
                            <h5 class="modal-title fw-bold text-dark" style="font-size: 1.05rem;">Modify System Gateway</h5>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold small">Method Name Reference</label>
                                <input type="text" name="name" id="edit_name"
                                    class="form-control bg-light border-0 py-2 small" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold small">Payment Channel Type</label>
                                <select name="type" id="edit_type" class="form-select bg-light border-0 py-2 small"
                                    required>
                                    <option value="direct_integration">Direct Integration (API Module)</option>
                                    <option value="manual_bank">Manual Bank Wire Node</option>
                                </select>
                            </div>

                            <div class="mb-0">
                                <label class="form-label text-dark fw-bold small">Update Logo Asset File</label>
                                <input type="file" name="logo" class="form-control bg-light border-0 py-2 small">
                                <div class="form-text text-muted mt-1" style="font-size: 0.75rem;">Leave empty if you intend
                                    to maintain active asset configuration placeholder.</div>
                            </div>
                        </div>
                        <div class="modal-header bg-light border-top py-3 px-4 d-flex justify-content-end gap-2">
                            <button type="button"
                                class="btn btn-sm btn-light border rounded-3 px-3 fw-semibold text-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-3 px-3 fw-semibold d-inline-flex align-items-center gap-1">
                                <i data-lucide="save" style="width: 14px; height: 14px;"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

        // Trigger Configuration and Setup Target Form Environment Elements
        function editPayment(id, name, type) {
            document.getElementById('editPaymentForm').action = '/admin/payment/update/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;

            var myModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
            myModal.show();
        }

        // Processing success alerts pipeline actions
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Configuration Updated',
                text: "{!! session('success') !!}",
                timer: 2000,
                showConfirmButton: false,
                position: 'center',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow'
                }
            });
        @endif
    </script>
@endsection
