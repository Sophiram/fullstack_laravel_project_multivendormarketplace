@extends('vendor.layouts.layout')

@section('vendor_page_title', 'Settings - Vendor Panel')

@section('vendor_layout')
    <div class="container-fluid px-2 px-md-4 py-3">
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                    <i data-feather="shield" class="me-2 text-primary"></i>
                    Account Security
                </h3>
                <p class="text-muted small mb-0">Update your security settings and account credentials.</p>
            </div>
            <div>
                <a href="{{ route('vendor.profile') }}"
                    class="btn btn-outline-secondary bg-white text-dark border rounded-3 px-3 py-2 small fw-semibold transition-all d-inline-flex align-items-center shadow-sm"
                    style="font-size: 13px;">
                    <i data-feather="arrow-left" class="me-2 text-muted" style="width: 15px; height: 15px;"></i> Back to
                    Profile
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                        <span class="p-2 rounded-3 bg-light text-secondary me-2 d-inline-flex">
                            <i data-feather="key" style="width: 18px; height: 18px;"></i>
                        </span>
                        Change Password
                    </h5>

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Current Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group custom-input-group">
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control custom-input @if ($errors->updatePassword->has('current_password')) is-invalid @endif"
                                    required>
                                <button
                                    class="btn btn-outline-secondary toggle-password px-3 bg-light-subtle border border-start-0 text-muted"
                                    type="button" data-target="current_password"
                                    style="border-radius: 0 8px 8px 0 !important;">
                                    <i data-feather="eye-off" style="width: 16px; height: 16px;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">New Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group custom-input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control custom-input @if ($errors->updatePassword->has('password')) is-invalid @endif"
                                    required>
                                <button
                                    class="btn btn-outline-secondary toggle-password px-3 bg-light-subtle border border-start-0 text-muted"
                                    type="button" data-target="password" style="border-radius: 0 8px 8px 0 !important;">
                                    <i data-feather="eye-off" style="width: 16px; height: 16px;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">Confirm New Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group custom-input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control custom-input" required>
                                <button
                                    class="btn btn-outline-secondary toggle-password px-3 bg-light-subtle border border-start-0 text-muted"
                                    type="button" data-target="password_confirmation"
                                    style="border-radius: 0 8px 8px 0 !important;">
                                    <i data-feather="eye-off" style="width: 16px; height: 16px;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-light text-end">
                            <button type="submit"
                                class="btn btn-primary px-4 py-2 rounded-3 fw-semibold w-100 w-md-auto transition-all">
                                <i data-feather="save" style="width: 16px; height: 16px;" class="me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Uniform Clean Input Architecture Styles */
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
            z-index: 3;
        }

        /* Group containment handling layout safely */
        .custom-input-group .custom-input:focus {
            border-right-color: transparent;
        }

        .custom-input-group .custom-input:focus+.toggle-password {
            border-color: #4f46e5;
        }

        .custom-input.is-invalid {
            border-color: #ef4444 !important;
            background-image: none;
        }

        .custom-input.is-invalid+.toggle-password {
            border-color: #ef4444 !important;
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
            // Initialize Feather iconography structures safely
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Fire SweetAlert Update Notifications cleanly matching the profile template
            @if (session('status') === 'password-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Security Updated!',
                    text: 'Your password has been changed successfully.',
                    showConfirmButton: false,
                    timer: 3000,
                    position: 'top-end',
                    toast: true,
                    timerProgressBar: true
                });
            @endif

            // Intercept validation error arrays assigned to updatePassword bag handles
            @if ($errors->updatePassword->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Security Error',
                    text: '{!! addslashes($errors->updatePassword->first()) !!}',
                    showConfirmButton: false,
                    timer: 4000,
                    position: 'top-end',
                    toast: true,
                    timerProgressBar: true
                });
            @endif

            // Managed Feather Field Toggle Engines
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const iconContainer = this.querySelector('i');

                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        iconContainer.setAttribute('data-feather', 'eye');
                    } else {
                        inputField.type = 'password';
                        iconContainer.setAttribute('data-feather', 'eye-off');
                    }

                    // Re-render target node component icons structures safely inline via Feather
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
            });
        });
    </script>
@endsection
