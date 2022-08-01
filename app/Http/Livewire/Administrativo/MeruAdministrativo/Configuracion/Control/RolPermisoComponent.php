<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Control;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Permiso;
use Illuminate\Support\Str;
use Livewire\Component;

class RolPermisoComponent extends Component
{   public $selectedRoles = [];
    public $selectedModuloId;
    public $permiso = [];
    public $iden;
    public $name;
    public $rol;


    public function mount()
    {
        $this->rol      = $this->rol;
        $this->iden      = $this->rol->id;
        $this->name    = $this->rol->name;
        $this->selectedRoles = collect();


    }

    public function getModuloProperty(){
        return Modulo::query()
                        ->where('status', 1)
                        ->orderBy('nombre')
                        ->pluck('nombre','id');
    }

    public function update()
    {
        try {

             if (count($this->selectedRoles) >0){
                $newPermissions = Permiso::where('modulo_id', '>=', $this->permiso[0]->modulo_id)
                                ->whereIn('id',$this->selectedRoles)
                                ->get();
                 $permissions =   $this->rol  ->getAllPermissions()
                                ->where('modulo_id', '!=', $this->permiso[0]->modulo_id)
                                ->merge($newPermissions);
                                $this->rol->syncPermissions($permissions  );
                                flash()->addSuccess('Permiso asociados Exitosamente.');
                                app()['cache']->forget('spatie.permission.cache');
            }else{
                flash()->addInfo('Debe Seleccionar Registro.');
                return redirect()->back()->withInput();
             }
       }catch (\Exception $e) {
           flash()->addError('Transacci&oacute;n Fallida: '.Str::limit($e, 200));
           return redirect()->back()->withInput();
        }

    }
    public function addPermiso()
    {
        $this->selectedRoles = $this->rol->getAllPermissions()
                                ->where('modulo_id', $this->selectedModuloId)
                                ->pluck('id');

        $this->permiso= Permiso::query()
        ->where('status', '1')
        ->where('modulo_id', $this->selectedModuloId)
        ->orderBy('name')
        ->get(['id', 'name']);


    }
    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.control.rol-permiso-component');
    }
}
