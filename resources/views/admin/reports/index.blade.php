@extends('admin.layouts.layout')
@section('admin_page_title', 'Global System Report')

@section('admin_layout')
    <div class="container-fluid px-4">
        <!-- Header ជាមួយប៊ូតុង Export -->
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h1 class="fw-bold m-0">Global System Report</h1>
            <a href="{{ route('admin.reports.export') }}" class="btn btn-success shadow-sm px-4">
                <i class="fas fa-file-excel me-2"></i> Export to Excel
            </a>
        </div>

        <!-- ស្ថិតិសរុប (Global Stats Cards) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3 border-0 bg-primary text-white shadow-sm rounded-3">
                    <h6 class="text-uppercase opacity-75 small">Total Users</h6>
                    <h3 class="fw-bold mb-0">{{ $reportData['total_users'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 border-0 bg-info text-white shadow-sm rounded-3">
                    <h6 class="text-uppercase opacity-75 small">Total Vendors</h6>
                    <h3 class="fw-bold mb-0">{{ $reportData['total_vendors'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 border-0 bg-success text-white shadow-sm rounded-3">
                    <h6 class="text-uppercase opacity-75 small">Total Sales</h6>
                    <h3 class="fw-bold mb-0">${{ number_format($reportData['total_sales'], 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 border-0 bg-secondary text-white shadow-sm rounded-3">
                    <h6 class="text-uppercase opacity-75 small">Total Products</h6>
                    <h3 class="fw-bold mb-0">{{ $reportData['total_products'] }}</h3>
                </div>
            </div>
        </div>

        <!-- ស្ថិតិដកប្រាក់ -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card p-3 border-0 bg-warning text-white shadow-sm rounded-3">
                    <h6 class="text-uppercase opacity-75 small">Pending Payouts</h6>
                    <h3 class="fw-bold mb-0">${{ number_format($reportData['pending_payouts'], 2) }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 bg-success text-white shadow-sm rounded-3">
                    <h6 class="text-uppercase opacity-75 small">Approved Payouts</h6>
                    <h3 class="fw-bold mb-0">${{ number_format($reportData['approved_payouts'], 2) }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 bg-dark text-white shadow-sm rounded-3">
                    <h6 class="text-uppercase opacity-75 small">Total Payout Volume</h6>
                    <h3 class="fw-bold mb-0">
                        ${{ number_format($reportData['pending_payouts'] + $reportData['approved_payouts'], 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- តារាងសកម្មភាពថ្មីៗ -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold text-secondary">Recent Payout Requests</h5>
            </div>
            <div class="card-body p-0 table-responsive mt-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $item)
                            <tr>
                                <td class="ps-4">{{ $item->created_at?->format('Y-m-d') }}</td>
                                <td>{{ $item->user->name ?? 'N/A' }}</td>
                                <td>${{ number_format($item->amount, 2) }}</td>
                                <td>
                                    <span
                                        class="badge rounded-pill {{ $item->status == 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-4 text-muted">No recent payouts.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
