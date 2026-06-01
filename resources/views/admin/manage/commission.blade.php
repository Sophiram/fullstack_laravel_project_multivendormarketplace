
@extends('admin.layouts.layout')

@section('admin_page_title', 'Manage Commissions - Admin Panel')

@section('admin_layout')
    <div class="container-fluid px-4 py-3">

        {{-- 📝 ផ្នែក Header បង្ហាញចំណងជើងទំព័រ (ដូចទំព័រ Manage Stores) --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manage Commissions</h4>
                <p class="text-muted small mb-0">Configure, overview, and update operational commission rates for store categories.</p>
            </div>
        </div>

        {{-- 💡 ហៅ Livewire Component មកដំណើរការនៅទីនេះ --}}
        <livewire:manage-commission-component />

    </div>
@endsection


