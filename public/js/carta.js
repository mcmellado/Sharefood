
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

document.addEventListener('DOMContentLoaded', function () {
    var direccionInput = document.getElementById('direccion');
    var direccionesGuardadas = document.getElementById('direccionesGuardadas');

    direccionInput.addEventListener('focus', function () {
        mostrarSugerencias();
    });

    direccionInput.addEventListener('blur', function () {
        setTimeout(function () {
            direccionesGuardadas.style.display = 'none';
        }, 200); 
    });

    function mostrarSugerencias() {
        var direcciones = obtenerDireccionesGuardadas();
        actualizarSugerencias(direcciones);
    }

    function obtenerDireccionesGuardadas() {
        var direcciones = [];
        var direccionCookie = getCookie("direccion_entrega");
        if (direccionCookie) {
            direcciones.push(decodeURIComponent(direccionCookie));
        }

        return direcciones;
    }

    function actualizarSugerencias(direcciones) {
        direccionesGuardadas.innerHTML = '';

        direcciones.forEach(function (direccion) {
            var li = document.createElement('li');
            li.className = 'list-group-item';
            li.innerText = direccion;

            li.addEventListener('click', function () {
                direccionInput.value = direccion;
                direccionesGuardadas.style.display = 'none';
            });

            direccionesGuardadas.appendChild(li);
        });

        if (direcciones.length > 0) {
            direccionesGuardadas.style.display = 'block';
        }
    }
});

function submitForm() {
    var direccion = document.getElementById('direccion').value;
    if (direccion.trim() !== '') {
        setCookie('direccion_entrega', encodeURIComponent(direccion), 30);
    }
    return true;
}

function toggleCantidadInput(event, productId) {
    var cantidadInput = event.target.parentNode.nextElementSibling;
    cantidadInput.style.display = event.target.checked ? 'block' : 'none';
    cantidadInput.disabled = !event.target.checked;
    cantidadInput.value = 1;
}

function validarCantidad(event) {
    if (parseFloat(event.target.value) === 0) {
        event.target.value = 1;
    }
}
