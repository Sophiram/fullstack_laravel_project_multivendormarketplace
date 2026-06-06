@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Stores - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-2 py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manage Stores</h4>
                <p class="text-muted small mb-0">Overview, monitor, and configure operational status parameters for
                    registered commercial stores.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold">Store Name</th>
                            <th class="py-3 text-muted fw-bold">Email Address</th>
                            <th class="py-3 text-muted fw-bold">Status</th>
                            <th class="pe-4 py-3 text-end text-muted fw-bold" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">{{ $store->store_name }}</td>
                                <td class="text-secondary">{{ $store->store_email }}</td>
                                <td>
                                    <span {{-- ✅ ប្តូរលក្ខខណ្ឌពិនិត្យពី 'active' ទៅជា 'approved' --}}
                                        class="badge rounded-pill px-2.5 py-1.5 font-monospace text-uppercase fw-semibold {{ $store->status == 'approved' ? 'bg-success-subtle text-success border border-success-subtle' : ($store->status == 'pending' ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }}"
                                        style="font-size: 0.72rem;">
                                        {{ $store->status }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <button type="button"
                                        class="btn btn-light btn-sm border rounded-2 text-primary p-2 d-inline-flex align-items-center"
                                        onclick="openEditModal('{{ $store->id }}', '{{ $store->status }}')"
                                        title="Edit Status">
                                        <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">No stores available inside the
                                    system database records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStoreModal" tabindex="-1" aria-labelledby="editStoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editStoreForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-bottom border-light px-4 py-3">
                        <h6 class="modal-title fw-bold text-dark" id="editStoreModalLabel">Update Operational Store Status
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-1">
                            <label class="form-label small fw-bold text-secondary">Store Visibility Status</label>
                            <select name="status" id="statusSelect" class="form-select rounded-3">
                                {{-- ✅ ប្តូរតម្លៃ Value ឱ្យត្រូវទៅតាមប្រភេទ ENUM ក្នុង Database --}}
                                <option value="approved">Approved / Active Public</option>
                                <option value="pending">Pending Review</option>
                                <option value="rejected">Rejected / Suspended</option>
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
        document.addEventListener("DOMContentLoaded", () => {
            // Render Structural Vector Graphics Icons Engine
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Trigger Modal Open Execution Context Runtime
        function openEditModal(id, status) {
            const form = document.getElementById('editStoreForm');
            const select = document.getElementById('statusSelect');

            form.action = `/admin/manage/stores/${id}`;
            select.value = status;

            new bootstrap.Modal(document.getElementById('editStoreModal')).show();
        }

        // Catch and process success operations response pipelines
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Status Updated!',
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
