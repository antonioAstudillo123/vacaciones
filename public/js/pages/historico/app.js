import { mensajeAlert } from "../../auxiliares.js";
import { peticionReportesPDF , peticionReportesExcel } from "../../ajax.js";

window.onload = main;

function main()
{
    //Activamos eventos en el DOM
    eventos();

    const tabla = datatable();

    //Le añadimos eventos al datatable
    obtener_data("#tablaHistorico" , tabla);
}

function datatable()
{
   return new DataTable('#tablaHistorico', {
        ajax: {
            url: '/rh/all',
            dataSrc: "data",
        },
        ordering: false,
        searching: true,
        responsive: true,
        columns:
        [
            { data: "colaborador" },
            { data: "numeroEmpleado" },
            { data: "fechaIngreso" },
            { data: "diasTomados" },
            {data:'diasRestante'},
            {
                defaultContent: `
                    <div class='container d-flex justify-content-center'>
                        <button class='fechas btn btn-info btn-sm mr-2' data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Visualizar fechas">
                            <i class="fa-solid fa-file-lines"></i>
                        </button>
                    </div> `,
            },
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay solicitudes",
            "info": "Mostrando _TOTAL_ solicitudes",
            "infoEmpty": "Mostrando 0 to 0 of 0 Solicitudes",
            "infoFiltered": "(Filtrado de _MAX_ total solicitudes)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "",
            'searchPlaceholder': "Buscar...",
            "zeroRecords": "Sin solicitudes que mostrar",
            "paginate": {
                "first": "<<",
                "last": ">>",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "createdRow": function( row, data, dataIndex , cells ) {

            if(data.diasRestante <= 0)
            {
                $(cells[4]).addClass('text-danger');
            }else{
                $(cells[4]).addClass('text-success');
            }
        },
    });
}

function obtener_data(tbody, tabla)
{
    let data = [];

    $(tbody).on("click", "button.fechas", function () {
        data = tabla.row($(this).closest("tr")).data();
        $('#showFechasModal').modal('show');
        console.log(data);
        llenarTablaSolicitudEmpleado(data);
    });


    $(tbody).on("click", "button.cancelaciones", function () {
        data = tabla.row($(this).closest("tr")).data();

        if(data.estatus === 'Aprobada')
        {
            mensajeAlert('Error' , 'Esta solicitud ya fue aprobada, no puedes cancelarla!' , 'error');
        }else if(data.estatus === 'Rechazada'){
            mensajeAlert('Error' , 'Esta solicitud ya fue rechazada!' , 'error');
        }
        else{
            $('#modalRechazarSolicitud').modal('show');
            document.getElementById('idSolicitudRechazo').value = data.id;
            document.getElementById('colaboradorInputModal').value = data.colaborador;
        }
    });


    $(tbody).on("click", "button.aprobaciones", function () {
        data = tabla.row($(this).closest("tr")).data();

        if(data.estatus === 'Aprobada')
        {
            mensajeAlert('Error' , 'Esta solicitud ya fue aprobada!' , 'error');
        }else if(data.estatus === 'Rechazada'){
            mensajeAlert('Error' , 'Esta solicitud fue rechazada, no se puede aprobar!' , 'error');
        }
        else{
            $('#modalAprobarSolicitud').modal('show');
            document.getElementById('btnAprobarSolicitud').value = data.id;
        }

    });
}








// Con este metodo vamos a registrar todos los eventos en el DOM
//Una vez que el DOM este cargado
function eventos()
{
        //Agregamos el evento click al a del button de reportes de usuarios excel  btnExcelReport
        document
        .getElementById("btnExcelReport")
        .addEventListener("click", function () {
            peticionReportesExcel('/rh/all' , 'GET');
        });


        document
        .getElementById("btnPdfReport")
        .addEventListener("click", function () {

            const headers = [
                "id",
                "Número empleado",
                "Colaborador",
                "Fecha ingreso",
                "Días tomados",
                "Días restantes",
            ];


            peticionReportesPDF('/rh/all' , 'GET' , headers , 'Resumen vacaciones' , 'resumen');

        });
}




