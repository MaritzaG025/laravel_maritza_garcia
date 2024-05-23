$(document).ready(function() {
    $('#formularioEmpresas').submit(function(event) {
        // Evitar que el formulario se envíe normalmente
        event.preventDefault();

        // Obtener la URL del formulario
        var url = $(this).attr('action');

        // Obtener los datos del formulario
        var formData = $(this).serialize();

        // Enviar la solicitud AJAX
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                if (response && response.successMessage) {
                    $('#mensajeTexto').text(response.successMessage);
                    $('#mensajeModal').modal('show');
                    $('#modalBtn').click(function() {
                        window.location.href = "{{ route('empresas.index') }}";
                    });
                }

                if (response && response.errorMessage) {
                    $('#mensajeTexto').text(response.errorMessage);
                    $('#mensajeModal').modal('show');
                    $('#modalBtn').click(function() {
                        $('#mensajeModal').modal('hide');
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#mensajeTexto').text(xhr
                    .responseText); // Puedes mostrar el mensaje de error de otra manera
                $('#mensajeModal').modal('show');
                $('#modalBtn').click(function() {
                    $('#mensajeModal').modal('hide');
                });
            }
        });
    });
});
