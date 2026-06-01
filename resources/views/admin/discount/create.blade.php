@extends('admin.layouts.layout')

@section('admin_page_title', 'Create Discount - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Create New Discount</h4>
                <p class="text-muted small mb-0">Set up promotional coupon codes or fixed financial store-wide discounts.</p>
            </div>

            <div class="d-flex">
                <a href="{{ url()->previous() }}"
                    class="btn btn-outline-secondary btn-sm rounded-3 w-100 w-sm-auto d-inline-flex align-items-center justify-content-center gap-1.5 fw-medium">
                    <i data-lucide="arrow-left" style="width: 15px; height: 15px;"></i> Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('admin.discount.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h6 class="fw-bold mb-3 text-dark border-bottom border-light pb-2">General Information</h6>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Discount Campaign Title</label>
                            <input type="text" class="form-control rounded-3" name="title" value="{{ old('title') }}"
                                placeholder="e.g., Summer Flash Sale" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Promo / Coupon Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-start-3 text-uppercase font-monospace"
                                        id="coupon_code" name="code" value="{{ old('code') }}"
                                        placeholder="e.g., SUMMER50">
                                    <button class="btn btn-outline-primary px-3 fw-semibold small" type="button"
                                        onclick="generateCode()">Generate</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Discount Calculation Type</label>
                                <select class="form-select rounded-3" name="type" required>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)</option>
                                    <option value="fixed_amount" {{ old('type') == 'fixed_amount' ? 'selected' : '' }}>
                                        Fixed Amount ($)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Discount Value Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control rounded-3"
                                    name="value" value="{{ old('value') }}" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Minimum Basket Requirement
                                    ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control rounded-3"
                                    name="min_requirement" value="{{ old('min_requirement') }}" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h6 class="fw-bold mb-3 text-dark border-bottom border-light pb-2">Active Timeline Schedule</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Start Validity Date</label>
                                <input type="datetime-local" class="form-control rounded-3" name="start_date"
                                    value="{{ old('start_date') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">End Expiration Date</label>
                                <input type="datetime-local" class="form-control rounded-3" name="end_date"
                                    value="{{ old('end_date') }}">
                                <div class="form-text text-muted small" style="font-size: 0.75rem;">Leave empty for
                                    permanent structural lifetime.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h6 class="fw-bold mb-3 text-dark border-bottom border-light pb-2">Visibility Status</h6>
                        <div class="form-check form-switch pt-1">
                            <input class="form-check-input style-pointer-device" type="checkbox" role="switch"
                                id="status" name="status" value="1"
                                {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <label
                                class="form-check-label small fw-semibold text-dark user-select-none style-pointer-device"
                                for="status">Publish as Active</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit"
                            class="btn btn-primary btn-sm rounded-3 py-2.5 fw-semibold shadow-sm d-inline-flex align-items-center justify-content-center gap-1.5">
                            <i data-lucide="save" style="width: 16px; height: 16px;"></i> Save Discount
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Trigger Dynamic Session Alert Confirmation Popups
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Discount Created!',
                text: "{!! session('success') !!}",
                confirmButtonColor: '#3b82f6',
                timer: 2500
            });
        @endif

        // Mapping validation catch exceptions pipeline
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Data Validation Failed',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#ef4444'
            });
        @endif

        // Strategic Alpha-Numeric Random Code Vector Generation Engine
        function generateCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = 'DISC-';
            for (let i = 0; i < 6; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('coupon_code').value = code;
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Render Structural Vector Node Graphics Elements
            lucide.createIcons();
        });
    </script>
@endsection
