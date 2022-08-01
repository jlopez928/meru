<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\TasaCambio;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class TasaCambioIndex extends Component
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
        return view('livewire.administrativo.meru-administrativo.configuracion.configuracion.tasa-cambio-index', [
            'headers' => [
                ['name' => 'ID',              'align' => 'center', 'sort' => 'id'],
                ['name' => 'Fecha Vigencia.', 'align' => 'center', 'sort' => 'fec_tasa'],
                ['name' => 'Monto ',          'align' => 'center', 'sort' => 'bs_tasa'],
                ['name' => 'Estado',          'align' => 'center', 'sort' => 'sta_reg'],
                ['name' => 'Fecha',           'align' => 'center', 'sort' => 'fecha'],
                // ['name' => 'Usuario',     'align' => 'center', 'sort' => 'usuario'],
                'Acción'
            ],
            'tasacambio' => TasaCambio::query()
                    ->where('id', 'like', '%'.$this->search.'%')
                     ->orWhere('fec_tasa', 'like', '%'.ucfirst($this->search).'%')
                     ->orwhere('bs_tasa', 'like', '%'.ucfirst($this->search).'%')
                    ->orderBy($this->sort, $this->direction)
                    ->paginate($this->paginate)
                    ]);
    }
}
