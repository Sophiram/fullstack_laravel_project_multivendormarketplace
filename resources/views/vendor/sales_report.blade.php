@extends('vendor.layouts.layout')

@section('vendor_page_title')
    Sales Report - Vendor Panel
@endsection

@section('vendor_layout')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Sales Reports</h3>
            <p class="text-muted small mb-0">Track your business growth, total earnings, and monthly sales performance.</p>
        </div>
        <a href="{{ route('vendor.report.export') }}" class="btn btn-success btn-sm px-3">
            <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-secondary fw-bold text-uppercase small mb-0">Total Earnings</h6>
                        <div class="bg-light-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i data-feather="dollar-sign" style="width: 20px; height: 20px;"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark mb-1">${{ number_format($total_earnings ?? 0, 2) }}</h2>
                    <span class="text-muted small">From successful deliveries</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-secondary fw-bold text-uppercase small mb-0">Items Sold</h6>
                        <div class="bg-light-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i data-feather="package" style="width: 20px; height: 20px;"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark mb-1">{{ number_format($total_items_sold ?? 0) }}</h2>
                    <span class="text-muted small">Products dynamic count</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
            <i class="bi bi-bar-chart-line text-primary me-2"></i>
            <h5 class="card-title mb-0 fw-bold text-dark text-uppercase small">Monthly Performance ({{ date('Y') }})
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3 border-0 small">Month</th>
                        <th class="py-3 border-0 small">Orders Count</th>
                        <th class="py-3 pe-4 border-0 text-end small">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($monthly_sales as $sale)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">
                                {{ \Carbon\Carbon::create()->month($sale->month)->format('F') }}</td>
                            <td class="text-secondary">{{ $sale->count }} Orders</td>
                            <td class="pe-4 fw-bold text-success text-end">${{ number_format($sale->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-graph-down fs-2 d-block mb-2 text-light-muted"></i>No sales data available
                                for this year.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .bg-light-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .bg-light-primary {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .text-light-muted {
            color: #dee2e6;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }
    </style>
@endsection
