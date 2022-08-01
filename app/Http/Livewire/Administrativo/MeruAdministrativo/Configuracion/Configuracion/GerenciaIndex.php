<?php
/*
namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Configuracion;

use Livewire\Component;

class GerenciaIndex extends Component
{
    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.configuracion.gerencia-index');
    }
}
*/

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Configuracion;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;

class GerenciaIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public $paginate           = '10';
    
    public function mount()
    {
        $this->sort      = 'cod_ger';
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

    public function order()
    {

    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.configuracion.gerencia-index', [
            'headers' => [
                ['name' => 'ID', 'align' => 'center', 'sort' => 'cod_ger', 'width' => '6%'],
                ['name' => 'Nombre', 'align' => 'center', 'sort' => 'des_ger', 'width' => '40%'],
                ['name' => 'Centro de Costo', 'align' => 'center', 'sort' => 'centro_costo', 'width' => '12%'],
                ['name' => 'Nombre Jefe', 'align' => 'center', 'sort' => 'nom_jefe', 'width' => '24%'],
                ['name' => 'Nomenclatura', 'align' => 'center', 'sort' => 'nomenclatura', 'width' => '10%'],
                'Acción'
            ],
            'gerencias' => Gerencia::query()
                ->where('des_ger', 'ilike', '%'.$this->search.'%')
                ->orWhere('centro_costo', 'like', $this->search.'%')
                ->orWhere('nom_jefe', 'ilike', '%'.$this->search.'%')
                ->orWhere('nomenclatura', 'ilike', '%'.$this->search.'%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)  
        ]);
    }
}
