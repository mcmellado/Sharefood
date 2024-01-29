
    function validarFormulario() {
        var precioRegex = /^\d+(\.\d{1,2})?$/;

        var precioInput = document.getElementsByName('precio')[0];
        var precioHelp = document.getElementById('precioHelp');

        if (!precioRegex.test(precioInput.value)) {
            precioHelp.innerHTML = 'Formato de precio no válido. Utiliza números con hasta dos decimales.';
            precioHelp.style.color = 'red';
            return false;
        } else {
            precioHelp.innerHTML = 'Formato válido: 123.45';
            precioHelp.style.color = 'black';
            return true;
        }
    }
