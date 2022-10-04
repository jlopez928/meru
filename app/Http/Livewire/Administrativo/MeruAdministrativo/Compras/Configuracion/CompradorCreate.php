<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Models\Administrativo\Meru_Administrativo\Compras\Comprador;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Usuario;

class CompradorCreate extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    protected $listeners = ['registrar'];

    public function mount()
    {
        $this->sort = 'nombre';
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

    public function confirmRegistrar(Usuario $usuario)
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'titulo'    => 'Compradores',
            'mensaje'   => 'Está seguro de Guardar el Comprador?',
            'funcion'   => 'registrar',
            'usuario'   => $usuario->usuario
        ]);
    }

    public function registrar(Usuario $usuario)
    {
        try {
            Comprador::create([
                                'usu_com'   => $usuario->usuario,
                                'usuario'   => auth()->id()
                            ]);

            $this->emit('swal:alert', [
                'tipo'      => 'success',
                'titulo'    => 'Éxito',
                'mensaje'   => 'Registro Guardado Exitosamente'
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
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.comprador-create', [
            'headers' => [
                            ['name' => 'Ficha', 'align' => 'left', 'sort' => 'ficha'],
                            ['name' => 'Usuario', 'align' => 'left', 'sort' => 'usuario'],
                            ['name' => 'Cédula', 'align' => 'left', 'sort' => 'cedula'],
                            ['name' => 'Nombre', 'align' => 'left', 'sort' => 'nombre'],
                            ['name' => 'Correo Electrónico', 'align' => 'left', 'sort' => 'correo'],
                            ['name' => 'Estado', 'align' => 'center', 'sort' => 'status'],
                            'Acción'
                        ],
            'usuarios'   =>  Usuario::query()
                                            ->activo()
                                            ->notComprador()
                                            ->when($this->search != '', function($query) {
                                                $query->where(function($q){
                                                    $q->where('ficha', 'like', '%'.strtoupper($this->search).'%')
                                                        ->orWhere('usuario', 'like', '%'.$this->search.'%')
                                                        ->orWhere('cedula', 'like', '%'.$this->search.'%')
                                                        ->orWhere('nombre', 'like', '%'.strtoupper($this->search).'%')
                                                        ->orWhere('correo', 'like', '%'.$this->search.'%');

                                                });
                                            })
                                            ->orderBy($this->sort, $this->direction)
                                            ->paginate($this->paginate)
        ]);
    }
}
