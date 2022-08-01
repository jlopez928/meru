<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Formulacion\Configuracion;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;

class MaestroLeyIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public $paginate           = '10';
    
    public function mount()
    {
        $this->sort      = 'id';
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
        return view('livewire.administrativo.meru-administrativo.formulacion.configuracion.maestro-ley-index', [
            'headers' => [
                ['name' => 'ID', 'align' => 'center', 'sort' => 'id', 'width' => '6%'],
                ['name' => 'Año', 'align' => 'center', 'sort' => 'ano_pro', 'width' => '7%'],
                ['name' => 'Código', 'align' => 'center', 'sort' => 'cod_com', 'width' => '15%'],
                ['name' => 'Centro Costo', 'align' => 'center', 'sort' => null, 'width' => '31%'],
                ['name' => 'Partida Presupuestaria', 'align' => 'center', 'sort' => null, 'width' => '31%'],
                'Acción'
            ],
            'estructuras' => MaestroLey::query()
                ->where('cod_com', 'ilike', $this->search.'%')
                ->orWhere(\DB::raw('ano_pro::TEXT'), 'like', $this->search . '%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)  
        ]);
    }
}
