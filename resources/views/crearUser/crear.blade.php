<div
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 dark:bg-gray-800 dark:bg-opacity-70">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 dark:bg-gray-900 dark:text-white">
        <h2 class="text-lg font-bold mb-4">{{ $userId ? 'Editar Usuario' : 'Crear Usuario' }}</h2>

        <div class="mb-4">
            <label class="block text-sm font-semibold dark:text-gray-300">Nombre</label>
            <input type="text" wire:model="name"
                class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600">
            @error('name')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold dark:text-gray-300">Email</label>
            <input type="email" wire:model="email"
                class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600">
            @error('email')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        @if (!$userId)
            <div class="mb-4">
                <label class="block text-sm font-semibold dark:text-gray-300">Contraseña</label>
                <input type="password" wire:model="password"
                    class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        @endif

        <div class="mb-4">
            <label class="block text-sm font-semibold dark:text-gray-300">Rol</label>
            <select wire:model="role_id"
                class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                <option value="" class="dark:bg-gray-900">Seleccione un rol</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" class="dark:bg-gray-900">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <button wire:click="$set('modal', false)"
                class="bg-gray-500 text-white px-4 py-2 rounded dark:bg-gray-700 dark:hover:bg-gray-600">
                Cancelar
            </button>

            @if ($userId)
                <button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                    class="bg-blue-500 text-white px-4 py-2 rounded flex items-center justify-center dark:bg-blue-700 dark:hover:bg-blue-600">
                    <div class="flex items-center justify-center min-w-[100px]">
                        <span wire:loading.remove wire:target="update">Actualizar</span>
                        <span wire:loading wire:target="update"
                            class="animate-spin h-5 w-5 ml-2 border-2 border-white border-t-transparent rounded-full"></span>
                    </div>
                </button>
            @else
                <button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                    class="bg-green-500 text-white px-4 py-2 rounded flex items-center justify-center dark:bg-green-700 dark:hover:bg-green-600">
                    <div class="flex items-center justify-center min-w-[90px]">
                        <span wire:loading.remove wire:target="store">Guardar</span>
                        <span wire:loading wire:target="store"
                            class="animate-spin h-5 w-5 ml-2 border-2 border-white border-t-transparent rounded-full"></span>
                    </div>
                </button>
            @endif
        </div>
    </div>
</div>
