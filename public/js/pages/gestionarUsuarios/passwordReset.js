import { mensajeAlert } from "../../auxiliares.js";
import {peticionAsincrona} from '../../ajax.js';

window.onload = main;

function main()
{
    document.getElementById('updatePassword').addEventListener('click' , function(e){

        e.preventDefault();

        let password1 = document.getElementById('password1').value;
        let password2 = document.getElementById('password2').value;

        if(password1 === '')
        {
            mensajeAlert('Error!' , 'Debe ingresar una contraseña!' , 'error');
        }else if(password2 === '')
        {
            mensajeAlert('Error!' , 'Debe confirmar la contraseña!' , 'error');
        }else if(password1 !== password2)
        {
            mensajeAlert('Error!' , 'Las contraseñas no coinciden!' , 'error');
        }else{
            //Actualizamos la contraseña del usuario logueado

            const data = {
                password:password1,
            }

            let respuesta = peticionAsincrona('/colaboradores/password/reset' , 'POST', data);

            respuesta.then(function(resultado){
                Swal.fire({
                    title: 'Buen trabajo!',
                    text: resultado,
                    icon: 'success'
                  }).then(()=>{
                     //actualizamos el form de actualizacion de contraseña
                     document.getElementById('formUpdatePassword').reset();
                  });
            }).catch(function(error){
                mensajeAlert('¡No pudimos procesar la solicitud!' , error.responseText , 'error');
            });

        }
    });
}
