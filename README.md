<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Objetivo

Diseñar un sistema que permita al personal administrativo solicitar períodos de vacaciones de manera eficiente y organizada.

## Sobre el sistema 
Administrador 
El administrador tendrá acceso a todos los módulos, podrá dar de alta nuevos usuarios, eliminarlos, editar la información de cada uno de ellos, y modificar el perfil.  

El perfil 'Humanos' tendrá la capacidad de registrar solicitudes de vacaciones y acceder al módulo de Recursos Humanos. En este módulo, podrá visualizar un listado completo de todos los colaboradores, junto con la cantidad de días de vacaciones que han tomado y los días restantes en el año actual. Además, podrá generar reportes en formato PDF y Excel

Jefe Este perfil puede crear nuevas solicitudes y gestionar las solicitudes de sus subordinados. 

Cada vez que un colaborador genera una solicitud de vacaciones, se almacenan en la tabla solicitud_vacaciones y solicitud_vacaciones_detalle, toda la información correspondiente a dicha solicitud. Al igual que se le manda un correo a su jefe inmediato informándole acerca de la solicitud realizada. 


Colaborador
El perfil colaborador, podrá solamente crear solicitudes nuevas de vacaciones. 


## Detalle técnicos
En el servidor en este caso de Cpanel es necesario tener levanto el servicio de colas, para que los correos se puedan enviar de forma correcta. Para hacerlo desde la terminar debemos de dirigirnos a la ruta de artisan de nuestro proyecto y ejecutar el siguiente comando php artisan queue:work. De esta manera el servicio de colas estará en escucha de una solicitud.

Para el proceso de envió de recordatorio de cada hora, es necesario tener configurado un cronjob dentro del servidor. 

En el caso de usar colas por medio de bases de datos es importante ejecutar los siguientes comandos de Laravel el cual nos va crear la tabla necesarias para que funcione adecuadamente nuestras colas de trabajo 

•	php artisan queue:table 

Y luego ejecutamos la migración
•	php artisan migrate



