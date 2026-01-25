<?php

namespace Database\Seeders;

use App\Models\AssetDepreciation;
use App\Models\CompanyAsset;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            [
                'name' => 'Laptop MacBook Pro M2',
                'asset_tag' => 'IT-001',
                'category' => 'Elektronik',
                'price' => 25000000,
                'years' => 4,
            ],
            [
                'name' => 'Meja Resepsionis',
                'asset_tag' => 'FUR-001',
                'category' => 'Furniture',
                'price' => 5000000,
                'years' => 8,
            ],
            [
                'name' => 'Motor Operasional Honda Beat',
                'asset_tag' => 'VEH-001',
                'category' => 'Kendaraan',
                'price' => 18000000,
                'years' => 5,
            ]
        ];

        foreach ($assets as $data) {
            if (CompanyAsset::where('asset_tag', $data['asset_tag'])->exists()) continue;

            $asset = CompanyAsset::create([
                'name' => $data['name'],
                'asset_tag' => $data['asset_tag'],
                'category' => $data['category'],
                'purchase_date' => Carbon::now()->subMonths(6), // Beli 6 bulan lalu
                'purchase_price' => $data['price'],
                'current_value' => $data['price'], // Initial
                'useful_life_years' => $data['years'],
                'location' => 'Kantor Pusat',
            ]);

            // Generate Depreciation
            $annualDepreciation = $data['price'] / $data['years'];
            $currentValue = $data['price'];

            for ($i = 1; $i <= $data['years']; $i++) {
                $date = Carbon::parse($asset->purchase_date)->addYears($i);
                $currentValue -= $annualDepreciation;
                
                AssetDepreciation::create([
                    'company_asset_id' => $asset->id,
                    'depreciation_date' => $date,
                    'amount' => $annualDepreciation,
                    'book_value_after' => max(0, $currentValue),
                ]);
            }
            
            // Update current value based on time passed (simple logic)
            // In real app, we check if depreciation date passed
        }
    }
}
