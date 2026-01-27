<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Produk - Yala Computer</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            @page { margin: 0; }
            body { margin: 0; -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
        
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 0 auto;
            background: white;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 35mm;
            gap: 2mm;
        }

        .label-card {
            border: 1px dashed #ccc;
            padding: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            page-break-inside: avoid;
        }

        .barcode {
            font-family: 'Libre Barcode 39', cursive;
            font-size: 36px;
            line-height: 1;
        }
        
        /* Thermal 58mm */
        .page-thermal {
            width: 58mm;
            margin: 0 auto;
            padding: 2mm;
        }
        .thermal-item {
            margin-bottom: 5mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
            text-align: center;
        }
    </style>
    <!-- Barcode Font -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="no-print p-4 bg-slate-800 text-white flex justify-between items-center shadow-lg sticky top-0 z-50">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.produk.label') }}" class="font-bold hover:underline">&larr; Kembali</a>
            <span>Preview Mode: <b>{{ strtoupper(session('print_labels.settings.size', 'a4')) }}</b></span>
        </div>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-bold shadow-lg transition">Cetak Sekarang</button>
    </div>

    @php
        $settings = session('print_labels.settings', []);
        $items = session('print_labels.items', []);
        $size = $settings['size'] ?? 'a4';
    @endphp

    @if($size == 'a4')
        <div class="page-a4 shadow-xl my-8">
            @foreach($items as $item)
                @for($i=0; $i<$item['qty']; $i++)
                    <div class="label-card">
                        <div class="font-bold text-xs uppercase mb-1 line-clamp-2 w-full">{{ $item['name'] }}</div>
                        
                        @if($settings['barcode'])
                            <div class="barcode">{{ $item['barcode'] }}</div>
                            <div class="text-[10px] font-mono tracking-widest">{{ $item['sku'] }}</div>
                        @endif

                        @if($settings['price'])
                            <div class="font-black text-sm mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                        @endif
                        
                        <div class="text-[8px] mt-1 text-gray-500">YALA COMPUTER</div>
                    </div>
                @endfor
            @endforeach
        </div>
    @else
        <div class="page-thermal bg-white shadow-xl my-8 min-h-screen">
            @foreach($items as $item)
                @for($i=0; $i<$item['qty']; $i++)
                    <div class="thermal-item">
                        <div class="font-bold text-xs mb-1">{{ $item['name'] }}</div>
                        @if($settings['barcode'])
                            <div class="barcode text-2xl">{{ $item['barcode'] }}</div>
                            <div class="text-[10px] font-mono">{{ $item['sku'] }}</div>
                        @endif
                        @if($settings['price'])
                            <div class="font-black text-sm mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                        @endif
                    </div>
                @endfor
            @endforeach
        </div>
    @endif

</body>
</html>
