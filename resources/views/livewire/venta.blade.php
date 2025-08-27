<div class="">
    <div class="">
        <div class="">
            <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen del artículo">
        </div>
        <div class="">
            <h1 class="product-name">{{ $nom_articulo }}</h1>
            <div class="product-price" id="product-price">{{ $costo }}</div>
            <div>{{ $total }}</div>

            <hr />

            <form action="#" method="POST" class="order-form">
                @csrf
                <input type="hidden" id="product_id" value="{{ $id_articulo }}" />
                <input type="hidden" id="quantity" name="quantity" value="{{ $cantidad }}" />

                <input type="hidden" id="product_price" name="product_price" value="{{ $costo }}" />
                <input type="hidden" id="nombre" name="nombre" value="{{ $nombre_ape }}" />

                <button class="btn-submit" id="checkout-btn" type="button">
                    <span class="icon-credit-card text-center">💳</span>Pagar
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago("{{ env('MERCADO_PAGO_PUBLIC_KEY') }}");
    console.log("Nombre y Apellido: ", nombre);
    document.getElementById('checkout-btn').addEventListener('click', function() {
        const cantidad = parseInt(document.getElementById('quantity').value);
        const nombre = document.getElementById('nombre').value;
        // const telefono = document.getElementById('phone').value;
        // const direccion = document.getElementById('address').value;

        const orderData = {
            product: [{
                id: document.getElementById('product_id').value,
                title: document.querySelector('.product-name').innerText,
                description: 'Descripción del producto', // Puedes ajustar esto si tienes más información
                currency_id: "USD",
                quantity: parseFloat(document.getElementById('quantity').value),
                unit_price: parseFloat(document.getElementById('product_price').value),
            }],
            name: nombre,
            surname: nombre, // Si tienes un campo de apellido, añádelo aquí
            email: 'user@example.com', // Agrega el correo electrónico si es necesario
            phone: '987654321',
            address: 'user@example.com',
        };

        console.log('Datos del pedido:', orderData);

        fetch('/create-preference', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(orderData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(preference => {
                if (preference.error) {
                    throw new Error(preference.error);
                }
                mp.checkout({
                    preference: {
                        id: preference.id
                    },
                    autoOpen: true
                });
                console.log('Respuesta de la preferencia:', preference);
            })
            .catch(error => console.error('Error al crear la preferencia:', error));
    });
</script>

</html>
