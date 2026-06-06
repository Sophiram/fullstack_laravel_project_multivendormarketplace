@extends('vendor.layouts.layout')

@section('vendor_page_title', 'Vendor Profile')

@section('vendor_layout')
    <div class="container-fluid px-2 px-md-4 py-3">
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                    <i data-feather="user" class="me-2 text-primary"></i>
                    Vendor Profile Settings
                </h3>
                <p class="text-muted small mb-0">Manage your vendor store information and account settings.</p>
            </div>
            <div>
                <a href="{{ route('vendor.settings') }}"
                    class="btn btn-outline-secondary bg-white text-dark border rounded-3 px-3 py-2 small fw-semibold transition-all d-inline-flex align-items-center shadow-sm"
                    style="font-size: 13px;">
                    <i data-feather="lock" class="me-2 text-muted" style="width: 15px; height: 15px;"></i> Security &
                    Password
                </a>
            </div>
        </div>

        <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="row g-4">
                <div class="col-12 col-lg-4 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4 bg-white text-center p-4">
                        <div class="position-relative d-inline-block mx-auto mb-3">
                            <img id="profileImagePreview"
                                src="{{ $vendor->image ? asset('storage/' . $vendor->image) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->name) . '&size=128&background=4f46e5&color=fff' }}"
                                alt="{{ $vendor->name }}"
                                class="img-fluid rounded-circle p-1 border border-2 border-primary-subtle shadow-sm"
                                style="width: 110px; height: 110px; object-fit: cover;" />
                        </div>

                        <div class="mb-3">
                            <label for="imageUpload"
                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm transition-all"
                                style="cursor: pointer; font-size: 13px; font-weight: 500;">
                                <i data-feather="camera" style="width: 13px; height: 13px;" class="me-1"></i> Change Photo
                            </label>
                            <input type="file" id="imageUpload" name="image" class="d-none" accept="image/*"
                                onchange="previewImage(event)">
                        </div>

                        <h5 class="fw-bold text-dark mb-1">{{ $vendor->name }}</h5>
                        <div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary text-uppercase px-2.5 py-1"
                                style="font-size: 10px; font-weight: 600; letter-spacing: 0.3px;">
                                Vendor Account
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8 col-xl-9">
                    <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                            <span class="p-2 rounded-3 bg-light text-secondary me-2 d-inline-flex">
                                <i data-feather="shopping-bag" style="width: 18px; height: 18px;"></i>
                            </span>
                            Vendor Information
                        </h5>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Full Name</label>
                                <input type="text" class="form-control custom-input @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $vendor->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1 d-flex align-items-center">
                                        <i data-feather="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Email Address</label>
                                <input type="email" class="form-control custom-input @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $vendor->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1 d-flex align-items-center">
                                        <i data-feather="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Commission Rate System</label>
                                <div class="alert alert-info border-0 bg-light-subtle text-dark p-3 rounded-3 mb-0 d-flex align-items-start"
                                    style="border-left: 4px solid #0ea5e9 !important;">
                                    <i data-feather="info" class="text-info me-2 flex-shrink-0 mt-0.5"
                                        style="width: 16px; height: 16px;"></i>
                                    <div class="small">
                                        <span class="fw-semibold text-info-emphasis d-block mb-0.5">Category Base
                                            Commissions</span>
                                        Applied automatically during system checkout metrics based on relative product
                                        classification paths.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary small d-block mb-2">Gender</label>
                                <div class="d-flex gap-4 pt-2">
                                    <div class="form-check custom-radio-wrapper">
                                        <input class="form-check-input custom-radio" type="radio" name="gender"
                                            id="genderMale" value="male"
                                            {{ old('gender', $vendor->gender) == 'male' ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark small fw-medium"
                                            for="genderMale">Male</label>
                                    </div>
                                    <div class="form-check custom-radio-wrapper">
                                        <input class="form-check-input custom-radio" type="radio" name="gender"
                                            id="genderFemale" value="female"
                                            {{ old('gender', $vendor->gender) == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark small fw-medium"
                                            for="genderFemale">Female</label>
                                    </div>
                                </div>
                                @error('gender')
                                    <div class="text-danger small mt-1 d-flex align-items-center">
                                        <i data-feather="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-secondary small">Bank Account Information</label>
                                <textarea name="bank_account_info" class="form-control custom-input @error('bank_account_info') is-invalid @enderror"
                                    rows="3" placeholder="Provide bank names, swift accounts, or deposit route identifiers...">{{ old('bank_account_info', $vendor->vendor->bank_account_info ?? '') }}</textarea>
                                @error('bank_account_info')
                                    <div class="text-danger small mt-1 d-flex align-items-center">
                                        <i data-feather="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top border-light">
                            <button type="submit"
                                class="btn btn-primary px-4 py-2 rounded-3 fw-semibold w-100 w-md-auto transition-all">
                                <i data-feather="save" style="width: 16px; height: 16px;" class="me-2"></i> Save
                                Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-input {
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 8px !important;
            transition: all 0.2s ease-in-out;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #4f46e5;
            box-shadow: none;
        }

        .custom-input.is-invalid {
            border-color: #ef4444 !important;
            background-image: none;
        }

        .custom-radio-wrapper .custom-radio {
            cursor: pointer;
            border-color: #cbd5e1;
        }

        .custom-radio-wrapper .custom-radio:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .bg-light-subtle {
            background-color: #f8fafc !important;
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        @media (max-width: 767.98px) {
            .w-md-auto {
                width: 100% !important;
            }
        }

        /* Ensures layout sizes fit beautifully with Feather SVGs rendering engine */
        .feather {
            display: inline-block;
            vertical-align: middle;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Replaced Lucide initialization engine with Feather architecture
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            @if (session('status') === 'profile-updated' || session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Your profile has been successfully updated.',
                    showConfirmButton: false,
                    timer: 3000,
                    position: 'top-end',
                    toast: true,
                    timerProgressBar: true
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: '{!! addslashes($errors->first()) !!}',
                    showConfirmButton: false,
                    timer: 4000,
                    position: 'top-end',
                    toast: true,
                    timerProgressBar: true
                });
            @endif
        });

        function previewImage(event) {
            const imagePreview = document.getElementById('profileImagePreview');
            if (event.target.files && event.target.files[0]) {
                imagePreview.src = URL.createObjectURL(event.target.files[0]);
            }
        }
    </script>
@endsection
