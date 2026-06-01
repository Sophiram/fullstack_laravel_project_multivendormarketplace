@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Payout Requests')

@section('admin_layout')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Payout Requests</h3>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Vendor Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td class="ps-4">{{ $request->user->name }}</td>
                                <td>${{ number_format($request->amount, 2) }}</td>
                                <td>
                                    <span
                                        class="badge {{ $request->status == 'pending' ? 'bg-warning' : ($request->status == 'approved' ? 'bg-success' : 'bg-danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                
                                <td class="text-end pe-4">
                                    @if ($request->status == 'pending')
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('admin.payouts.approve', $request->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-success">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.payouts.reject', $request->id) }}" method="POST"
                                                onsubmit="return confirm('Reject this request?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
