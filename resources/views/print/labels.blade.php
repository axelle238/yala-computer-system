<!DOCTYPE html>
<html>
<head>
    <title>Cetak Label</title>
    <style>
        @media print {
            @page { margin: 0; }
            body { margin: 0; -webkit-print-color-adjust: exact; }
        }
        body { font-family: 'Arial', sans-serif; }
        
        /* A4 Sticker Sheet (Example: 3x7 grid) */
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start;
            gap: 5mm;
            box-sizing: border-box;
        }
        
        .label-card {
            width: 60mm;
            height: 40mm;
            border: 1px dashed #ccc;
            padding: 2mm;
            box-sizing: border-box;
            display: flex;
            flex-col;
            align-items: center;
            justify-content: center;
            text-align: center;
            page-break-inside: avoid;
        }

        .label-thermal {
            width: 100mm;
            height: 150mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            page-break-after: always;
        }

        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 30px;
            margin: 5px 0;
        }
        
        .sku { font-size: 10px; font-weight: bold; }
        .name { font-size: 12px; margin-bottom: 5px; max-height: 2.4em; overflow: hidden; }
        .price { font-size: 16px; font-weight: bold; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
</head>
<body onload="window.print()">

    @if($paper == 'a4')
        <div class="page-a4">
            @foreach($items as $item)
                @for($i = 0; $i < $item->print_qty; $i++)
                    <div class="label-card">
                        <div class="name">{{ $item->name }}</div>
                        <div class="barcode">{{ $item->sku }}</div>
                        <div class="sku">{{ $item->sku }}</div>
                        @if($type == 'price_tag')
                            <div class="price">Rp {{ number_format($item->sell_price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                @endfor
            @endforeach
        </div>
    @else
        @foreach($items as $item)
            @for($i = 0; $i < $item->print_qty; $i++)
                <div class="label-thermal">
                    <h2 style="font-size: 18px; margin: 0 0 10px;">{{ config('app.name') }}</h2>
                    <div class="name" style="font-size: 16px; font-weight: bold;">{{ $item->name }}</div>
                    <div class="barcode" style="font-size: 60px;">{{ $item->sku }}</div>
                    <div class="sku" style="font-size: 14px;">{{ $item->sku }}</div>
                    @if($type == 'price_tag')
                        <div class="price" style="font-size: 32px; margin-top: 10px;">Rp {{ number_format($item->sell_price, 0, ',', '.') }}</div>
                    @endif
                </div>
            @endfor
        @endforeach
    @endif

</body>
</html>