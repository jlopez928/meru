<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Contratos\Proceso;

use App\Models\Administrativo\Meru_Administrativo\Compra\EncNotaEntrega;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class ActaContratoObraServIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort         = 'id';
        $this->direction    = 'desc';

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
        return view('livewire.administrativo.meru-administrativo.contratos.proceso.acta-contrato-obra-serv-index',[
            'headers' => [
                ['name' => 'ID ',             'align' => 'center', 'sort' => 'id'],
                ['name' => 'Año',             'align' => 'center', 'sort' => 'fk_ano_pro'],
                ['name' => 'Grupo',           'align' => 'center', 'sort' => 'grupo'],
                ['name' => 'Número ',         'align' => 'center', 'sort' => 'nro_ent'],
                ['name' => 'Nro Ord. Compra', 'align' => 'center', 'sort' => 'fk_nro_ord'],
                ['name' => 'Fec. Creación',   'align' => 'center', 'sort' => 'fec_pos'],
                ['name' => 'Estado',          'align' => 'center', 'sort' => 'sta_ent'],
              'Acción'
            ],
            'encnotaentrega' => EncNotaEntrega::query()
                     ->where('grupo','=','CO')
                     ->where('id',           'like', '%'.$this->search.'%')
                    //  ->Where('grupo',      'like', '%'.$this->search.'%')
                    //  ->Where('sta_ent',    'like', '%'.ucfirst($this->search).'%')
                    //  ->orWhere('nro_ent',    'like', '%'.ucfirst($this->search).'%')
                    //  ->orWhere('fk_nro_ord', 'like', '%'.ucfirst($this->search).'%')
                      ->where('fk_ano_pro', 'like', '%'.ucfirst($this->search).'%')
                    // ->orWhere('fec_pos',    'like', '%'.ucfirst($this->search).'%')
                    ->orderBy($this->sort, $this->direction)
                    ->paginate($this->paginate)
                    ]);
    }
}



