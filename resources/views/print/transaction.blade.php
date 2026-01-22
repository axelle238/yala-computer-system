<x-layouts.print>
    <div class="receipt">
        <div class="header text-center">
            <h1 class="uppercase">{{ $shopName }}</h1>
            <p>{{ $shopAddress }}</p>
            <p>WA: {{ $shopPhone }}</p>
        </div>

        <div class="text-center mb-2">
            <h2 style="margin:0; font-size:14px;">NOTA TRANSAKSI</h2>
            <p>{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
            <p>Reff: {{ $transaction->reference_number ?? '-' }}</p>
            <p>Kasir: {{ $transaction->user->name ?? 'System' }}</p>
        </div>

        <div class="divider"></div>

        <table>
            <tr>
                <td colspan="2" class="font-bold">{{ $transaction->product->name }}</td>
            </tr>
            <tr>
                <td>{{ $transaction->quantity }} x {{ number_format($transaction->product->sell_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($transaction->quantity * $transaction->product->sell_price, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table>
            <tr class="font-bold" style="font-size:14px;">
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($transaction->quantity * $transaction->product->sell_price, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="footer">
            <p>Terima Kasih Telah Berbelanja</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
            <p>Garansi berlaku sesuai ketentuan distributor.</p>
        </div>
    </div>
</x-layouts.print>
