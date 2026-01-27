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
                            'label' => 'Kasir / POS',
                            'route' => 'admin.transaksi.buat',
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
                            'label' => 'Buka/Tutup Kasir',
                            'route' => 'admin.keuangan.kasir',
                            'roles' => ['admin', 'owner', 'cashier'],
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
                            'label' => 'Garansi & RMA',
                            'route' => 'admin.garansi.indeks',
                            'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                        ],
                        [
                            'label' => 'Produksi Rakitan',
                            'route' => 'admin.perakitan.indeks',
                            'roles' => ['admin', 'owner', 'technician'],
                        ],
                    ]
                ],
                [
                    'label' => 'Logistik',
                    'icon' => 'truck',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'order.view',
                    'sub_menu' => [
                        [
                            'label' => 'Pengiriman & Kurir',
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
                    'label' => 'Manajemen Stok',
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
                            'label' => 'Audit Stok (Opname)',
                            'route' => 'admin.gudang.stok-opname',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Transfer Gudang',
                            'route' => 'admin.gudang.transfer',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Manajemen Aset',
                            'route' => 'admin.aset.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Cetak Barcode/Label',
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
                            'label' => 'Pembelian (PO)',
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
                            'label' => 'Pemasok (Supplier)',
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
                            'label' => 'Pengeluaran Biaya',
                            'route' => 'admin.pengeluaran',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Piutang & Invoice',
                            'route' => 'admin.keuangan.piutang',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Penawaran Harga (Quotation)',
                            'route' => 'admin.penawaran.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
                [
                    'label' => 'Laporan Bisnis',
                    'icon' => 'chart-pie',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'report.finance',
                    'sub_menu' => [
                        [
                            'label' => 'Laba Rugi',
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
                    'label' => 'Kepegawaian',
                    'icon' => 'users',
                    'roles' => ['admin', 'owner', 'hr'],
                    'permission' => 'employee.view',
                    'sub_menu' => [
                        [
                            'label' => 'Daftar Pegawai',
                            'route' => 'admin.karyawan.indeks',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Manajemen Shift',
                            'route' => 'admin.shift',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Absensi',
                            'route' => 'admin.karyawan.kehadiran',
                            'roles' => ['admin', 'owner', 'hr', 'technician', 'cashier', 'warehouse'],
                        ],
                        [
                            'label' => 'Penggajian (Payroll)',
                            'route' => 'admin.karyawan.gaji-pengelola',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Reimbursement',
                            'route' => 'admin.reimbursement',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Jabatan & Akses',
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
                    'label' => 'Pelanggan (CRM)',
                    'icon' => 'chat-bubble-left-right',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'pos.access',
                    'sub_menu' => [
                        [
                            'label' => 'Pesan Masuk',
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
                            'label' => 'Loyalty Member',
                            'route' => 'admin.pelanggan.loyalitas',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Ulasan Produk',
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
                            'label' => 'Banner Promo',
                            'route' => 'admin.spanduk.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Obral Kilat (Flash Sale)',
                            'route' => 'admin.pemasaran.obral-kilat.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Voucher & Diskon',
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
                            'label' => 'Pengaturan Toko',
                            'route' => 'admin.pengaturan',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Knowledge Base',
                            'route' => 'admin.pengetahuan.indeks',
                            'roles' => ['admin', 'owner', 'technician', 'hr'],
                        ],
                        [
                            'label' => 'Audit Log',
                            'route' => 'admin.log-aktivitas.indeks',
                            'roles' => ['admin', 'owner'],
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
                            'label' => 'Informasi Server',
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
                            'label' => 'Firewall & Blokir',
                            'route' => 'admin.keamanan.firewall',
                            'roles' => ['admin'],
                        ],
                        [
                            'label' => 'Monitor Trafik',
                            'route' => 'admin.keamanan.traffic',
                            'roles' => ['admin'],
                        ],
                    ]
                ]
            ],
        ],
    ],
];