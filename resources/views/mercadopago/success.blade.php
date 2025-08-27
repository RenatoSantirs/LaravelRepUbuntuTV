<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            /* Fondo claro */
            color: #333333;
            /* Texto oscuro pero legible */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            color: #4caf50;
            /* Color verde para indicar éxito */
        }

        p {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        a {
            font-size: 1rem;
            color: #007bff;
            /* Color azul para el enlace */
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #007bff;
        }

        a:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div>
        <h1>¡Pago Exitoso!</h1>
        <p>ID de Pago: {{ $paymentId }}</p>
        <p>Estado del Pago: {{ $status }}</p>
        <p>ID de Preferencia: {{ $preferenceId }}</p>
        <a href="{{ route('contenido3') }}" target="_blank">Volver a la tienda</a>


        @if (!empty($compra))
            <h2>Detalles de la Compra</h2>
            <p><strong>Usuario:</strong> {{ $compra['id_usuario'] }}</p>
            <p><strong>Nombre:</strong> {{ $compra['nombre_ape'] }}</p>
            <p><strong>Dirección:</strong> {{ $compra['direccion'] }}</p>
            <p><strong>Artículo:</strong> {{ $compra['nom_articulo'] }}</p>
            <p><strong>Cantidad:</strong> {{ $compra['cantidad'] }}</p>
            <p><strong>Costo Unitario:</strong> ${{ $compra['costo'] }}</p>
            <p><strong>Total:</strong> ${{ $compra['total'] }}</p>
            <p><strong>ID del Artículo:</strong> {{ $compra['id_articulo'] }}</p>
            <p>
                <strong>Imagen del Artículo:</strong><br>
                <img src="{{ asset('storage/' . $compra['imagen']) }}" alt="Artículo" style="max-width: 200px;">
            </p>
        @else
            <p>No se encontraron detalles de la compra.</p>
        @endif
    </div>

</body>

</html>
