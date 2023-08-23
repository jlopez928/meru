<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;

use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Models\Administrativo\Meru_Administrativo\Compras\Producto;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;

class BienMaterialServicioIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    protected $listeners = ['inactivar'];

    public function mount()
    {
        $this->sort = 'cod_prod';
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

    public function confirmInactivar(Producto $producto)
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'titulo'    => 'Bien/Material/Servicio',
            'mensaje'   => 'Está seguro de Eliminar el Bien/Material/Servicio?',
            'funcion'   => 'inactivar',
            'cod_prod'  => $producto->cod_prod
        ]);
    }

    public function inactivar(Producto $producto)
    {
        try {
            $producto->update([
                                'sta_reg'  => Estado::Inactivo->value,
                            ]);

            $this->emit('swal:alert', [
                'tipo'      => 'success',
                'titulo'    => 'Éxito',
                'mensaje'   => 'Registro Eliminado Exitosamente'
            ]);

            return to_route('compras.configuracion.bien_material_servicio.index');

        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      => 'error',
                'titulo'    => 'Error',
                'mensaje'   => str($ex)->limit(250)
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.bien-material-servicio-index', [
            'headers' => [
                ['name' => 'Código', 'align' => 'center', 'sort' => 'cod_prod'],
                ['name' => 'Descripción', 'align' => 'center', 'sort' => 'des_prod'],
                ['name' => 'Último Precio', 'align' => 'left', 'sort' => 'ult_pre'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_reg'],
                'Acción'
            ],
            'productos' => Producto::query()
                ->when($this->search != '', function ($query) {
                    $query->where('cod_prod', 'like', '%' . strtoupper($this->search) . '%')
                        ->orWhere('des_prod', 'like', '%' . strtoupper($this->search) . '%')
                        ->orWhere('ult_pre', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)
        ]);
    }
}