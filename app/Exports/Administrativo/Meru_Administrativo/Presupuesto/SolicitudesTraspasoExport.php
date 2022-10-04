<?php

namespace App\Exports\Administrativo\Meru_Administrativo\Presupuesto;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SolicitudesTraspasoExport implements FromQuery, WithColumnWidths, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

	private $queryExec;
	private $titulo;

	public function __construct()
	{
		$this->titulo = 'SOLICITUDES DE TRASPASOS PRESUPUESTARIOS';
	}

	public function query()
	{
		return $this->queryExec;
	}

	public function setQuery($query)
	{
		$this->queryExec = $query;
		return $this;
	}

	public function titulo($titulo)
	{
		$this->titulo = $titulo;
		return $this;
	}

	public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
			'C' => 20,
			'D' => 50,
			'E' => 30,
			'F' => 20,
			'G' => 50,
			'H' => 50,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:I1');
        return [
            2 	 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'A'  => ['alignment' => ['horizontal' => 'center']],
            'B'  => ['alignment' => ['horizontal' => 'center']],
            'C'  => ['alignment' => ['horizontal' => 'center']],
            'E'  => ['alignment' => ['horizontal' => 'center']],
            'A1' => ['font' => ['bold' => true]]
        ];
    }

	public function headings(): array
	{
		return [
			[$this->titulo],
			['SOLICITUD', 'STATUS', 'FECHA', 'GERENCIA', 'ESTRUCTURA DE GASTOS', 'MONTO', 'CONCEPTO', 'JUSTIFICACIÓN']
		];
	}

	public function columnFormats(): array
	{
		return [
            'A' => NumberFormat::FORMAT_DATE_DMYSLASH,
			'B' => NumberFormat::FORMAT_TEXT,
			'C' => NumberFormat::FORMAT_TEXT,
			'D' => NumberFormat::FORMAT_TEXT,
			'E' => NumberFormat::FORMAT_TEXT,
			'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'G' => NumberFormat::FORMAT_TEXT,
			'H' => NumberFormat::FORMAT_TEXT
		];
	}
}
