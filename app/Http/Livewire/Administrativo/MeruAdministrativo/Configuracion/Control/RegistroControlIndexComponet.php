<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Control;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class RegistroControlIndexComponet extends Component
{   use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'ano_pro';
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
        return view('livewire.administrativo.meru-administrativo.configuracion.control.registro-control-index-componet', [
            'headers' => [
                ['name' => 'ID',                 'align' => 'center', 'sort' => 'id'],
                ['name' => 'Año en Proceso',     'align' => 'center', 'sort' => 'ano_pro'],
                ['name' =>'Estado',              'align' => 'center', 'sort' => 'sta_con'],
                ['name' =>'Empresa',       'align' => 'center', 'sort' => 'des_emp1'],
                ['name' =>'Mes',                 'align' => 'center', 'sort' => 'ult_mes'],
                ['name' =>'Comprob. Abiertos',   'align' => 'center', 'sort' => 'con_con'],
                ['name' =>'Cta. Resultado',      'align' => 'center', 'sort' => 'ctaresultado'],
                ['name' =>'Cd. Emic. Cheque',    'align' => 'center', 'sort' => 'ciudad'],

            ],
            'registrocontrol' =>  RegistroControl::query()
                                ->where('id', 'like', '%'.$this->search.'%')
                                ->orWhere('ano_pro', 'like', '%'.ucfirst($this->search).'%')
                                ->orwhere('des_emp1', 'like', '%'.ucfirst($this->search).'%')
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->paginate)
       ]);
    }
}
