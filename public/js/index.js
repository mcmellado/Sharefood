    var desplegable = document.getElementById('sugerencias-desplegable');
    var inputBuscar = document.getElementById('buscar-input');

    inputBuscar.addEventListener('input', function () {
        var query = this.value;

        if (query.length >= 3) {
            fetch(`/restaurantes/buscar-sugerencias?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    actualizarDesplegableSugerencias(data);
                })
                .catch(error => {
                    console.error('Error al obtener sugerencias:', error);
                });
        } else {
            desplegable.innerHTML = ''; 
        }
    });

    function actualizarDesplegableSugerencias(sugerencias) {
        desplegable.innerHTML = '';

        if (sugerencias.length === 0) {
            desplegable.style.display = 'none';
            return;
        }

        sugerencias.forEach(function (sugerencia) {
            var sugerenciaItem = document.createElement('div');
            sugerenciaItem.className = 'sugerencia-item';

            // Crear un enlace al perfil del restaurante
            var enlacePerfil = document.createElement('a');
            enlacePerfil.href = `/restaurantes/${sugerencia.slug}`;
            enlacePerfil.textContent = sugerencia.nombre;

            sugerenciaItem.appendChild(enlacePerfil);
            desplegable.appendChild(sugerenciaItem);
        });

        desplegable.style.display = 'block';
    }

    document.addEventListener('click', function (event) {
        if (!desplegable.contains(event.target)) {
            desplegable.style.display = 'none';
        }
    });

    $(document).ready(function(){
        setTimeout(function(){
            $("#notificacion").alert('close');
        }, 5000);
    });

  
