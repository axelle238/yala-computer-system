<x-layouts.print>
    <div style="font-family: sans-serif; max-width: 210mm; margin: 0 auto;">
        <!-- Kop Surat -->
        <div style="border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0; color: #2563eb; font-size: 24px;">{{ $shopName }}</h1>
                <p style="margin: 5px 0; font-size: 12px; color: #555;">{{ $shopAddress }}</p>
                <p style="margin: 0; font-size: 12px;">Hotline: {{ $shopPhone }}</p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin: 0; font-size: 20px;">TANDA TERIMA SERVIS</h2>
                <p style="font-family: monospace; font-size: 16px; font-weight: bold; background: #eee; padding: 5px; margin-top: 5px;">{{ $ticket->ticket_number }}</p>
            </div>
        </div>

        <!-- Info Pelanggan & Perangkat -->
        <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; font-size: 14px;">DATA PELANGGAN</h3>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 100px; font-weight: bold;">Nama</td>
                            <td>: {{ $ticket->customer_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Telepon</td>
                            <td>: {{ $ticket->customer_phone }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Tanggal Masuk</td>
                            <td>: {{ $ticket->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; font-size: 14px;">DATA PERANGKAT</h3>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 100px; font-weight: bold;">Unit</td>
                            <td>: {{ $ticket->device_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Keluhan</td>
                            <td>: {{ $ticket->problem_description }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Estimasi Biaya</td>
                            <td>: Rp {{ number_format($ticket->estimated_cost, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Syarat & Ketentuan -->
        <div style="border: 1px solid #eee; padding: 15px; font-size: 11px; color: #444; background: #f9f9f9; margin-bottom: 30px;">
            <strong>SYARAT & KETENTUAN SERVIS:</strong>
            <ol style="margin-left: -20px; margin-bottom: 0;">
                <li>Barang servis yang tidak diambil lebih dari 1 bulan di luar tanggung jawab kami.</li>
                <li>Garansi servis berlaku 1 minggu untuk kerusakan yang sama.</li>
                <li>Kami tidak bertanggung jawab atas kehilangan data software. Harap backup data penting.</li>
                <li>Biaya pembatalan servis (cancel) dikenakan charge pengecekan Rp 25.000.</li>
            </ol>
        </div>

        <!-- Tanda Tangan -->
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="height: 80px; vertical-align: bottom;">( {{ $ticket->customer_name }} )<br>Pelanggan</td>
                <td style="height: 80px; vertical-align: bottom;">( {{ Auth::user()->name ?? 'Admin' }} )<br>Penerima</td>
            </tr>
        </table>
    </div>
</x-layouts.print>
