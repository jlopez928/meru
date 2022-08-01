<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Configuracion;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\UbicacionGeografica;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;

class UbicacionGeograficaIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public $paginate           = '10';
    
    public function mount()
    {
        $this->sort      = 'id';
        $this->direction = 'asc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPaginate()
    {
        $this->resetPage();
    }

    public function order()
    {

    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.configuracion.ubicacion-geografica-index',[
            'headers' => [
                ['name' => 'ID', 'align' => 'center', 'sort' => 'id', 'width' => '8%'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'cod_edo', 'width' => '8%'],
                ['name' => 'Municipio', 'align' => 'center', 'sort' => 'cod_mun', 'width' => '8%'],
                ['name' => 'Parroquia', 'align' => 'center', 'sort' => 'des_par', 'width' => '8%'],
                ['name' => 'Descripcion', 'align' => 'center', 'sort' => 'des_ubi', 'width' => '25%'],
                ['name' => 'Capital', 'align' => 'center', 'sort' => 'capital', 'width' => '25%'],
                ['name' => 'Código', 'align' => 'center', 'sort' => 'cod_ubi', 'width' => '8%'],
                'Acción'
            ],
            'ubicaciones' => UbicacionGeografica::query()
                ->orWhere('des_ubi', 'ilike', '%'.$this->search.'%')
                ->orWhere('capital', 'ilike', '%'.$this->search.'%')
                ->orWhere('cod_ubi', 'ilike', '%'.$this->search.'%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)  
        ]);
    }
}
