<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\LogStok;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class MutasiExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithColumnFormatting
{
    protected $id_produk;
    protected $start;
    protected $end;

    public function __construct($id_produk, $start, $end)
    {
        $this->id_produk = $id_produk;
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return LogStok::with('relProduk')
            ->when($this->id_produk != 'ALL', function ($query) {
                $query->where('id_produk', $this->id_produk);
            })
            ->when($this->start && $this->end, function ($query) {
                $query->whereBetween('tanggal', [$this->start, $this->end]);
            })
            ->orderBy('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Produk',
            'Status',
            'Jumlah',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            Date::stringToExcel($row->tanggal),
            $row->relProduk->nama,
            Helper::statusLogStok($row->status),
            $row->unit_masuk ?? $row->unit_keluar,
            $row->keterangan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 30,
            'D' => 15,
            'E' => 12,
            'F' => 40,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
