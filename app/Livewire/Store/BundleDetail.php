<?php

namespace App\Livewire\Store;

use App\Models\ProductBundle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Detail Bundle - Yala Computer')]
class BundleDetail extends Component
{
    public ProductBundle $bundle;

    public function mount($slug)
    {
        $this->bundle = ProductBundle::with(['items.product', 'products'])->where('slug', $slug)->firstOrFail();
    }

    public function addToCart()
    {
        // For bundle, we add individual items to cart OR handle bundle as a special cart item type.
        // Assuming simplistic approach: Add all items to cart at discounted rate?
        // No, usually bundle is a cart item itself to maintain price.
        // BUT current Cart logic uses Product ID.
        // Strategy: Create a "Virtual Product" or modify Cart to support Bundles.
        // Given complexity limits: We will create a fake "Product" entry for the bundle on the fly or just use the Bundle Model if Cart supports polymorphism.
        // QUICK FIX: Cart uses session [id => qty]. It expects Product IDs.
        // ALTERNATIVE: Add components to cart with modified price? Hard to track.

        // BEST APPROACH FOR NOW: Just notify user "Bundle added" (Logic needs Cart refactor to support `type: bundle`).
        // Let's implement: Add each item to cart, but that loses the "Bundle Price".
        // OK, I will assume Cart logic update in future. For now, show alert.

        $this->dispatch('notify', message: 'Fitur Keranjang untuk Bundle sedang dalam pengembangan.', type: 'info');
    }

    public function render()
    {
        return view('livewire.store.bundle-detail');
    }
}
