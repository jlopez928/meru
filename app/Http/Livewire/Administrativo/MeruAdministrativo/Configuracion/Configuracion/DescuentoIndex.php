<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Descuento;
use Livewire\Component;

use App\Traits\WithSorting;
use Livewire\WithPagination;


class DescuentoIndex extends Component
{

    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';


    public function mount()
    {
        $this->sort = 'id';
        $this->direction = 'desc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPaginate()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.configuracion.descuento-index', [
            'headers' => [
                ['name' => 'ID',          'align' => 'center', 'sort' => 'adm_descuentos.id'],
                ['name' => 'Código.',     'align' => 'center', 'sort' => 'cod_des'],
                ['name' => 'Descripción', 'align' => 'center', 'sort' => 'des_desc'],
                ['name' => 'Tipo Monto ', 'align' => 'center', 'sort' => 'tip_mto'],
                ['name' => 'Clase',       'align' => 'center', 'sort' => 'cla_desc'],
              //  ['name' => 'Fecha',       'align' => 'center', 'sort' => 'fecha'],
                // ['name' => 'Usuario',     'align' => 'center', 'sort' => 'usuario'],
                ['name' => 'Estado',      'align' => 'center', 'sort' => 'status'],
                'Acción'
            ],
            'descuento' => Descuento::query()
                     ->with('tipomontos:id,descripcion','adm_retencions:id,des_ret','adm_residencias:id,descripcion')
                    ->where('adm_descuentos.id', 'like', '%'.$this->search.'%')
                     ->orWhere('cod_des', 'like', '%'.ucfirst($this->search).'%')
                     ->orWhere('des_des', 'like', '%'.ucfirst($this->search).'%')
                     ->orwhere('tip_mto', 'like', '%'.ucfirst($this->search).'%')
                     ->orderBy($this->sort, $this->direction)
                    ->paginate($this->paginate)
                    ]);
    }

}
