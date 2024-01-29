
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.eliminar-restaurante').forEach(function(el) {
        el.addEventListener('click', function() {
            var restauranteId = this.getAttribute('data-restaurante-id');
            var restauranteNombre = this.getAttribute('data-restaurante-nombre');

            Swal.fire({
                title: 'Confirmar eliminación',
                text: `¿Seguro que deseas eliminar el restaurante "${restauranteNombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
