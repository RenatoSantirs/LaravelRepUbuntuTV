<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-dark-eval-1">
        <div class="p-6 flex flex-col items-center justify-center gap-4 bg-white dark:bg-gray-800 rounded-md shadow-md">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">¡Ups! La página que estás buscando no existe, o hubo problemas con el internet.</h3>
            <p class="text-lg text-center text-gray-700 dark:text-gray-300">Parece que la página ha sido movida, eliminada o nunca existió.</p>
            <p class="text-lg text-center text-gray-700 dark:text-gray-300">Pero dado el caso se realizo la compra y sale esta pantalla, vaya a "Ventas hechas por el usuario" para confirmar su compra.</p>
            <p class="text-lg text-center text-gray-700 dark:text-gray-300">Quizas hubo un error si realizo la compra revise la vista Ventas hechas por el usuario.</p>
            <p class="text-lg text-center text-gray-700 dark:text-gray-300">En caso de error comunicarse con...</p>

            <div class="mt-4 flex gap-4">
                <a href="{{ url('inicio') }}" class="btn btn-primary bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">
                    Volver a la página inicio
                </a>
            
                <a href="{{ url('contenido3') }}" class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                    Ventas hechas por el usuario
                </a>
            </div>
        </div>
    </div>
</x-app-layout>