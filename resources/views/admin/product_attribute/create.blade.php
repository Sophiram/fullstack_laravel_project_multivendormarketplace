@extends('admin.layouts.layout')

@section('admin_page_title', 'Create Product Attribute - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Create Product Attribute</h4>
                <p class="text-muted small mb-0">Define structural global attributes such as sizes, operational colors, or
                    base material configurations.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Attribute Structural Definition</h5>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div
                                class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm d-flex align-items-start gap-2">
                                <i data-lucide="alert-circle" class="text-danger mt-0.5 flex-shrink-0"
                                    style="width: 18px; height: 18px;"></i>
                                <ul class="mb-0 small ps-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.attribute.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Attribute Group Name</label>
                                <input type="text" name="name" class="form-control rounded-3"
                                    placeholder="e.g., Size, Color, Fabric" value="{{ old('name') }}" required>
                                <div class="form-text text-muted" style="font-size: 0.75rem;">This defines the global parent
                                    object structural category.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Default Sub-Option Values</label>
                                <div id="values-container">
                                    <div class="d-flex gap-2 mb-2 alignment-wrapper">
                                        <input type="text" name="values[]" class="form-control rounded-3"
                                            placeholder="e.g., Small, Blue, Cotton" required>
                                        <div style="width: 38px;" class="d-none d-md-block flex-shrink-0"></div>
                                    </div>
                                </div>

                                <button type="button"
                                    class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1 mt-1"
                                    id="add-value-btn">
                                    <i data-lucide="plus" style="width: 14px; height: 14px;"></i> Add Segment Value
                                </button>
                            </div>

                            <hr class="text-muted opacity-25 my-4">

                            <div class="d-flex gap-2">
                                <button type="submit"
                                    class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold shadow-sm">
                                    Save Default Attribute
                                </button>
                                <a href="{{ url()->previous() }}"
                                    class="btn btn-light border btn-sm rounded-3 px-3 py-2 fw-semibold text-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Render Global Icons Node Matrix
            lucide.createIcons();

            // Append dynamic multi-value mapping input vectors
            document.getElementById('add-value-btn').addEventListener('click', function() {
                const container = document.getElementById('values-container');
                const div = document.createElement('div');
                div.className = 'd-flex gap-2 mb-2 dynamic-input-row';
                div.innerHTML = `
                    <input type="text" name="values[]" class="form-control rounded-3" placeholder="Enter option value string" required>
                    <button type="button" class="btn btn-light border text-danger rounded-3 p-2 d-inline-flex align-items-center justify-content-center remove-btn flex-shrink-0" style="width: 38px; height: 38px;" title="Remove element row">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                    </button>
                `;
                container.appendChild(div);

                // Inject downstream instance mapping metrics onto lucide scope
                lucide.createIcons();

                // Structural binding context removal monitoring loop
                div.querySelector('.remove-btn').addEventListener('click', function() {
                    div.remove();
                });
            });
        });
    </script>
@endsection
