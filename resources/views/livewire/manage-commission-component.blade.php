<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\CommissionRule;
use App\Models\Category;

new class extends Component {
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Component Properties (States)
    public $commission_id = null;
    public $category_id = '';
    public $commission_rate = '';
    public $status = 'Active';
    public $isEditMode = false;
    public $search = '';

    // Validation Rules
    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive',
        ];
    }

    // Reset Form Fields
    public function resetFields()
    {
        $this->commission_id = null;
        $this->category_id = '';
        $this->commission_rate = '';
        $this->status = 'Active';
        $this->isEditMode = false;
        $this->resetErrorBag();
    }

    // Save or Update Commission Rule
    public function saveCommission()
    {
        $this->validate();

        if ($this->isEditMode) {
            $rule = CommissionRule::findOrFail($this->commission_id);
            $rule->update([
                'category_id' => $this->category_id,
                'commission_rate' => $this->commission_rate,
                'status' => $this->status,
            ]);
            $this->dispatch('notify', ['title' => 'Successfully updated!', 'type' => 'success']);
        } else {
            $exists = CommissionRule::where('category_id', $this->category_id)->exists();
            if ($exists) {
                $this->addError('category_id', 'This product category already has a commission rule.');
                return;
            }

            CommissionRule::create([
                'category_id' => $this->category_id,
                'commission_rate' => $this->commission_rate,
                'status' => $this->status,
            ]);
            $this->dispatch('notify', ['title' => 'Successfully created!', 'type' => 'success']);
        }

        $this->resetFields();
    }

    // Fetch Data for Editing
    public function editCommission($id)
    {
        $rule = CommissionRule::findOrFail($id);
        $this->commission_id = $rule->commission_id ?? $rule->id;
        $this->category_id = $rule->category_id;
        $this->commission_rate = $rule->commission_rate;
        $this->status = $rule->status;
        $this->isEditMode = true;
    }

    // Delete Commission Rule
    public function deleteCommission($id)
    {
        CommissionRule::findOrFail($id)->delete();
        $this->dispatch('notify', ['title' => 'Successfully deleted!', 'type' => 'error']);
    }

    // Reset Pagination Page on Search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Pass Dynamic Data to Blade View
    public function with(): array
    {
        $rulesList = CommissionRule::with('category')
            ->whereHas('category', function ($query) {
                $query->where('category_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return [
            'rulesList' => $rulesList,
            'categoriesList' => Category::all(),
        ];
    }
}; ?>

<div class="row g-4 commission-wrapper">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .commission-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* Premium Cards */
        .card-custom {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        /* Modern Form Inputs */
        .form-control-custom,
        .form-select-custom {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1e293b;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .input-group-text-custom {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #64748b;
            font-weight: 600;
        }

        /* Premium Buttons */
        .btn-premium {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            border-radius: 12px;
            padding: 0.65rem 1.25rem;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
        }

        .btn-premium:hover {
            background: linear-gradient(135deg, #4338ca, #2563eb);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35);
        }

        .btn-light-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #475569;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-light-custom:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        /* Table Enhancements */
        .table-custom th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            border-bottom: 2px solid #f1f5f9;
            background-color: transparent;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .table-custom td {
            padding: 1rem 0.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
        }

        .table-custom tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Action Buttons */
        .btn-action {
            border-radius: 10px;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .btn-action.edit:hover {
            background: #eff6ff;
            color: #3b82f6 !important;
            border-color: #bfdbfe;
        }

        .btn-action.delete:hover {
            background: #fef2f2;
            color: #ef4444 !important;
            border-color: #fecaca;
        }

        /* Icon Box */
        .icon-box-inline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Search Bar */
        .search-wrapper {
            position: relative;
            max-width: 300px;
            width: 100%;
        }

        .search-wrapper .lucide-search {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        .search-input {
            padding-left: 40px !important;
            border-radius: 100px;
            /* Pill shape */
        }
    </style>

    {{-- 🎨 Form Section --}}
    <div class="col-lg-4">
        <div class="card card-custom p-4 p-md-5">
            <h6 class="fw-bolder mb-4 text-dark fs-6 icon-box-inline" style="letter-spacing: -0.3px;">
                @if ($this->isEditMode)
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 d-flex text-primary">
                        <i data-lucide="edit-3" style="width: 20px; height: 20px;"></i>
                    </div>
                    Update Rule
                @else
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 d-flex text-primary">
                        <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
                    </div>
                    Create New Rule
                @endif
            </h6>

            <form wire:submit.prevent="saveCommission">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary mb-1">Product Category</label>
                    <select wire:model="category_id"
                        class="form-select form-control-custom form-select-custom @error('category_id') is-invalid @enderror"
                        {{ $this->isEditMode ? 'disabled' : '' }}>
                        <option value="">-- Select a Category --</option>
                        @foreach ($categoriesList as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback small fw-medium mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary mb-1">Commission Rate</label>
                    <div class="input-group">
                        <input type="number" step="0.01" wire:model="commission_rate"
                            class="form-control form-control-custom @error('commission_rate') is-invalid @enderror"
                            placeholder="e.g. 5.50" style="border-right: none;">
                        <span class="input-group-text input-group-text-custom font-outfit">%</span>
                    </div>
                    @error('commission_rate')
                        <div class="text-danger fw-medium mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary mb-1">Rule Status</label>
                    <select wire:model="status" class="form-select form-control-custom form-select-custom">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-premium flex-grow-1">
                        <span wire:loading class="spinner-border spinner-border-sm me-1"></span>
                        <span wire:loading.remove class="icon-box-inline">
                            <i data-lucide="{{ $this->isEditMode ? 'check-circle' : 'plus-circle' }}"
                                style="width: 18px; height: 18px;"></i>
                        </span>
                        {{ $this->isEditMode ? 'Save Changes' : 'Create Rule' }}
                    </button>

                    @if ($this->isEditMode)
                        <button type="button" wire:click="resetFields"
                            class="btn btn-light-custom px-3 icon-box-inline">
                            <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- 📊 Table Section --}}
    <div class="col-lg-8">
        <div class="card card-custom p-4 p-md-5 h-100">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <h6 class="fw-bolder m-0 text-dark fs-6 icon-box-inline" style="letter-spacing: -0.3px;">
                    <div class="bg-secondary bg-opacity-10 p-2 rounded-3 d-flex text-secondary">
                        <i data-lucide="layers" style="width: 20px; height: 20px;"></i>
                    </div>
                    Active Rules
                </h6>

                <div class="search-wrapper">
                    <i data-lucide="search" class="lucide-search"></i>
                    <input type="text" wire:model.live="search" class="form-control form-control-custom search-input"
                        placeholder="Search categories...">
                </div>
            </div>

            <div class="table-responsive flex-grow-1">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Category</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th class="pe-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rulesList as $rule)
                            @php $id = $rule->commission_id ?? $rule->id; @endphp
                            <tr wire:key="rule-{{ $id }}">
                                <td class="ps-3 font-outfit text-muted fw-semibold" style="font-size: 0.9rem;">
                                    #{{ str_pad($id, 4, '0', STR_PAD_LEFT) }}</td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold font-outfit"
                                            style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ substr($rule->category->category_name ?? 'C', 0, 1) }}
                                        </div>
                                        <span
                                            class="fw-bold text-dark">{{ $rule->category->category_name ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="font-outfit fw-bold text-primary fs-6">
                                    {{ number_format($rule->commission_rate, 2) }}%
                                </td>

                                <td>
                                    @if ($rule->status == 'Active')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25 fw-semibold"
                                            style="font-size: 0.75rem;">
                                            <span class="d-inline-block bg-success rounded-circle me-1"
                                                style="width: 6px; height: 6px; margin-bottom: 1px;"></span> Active
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25 fw-semibold"
                                            style="font-size: 0.75rem;">
                                            <span class="d-inline-block bg-danger rounded-circle me-1"
                                                style="width: 6px; height: 6px; margin-bottom: 1px;"></span> Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="pe-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" wire:click="editCommission({{ $id }})"
                                            class="btn-action edit text-muted" title="Edit">
                                            <i data-lucide="edit-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                        <button type="button" onclick="confirmDelete({{ $id }})"
                                            class="btn-action delete text-muted" title="Delete">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center text-muted">
                                        <div class="bg-light p-3 rounded-circle mb-3 d-flex align-items-center justify-content-center"
                                            style="width: 64px; height: 64px;">
                                            <i data-lucide="inbox" class="text-secondary"
                                                style="width: 32px; height: 32px; opacity: 0.5;"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No Rules Found</h6>
                                        <p class="small mb-0">Create a new commission rule to see it here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($rulesList->hasPages())
                <div class="d-flex justify-content-end pt-4 mt-auto border-top border-light">
                    {{ $rulesList->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ⚙️ Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Rule?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#e2e8f0',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: '<span style="color: #475569">Cancel</span>',
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg',
                    confirmButton: 'rounded-pill px-4',
                    cancelButton: 'rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteCommission', id);
                }
            });
        }

        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        document.addEventListener('livewire:init', () => {
            initLucideIcons();

            Livewire.hook('morph.updated', () => {
                initLucideIcons();
            });

            Livewire.on('notify', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                Swal.fire({
                    title: data.title,
                    icon: data.type,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    background: '#ffffff',
                    iconColor: data.type === 'success' ? '#10b981' : '#ef4444',
                    customClass: {
                        popup: 'rounded-3 shadow-sm border'
                    }
                });
            });
        });

        document.addEventListener('livewire:navigated', () => {
            initLucideIcons();
        });
    </script>
</div>
