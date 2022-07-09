<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //

    // protected $connection = 'pgsql_gral';
    protected $table = 'menus';
    // protected $dateFormat = 'd/m/Y H:i:s';
    // protected $fillable =   [
    //                             'nombre',
    //                             'padre',
    //                             'orden',
    //                             'activo',
    //                             'url_destino',
    //                             'id_aplicacion',
    //                             'icono'
    //                         ];

    protected $guarded = [];

    public function getChildren($data, $line)
    {
        $children = [];
        foreach ($data as $line1) {
            $line1a = (array)$line1;
            if ($line['id'] == $line1a['padre']) {
                $children = array_merge($children, [ array_merge($line1a, ['submenu' => $this->getChildren($data, $line1a) ]) ]);
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
        $menus = new Menu();

        $sql = "WITH RECURSIVE nodos_cte(id, modulo, nombre, padre, url_destino, icono, nivel, orden, path, path_orden, pseudo_ruta) AS(
            SELECT
              tn.id,
              tn.modulo,
              tn.nombre,
              tn.padre,
              tn.url_destino,
              tn.icono,
              1::INTEGER AS nivel,
              tn.orden,
              NULL::bigint[] || tn.id AS path,
              ARRAY[(tn.orden, tn.id)] AS path_orden,
              '' || tn.modulo as pseudo_ruta
            FROM menus AS tn
            WHERE tn.padre = 0
            UNION ALL
            (
            SELECT
              c.id,
              c.modulo,
              c.nombre,
              c.padre,
              c.url_destino,
              c.icono,
              p.nivel + 1 AS nivel,
              c.orden,
              p.path || c.id AS path,
              p.path_orden || (c.orden, c.id),
              p.pseudo_ruta || '.' || LOWER(REPLACE(c.nombre, ' ', ''))AS pseudo_ruta
            FROM nodos_cte AS p, menus AS c
            WHERE c.padre = p.id
            ORDER BY c.orden
            )
          )
          SELECT DISTINCT
              n1.id,
              n1.modulo,
              n1.nombre,
              n1.padre,
              n1.url_destino,
              n1.icono,
              n1.nivel,
              n1.orden,
              n1.path,
              n1.path_orden,
              CASE WHEN nivel <= 2 THEN	'' ELSE pseudo_ruta END AS pseudo_ruta
          FROM nodos_cte n1
          INNER JOIN
          (
              SELECT mj.path
              FROM public.model_has_roles mhr
              INNER JOIN public.role_has_permissions rhp ON mhr.role_id = rhp.role_id
              INNER JOIN public.permissions p2 ON rhp.permission_id = p2.id
              INNER JOIN (
                  SELECT n.url_destino, n.path
                FROM
                  nodos_cte AS n
              ) mj ON p2.name = mj.url_destino
              WHERE mhr.model_id = 5

          ) AS permisos ON n1.id = ANY(permisos.path)
          ORDER BY padre, orden, nombre";

        $data = DB::connection('pgsql')->select(DB::raw($sql));

        $menuAll = [];
        foreach ($data as $line) {
            $linea = (array)$line;
            $item = [ array_merge($linea, ['submenu' => $menus->getChildren($data,$linea) ]) ];
            $menuAll = array_merge($menuAll, $item);
        }

        return $menus->menuAll = $menuAll;
    }
}
