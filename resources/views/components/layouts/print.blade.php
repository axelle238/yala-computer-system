<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Cetak Dokumen' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&display=swap');
        
        body {
            font-family: 'Inconsolata', monospace; /* Font struk */
            background: #fff;
            color: #000;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .receipt {
            width: 100%;
            max-width: 80mm; /* Standar Thermal 80mm */
            margin: 0 auto;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        .header {
            margin-bottom: 20px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; font-size: 10px; }

        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 5px 0; vertical-align: top; }
        
        .divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }

        /* Utility untuk menyembunyikan elemen saat cetak */
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }

        /* Tombol Cetak */
        .btn-print {
            background: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-family: sans-serif;
            font-weight: bold;
            display: block;
            margin: 0 auto 20px auto;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print no-print">🖨️ Cetak Dokumen</button>
    
    {{ $slot }}

    <script>
        // Auto print jika ada parameter ?print=true
        if (new URLSearchParams(window.location.search).has('print')) {
            window.print();
        }
    </script>
</body>
</html>
