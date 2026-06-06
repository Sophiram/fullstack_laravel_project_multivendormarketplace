@extends('user.layouts.layout')

@section('user_page_title', 'Change Password')

@section('user_layout')
    <div class="container-fluid px-2 px-md-4 py-3">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center" style="color: #0f172a;">
                <i data-lucide="key-round" class="me-2 text-primary"></i>
                Change Password
            </h3>
            <p class="text-muted small mb-0">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                        <span class="p-2 rounded-3 bg-light text-secondary me-2 d-inline-flex">
                            <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
                        </span>
                        Update Security Credentials
                    </h5>

                    <form action="{{ route('user.password.update') }}" method="POST">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small" for="current_password">Current
                                Password</label>
                            <div class="input-group custom-input-group">
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control custom-input @error('current_password') is-invalid @enderror"
                                    required>
                                <button class="btn btn-outline-input-group transition-all" type="button"
                                    onclick="togglePassword('current_password')">
                                    <i data-lucide="eye" id="eye-current_password" style="width: 18px; height: 18px;"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1 d-flex align-items-center">
                                    <i data-lucide="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small" for="password">New Password</label>
                            <div class="input-group custom-input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control custom-input @error('password') is-invalid @enderror" required>
                                <button class="btn btn-outline-input-group transition-all" type="button"
                                    onclick="togglePassword('password')">
                                    <i data-lucide="eye" id="eye-password" style="width: 18px; height: 18px;"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 d-flex align-items-center">
                                    <i data-lucide="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small" for="password_confirmation">Confirm
                                New Password</label>
                            <div class="input-group custom-input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control custom-input @error('password_confirmation') is-invalid @enderror"
                                    required>
                                <button class="btn btn-outline-input-group transition-all" type="button"
                                    onclick="togglePassword('password_confirmation')">
                                    <i data-lucide="eye" id="eye-password_confirmation"
                                        style="width: 18px; height: 18px;"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1 d-flex align-items-center">
                                    <i data-lucide="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="pt-2 border-top border-light">
                            <button type="submit"
                                class="btn btn-primary px-4 py-2 rounded-3 fw-semibold w-100 w-md-auto transition-all">
                                <i data-lucide="save" style="width: 16px; height: 16px;" class="me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Inputs Style Mapping */
        .custom-input {
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
            z-index: 1;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #4f46e5;
            box-shadow: none;
        }

        /* Harmonized Input Group Button styling */
        .btn-outline-input-group {
            border: 1px solid #e2e8f0;
            border-left: none;
            background-color: #f8fafc;
            color: #64748b;
            padding: 0.6rem 1rem;
        }

        .btn-outline-input-group:hover {
            background-color: #f1f5f9;
            color: #334155;
        }

        /* Focus state encapsulation across full combined field element wrapper */
        .custom-input-group:focus-within .custom-input {
            border-color: #4f46e5;
            background-color: #ffffff;
        }

        .custom-input-group:focus-within .btn-outline-input-group {
            border-color: #4f46e5;
            background-color: #ffffff;
        }

        /* Exception fallback handling for native Bootstrap invalid markers inside input wrappers */
        .custom-input.is-invalid {
            border-color: #ef4444 !important;
            background-image: none;
        }

        .custom-input-group:focus-within .custom-input.is-invalid+.btn-outline-input-group,
        .custom-input.is-invalid+.btn-outline-input-group {
            border-color: #ef4444 !important;
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        @media (max-width: 767.98px) {
            .w-md-auto {
                width: 100% !important;
            }
        }
    </style>

    <script>
        // Ensure scripts bind properly after full runtime readiness context
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Fire SweetAlert General Validation Failure Alert Messages Cleanly
            @if ($errors->any())
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
            @endif

            // Fire SweetAlert Password Update Success Confirms Contextually
            @if (session('status') === 'password-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Your password has been successfully updated.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        });

        // Interactive toggle controller handling password text switches natively
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('eye-' + inputId);

            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = "password";
                icon.setAttribute('data-lucide', 'eye');
            }

            // Re-render target Lucide icons dynamically post alteration
            lucide.createIcons();
        }
    </script>
@endsection

