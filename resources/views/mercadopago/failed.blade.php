<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Fallido</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffe6e6; /* Fondo rojo claro */
            color: #333333; /* Texto oscuro */
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
            color: #e74c3c; /* Color rojo para indicar error */
        }

        p {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        a {
            font-size: 1rem;
            color: #007bff; /* Color azul para el enlace */
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

        .alert {
            background-color: #f8d7da; /* Fondo rojo suave */
            color: #721c24; /* Color de texto rojo oscuro */
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div>
        <h1>¡Pago Fallido!</h1>
        <div class="alert">
            <p>Hubo un error en el proceso de pago. Por favor, intente nuevamente.</p>
        </div>
        <p>ID de Pago: {{ $paymentId }}</p>
        <p>Estado del Pago: {{ $status }}</p>
        <p>ID de Preferencia: {{ $preferenceId }}</p>
        <a href="{{ url('/contenido3') }}">Volver a la tienda</a>
    </div>
</body>
</html>
