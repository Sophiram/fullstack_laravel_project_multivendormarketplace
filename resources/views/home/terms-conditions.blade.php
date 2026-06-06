@extends('layouts.user')

@section('home')
    <div class="container-fluid container-md py-5 px-3 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <!-- 📌 ផ្នែក Header: ប្តូរមកដាក់ក្នុង Column នេះដើម្បីឱ្យរត់ស្មើគែមជាមួយ Card -->
                <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                            <i data-feather="file-text" class="me-2 text-primary"></i>
                            Terms & Conditions
                        </h3>
                        <p class="text-muted small mb-0">Welcome to QuickCart. By using our website, you agree to these
                            terms.</p>
                    </div>
                </div>

                <!-- Upgraded Card Architecture Matching Dashboard Design -->
                <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white">
                    <p class="text-secondary mb-4 lh-lg">
                        Please read these terms and conditions carefully before accessing or using our multi-vendor
                        platform. Your compliance and agreement to these guidelines govern your relationship with QuickCart.
                    </p>

                    <div class="d-flex flex-column gap-4">

                        <!-- 1. Use of Website -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="user" style="width: 16px; height: 16px;"></i>
                                </span>
                                1. Use of Website
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                You must be at least 18 years old to use this service. You are responsible for maintaining
                                the confidentiality of your account information.
                            </p>
                        </div>

                        <!-- 2. Products and Pricing -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="tag" style="width: 16px; height: 16px;"></i>
                                </span>
                                2. Products and Pricing
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                All products displayed on our site are subject to availability. Prices may change without
                                notice. We strive to ensure accuracy in product descriptions and pricing.
                            </p>
                        </div>

                        <!-- 3. Orders and Payments -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="credit-card" style="width: 16px; height: 16px;"></i>
                                </span>
                                3. Orders and Payments
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                By placing an order, you agree to pay the total amount specified. We use secure payment
                                gateways to process your transactions.
                            </p>
                        </div>

                        <!-- 4. Limitation of Liability -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="alert-circle" style="width: 16px; height: 16px;"></i>
                                </span>
                                4. Limitation of Liability
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                QuickCart shall not be liable for any indirect, incidental, or consequential damages arising
                                from the use of our services or products purchased through our platform.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-light-subtle {
            background-color: #f8fafc !important;
        }

        .border-light-subtle {
            border-color: #f1f5f9 !important;
        }

        .lh-base {
            line-height: 1.6 !important;
        }

        .lh-lg {
            line-height: 1.75 !important;
        }

        .shadow-sm-subtle {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Ensures layout sizes fit beautifully with Feather SVGs rendering engine */
        .feather {
            display: inline-block;
            vertical-align: middle;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Feather iconography structures cleanly
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection
