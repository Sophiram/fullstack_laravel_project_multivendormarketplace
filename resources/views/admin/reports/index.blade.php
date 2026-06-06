@extends('admin.layouts.layout')
@section('admin_page_title', 'Global System Report')

@section('admin_layout')
    <div class="container-fluid px-2 py-2">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 mt-2">
            <div>
                <h4 class="fw-bolder text-dark mb-1 fs-3">Global System Report</h4>
                <p class="text-secondary mb-0" style="font-size: 0.9rem;">
                    Welcome back, <span class="fw-bold" style="color: #6366f1;">{{ Auth::user()->name }}</span>! Here is your
                    real-time ecosystem pipeline.
                </p>
            </div>

            <div class="d-flex gap-2 flex-column flex-sm-row">
                <a href="{{ route('admin.reports.export', request()->query()) }}"
                    class="btn btn-success shadow-sm px-4 py-2.5 rounded-3 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 text-nowrap flex-shrink-0">
                    <i class="fas fa-file-excel" style="font-size: 16px;"></i> Export to Excel
                </a>
            </div>
        </div>

        <div class="card p-3 p-md-4 border-0 shadow-sm rounded-4 bg-white mb-4">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label text-muted small fw-bold text-uppercase">Start Date</label>
                    <input type="date" name="start_date" class="form-control rounded-3 shadow-none border-light-subtle"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label text-muted small fw-bold text-uppercase">End Date</label>
                    <input type="date" name="end_date" class="form-control rounded-3 shadow-none border-light-subtle"
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 rounded-3 w-100 fw-medium shadow-sm">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                    @if (request()->has('start_date') || request()->has('end_date'))
                        <a href="{{ route('admin.reports.index') }}"
                            class="btn btn-light px-4 rounded-3 text-secondary border border-light-subtle w-100 fw-medium">
                            <i class="fas fa-undo me-2"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card p-4 border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small fw-bold">Total Users</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $reportData['total_users'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px;">
                            <i class="fas fa-users text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card p-4 border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small fw-bold">Total Vendors</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $reportData['total_vendors'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px;">
                            <i class="fas fa-store text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card p-4 border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small fw-bold">Total Sales</h6>
                            <h3 class="fw-bold mb-0 text-dark">${{ number_format($reportData['total_sales'], 2) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px;">
                            <i class="fas fa-dollar-sign text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card p-4 border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small fw-bold">Total Products</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $reportData['total_products'] }}</h3>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px;">
                            <i class="fas fa-box text-secondary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold text-secondary mb-3 mt-2">Payout Overview</h5>
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card p-4 border-0 shadow-sm rounded-4 bg-white h-100 border-start border-warning border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small fw-bold">Pending Payouts</h6>
                            <h3 class="fw-bold mb-0 text-dark">${{ number_format($reportData['pending_payouts'], 2) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px;">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card p-4 border-0 shadow-sm rounded-4 bg-white h-100 border-start border-success border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small fw-bold">Approved Payouts</h6>
                            <h3 class="fw-bold mb-0 text-dark">${{ number_format($reportData['approved_payouts'], 2) }}
                            </h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px;">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-4">
                <div class="card p-4 border-0 shadow-sm rounded-4 bg-white h-100 border-start border-dark border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small fw-bold">Total Payout Volume</h6>
                            <h3 class="fw-bold mb-0 text-dark">
                                ${{ number_format($reportData['pending_payouts'] + $reportData['approved_payouts'], 2) }}
                            </h3>
                        </div>
                        <div class="bg-dark bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px;">
                            <i class="fas fa-wallet text-dark fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark m-0">Recent Payout Requests</h5>
            </div>
            <div class="card-body p-0 table-responsive mt-2">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 fw-bold border-0">Date</th>
                            <th class="py-3 fw-bold border-0">Vendor</th>
                            <th class="py-3 fw-bold border-0">Amount</th>
                            <th class="py-3 fw-bold border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $item)
                            <tr>
                                <td class="ps-4 py-3 text-secondary">{{ $item->created_at?->format('d M, Y') }}</td>
                                <td class="py-3 fw-medium text-dark">{{ $item->user->name ?? 'N/A' }}</td>
                                <td class="py-3 fw-bold text-dark">${{ number_format($item->amount, 2) }}</td>
                                <td class="py-3">
                                    <span
                                        class="badge rounded-pill px-3 py-2 {{ $item->status == 'approved' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-folder-open fs-1 text-light mb-3"></i>
                                        <p class="mb-0">No recent payouts found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
