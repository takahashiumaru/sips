<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TunggakanExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    protected $tunggakan;

    public function __construct($tunggakan)
    {
        $this->tunggakan = $tunggakan;
    }

    public function view(): View
    {
        return view('laporan.excel.tunggakan', [
            'tunggakan' => $this->tunggakan
        ]);
    }

    public function title(): string
    {
        return 'Laporan Tunggakan SPP';
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
