<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding: 10px;
        }
        .label {
            width: 50mm;
            height: 30mm;
            border: 1px dashed #ccc; /* Helper border, remove for production if needed */
            box-sizing: border-box;
            display: flex;
            flex-col: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            page-break-inside: avoid;
            overflow: hidden;
            padding: 2px;
        }
        .label-name {
            font-size: 9px;
            font-weight: bold;
            max-height: 22px;
            overflow: hidden;
            line-height: 1.1;
            margin-bottom: 2px;
        }
        .label-price {
            font-size: 10px;
            font-weight: bold;
        }
        .barcode-container {
            height: 25px; /* Adjust based on generator */
            width: 90%;
            background: #eee; /* Placeholder for real barcode */
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
        }
    </style>
    <!-- Use a JS barcode library -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    <div class="no-print" style="padding: 20px; background: #f0f0f0; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">PRINT SEKARANG</button>
    </div>

    <div class="container">
        @foreach($queue as $item)
            @for($i = 0; $i < $item['qty_to_print']; $i++)
                <div class="label">
                    <div class="label-name">{{ $item['name'] }}</div>
                    <svg class="barcode"
                        jsbarcode-format="CODE128"
                        jsbarcode-value="{{ $item['barcode'] }}"
                        jsbarcode-textmargin="0"
                        jsbarcode-fontoptions="bold"
                        jsbarcode-width="1.5"
                        jsbarcode-height="25"
                        jsbarcode-displayValue="true"
                        jsbarcode-fontSize="10">
                    </svg>
                    <div class="label-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
            @endfor
        @endforeach
    </div>

    <script>
        JsBarcode(".barcode").init();
    </script>
</body>
</html>
