import {peticionSolicitudVacaciones} from '../../ajax.js';
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

        //Ocultamos boton de solicitar vacaciones
        e.target.classList.add('d-none');

        //Mostraos boton de spinner
        document.getElementById('btnSolicitarSpinner').classList.remove('d-none');

        //Deshabilitamos el draggable
        document.querySelector('.external-event').classList.add('disabled');

        eventosEnDia.forEach(function(evento) {
            auxArray.push(evento.start.toISOString().split('T')[0]);
         });



         peticionSolicitudVacaciones('/colaboradores/registroVacaciones' , 'post' , {data:auxArray});

        // console.log(auxArray);
    });

  })
