document.addEventListener('DOMContentLoaded', function () {
    const eliminarUsuarioForms = document.querySelectorAll('.eliminar-usuario-form');

    eliminarUsuarioForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Una vez eliminado, no podrás recuperar este usuario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
