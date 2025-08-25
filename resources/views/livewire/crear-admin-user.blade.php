@can('manage tasks')
    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg">
        <input type="text" wire:model="search" placeholder="Buscar administrador..."
            class="border px-3 py-2 mb-4 w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white" />

        <button wire:click="create"
            class="bg-green-500 text-white px-4 py-2 rounded dark:bg-green-700 dark:hover:bg-green-600">Nuevo
            Administrador</button>

        @if ($modal)
            @include('crearUser.crear')
        @endif

        <table class="table-auto w-full mt-4 border dark:border-gray-700">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-800">
                    <th class="px-4 py-2 dark:text-gray-300">Nombre</th>
                    <th class="px-4 py-2 dark:text-gray-300">Email</th>
                    <th class="px-4 py-2 dark:text-gray-300">Rol</th> <!-- Nueva columna para los roles -->
                    <th class="px-4 py-2 dark:text-gray-300">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2 dark:text-white">{{ $user->name }}</td>
                        <td class="px-4 py-2 dark:text-white">{{ $user->email }}</td>
                        <td class="px-4 py-2 dark:text-white">
                            {{ $user->roles->pluck('name')->join(', ') }} <!-- Mostrar roles -->
                        </td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $user->id }})"
                                class="bg-blue-500 text-white px-2 py-1 rounded dark:bg-blue-700 dark:hover:bg-blue-600">Editar</button>
                            <button wire:click="delete({{ $user->id }})"
                                class="bg-red-500 text-white px-2 py-1 rounded dark:bg-red-700 dark:hover:bg-red-600">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 dark:text-white">
            {{ $users->links() }}
        </div>
    </div>
@endcan
