<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleSheetsService
{
    const SPREADSHEET_ID = '1Qpf0C2uJOCt-UF826QTCZMPwPv4c7xMg';
    const SHEET_GID      = '1160985008';
    const CACHE_KEY      = 'tekpol_kondisi_rumah';
    const CACHE_TTL      = 3600; // 1 jam

    /** Ambil data (dari cache jika ada) */
    public static function getTekpolData(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::fetchAndParse();
        });
    }

    /** Paksa refresh cache */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /** Parse angka dari string CSV (hapus koma, kutip, spasi) */
    private static function num(string $val): int
    {
        return (int) preg_replace('/[^0-9]/', '', $val);
    }

    /** Ambil & parse CSV dari Google Sheets */
    private static function fetchAndParse(): array
    {
        $url = sprintf(
            'https://docs.google.com/spreadsheets/d/%s/export?format=csv&gid=%s',
            self::SPREADSHEET_ID,
            self::SHEET_GID
        );

        try {
            $response = Http::timeout(20)->get($url);
            if (!$response->successful()) {
                return self::emptyResult('Gagal mengambil data dari Google Sheets (HTTP ' . $response->status() . ')');
            }
            $csv = $response->body();
        } catch (\Exception $e) {
            return self::emptyResult('Koneksi gagal: ' . $e->getMessage());
        }

        // Normalisasi line ending
        $csv   = str_replace(["\r\n", "\r"], "\n", $csv);
        $lines = explode("\n", $csv);

        $matrixRows     = [];
        $currentSection = null;
        $grandTotal     = 0;

        // Keyword yang menandai baris header (bukan data)
        $skipPrefixes = [
            'unit', 'database', 'permanen', 'semi permanen',
            'dihuni', 'tidak dihuni', 'baik', 'rusak', 'sedang',
            'uraian', 'bangunan', 'jumlah bangunan',
        ];

        foreach ($lines as $line) {
            if (trim($line) === '') continue;

            // Parse CSV baris
            $cols = str_getcsv($line);
            while (count($cols) < 19) $cols[] = '';

            $name = trim($cols[1] ?? '');

            // ── Baris dengan nama kosong = kemungkinan subtotal ──────────
            if ($name === '') {
                if ($currentSection !== null) {
                    $first = self::num($cols[2] ?? '');
                    if ($first > 0 || self::num($cols[3] ?? '') > 0) {
                        // Subtotal baris per distrik
                        $values = [];
                        for ($i = 2; $i <= 17; $i++) {
                            $values[] = self::num($cols[$i] ?? '');
                        }
                        $currentSection['subtotal']      = $values;
                        $currentSection['subtotalTotal'] = self::num($cols[18] ?? '');
                        $matrixRows[]   = $currentSection;
                        $currentSection = null;
                    }
                }
                continue;
            }

            // ── Skip baris header ─────────────────────────────────────────
            $nameLower = strtolower($name);
            $skip      = false;
            foreach ($skipPrefixes as $prefix) {
                if (str_starts_with($nameLower, $prefix)) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;

            // ── Parse 16 nilai + total ────────────────────────────────────
            $values = [];
            for ($i = 2; $i <= 17; $i++) {
                $values[] = self::num($cols[$i] ?? '');
            }
            $total = self::num($cols[18] ?? '');

            // ── Deteksi tipe baris ────────────────────────────────────────
            if (str_contains(strtoupper($name), 'DISTRIK')) {
                // Baris distrik baru
                $regionName = strtoupper(preg_replace('/\s+/', ' ', trim($name)));
                $currentSection = [
                    'region'        => $regionName,
                    'distrik'       => $values,
                    'distrikTotal'  => $total,
                    'subtotal'      => array_fill(0, 16, 0),
                    'subtotalTotal' => 0,
                    'rows'          => [],
                ];
            } elseif (strtoupper($name) === 'JUMLAH') {
                // Baris grand total
                $grandTotal = $total;
            } elseif ($currentSection !== null) {
                // Baris unit kebun/PKS
                $currentSection['rows'][] = [$name, $values, $total];
            }
        }

        // Simpan section terakhir jika tidak ada subtotal
        if ($currentSection !== null) {
            $matrixRows[] = $currentSection;
        }

        return [
            'matrixRows'  => $matrixRows,
            'grandTotal'  => $grandTotal,
            'cachedAt'    => now()->format('d M Y H:i'),
            'error'       => null,
        ];
    }

    private static function emptyResult(string $error): array
    {
        return [
            'matrixRows' => [],
            'grandTotal' => 0,
            'cachedAt'   => null,
            'error'      => $error,
        ];
    }
}
