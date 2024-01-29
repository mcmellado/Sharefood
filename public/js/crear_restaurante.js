
    document.addEventListener("DOMContentLoaded", function () {
        const selectsEstado = document.querySelectorAll('[name^="estado_"]');

        selectsEstado.forEach(selectEstado => {
            const inputApertura = selectEstado.parentElement.querySelector('[name^="hora_apertura"]');
            const inputCierre = selectEstado.parentElement.querySelector('[name^="hora_cierre"]');

            selectEstado.addEventListener("change", function () {
                const cerrado = this.value === 'cerrado';
                inputApertura.disabled = cerrado;
                inputCierre.disabled = cerrado;
            });

            if (selectEstado.value === 'cerrado') {
                inputApertura.disabled = true;
                inputCierre.disabled = true;
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-agregar-horario').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const dia = this.getAttribute('data-dia');
                const container = this.closest('.form-group').querySelector('.horarios-container');
                const nuevoHorario = container.querySelector('.horario').cloneNode(true);
                container.appendChild(nuevoHorario);
            });
        });
    });

