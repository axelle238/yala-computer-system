<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Pengiriman - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }
        .label-container {
            width: 100mm;
            height: 150mm;
            border: 1px solid #000;
            margin: 0 auto;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .courier-logo {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .tracking-number {
            text-align: center;
            margin-bottom: 10px;
        }
        .barcode {
            height: 50px;
            background-color: #ccc; /* Placeholder for barcode */
            margin: 5px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .address-section {
            display: flex;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .sender, .receiver {
            flex: 1;
            font-size: 12px;
        }
        .receiver {
            border-left: 1px dashed #000;
            padding-left: 10px;
        }
        .label-title {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 4px;
        }
        .name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .details {
            font-size: 14px;
            line-height: 1.4;
        }
        .order-details {
            font-size: 10px;
            margin-top: auto;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
        }
        .footer {
            margin-top: 10px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="label-container">
        <div class="header">
            <div class="courier-logo">{{ $order->shipping_courier ?? 'KURIR' }}</div>
            <div style="font-size: 12px; font-weight: bold;">{{ $order->created_at->format('d/m/Y') }}</div>
        </div>

        <div class="tracking-number">
            <div style="font-size: 12px; margin-bottom: 2px;">No. Resi / Order ID</div>
            <div style="font-size: 18px; font-weight: bold;">{{ $order->shipping_tracking_number ?? $order->order_number }}</div>
            <!-- Barcode Placeholder (In real app, use a barcode generator) -->
            <div style="margin-top: 5px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px;">
                |||| ||||| |||| || ||||| |||| ||| |||
            </div>
        </div>

        <div class="address-section">
            <div class="sender">
                <div class="label-title">Pengirim:</div>
                <div class="name">{{ $shopName }}</div>
                <div class="details">{{ $shopPhone }}<br>{{ Str::limit($shopAddress, 50) }}</div>
            </div>
            <div class="receiver">
                <div class="label-title">Penerima:</div>
                <div class="name">{{ $order->guest_name ?? $order->user->name }}</div>
                <div class="details">
                    {{ $order->guest_whatsapp ?? $order->user->phone }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}
                </div>
            </div>
        </div>

        <div class="order-details">
            <div class="label-title">Isi Paket:</div>
            @foreach($order->items as $item)
                <div class="order-item">
                    <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                </div>
            @endforeach
            @if($order->notes)
                <div style="margin-top: 5px; font-style: italic;">Catatan: {{ $order->notes }}</div>
            @endif
        </div>

        <div class="footer">
            Dicetak oleh Sistem Yala Computer pada {{ date('d/m/Y H:i') }}
        </div>
    </div>

</body>
</html>
