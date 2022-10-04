<?php

namespace App\Exports\Administrativo\Meru_Administrativo\Presupuesto;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison; // Imprimir los ceros (0) en las celdas
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class OperacionesPresupuestariasExport implements FromArray, WithColumnWidths, WithStyles, WithHeadings, WithColumnFormatting, WithStrictNullComparison
{
    use Exportable;

    private $arreglo;
    private $titulo;
    private $rango;

    public function __construct(array $arreglo)
    {
        $this->arreglo = $arreglo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->arreglo;
    }

    public function setData(array $arreglo)
    {
        $this->arreglo = $arreglo;
    }

    public function titulo($titulo)
	{
		$this->titulo = $titulo;
		return $this;
	}

    public function rango($rango) 
    {
        $this->rango = $rango;
        return $this;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
			'C' => 20,
			'D' => 50,
			'E' => 20,
			'F' => 10,
			'G' => 10,
			'H' => 10,
			'I' => 10,
            'J' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $conditional = new Conditional();
        // Condicional menor que
        // $conditional->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
        // $conditional->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
        // $conditional->addCondition('0');

        // Condicional contiene texto
        $conditional->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
        $conditional->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT);
        $conditional->setText('-');
        $conditional->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $conditional->getStyle()->getFont()->setBold(true);

        $conditionalStyles = $sheet->getStyle('E')->getConditionalStyles();
        $conditionalStyles[] = $conditional;

        $sheet->getStyle('E')->setConditionalStyles($conditionalStyles);

        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');
        return [
            'A1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'A2' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'A3' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            4 	 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'B'  => ['alignment' => ['horizontal' => 'center']],
            'C'  => ['alignment' => ['horizontal' => 'center']],
            'E'  => ['alignment' => ['horizontal' => 'right']],
            'F'  => ['alignment' => ['horizontal' => 'center']],
            'G'  => ['alignment' => ['horizontal' => 'center']],
            'H'  => ['alignment' => ['horizontal' => 'center']],
            'I'  => ['alignment' => ['horizontal' => 'center']],
            'J'  => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function headings(): array
	{
		return [
			['HIDROBOLIVAR'],
            [$this->titulo],
            [$this->rango],
			['OPERACION','DOCUMENTO','FECHA','NOMBRE','MONTO MOV','PA','GN','ESP','SUB.ESP','ESTRUCTURA']
		];
	}

    public function columnFormats(): array
	{
		return [
            'A' => NumberFormat::FORMAT_TEXT,
			'B' => NumberFormat::FORMAT_TEXT,
			'C' => NumberFormat::FORMAT_TEXT,
			'D' => NumberFormat::FORMAT_DATE_DMYSLASH,
			'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
			'F' => NumberFormat::FORMAT_NUMBER,
			'G' => NumberFormat::FORMAT_NUMBER,
			'H' => NumberFormat::FORMAT_NUMBER,
			'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_TEXT
		];
	}
}
