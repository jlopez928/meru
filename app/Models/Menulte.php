<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menulte extends Model
{
    //
    use SoftDeletes;

    protected $connection = 'pgsql_gral';
    protected $table = 'menus';
    protected $dateFormat = 'd/m/Y H:i:s';
    protected $fillable =   [
                                'nombre',
                                'padre',
                                'orden',
                                'activo',
                                'url_destino',
                                'id_aplicacion',
                                'icono'
                            ];

    public function getChildren($data, $line)
    {
        $children = [];
        foreach ($data as $line1) {
            if ($line['id'] == $line1['padre']) {
                $children = array_merge($children, [ array_merge($line1, ['submenu' => $this->getChildren($data, $line1) ]) ]);
            }
        }
        return $children;
    }

    public function optionsMenu($aplicacion)
    {
        return $this->whereIdAplicacion($aplicacion)
            ->where('activo', 1)
            ->orderby('padre')
            ->orderby('orden')
            ->orderby('nombre')
            ->get()
            ->toArray();
    }

    public static function menus($aplicacion)
    {
        $menus = new Menulte();
        $data = $menus->optionsMenu($aplicacion);
        $menuAll = [];
        foreach ($data as $line) {
            $item = [ array_merge($line, ['submenu' => $menus->getChildren($data, $line) ]) ];
            $menuAll = array_merge($menuAll, $item);
        }

        return $menus->menuAll = $menuAll;
    }
}
