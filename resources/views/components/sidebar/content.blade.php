<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>
    <x-sidebar.link title="Inicio" href="{{ route('inicio') }}" :isActive="request()->routeIs('inicio')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    @can('manage tasks')
    <x-sidebar.dropdown title="Estadisticas" :active="Str::startsWith(request()->route()->uri(), 'buttons') ||
        request()->routeIs('estadisticas') ||
        request()->routeIs('estadisticas-prediccion')">
        <x-slot name="icon">
            <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Estadisticas" href="{{ route('estadisticas') }}" :active="request()->routeIs('estadisticas')" />
        <x-sidebar.sublink title="Estadisticas Prediccion" href="{{ route('estadisticas-prediccion') }}" :active="request()->routeIs('estadisticas-prediccion')" />
    </x-sidebar.dropdown>

    @endcan
    
    @can('manage tasks')
        <x-sidebar.link title="Crear Articulo" href="{{ route('contenido1') }}" :isActive="request()->routeIs('contenido1')">
            <x-slot name="icon">
                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>
    @endcan

    @can('manage tasks')
    <x-sidebar.link title="Ventas" href="{{ route('contenido2') }}" :isActive="request()->routeIs('contenido2')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>
    @endcan

    <x-sidebar.link title="Ventas hechas por el usuario" href="{{ route('contenido3') }}" :isActive="request()->routeIs('contenido3')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    @can('manage tasks')
    <x-sidebar.link title="Crear Usuario Adm" href="{{ route('crearadmuser') }}" :isActive="request()->routeIs('crearadmuser')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Crear Rol Usuario" href="{{ route('crearuserrol') }}" :isActive="request()->routeIs('crearuserrol')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>
    <x-sidebar.link title="Crear Categ. y Marca" href="{{ route('crearcategoriaymarca') }}" :isActive="request()->routeIs('crearcategoriaymarca')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>
    @endcan   
    {{-- Examples --}}

    {{-- <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')" /> --}}

    <x-sidebar.dropdown title="Ropa" :active="Str::startsWith(request()->route()->uri(), 'buttons') ||
        request()->routeIs('inicio') ||
        request()->routeIs('hombre') ||
        request()->routeIs('mujer')">
        <x-slot name="icon">
            <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Categoria" href="{{ route('categorias') }}" :active="request()->routeIs('categorias')" />
        <x-sidebar.sublink title="Hombre" href="{{ route('hombre') }}" :active="request()->routeIs('hombre')" />
        <x-sidebar.sublink title="Mujer" href="{{ route('mujer') }}" :active="request()->routeIs('mujer')" />
    </x-sidebar.dropdown>


</x-perfect-scrollbar>
