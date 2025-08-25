<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class CrearRolUser extends Component
{
    public $name;
    public $modal = false;

    protected $rules = [
        'name' => 'required|min:3|unique:roles,name'
    ];

    public function create()
    {
        $this->reset();
        $this->modal = true;
    }

    public function store()
    {
        $this->validate();

        Role::create([
            'name' => $this->name,
            'guard_name' => 'web' // Asegura que el guard se define correctamente
        ]);

        session()->flash('message', 'Rol creado exitosamente.');

        $this->modal = false;
        $this->reset();
    }

    public function deleteRole($roleId)
    {
        $role = Role::where('id', $roleId)->firstOrFail();
        $role->delete();

        session()->flash('message', 'Rol eliminado correctamente.');
    }


    public function render()
    {
        return view('livewire.crear-rol-user', [
            'roles' => Role::all()
        ]);
    }
}
