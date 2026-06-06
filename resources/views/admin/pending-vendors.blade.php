@extends('admin.layouts.layout')

@section('admin_page_title', 'Pending Vendors - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-2 py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Pending Vendor Approvals</h4>
                <p class="text-muted small mb-0">Review, audit, and verify incoming third-party merchant registration
                    requests.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Vendors Awaiting Core Access</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-uppercase fw-bold"
                            style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <tr>
                                <th class="ps-4 py-3 text-muted">Vendor Identity Reference</th>
                                <th class="py-3 text-muted">Email Communications Node</th>
                                <th class="pe-4 py-3 text-end text-muted" style="width: 150px;">Action Console</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingVendors as $vendor)
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">{{ $vendor->name }}</td>
                                    <td class="text-secondary font-monospace">{{ $vendor->email }}</td>
                                    <td class="pe-4 text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-primary rounded-2 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                            data-bs-toggle="modal" data-bs-target="#approveModal{{ $vendor->id }}">
                                            <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i> Approve
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="approveModal{{ $vendor->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 overflow-hidden shadow">
                                            <div class="modal-header bg-light border-bottom py-3 px-4">
                                                <h5 class="modal-title fw-bold text-dark" style="font-size: 1.05rem;">
                                                    Confirm Merchant Approval</h5>
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <p class="text-secondary mb-0 lh-base">
                                                    Are you absolutely sure you want to approve <strong
                                                        class="text-dark">{{ $vendor->name }}</strong> as an authorized
                                                    platform merchant? This system execution will immediately grant them
                                                    administrative dashboard operational privileges.
                                                </p>
                                            </div>
                                            <div
                                                class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-end gap-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-light border rounded-3 px-3 fw-semibold text-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.approve', $vendor->id) }}" method="POST"
                                                    class="m-0">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success rounded-3 px-3 fw-semibold d-inline-flex align-items-center gap-1">
                                                        <i data-lucide="shield-check"
                                                            style="width: 14px; height: 14px;"></i> Yes, Approve Now
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small fw-medium">
                                        No pending vendor registration datasets detected inside active runtime buffers.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Render Structural Vector Graphics Icons Engine
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Catch and process success verification pipeline alerts
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Merchant Verified',
                text: "{!! session('success') !!}",
                timer: 2500,
                showConfirmButton: false,
                position: 'center',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow'
                }
            });
        @endif
    </script>
@endsection
