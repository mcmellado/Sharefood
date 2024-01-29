function confirmarBorrado(slug) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Una vez borrado, no podrás recuperar este restaurante.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('restaurante.borrar', ['slug' => ':slug']) }}".replace(':slug', slug);
            form.style.display = 'none';

            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            form.appendChild(csrfToken);

            var methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);

            form.submit();
        }
    });
}

function mostrarMensajeReservas() {
    Swal.fire({
        title: 'No puedes borrar el restaurante',
        text: 'Tienes reservas pendientes para este restaurante.',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Entendido'
    });
}
