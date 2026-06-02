@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Product - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Product Management</h4>
                <p class="text-muted small mb-0">Add, edit, track inventory, and manage all retail products available on your
                    store.</p>
            </div>
            {{-- 🔄 ប្តូរពីការដើរទៅកាន់ Route មកជាការបើក Modal វិញ --}}
            <button type="button"
                class="btn btn-primary rounded-3 px-3 py-2 fw-medium d-inline-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Add Product
            </button>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div
                    class="card border-0 shadow-sm rounded-4 p-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Products</small>
                        <h4 class="fw-bold text-dark mb-0">{{ $data['totalProducts'] }}</h4>
                    </div>
                    <div class="p-2 bg-light rounded-3 text-primary">
                        <i data-lucide="package" style="width: 24px; height: 24px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div
                    class="card border-0 shadow-sm rounded-4 p-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.72rem; letter-spacing: 0.5px;">Active Visible</small>
                        <h4 class="fw-bold text-success mb-0">{{ $data['activeProducts'] }}</h4>
                    </div>
                    <div class="p-2 bg-success-subtle rounded-3 text-success">
                        <i data-lucide="eye" style="width: 24px; height: 24px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div
                    class="card border-0 shadow-sm rounded-4 p-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.72rem; letter-spacing: 0.5px;">Low Stock Alert</small>
                        <h4 class="fw-bold text-warning mb-0">{{ $data['lowStock'] }}</h4>
                    </div>
                    <div class="p-2 bg-warning-subtle rounded-3 text-warning">
                        <i data-lucide="alert-triangle" style="width: 24px; height: 24px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div
                    class="card border-0 shadow-sm rounded-4 p-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.72rem; letter-spacing: 0.5px;">Out of Stock</small>
                        <h4 class="fw-bold text-danger mb-0">{{ $data['outOfStock'] }}</h4>
                    </div>
                    <div class="p-2 bg-danger-subtle rounded-3 text-danger">
                        <i data-lucide="slash" style="width: 24px; height: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <div class="row g-2 align-items-center justify-content-between">
                    <div class="col-12 col-md-4">
                        <form method="GET" action="{{ route('product.manage') }}">
                            <div class="input-group">
                                <span
                                    class="input-group-text bg-light border-0 border-start text-muted rounded-start-3 px-3">
                                    <i data-lucide="search" style="width: 16px; height: 16px;"></i>
                                </span>
                                <input type="text" name="search"
                                    class="form-control bg-light border-0 small rounded-end-3 py-2"
                                    placeholder="Search by product name, SKU..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>

                    <div class="col-12 col-md-auto">
                        <form method="GET" action="{{ route('product.manage') }}" class="d-flex gap-2">
                            <select name="category_id"
                                class="form-select form-select-sm rounded-3 bg-light border-0 text-secondary py-2 px-3"
                                onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach (\App\Models\Category::all() as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="stock_status"
                                class="form-select form-select-sm rounded-3 bg-light border-0 text-secondary py-2 px-3"
                                onchange="this.form.submit()">
                                <option value="">All Stock Status</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In
                                    Stock</option>
                                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>
                                    Low Stock</option>
                                <option value="out_of_stock"
                                    {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-bold">Product Details</th>
                                <th class="py-3 text-muted fw-bold">SKU</th>
                                <th class="py-3 text-muted fw-bold">Category</th>
                                <th class="py-3 text-muted fw-bold">Price</th>
                                <th class="py-3 text-muted fw-bold">Stock Qty</th>
                                <th class="py-3 text-muted fw-bold">Status</th>
                                <th class="pe-4 py-3 text-end text-muted fw-bold" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                    alt="{{ $product->product_name }}"
                                                    class="rounded-3 border border-light shadow-sm"
                                                    style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('assets/images/default-product.png') }}" alt="No Image"
                                                    class="rounded-3 border border-light shadow-sm"
                                                    style="width: 48px; height: 48px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <span
                                                    class="d-block fw-semibold text-dark mb-0">{{ $product->product_name }}</span>
                                                <small class="text-muted font-monospace"
                                                    style="font-size: 0.75rem;">{{ $product->brand ?? 'No Brand' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-monospace text-secondary">{{ $product->sku }}</td>
                                    <td><span
                                            class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-2 fw-medium">{{ $product->category->category_name }}</span>
                                    </td>
                                    <td class="fw-bold text-dark">${{ number_format($product->regular_price, 2) }}</td>
                                    <td>
                                        @if ($product->stock_quantity <= 0)
                                            <span class="text-danger fw-semibold">0 units</span>
                                        @elseif($product->stock_quantity <= 5)
                                            <span class="text-warning fw-semibold">{{ $product->stock_quantity }}
                                                units</span>
                                        @else
                                            <span class="text-secondary fw-semibold">{{ $product->stock_quantity }}
                                                units</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill px-2.5 py-1.5 font-monospace {{ $product->status == 'published' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' }}">
                                            {{ strtoupper($product->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button"
                                                class="btn btn-sm btn-light border text-primary rounded-2 p-2 d-inline-flex align-items-center edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                data-route="{{ route('product.update', $product->id) }}"
                                                data-name="{{ $product->product_name }}"
                                                data-price="{{ $product->regular_price }}"
                                                data-stock="{{ $product->stock_quantity }}"
                                                data-status="{{ $product->status }}" title="Edit Product">
                                                <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                            </button>
                                            <form action="{{ route('product.destroy', $product->id) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-light border text-danger rounded-2 p-2 d-inline-flex align-items-center delete-btn"
                                                    title="Delete Product">
                                                    <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i data-lucide="inbox" class="d-block mx-auto mb-2 text-secondary"
                                            style="width: 32px; height: 32px;"></i>
                                        No products found matching the criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="card-footer bg-white py-3 border-top border-light d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                <small class="text-muted small">
                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of
                    {{ $products->total() }} entries
                </small>
                <nav aria-label="Pagination">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow rounded-4">
                <form id="editProductForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom px-4 py-3">
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                            <i data-lucide="edit" class="text-primary" style="width: 20px; height: 20px;"></i> Edit
                            Product Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary mb-2">Product Name</label>
                                <input type="text" name="product_name" id="edit_name" class="form-control rounded-3"
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary mb-2">Price ($ USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border text-muted px-3">$</span>
                                    <input type="number" step="0.01" name="price" id="edit_price"
                                        class="form-control rounded-end-3" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary mb-2">Stock Quantity</label>
                                <input type="number" name="stock_quantity" id="edit_stock"
                                    class="form-control rounded-3" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary mb-2">Visibility Status</label>
                                <select name="status" id="edit_status" class="form-select rounded-3">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-end gap-2 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light rounded-3 fw-medium"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ➕ Modal សម្រាប់បង្កើតផលិតផលថ្មី (Create Product Pop-up Modal) --}}
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- ប្រើកម្រិត XL ដើម្បីឱ្យមានផ្ទៃធំទូលាយងាយស្រួលបំពេញ --}}
            <div class="modal-content border-0 shadow rounded-4">

                {{-- 🛠️ ផ្នែកក្បាល Modal --}}
                <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i data-lucide="plus-circle" class="text-primary" style="width: 22px; height: 22px;"></i> Add New
                        Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- 📝 ទម្រង់ Form បង្កើតផលិតផល --}}
                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data"
                    id="modalProductForm">
                    @csrf
                    <input type="hidden" name="vendor_id" value="{{ auth()->id() }}">
                    <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                        <div class="row g-4">

                            {{-- ⬅️ ផ្នែកខាងឆ្វេង (ព័ត៌មានទូទៅ រូបភាព និង ជម្រើសលម្អិត) --}}
                            <div class="col-12 col-lg-8">
                                <!-- General Info -->
                                <div class="card border shadow-sm rounded-3 mb-4">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">General Information</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-secondary small">Product Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="product_name" class="form-control py-2 rounded-2"
                                                placeholder="e.g., Lenovo IdeaPad 3" required>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold text-secondary small">Description</label>
                                            <textarea name="description" rows="4" class="form-control rounded-2"
                                                placeholder="Provide detailed specifications..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="card border shadow-sm rounded-3 mb-4">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Product Gallery</h6>
                                        <div class="border border-dashed p-3 text-center rounded-3 bg-light position-relative"
                                            style="border-style: dashed !important;">
                                            <i class="bi bi-images fs-2 text-muted mb-1 d-block"></i>
                                            <input type="file" name="images[]"
                                                class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                multiple id="modal-image-input" style="cursor: pointer;">
                                            <span class="text-primary fw-bold small">Click to upload</span> <span
                                                class="small">or drag files here</span>
                                        </div>
                                        <div id="modal-image-preview-container" class="d-flex flex-wrap gap-2 mt-2"></div>
                                    </div>
                                </div>

                                <!-- Variants & Attributes -->
                                <div class="card border shadow-sm rounded-3">
                                    <div class="card-body p-3">
                                        <div
                                            class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                            <h6 class="fw-bold text-dark mb-0">Variants & Attributes</h6>
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm fw-bold px-2 rounded-2"
                                                id="modal-add-attribute-btn">
                                                <i class="bi bi-plus-circle me-1"></i> Add Row
                                            </button>
                                        </div>
                                        <div id="modal-attributes-container">
                                            <div
                                                class="p-3 mb-2 bg-light rounded-3 border position-relative modal-attribute-row">
                                                <button type="button"
                                                    class="btn btn-sm btn-link text-danger position-absolute top-0 end-0 mt-1 me-1 p-0 modal-remove-row-btn d-none shadow-none">
                                                    <i class="bi bi-x-circle-fill fs-5"></i>
                                                </button>
                                                <div class="row g-2">
                                                    <div class="col-12 col-md-4">
                                                        <label
                                                            class="form-label mb-1 small text-secondary fw-semibold">Type</label>
                                                        <select name="attributes[0][attribute_id]"
                                                            class="form-select form-select-sm modal-attribute-selector">
                                                            <option value="">-- Select --</option>
                                                            @foreach (\App\Models\Attribute::all() as $attr)
                                                                {{-- ទាញយក Attribute ទាំងអស់មកបង្ហាញ --}}
                                                                <option value="{{ $attr->id }}">{{ $attr->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label
                                                            class="form-label mb-1 small text-secondary fw-semibold">Value</label>
                                                        <select name="attributes[0][attribute_value_id]"
                                                            class="form-select form-select-sm modal-value-selector"
                                                            disabled>
                                                            <option value="">-- Value --</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label
                                                            class="form-label mb-1 small text-secondary fw-semibold">Extra
                                                            Price ($)</label>
                                                        <input type="number" step="0.01"
                                                            name="attributes[0][additional_price]"
                                                            class="form-control form-control-sm" placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ➡️ ផ្នែកខាងស្តាំ (ការកំណត់តម្លៃ ឃ្លាំង និង ស្ថាប័ន) --}}
                            <div class="col-12 col-lg-4">
                                <!-- Organization -->
                                <div class="card border shadow-sm rounded-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Organization</h6>
                                        <div class="mb-2">
                                            <label class="form-label small text-secondary fw-semibold">Select Store <span
                                                    class="text-danger">*</span></label>
                                            <select name="store_id" class="form-select form-select-sm" required>
                                                @foreach (\App\Models\Store::all() as $store)
                                                    <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="mb-2">
                                            <label class="form-label small text-secondary fw-semibold">Category <span
                                                    class="text-danger">*</span></label>
                                            <select name="category_id" id="modal_category_id"
                                                class="form-select form-select-sm" required>
                                                <option value="">Select Category</option>
                                                @foreach (\App\Models\Category::all() as $category)
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small text-secondary fw-semibold">Subcategory</label>
                                            <select name="subcategory_id" id="modal_subcategory_id"
                                                class="form-select form-select-sm">
                                                <option value="">Select Subcategory</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing & Stock -->
                                <div class="card border shadow-sm rounded-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Pricing & Inventory</h6>
                                        <div class="mb-2">
                                            <label class="form-label small text-secondary fw-semibold">Product SKU <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="sku" class="form-control form-control-sm"
                                                placeholder="e.g., SKU123" required>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label small text-secondary fw-semibold">Price ($)
                                                    *</label>
                                                <input type="number" step="0.01" name="regular_price"
                                                    class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-secondary fw-semibold">Discount
                                                    ($)</label>
                                                <input type="number" step="0.01" name="discounted_price"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small text-secondary fw-semibold">Tax (%)</label>
                                                <input type="number" step="0.01" name="tax_rate"
                                                    class="form-control form-control-sm" value="0.0">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-secondary fw-semibold">Stock Qty
                                                    *</label>
                                                <input type="number" name="stock_quantity"
                                                    class="form-control form-control-sm" value="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status / Visibility -->
                                <div class="card border shadow-sm rounded-3">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Status</h6>
                                        <label class="form-label small text-secondary fw-semibold">Visibility
                                            Status</label>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- 📥 ផ្នែកប៊ូតុងខាងក្រោម --}}
                    <div
                        class="modal-footer border-top d-flex justify-content-end gap-2 px-4 py-3 bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-light btn-sm rounded-3 fw-medium"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-bold">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Publish Product
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Lucide Icons
            lucide.createIcons();

            // គ្រប់គ្រងការចុចប៊ូតុង Delete
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this product dynamic!",
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

            // គ្រប់គ្រងការចុចប៊ូតុង Edit ដើម្បីទាញទិន្នន័យដាក់ Modal Input
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('editProductForm').action = this.getAttribute(
                        'data-route');
                    document.getElementById('edit_name').value = this.getAttribute('data-name');
                    document.getElementById('edit_price').value = this.getAttribute('data-price');
                    document.getElementById('edit_stock').value = this.getAttribute('data-stock');
                    document.getElementById('edit_status').value = this.getAttribute('data-status');

                    setTimeout(() => {
                        lucide.createIcons();
                    }, 150);
                });
            });

            // បន្ថែម Loading ពេលកំពុងរក្សាទុកការកែប្រែ
            document.getElementById('editProductForm').addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
            });
        });

        // បង្ហាញ Success Popup របស់ SweetAlert2
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalRowIndex = 1;
            const modalContainer = document.getElementById('modal-attributes-container');
            const modalAddBtn = document.getElementById('modal-add-attribute-btn');
            const modalImageInput = document.getElementById('modal-image-input');
            const modalPreviewContainer = document.getElementById('modal-image-preview-container');

            // 📷 គ្រប់គ្រងការបង្ហាញរូបភាព Preview ក្នុង Modal
            if (modalImageInput) {
                modalImageInput.addEventListener('change', function() {
                    modalPreviewContainer.innerHTML = '';
                    if (this.files) {
                        Array.from(this.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const div = document.createElement('div');
                                div.className = 'position-relative border rounded p-1 bg-white';
                                div.style.width = '60px';
                                div.style.height = '60px';
                                div.innerHTML =
                                    `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover rounded">`;
                                modalPreviewContainer.appendChild(div);
                            }
                            reader.readAsDataURL(file);
                        });
                    }
                });
            }

            // 🗂️ យន្តការទាញយក Subcategory តាមរយៈ AJAX ក្នុង Modal
            const modalCatSelect = document.getElementById('modal_category_id');
            if (modalCatSelect) {
                modalCatSelect.addEventListener('change', function() {
                    const categoryId = this.value;
                    const subcategorySelect = document.getElementById('modal_subcategory_id');
                    subcategorySelect.innerHTML = '<option value="">Loading...</option>';

                    if (!categoryId) {
                        subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                        return;
                    }

                    fetch(`/api/categories/${categoryId}/subcategories`)
                        .then(response => response.json())
                        .then(data => {
                            subcategorySelect.innerHTML =
                                '<option value="">Select Subcategory</option>';
                            data.forEach(sub => {
                                subcategorySelect.innerHTML +=
                                    `<option value="${sub.id}">${sub.subcategory_name}</option>`;
                            });
                        })
                        .catch(error => {
                            subcategorySelect.innerHTML = '<option value="">Error loading...</option>';
                        });
                });
            }

            // 🔄 បច្ចុប្បន្នភាពប៊ូតុងលុបជួរលក្ខណៈសម្បត្តិ
            function updateModalRemoveButtons() {
                const rows = modalContainer.querySelectorAll('.modal-attribute-row');
                rows.forEach((row) => {
                    const btn = row.querySelector('.modal-remove-row-btn');
                    btn.classList.toggle('d-none', rows.length <= 1);
                });
            }

            // ⚡ AJAX ទាញយកទិន្នន័យតម្លៃ Attribute (Value Selector)
            modalContainer.addEventListener('change', function(e) {
                if (e.target.classList.contains('modal-attribute-selector')) {
                    const attrId = e.target.value;
                    const row = e.target.closest('.modal-attribute-row');
                    const valueSelect = row.querySelector('.modal-value-selector');

                    if (!attrId) {
                        valueSelect.innerHTML = '<option value="">-- Value --</option>';
                        valueSelect.disabled = true;
                        return;
                    }

                    valueSelect.innerHTML = '<option value="">...</option>';
                    valueSelect.disabled = false;

                    fetch(`/api/attributes/${attrId}/values`)
                        .then(response => response.json())
                        .then(data => {
                            valueSelect.innerHTML = '<option value="">-- Value --</option>';
                            data.forEach(val => {
                                valueSelect.innerHTML +=
                                    `<option value="${val.id}">${val.value}</option>`;
                            });
                        })
                        .catch(() => {
                            valueSelect.innerHTML = '<option value="">Error</option>';
                        });
                }
            });

            // ➕ បន្ថែមជួរលក្ខណៈសម្បត្តិថ្មី (Dynamic Add Row)
            modalAddBtn.addEventListener('click', function() {
                const firstRow = document.querySelector('.modal-attribute-row');
                const clone = firstRow.cloneNode(true);

                clone.querySelector('.modal-attribute-selector').name =
                    `attributes[${modalRowIndex}][attribute_id]`;
                clone.querySelector('.modal-value-selector').name =
                    `attributes[${modalRowIndex}][attribute_value_id]`;
                clone.querySelector('input[type="number"]').name =
                    `attributes[${modalRowIndex}][additional_price]`;

                clone.querySelector('.modal-attribute-selector').value = '';
                clone.querySelector('.modal-value-selector').innerHTML =
                    '<option value="">-- Value --</option>';
                clone.querySelector('.modal-value-selector').disabled = true;
                clone.querySelector('input[type="number"]').value = '';

                modalContainer.appendChild(clone);
                modalRowIndex++;
                updateModalRemoveButtons();
            });

            // ➖ លុបជួរលក្ខណៈសម្បត្តិ (Dynamic Remove Row)
            modalContainer.addEventListener('click', function(e) {
                if (e.target.closest('.modal-remove-row-btn')) {
                    e.target.closest('.modal-attribute-row').remove();
                    updateModalRemoveButtons();
                }
            });

            // បន្ថែមចលនា Loading ពេល Submit Form
            document.getElementById('modalProductForm').addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Publishing...';
            });
        });
    </script>
@endsection
