@extends('admin.layouts.layout')

@section('admin_page_title', 'Dashboard - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Dashboard Overview</h4>
                <p class="text-muted small mb-0">Welcome back, <span
                        class="fw-semibold text-dark">{{ Auth::user()->name }}</span>! Here is your system performance
                    pipeline.</p>
            </div>
            <div>
                <a href="{{ route('admin.export.report') }}"
                    class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-1.5 shadow-sm w-100">
                    <i data-lucide="download" style="width: 16px; height: 16px;"></i> Export Operational Report
                </a>
            </div>
        </div>

        <div class="row g-3.5 mb-4">
            @php
                $metrics = [
                    [
                        'title' => 'Categories',
                        'value' => $categoryCount,
                        'bg' => '#e0e7ff',
                        'icon' => 'grid',
                        'text' => '#4338ca',
                    ],
                    [
                        'title' => 'Products',
                        'value' => $productCount,
                        'bg' => '#dcfce7',
                        'icon' => 'shopping-bag',
                        'text' => '#15803d',
                    ],
                    [
                        'title' => 'Pending',
                        'value' => $pendingVendorCount,
                        'bg' => '#fef9c3',
                        'icon' => 'user-check',
                        'text' => '#a16207',
                    ],
                    [
                        'title' => 'Orders',
                        'value' => $orderCount,
                        'bg' => '#fee2e2',
                        'icon' => 'shopping-cart',
                        'text' => '#b91c1c',
                    ],
                ];
            @endphp

            @foreach ($metrics as $metric)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-4 rounded-4 metric-card h-100"
                        style="background-color: {{ $metric['bg'] }};">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted text-uppercase fw-bold d-block mb-1"
                                    style="font-size: 0.725rem; letter-spacing: 0.05em;">{{ $metric['title'] }}</span>
                                <h3 class="fw-bold mb-0" style="color: {{ $metric['text'] }}; font-size: 1.75rem;">
                                    {{ number_format($metric['value']) }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center">
                                <i data-lucide="{{ $metric['icon'] }}"
                                    style="color: {{ $metric['text'] }}; width: 22px; height: 22px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 1.05rem;">Sales Performance Matrix</h5>
                    <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Recent Platform Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-uppercase fw-bold"
                            style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <tr>
                                <th class="ps-4 py-3 text-muted">Customer Node</th>
                                <th class="py-3 text-muted">Order ID Reference</th>
                                <th class="py-3 text-muted">Total Gross Amount</th>
                                <th class="pe-4 py-3 text-end text-muted">Execution Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold small"
                                                style="width: 36px; height: 36px; color: #64748b; shrink: 0;">
                                                {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                            </div>
                                            <span class="fw-semibold text-dark">{{ $order->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-secondary font-monospace">#{{ $order->id }}</td>
                                    <td class="fw-bold text-dark">${{ number_format((float) $order->total_amount, 2) }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span
                                            class="badge {{ $order->status == 'complete' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-1.5 fw-medium text-uppercase"
                                            style="font-size: 0.725rem;">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small fw-medium">
                                        No recent platform transaction logs detected inside system buffers.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .metric-card {
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08) !important;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .table td,
            .table th {
                white-space: nowrap;
                padding: 0.875rem 0.75rem !important;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Render Structural Vector Graphics Icons Engine
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Chart Initialization Sequence Configuration
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! $labels !!},
                    datasets: [{
                        data: {!! $values !!},
                        borderColor: '#4338ca',
                        backgroundColor: 'rgba(67, 56, 202, 0.04)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.38,
                        pointBackgroundColor: '#4338ca',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
