<?php

return [
    'menu' => [
        [
            'title' => 'UTAMA',
            'items' => [
                [
                    'label' => 'Beranda',
                    'route' => 'admin.beranda',
                    'icon' => 'home',
                    'roles' => ['admin', 'owner', 'technician', 'cashier', 'warehouse'],
                    'permission' => 'dashboard.view',
                ],
                [
                    'label' => 'Tugas & Kolaborasi',
                    'route' => 'admin.tugas',
                    'icon' => 'clipboard-list',
                    'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                    'permission' => 'dashboard.view',
                ],
                [
                    'label' => 'Layar Toko (Front)',
                    'route' => 'beranda',
                    'icon' => 'store',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'target' => '_blank',
                ],
            ],
        ],
        [
            'title' => 'OPERASIONAL',
            'items' => [
                [
                    'label' => 'Penjualan & Kasir',
                    'icon' => 'shopping-cart',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'pos.access',
                    'sub_menu' => [
                        [
                            'label' => 'Dashboard Penjualan',
                            'route' => 'admin.analitik.penjualan',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Aplikasi Kasir (POS)',
                            'route' => 'admin.kasir',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Pesanan Online',
                            'route' => 'admin.pesanan.indeks',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Riwayat Transaksi',
                            'route' => 'admin.transaksi.indeks',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Kelola Shift Kasir',
                            'route' => 'admin.keuangan.kasir',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Input Manual',
                            'route' => 'admin.transaksi.buat',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
                [
                    'label' => 'Servis & Perbaikan',
                    'icon' => 'wrench',
                    'roles' => ['admin', 'owner', 'technician'],
                    'permission' => 'service.view',
                    'sub_menu' => [
                        [
                            'label' => 'Dashboard Servis',
                            'route' => 'admin.servis.papan',
                            'roles' => ['admin', 'owner', 'technician'],
                        ],
                        [
                            'label' => 'Semua Tiket Servis',
                            'route' => 'admin.servis.indeks',
                            'roles' => ['admin', 'owner', 'technician'],
                        ],
                        [
                            'label' => 'Produksi Rakitan PC',
                            'route' => 'admin.perakitan.indeks',
                            'roles' => ['admin', 'owner', 'technician'],
                        ],
                        [
                            'label' => 'Garansi & RMA',
                            'route' => 'admin.garansi.indeks',
                            'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                        ],
                    ]
                ],
                [
                    'label' => 'Logistik & Pengiriman',
                    'icon' => 'truck',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'order.view',
                    'sub_menu' => [
                        [
                            'label' => 'Pengiriman Aktif',
                            'route' => 'admin.logistik',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                    ]
                ],
            ],
        ],
        [
            'title' => 'INVENTARIS & GUDANG',
            'items' => [
                [
                    'label' => 'Produk & Stok',
                    'icon' => 'box',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'product.view',
                    'sub_menu' => [
                        [
                            'label' => 'Dashboard Stok',
                            'route' => 'admin.analitik.stok',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Daftar Produk',
                            'route' => 'admin.produk.indeks',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Paket Bundling',
                            'route' => 'admin.produk.paket',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Transfer Antar Gudang',
                            'route' => 'admin.gudang.transfer',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Stok Opname (Audit)',
                            'route' => 'admin.gudang.stok-opname',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Cetak Barcode',
                            'route' => 'admin.produk.label',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                    ]
                ],
                [
                    'label' => 'Pengadaan (Procurement)',
                    'icon' => 'clipboard-check',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'stock.adjust',
                    'sub_menu' => [
                        [
                            'label' => 'Permintaan Stok (PR)',
                            'route' => 'admin.permintaan-stok.indeks',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Pesanan Pembelian (PO)',
                            'route' => 'admin.pesanan-pembelian.indeks',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Penerimaan Barang (GRN)',
                            'route' => 'admin.pesanan-pembelian.terima',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                    ]
                ],
                [
                    'label' => 'Aset & Inventaris',
                    'icon' => 'cube-transparent',
                    'roles' => ['admin', 'owner'],
                    'sub_menu' => [
                        [
                            'label' => 'Daftar Aset Kantor',
                            'route' => 'admin.aset.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
                [
                    'label' => 'Data Master',
                    'icon' => 'database',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'product.view',
                    'sub_menu' => [
                        [
                            'label' => 'Kategori Produk',
                            'route' => 'admin.master.kategori',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Data Pemasok',
                            'route' => 'admin.master.pemasok',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                    ]
                ]
            ],
        ],
        [
            'title' => 'KEUANGAN & BISNIS',
            'items' => [
                [
                    'label' => 'Keuangan',
                    'icon' => 'banknotes',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'order.view',
                    'sub_menu' => [
                        [
                            'label' => 'Catat Pengeluaran',
                            'route' => 'admin.pengeluaran',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Piutang & Invoice',
                            'route' => 'admin.keuangan.piutang',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Penawaran Harga (B2B)',
                            'route' => 'admin.penawaran.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
                [
                    'label' => 'Laporan & Analitik',
                    'icon' => 'chart-pie',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'report.finance',
                    'sub_menu' => [
                        [
                            'label' => 'Pusat Analitik',
                            'route' => 'admin.analitik.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Laporan Laba Rugi',
                            'route' => 'admin.keuangan.laba-rugi',
                            'roles' => ['owner'],
                        ],
                        [
                            'label' => 'Analisis Keuangan',
                            'route' => 'admin.analitik.keuangan',
                            'roles' => ['owner'],
                        ],
                        [
                            'label' => 'Laporan Penjualan',
                            'route' => 'admin.analitik.penjualan',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
            ],
        ],
        [
            'title' => 'HR & PERSONALIA',
            'items' => [
                [
                    'label' => 'Karyawan',
                    'icon' => 'users',
                    'roles' => ['admin', 'owner', 'hr'],
                    'permission' => 'employee.view',
                    'sub_menu' => [
                        [
                            'label' => 'Data Pegawai',
                            'route' => 'admin.karyawan.indeks',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Manajemen Shift',
                            'route' => 'admin.shift',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Absensi Kehadiran',
                            'route' => 'admin.karyawan.kehadiran',
                            'roles' => ['admin', 'owner', 'hr', 'technician', 'cashier', 'warehouse'],
                        ],
                        [
                            'label' => 'Penggajian (Payroll)',
                            'route' => 'admin.karyawan.gaji-pengelola',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Klaim Reimbursement',
                            'route' => 'admin.reimbursement',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Kelola Jabatan',
                            'route' => 'admin.karyawan.peran.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
            ],
        ],
        [
            'title' => 'MARKETING & KONTEN',
            'items' => [
                [
                    'label' => 'Hubungan Pelanggan',
                    'icon' => 'chat-bubble-left-right',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'pos.access',
                    'sub_menu' => [
                        [
                            'label' => 'Kotak Masuk',
                            'route' => 'admin.pelanggan.kotak-masuk',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Live Chat',
                            'route' => 'admin.pelanggan.obrolan',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Data Pelanggan',
                            'route' => 'admin.pelanggan.indeks',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Program Loyalitas',
                            'route' => 'admin.pelanggan.loyalitas',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Ulasan & Rating',
                            'route' => 'admin.pemasaran.ulasan',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
                [
                    'label' => 'Promosi',
                    'icon' => 'star',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                    'sub_menu' => [
                        [
                            'label' => 'Banner Toko',
                            'route' => 'admin.spanduk.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Obral Kilat (Flash Sale)',
                            'route' => 'admin.pemasaran.obral-kilat.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Voucher & Kupon',
                            'route' => 'admin.pemasaran.voucher.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'WhatsApp Blast',
                            'route' => 'admin.pemasaran.whatsapp',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Berita & Artikel',
                            'route' => 'admin.berita.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
            ],
        ],
        [
            'title' => 'SISTEM & KEAMANAN',
            'items' => [
                [
                    'label' => 'Pengaturan',
                    'icon' => 'cog',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                    'sub_menu' => [
                        [
                            'label' => 'Konfigurasi Toko',
                            'route' => 'admin.pengaturan',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Knowledge Base',
                            'route' => 'admin.pengetahuan.indeks',
                            'roles' => ['admin', 'owner', 'technician', 'hr'],
                        ],
                        [
                            'label' => 'Log Aktivitas',
                            'route' => 'admin.log-aktivitas.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
                [
                    'label' => 'Keamanan',
                    'icon' => 'shield-check',
                    'roles' => ['admin'],
                    'permission' => 'setting.manage',
                    'sub_menu' => [
                        [
                            'label' => 'Dashboard Keamanan',
                            'route' => 'admin.keamanan.dashboard',
                            'roles' => ['admin'],
                        ],
                        [
                            'label' => 'Firewall & IPS',
                            'route' => 'admin.keamanan.firewall',
                            'roles' => ['admin'],
                        ],
                        [
                            'label' => 'Monitor Trafik',
                            'route' => 'admin.keamanan.traffic',
                            'roles' => ['admin'],
                        ],
                    ]
                ],
                [
                    'label' => 'Pemeliharaan',
                    'icon' => 'server',
                    'roles' => ['admin'],
                    'permission' => 'setting.manage',
                    'sub_menu' => [
                        [
                            'label' => 'Kesehatan Sistem',
                            'route' => 'admin.sistem.kesehatan',
                            'roles' => ['admin'],
                        ],
                        [
                            'label' => 'Info Server',
                            'route' => 'admin.sistem.info',
                            'roles' => ['admin'],
                        ],
                        [
                            'label' => 'Backup Database',
                            'route' => 'admin.sistem.cadangan',
                            'roles' => ['admin'],
                        ],
                    ]
                ],
            ],
        ],
    ],
];