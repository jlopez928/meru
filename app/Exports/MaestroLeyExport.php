<?php

namespace App\Exports;

// use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
// use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaestroLeyExport implements FromQuery, WithColumnWidths, WithColumnFormatting, WithHeadings, WithStyles
{
	use Exportable;

	private $queryExec;
	private $titulo;

	public function __construct()
	{
		$this->titulo = 'PRESUPUESTO HIDROBOLIVAR';
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
        	'A' => 30,
            'B' => 20,
			'C' => 20,
			'D' => 20,
			'E' => 20,
			'F' => 20,
			'G' => 20,
			'H' => 20,
			'I' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
    	$sheet->mergeCells('A1:I1');
        return [
            2 	 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'A'  => ['alignment' => ['horizontal' => 'center']],
            'A1' => ['font' => ['bold' => true]]
        ];
    }

	public function headings(): array
	{
		return [
			[$this->titulo],
			['ESTRUCTURA','MONTO LEY','MODIFICADO','APARTADO','PRE-COMP','COMP','CAUSADO','PAGADO','DISPONIBLE']
		];
	}

	public function columnFormats(): array
	{
		return [
			'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
		];
	}
}
