import {peticionActualizacionEstatus} from '../../ajax.js';
import { mensajeAlert } from "../../auxiliares.js";

window.onload = main;

function main()
{
    //Activamos eventos en el DOM
    eventos();

    const tabla = datatable();

    //Le añadimos eventos al datatable
    obtener_data("#tablaSolicitudes" , tabla);
}

function datatable()
{
   return new DataTable('#tablaSolicitudes', {
        processing: false,
        serverSide: true,
        deferRender: true,
        ajax: {
            url: '/colaboradores/getSolicitudes',
            data: function (d) {
                d.start = d.start || 0; // Agrega el valor start con un valor predeterminado de 0 si no está definido
                d.length = d.length || 10;
            },
            dataSrc: "data",
        },
        ordering: false,
        searching: true,
        responsive: true,
        columns:
        [
            { data: "id" },
            { data: "colaborador" },
            { data: "fecha" },
            { data: "dias" },
            {data:'estatus'},
            {
                defaultContent: `
                    <div class='container d-flex justify-content-center'>
                        <button class='fechas btn btn-secondary btn-sm mr-2' data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Visualizar fechas">
                            <i class="fas fa-calendar"></i>
                        </button>
                        <button class='aprobaciones btn btn-success btn-sm mr-2' data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Aprobar solicitud">
                            <i class="fas fa-thumbs-up"></i>
                        </button>
                        <button class='cancelaciones btn btn-danger btn-sm mr-2' data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Rechazar solicitud">
                            <i class="fas fa-thumbs-down"></i>
                        </button>
                    </div> `,
            },
        ],
        "createdRow": function( row, data, dataIndex , cells ) {

            switch(data.estatus)
            {
                case 'Pendiente':
                    $(cells[4]).addClass('bg-primary text-center font-weight-bold');
                break;
                case 'Aprobada':
                    $(cells[4]).addClass('bg-success text-center font-weight-bold');
                break;
                case 'Cancelada':
                    $(cells[4]).addClass('bg-danger text-center font-weight-bold');
                break;
                default:
                    $(cells[4]).addClass('bg-danger text-center font-weight-bold');
                break;
            }
        },
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


function llenarTablaSolicitudEmpleado(data)
{
    document.getElementById('colaboradorModal').textContent = data.colaborador;
    const dataUser = {
        id:data.id
    }

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
    });

    $.ajax({
        type: 'POST',
        url: '/colaboradores/getSolicitudUser',
        data: dataUser,
        beforeSend: function (xhr) {
            // Agregar cabeceras de seguridad
            xhr.setRequestHeader("X-Content-Type-Options", "nosniff");
            xhr.setRequestHeader("X-Frame-Options", "DENY");
            xhr.setRequestHeader(
                "Content-Security-Policy",
                "default-src 'self'"
            );
        },
        success: function(response)
        {
            const {data} = response;
            let html = '';

            data.forEach(element => {
               html += `    <tr>
               <th>${element.id}</th>
               <td>${element.fecha}</td>
               <th>${obtenerDia(element.dia)}</th>
             </tr>`
            });

            document.getElementById('bodyTablaModal').innerHTML = html;
        },

        error: function(error)
        {
            console.log(error);
            //mensajeAlert('error' , 'Ups!' , 'No pudimos actualizar el registro. Contacta a sistemas');
        }
    });
}


function obtenerDia(data)
{
    let dia = new Date(data);
    // Días de la semana en formato texto
    let diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

    // Meses del año en formato texto
    let meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    let diaSemana = diasSemana[dia.getDay()];
    let diaMes = dia.getDate();
    let mes = meses[dia.getMonth()];
    let año = dia.getFullYear();

    // Construir la cadena de fecha formateada
    let fechaFormateada = diaSemana + ' ' + diaMes + ' de ' + mes + ' del ' + año;

    return fechaFormateada;
}


// Con este metodo vamos a registrar todos los eventos en el DOM
//Una vez que el DOM este cargado
function eventos()
{
    //Evento para cerrar modal de confirmacion de aprobacion de solicitud
    document.getElementById('btnCerrarModalSolicitud').addEventListener('click' , function(){
        cerrarModalConfirmacion('#modalAprobarSolicitud');
    });


    //Evento para actualizar estatus de solicitud
    document.getElementById('btnAprobarSolicitud').addEventListener('click' , function(e)
    {
        const data = {
            id: e.target.value
        }
        actualizarEstatus(data , '/colaboradores/aprobarSolicitud' , '#modalAprobarSolicitud');
    });


    //Evento para cerrar modal de rechazo de solicitud
    document.getElementById('btnCerrarModalRechazo').addEventListener('click' , function(){
        cerrarModalConfirmacion('#modalRechazarSolicitud');
    });

    //Evento para cambiar estado de solicitud a rechazada
    document.getElementById('btnRechazoSolicitud').addEventListener('click' , function(){


        let motivo = document.getElementById('motivoRechazoText').value;
        let idRechazo = document.getElementById('idSolicitudRechazo').value;

        const data = {
            id: idRechazo,
            motivo:motivo
        }


        if(motivo === '')
        {
            mensajeAlert('Error' , 'Debe ingresar el motivo por el cual rechaza la solicitud!' , 'error');
        }else{
            //hacemos peticion al servidor
            actualizarEstatus(data , '/colaboradores/rechazarSolicitud' , '#modalRechazarSolicitud');
        }
    });
}



function cerrarModalConfirmacion(id)
{
    $(id).modal('hide');
}

function actualizarEstatus(id , url , modalId)
{
    cerrarModalConfirmacion(modalId);
    peticionActualizacionEstatus(id , url);
}
