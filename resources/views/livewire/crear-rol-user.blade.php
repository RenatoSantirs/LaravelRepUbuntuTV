@can('manage tasks')
    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg">
        <input type="text" wire:model="search" placeholder="Buscar rol..."
            class="border px-3 py-2 mb-4 w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white" />

        <button wire:click="create"
            class="bg-green-500 text-white px-4 py-2 rounded dark:bg-green-700 dark:hover:bg-green-600">Nuevo Rol</button>

        @if ($modal)
            <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-lg font-bold mb-4 dark:text-white">Crear Rol</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold dark:text-gray-300">Nombre del Rol</label>
                        <input type="text" wire:model="name"
                            class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button wire:click="$set('modal', false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded dark:bg-gray-600">
                            Cancelar
                        </button>

                        <button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                            class="bg-blue-500 text-white px-4 py-2 rounded flex items-center justify-center dark:bg-blue-700 dark:hover:bg-blue-600">
                            <div class="flex items-center justify-center min-w-[90px]">
                                <span wire:loading.remove wire:target="store">Guardar</span>
                                <span wire:loading wire:target="store"
                                    class="animate-spin h-5 w-5 ml-2 border-2 border-white border-t-transparent rounded-full"></span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <table class="table-auto w-full mt-4 border dark:border-gray-700">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-800">
                    <th class="px-4 py-2 dark:text-gray-300">Rol</th>
                    <th class="px-4 py-2 dark:text-gray-300">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2 text-center dark:text-white">{{ $role->name }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="deleteRole('{{ $role->id }}')"
                                class="bg-red-500 text-white px-2 py-1 rounded dark:bg-red-700 dark:hover:bg-red-600">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endcan
