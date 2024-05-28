let carrito = [];

function addToCart(productId, productName, price) {
    const index = carrito.findIndex(item => item.id === productId);
    if (index > -1) {
        carrito[index].cantidad += 1;
    } else {
        carrito.push({
            id: productId,
            nombre: productName,
            cantidad: 1,
            precio: price
        });
    }
    actualizarCarrito();
}

function actualizarCarrito() {
    let subtotal = 0;
    $('#carrito').empty();
    $('#productosForm').empty();

    carrito.forEach(item => {
        const subtotalItem = item.cantidad * item.precio;
        subtotal += subtotalItem;

        $('#carrito').append(
            `<li>${item.nombre} - Cantidad: ${item.cantidad} - Subtotal: $${subtotalItem.toFixed(2)}</li>`
        );

        $('#productosForm').append(`
            <input type="hidden" name="productos[${item.id}][id]" value="${item.id}">
            <input type="hidden" name="productos[${item.id}][cantidad]" value="${item.cantidad}">
            <input type="hidden" name="productos[${item.id}][precio]" value="${item.precio}">
            <input type="hidden" name="productos[${item.id}][subtotal]" value="${subtotalItem}">
        `);
    });

    const iva = subtotal * 0.13;
    const total = subtotal + iva;
    const totalRedondeado = Math.ceil(total);

    $('#subtotal').text(`$${subtotal.toFixed(2)}`);
    $('#iva').text(`$${iva.toFixed(2)}`);
    $('#total').text(`$${totalRedondeado}`);
    $('#inputTotalCarrito').val(totalRedondeado);
}

function abrirCaja() {
    return fetch('/ventas/toggleCaja', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                abrir: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#inputCajaId').val(data.cajaId);
                $('#botonCaja').text('Cerrar Caja').removeClass('btn-primary').addClass('btn-warning');
                console.log('Caja abierta con ID:', data.cajaId);
                location.reload();
            } else {
                if (data.cajaId) {
                    $('#inputCajaId').val(data.cajaId);
                    $('#botonCaja').text('Cerrar Caja').removeClass('btn-primary').addClass('btn-warning');
                    console.warn('Usando caja existente con ID:', data.cajaId);
                }
                console.error('Error al abrir la caja:', data.message);
                throw new Error('Error al abrir la caja: ' + data.message);
            }
            return data.cajaId;
        })
        .catch(error => {
            console.error('Error al manejar la respuesta de la caja:', error);
            throw error;
        });
}

function toggleCaja() {
    const botonCaja = $('#toggleCajaBtn');
    const cajaId = $('#inputCajaId').val();

    if (botonCaja.hasClass('btn-warning')) {
        $('#modalConfirmacionCerrarCaja').modal('show');
    } else {
        $('#modalConfirmacionAbrirCaja').modal('show');
    }
}

function cerrarCaja() {
    const cajaId = $('#inputCajaId').val();
    if (!cajaId) {
        console.error('No hay un ID de caja para cerrar.');
        return Promise.reject('No hay un ID de caja para cerrar.');
    }

    const dineroEnCaja = $('#dineroEnCajaInput').val();
    const totalBilletesMonedas = $('#totalBilletesMonedas').val();
    const observaciones = $('#observaciones').val();
    const billetes = {};
    const monedas = {};

    $('#modalConfirmacionCerrarCaja input[name^="billetes"]').each(function() {
        const denominacion = $(this).data('value');
        const cantidad = parseInt($(this).val()) || 0;
        billetes[denominacion] = cantidad;
    });

    $('#modalConfirmacionCerrarCaja input[name^="monedas"]').each(function() {
        const denominacion = $(this).data('value');
        const cantidad = parseInt($(this).val()) || 0;
        monedas[denominacion] = cantidad;
    });

    return fetch('/ventas/cerrarCaja', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                caja_id: cajaId,
                dinero_en_caja: dineroEnCaja,
                total_billetes_monedas: totalBilletesMonedas,
                observaciones: observaciones,
                billetes: billetes,
                monedas: monedas
            })
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    console.log('Caja cerrada correctamente');
                    $('#modalConfirmacionCerrarCaja').modal('hide');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    console.error('Error al cerrar la caja:', data.message);
                    throw new Error(data.message);
                }
            } catch (error) {
                $('#errorModalBody').html(text);
                $('#errorModal').modal('show');
                throw new Error('La respuesta no es JSON. Mostrando el contenido HTML en el modal.');
            }
        })
        .catch(error => {
            console.error('Error al cerrar la caja:', error);
            throw error;
        });
}

$('#confirmarCerrarCaja').on('click', function() {
    cerrarCaja()
        .then(() => {
            $('#modalConfirmarCerrarCaja').modal('hide');
        })
        .catch(error => {
            console.error('Error al cerrar la caja:', error);
        });
});
    
$(document).ready(function() {
    $('#modalConfirmacionCerrarCaja').on('shown.bs.modal', function() {
        calcularTotalBilletesMonedas();
    });

    $('#modalConfirmacionCerrarCaja').find('input[type="number"]').on('input', calcularTotalBilletesMonedas);

    function calcularTotalBilletesMonedas() {
        var total = 0;
        $('#modalConfirmacionCerrarCaja input[type="number"]').each(function() {
            var valor = parseFloat($(this).data('value'));
            var cantidad = parseInt($(this).val()) || 0;
            total += valor * cantidad;
        });
        $('#totalBilletesMonedas').val(total.toFixed(2));
    }

    $('.clickable-card').click(function(event) {
        event.preventDefault();
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const price = parseFloat($(this).data('product-price'));

        if (!cajaAbierta) {
            $('#modalAbrirCajaPrimero').modal('show');
        } else {
            addToCart(productId, productName, price);
        }
    });

    $('#modalCancelar').on('show.bs.modal', function() {
        actualizarCarrito();
    });

    $('#modalPago').on('show.bs.modal', function() {
        const cajaId = $('#inputCajaId').val();
        if (!cajaId) {
            abrirCaja().then(cajaId => {
                $('#inputCajaId').val(cajaId);
            }).catch(error => {
                console.error(error);
            });
        }
    });

    const botonCaja = document.getElementById('botonCaja');
    if (botonCaja) {
        botonCaja.addEventListener('click', toggleCaja);
    }
});

