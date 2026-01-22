<x-layouts.print>
    <div style="text-align: center; width: 50mm; height: 30mm; border: 1px solid #000; padding: 5px; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 0 auto;">
        <h3 style="margin: 0; font-size: 10px; text-transform: uppercase;">{{ \App\Models\Setting::get('shop_name') }}</h3>
        
        <!-- Barcode using JsBarcode (Client side rendering for speed) -->
        <svg id="barcode"></svg>
        
        <p style="margin: 0; font-size: 10px; font-weight: bold;">{{ $product->name }}</p>
        <p style="margin: 2px 0 0 0; font-size: 12px; font-weight: bold;">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode("#barcode", "{{ $product->sku }}", {
            format: "CODE128",
            width: 1.5,
            height: 30,
            displayValue: true,
            fontSize: 10,
            margin: 5
        });
    </script>
</x-layouts.print>
