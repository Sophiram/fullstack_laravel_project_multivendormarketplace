@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Payment - Admin Panel')

@section('admin_layout')
    <div class="container-fluid py-4 px-3 px-md-4 payment-wrapper" style="background-color: #f8fafc; min-height: 100vh;">

        {{-- Custom Typography and Styles --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
            rel="stylesheet">

        <style>
            .payment-wrapper {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .font-outfit {
                font-family: 'Outfit', sans-serif;
            }

            /* Premium Grid Cards */
            .card-gateway {
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                background: #ffffff;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01);
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .card-gateway:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.04), 0 2px 6px rgba(0, 0, 0, 0.02);
                border-color: #cbd5e1;
            }

            /* Logo Display Containers */
            .logo-container {
                width: 64px;
                height: 42px;
                background: #f8fafc;
                border: 1px solid #f1f5f9;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                padding: 4px;
            }

            /* Form Elements */
            .form-control-custom,
            .form-select-custom {
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 0.7rem 1rem;
                font-size: 0.9rem;
                font-weight: 500;
                color: #1e293b;
                background-color: #f8fafc;
                transition: all 0.2s ease-in-out;
            }

            .form-control-custom:focus,
            .form-select-custom:focus {
                background-color: #ffffff;
                border-color: #6366f1;
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
                outline: none;
            }

            /* Custom Premium Buttons */
            .btn-premium {
                background: linear-gradient(135deg, #4f46e5, #3b82f6);
                color: white;
                border-radius: 12px;
                padding: 0.65rem 1.25rem;
                border: none;
                font-weight: 600;
                font-size: 0.9rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
                transition: all 0.2s ease;
            }

            .btn-premium:hover {
                background: linear-gradient(135deg, #4338ca, #2563eb);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35);
            }

            .btn-light-custom {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                color: #475569;
                border-radius: 10px;
                padding: 0.5rem 1rem;
                font-weight: 600;
                font-size: 0.85rem;
                transition: all 0.2s;
            }

            .btn-light-custom:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
                color: #0f172a;
            }

            /* Custom Smooth Form Switches */
            .form-switch .form-check-input {
                width: 2.5em;
                height: 1.35em;
                background-color: #e2e8f0;
                border-color: transparent;
                cursor: not-allowed !important;
                transition: background-position .15s ease-in-out, background-color .15s ease-in-out;
            }

            .form-switch .form-check-input:checked {
                background-color: #10b981;
            }

            /* Dialog Modals */
            .modal-custom-content {
                border: none;
                border-radius: 20px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .dynamic-section {
                transition: all 0.3s ease-in-out;
            }
        </style>

        {{-- Header Layout UI --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Payment Gateways</h3>
                <p class="text-muted small mb-0" style="font-weight: 500;">Configure and manage your e-commerce transaction
                    options and API integration modules.</p>
            </div>
            <div>
                <a href="{{ route('admin.payment.add') }}" class="btn btn-premium w-100 justify-content-center">
                    <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Add Payment Method
                </a>
            </div>
        </div>

        <h6 class="fw-bold text-secondary text-uppercase mb-4"
            style="font-size: 0.75rem; letter-spacing: 0.06em; opacity: 0.8;">
            All Registered Payment Channels
        </h6>

        {{-- Grid Data Collection Layout Pipeline --}}
        <div class="row g-4 mb-4">
            @forelse ($paymentMethods as $method)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card card-gateway p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="logo-container shadow-sm">
                                    @if ($method->logo)
                                        <img src="{{ asset('storage/' . $method->logo) }}" alt="Logo"
                                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    @else
                                        <span class="fw-bold text-primary font-outfit small text-uppercase"
                                            style="font-size: 0.8rem; letter-spacing: 0.05em;">
                                            {{ substr($method->name, 0, 3) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="form-check form-switch p-0 m-0 d-flex align-items-center">
                                    <input class="form-check-input ms-0 shadow-none status-toggle-switch" type="checkbox"
                                        role="switch" data-id="{{ $method->id }}" {{ $method->status ? 'checked' : '' }}
                                        style="opacity: 0.9; cursor: pointer;">
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark mb-1 text-truncate fs-6" title="{{ $method->name }}"
                                style="letter-spacing: -0.2px;">
                                {{ $method->name }}
                            </h6>
                            <p class="text-muted font-outfit mb-2" style="font-size: 0.78rem; font-weight: 500;">
                                <span
                                    class="d-inline-block bg-secondary bg-opacity-10 text-secondary rounded px-2 py-0.5 small text-uppercase fs-xs">
                                    {{ str_replace('_', ' ', $method->type) }}
                                </span>
                            </p>
                            @if ($method->description)
                                <p class="text-muted text-truncate small mb-0" style="max-width: 100%;">
                                    {{ $method->description }}</p>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top border-light pt-3 mt-4">
                            @if ($method->status)
                                <span
                                    class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 rounded-pill border border-success border-opacity-10 fw-semibold font-outfit"
                                    style="font-size: 0.7rem;">
                                    <span class="d-inline-block bg-success rounded-circle me-1"
                                        style="width: 5px; height: 5px; margin-bottom: 1.5px;"></span> Active
                                </span>
                            @else
                                <span
                                    class="badge bg-slate bg-opacity-10 text-secondary px-2.5 py-1.5 rounded-pill border border-secondary border-opacity-10 fw-semibold font-outfit"
                                    style="font-size: 0.7rem; background-color: #f1f5f9;">
                                    <span class="d-inline-block bg-secondary rounded-circle me-1"
                                        style="width: 5px; height: 5px; margin-bottom: 1.5px;"></span> Disabled
                                </span>
                            @endif

                            {{-- Pass structural gateway payload variables to JavaScript handler --}}
                            <button type="button" class="btn btn-light-custom d-inline-flex align-items-center gap-1.5"
                                onclick="editPayment(
                                    '{{ $method->id }}',
                                    '{{ addslashes($method->name) }}',
                                    '{{ $method->type }}',
                                    '{{ addslashes($method->description) }}',
                                    '{{ json_encode($method->credentials) }}'
                                )">
                                <i data-lucide="sliders-horizontal" style="width: 14px; height: 14px;"></i> Configure
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 py-5 text-center bg-white">
                        <div class="bg-light p-3 rounded-circle mb-3 d-inline-flex align-items-center justify-content-center mx-auto"
                            style="width: 64px; height: 64px;">
                            <i data-lucide="credit-card" class="text-secondary opacity-50"
                                style="width: 32px; height: 32px;"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">No Gateways Available</h5>
                        <p class="text-muted small mb-0">Get started by creating your system transaction processing options.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination Module Footer --}}
        @if ($paymentMethods->hasPages())
            <div class="d-flex justify-content-center pt-2 mb-4">
                {{ $paymentMethods->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif

        {{-- Configuration Modal Architecture --}}
        <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- Large modal layout optimized for dataset parameters --}}
                <div class="modal-content modal-custom-content overflow-hidden bg-white">
                    <form id="editPaymentForm" method="POST" enctype="multipart/form-data" class="m-0">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-bottom border-light py-3 px-4 bg-light bg-opacity-40">
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2"
                                style="font-size: 1.05rem; letter-spacing: -0.3px;">
                                <i data-lucide="settings-2" class="text-indigo" style="width: 20px; height: 20px;"></i>
                                Modify Gateway Settings
                            </h5>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Method Name Reference</label>
                                    <input type="text" name="name" id="edit_name"
                                        class="form-control form-control-custom" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Payment Channel Type</label>
                                    <select name="type" id="edit_type" class="form-select form-select-custom" required>
                                        <option value="direct_integration">Direct Integration (API Module)</option>
                                        <option value="manual_bank">Manual Bank Wire Node</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Description</label>
                                    <textarea name="description" id="edit_description" class="form-control form-control-custom" rows="2"></textarea>
                                </div>

                                {{-- Dynamic Edit Section: Direct Integration --}}
                                <div id="edit-direct-section" class="col-12 dynamic-section d-none">
                                    <div class="p-3 rounded-4 border bg-light bg-opacity-50">
                                        <h6 class="fw-bold mb-2 text-indigo small d-flex align-items-center gap-2">
                                            <i data-lucide="key" style="width: 14px; height: 14px;"></i> API Credentials
                                        </h6>
                                        <div class="row g-2">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label text-dark small mb-1">API Public Key</label>
                                                <input type="text" name="api_public_key" id="edit_public_key"
                                                    class="form-control form-control-custom bg-white">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label text-dark small mb-1">API Secret Key</label>
                                                <input type="password" name="api_secret_key" id="edit_secret_key"
                                                    class="form-control form-control-custom bg-white">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label text-dark small mb-1">Environment Mode</label>
                                                <select name="environment" id="edit_environment"
                                                    class="form-select form-select-custom bg-white">
                                                    <option value="sandbox">Sandbox / Testing</option>
                                                    <option value="production">Production / Live</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dynamic Edit Section: Manual Bank --}}
                                <div id="edit-manual-section" class="col-12 dynamic-section d-none">
                                    <div class="p-3 rounded-4 border bg-light bg-opacity-50">
                                        <h6 class="fw-bold mb-2 text-info small d-flex align-items-center gap-2">
                                            <i data-lucide="home" style="width: 14px; height: 14px;"></i> Bank Account /
                                            Manual Details
                                        </h6>
                                        <div class="row g-2">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label text-dark small mb-1">Account Name</label>
                                                <input type="text" name="account_name" id="edit_account_name"
                                                    class="form-control form-control-custom bg-white">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label text-dark small mb-1">Account Number</label>
                                                <input type="text" name="account_number" id="edit_account_number"
                                                    class="form-control form-control-custom bg-white">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label text-dark small mb-1">Update QR Code Image
                                                    Asset</label>
                                                <input type="file" name="qr_code"
                                                    class="form-control form-control-custom bg-white">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Update Logo Asset File</label>
                                    <input type="file" name="logo"
                                        class="form-control form-control-custom bg-white">
                                    <div class="form-text text-muted mt-1" style="font-size: 0.78rem;">
                                        Leave empty if you intend to maintain the active asset layout configuration.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="modal-footer border-top border-light py-3 px-4 d-flex justify-content-end gap-2 bg-light bg-opacity-10">
                            <button type="button" class="btn btn-light-custom px-4"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-premium px-4">
                                <i data-lucide="check" style="width: 16px; height: 16px;"></i> Save Changes
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
            const editTypeSelect = document.getElementById('edit_type');
            editTypeSelect.addEventListener('change', handleEditTypeToggle);

            const statusSwitches = document.querySelectorAll('.status-toggle-switch');
            statusSwitches.forEach(element => {
                element.addEventListener('change', function() {
                    const methodId = this.getAttribute('data-id');

                    // Forward state pipeline synchronization request via Fetch API
                    fetch(`/admin/payment/toggle-status/${methodId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Operation Successful!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true,
                                    background: '#ffffff',
                                    iconColor: '#10b981',
                                    customClass: {
                                        popup: 'rounded-4 shadow-sm border'
                                    }
                                });

                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred, please try again!');
                        });
                });
            });
        });

        const editDirectSection = document.getElementById('edit-direct-section');
        const editManualSection = document.getElementById('edit-manual-section');

        function handleEditTypeToggle() {
            const selectedValue = document.getElementById('edit_type').value;

            if (selectedValue === 'direct_integration') {
                editDirectSection.classList.remove('d-none');
                editManualSection.classList.add('d-none');
            } else if (selectedValue === 'manual_bank') {
                editManualSection.classList.remove('d-none');
                editDirectSection.classList.add('d-none');
            } else {
                editDirectSection.classList.add('d-none');
                editManualSection.classList.add('d-none');
            }
        }

        // Capture runtime gateway definitions and inject into interactive structural modal
        function editPayment(id, name, type, description, credentialsJson) {
            // Bind operational target action route to resource update node
            document.getElementById('editPaymentForm').action = '/admin/payment/update/' + id;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_description').value = description;

            // Flush interface legacy value buffers
            document.getElementById('edit_public_key').value = '';
            document.getElementById('edit_secret_key').value = '';
            document.getElementById('edit_environment').value = 'sandbox';
            document.getElementById('edit_account_name').value = '';
            document.getElementById('edit_account_number').value = '';

            // Evaluate data objects and parse payload fields safely
            if (credentialsJson && credentialsJson !== 'null' && credentialsJson !== '') {
                try {
                    const credentials = JSON.parse(credentialsJson);

                    if (type === 'direct_integration') {
                        document.getElementById('edit_public_key').value = credentials.public_key || '';
                        document.getElementById('edit_secret_key').value = credentials.secret_key || '';
                        document.getElementById('edit_environment').value = credentials.environment || 'sandbox';
                    } else if (type === 'manual_bank') {
                        document.getElementById('edit_account_name').value = credentials.account_name || '';
                        document.getElementById('edit_account_number').value = credentials.account_number || '';
                    }
                } catch (e) {
                    console.error("Error parsing credentials JSON", e);
                }
            }

            // Execute dynamic workflow layer layout toggles
            handleEditTypeToggle();

            // Instantiate layout modal viewport container
            var myModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
            myModal.show();
        }

        // Processing success alerts pipeline actions
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Gateway Configured',
                text: "{!! session('success') !!}",
                timer: 2500,
                showConfirmButton: false,
                position: 'top-end',
                background: '#ffffff',
                iconColor: '#10b981',
                toast: true,
                customClass: {
                    popup: 'rounded-4 shadow-sm border'
                }
            });
        @endif
    </script>
@endsection
