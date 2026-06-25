<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapBulananExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    protected $rekap;
    protected $tahun;

    public function __construct(array $rekap, int $tahun)
    {
        $this->rekap = $rekap;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('laporan.excel.rekap-bulanan', [
            'rekap' => $this->rekap,
            'tahun' => $this->tahun
        ]);
    }

    public function title(): string
    {
        return 'Rekap Bulanan SPP ' . $this->tahun;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['italic' => true, 'size' => 10]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}
