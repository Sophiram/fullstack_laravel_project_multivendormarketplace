<?php

use Livewire\Component;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

new class extends Component {
    public $search = '';

    public function render()
    {
        $products = [];

        if (strlen($this->search) >= 2) {
            $products = Product::query()
                ->where(function ($query) {
                    $query
                        ->where('product_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('category', function ($q) {
                            $q->where('category_name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('store', function ($q) {
                            $q->where('store_name', 'like', '%' . $this->search . '%');
                        });
                })
                ->with(['store', 'category', 'images'])
                ->take(6)
                ->get();
        }

        return view('livewire.product-search-component', [
            'products' => $products,
        ]);
    }
};
?>

<div class="w-100 position-relative">
    <style>
        .search-input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input-container button:hover {
            color: #ef4444 !important;
            /* ប្តូរពណ៌ទៅក្រហមពេលយក Mouse ដាក់លើសញ្ញា X */
        }
    </style>
    <form action="" class="w-100 m-0 p-0" onsubmit="event.preventDefault();">
        <div class="search-input-container" style="position: relative;">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search for products, brands or stores..." style="padding-right: 40px;">
            <!-- កំណត់ padding ដើម្បីកុំឱ្យអក្សរត្រួតលើ X -->

            <!-- ប៊ូតុង Clear: បង្ហាញតែពេលដែលមានពាក្យក្នុង $search -->
            @if (strlen($search) > 0)
                <button type="button" wire:click="$set('search', '')" class="btn position-absolute"
                    style="right: 40px; top: 0; bottom: 0; border: none; background: none; color: #64748b;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            @endif

            <button class="search-submit-btn" type="button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>

    {{-- 📦 ផ្ទាំងបង្ហាញលទ្ធផលស្វែងរក (Dropdown Search Results) --}}
    @if (strlen($search) >= 2)
        <div class="position-absolute w-100 bg-white shadow-lg border rounded-4 mt-2 p-2"
            style="z-index: 2000; max-height: 380px; overflow-y: auto;">

            @if (count($products) > 0)
                <div class="px-3 py-1 text-muted small fw-bold text-uppercase"
                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    Products Found ({{ count($products) }})
                </div>
                <hr class="my-1 text-black-50">

                @foreach ($products as $product)
                    <a href="{{ route('product.details', ['productId' => $product->id]) }}"
                        class="d-flex align-items-center gap-3 p-2 rounded-3 text-decoration-none hover-search-item">

                        {{-- កែសម្រួលផ្នែក src នេះ --}}
                        <img src="{{ $product->images && $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('images/default-product.png') }}"
                            alt="{{ $product->product_name }}" style="width: 45px; height: 45px;"
                            class="rounded-2 object-fit-cover"
                            onerror="this.onerror=null; this.src='https://placehold.co/45?text=No+Img';">

                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark small">{{ $product->product_name }}</span>

                            {{-- បង្ហាញ Category និង Store នៅខាងក្រោមឈ្មោះ --}}
                            <div class="small text-muted">
                                <i
                                    class="fa-solid fa-tag me-1"></i>{{ $product->category->category_name ?? 'No Category' }}
                                | <i
                                    class="fa-solid fa-store me-1"></i>{{ $product->store->store_name ?? 'Unknown Store' }}
                            </div>

                            <span
                                class="text-success fw-bold small">${{ number_format($product->product_price, 2) }}</span>
                        </div>
                    </a>
                @endforeach
            @else
                {{-- ករណីរកមិនឃើ
                ញផលិតផល --}}
                <div class="text-center py-4 text-muted small">
                    <i class="fa-regular fa-face-frown d-block mb-2 fs-4"></i>
                    No products found for "<span class="fw-bold">{{ $search }}</span>"
                </div>
            @endif

        </div>
    @endif
</div>
