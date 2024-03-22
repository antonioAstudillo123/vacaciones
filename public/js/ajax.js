
import { mensajeAlert } from "./auxiliares.js";


export function peticionSolicitudVacaciones(url , tipo , data)
{
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
    });

    $.ajax({
            type: tipo,
            url: url,
            data: data,
            success: function(response)
            {
                console.log(response);
                Swal.fire({
                    title: 'Buen trabajo!',
                    text: 'Las vacaciones fueron solicitadas con éxito!',
                    icon: 'success'
                  }).then(()=>{
                    location.reload();
                  });

            },
            error: function(error)
            {

                mensajeAlert('¡No pudimos procesar la solicitud!' , error.responseText , 'error');
                document.getElementById('btnSolicitar').classList.remove('d-none');

                //Mostraos boton de spinner
                document.getElementById('btnSolicitarSpinner').classList.add('d-none');

                //Deshabilitamos el draggable
                document.querySelector('.external-event').classList.remove('disabled');

            }
        });
}
