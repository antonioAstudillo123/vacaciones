
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
                mensajeAlert('Buen trabajo!' , 'Las vacaciones fueron solicitadas con éxito!' , 'success')

            },
            error: function(error)
            {
                console.log(error);
            }
        });
}
