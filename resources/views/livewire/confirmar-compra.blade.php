<div>
    <!-- Div para bloqueador de anuncios -->
    <div id="adblock"
        class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 dark:bg-dark-eval-1">
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-md text-center">
            <h2 class="text-lg font-semibold text-white dark:text-gray-200">
                Por favor, desactiva tu bloqueador de anuncios
            </h2>
            <p class="text-white dark:text-gray-400">
                Para continuar con la compra, desactiva el bloqueador de anuncios y refresca la página.
            </p>
            <p class="text-white dark:text-gray-400">
                Para evitar errores usa otro navegador que no sea Brave y que no bloqueen anuncios por favor.
            </p>
            <p class="text-white dark:text-gray-400">
                Ya que podría obtener errores en la página al momento de comprar. Gracias.
            </p>
        </div>
    </div>

    <!-- Div para bloqueador de trackers -->
    <div id="adtrackers"
        class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 dark:bg-dark-eval-1">
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-md text-center">
            <h2 class="text-lg font-semibold text-white dark:text-gray-200">
                Por favor, desactiva tu bloqueador de trackers
            </h2>
            <p class="text-white dark:text-gray-400">
                Para continuar con la compra, desactiva el bloqueador de trackers y refresca la página.
            </p>
            <p class="text-white dark:text-gray-400">
                Para evitar errores usa otro navegador que no sea Brave y que no bloqueen trackers por favor.
            </p>
            <p class="text-white dark:text-gray-400">
                Ya que podría obtener errores en la página al momento de comprar. Gracias.
            </p>
        </div>
    </div>

    <!-- Div para bloqueador de anuncios y trackers -->
    <div id="AdblokAdtacker"
        class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 dark:bg-dark-eval-1">
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-md text-center">
            <h2 class="text-lg font-semibold text-white dark:text-gray-200">
                Por favor, desactiva tu bloqueador de trackers y bloqueador de anuncios(AdBlockers)
            </h2>
            <p class="text-white dark:text-gray-400">
                Para continuar con la compra, desactiva el bloqueador de trackers y anuncios, luego refresca la página.
            </p>
            <p class="text-white dark:text-gray-400">
                Para evitar errores usa otro navegador que no sea Brave y que no bloqueen trackers ni anuncios por favor.
            </p>
            <p class="text-white dark:text-gray-400">
                Ya que podría obtener errores en la página al momento de comprar. Gracias.
            </p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Inicializamos variables de estado
        var adblockDetected = false;
        var trackersDetected = false;

        // Intentamos cargar un script que podría ser bloqueado por adblockers
        var script = document.createElement('script');
        script.src =
            'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'; // Cambia esta URL a una fuente que pueda ser bloqueada por adblockers
        script.onload = function() {
            // Si el script se carga correctamente, no hacemos nada para adblock
        };
        script.onerror = function() {
            // Si el script no se carga (probablemente por un adblocker), detectamos adblock
            adblockDetected = true;
            document.getElementById('adblock').classList.remove('hidden');
        };

        // Agregamos el script al documento
        document.head.appendChild(script);

        // Intentamos cargar un pixel de seguimiento de Twitter
        var img = new Image();
        img.src =
            'https://analytics.twitter.com/i/adsct?txn_id=YOUR_TXN_ID&cust_params=&t=tracking_pixel'; // Pixel de seguimiento de Twitter
        img.onload = function() {
            // Si no se detecta un bloqueador de trackers, no hacemos nada para trackers
        };
        img.onerror = function() {
            // Si el pixel no se carga (probablemente por un bloqueador de trackers), detectamos trackers
            trackersDetected = true;
            document.getElementById('adtrackers').classList.remove('hidden');
        };

        // Agregamos el pixel al documento
        document.head.appendChild(img);

        // Esperamos a que ambos scripts terminen para decidir qué mostrar
        setTimeout(function() {
            if (adblockDetected && trackersDetected) {
                // Si se detectan ambos, mostramos el div de ambos bloqueadores
                document.getElementById('AdblokAdtacker').classList.remove('hidden');
            }
        }, 2000); // Esperamos 2 segundos para asegurar que ambos recursos se hayan intentado cargar

    </script>
    

{{-- 

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div id="message"></div>

    <script>
        // Intentamos cargar un script que podría ser bloqueado por adblockers
        var script = document.createElement('script');
        script.src =
            'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'; // Cambia esta URL a una fuente que pueda ser bloqueada por adblockers
        script.onload = function() {
            // Si el script se carga correctamente, mostramos un mensaje de éxito
            document.getElementById('adblock').classList.add('hidden');
        };
        script.onerror = function() {
            // Si el script no se carga (probablemente por un adblocker), mostramos el mensaje de detección
            document.getElementById('adblock').classList.remove('hidden');
            
        };

        // Agregamos el script al documento
        document.head.appendChild(script);
    </script>

    <div id="trackerMessage"></div>

    <script>
        // Intentamos cargar un pixel de seguimiento de Twitter
        var img = new Image();
        img.src =
            'https://analytics.twitter.com/i/adsct?txn_id=YOUR_TXN_ID&cust_params=&t=tracking_pixel'; // Pixel de seguimiento de Twitter
        img.onload = function() {
            // Si no se detecta un bloqueador de trackers, no hacemos nada (el div permanecerá oculto)
            document.getElementById('adtrackers').classList.add('hidden');
            document.getElementById('adblock').classList.add('hidden');

        };

        img.onerror = function() {
            // Si se detecta un bloqueador de trackers, mostramos el mensaje de advertencia
            document.getElementById('adtrackers').classList.remove('hidden');
            document.getElementById('adblock').classList.add('hidden');

        };
    </script> --}}

</div>
