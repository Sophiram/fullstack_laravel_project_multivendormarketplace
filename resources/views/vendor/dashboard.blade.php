@extends('vendor.layouts.layout')

@section('vendor_page_title')
    Dashboard - Vendor Panel
@endsection

@section('vendor_layout')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark mb-1">Vendor Dashboard</h3>
            <p class="text-muted small mb-0">Welcome back! Here's an overview of your business performance today.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Card 1: Stores -->
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-lg rounded-4 h-100 card-gradient-primary text-white">
                <div class="card-body p-4 py-5 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white text-opacity-75 fw-bold text-uppercase small tracking-wider mb-2">Stores</h6>
                        <h1 class="fw-bold display-4 text-white mb-0">{{ $total_stores ?? 0 }}</h1>
                    </div>
                    <div class="icon-container rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-25"
                        style="width: 60px; height: 60px;">
                        <i data-feather="home" style="width: 30px; height: 30px; stroke: white;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Products -->
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-lg rounded-4 h-100 card-gradient-success text-white">
                <div class="card-body p-4 py-5 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white text-opacity-75 fw-bold text-uppercase small tracking-wider mb-2">Active
                            Products</h6>
                        <h1 class="fw-bold display-4 text-white mb-0">{{ $total_products ?? 0 }}</h1>
                    </div>
                    <div class="icon-container rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-25"
                        style="width: 60px; height: 60px;">
                        <i data-feather="shopping-bag" style="width: 30px; height: 30px; stroke: white;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Orders -->
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-lg rounded-4 h-100 card-gradient-warning text-white">
                <div class="card-body p-4 py-5 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white text-opacity-75 fw-bold text-uppercase small tracking-wider mb-2">Total Orders
                        </h6>
                        <h1 class="fw-bold display-4 text-white mb-0">{{ $total_orders ?? 0 }}</h1>
                    </div>
                    <div class="icon-container rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-25"
                        style="width: 60px; height: 60px;">
                        <i data-feather="shopping-cart" style="width: 30px; height: 30px; stroke: white;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div
                    class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 border-bottom border-light pb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Performance Metrics</h5>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <span
                            class="badge bg-light text-dark border px-3 py-2 rounded-3 small fw-semibold d-inline-flex align-items-center">
                            <i class="align-middle me-2 text-secondary" data-feather="calendar"
                                style="width: 14px; height: 14px;"></i>
                            {{ date('M d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-7 border-end-xl">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">Monthly Sales Performance</h6>
                            <span class="small text-muted d-flex align-items-center gap-1">
                                <span class="d-inline-block rounded-circle bg-primary"
                                    style="width: 10px; height: 10px;"></span> Total Sales ($)
                            </span>
                        </div>
                        <div class="p-2" style="min-height: 280px;">
                            <div id="salesChart"></div>
                        </div>
                    </div>

                    <div class="col-xl-5">
                        <h6 class="fw-bold text-dark mb-3">Top Store Performance</h6>
                        <div class="p-2" style="min-height: 280px;">
                            <div id="storeChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Recent Activity Feed</h5>
                    <span class="badge bg-light text-secondary px-3 py-2 rounded-pill small fw-semibold">Latest
                        Activity</span>
                </div>
                <div class="card-body p-4 pt-2">
                    @isset($recent_activities)
                        @forelse($recent_activities as $activity)
                            <div class="d-flex align-items-center py-3 {{ !$loop->last ? 'border-bottom border-light' : '' }}">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i data-feather="{{ $activity->icon ?? 'bell' }}"
                                            style="width: 18px; height: 18px;"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold text-dark">{{ $activity->title }}</p>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i data-feather="inbox" class="text-muted mb-2" style="width: 40px; height: 40px;"></i>
                                <p class="text-muted">No recent activity available.</p>
                            </div>
                        @endforelse
                    @else
                        <!-- Fallback content បើគ្មានទិន្នន័យ -->
                        <p class="text-muted small">No activities to display.</p>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        /* Gradient Color Palettes */
        .card-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .card-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }

        .card-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
        }

        /* បង្កើនទំហំកាត និងភាពទាក់ទាញ */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
        }

        .icon-container {
            backdrop-filter: blur(8px);
            transition: transform 0.3s ease;
        }

        .card:hover .icon-container {
            transform: scale(1.1);
        }


        
        .card-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .card-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }

        .card-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
        }

        .icon-container {
            width: 48px;
            height: 48px;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
        }

        .bg-primary-subtle {
            background-color: #e0f2fe !important;
        }

        .bg-danger-subtle {
            background-color: #fee2e2 !important;
        }

        .bg-success-subtle {
            background-color: #d1fae5 !important;
        }

        .bg-info-subtle {
            background-color: #e0f7fa !important;
        }

        .bg-warning-subtle {
            background-color: #fef3c7 !important;
        }

        .rounded-4 {
            border-radius: 1.2rem !important;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        @media (min-width: 1200px) {
            .border-end-xl {
                border-right: 1px solid #e2e8f0 !important;
            }
        }

        @media (max-width: 768px) {

            #salesChart,
            #storeChart {
                width: 100% !important;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // 🟢 1. DYNAMIC SALES LINE CHART
            // បង្កើត PHP Array ទុកខាងក្រៅដើម្បីកុំឱ្យជាន់គ្នាជាមួយ Syntax JavaScript
            @php
                $sales_data = $chart_sales_data ?? [31, 40, 28, 51, 42, 109, 100];
                $sales_months = $chart_sales_months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
            @endphp

            const salesChartOptions = {
                series: [{
                    name: 'Total Sales ($)',
                    data: {!! json_encode($sales_data) !!}
                }],
                chart: {
                    height: 280,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#3b82f6'],
                xaxis: {
                    categories: {!! json_encode($sales_months) !!}
                },
                tooltip: {
                    y: {
                        formatter: val => "$ " + val
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3
                    }
                },
                // បន្ថែម Marker
                markers: {
                    size: 5,
                    strokeColors: '#fff',
                    strokeWidth: 2
                }
            };
            new ApexCharts(document.querySelector("#salesChart"), salesChartOptions).render();


            // 🟢 2. DYNAMIC TOP STORES BAR CHART
            @php
                $store_data = $chart_store_data ?? [44, 55, 41, 67, 22];
                $store_names = $chart_store_names ?? ['Store A', 'Store B', 'Store C', 'Store D', 'Store E'];
            @endphp

            const storeChartOptions = {
                series: [{
                    name: 'Revenue ($)',
                    data: {!! json_encode($store_data) !!}
                }],
                chart: {
                    height: 280,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        horizontal: true
                    }
                },
                colors: ['#10b981'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: {!! json_encode($store_names) !!}
                }
            };
            new ApexCharts(document.querySelector("#storeChart"), storeChartOptions).render();
        });
    </script>
@endsection
