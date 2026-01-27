<?php

return [
    'menu' => [
        [
            'title' => 'UTAMA',
            'items' => [
                [
                    'label' => 'Beranda',
                    'route' => 'dashboard',
                    'icon' => 'home',
                    'roles' => ['admin', 'owner', 'technician', 'cashier', 'warehouse'],
                    'permission' => 'dashboard.view',
                ],
                [
                    'label' => 'Tugas & Kolaborasi',
                    'route' => 'admin.tasks',
                    'icon' => 'clipboard-list',
                    'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                    'permission' => 'dashboard.view',
                ],
                [
                    'label' => 'Layar Toko (Front)',
                    'route' => 'home',
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
                    'icon' => 'shopping-cart', // Changed from shopping-bag
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
                            'route' => 'transactions.create',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Shift & Saldo',
                            'route' => 'finance.cash-register',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Riwayat Transaksi',
                            'route' => 'transactions.index',
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
                            'route' => 'admin.servis.papan', // Kanban as dashboard
                            'roles' => ['admin', 'owner', 'technician'],
                        ],
                        [
                            'label' => 'Semua Servis',
                            'route' => 'services.index',
                            'roles' => ['admin', 'owner', 'technician'],
                        ],
                        [
                            'label' => 'Papan Kanban',
                            'route' => 'services.kanban',
                            'roles' => ['admin', 'owner', 'technician'],
                        ],
                        [
                            'label' => 'Garansi & RMA',
                            'route' => 'rma.index',
                            'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                        ],
                        [
                            'label' => 'Produksi Rakitan',
                            'route' => 'assembly.manager',
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
                            'label' => 'Dashboard Logistik',
                            'route' => 'admin.logistik',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Pengiriman',
                            'route' => 'logistics.manager',
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
                    'icon' => 'box', // Changed from archive-box
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
                            'route' => 'products.index',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Audit Stok (Opname)',
                            'route' => 'warehouses.stock-opname',
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
                    'icon' => 'clipboard-check', // Changed from shopping-cart (duplicate)
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'stock.adjust',
                    'sub_menu' => [
                        [
                            'label' => 'Pembelian (PO)',
                            'route' => 'purchase-orders.index',
                            'roles' => ['admin', 'owner', 'warehouse'],
                        ],
                        [
                            'label' => 'Penerimaan (GRN)',
                            'route' => 'purchase-orders.receive',
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
                    'icon' => 'banknotes', // Changed from briefcase
                    'roles' => ['admin', 'owner'],
                    'permission' => 'order.view',
                    'sub_menu' => [
                        [
                            'label' => 'Penawaran Harga',
                            'route' => 'quotations.index',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Piutang & Invoice',
                            'route' => 'finance.receivables',
                            'roles' => ['admin', 'owner'],
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
                            'route' => 'employees.index',
                            'roles' => ['admin', 'owner', 'hr'],
                        ],
                        [
                            'label' => 'Jabatan (Role)',
                            'route' => 'employees.roles',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Absensi',
                            'route' => 'employees.attendance',
                            'roles' => ['admin', 'owner', 'hr', 'technician', 'cashier', 'warehouse'],
                        ],
                        [
                            'label' => 'Penggajian',
                            'route' => 'employees.payroll-manager',
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
                            'route' => 'customers.inbox',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Manajemen Pelanggan',
                            'route' => 'customers.index',
                            'roles' => ['admin', 'owner', 'cashier'],
                        ],
                        [
                            'label' => 'Loyalty & Membership',
                            'route' => 'customers.loyalty',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Reputasi & Ulasan',
                            'route' => 'reviews.manager',
                            'roles' => ['admin', 'owner'],
                        ],
                    ]
                ],
                [
                    'label' => 'Promosi',
                    'icon' => 'star', // Changed from megaphone
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                    'sub_menu' => [
                        [
                            'label' => 'Banner Promo',
                            'route' => 'banners.index',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Voucher & Diskon',
                            'route' => 'marketing.vouchers.index',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Berita & Artikel',
                            'route' => 'admin.news.index',
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
                    'icon' => 'chart-pie', // Changed from chart-bar
                    'roles' => ['admin', 'owner'],
                    'permission' => 'report.finance',
                    'sub_menu' => [
                        [
                            'label' => 'Laba Rugi',
                            'route' => 'reports.finance',
                            'roles' => ['owner'],
                        ],
                        [
                            'label' => 'Laporan Penjualan',
                            'route' => 'reports.sales',
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
                            'route' => 'settings.index',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Knowledge Base',
                            'route' => 'knowledge.index',
                            'roles' => ['admin', 'owner', 'technician', 'hr'],
                        ],
                        [
                            'label' => 'Audit Log',
                            'route' => 'activity-logs.index',
                            'roles' => ['admin', 'owner'],
                        ],
                        [
                            'label' => 'Kesehatan Sistem',
                            'route' => 'system.health',
                            'roles' => ['admin'],
                        ],
                        [
                            'label' => 'Backup Database',
                            'route' => 'system.backups',
                            'roles' => ['admin'],
                        ],
                    ]
                ],
            ],
        ],
    ],
];
