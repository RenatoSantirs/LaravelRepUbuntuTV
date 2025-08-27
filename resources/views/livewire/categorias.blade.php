<div>
    <h2>Categorías Disponibles</h2>
    <div class="grid grid-cols-2 gap-4">
        @foreach ($categorias as $categoria)
            <div>
                <a href="{{ route('articulo-categorias', $categoria->categoria) }}">
                    <img src="{{ asset('storage/' . $categoria->imagen) }}" 
                         alt="{{ $categoria->categoria }}" 
                         class="w-full h-96 object-cover">
                    <p class="text-center">{{ $categoria->categoria }}</p>
                </a>
            </div>
        @endforeach
    </div>
</div>