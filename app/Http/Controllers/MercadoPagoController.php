<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Compra;
use Illuminate\Http\Request;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Exception;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MercadoPagoController extends Controller

{

    public function createPaymentPreference(Request $request)
    {
        Log::info('Creando preferencia de pago');
        $this->authenticate();
        Log::info('Autenticado con éxito');

        // Paso 1: Obtener la información del producto desde la solicitud JSON
        $product = $request->input('product'); // Asumiendo que envías un campo 'product' con los datos

        if (empty($product) || !is_array($product)) {
            return response()->json(['error' => 'Los datos del producto son requeridos.'], 400);
        }

        // Paso 2: Información del comprador (esto puedes obtenerlo desde el usuario autenticado) 
        $payer = [
            "name" => $request->input('name', 'John'), // Puedes obtener el nombre del request o usar un valor predeterminado
            "surname" => $request->input('surname', 'Doe'),
            "email" => $request->input('email', 'user@example.com'),
        ];

        // Paso 3: Crear la solicitud de preferencia 
        $requestData = $this->createPreferenceRequest($product, $payer);

        // Paso 4: Crear la preferencia con el cliente de preferencia 
        $client = new PreferenceClient();

        try {
            $preference = $client->create($requestData);

            return response()->json([
                'id' => $preference->id,
                'init_point' => $preference->init_point,
            ]);
        } catch (MPApiException $error) {
            return response()->json([
                'error' => $error->getApiResponse()->getContent(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Autenticación con Mercado Pago 
    protected function authenticate()
    {
        $mpAccessToken = config('services.mercadopago.token');
        if (!$mpAccessToken) {
            throw new Exception("El token de acceso de Mercado Pago no está configurado.");
        }
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }


    // Función para crear la estructura de preferencia 
    protected function createPreferenceRequest($items, $payer): array
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1

        ];

        $backUrls = [
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failed')
        ];

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "TIENDA ONLINE",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];
        return $request;
    }
    public $nombre_ape, $direccion, $nom_articulo, $cantidad, $total, $id_articulo, $costo, $imagen, $id_usuario;

    public function paymentSuccess(Request $request)
    {
        // Obtener los parámetros de la URL
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $preferenceId = $request->query('preference_id');

        $data = [];
        // Verificar si existe el archivo temporal y leer sus datos
        if (Storage::exists('temp_compra.json')) {
            $data = json_decode(Storage::get('temp_compra.json'), true) ?? [];

            if (!empty($data)) {
                // Obtener el artículo correspondiente al ID
                $articulo = Articulo::find($data['id_articulo']);

                if ($articulo) {
                    // Verificar si hay suficiente stock antes de proceder (por si hubo otra compra antes)
                    if ($articulo->cantidad >= $data['cantidad']) {
                        // Calcular la nueva cantidad y actualizar en la base de datos
                        $nueva_cantidad = $articulo->cantidad - $data['cantidad'];
                        $nuevo_total = $articulo->costo * $nueva_cantidad;

                        $articulo->update([
                            'cantidad' => $nueva_cantidad,
                            'total' => $nuevo_total,
                        ]);

                        // Guardar la compra en la base de datos
                        Compra::create([
                            'id_usuario' => $data['id_usuario'],
                            'nombre_ape' => $data['nombre_ape'],
                            'direccion' => $data['direccion'],
                            'nom_articulo' => $data['nom_articulo'],
                            'cantidad' => $data['cantidad'],
                            'costo' => $data['costo'],
                            'total' => $data['total'],
                            'imagen' => $data['imagen'],
                            'id_articulo' => $data['id_articulo'],
                        ]);

                        // Eliminar el archivo temporal después de procesar la compra
                        Storage::delete('temp_compra.json');
                    } else {
                        Log::warning('Stock insuficiente en paymentSuccess.');
                        return redirect()->route('pago.fallido')->with('error', 'Stock insuficiente');
                    }
                }
            }
        } else {
            Log::warning('Archivo temp_compra.json no encontrado.');
        }

        // Pasar los datos a la vista de éxito
        return view('mercadopago.success', [
            'paymentId' => $paymentId,
            'status' => $status,
            'preferenceId' => $preferenceId,
            'compra' => $data ?? [],
        ]);
    }



    public function paymentFailed(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $preferenceId = $request->query('preference_id');

        return view('mercadopago.failed', [
            'paymentId' => $paymentId,
            'status' => $status,
            'preferenceId' => $preferenceId,
        ]);
    }
}
