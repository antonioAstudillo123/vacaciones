import { crearExcelDetails } from "./excel.js";
import { generarPdfDetails } from "./pdf.js";
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


//En este metodo voy a actualizar el estatus de una Solicitud a aprobada
export function peticionActualizacionEstatus(data , url)
{

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
    });

    $.ajax({
            type: 'POST',
            url: url,
            data:data,
            success: function(response)
            {
                Swal.fire({
                    title: 'Buen trabajo!',
                    text: response,
                    icon: 'success'
                  }).then(()=>{

                    if(document.getElementById('formRechazoSolicitud') !== null)
                    {
                        document.getElementById('formRechazoSolicitud').reset();
                    }
                    $("#tablaSolicitudes").DataTable().ajax.reload();
                  });

            },
            error: function(error)
            {
                mensajeAlert('¡No pudimos procesar la solicitud!' , error.responseText , 'error');
            }
        });
}



export function peticionReportesExcel(url , type , data = null){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
    });

    $.ajax({
            type: type,
            url: url,
            data:data,
            success: function(response)
            {
                crearExcelDetails(response.data, "resumen.xlsx");
            },
            error: function(error)
            {
                mensajeAlert('¡No pudimos procesar la solicitud!' , error.responseText , 'error');
            }
        });
}


export function peticionReportesPDF(url , type , encabezado , tituloReporte , nombreArchivo , data = null){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
    });

    $.ajax({
            type: type,
            url: url,
            data:data,
            success: function(response)
            {
                generarPdfDetails(
                    response.data,
                    encabezado,
                    tituloReporte,
                    nombreArchivo
                );
            },
            error: function(error)
            {
                mensajeAlert('¡No pudimos procesar la solicitud!' , error.responseText , 'error');
            }
        });
}
