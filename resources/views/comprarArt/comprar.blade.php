<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4  dark:bg-slate-900">

                    <div class="mb-4">
                        <label for="id_usuario" class="block text-gray-700 text-sm font-bold mb-2  dark:text-white">ID
                            Usuario</label>
                        <input type="text"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                            id="id_usuario" wire:model="id_usuario" disabled>
                        @error('id_usuario')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="imagen"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Imagen:</label>
                        <img src="{{ asset('storage/' . $imagen) }}" width="200">
                        <input type="text"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                            id="imagen" wire:model="imagen" style="display: none;">
                        @error('imagen')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="nombre_ape"
                            class="block text-gray-700 text-sm font-bold mb-2  dark:text-white">Nombres y
                            apellidos:</label>
                        <input type="text"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                            id="nombre_ape" wire:model="nombre_ape">
                        @error('nombre_ape')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="direccion"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Direccion:</label>
                        <input type="text"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                            id="direccion" wire:model="direccion">
                        @error('direccion')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="id_articulo" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">ID
                            articulo:</label>
                        <input type="text"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                            id="id_articulo" wire:model="id_articulo" disabled>
                        @error('id_articulo')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="nom_articulo"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nombre de
                            Articulo:</label>
                        <input type="text"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                            id="nom_articulo" wire:model="nom_articulo" disabled>
                        @error('nom_articulo')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="costo"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Costo:</label>
                        <input type="text"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                            id="costo" wire:model="costo" disabled>
                        @error('costo')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="cantidad"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Cantidad:</label>
                        <input type="number" id="cantidad" wire:model="cantidad" min="1"
                            max="{{ $stockDisponible }}"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                        @error('cantidad')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror

                        <label for="total"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Total:</label>
                        <input type="text" id="total" wire:model="total" disabled
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                        @error('total')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror


                        @if ($mensajeError)
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert">
                                <strong class="font-bold">¡Error!</strong>
                                <span class="block sm:inline">{{ $mensajeError }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-slate-900">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button wire:click.prevent="guardar()" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-purple-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Guardar</button>
                        </span>

                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button wire:click="cerrarModal()" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancelar</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
