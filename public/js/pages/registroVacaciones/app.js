import {peticionAsincrona} from '../../ajax.js';
import { mensajeAlert } from '../../auxiliares.js';


$(function () {

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;
    var containerEl = document.getElementById('external-events');
    var calendarEl = document.getElementById('calendar');

     new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });

    var calendar = new Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      themeSystem: 'bootstrap',
      locale: 'es',
      validRange: {
        start: new Date()
      },

      dateClick: function(e) {

        var fechaClic = e.date.toISOString().split('T')[0];
        var eventosEnDia = calendar.getEvents();

        eventosEnDia.forEach(function(evento) {
           let fechaEvento = evento.start.toISOString().split('T')[0];

           if(fechaClic === fechaEvento )
           {
             evento.remove();
           }
        });
      },
      eventColor: '#378006',
      //Random default events
      editable  : false,
    });

    calendar.render();


    /**
     * Evento de btn solicitar vacaciones
     */

    document.getElementById('btnSolicitar').addEventListener('click',function(e){
        let eventosEnDia = calendar.getEvents();
        let auxArray = [];

        if(eventosEnDia.length === 0)
        {
            mensajeAlert('Error!' , 'Selecciona los días de forma correcta!' , 'error');
        }else{
             //Ocultamos boton de solicitar vacaciones
            e.target.classList.add('d-none');

            //Mostraos boton de spinner
            document.getElementById('btnSolicitarSpinner').classList.remove('d-none');

            //Deshabilitamos el draggable
            document.querySelector('.external-event').classList.add('disabled');

            eventosEnDia.forEach(function(evento) {
                auxArray.push(evento.start.toISOString().split('T')[0]);
            });

           let respuesta =  peticionAsincrona('/colaboradores/registroVacaciones' , 'post' , {data:auxArray});

           respuesta.then(function(resultado)  {
            Swal.fire({
                title: 'Buen trabajo!',
                text: 'Las vacaciones fueron solicitadas con éxito!',
                icon: 'success'
              }).then(()=>{
                location.reload();
              });
           }).catch(function(error)
           {

                mensajeAlert('¡No pudimos procesar la solicitud!' , error.responseText , 'error');

                //Mostramos el boton de solicitar vacaciones
                document.getElementById('btnSolicitar').classList.remove('d-none');

                //Mostraos boton de spinner
                document.getElementById('btnSolicitarSpinner').classList.add('d-none');

                //Deshabilitamos el draggable
                document.querySelector('.external-event').classList.remove('disabled');
           });

        }

    });

  })
