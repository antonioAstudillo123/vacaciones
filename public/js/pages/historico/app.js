
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
        ordering:false,
        responsive: true,

        columns:
        [
            { data: "numeroEmpleado" },
            { data: "colaborador" },
            {data:'puestoNombre'},
            { data: "fechaIngreso" },
            { data: "diasTomados" },
            {data:'diasRestante'},
            {
                defaultContent: `
                    <div class='container d-flex justify-content-center'>
                        <button class='reporte btn btn-info btn-sm mr-2' data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Visualizar fechas">
                            <i class="fa-solid fa-file-lines"></i>
                        </button>
                    </div> `,
            },
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay colaboradores",
            "info": "Mostrando _TOTAL_ colaboradores",
            "infoEmpty": "Mostrando 0 to 0 of 0 colaboradores",
            "infoFiltered": "(Filtrando de _MAX_ total colaboradores)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "",
            'searchPlaceholder': "Buscar...",
            "zeroRecords": "Sin colaboradores que mostrar",
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
                $(cells[5]).addClass('text-danger');
            }else{
                $(cells[5]).addClass('text-success');
            }
        },
    });
}

function obtener_data(tbody, tabla)
{
    let data = [];

    $(tbody).on("click", "button.reporte", function () {
        data = tabla.row($(this).closest("tr")).data();
        $('#showFechasModal').modal('show');

        const dataUser = {
            id: data.id
        }

        const headers = [
            "Empleado",
            "Nombre",
            "Fecha ingreso",
            "Puesto",
            "Días tomados",
            "Días restantes",
            "Año"
        ];

        peticionReportesPDF('/rh/reporteEmpleado' , 'GET' , headers , 'Resumen vacaciones' , 'resumen' , dataUser);
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
                "Id",
                "Empleado",
                "Nombre",
                "Fecha ingreso",
                "Puesto",
                "Días tomados",
                "Días restantes",
                "Año"
            ];


            peticionReportesPDF('/rh/all' , 'GET' , headers , 'Resumen vacaciones' , 'resumen');

        });
}




