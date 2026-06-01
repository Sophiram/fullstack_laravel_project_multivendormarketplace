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

<div class="row g-4">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;600;700&family=Kantumruuy+Pro:wght@400;600;700&display=swap"
        rel="stylesheet">

    <style>
        .commission-wrapper {
            font-family: 'Plus Jakarta Sans', 'Kantumruuy Pro', sans-serif;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
        }

        .form-control-custom {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
        }

        .form-control-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .btn-premium {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
            border: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-premium:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
        }

        /* បង្កើនសោភ័ណភាពតុបតែងបន្ថែម */
        .icon-box-inline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    {{-- Form Section --}}
    <div class="col-lg-4 commission-wrapper">
        <div class="card card-custom shadow-sm p-4">
            <h6 class="fw-bold mb-4 text-dark text-uppercase fs-6 tracking-wide icon-box-inline">
                @if ($this->isEditMode)
                    <i data-lucide="edit-3" class="text-primary" style="width: 18px; height: 18px;"></i>
                    Edit Commission Rule
                @else
                    <i data-lucide="plus-circle" class="text-primary" style="width: 18px; height: 18px;"></i>
                    Create Commission Rule
                @endif
            </h6>

            <form wire:submit.prevent="saveCommission">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Product Category</label>
                    <select wire:model="category_id"
                        class="form-select form-control-custom @error('category_id') is-invalid @enderror"
                        {{ $this->isEditMode ? 'disabled' : '' }}>
                        <option value="">-- Select Category --</option>
                        @foreach ($categoriesList as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Commission Rate (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" wire:model="commission_rate"
                            class="form-control form-control-custom @error('commission_rate') is-invalid @enderror"
                            placeholder="e.g. 5.50">
                        <span class="input-group-text bg-light font-outfit"
                            style="border-radius: 0 10px 10px 0; border: 1px solid #cbd5e1; border-left: none;">%</span>
                    </div>
                    @error('commission_rate')
                        <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Status</label>
                    <select wire:model="status" class="form-select form-control-custom">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-premium flex-grow-1">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        <span wire:loading.remove class="icon-box-inline">
                            <i data-lucide="{{ $this->isEditMode ? 'refresh-cw' : 'save' }}"
                                style="width: 16px; height: 16px;"></i>
                        </span>
                        {{ $this->isEditMode ? 'Update' : 'Save Changes' }}
                    </button>
                    @if ($this->isEditMode)
                        <button type="button" wire:click="resetFields" class="btn btn-light border icon-box-inline"
                            style="border-radius: 10px; padding: 10px 15px;">
                            <i data-lucide="x" class="text-secondary" style="width: 16px; height: 16px;"></i>
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="col-lg-8 commission-wrapper">
        <div class="card card-custom shadow-sm p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <h6 class="fw-bold m-0 text-dark text-uppercase fs-6 tracking-wide icon-box-inline">
                    <i data-lucide="list" class="text-dark" style="width: 18px; height: 18px;"></i>
                    Commission Rules List
                </h6>
                <div style="max-width: 280px;" class="w-100 position-relative">
                    <input type="text" wire:model.live="search" class="form-control form-control-custom small ps-5"
                        placeholder="Search category...">
                    <i data-lucide="search" class="text-muted position-absolute"
                        style="width: 16px; height: 16px; left: 16px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold">ID</th>
                            <th class="py-3 text-muted fw-bold">Category Name</th>
                            <th class="py-3 text-muted fw-bold">Commission Rate</th>
                            <th class="py-3 text-muted fw-bold">Status</th>
                            <th class="pe-4 py-3 text-end text-muted fw-bold" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rulesList as $rule)
                            @php $id = $rule->commission_id ?? $rule->id; @endphp
                            <tr wire:key="rule-{{ $id }}">
                                <td class="ps-4 font-outfit fw-bold text-muted">#{{ $id }}</td>
                                <td class="fw-semibold text-dark">{{ $rule->category->category_name ?? 'N/A' }}</td>
                                <td class="font-outfit fw-bold text-primary fs-6">
                                    {{ number_format($rule->commission_rate, 2) }}%</td>
                                <td>
                                    <span
                                        class="badge rounded-pill px-3 py-1.5 font-monospace text-uppercase fw-semibold {{ $rule->status == 'Active' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}"
                                        style="font-size: 0.72rem;">
                                        {{ $rule->status }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <button type="button" wire:click="editCommission({{ $id }})"
                                        class="btn btn-light btn-sm border rounded-2 text-primary p-2 d-inline-flex align-items-center"
                                        title="Edit">
                                        <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                    </button>
                                    <button type="button" onclick="confirmDelete({{ $id }})"
                                        class="btn btn-light btn-sm border rounded-2 text-danger p-2 d-inline-flex align-items-center"
                                        title="Delete">
                                        <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted small">No commission rules
                                    available inside the system records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $rulesList->links() }}
            </div>
        </div>
    </div>

    {{-- Notification and Icon Scripts --}}
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1d4ed8',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                position: 'center'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteCommission', id);
                }
            });
        }

        // 💡 មុខងារធានាការ Re-render គ្រាប់ Icon ទាំងអស់ឡើងវិញនៅពេល DOM របស់ Livewire ផ្លាស់ប្តូរ
        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        document.addEventListener('livewire:init', () => {
            initLucideIcons();

            // ចាប់ Morph Update (នៅពេលបញ្ចូលទិន្នន័យ, ចុច Edit, ឬប្តូរទំព័រ Pagination)
            Livewire.hook('morph.updated', ({
                el
            }) => {
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
                    timer: 2000,
                    timerProgressBar: true
                });
            });
        });

        document.addEventListener('livewire:navigated', () => {
            initLucideIcons();
        });
    </script>
</div>
