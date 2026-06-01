@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Attributes - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manage Attributes</h4>
                <p class="text-muted small mb-0">Define and configure specialized specifications and product variations.</p>
            </div>
            <a href="{{ route('productattribute.create') }}"
                class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Create New Attribute
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Product Specific Variations</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-4 text-muted fw-bold" style="width: 70px;">#</th>
                            <th class="text-muted fw-bold">Product Target</th>
                            <th class="text-muted fw-bold">Attribute Value</th>
                            <th class="text-muted fw-bold">Additional Price</th>
                            <th class="pe-4 text-end text-muted fw-bold" style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attributes as $attr)
                            <tr>
                                <td class="ps-4 text-secondary font-monospace">{{ $loop->iteration }}</td>
                                <td><span
                                        class="fw-semibold text-dark">{{ $attr->product->product_name ?? 'Unknown Product' }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-2 px-2 py-1 font-monospace">
                                        {{ $attr->attribute->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark">${{ number_format($attr->additional_price, 2) }}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1.5">
                                        <button type="button"
                                            class="btn btn-light btn-sm border rounded-2 text-primary p-2 d-inline-flex align-items-center edit-prod-btn"
                                            data-bs-toggle="modal" data-bs-target="#editProdModal"
                                            data-route="{{ route('update.attribute', $attr->id) }}"
                                            data-product="{{ $attr->product_id }}"
                                            data-attribute="{{ $attr->attribute_id }}"
                                            data-price="{{ $attr->additional_price }}" title="Edit Product Variant">
                                            <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                        </button>

                                        <form action="{{ route('delete.attribute', $attr->id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-light btn-sm border rounded-2 text-danger p-2 d-inline-flex align-items-center delete-btn"
                                                title="Remove Link">
                                                <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">No product-specific attributes
                                    linked at the moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Global Master Attributes</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-4 text-muted fw-bold">Attribute Group Name</th>
                            <th class="text-muted fw-bold">Registered Option Values</th>
                            <th class="pe-4 text-end text-muted fw-bold" style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($globalAttributes as $gAttr)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $gAttr->name }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse ($gAttr->values as $val)
                                            <span
                                                class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 small font-monospace fw-normal">
                                                {{ $val->value }}
                                            </span>
                                        @empty
                                            <span class="text-muted small italic">No values defined</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1.5">
                                        <button type="button"
                                            class="btn btn-light btn-sm border rounded-2 text-primary p-2 d-inline-flex align-items-center edit-global-btn"
                                            data-bs-toggle="modal" data-bs-target="#editGlobalModal"
                                            data-id="{{ $gAttr->id }}" data-name="{{ $gAttr->name }}"
                                            data-route="{{ route('admin.attribute.update', $gAttr->id) }}"
                                            title="Edit Master Name">
                                            <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                        </button>

                                        <form action="{{ route('admin.attribute.destroy', $gAttr->id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-light btn-sm border rounded-2 text-danger p-2 d-inline-flex align-items-center delete-btn"
                                                title="Delete Attribute Group">
                                                <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted small">No global master attributes
                                    configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProdModal" tabindex="-1" aria-labelledby="editProdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form id="editProdForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom border-light px-4 py-3">
                        <h6 class="modal-title fw-bold text-dark" id="editProdModalLabel">Edit Product Attribute
                            Configuration</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Target Product Selection</label>
                            <select name="product_id" id="edit_product_id" class="form-select rounded-3">
                                @foreach (\App\Models\Product::all() as $p)
                                    <option value="{{ $p->id }}">{{ $p->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Assigned Attribute Variant</label>
                            <select name="attribute_id" id="edit_attribute_id" class="form-select rounded-3">
                                @foreach (\App\Models\Attribute::all() as $a)
                                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold text-secondary">Price Markup Adjustment ($)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted font-monospace">$</span>
                                <input type="number" step="0.01" name="additional_price" id="edit_price"
                                    class="form-control rounded-end-3" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-light px-4 py-2.5 bg-light-subtle">
                        <button type="button"
                            class="btn btn-light border btn-sm rounded-3 px-3 fw-semibold text-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editGlobalModal" tabindex="-1" aria-labelledby="editGlobalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form id="editGlobalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom border-light px-4 py-3">
                        <h6 class="modal-title fw-bold text-dark" id="editGlobalModalLabel">Modify Master Attribute Name
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        <div class="mb-2">
                            <label class="form-label small fw-bold text-secondary">Attribute Structural Identifier</label>
                            <input type="text" name="name" id="edit_name" class="form-control rounded-3"
                                placeholder="e.g., Size, Color, Capacity" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-light px-4 py-2.5 bg-light-subtle">
                        <button type="button"
                            class="btn btn-light border btn-sm rounded-3 px-3 fw-semibold text-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold">Update
                            Master</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Render Lucide System Icons
            lucide.createIcons();

            // Edit Product Bind Matrix
            document.querySelectorAll('.edit-prod-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('editProdForm').action = this.getAttribute(
                        'data-route');
                    document.getElementById('edit_product_id').value = this.getAttribute(
                        'data-product');
                    document.getElementById('edit_attribute_id').value = this.getAttribute(
                        'data-attribute');
                    document.getElementById('edit_price').value = this.getAttribute('data-price');
                });
            });

            // Edit Global Element Bind Matrix
            document.querySelectorAll('.edit-global-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('editGlobalForm').action = this.getAttribute(
                        'data-route');
                    document.getElementById('edit_name').value = this.getAttribute('data-name');
                });
            });

            // Re-usable SweetAlert Confirmation Dialog
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Are you absolutely sure?',
                        text: "This removal operation can break existing product variation mappings!",
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

        // Universal Success Toast Interception logic
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Operation Completed!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
                position: 'center'
            });
        @endif
    </script>
@endsection
