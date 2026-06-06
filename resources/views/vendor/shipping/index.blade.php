@extends('vendor.layouts.layout')

@section('vendor_page_title', 'Shipping Management')

@section('vendor_layout')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-1">Shipping Management</h3>
            <p class="text-muted small">Manage your shipping companies and tracking preferences.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addShipModal">
                    <i data-feather="plus" class="me-1"></i> Add New Company
                </button>
            </div>

            <!-- តារាងបង្ហាញក្រុមហ៊ុន -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark text-uppercase small tracking-wider">My Shipping Companies</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">Company Name</th>
                                    <th>Fee</th> <!-- បន្ថែមជួរនេះ -->
                                    <th>Template</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companies as $company)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $company->name }}</td>

                                        <!-- បន្ថែមបង្ហាញ Fee នៅទីនេះ -->
                                        <td>${{ number_format($company->shipping_fee, 2) }}</td>

                                        <td><small class="text-muted">{{ $company->tracking_url_template ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $company->is_active ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }} border rounded-pill">
                                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-light border"
                                                    onclick="editCompany({{ $company->id }}, '{{ $company->name }}', '{{ $company->tracking_url_template }}', {{ $company->shipping_fee }}, {{ $company->is_active }})">
                                                    <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                                </button>

                                                <button type="button" class="btn btn-sm btn-light border text-danger"
                                                    onclick="confirmDelete('{{ route('vendor.shipping.destroy', $company->id) }}')">
                                                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <!-- ប្តូរ colspan ពី 4 ទៅ 5 ព្រោះយើងបានបន្ថែមជួរថ្មី -->
                                        <td colspan="5" class="text-center py-5 text-muted">No companies added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editShipModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm" method="POST" class="modal-content border-0 shadow">
                @csrf @method('PUT')
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Edit Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary">Company Name</label>
                        <input type="text" name="name" id="editName" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary">Shipping Fee ($)</label>
                        <input type="number" step="0.01" name="shipping_fee" id="editFee"
                            class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary">Tracking URL Template</label>
                        <input type="text" name="tracking_url_template" id="editTemplate"
                            class="form-control shadow-none">
                    </div>
                    <!-- បន្ថែមក្នុង Edit Modal ក្រោម Tracking URL Template -->
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive">
                        <label class="form-check-label small fw-bold" for="editIsActive">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Update Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add New Company Modal -->
    <div class="modal fade" id="addShipModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('vendor.shipping.store') }}" method="POST" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Add New Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary">Company Name</label>
                        <input type="text" name="name" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary">Shipping Fee ($)</label>
                        <input type="number" step="0.01" name="shipping_fee" class="form-control shadow-none"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary">Tracking URL Template</label>
                        <input type="text" name="tracking_url_template" class="form-control shadow-none"
                            placeholder="https://track.com/id={tracking_number}">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                        <label class="form-check-label small fw-bold" for="isActive">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save Company</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editCompany(id, name, template, fee, isActive) {
            document.getElementById('editForm').action = '/vendor/shipping/' + id;
            document.getElementById('editName').value = name;
            // ដោះស្រាយបញ្ហា template ជា null
            document.getElementById('editTemplate').value = (template === 'null' || !template) ? '' : template;
            document.getElementById('editFee').value = fee;
            document.getElementById('editIsActive').checked = (isActive == 1);
            new bootstrap.Modal(document.getElementById('editShipModal')).show();
        }

        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = deleteUrl;
                    form.method = 'POST';
                    form.innerHTML = '@csrf @method('DELETE')';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#0d6efd',
                    timer: 3000
                });
            @endif
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
@endsection
