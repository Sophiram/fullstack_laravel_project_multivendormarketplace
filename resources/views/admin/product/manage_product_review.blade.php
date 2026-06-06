@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Reviews - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-2 py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Customer Reviews</h4>
                <p class="text-muted small mb-0">Review, approve, and manage customer product ratings and feedback.</p>
            </div>
        </div>

        @forelse ($reviews as $review)
            @if ($loop->first)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                            <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-bold">Product</th>
                                    <th class="py-3 text-muted fw-bold">Customer</th>
                                    <th class="py-3 text-muted fw-bold">Comment</th> {{-- 💡 ថែមចំណងជើងត្រង់នេះ --}}
                                    <th class="py-3 text-muted fw-bold">Rating</th>
                                    <th class="py-3 text-muted fw-bold">Status</th>
                                    <th class="py-3 text-muted fw-bold">Date</th>
                                    <th class="pe-4 py-3 text-end text-muted fw-bold" style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
            @endif

            <tr>
                <td class="ps-4">
                    <span class="fw-semibold text-dark">{{ $review->product->product_name ?? 'Unknown Product' }}</span>
                </td>

                <td>
                    <div class="fw-semibold text-dark">{{ $review->user->name ?? 'Guest User' }}</div>
                    <small class="text-muted font-monospace"
                        style="font-size: 0.75rem;">{{ $review->user->email ?? 'N/A' }}</small>
                </td>
                <td>
                    <p class="mb-0 text-dark text-wrap" style="max-width: 250px;">
                        {{ $review->review ?? 'N/A' }}
                    </p>
                </td>

                <td>
                    <div class="text-warning d-inline-flex gap-0.5">
                        @for ($i = 0; $i < 5; $i++)
                            @if ($i < $review->rating)
                                <i data-lucide="star" style="width: 16px; height: 16px; fill: currentColor;"></i>
                            @else
                                <i data-lucide="star" style="width: 16px; height: 16px; text-muted;"></i>
                            @endif
                        @endfor
                    </div>
                </td>

                <td>
                    @if ($review->status == 'approved')
                        <span
                            class="badge rounded-pill px-2.5 py-1.5 bg-success-subtle text-success border border-success-subtle font-monospace text-uppercase"
                            style="font-size: 0.7rem;">
                            Approved
                        </span>
                    @else
                        <span
                            class="badge rounded-pill px-2.5 py-1.5 bg-warning-subtle text-warning border border-warning-subtle font-monospace text-uppercase"
                            style="font-size: 0.7rem;">
                            Pending
                        </span>
                    @endif
                </td>

                <td class="text-secondary small">{{ $review->created_at->format('M d, Y') }}</td>

                <td class="pe-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        @if ($review->status !== 'approved')
                            <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST"
                                class="d-inline approve-form">
                                @csrf
                                @method('PUT')
                                <button type="button"
                                    class="btn btn-sm btn-light border text-success rounded-2 p-2 d-inline-flex align-items-center approve-btn"
                                    title="Approve Review">
                                    <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.review.reject', $review->id) }}" method="POST"
                            class="d-inline reject-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                class="btn btn-sm btn-light border text-danger rounded-2 p-2 d-inline-flex align-items-center reject-btn"
                                title="Reject & Delete">
                                <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            @if ($loop->last)
                </tbody>
                </table>
    </div>

    <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
        <small class="text-muted small">
            Showing {{ $reviews->firstItem() ?? 0 }} to {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() }} entries
        </small>
        <nav aria-label="Pagination">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </nav>
    </div>
    </div>
    @endif
@empty
    <div class="card p-5 text-center border-0 shadow-sm rounded-4 bg-white">
        <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto text-secondary"
            style="width: 64px; height: 64px;">
            <i data-lucide="message-square" style="width: 32px; height: 32px;"></i>
        </div>
        <h5 class="fw-bold text-dark">No Reviews Found</h5>
        <p class="text-muted small max-w-md mx-auto mb-0">There are no customer reviews or product feedback logs registered
            at the moment.</p>
    </div>
    @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Lucide Icons
            lucide.createIcons();

            // Handle Approve Action Confirm Popup
            document.querySelectorAll('.approve-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.approve-form');

                    Swal.fire({
                        title: 'Approve Review?',
                        text: "This review will become visible to all users on the store page.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, approve it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Handle Reject/Delete Action Confirm Popup
            document.querySelectorAll('.reject-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.reject-form');

                    Swal.fire({
                        title: 'Reject & Delete Review?',
                        text: "Are you sure you want to permanently erase this evaluation log?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

        // Trigger Success Flash Messages Popup
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
                position: 'center'
            });
        @endif
    </script>
@endsection
