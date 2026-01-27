<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur #{{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 0;
                size: A4;
            }
        }
    </style>
</head>
<body class="bg-slate-100 print:bg-white text-slate-800 font-sans antialiased" onload="window.print()">
    
    <div class="max-w-[210mm] mx-auto bg-white min-h-screen p-12 shadow-xl print:shadow-none print:p-8">
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-slate-200 pb-8 mb-8">
            <div class="flex items-center gap-4">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto"> --}}
                <div>
                    <h1 class="text-2xl font-black font-tech uppercase tracking-tighter text-blue-600">Yala Computer</h1>
                    <p class="text-sm text-slate-500">Jl. Teknologi No. 123, Jakarta Selatan</p>
                    <p class="text-sm text-slate-500">support@yalacomputer.com | +62 812 3456 7890</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-black text-slate-200 uppercase tracking-widest">FAKTUR</h2>
                <p class="font-mono font-bold text-slate-700 mt-2">#{{ $order->order_number }}</p>
                <p class="text-sm text-slate-500">{{ $order->created_at->format('d F Y') }}</p>
            </div>
        </div>

        <!-- Info -->
        <div class="flex justify-between mb-8">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ditagihkan Kepada</h3>
                <p class="font-bold text-slate-800">{{ $order->guest_name }}</p>
                <p class="text-sm text-slate-600 max-w-xs">{{ $order->shipping_address }}</p>
                <p class="text-sm text-slate-600">{{ $order->shipping_city }}</p>
                <p class="text-sm text-slate-600 mt-2">{{ $order->guest_whatsapp }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Status Pembayaran</h3>
                <div class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase border 
                    {{ $order->payment_status === 'paid' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                    {{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM DIBAYAR' }}
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="bg-slate-50 border-y border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider text-left">
                    <th class="py-3 px-4">Produk</th>
                    <th class="py-3 px-4 text-right">Harga</th>
                    <th class="py-3 px-4 text-center">Qty</th>
                    <th class="py-3 px-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($order->item as $item)
                    <tr class="border-b border-slate-100">
                        <td class="py-4 px-4">
                            <p class="font-bold text-slate-800">{{ $item->produk->name }}</p>
                            <p class="text-xs text-slate-500">{{ $item->produk->category?->name ?? 'Umum' }}</p>
                        </td>
                        <td class="py-4 px-4 text-right font-mono text-slate-600">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-4 text-center text-slate-600">
                            {{ $item->quantity }}
                        </td>
                        <td class="py-4 px-4 text-right font-mono font-bold text-slate-800">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="flex justify-end mb-12">
            <div class="w-1/2 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 font-bold">Subtotal</span>
                    <span class="font-mono text-slate-700">Rp {{ number_format($order->item->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 font-bold">Ongkos Kirim</span>
                    <span class="font-mono text-slate-700">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-sm text-emerald-600">
                    <span class="font-bold">Diskon</span>
                    <span class="font-mono">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($order->voucher_discount > 0)
                <div class="flex justify-between text-sm text-emerald-600">
                    <span class="font-bold">Voucher</span>
                    <span class="font-mono">- Rp {{ number_format($order->voucher_discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                    <span class="text-base font-black uppercase text-slate-800">Total</span>
                    <span class="text-xl font-black font-mono text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-200 pt-8 text-center text-xs text-slate-500">
            <p>Terima kasih telah berbelanja di Yala Computer.</p>
            <p class="mt-1">Dokumen ini diterbitkan secara otomatis oleh komputer dan sah tanpa tanda tangan.</p>
        </div>

        <!-- Print Button (Hidden on Print) -->
        <div class="fixed bottom-8 right-8 no-print">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Faktur
            </button>
        </div>
    </div>
</body>
</html>
