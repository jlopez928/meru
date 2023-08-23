<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;

use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Models\Administrativo\Meru_Administrativo\Compras\Comprador;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;

class CompradorIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    protected $listeners = ['activar','inactivar'];

    public function mount()
    {
        $this->sort = 'cod_com';
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

    public function confirmActivar(Comprador $comprador)
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'titulo'    => 'Compradores',
            'mensaje'   => 'Está seguro de Activar el Comprador?',
            'funcion'   => 'activar',
            'cod_com'   => $comprador->cod_com
        ]);
    }

    public function activar(Comprador $comprador)
    {
        try {
                $comprador->update([
                                'sta_reg'   => Estado::Activo->value,
                            ]);

            $this->emit('swal:alert', [
                'tipo'      => 'success',
                'titulo'    => 'Éxito',
                'mensaje'   => 'Comprador Activado Exitosamente'
            ]);

            return to_route('compras.configuracion.comprador.index');

        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      => 'error',
                'titulo'    => 'Error',
                'mensaje'   => str($ex)->limit(250)
            ]);

            return redirect()->back();
        }
    }
    
    public function confirmInactivar(Comprador $comprador)
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'titulo'    => 'Compradores',
            'mensaje'   => 'Está seguro de Inactivar el Comprador?',
            'funcion'   => 'inactivar',
            'cod_com'   => $comprador->cod_com
        ]);
    }

    public function inactivar(Comprador $comprador)
    {
        try {
                $comprador->update([
                                'sta_reg'   => Estado::Inactivo->value,
                            ]);

            $this->emit('swal:alert', [
                'tipo'      => 'success',
                'titulo'    => 'Éxito',
                'mensaje'   => 'Comprador Inactivado Exitosamente'
            ]);

            return to_route('compras.configuracion.comprador.index');

        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      => 'error',
                'titulo'    => 'Error',
                'mensaje'   => str($ex)->limit(250)
            ]);

            return redirect()->back();
        }
    }
    
    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.comprador-index', [
            'headers' => [
                            ['name' => 'Código', 'align' => 'center', 'sort' => 'cod_com'],
                            ['name' => 'Usuario', 'align' => 'left', 'sort' => 'usu_com'],
                            ['name' => 'Nombre', 'align' => 'left', 'sort' => 'nombre'],
                            ['name' => 'Cédula', 'align' => 'left', 'sort' => 'cedula'],
                            ['name' => 'Correo Electrónico', 'align' => 'left', 'sort' => 'correo'],
                            ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_reg'],
                            'Acción'
                        ],
            'compradores'   =>  Comprador::query()
                                            ->with('usuariot:usuario,nombre,cedula,correo')
                                            ->when($this->search != '', function($query) {
                                                $query->where('cod_com', 'like', '%'.$this->search.'%')
                                                    ->orWhere('usu_com', 'like', '%'.$this->search.'%')
                                                    ->orWhereHas('usuariot', function($q){
                                                        $q->where('nombre', 'like', '%'.strtoupper($this->search).'%')
                                                            ->orwhere('cedula', 'like', '%'.$this->search.'%')
                                                            ->orwhere('correo', 'like', '%'.$this->search.'%');
                                                    });
                                            })
                                            ->orderBy($this->sort, $this->direction)
                                            ->paginate($this->paginate)
        ]);
    }
}