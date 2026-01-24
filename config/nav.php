<?php

return [
    'menu' => [
        [
            'title' => 'UTAMA',
            'items' => [
                [
                    'label' => 'Beranda',
                    'route' => 'dashboard',
                    'icon'  => 'home',
                    'roles' => ['admin', 'owner', 'technician', 'cashier', 'warehouse'],
                ],
                [
                    'label' => 'Layar Toko (Front)',
                    'route' => 'home',
                    'icon'  => 'store',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'target' => '_blank',
                ],
            ]
        ],
        [
            'title' => 'OPERASIONAL',
            'items' => [
                [
                    'label' => 'Kasir / POS',
                    'route' => 'transactions.create',
                    'icon'  => 'pos',
                    'roles' => ['admin', 'owner', 'cashier'],
                ],
                [
                    'label' => 'Shift & Saldo',
                    'route' => 'finance.cash-register',
                    'icon'  => 'cash-register',
                    'roles' => ['admin', 'owner', 'cashier'],
                ],
                [
                    'label' => 'Riwayat Transaksi',
                    'route' => 'transactions.index',
                    'icon'  => 'receipt',
                    'roles' => ['admin', 'owner', 'cashier'],
                ],
                [
                    'label' => 'Servis & Perbaikan',
                    'route' => 'services.index',
                    'icon'  => 'wrench',
                    'roles' => ['admin', 'owner', 'technician'],
                ],
                [
                    'label' => 'Papan Kanban Servis',
                    'route' => 'services.kanban',
                    'icon'  => 'kanban',
                    'roles' => ['admin', 'owner', 'technician'],
                ],
                [
                    'label' => 'Garansi & RMA',
                    'route' => 'rma.index',
                    'icon'  => 'shield-check',
                    'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                ],
            ]
        ],
        [
            'title' => 'INVENTARIS & GUDANG',
            'items' => [
                [
                    'label' => 'Produk & Stok',
                    'route' => 'products.index',
                    'icon'  => 'box',
                    'roles' => ['admin', 'owner', 'warehouse'],
                ],
                [
                    'label' => 'Audit Stok (Opname)',
                    'route' => 'warehouses.stock-opname',
                    'icon'  => 'clipboard-check',
                    'roles' => ['admin', 'owner', 'warehouse'],
                ],
                [
                    'label' => 'Pembelian (PO)',
                    'route' => 'purchase-orders.index',
                    'icon'  => 'shopping-cart',
                    'roles' => ['admin', 'owner', 'warehouse'],
                ],
                [
                    'label' => 'Penerimaan (GRN)',
                    'route' => 'purchase-orders.receive',
                    'icon'  => 'truck-loading',
                    'roles' => ['admin', 'owner', 'warehouse'],
                ],
            ]
        ],
        [
            'title' => 'SUMBER DAYA MANUSIA',
            'items' => [
                [
                    'label' => 'Daftar Pegawai',
                    'route' => 'employees.index',
                    'icon'  => 'users',
                    'roles' => ['admin', 'owner', 'hr'],
                ],
                [
                    'label' => 'Absensi',
                    'route' => 'employees.attendance',
                    'icon'  => 'clock',
                    'roles' => ['admin', 'owner', 'hr', 'technician', 'cashier', 'warehouse'], // All can see own attendance typically, or limit to admin
                ],
                [
                    'label' => 'Penggajian (Payroll)',
                    'route' => 'employees.payroll-manager',
                    'icon'  => 'banknotes',
                    'roles' => ['admin', 'owner', 'hr'],
                ],
            ]
        ],
        [
            'title' => 'MARKETING & KONTEN',
            'items' => [
                [
                    'label' => 'Pesan Pembeli',
                    'route' => 'customers.inbox',
                    'icon'  => 'chat-bubble-left-right', // Need to create icon
                    'roles' => ['admin', 'owner', 'cashier'],
                ],
                [
                    'label' => 'Manajemen Pelanggan',
                    'route' => 'customers.index',
                    'icon'  => 'user-group',
                    'roles' => ['admin', 'owner', 'cashier'],
                ],
                [
                    'label' => 'Banner Promo',
                    'route' => 'banners.index',
                    'icon'  => 'photo',
                    'roles' => ['admin', 'owner'],
                ],
                [
                    'label' => 'Berita & Artikel',
                    'route' => 'admin.news.index',
                    'icon'  => 'newspaper',
                    'roles' => ['admin', 'owner'],
                ],
            ]
        ],
        [
            'title' => 'SISTEM & KEUANGAN',
            'items' => [
                [
                    'label' => 'Laporan Laba Rugi',
                    'route' => 'finance.profit-loss',
                    'icon'  => 'chart-pie',
                    'roles' => ['owner'], // Sensitive
                ],
                [
                    'label' => 'Pengaturan Toko',
                    'route' => 'settings.index',
                    'icon'  => 'cog',
                    'roles' => ['admin', 'owner'],
                ],
                [
                    'label' => 'Audit Log',
                    'route' => 'activity-logs.index',
                    'icon'  => 'fingerprint',
                    'roles' => ['admin', 'owner'],
                ],
            ]
        ],
    ]
];
