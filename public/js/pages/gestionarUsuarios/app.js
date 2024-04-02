import {peticionUpdateUser , peticionEliminarUser , peticionAddUser , peticionChangeRole} from '../../ajax.js';
import { mensajeAlert } from "../../auxiliares.js";
window.onload = main;

function main()
{
    //Activamos eventos en el DOM
   eventos();

    const tabla = datatable();

    //Le añadimos eventos al datatable
    obtener_data("#tablaUsuarios" , tabla);
}

function datatable()
{
   return new DataTable('#tablaUsuarios', {
        processing: false,
        serverSide: true,
        deferRender: true,
        ajax: {
            url: '/sistemas/all',
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
            { data: "name" },
            { data: "email" },
            { data: "puesto" },
            {
                defaultContent: `
                    <div class='container d-flex justify-content-center'>
                    <button class='permisos btn btn-info btn-sm mr-2' data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Modificar roles">
                        <i class="fas fa-user-cog"></i>
                    </button>
                        <button class='editar btn btn-warning btn-sm mr-2' data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Editar información">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class='eliminar btn btn-danger btn-sm mr-2' data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Eliminar usuario">
                            <i class="fa-solid fa-user-minus"></i>
                        </button>
                    </div> `,
            },
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay usuarios",
            "info": "Mostrando _TOTAL_ usuarios",
            "infoEmpty": "Mostrando 0 to 0 of 0 usuarios",
            "infoFiltered": "(Filtrado de _MAX_ total usuarios)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "",
            'searchPlaceholder': "Buscar...",
            "zeroRecords": "Sin usuarios que mostrar",
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


    //Evento para modificar
    $(tbody).on("click", "button.editar", function () {
        data = tabla.row($(this).closest("tr")).data();
        $('#editUserModal').modal('show');
        llenarModalUser(data);
    });


    //Evento para eliminar
    $(tbody).on("click", "button.eliminar", function () {
        data = tabla.row($(this).closest("tr")).data();
        $('#modalConfirmDeleteUser').modal('show');
        document.getElementById('btnAprobarSolicitud').value = data.id;

    });


    //Evento de permisos
    $(tbody).on("click", "button.permisos", function () {
        data = tabla.row($(this).closest("tr")).data();
        $('#permisoUserModal').modal('show');
        permisosUserModal(data);


    });
}

// Con este metodo vamos a registrar todos los eventos en el DOM
//Una vez que el DOM este cargado
function eventos()
{
    //cerramos modal de show user
    document.getElementById('btnCerrarModalSolicitud').addEventListener('click' , function(){
        cerrarModalConfirmacion('#editUserModal');
    });

    //evento de btn de edit user
    document.getElementById('btnEditUser').addEventListener('click' , editUser);


    //Cerramos modal de confirmacion delete
    document.getElementById('btnCerrarModalConfirmDelete').addEventListener('click' , function(){
        cerrarModalConfirmacion('#modalConfirmDeleteUser');
    });


    //Se aprueba la solicitud de eliminar el usuario
    document.getElementById('btnAprobarSolicitud').addEventListener('click' , function(e){
        eliminarUsuario(e.target.value);
    });


    //Evento del boton para agregar usuario
    document.getElementById('btnAddUser').addEventListener('click' , function(){
        $("#addUserModal").modal('show');
    });


    //Evento para cerrar modal de add user
    document.getElementById('btnCerrarAddUser').addEventListener('click' , function(){
        cerrarModalConfirmacion("#addUserModal");
    });


    //Evento para guardar usuario en el sistema
    document.getElementById('btnSaveUser').addEventListener('click' , saveUser);


    //Le agregamos evento al boton de guardar cambios en permisos
    document.getElementById('btnAddRolePermiso').addEventListener('click' , saveRoleUser);
}




function cerrarModalConfirmacion(id)
{
    $(id).modal('hide');
}

function llenarModalUser(data)
{
    document.getElementById('userName').value = data.name;
    document.getElementById('emailUser').value = data.email;
    document.getElementById('idUser').value = data.id;
}


function editUser()
{
    let name = document.getElementById('userName').value;
    let email = document.getElementById('emailUser').value;
    let password = document.getElementById('passwordUser').value;
    let id = document.getElementById('idUser').value;

    const data = {
        name:name,
        email:email,
        password:password,
        id:id
    }

    peticionUpdateUser('/sistemas/update' , 'POST' , data);
}


function eliminarUsuario(id)
{
    const data = {
        id:id
    }
    peticionEliminarUser('/sistemas/delete' , 'POST' , data);
}


function saveUser()
{
    let user = document.getElementById('userNameAdd').value;
    let email = document.getElementById('emailUserAdd').value;
    let password = document.getElementById('passwordAdd').value;
    let colaborador = document.getElementById('colaboradorUserAdd').value;
    let role = document.getElementById('roleUserAddSelect').value;


    if(user === '')
    {
        mensajeAlert('Error!' , 'Debe ingresar un usuario!' , 'error');
    }else if(email === '')
    {
        mensajeAlert('Error!' , 'Debe ingresar un email!' , 'error');
    }else if(password === '')
    {
        mensajeAlert('Error!' , 'Debe ingresar un password!' , 'error');
    }else if(colaborador === '')
    {
        mensajeAlert('Error!' , 'Debe seleccionar un colaborador!' , 'error');
    }else if(role === '')
    {
        mensajeAlert('Error!' , 'Debe seleccionar el perfil que va tener este colaborador!' , 'error');
    }
    else{

        const data = {
            email:email,
            user:user,
            password:password,
            colabordor:colaborador,
            role:role,
        }

        peticionAddUser('/sistemas/create' , 'POST' , data);
    }
}


function permisosUserModal(data)
{
    document.getElementById('nameUserPermisos').value = data.name;
    document.getElementById('emailUserPermisos').value = data.email;
}


function saveRoleUser()
{

    if(document.getElementById('perfilesUserPermisos').value === '')
    {
        mensajeAlert('Error!' , 'Debe seleccionar un perfil.' , 'error');
    }else{
        let email = document.getElementById('emailUserPermisos').value;
        let role = document.getElementById('perfilesUserPermisos').value;

        const data = {
            email : email ,
            role:role
        }

        peticionChangeRole('/sistemas/permisos/change' , 'POST' , data);
    }

}
