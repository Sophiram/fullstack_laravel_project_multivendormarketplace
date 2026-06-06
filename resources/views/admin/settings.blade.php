@extends('admin.layouts.layout')

@section('admin_page_title', 'Settings - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-2 py-2">
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">Home Page Settings</h4>
            <p class="text-muted small">Configure your storefront homepage layout grid, promotional highlights, and marketing
                campaigns.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white py-3 border-bottom border-light px-4">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Marketing & Campaign Layout</h5>
                    </div>

                    <div class="card-body p-4">
                        {{-- Validation Errors Pipeline Logs --}}
                        @if ($errors->any())
                            <div
                                class="alert alert-danger border-0 shadow-sm rounded-3 p-3 mb-3 d-flex align-items-start gap-2">
                                <span class="text-danger mt-0.5 d-inline-flex">
                                    <i data-lucide="alert-octagon" style="width: 16px; height: 16px;"></i>
                                </span>
                                <div class="small fw-semibold">
                                    <ul class="mb-0 ps-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Operation Success Feedback Alert --}}
                        @if (session('success'))
                            <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 mb-3 d-flex align-items-center gap-2 small fw-semibold"
                                role="alert">
                                <i data-lucide="check-circle" class="text-success" style="width: 16px; height: 16px;"></i>
                                <span class="text-dark">{!! session('success') !!}</span>
                            </div>
                        @endif

                        <form action="{{ route('admin.homepagesetting.update', $homepagesetting->id) }}" method="POST"
                            class="m-0">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Flash Discount Product</label>
                                    <select name="discounted_product_id"
                                        class="form-select select2 bg-light border-0 py-2 small">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $homepagesetting->discounted_product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Discount Proportion (%)</label>
                                    <input type="number" name="discount_percent"
                                        value="{{ $homepagesetting->discount_percent }}"
                                        class="form-control bg-light border-0 py-2 small" placeholder="e.g., 20"
                                        min="0" max="100">
                                </div>
                            </div>

                            <hr class="my-3.5 opacity-25">

                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold small mb-1.5">Campaign Header Label</label>
                                <input type="text" name="discount_heading"
                                    value="{{ $homepagesetting->discount_heading }}"
                                    class="form-control bg-light border-0 py-2 small"
                                    placeholder="e.g., Seasonal Summer Sale 2026">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold small mb-1.5">Sub-text Description Label</label>
                                <input type="text" name="discount_subheading"
                                    value="{{ $homepagesetting->discount_subheading }}"
                                    class="form-control bg-light border-0 py-2 small"
                                    placeholder="e.g., Enjoy massive discount tiers up to 50% off on all catalog collections.">
                            </div>

                            <hr class="my-3.5 opacity-25">

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Featured Product Showcase
                                        1</label>
                                    <select name="featured_product_1_id"
                                        class="form-select select2 bg-light border-0 py-2 small">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $homepagesetting->featured_product_1_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Featured Product Showcase
                                        2</label>
                                    <select name="featured_product_2_id"
                                        class="form-select select2 bg-light border-0 py-2 small">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $homepagesetting->featured_product_2_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid pt-2 border-top border-light">
                                <button type="submit"
                                    class="btn btn-primary rounded-3 py-2 small fw-semibold d-inline-flex align-items-center justify-content-center gap-1.5 shadow-sm">
                                    <i data-lucide="save" style="width: 15px; height: 15px;"></i> Update Layout Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Render Structural Vector Graphics Icons Engine
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endsection
