<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Formulacion\Configuracion;

use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class PartidaPresupuestariaIndex extends Component
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
        return view('livewire.administrativo.meru-administrativo.formulacion.configuracion.partida-presupuestaria-index', [
            'headers' => [
                ['name' => 'ID', 'align' => 'center', 'sort' => 'id', 'width' => '5%'],
                ['name' => 'Código', 'align' => 'center', 'sort' => 'cod_cta', 'width' => '15%'],
                ['name' => 'Descripción', 'align' => 'center', 'sort' => 'des_con', 'width' => '55%'],
                ['name' => 'Partida Asociada (4.11)', 'align' => 'center', 'sort' => 'part_asociada', 'width' => '15%'],
                'Acción'
            ],
            'partidas' =>
                PartidaPresupuestaria::query()
                ->where('des_con', 'ilike', '%'.$this->search.'%')
                ->orWhere('cod_cta', 'ilike', $this->search.'%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)
        ]);
    }
}
