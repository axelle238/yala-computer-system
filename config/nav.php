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
                            'label' => 'Shift & Saldo',
                            'route' => 'admin.keuangan.kasir',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Riwayat Transaksi',
                            'route' => 'admin.transaksi.indeks',
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
                            'label' => 'Semua Servis',
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
                            'label' => 'Pengiriman & Logistik',
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
                            'label' => 'Produk & Stok',
                            'route' => 'admin.produk.indeks',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Audit Stok (Opname)',
                            'route' => 'admin.gudang.stok-opname',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Cetak Label',
                            'route' => 'admin.produk.label',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                    ]
                ],
                [
                    'label' => 'Pengadaan',
                    'icon' => 'clipboard-check',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'stock.adjust',
                    'sub_menu' => [
                        [
                            'label' => 'Pembelian (PO)',
                            'route' => 'admin.pesanan-pembelian.indeks',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Penerimaan (GRN)',
                            'route' => 'admin.pesanan-pembelian.terima',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                    ]
                ]
            ],
        ],
        [
            'title' => 'BISNIS & KORPORAT',
            'items' => [
                [
                    'label' => 'B2B & Keuangan',
                    'icon' => 'banknotes',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'order.view',
                    'sub_menu' => [
                        [
                            'label' => 'Penawaran Harga',
                            'route' => 'admin.penawaran.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Piutang & Invoice',
                            'route' => 'admin.keuangan.piutang',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Laba Rugi',
                            'route' => 'admin.keuangan.laba-rugi',
                            'roles' => ['owner'],
                        ],
                    ]
                ],
            ],
        ],
        [
            'title' => 'SUMBER DAYA MANUSIA',
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
                            'label' => 'Jabatan (Role)',
                            'route' => 'admin.karyawan.peran.indeks',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Absensi',
                            'route' => 'admin.karyawan.kehadiran',
                            'roles' => ['admin', 'owner', 'hr', 'technician', 'cashier', 'warehouse'],
                        ],
                        [
                            'label' => 'Penggajian',
                            'route' => 'admin.karyawan.gaji-pengelola',
                            'roles' => ['admin', 'owner', 'hr'],
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
                            'label' => 'Pesan Pembeli',
                            'route' => 'admin.pelanggan.kotak-masuk',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Manajemen Pelanggan',
                            'route' => 'admin.pelanggan.indeks',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Loyalty & Membership',
                            'route' => 'admin.pelanggan.loyalitas',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Reputasi & Ulasan',
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
                            'label' => 'Voucher & Diskon',
                            'route' => 'admin.pemasaran.voucher.indeks',
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
            'title' => 'SISTEM & KEUANGAN',
            'items' => [
                [
                    'label' => 'Laporan',
                    'icon' => 'chart-pie',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'report.finance',
                    'sub_menu' => [
                        [
                            'label' => 'Laba Rugi',
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
                        [
                            'label' => 'Kesehatan Sistem',
                            'route' => 'admin.sistem.kesehatan',
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