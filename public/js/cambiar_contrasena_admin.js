function validarContrasena(event) {
    var contrasena = document.getElementById('password').value;
    var confirmarContrasena = document.getElementsByName('password_confirmation')[0].value;

    if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/.test(contrasena)) {
        if (contrasena.length < 6) {
            document.getElementById('errorPasswordLength').style.display = 'block';
            document.getElementById('errorPasswordPattern').style.display = 'none';
        } else {
            document.getElementById('errorPasswordLength').style.display = 'none';
            document.getElementById('errorPasswordPattern').style.display = 'block';
        }
        event.preventDefault();
        return false;
    } else {
        document.getElementById('errorPasswordLength').style.display = 'none';
        document.getElementById('errorPasswordPattern').style.display = 'none';
    }

    if (contrasena !== confirmarContrasena) {
        alert('Las contraseñas no coinciden.');
        event.preventDefault();
        return false;
    }
    return true;
}
