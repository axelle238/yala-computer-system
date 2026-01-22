<?php

namespace App\Services;

class BusinessIntelligence
{
    /**
     * Generate simple insights for the dashboard.
     * 
     * @return array
     */
    public function getInsights()
    {
        return [
            [
                'type' => 'success',
                'message' => 'Penjualan laptop meningkat 15% minggu ini.',
            ],
            [
                'type' => 'warning',
                'message' => 'Stok SSD Samsung 990 Pro menipis, segera restock.',
            ],
            [
                'type' => 'info',
                'message' => 'Disarankan untuk membuat promo bundle Processor + Motherboard.',
            ]
        ];
    }
}