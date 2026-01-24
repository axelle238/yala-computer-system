<?php

namespace App\Services;

use App\Models\Product;

class PcCompatibilityService
{
    /**
     * Analyze the current selection of parts and return issues/warnings.
     */
    public function checkCompatibility(array $parts): array
    {
        $issues = [];
        $warnings = [];
        $info = [];

        $cpu = $this->getPart($parts, 'processors');
        $mobo = $this->getPart($parts, 'motherboards');
        $ram = $this->getPart($parts, 'rams');
        $gpu = $this->getPart($parts, 'gpus');
        $psu = $this->getPart($parts, 'psus');
        $case = $this->getPart($parts, 'cases');

        // 1. CPU & Motherboard (Socket)
        if ($cpu && $mobo) {
            $cpuSocket = $this->getSpec($cpu, 'socket');
            $moboSocket = $this->getSpec($mobo, 'socket');

            if ($cpuSocket && $moboSocket && strcasecmp($cpuSocket, $moboSocket) !== 0) {
                $issues[] = "CPU Socket ({$cpuSocket}) tidak cocok dengan Motherboard ({$moboSocket}).";
            }
        }

        // 2. RAM & Motherboard (Memory Type)
        if ($ram && $mobo) {
            $ramType = $this->getSpec($ram, 'memory_type'); // DDR4, DDR5
            $moboRamType = $this->getSpec($mobo, 'memory_type');

            // Logic: Mobo usually supports one type.
            if ($ramType && $moboRamType) {
                // Normalize strings
                $r = strtolower($ramType);
                $m = strtolower($moboRamType);

                if (!str_contains($m, $r)) {
                    $issues[] = "RAM ({$ramType}) tidak didukung oleh Motherboard ({$moboRamType}).";
                }
            }
        }

        // 3. Form Factor (Mobo vs Case)
        if ($mobo && $case) {
            $moboForm = strtolower($this->getSpec($mobo, 'form_factor') ?? 'atx');
            $caseForms = strtolower($this->getSpec($case, 'supported_forms') ?? 'atx,m-atx,itx');

            // Logic: Case supports list vs Mobo single form
            if (!str_contains($caseForms, $moboForm)) {
                $issues[] = "Ukuran Motherboard ({$moboForm}) mungkin tidak muat di Casing.";
            }
        }

        // 4. Power Supply (Wattage)
        $totalWattage = 50; // System Overhead
        if ($cpu) $totalWattage += (int) $this->getSpec($cpu, 'tdp', 65);
        if ($gpu) $totalWattage += (int) $this->getSpec($gpu, 'tdp', 0);
        
        $recommendedPsu = $totalWattage + 100; // Headroom

        if ($psu) {
            $psuWattage = (int) $this->getSpec($psu, 'wattage', 0);
            if ($psuWattage < $totalWattage) {
                $issues[] = "PSU ({$psuWattage}W) tidak mencukupi. Estimasi beban: {$totalWattage}W.";
            } elseif ($psuWattage < $recommendedPsu) {
                $warnings[] = "PSU agak mepet. Disarankan minimal {$recommendedPsu}W untuk keamanan.";
            }
        } else {
            if ($totalWattage > 200) {
                $info[] = "Estimasi daya sistem: {$totalWattage}W. Pilih PSU minimal {$recommendedPsu}W.";
            }
        }

        return [
            'issues' => $issues,
            'warnings' => $warnings,
            'info' => $info,
            'wattage' => $totalWattage
        ];
    }

    private function getPart(array $parts, string $key)
    {
        return $parts[$key] ?? null;
    }

    private function getSpec($part, $key, $default = null)
    {
        // Handle if part is array (from session/selection) or Product model
        if (is_array($part)) {
            return $part['specs'][$key] ?? $default;
        }
        // If Model (fetched fresh)
        return $part->specifications[$key] ?? $default;
    }
}
