@can('manage tasks')
    <div class="grid grid-cols-2 gap-6">
        <!-- Categorías -->
        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Categorías</h2>

            <button wire:click="abrirModalCategoria"
                class="bg-green-600 text-white px-4 py-2 rounded dark:bg-green-800 dark:hover:bg-green-700">
                Nueva Categoría
            </button>

            <!-- Tabla de categorías -->
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-white">
                        <tr>
                            <th class="p-2 border border-gray-300 dark:border-gray-700">ID</th>
                            <th class="p-2 border border-gray-300 dark:border-gray-700">Nombre</th>
                            <th class="p-2 border border-gray-300 dark:border-gray-700">Imagen</th>
                            <th class="p-2 border border-gray-300 dark:border-gray-700">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white">
                                <td class="p-2 border border-gray-300 dark:border-gray-700">{{ $categoria->id }}</td>
                                <td class="p-2 border border-gray-300 dark:border-gray-700 font-semibold">
                                    {{ $categoria->nombre }}</td>
                                <td class="p-2 border border-gray-300 dark:border-gray-700">
                                    <img src="{{ asset('storage/images/categorias/' . $categoria->imagen) }}"
                                        class="w-16 h-16 object-cover rounded-lg">
                                </td>
                                <td class="p-2 border border-gray-300 dark:border-gray-700">
                                    <button wire:click="eliminarCategoria({{ $categoria->id }})"
                                        class="bg-red-600 text-white px-3 py-1 rounded dark:bg-red-800 dark:hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Marcas -->
        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Marcas</h2>

            <button wire:click="abrirModalMarca"
                class="bg-blue-600 text-white px-4 py-2 rounded dark:bg-blue-800 dark:hover:bg-blue-700">
                Nueva Marca
            </button>

            <!-- Tabla de marcas -->
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-white">
                        <tr>
                            <th class="p-2 border border-gray-300 dark:border-gray-700">ID</th>
                            <th class="p-2 border border-gray-300 dark:border-gray-700">Nombre</th>
                            <th class="p-2 border border-gray-300 dark:border-gray-700">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marcas as $marca)
                            <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white">
                                <td class="p-2 border border-gray-300 dark:border-gray-700">{{ $marca->id }}</td>
                                <td class="p-2 border border-gray-300 dark:border-gray-700 font-semibold">
                                    {{ $marca->nombre }}</td>
                                <td class="p-2 border border-gray-300 dark:border-gray-700">
                                    <button wire:click="eliminarMarca({{ $marca->id }})"
                                        class="bg-red-600 text-white px-3 py-1 rounded dark:bg-red-800 dark:hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Categoría -->

        @if ($modalCategoria)
            <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-lg font-bold mb-4 dark:text-white">Nueva Categoría</h2>

                    <input type="text" wire:model="nombreCategoria" placeholder="Nombre de la categoría"
                        class="border p-2 rounded w-full bg-white dark:bg-gray-900 dark:text-white dark:border-gray-600">

                    <!-- Input para subir imagen -->
                    <input type="file" wire:model.defer="imagenCategoria" accept="image/png, image/jpg, image/jpeg">

                    <!-- Vista previa de la imagen -->
                    @if ($imagenCategoria)
                        <div class="mt-2">
                            <img src="{{ $imagenCategoria->temporaryUrl() }}" class="w-52 h-52 object-cover rounded">
                        </div>
                    @endif

                    <div class="flex justify-end space-x-2 mt-4">
                        <button wire:click="cerrarModal" class="bg-gray-500 text-white px-4 py-2 rounded dark:bg-gray-600">
                            Cancelar
                        </button>
                        <button wire:click="{{ $mostrarGuardar ? 'agregarCategoria' : 'procesarSiguiente' }}"
                            wire:loading.attr="disabled" wire:target="agregarCategoria, procesarSiguiente"
                            class="px-4 py-2 rounded text-white transition-all duration-300 flex items-center justify-center
               {{ $mostrarGuardar ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }}">

                            <!-- Spinner de carga -->
                            <span wire:loading wire:target="agregarCategoria, procesarSiguiente">
                                <svg class="inline w-4 h-4 mr-2 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0116 0"></path>
                                </svg>
                            </span>

                            <!-- Texto del botón -->
                            <span wire:loading.remove wire:target="agregarCategoria, procesarSiguiente">
                                {{ $mostrarGuardar ? 'Guardar' : 'Siguiente' }}
                            </span>
                        </button>

                    </div>
                </div>
            </div>
        @endif


        <!-- Modal Marca -->
        @if ($modalMarca)
            <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-lg font-bold mb-4 dark:text-white">Nueva Marca</h2>

                    <input type="text" wire:model="nombreMarca" placeholder="Nombre de la marca"
                        class="border p-2 rounded w-full bg-white dark:bg-gray-900 dark:text-white dark:border-gray-600">

                    <div class="flex justify-end space-x-2 mt-4">
                        <button wire:click="cerrarModal" class="bg-gray-500 text-white px-4 py-2 rounded dark:bg-gray-600">
                            Cancelar
                        </button>
                        <button wire:click="agregarMarca" wire:loading.attr="disabled" wire:target="agregarMarca"
                            class="bg-blue-600 text-white px-4 py-2 rounded dark:bg-blue-800 dark:hover:bg-blue-700">
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endcan
