@extends('layouts.user')

@section('home')
    <div class="container-fluid container-md py-5 px-3 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">

                <!-- 📌 ផ្នែក Header: បានប្តូរមកដាក់ក្នុង Column នេះដើម្បីឱ្យរត់ស្មើគែមជាមួយ Card -->
                <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                            <i data-feather="info" class="me-2 text-primary"></i>
                            About Us
                        </h3>
                        <p class="text-muted small mb-0">Learn more about QuickCart, our mission, and our marketplace
                            ecosystem.</p>
                    </div>
                </div>

                <!-- 🏢 ផ្នែក Content Card -->
                <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white">

                    <div class="mb-4">
                        <h4 class="lead fw-bold text-primary mb-3" style="font-size: 1.35rem;">
                            Welcome to <strong>QuickCart</strong>!
                        </h4>
                        <p class="text-secondary lh-lg mb-3">
                            QuickCart is your premium multi-vendor marketplace, designed to connect buyers and sellers
                            seamlessly. Our platform is built with a focus on high standards, ensuring a secure
                            and verified shopping experience for all our users.
                        </p>
                        <p class="text-secondary lh-lg mb-0">
                            We take pride in bringing local vendors closer to you, offering a wide range of products
                            with convenience and reliability. Whether you are looking for the latest tech or
                            everyday essentials, QuickCart is here to serve you.
                        </p>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="mt-2">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                            <span class="p-2 rounded-3 bg-light text-secondary me-2 d-inline-flex">
                                <i data-feather="target" style="width: 18px; height: 18px;" class="text-primary"></i>
                            </span>
                            Our Mission
                        </h5>
                        <div class="alert alert-info border-0 bg-light-subtle text-dark p-3 rounded-3 mb-0 d-flex align-items-start"
                            style="border-left: 4px solid #4f46e5 !important;">
                            <div class="small lh-lg">
                                To provide a trusted digital space where local businesses can grow and customers can
                                shop with complete peace of mind.
                            </div>
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

        .lh-lg {
            line-height: 1.75 !important;
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
