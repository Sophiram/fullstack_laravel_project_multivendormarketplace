@extends('user.layouts.layout')

@section('user_page_title', 'My Profile')

@section('user_layout')
    <div class="container-fluid px-2 px-md-4 py-3">
        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                <i data-lucide="user" class="me-2 text-primary"></i>
                Profile Settings
            </h3>
            <p class="text-muted small mb-0">Update your personal identification and manage account security.</p>
        </div>

        <!-- Success Toast Alert -->
        @if (session('status') === 'profile-updated')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Your Profile has been successfully updated.',
                        showConfirmButton: false,
                        timer: 2000,
                        position: 'top-end',
                        toast: true,
                        timerProgressBar: true
                    });
                });
            </script>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="row g-4">
                <!-- Left Sidebar: Avatar Management -->
                <div class="col-12 col-lg-4 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="p-4 text-center d-flex flex-column align-items-center justify-content-center h-100">
                            <div class="position-relative d-inline-block mb-3">
                                @if (Auth::user()->image)
                                    <img id="profileImagePreview" src="{{ asset('storage/' . Auth::user()->image) }}"
                                        alt="{{ Auth::user()->name }}"
                                        class="img-fluid rounded-circle p-1 border border-2 border-primary-subtle shadow-sm animate-fade-in"
                                        style="width: 120px; height: 120px; object-fit: cover;" />
                                @else
                                    <img id="profileImagePreview"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=128&background=4f46e5&color=fff&bold=true"
                                        alt="{{ Auth::user()->name }}"
                                        class="img-fluid rounded-circle p-1 border border-2 border-primary-subtle shadow-sm animate-fade-in"
                                        style="width: 120px; height: 120px; object-fit: cover;" />
                                @endif
                                <span
                                    class="position-absolute bottom-0 end-0 rounded-circle bg-success border border-2 border-white status-indicator"
                                    style="width: 16px; height: 16px; margin-right: 8px; margin-bottom: 8px;"></span>
                            </div>

                            <div class="mb-3">
                                <label for="imageUpload"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm transition-all fw-medium"
                                    style="cursor: pointer;">
                                    <i data-lucide="camera" style="width: 14px; height: 14px;" class="me-1"></i> Change
                                    Photo
                                </label>
                                <input type="file" id="imageUpload" name="image" class="d-none" accept="image/*"
                                    onchange="previewImage(event)">
                            </div>

                            <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                            <span
                                class="badge rounded-pill bg-primary-subtle text-primary text-uppercase px-3 py-1 fw-semibold"
                                style="font-size: 10px; letter-spacing: 0.5px;">
                                {{ Auth::user()->role ?? 'User' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Layout: Account Fields -->
                <div class="col-12 col-lg-8 col-xl-9">
                    <div class="card shadow-sm border-0 p-4 rounded-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                            <span class="p-2 rounded-3 bg-light text-secondary me-2 d-inline-flex">
                                <i data-lucide="vcard" style="width: 18px; height: 18px;"></i>
                            </span>
                            Account Information
                        </h5>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary small" for="inputName">Full Name</label>
                                <input type="text" class="form-control rounded-3 custom-input" id="inputName"
                                    name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary small" for="inputEmail">Email
                                    Address</label>
                                <input type="email" class="form-control rounded-3 custom-input" id="inputEmail"
                                    name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-secondary small d-block mb-2">Gender</label>
                            <div class="d-flex gap-4">
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="gender" id="genderMale"
                                        value="male" {{ Auth::user()->gender == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-medium small" for="genderMale"
                                        style="cursor: pointer;">Male</label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                        value="female" {{ Auth::user()->gender == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-medium small" for="genderFemale"
                                        style="cursor: pointer;">Female</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 border-top border-light">
                            <button type="submit"
                                class="btn btn-primary px-4 py-2 rounded-3 fw-semibold w-100 w-md-auto transition-all">
                                <i data-lucide="save" style="width: 16px; height: 16px;" class="me-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Security Block -->
        <div class="card shadow-sm border-0 p-4 rounded-4 mt-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-3 rounded-3 bg-danger-subtle text-danger d-none d-sm-inline-flex">
                        <i data-lucide="shield-alert" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Security & Privacy</h5>
                        <p class="text-muted small mb-0">Change your password regularly to keep your account safe.</p>
                    </div>
                </div>
                <a href="{{ route('user.password.edit') }}"
                    class="btn btn-outline-danger rounded-3 py-2 px-3 border-dashed fw-medium transition-all w-100 w-md-auto text-center">
                    <i data-lucide="lock" style="width: 16px; height: 16px;" class="me-1"></i> Change Password
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Form Inputs Focus & Styling Styling */
        .custom-input {
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        /* Radios Optimization */
        .custom-radio .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        /* Generic Helper Transition utilities */
        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        /* Status Indicator Pulse Animation */
        .status-indicator {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        @media (max-width: 767.98px) {
            .w-md-auto {
                width: 100% !important;
            }
        }
    </style>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Preview Image dynamically instantly
        function previewImage(event) {
            const imageInput = event.target;
            const imagePreview = document.getElementById('profileImagePreview');

            if (imageInput.files && imageInput.files[0]) {
                imagePreview.src = URL.createObjectURL(imageInput.files[0]);
                imagePreview.style.objectFit = "cover";
            }
        }

        // Fire SweetAlert Error Messages cleanly on validation failures
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{!! addslashes($errors->first()) !!}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        @endif
    </script>
@endsection
