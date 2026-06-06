@extends('admin.layouts.layout')

@section('admin_page_title', 'Add Payment Method - Admin Panel')

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

            /* Premium Cards */
            .card-custom {
                border: none;
                border-radius: 20px;
                background: #ffffff;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
            }

            /* Modern Form Inputs */
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

            .form-control-custom::placeholder {
                color: #94a3b8;
                font-weight: 400;
            }

            .form-control-custom:focus,
            .form-select-custom:focus {
                background-color: #ffffff;
                border-color: #6366f1;
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
                outline: none;
            }

            /* File Input Customization */
            .form-control-custom[type="file"]::file-selector-button {
                background-color: #f1f5f9;
                border: none;
                border-right: 1px solid #e2e8f0;
                color: #475569;
                font-weight: 600;
                padding: 0.5rem 1rem;
                margin-right: 1rem;
                border-radius: 8px 0 0 8px;
                transition: background-color 0.2s;
                cursor: pointer;
            }

            .form-control-custom[type="file"]::file-selector-button:hover {
                background-color: #e2e8f0;
            }

            /* Buttons */
            .btn-premium {
                background: linear-gradient(135deg, #4f46e5, #3b82f6);
                color: white;
                border-radius: 12px;
                padding: 0.7rem 1.5rem;
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
                border-radius: 12px;
                padding: 0.7rem 1.2rem;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.2s;
            }

            .btn-light-custom:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
                color: #0f172a;
            }

            /* Error Alert */
            .alert-custom-danger {
                background-color: #fef2f2;
                border: 1px solid #fecaca;
                border-left: 4px solid #ef4444;
                color: #991b1b;
            }

            .dynamic-section {
                transition: all 0.3s ease-in-out;
            }
        </style>

        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Add New Payment Method</h3>
                <p class="text-muted small mb-0" style="font-weight: 500;">Configure a new payment gateway to scale system
                    checkout options.</p>
            </div>
            <div>
                <a href="{{ route('admin.payment.manage') }}"
                    class="btn btn-light-custom d-inline-flex align-items-center gap-2 w-100 justify-content-center">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> Back to Methods
                </a>
            </div>
        </div>

        {{-- Validation Error Pipeline Logs --}}
        @if ($errors->any())
            <div class="alert alert-custom-danger shadow-sm rounded-4 p-4 mb-4 d-flex align-items-start gap-3">
                <span class="text-danger mt-1 d-inline-flex bg-danger bg-opacity-10 p-2 rounded-circle">
                    <i data-lucide="alert-octagon" style="width: 20px; height: 20px;"></i>
                </span>
                <div class="small">
                    <span class="d-block mb-2 fw-bolder fs-6">Configuration Validation Failed</span>
                    <ul class="mb-0 ps-3 text-danger opacity-75 fw-medium">
                        @foreach ($errors->all() as $error)
                            <li class="mb-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Core Configuration Form Input Architecture --}}
        <div class="card card-custom overflow-hidden">
            <form action="{{ route('admin.payment.store') }}" method="POST" enctype="multipart/form-data" class="m-0">
                @csrf
                <div class="card-body p-4 p-md-5">

                    <h6 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                            <i data-lucide="settings" style="width: 18px; height: 18px;"></i>
                        </div>
                        Gateway Settings
                    </h6>

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label text-dark fw-bold small mb-2">Method Name Reference</label>
                            <input type="text" name="name" class="form-control form-control-custom"
                                placeholder="e.g., ABA Pay, Wing Bank, Cash on Delivery" required>
                            <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">The custom structural gateway
                                label displayed to customers during checkout.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label text-dark fw-bold small mb-2">Payment Integration Type</label>
                            <select name="type" id="payment-type" class="form-select form-select-custom" required>
                                <option value="" disabled selected>Select execution mode channel...</option>
                                <option value="direct_integration">Direct Integration (API Module)</option>
                                <option value="manual_bank">Manual Bank Wire Node / COD</option>
                            </select>
                            <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">Select the backend runtime
                                execution and verification integration mechanism.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-dark fw-bold small mb-2">Description</label>
                            <textarea name="description" class="form-control form-control-custom" rows="3"
                                placeholder="Enter payment instructions or details for customers..."></textarea>
                            <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">This description will be
                                displayed to users at checkout.</div>
                        </div>

                        {{-- Dynamic Section: Direct Integration (API) --}}
                        <div id="direct-integration-section" class="col-12 dynamic-section d-none">
                            <div class="p-4 rounded-4 border bg-light bg-opacity-50">
                                <h6 class="fw-bold mb-3 text-indigo d-flex align-items-center gap-2">
                                    <i data-lucide="key" style="width: 16px; height: 16px;"></i> API Credentials
                                    Configuration
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label text-dark fw-bold small mb-2">API Public Key</label>
                                        <input type="text" name="api_public_key"
                                            class="form-control form-control-custom bg-white" placeholder="pk_live_...">
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <label class="form-label text-dark fw-bold small mb-2">API Secret Key</label>
                                        <input type="password" name="api_secret_key"
                                            class="form-control form-control-custom bg-white"
                                            placeholder="Leave blank to use default Token from .env">
                                        <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">
                                            If left blank, the system will use the `KHQR_TOKEN` value from the .env file.
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <label class="form-label text-dark fw-bold small mb-2">Environment Mode</label>
                                        <select name="environment" class="form-select form-select-custom bg-white">
                                            <option value="sandbox">Sandbox / Testing</option>
                                            <option value="production">Production / Live</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Section: Manual Bank Wire / QR / COD --}}
                        <div id="manual-bank-section" class="col-12 dynamic-section d-none">
                            <div class="p-4 rounded-4 border bg-light bg-opacity-50">
                                <h6 class="fw-bold mb-3 text-info d-flex align-items-center gap-2">
                                    <i data-lucide="home" style="width: 16px; height: 16px;"></i> Bank Account / Manual
                                    Details
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label text-dark fw-bold small mb-2">Account Name</label>
                                        <input type="text" name="account_name"
                                            class="form-control form-control-custom bg-white"
                                            placeholder="e.g., CHAN VANNAK">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label text-dark fw-bold small mb-2">Account Number</label>
                                        <input type="text" name="account_number"
                                            class="form-control form-control-custom bg-white"
                                            placeholder="e.g., 000 111 222">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-dark fw-bold small mb-2">Upload QR Code Image
                                            Asset</label>
                                        <input type="file" name="qr_code"
                                            class="form-control form-control-custom bg-white">
                                        <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">Required for QR
                                            code scanning methods. Skip for Cash on Delivery (COD).</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- General Logo Upload --}}
                        <div class="col-12">
                            <div class="p-4 rounded-4" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                                <label class="form-label text-dark fw-bold small mb-2">Upload Logo Asset File</label>
                                <input type="file" name="logo" class="form-control form-control-custom bg-white">
                                <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">
                                    <i data-lucide="info" class="d-inline-block align-text-bottom me-1"
                                        style="width: 14px; height: 14px;"></i>
                                    Optimize clear micro dimensions asset (JPG, PNG) recommended for UI processing.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="card-footer bg-white border-top p-4 d-flex flex-column flex-md-row justify-content-end gap-3 text-end">
                    <a href="{{ route('admin.payment.manage') }}"
                        class="btn btn-light-custom text-center order-2 order-md-1">
                        Cancel Changes
                    </a>
                    <button type="submit" class="btn btn-premium order-1 order-md-2">
                        <i data-lucide="save" style="width: 18px; height: 18px;"></i>
                        Save Payment Method
                    </button>
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

            // Logic សម្រាប់ Toggle លាក់/បង្ហាញ ទៅតាមប្រភេទលំហូរទូទាត់
            const paymentTypeSelect = document.getElementById('payment-type');
            const directSection = document.getElementById('direct-integration-section');
            const manualSection = document.getElementById('manual-bank-section');

            function handlePaymentTypeToggle() {
                const selectedValue = paymentTypeSelect.value;

                if (selectedValue === 'direct_integration') {
                    directSection.classList.remove('d-none');
                    manualSection.classList.add('d-none');
                } else if (selectedValue === 'manual_bank') {
                    manualSection.classList.remove('d-none');
                    directSection.classList.add('d-none');
                } else {
                    directSection.classList.add('d-none');
                    manualSection.classList.add('d-none');
                }
            }

            paymentTypeSelect.addEventListener('change', handlePaymentTypeToggle);

            // ហៅដំបូងដើម្បីផ្ទៀងផ្ទាត់ករណីមានតម្លៃចាស់ (Old value) ពេល Validation លោតខុស
            handlePaymentTypeToggle();
        });

        // Catch and process flash success operations pipeline records
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
