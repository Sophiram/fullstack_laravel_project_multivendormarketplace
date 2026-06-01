@extends('admin.layouts.layout')

@section('admin_page_title', 'Add Payment Method - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Add New Payment Method</h4>
                <p class="text-muted small mb-0">Please fill in the structured configuration fields below to scale system
                    gateway options.</p>
            </div>
            <div>
                <a href="{{ route('admin.payment.manage') }}"
                    class="btn btn-light border btn-sm rounded-3 px-3 py-2 fw-medium d-inline-flex align-items-center gap-1.5 text-secondary w-100">
                    <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back to Methods
                </a>
            </div>
        </div>

        {{-- Validation Error Pipeline Logs --}}
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-start gap-2.5">
                <span class="text-danger mt-0.5 d-inline-flex">
                    <i data-lucide="alert-octagon" style="width: 18px; height: 18px;"></i>
                </span>
                <div class="small fw-semibold">
                    <span class="d-block text-dark mb-1 fw-bold">Configuration Validation Failed:</span>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Core Configuration Form Input Architecture --}}
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-white">
            <form action="{{ route('admin.payment.store') }}" method="POST" enctype="multipart/form-data" class="m-0">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-3.5">
                        <div class="col-12 col-md-6">
                            <label class="form-label text-dark fw-bold small mb-1.5">Method Name Reference</label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-2 small"
                                placeholder="e.g., ABA Pay, Wing Bank, Stripe API" required>
                            <div class="form-text text-muted mt-1" style="font-size: 0.75rem;">The custom structural gateway
                                text label to be displayed to customers during checkout.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label text-dark fw-bold small mb-1.5">Payment Integration Type</label>
                            <select name="type" class="form-select bg-light border-0 py-2 small select-custom-icon"
                                required>
                                <option value="" disabled selected>Select execution mode channel...</option>
                                <option value="direct_integration">Direct Integration (API Module)</option>
                                <option value="manual_bank">Manual Bank Wire Node</option>
                            </select>
                            <div class="form-text text-muted mt-1" style="font-size: 0.75rem;">Select the backend runtime
                                execution and verification integration layout mechanism.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label text-dark fw-bold small mb-1.5">Upload Logo Asset File</label>
                            <input type="file" name="logo" class="form-control bg-light border-0 py-2 small">
                            <div class="form-text text-muted mt-1" style="font-size: 0.75rem;">Optimize clear micro
                                dimensions asset (JPG, PNG) recommended for UI processing.</div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-top p-3 px-4 d-md-flex justify-content-end gap-2 text-end">
                    <a href="{{ route('admin.payment.manage') }}"
                        class="btn btn-sm btn-light border rounded-3 px-4 py-2 small fw-semibold text-secondary w-100 w-md-auto mb-2 mb-md-0">
                        Cancel Changes
                    </a>
                    <button type="submit"
                        class="btn btn-sm btn-primary rounded-3 px-4 py-2 small fw-semibold d-inline-flex align-items-center justify-content-center gap-1.5 shadow-sm w-100 w-md-auto">
                        <i data-lucide="save" style="width: 15px; height: 15px;"></i> Save Payment Method
                    </button>
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

        // Catch and process flash success operations pipeline records
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Gateway Form Configured',
                text: "{!! session('success') !!}",
                timer: 2000,
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
