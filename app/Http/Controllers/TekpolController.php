<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetsService;

class TekpolController extends Controller
{
    public function dashboard()
    {
        $data = GoogleSheetsService::getTekpolData();

        $matrixRows = $data['matrixRows'] ?? [];
        $grandTotal = $data['grandTotal'] ?? 0;
        $cachedAt   = $data['cachedAt']   ?? null;
        $fetchError = $data['error']       ?? null;

        // Hitung grand totals dari semua baris unit
        $grandTotals     = array_fill(0, 16, 0);
        $grandTotalUnits = 0;
        foreach ($matrixRows as $section) {
            foreach ($section['rows'] as [$unit, $values, $total]) {
                foreach ($values as $idx => $v) {
                    $grandTotals[$idx] += $v;
                }
                $grandTotalUnits += $total;
            }
        }

        // Summary cards dihitung otomatis dari data
        $permDihuni    = array_sum(array_slice($grandTotals, 0, 4));
        $permTdk       = array_sum(array_slice($grandTotals, 4, 4));
        $semiDihuni    = array_sum(array_slice($grandTotals, 8, 4));
        $semiTdk       = array_sum(array_slice($grandTotals, 12, 4));
        $totalPintu    = $grandTotal ?: $grandTotalUnits;

        $summaryCards = [
            ['title' => 'Total Pintu',         'value' => number_format($totalPintu),            'sub' => 'Seluruh Regional 1',   'color' => 'blue',   'icon' => 'home'],
            ['title' => 'Permanen Dihuni',      'value' => number_format($permDihuni),            'sub' => 'Baik + Rusak',          'color' => 'green',  'icon' => 'building'],
            ['title' => 'Semi Permanen Dihuni', 'value' => number_format($semiDihuni),            'sub' => 'Baik + Rusak',          'color' => 'violet', 'icon' => 'clipboard'],
            ['title' => 'Tidak Dihuni',         'value' => number_format($permTdk + $semiTdk),   'sub' => 'Perm. + Semi Perm.',   'color' => 'orange', 'icon' => 'warning'],
        ];

        $subHeaders = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Sedang'];

        return view('tekpol.dashboard-new');
    }

    /** Hapus cache dan redirect kembali ke dashboard */
    public function refreshCache()
    {
        GoogleSheetsService::clearCache();
        return redirect()->route('tekpol.dashboard')->with('success', 'Data berhasil diperbarui dari Google Sheets.');
    }
}
