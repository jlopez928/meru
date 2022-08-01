<?php

namespace App\Imports;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class MaestroLeyImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $errores;
    private $estructuras;
    private $idUsuario;
    private $usuarioHB;

    public function __construct() 
    {
        $this->errores     = [];
        $this->estructuras = [];
        $this->usuario     = auth()->user()->id;
        $this->usuarioHB   = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
    }

    public function getErrores()
    {
        return $this->errores;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MaestroLey([
            'ano_pro'    => $row['ano_pro'],
            'tip_cod'    => $row['tip_cod'],
            'cod_pryacc' => $row['cod_pryacc'],
            'cod_obj'    => $row['cod_obj'],
            'gerencia'   => $row['gerencia'],
            'unidad'     => $row['unidad'],
            'cod_par'    => $row['cod_par'],
            'cod_gen'    => $row['cod_gen'],
            'cod_esp'    => $row['cod_esp'],
            'cod_sub'    => $row['cod_sub'],
            'cod_com'    => $row['cod_com'],
            'ley_for'    => $row['ley_for'],
            'mto_ley'    => $row['ley_for'],
            'mto_dis'    => $row['ley_for'],
            'usuario'    => $this->usuarioHB,
            'user_id'    => $this->usuario
        ]);
    }

    public function batchSize(): int
    {
        return 4000;
    }

    public function chunkSize(): int
    {
        return 4000;
    }

    public function rules(): array
    {
        return [
            '*.ano_pro'    => 'integer|required',
            '*.tip_cod'    => 'integer|required',
            '*.cod_pryacc' => 'integer|required',
            '*.cod_obj'    => 'integer|required',
            '*.gerencia'   => 'integer|required',
            '*.cod_par'    => 'integer|required',
            '*.cod_gen'    => 'integer|required',
            '*.cod_esp'    => 'integer|required',
            '*.cod_sub'    => 'integer|required',
            '*.cod_com'    => 'string|required',
            '*.ley_for'    => 'numeric|required',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $errores = [];
        foreach ($failures as $failure) {
            foreach ($failure->errors() as $err) {
                $this->errores[] = ['fila' => $failure->row(), 'columna' => $failure->attribute(), 'error' => $err];
            }
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $arrData = $validator->getData();

            foreach ($arrData as $rowNum => $row) {
                $cenCosRow = implode('.', [
                    \Str::padLeft($row['tip_cod'], 2, '0'),
                    \Str::padLeft($row['cod_pryacc'], 2, '0'),
                    \Str::padLeft($row['cod_obj'], 2, '0'),
                    \Str::padLeft($row['gerencia'], 2, '0'),
                    \Str::padLeft($row['unidad'], 2, '0'),
                ]);

                $partidaRow = implode('.', [
                    \Str::padLeft($row['cod_par'], 2, '0'),
                    \Str::padLeft($row['cod_gen'], 2, '0'),
                    \Str::padLeft($row['cod_esp'], 2, '0'),
                    \Str::padLeft($row['cod_sub'], 2, '0')
                ]);

                $estructura = $cenCosRow . '.' . $partidaRow;


                // No existe el Centro de Costo en el año
                $existeCentroCosto = CentroCosto::where('cod_cencos', $cenCosRow)->where('ano_pro', $row['ano_pro']);

                if (is_null($existeCentroCosto)) {
                    $validator->errors()->add($rowNum, 'El Centro de Costo no existe para el año.');
                }

                // No existe la Partida Presupuetaria
                $existePartida = PartidaPresupuestaria::where('cod_cta', '4.' . $partidaRow)->first();

                if (is_null($existePartida)) {
                    $validator->errors()->add($rowNum, 'La Partida Presupuestaria no existe.');
                }

                // Estructura difiere de individuales
                if ($estructura != $row['cod_com']) {
                    $validator->errors()->add($rowNum, 'El código de la estructura no coincide con los campos individuales');
                }

                // Estructura ya existe para el año
                $existeEstructura = MaestroLey::where('cod_com', $estructura)
                    ->where('ano_pro', $row['ano_pro'])
                    ->first();

                if (!is_null($existeEstructura)) {
                    $validator->errors()->add($rowNum, 'El código de la estructura ya existe para el año');
                }

                // Estructura repetida en el archivo
                if (in_array($estructura, $this->estructuras)) {
                    $validator->errors()->add($rowNum, 'El código de la estructura está repetida en el archivo');
                } else {
                    $this->estructuras[] = $estructura;
                }
            }
        });
    }
}