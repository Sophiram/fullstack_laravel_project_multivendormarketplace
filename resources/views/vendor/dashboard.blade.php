@extends('vendor.layouts.layout')

@section('vendor_page_title')
    Dashboard - Vendor Panel
@endsection

@section('vendor_layout')
    <!-- ផ្នែកស្វាគមន៍ (Header Section) -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark mb-1 tracking-tight">Vendor Dashboard</h3>
            <p class="text-muted small mb-0">Welcome back! Here's an overview of your business performance today.</p>
        </div>
    </div>

    <!-- ផ្នែកកាតស្ថិតិ (Top Metrics Cards) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Stores -->
        <div class="col-sm-6 col-xl-4">
            <div
                class="card border-0 shadow-sm rounded-4 h-100 card-gradient-primary text-white position-relative overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center justify-content-between position-relative z-1">
                    <div>
                        <span
                            class="text-white text-opacity-75 fw-semibold text-uppercase tracking-wider extra-small mb-1 d-block">Stores</span>
                        <h2 class="fw-bold display-6 text-white mb-0">{{ $total_stores ?? 0 }}</h2>
                    </div>
                    <div class="icon-container rounded-3 d-flex align-items-center justify-content-center">
                        <i data-feather="home" class="icon-main"></i>
                    </div>
                </div>
                <div class="card-bubble shadow-bubble-primary"></div>
            </div>
        </div>

        <!-- Card 2: Products -->
        <div class="col-sm-6 col-xl-4">
            <div
                class="card border-0 shadow-sm rounded-4 h-100 card-gradient-success text-white position-relative overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center justify-content-between position-relative z-1">
                    <div>
                        <span
                            class="text-white text-opacity-75 fw-semibold text-uppercase tracking-wider extra-small mb-1 d-block">Active
                            Products</span>
                        <h2 class="fw-bold display-6 text-white mb-0">{{ $total_products ?? 0 }}</h2>
                    </div>
                    <div class="icon-container rounded-3 d-flex align-items-center justify-content-center">
                        <i data-feather="shopping-bag" class="icon-main"></i>
                    </div>
                </div>
                <div class="card-bubble shadow-bubble-success"></div>
            </div>
        </div>

        <!-- Card 3: Orders -->
        <div class="col-sm-6 col-xl-4">
            <div
                class="card border-0 shadow-sm rounded-4 h-100 card-gradient-warning text-white position-relative overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center justify-content-between position-relative z-1">
                    <div>
                        <span
                            class="text-white text-opacity-75 fw-semibold text-uppercase tracking-wider extra-small mb-1 d-block">Total
                            Orders</span>
                        <h2 class="fw-bold display-6 text-white mb-0">{{ $total_orders ?? 0 }}</h2>
                    </div>
                    <div class="icon-container rounded-3 d-flex align-items-center justify-content-center">
                        <i data-feather="shopping-cart" class="icon-main"></i>
                    </div>
                </div>
                <div class="card-bubble shadow-bubble-warning"></div>
            </div>
        </div>
    </div>

    <!-- ផ្នែកក្រាហ្វិកវិភាគទិន្នន័យ (Performance Analytics) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div
                    class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 border-bottom border-light pb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Performance Metrics</h5>
                        <p class="text-muted small mb-0">Visual representation of your store sales and performance.</p>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <span
                            class="badge bg-light text-dark border border-secondary border-opacity-10 px-3 py-2 rounded-3 small fw-semibold d-inline-flex align-items-center">
                            <i class="align-middle me-2 text-secondary" data-feather="calendar"
                                style="width: 14px; height: 14px;"></i>
                            {{ date('M d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Monthly Sales Line Chart -->
                    <div class="col-xl-7 border-end-xl">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 small text-uppercase tracking-wider">Monthly Sales Performance
                            </h6>
                            <span class="small text-muted d-flex align-items-center gap-2">
                                <span class="d-inline-block rounded-circle bg-primary animate-pulse"
                                    style="width: 8px; height: 8px;"></span>
                                Total Sales ($)
                            </span>
                        </div>
                        <div class="p-2" style="min-height: 280px;">
                            <div id="salesChart"></div>
                        </div>
                    </div>

                    <!-- Top Store Bar Chart -->
                    <div class="col-xl-5">
                        <h6 class="fw-bold text-dark mb-3 small text-uppercase tracking-wider">Top Store Performance</h6>
                        <div class="p-2" style="min-height: 280px;">
                            <div id="storeChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ផ្នែកសកម្មភាពថ្មីៗ (Recent Activity Feed - Timeline Style) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Recent Activity Feed</h5>
                        <p class="text-muted small mb-0">Real-time updates of your shop activities.</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill small fw-semibold">Latest
                        Logs</span>
                </div>
                <div class="card-body px-4 pt-2 pb-4">
                    <div class="timeline-wrapper mt-3 position-relative">
                        @isset($recent_activities)
                            @forelse($recent_activities as $activity)
                                <div class="timeline-item d-flex mb-4 position-relative">
                                    <div class="timeline-icon-box flex-shrink-0 me-3 position-relative z-1">
                                        <div class="bg-light border border-2 border-white text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                            style="width: 38px; height: 38px;">
                                            <i data-feather="{{ $activity->icon ?? 'bell' }}"
                                                style="width: 16px; height: 16px;"></i>
                                        </div>
                                    </div>
                                    <div
                                        class="timeline-content bg-light bg-opacity-50 rounded-3 p-3 flex-grow-1 border border-light">
                                        <div
                                            class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-1">
                                            <p class="mb-0 fw-semibold text-dark text-sm">{{ $activity->title }}</p>
                                            <small class="text-muted text-xs"><i data-feather="clock" class="me-1"
                                                    style="width: 12px;"></i>{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if (isset($activity->description))
                                            <p class="text-muted small mb-0 mt-1">{{ $activity->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                                        <i data-feather="inbox" class="text-muted" style="width: 32px; height: 32px;"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark">No activities yet</h6>
                                    <p class="text-muted small">We'll notify you when something important happens.</p>
                                </div>
                            @endforelse
                        @else
                            <p class="text-muted small text-center py-4">No activities to display.</p>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ApexCharts Library -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- ផ្នែក CSS ជំនួយការតុបតែង (Styles) -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body,
        .card,
        h3,
        h5,
        h6,
        p,
        span,
        small {
            font-family: var(--font-sans);
        }

        /* Gradient Color Palettes for Premium Look */
        .card-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        }

        .card-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .card-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        }

        /* Soft Shadows & Micro-interactions */
        .card {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        /* Glassmorphism Icon Containers inside Cards */
        .icon-container {
            width: 54px;
            height: 54px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .card:hover .icon-container {
            transform: scale(1.08) rotate(5deg);
        }

        .icon-main {
            width: 24px;
            height: 24px;
            stroke: #ffffff;
            stroke-width: 2px;
        }

        /* Decorative Bubbles inside Cards */
        .card-bubble {
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            bottom: -50px;
            right: -30px;
            opacity: 0.15;
            z-index: 0;
        }

        .shadow-bubble-primary {
            background-color: #fff;
        }

        .shadow-bubble-success {
            background-color: #fff;
        }

        .shadow-bubble-warning {
            background-color: #fff;
        }

        /* Bootstrap Subtle Colors Fallbacks */
        .bg-primary-subtle {
            background-color: #eff6ff !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .tracking-tight {
            tracking-spacing: -0.025em;
        }

        .extra-small {
            font-size: 0.75rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        /* Timeline UI Styles */
        .timeline-wrapper::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 18px;
            height: calc(100% - 25px);
            width: 2px;
            background-color: #f1f5f9;
            z-index: 0;
        }

        .timeline-item:last-child .timeline-content {
            margin-bottom: 0;
        }

        .timeline-content {
            transition: background 0.2s ease;
        }

        .timeline-item:hover .timeline-content {
            background-color: #f8fafc !important;
        }

        /* Pulse Animation for Live Status */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .4;
            }
        }

        @media (min-width: 1200px) {
            .border-end-xl {
                border-right: 1px solid #f1f5f9 !important;
            }
        }
    </style>

    <!-- ផ្នែក JavaScript គ្រប់គ្រងក្រាហ្វិក (ApexCharts Configuration) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // 📊 1. LINE CHART: MONTHLY SALES
            @php
                $sales_data = !empty($chart_sales_data) ? $chart_sales_data : [35, 50, 40, 65, 55, 110, 95];
                $sales_months = !empty($chart_sales_months) ? $chart_sales_months : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
            @endphp
            const salesChartOptions = {
                series: [{
                    name: 'Total Sales',
                    data: {!! json_encode($sales_data) !!}
                }],
                chart: {
                    height: 280,
                    type: 'area',
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: {
                        show: false
                    },
                    sparkline: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#4f46e5']
                },
                colors: ['#4f46e5'],
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    padding: {
                        left: 10,
                        right: 10,
                        bottom: 0
                    }
                },
                xaxis: {
                    categories: {!! json_encode($sales_months) !!},
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontVerity: 12
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#94a3b8'
                        }
                    }
                },
                tooltip: {
                    theme: 'light',
                    x: {
                        show: true
                    },
                    y: {
                        formatter: val => "$ " + val.toLocaleString()
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.0,
                        stops: [0, 90, 100]
                    }
                },
                markers: {
                    size: 0,
                    hover: {
                        size: 5
                    }
                }
            };
            new ApexCharts(document.querySelector("#salesChart"), salesChartOptions).render();


            // 📊 2. BAR CHART: TOP STORES
            @php
                $store_data = !empty($chart_store_data) ? $chart_store_data : [75, 88, 62, 94, 45];
                $store_names = !empty($chart_store_names) ? $chart_store_names : ['Fashion Store', 'Gadget Hub', 'Home Decor', 'Beauty Box', 'Mini Mart'];
            @endphp

            const storeChartOptions = {
                series: [{
                    name: 'Revenue',
                    data: {!! json_encode($store_data) !!}
                }],
                chart: {
                    height: 280,
                    type: 'bar',
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        horizontal: true,
                        barHeight: '45%',
                        distributed: true
                    }
                },
                colors: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#ec4899'],
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                xaxis: {
                    categories: {!! json_encode($store_names) !!},
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#94a3b8'
                        },
                        formatter: val => "$ " + val
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#475569',
                            fontWeight: 500
                        }
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: val => "$ " + val.toLocaleString()
                    }
                }
            };
            new ApexCharts(document.querySelector("#storeChart"), storeChartOptions).render();
        });
    </script>
@endsection
