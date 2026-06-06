@extends('layouts.user')

@section('home')
    <div class="container-fluid container-md py-5 px-3 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <!-- 📌 ផ្នែក Header: ប្តូរមកដាក់ក្នុង Column នេះដើម្បីឱ្យរត់ស្មើគែមជាមួយ Card -->
                <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                            <i data-feather="shield" class="me-2 text-primary"></i>
                            Privacy Policy
                        </h3>
                        <p class="text-muted small mb-0">Last updated: June 2, 2026</p>
                    </div>
                </div>

                <!-- Upgraded Card Architecture Matching Dashboard Design -->
                <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white">
                    <p class="text-secondary mb-4 lh-lg">
                        At QuickCart, we value your trust and are committed to protecting your personal data. This Privacy
                        Policy explains how we collect, use, and safeguard your information when you interact with our
                        multi-vendor platform.
                    </p>

                    <div class="d-flex flex-column gap-4">

                        <!-- 1. Information We Collect -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="database" style="width: 16px; height: 16px;"></i>
                                </span>
                                1. Information We Collect
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                We collect information you provide directly to us, such as when you create an account,
                                update your profile, or make a purchase. This includes your name, email, shipping address,
                                and payment information.
                            </p>
                        </div>

                        <!-- 2. How We Use Your Information -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="sliders" style="width: 16px; height: 16px;"></i>
                                </span>
                                2. How We Use Your Information
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                We use the information we collect to provide, maintain, and improve our services, process
                                your transactions, and send you updates about your orders and promotional offers.
                            </p>
                        </div>

                        <!-- 3. Information Sharing -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="share-2" style="width: 16px; height: 16px;"></i>
                                </span>
                                3. Information Sharing
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                We do not sell your personal information. We may share information with vendors to fulfill
                                your orders or with service providers who perform services on our behalf.
                            </p>
                        </div>

                        <!-- 4. Security -->
                        <div class="p-3 rounded-3 bg-light-subtle border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center"
                                style="font-size: 1.1rem; color: #0f172a;">
                                <span class="p-2 rounded-2 bg-white text-primary me-2 d-inline-flex shadow-sm-subtle">
                                    <i data-feather="lock" style="width: 16px; height: 16px;"></i>
                                </span>
                                4. Security
                            </h5>
                            <p class="text-secondary small lh-base mb-0 ps-md-4 ms-md-2">
                                We take reasonable measures to help protect information about you from loss, theft, misuse,
                                and unauthorized access.
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
