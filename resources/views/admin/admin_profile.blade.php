@extends('admin.layouts.layout')

@section('admin_page_title', 'Admin Profile - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">Account Settings</h4>
            <p class="text-muted small">Update your profile information, manage security, and personalize your admin account.
            </p>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="m-0">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="{{ Auth::user()->image ? asset('upload/admin_images/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . Auth::user()->name }}"
                                    class="rounded-circle shadow-sm border border-4 border-white object-cover"
                                    style="width: 140px; height: 140px; object-fit: cover;" id="profileImage">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h6>
                            <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>

                            <label for="imageUpload"
                                class="btn btn-outline-primary btn-sm rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 small">
                                <i data-lucide="camera" style="width: 14px; height: 14px;"></i> Change Photo
                            </label>
                            <input type="file" name="image" id="imageUpload" class="d-none" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                        <div class="card-header bg-white py-3 px-4 border-bottom border-light">
                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Personal Information</h5>
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
                                    <i data-lucide="check-circle" class="text-success"
                                        style="width: 16px; height: 16px;"></i>
                                    <span class="text-dark">{!! session('success') !!}</span>
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Full Name</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                                        class="form-control bg-light border-0 py-2 small" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Email Address</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}"
                                        class="form-control bg-light border-0 py-2 small" required>
                                </div>
                            </div>

                            <hr class="my-4 opacity-25">
                            <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">Security Node (Leave blank if
                                unchanged)</h6>

                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold small mb-1.5">Current Password</label>
                                <input type="password" name="current_password"
                                    class="form-control bg-light border-0 py-2 small" placeholder="••••••••">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">New Password</label>
                                    <input type="password" name="password" class="form-control bg-light border-0 py-2 small"
                                        placeholder="Minimum 8 characters">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small mb-1.5">Confirm Password</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control bg-light border-0 py-2 small" placeholder="Repeat new password">
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top border-light d-flex justify-content-end">
                                <button type="submit"
                                    class="btn btn-primary rounded-3 px-4 py-2 small fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm">
                                    <i data-lucide="save" style="width: 15px; height: 15px;"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Render Structural Vector Graphics Icons Engine
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Real-time Image Binary Stream Preview Pipeline
            const imageUpload = document.getElementById('imageUpload');
            if (imageUpload) {
                imageUpload.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            document.getElementById('profileImage').src = event.target.result;
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }
        });
    </script>
@endsection
