@extends('layouts.user')

@section('home')
    <div class="container-fluid container-md py-5 px-3 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                            <i data-feather="truck" class="me-2 text-primary"></i>
                            Delivery Information
                        </h3>
                        <p class="text-muted small mb-0">At QuickCart, we strive to ensure that your items reach you safely
                            and on time.</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4 bg-white">
                    <div class="row g-4 g-md-5">

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <span class="p-3 rounded-3 bg-light-primary text-primary d-inline-flex shadow-sm-subtle">
                                    <i data-feather="clock" style="width: 22px; height: 22px;"></i>
                                </span>
                                <div>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; color: #0f172a;">Delivery
                                        Time</h5>
                                    <p class="text-secondary small lh-base mb-0">Delivery within Phnom Penh takes 1 to 2
                                        business days. For provinces, it takes 2 to 4 business days.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <span class="p-3 rounded-3 bg-light-primary text-primary d-inline-flex shadow-sm-subtle">
                                    <i data-feather="dollar-sign" style="width: 22px; height: 22px;"></i>
                                </span>
                                <div>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; color: #0f172a;">Shipping
                                        Fees</h5>
                                    <p class="text-secondary small lh-base mb-0">Shipping fees depend on the weight of the
                                        items and your location. You will see the actual shipping cost before confirming
                                        your order.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <span class="p-3 rounded-3 bg-light-primary text-primary d-inline-flex shadow-sm-subtle">
                                    <i data-feather="map-pin" style="width: 22px; height: 22px;"></i>
                                </span>
                                <div>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; color: #0f172a;">Order
                                        Tracking</h5>
                                    <p class="text-secondary small lh-base mb-0">You can track your delivery status at any
                                        time via the "My Orders" page in your account.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <span class="p-3 rounded-3 bg-light-primary text-primary d-inline-flex shadow-sm-subtle">
                                    <i data-feather="phone-call" style="width: 22px; height: 22px;"></i>
                                </span>
                                <div>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; color: #0f172a;">Customer
                                        Support</h5>
                                    <p class="text-secondary small lh-base mb-0">If there are any delays or issues, please
                                        contact our customer service team at +00 123-456-789.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-light-primary {
            background-color: #f1f5f9 !important;
            color: #4f46e5 !important;
        }

        .lh-base {
            line-height: 1.6 !important;
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
