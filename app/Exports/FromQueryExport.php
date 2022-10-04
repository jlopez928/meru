<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FromQueryExport implements FromQuery, WithColumnWidths, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

	private $alpha;
	private $formats;
	private $config;

	public function __construct($conf)
	{
		$this->alpha = range('A','Z');
		$this->formats = [
			'T' => NumberFormat::FORMAT_TEXT, // Texto
			'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Decimales
			'D' => NumberFormat::FORMAT_DATE_DMYSLASH // Fecha
		];
		$this->config = $conf;
	}

	public function query()
	{
		return $this->config['query'];
	}

	public function columnWidths(): array
    {
        $i = 0;
        $widths = [];

        foreach($this->config['ancho'] as $width) {
            $widths[$this->alpha[$i]] = $width;
            $i++;
        }

        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        $styles  = [];
        $cantTit = count($this->config['titulo']);
        $cantCol = count($this->config['columnas']);
        $cantAli = count($this->config['alineacion']);

		// Alineación de columnas
        for ($i = 0; $i < $cantAli; $i++) {
            $styles[$this->alpha[$i]] = [
                'alignment' => [
                    'horizontal' => [
                        'L' => 'left',
                        'C' => 'center',
                        'R' => 'right'
                    ][$this->config['alineacion'][$i]]
                ]
            ];
        }

    	// Combinar celdas titulo y fijar estilos
        for ($i = 1; $i <= $cantTit; $i++) {
            $sheet->mergeCells("A$i:{$this->alpha[$cantCol - 1]}$i");

            $styles["A$i"] = [
                'alignment' => ['horizontal' => 'center'],
                'font' => ['bold' => true]
            ];
        }

    	// Estilos cabecera
        $styles[$cantTit + 1] = ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']];

        return $styles;
    }

	public function headings(): array
	{
		$headings = [];

		foreach ($this->config['titulo'] as $titulo) {
			$headings[] = (array)$titulo;
		}

		$headings[] = $this->config['columnas'];

		return $headings;
	}

	public function columnFormats(): array
	{
		$formats = [];
		$cant = count($this->config['formatos']);

		for ($i = 0; $i < $cant; $i++) {
			$formats[$this->alpha[$i]] = $this->formats[$this->config['formatos'][$i]];
		}

		return $formats;
	}

	/*
	// Uso
	$data = [
		'query'      => $sql,
		'titulo'     => ['TITULO REPORTE', 'SUBTITULOS'],
		'ancho'      => [30,20,20,20,20,20,20,20,20],
		'alineacion' => ['R','C','C','C','L','R','C','C','C'],
		'formatos'   => ['T','M','M','M','M','M','M','M','M'],
		'columnas'   => ['ESTRUCTURA','MONTO LEY','MODIFICADO','APARTADO','PRE-COMP','COMP','CAUSADO','PAGADO','DISPONIBLE']
	];

	return (new FromQueryExport($data))->download('nombre_archivo.xlsx');
	*/
}

