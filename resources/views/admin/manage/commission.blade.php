@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Commissions - Admin Panel')

@section('admin_layout')
    <div class="container-fluid py-4 px-3 px-md-4" style="background-color: #f8fafc; min-height: 100vh;">

        {{-- 📝 Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h3 class="fw-bolder text-dark mb-1"
                    style="font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.5px;">Manage Commissions</h3>
                <p class="text-muted small mb-0" style="font-weight: 500;">Configure, overview, and update operational
                    commission rates for store categories.</p>
            </div>
        </div>

        {{-- 💡 Livewire Component --}}
        <livewire:manage-commission-component />

    </div>
@endsection
