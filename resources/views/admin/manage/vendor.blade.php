@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Vendors - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manage Vendors</h4>
                <p class="text-muted small mb-0">Review, dynamic check, and update validation status profiles for your
                    marketplace vendors.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold">Vendor Name</th>
                            <th class="py-3 text-muted fw-bold">Email Address</th>
                            <th class="py-3 text-muted fw-bold">Approval Status</th>
                            <th class="pe-4 py-3 text-end text-muted fw-bold" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">{{ $vendor->user->name ?? 'N/A' }}</td>
                                <td class="text-secondary">{{ $vendor->user->email ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge rounded-pill px-2.5 py-1.5 font-monospace text-uppercase fw-semibold {{ $vendor->approval_status == 'approved' ? 'bg-success-subtle text-success border border-success-subtle' : ($vendor->approval_status == 'pending' ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }}"
                                        style="font-size: 0.72rem;">
                                        {{ $vendor->approval_status }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <button type="button"
                                        class="btn btn-light btn-sm border rounded-2 text-primary p-2 d-inline-flex align-items-center edit-btn"
                                        onclick="openEditModal('{{ $vendor->id }}', '{{ $vendor->approval_status }}')"
                                        title="Edit Status">
                                        <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">No vendors available inside the
                                    system records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVendorModal" tabindex="-1" aria-labelledby="editVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editVendorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-bottom border-light px-4 py-3">
                        <h6 class="modal-title fw-bold text-dark" id="editVendorModalLabel">Update Operational Approval
                            Status</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-1">
                            <label class="form-label small fw-bold text-secondary">Vendor Approval Status</label>
                            <select name="approval_status" id="statusSelect" class="form-select rounded-3">
                                <option value="pending">Pending Review</option>
                                <option value="approved">Approved / Active Access</option>
                                <option value="rejected">Rejected / Disabled Access</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-light px-4 py-2.5 bg-light-subtle">
                        <button type="button" class="btn btn-light border btn-sm rounded-3 px-3 fw-semibold text-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold">Save
                            Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // បង្កើត instance តែម្តងទុកជាសកល (Global)
        let editModal;
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            // ចាប់យក Modal Elements ទុកជាមុន
            const modalElement = document.getElementById('editVendorModal');
            if (modalElement) {
                editModal = new bootstrap.Modal(modalElement);
            }
        });

        function openEditModal(id, status) {
            const form = document.getElementById('editVendorForm');
            const select = document.getElementById('statusSelect');

            form.action = `/admin/manage/vendors/${id}`;
            select.value = status;

            // បើកដំណើរការ Modal ដែលបានបង្កើតរួច
            if (editModal) {
                editModal.show();
            }
        }

        // Catch and process success operations response pipelines
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Data Record Updated!',
                text: "{!! session('success') !!}",
                confirmButtonColor: '#3b82f6',
                timer: 2500
            });
        @endif

        // Error message catch responses matrix mapping
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Operation Failed',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#ef4444'
            });
        @endif
    </script>
@endsection
