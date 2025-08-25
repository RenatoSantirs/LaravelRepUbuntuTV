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

                    <label for="categoria"
                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Categoría</label>
                    <select id="categoria" wire:model="categoria"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                        <option value="">Elegir la categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="nombre"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nombre:</label>
                        <input type="text" id="nombre" wire:model="nombre"
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                        @error('nombre')
                            <div class="block w-full text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <label for="marca"
                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Marca</label>
                    <select id="marca" wire:model="marca"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                        <option value="">Elegir la marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->nombre }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                    @error('marca')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <label for="descripcion"
                        class="block text-gray-700 text-sm font-bold mb-2  dark:text-white">Descripción(No
                        necesario):</label>
                    <input type="text"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                        dark:border-slate-800"
                        id="descripcion" wire:model="descripcion">
                    @error('descripcion')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <label for="caracteristicas"
                        class="block text-gray-700 text-sm font-bold mb-2  dark:text-white">Características(No
                        necesario):</label>
                    <input type="text"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                        dark:border-slate-800"
                        id="caracteristicas" wire:model="caracteristicas">
                    @error('caracteristicas')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <label for="imagen"
                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Imagen:</label>
                    @if ($id_articulo)
                        @if (is_string($imagen) && $imagen !== '' && Str::endsWith($imagen, ['.png', '.jpg', '.jpeg']))
                            <img src="{{ asset('storage/' . $imagen) }}" class="mb-2" width="150"
                                alt="Imagen actual">
                        @elseif ($imagen && $imagen->isValid() && in_array($imagen->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                            <img src="{{ $imagen->temporaryUrl() }}" class="mb-2" width="150"
                                alt="Vista previa de la imagen">
                        @endif

                        <input type="file" id="imagen" wire:model="imagen" accept="image/*"
                            class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                        <input type="text" value="{{ 'storage/' . $imagen }}" readonly
                            class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800"
                            id="imagen" wire:model="imagen">
                    @else
                        <input type="file" id="imagen" wire:model="imagen" accept="image/*"
                            class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                    @endif
                    @error('imagen')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <label for="video" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Video (No
                        necesario):</label>
                    @if ($id_articulo)
                        @if (is_string($video) && $video !== '' && Str::endsWith($video, ['.mp4']))
                            <video controls width="320">
                                <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @elseif ($video && $video->isValid() && $video->getClientOriginalExtension() === 'mp4')
                            <video controls width="320">
                                <source src="{{ $video->temporaryUrl() }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif

                        <input type="file" id="video" wire:model="video" accept="video/mp4"
                            class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                    @else
                        <input type="file" id="video" wire:model="video" accept="video/mp4"
                            class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                    @endif
                    <!-- Manejo de errores -->
                    @error('video')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror


                    <label for="costo"
                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Costo:</label>
                    <input type="text"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                        id="costo" wire:model="costo">
                    @error('costo')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <label for="cantidad"
                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Cantidad:</label>
                    <input type="text"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800
                            dark:border-slate-800"
                        id="cantidad" wire:model="cantidad">
                    @error('cantidad')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <label for="sexo_de_prenda"
                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Sexo de la
                        prenda:</label>
                    <select id="sexo_de_prenda" wire:model="sexo_de_prenda"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                        <option value="">Selecciona el sexo de la prenda</option>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="Unisex">Unisex</option>
                        <option value="Ninguno">Ninguno</option>
                    </select>
                    @error('sexo_de_prenda')
                        <div class="block w-full text-red-600">{{ $message }}</div>
                    @enderror

                    <div x-data="{ mostrarOpciones: false }" class="mt-4">
                        <button @click="mostrarOpciones = !mostrarOpciones" type="button"
                            class="text-blue-500 hover:text-blue-700 focus:outline-none">
                            Mostrar más opciones
                        </button>

                        <div x-show="mostrarOpciones" class="mt-4">
                            <label for="imagen2" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">
                                Imagen adicional:
                            </label>
                            @if ($id_articulo)
                                @if (is_string($imagen2) && $imagen2 !== '')
                                    <img src="{{ asset('storage/' . $imagen2) }}" class="mb-2" width="150"
                                        alt="Imagen adicional">
                                @else
                                    <img src="{{ $imagen2 ? $imagen2->temporaryUrl() : '#' }}" class="mb-2"
                                        width="150" alt="Vista previa de la imagen adicional">
                                @endif

                                <input type="file" id="imagen2" wire:model="imagen2"
                                    class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                                <input type="text" value="{{ 'storage/' . $imagen2 }}" readonly
                                    class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800"
                                    id="imagen2" wire:model="imagen2">
                            @else
                                <input type="file" id="imagen2" wire:model="imagen2"
                                    class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                            @endif
                            @error('imagen2')
                                <div class="block text-red-600 text-sm">{{ $message }}</div>
                            @enderror

                            <label for="imagen3" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">
                                Imagen adicional:
                            </label>
                            @if ($id_articulo)
                                @if (is_string($imagen3) && $imagen3 !== '')
                                    <img src="{{ asset('storage/' . $imagen3) }}" class="mb-2" width="150"
                                        alt="Imagen adicional">
                                @else
                                    <img src="{{ $imagen3 ? $imagen3->temporaryUrl() : '#' }}" class="mb-2"
                                        width="150" alt="Vista previa de la imagen adicional">
                                @endif

                                <input type="file" id="imagen3" wire:model="imagen3"
                                    class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                                <input type="text" value="{{ 'storage/' . $imagen3 }}" readonly
                                    class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800"
                                    id="imagen3" wire:model="imagen3">
                            @else
                                <input type="file" id="imagen3" wire:model="imagen3"
                                    class="mb-2 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:placeholder-gray-400 dark:bg-slate-800 dark:border-slate-800">
                            @endif
                            @error('imagen3')
                                <div class="block text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
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
