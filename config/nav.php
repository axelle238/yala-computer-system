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
                    'icon' => 'clipboard-list', // Need to create icon
                    'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                    'permission' => 'dashboard.view', // Basic permission for all staff
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
                    'label' => 'Kasir / POS',
                    'route' => 'transactions.create',
                    'icon' => 'pos',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'pos.access',
                ],
                [
                    'label' => 'Shift & Saldo',
                    'route' => 'finance.cash-register',
                    'icon' => 'cash-register',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'pos.access',
                ],
                [
                    'label' => 'Riwayat Transaksi',
                    'route' => 'transactions.index',
                    'icon' => 'receipt',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'order.view',
                ],
                [
                    'label' => 'Pengiriman & Logistik',
                    'route' => 'logistics.manager',
                    'icon' => 'truck', // Need icon
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'order.view',
                ],
                [
                    'label' => 'Servis & Perbaikan',
                    'route' => 'services.index',
                    'icon' => 'wrench',
                    'roles' => ['admin', 'owner', 'technician'],
                    'permission' => 'service.view',
                ],
                [
                    'label' => 'Papan Kanban Servis',
                    'route' => 'services.kanban',
                    'icon' => 'kanban',
                    'roles' => ['admin', 'owner', 'technician'],
                    'permission' => 'service.view',
                ],
                [
                    'label' => 'Garansi & RMA',
                    'route' => 'rma.index',
                    'icon' => 'shield-check',
                    'roles' => ['admin', 'owner', 'technician', 'warehouse'],
                    'permission' => 'order.refund',
                ],
                [
                    'label' => 'Produksi Rakitan',
                    'route' => 'assembly.manager',
                    'icon' => 'cpu-chip', // Need icon
                    'roles' => ['admin', 'owner', 'technician'],
                    'permission' => 'service.view',
                ],
            ],
        ],
        [
            'title' => 'INVENTARIS & GUDANG',
            'items' => [
                [
                    'label' => 'Produk & Stok',
                    'route' => 'products.index',
                    'icon' => 'box',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'product.view',
                ],
                [
                    'label' => 'Audit Stok (Opname)',
                    'route' => 'warehouses.stock-opname',
                    'icon' => 'clipboard-check',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'stock.opname',
                ],
                [
                    'label' => 'Pembelian (PO)',
                    'route' => 'purchase-orders.index',
                    'icon' => 'shopping-cart',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'stock.adjust',
                ],
                [
                    'label' => 'Penerimaan (GRN)',
                    'route' => 'purchase-orders.receive',
                    'icon' => 'truck-loading',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'stock.adjust',
                ],
                [
                    'label' => 'Cetak Label',
                    'route' => 'admin.produk.label',
                    'icon' => 'qr-code', // Need icon
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'product.view',
                ],
            ],
        ],
        [
            'title' => 'BISNIS & KORPORAT (B2B)',
            'items' => [
                [
                    'label' => 'Penawaran Harga',
                    'route' => 'quotations.index',
                    'icon' => 'newspaper', // Reuse newspaper or similar
                    'roles' => ['admin', 'owner'],
                    'permission' => 'order.view',
                ],
                [
                    'label' => 'Piutang & Invoice',
                    'route' => 'finance.receivables',
                    'icon' => 'banknotes',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'report.finance',
                ],
            ],
        ],
        [
            'title' => 'SUMBER DAYA MANUSIA',
            'items' => [
                [
                    'label' => 'Daftar Pegawai',
                    'route' => 'employees.index',
                    'icon' => 'users',
                    'roles' => ['admin', 'owner', 'hr'],
                    'permission' => 'employee.view',
                ],
                [
                    'label' => 'Jabatan (Role)',
                    'route' => 'employees.roles',
                    'icon' => 'shield-check', // Reuse shield for roles
                    'roles' => ['admin', 'owner'],
                    'permission' => 'role.manage',
                ],
                [
                    'label' => 'Absensi',
                    'route' => 'employees.attendance',
                    'icon' => 'clock',
                    'roles' => ['admin', 'owner', 'hr', 'technician', 'cashier', 'warehouse'],
                    'permission' => 'employee.view',
                ],
                [
                    'label' => 'Penggajian (Payroll)',
                    'route' => 'employees.payroll-manager',
                    'icon' => 'banknotes',
                    'roles' => ['admin', 'owner', 'hr'],
                    'permission' => 'employee.manage',
                ],
            ],
        ],
        [
            'title' => 'MARKETING & KONTEN',
            'items' => [
                [
                    'label' => 'Pesan Pembeli',
                    'route' => 'customers.inbox',
                    'icon' => 'chat-bubble-left-right',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'pos.access',
                ],
                [
                    'label' => 'Manajemen Pelanggan',
                    'route' => 'customers.index',
                    'icon' => 'user-group',
                    'roles' => ['admin', 'owner', 'cashier'],
                    'permission' => 'pos.access',
                ],
                [
                    'label' => 'Loyalty & Membership',
                    'route' => 'customers.loyalty',
                    'icon' => 'star', // Reuse star
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                ],
                [
                    'label' => 'Banner Promo',
                    'route' => 'banners.index',
                    'icon' => 'photo',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                ],
                [
                    'label' => 'Voucher & Diskon',
                    'route' => 'marketing.vouchers.index',
                    'icon' => 'receipt', // Reusing receipt or ticket icon
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                ],
                [
                    'label' => 'Berita & Artikel',
                    'route' => 'admin.news.index',
                    'icon' => 'newspaper',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                ],
                [
                    'label' => 'Reputasi & Ulasan',
                    'route' => 'reviews.manager',
                    'icon' => 'star', // Need icon
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                ],
            ],
        ],
        [
            'title' => 'SISTEM & KEUANGAN',
            'items' => [
                [
                    'label' => 'Laporan Laba Rugi',
                    'route' => 'reports.finance',
                    'icon' => 'chart-pie',
                    'roles' => ['owner'],
                    'permission' => 'report.finance',
                ],
                [
                    'label' => 'Laporan Penjualan',
                    'route' => 'reports.sales',
                    'icon' => 'receipt',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'report.sales',
                ],
                [
                    'label' => 'Laporan Stok & Mutasi',
                    'route' => 'reports.stock',
                    'icon' => 'clipboard-check',
                    'roles' => ['admin', 'owner', 'warehouse'],
                    'permission' => 'stock.view',
                ],
                [
                    'label' => 'Pengaturan Toko',
                    'route' => 'settings.index',
                    'icon' => 'cog',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.edit',
                ],
                [
                    'label' => 'Knowledge Base',
                    'route' => 'knowledge.index',
                    'icon' => 'book-open', // Need icon
                    'roles' => ['admin', 'owner', 'technician', 'hr'],
                    'permission' => 'dashboard.view', // General access
                ],
                [
                    'label' => 'Audit Log',
                    'route' => 'activity-logs.index',
                    'icon' => 'fingerprint',
                    'roles' => ['admin', 'owner'],
                    'permission' => 'setting.view',
                ],
                [
                    'label' => 'Kesehatan Sistem',
                    'route' => 'system.health',
                    'icon' => 'server', // Need icon
                    'roles' => ['admin'], // Admin only
                    'permission' => 'setting.manage', // High privilege
                ],
                [
                    'label' => 'Backup Database',
                    'route' => 'system.backups',
                    'icon' => 'database', // Need icon
                    'roles' => ['admin'],
                    'permission' => 'setting.manage',
                ],
            ],
        ],
    ],
];
