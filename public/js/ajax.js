import { crearExcelDetails } from "./excel.js";
import { generarPdfDetails } from "./pdf.js";
import { mensajeAlert } from "./auxiliares.js";


export async function peticionAsincrona(url , tipo , data = null)
{
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
    });

    let response = $.ajax({
            type: tipo,
            url: url,
            data: data,
            beforeSend: function (xhr) {
                // Agregar cabeceras de seguridad
                xhr.setRequestHeader("X-Content-Type-Options", "nosniff");
                xhr.setRequestHeader("X-Frame-Options", "DENY");
                xhr.setRequestHeader(
                    "Content-Security-Policy",
                    "default-src 'self'"
                );
            }
        });

    return response;
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

export function peticionChangeRole(url , type , data)
{
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
                Swal.fire({
                    title: 'Buen trabajo!',
                    text: response,
                    icon: 'success'
                  }).then(()=>{
                    $("#tablaUsuarios").DataTable().ajax.reload();
                    $('#permisoUserModal').modal('hide');
                  });
            },
            error: function(error)
            {
                mensajeAlert('¡No pudimos procesar la solicitud!' , error.responseText , 'error');
            }
        });
}

