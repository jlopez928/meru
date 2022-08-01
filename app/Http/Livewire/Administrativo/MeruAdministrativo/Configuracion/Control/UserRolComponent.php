<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Control;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Rol;
use Illuminate\Support\Str;
use Livewire\Component;

class UserRolComponent extends Component
{

    public $selectedUser = [];
    public $rol = [];
    public $iden;
    public $name;
    public $cedula;
    public $email;
    public $userrol;


 public function mount()
    {
        $this->userrol      = $this->userrol;
        $this->iden      = $this->userrol->id;
        $this->name    = $this->userrol->name;
        $this->cedula    = $this->userrol->cedula;
        $this->email    = $this->userrol->email;
        $this->selectedUser = collect();

        $this->rol = Rol::query()
        ->where('status', 1)
        ->orderBy('name')
        ->get(['id', 'name']);

        $this->selectedUser =$this->userrol->roles()->pluck('id');

    }

     public function update()
    {
        try {

             if (count( $this->selectedUser) >0){
                $this->userrol->syncRoles($this->selectedUser);
                flash()->addSuccess('Roles actualizado Exitosamente.');
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

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.control.user-rol-component');
    }
}
