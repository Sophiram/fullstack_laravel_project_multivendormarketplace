@extends('user.layouts.layout')

@section('user_page_title', 'Dashboard - User Panel')

@section('user_layout')
    <div class="container-fluid px-2 px-md-4 py-3">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                    <i data-lucide="layout-dashboard" class="me-2 text-primary"></i>
                    User Dashboard
                </h3>
                <p class="text-muted small mb-0">Overview of your account activities and metrics.</p>
            </div>
        </div>

        <!-- Welcome Banner -->
        <div class="card border-0 p-4 p-md-5 mb-4 shadow-sm position-relative overflow-hidden welcome-banner"
            style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);">
            <div class="row align-items-center">
                <div class="col-md-9 position-relative" style="z-index: 2;">
                    <h4 class="fw-bold text-dark mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h4>
                    <p class="text-secondary mb-0" style="max-width: 600px; font-size: 15px; line-height: 1.6;">
                        This is your personal space. Easily manage your order tracking, process payment configurations, and
                        control your affiliate link generation from one place.
                    </p>
                </div>
            </div>
            <!-- Decorative Icon -->
            <div class="position-absolute end-0 top-50 translate-middle-y text-primary opacity-10 d-none d-md-block pe-4"
                style="z-index: 1;">
                <i data-lucide="activity" style="width: 160px; height: 160px; stroke-width: 1;"></i>
            </div>
        </div>

        <!-- Stat Cards Section -->
        <div class="row g-4 mb-4">
            @php
                $cards = [
                    [
                        'title' => 'Total Spent',
                        'value' => '$' . number_format($stats['totalSpent'], 2),
                        'icon' => 'dollar-sign',
                        'color' => 'text-primary',
                        'bg' => 'bg-primary-subtle',
                    ],
                    [
                        'title' => 'Total Orders',
                        'value' => $stats['totalOrders'],
                        'icon' => 'shopping-bag',
                        'color' => 'text-info',
                        'bg' => 'bg-info-subtle',
                    ],
                    [
                        'title' => 'Completed',
                        'value' => $stats['completedOrders'],
                        'icon' => 'check-circle',
                        'color' => 'text-success',
                        'bg' => 'bg-success-subtle',
                    ],
                    [
                        'title' => 'Pending',
                        'value' => $stats['pendingOrders'],
                        'icon' => 'clock',
                        'color' => 'text-warning',
                        'bg' => 'bg-warning-subtle',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-6 col-md-3">
                    <div class="card p-3 border-0 shadow-sm card-hover h-100" style="border-radius: 16px;">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-4 {{ $card['bg'] }} {{ $card['color'] }} me-3">
                                <i data-lucide="{{ $card['icon'] }}" style="width: 24px; height: 24px;"></i>
                            </div>
                            <div>
                                <span class="text-muted d-block"
                                    style="font-size: 11px; text-transform: uppercase; font-weight: 700;">{{ $card['title'] }}</span>
                                <h5 class="fw-bold mb-0 mt-1">{{ $card['value'] }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Orders Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4"
                    style="border-radius: 20px; background: #ffffff; overflow: hidden;">
                    <div
                        class="card-header bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center" style="font-size: 16px;">
                            <i data-lucide="clock" class="me-2 text-secondary icon-sm"></i>
                            Recent Orders
                        </h5>
                        <a href="{{ route('user.order.history') }}"
                            class="btn btn-link text-primary btn-sm fw-semibold text-decoration-none p-0 d-flex align-items-center">
                            View All <i data-lucide="arrow-right" class="ms-1" style="width: 14px; height: 14px;"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table custom-table align-middle mb-0 text-nowrap">
                            <thead style="background-color: #f8fafc;">
                                <tr class="text-secondary"
                                    style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <th class="border-0 ps-4 py-3">Order ID</th>
                                    <th class="border-0 py-3">Date</th>
                                    <th class="border-0 py-3">Status</th>
                                    <th class="border-0 text-end pe-4 py-3">Total</th>
                                    <th class="border-0 text-end pe-4 py-3">Action</th> {{-- បន្ថែមថ្មី --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders->take(3) as $order)
                                    <tr class="hover-bg-light">
                                        <td class="ps-4 py-3 fw-bold text-dark">#{{ $order->order_number }}</td>
                                        <td class="text-secondary">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="py-3">
                                            @php
                                                $statusClass = [
                                                    'completed' => 'bg-success-subtle text-success border-success',
                                                    'pending' => 'bg-warning-subtle text-warning border-warning',
                                                    'processing' => 'bg-info-subtle text-info border-info',
                                                    'canceled' => 'bg-danger-subtle text-danger border-danger',
                                                ];
                                                $status = strtolower($order->status);
                                                $badgeStyle =
                                                    $statusClass[$status] ?? 'bg-secondary-subtle text-secondary';
                                            @endphp
                                            <span class="badge rounded-pill border {{ $badgeStyle }} px-3 py-1">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            ${{ number_format($order->total_amount, 2) }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('user.order.show', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-secondary">
                                                <i data-lucide="package"
                                                    style="width: 40px; height: 40px; opacity: 0.5;"></i>
                                                <p class="mt-2 mb-0 fw-medium">No recent orders yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Card Hover Effects */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1) !important;
        }

        /* Table Row Hover */
        .custom-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Status Dot */
        .status-dot {
            width: 6px;
            height: 6px;
            vertical-align: middle;
        }

        /* Icon Sizing */
        .icon-sm {
            width: 18px;
            height: 18px;
        }

        /* Active Status Pulse Animation */
        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        /* Mobile Scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .hover-bg-light:hover {
            background-color: #fcfcfc !important;
            cursor: pointer;
        }

        .border-success {
            border-color: rgba(25, 135, 84, 0.2) !important;
        }

        .border-warning {
            border-color: rgba(255, 193, 7, 0.2) !important;
        }

        .border-info {
            border-color: rgba(13, 202, 240, 0.2) !important;
        }

        .border-danger {
            border-color: rgba(220, 53, 69, 0.2) !important;
        }
    </style>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Copy Referral Link Function
        function copyDbLink() {
            const linkInput = document.getElementById('dbRefLink');
            const btnCopy = document.getElementById('btnDbCopy');
            const originalContent = '<i data-lucide="copy" class="me-1" style="width: 14px; height: 14px;"></i> Copy';

            navigator.clipboard.writeText(linkInput.value).then(() => {
                // Change to Success state
                btnCopy.innerHTML =
                    '<i data-lucide="check" class="me-1" style="width: 14px; height: 14px;"></i> Copied!';
                btnCopy.classList.remove('btn-primary');
                btnCopy.classList.add('btn-success');
                lucide.createIcons(); // Re-initialize icons inside button

                // Revert after 2 seconds
                setTimeout(() => {
                    btnCopy.innerHTML = originalContent;
                    btnCopy.classList.remove('btn-success');
                    btnCopy.classList.add('btn-primary');
                    lucide.createIcons();
                }, 2000);
            });
        }
    </script>
@endsection
