<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CrearAdminUser extends Component
{
    use WithPagination;

    public $name, $email, $password, $userId, $role_id;
    public $modal = false;
    public $search = '';

    public function render()
    {
        return view('livewire.crear-admin-user', [
            'users' => User::with('roles')->where('name', 'like', '%' . $this->search . '%')->paginate(10),
            'roles' => Role::all()
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->modal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email', // Verifica que el email no exista
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        // Crear el usuario solo si el email no está en uso
        if (!User::where('email', $this->email)->exists()) {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $role = Role::find($this->role_id);
            if ($role) {
                $user->assignRole($role);
            }

            session()->flash('message', 'Usuario creado exitosamente con rol ' . $role->name);
        } else {
            session()->flash('error', 'El email ya está en uso.');
        }

        $this->resetInputFields();
        $this->modal = false;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles->first()->id ?? null;
        $this->modal = true;
    }

    public function update()
    {
        // Verifica si el email está en uso por otro usuario
        $emailExists = User::where('email', $this->email)
            ->where('id', '!=', $this->userId) // Excluye el usuario actual
            ->exists();

        if ($emailExists) {
            $this->addError('email', 'El email ya está en uso por otro usuario.');
            return;
        }

        // Validación normal
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $role = Role::find($this->role_id);
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        session()->flash('message', 'Usuario actualizado correctamente.');
        $this->resetInputFields();
        $this->modal = false;
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->userId = null;
        $this->role_id = null;
    }
}
