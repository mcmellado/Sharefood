function validarRestaurante() {
    var telefonoInput = document.getElementById('telefono');
    
    if (telefonoInput.value !== '' && !/^\d+$/.test(telefonoInput.value)) {
        alert('Por favor, ingrese un número de teléfono válido.');
        return false;
    }

    return true;
}
