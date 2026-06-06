@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Payout Requests')

@section('admin_layout')
    <div class="container-fluid py-4">

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Payout Requests</h3>
                <p class="text-muted mb-0 small">Review and manage vendor withdrawal requests.</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary fw-semibold border-bottom-0">Vendor Name</th>
                            <th class="py-3 text-secondary fw-semibold border-bottom-0">Amount</th>
                            <th class="py-3 text-secondary fw-semibold border-bottom-0">Status</th>
                            <th class="py-3 text-secondary fw-semibold border-bottom-0">Requested At</th>
                            <th class="text-end pe-4 py-3 text-secondary fw-semibold border-bottom-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($requests as $request)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 42px; height: 42px;">
                                            {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $request->user->name }}</h6>
                                            <span class="text-muted" style="font-size: 0.8rem;">Vendor ID:
                                                #{{ $request->user->id }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3 fw-bold text-dark fs-6">
                                    ${{ number_format($request->amount, 2) }}
                                </td>

                                <td class="py-3">
                                    @if ($request->status == 'pending')
                                        <span
                                            class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
                                            Pending
                                        </span>
                                    @elseif($request->status == 'approved')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25">
                                            Approved
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25">
                                            Rejected
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3">
                                    <div class="text-dark fw-medium">{{ $request->created_at?->format('M d, Y') ?? 'N/A' }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $request->created_at?->format('h:i A') ?? '' }}</div>
                                </td>

                                <td class="text-end pe-4 py-3">
                                    @if ($request->status == 'pending')
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('admin.payouts.approve', $request->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-success rounded-pill px-3 shadow-sm d-flex align-items-center gap-1">
                                                    <i class="bi bi-check2-circle"></i>
                                                    <span class="d-none d-sm-inline">Approve</span>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.payouts.reject', $request->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to reject this payout request?');">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3 d-flex align-items-center gap-1">
                                                    <i class="bi bi-x-circle"></i>
                                                    <span class="d-none d-sm-inline">Reject</span>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                            Processed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="bi bi-inbox fs-1 text-secondary opacity-50"></i>
                                    </div>
                                    <h6 class="fw-semibold">No payout requests found</h6>
                                    <p class="small mb-0">When vendors request a payout, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($requests, 'links') && $requests->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4">
                    {{ $requests->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
@endsection
