# Subasta Backend
Api URL: //localhost/api

Api Documentation URL: //localhost/api-doc


## Descripción 
Subasta Backend 

## Librerias empleadas
Descripción de las librerias empleadas: 
 - "barryvdh/laravel-dompdf": Generación de reportes pdf.
 - "flowframe/laravel-trend": Generación de datos estadísticos de un modelo.
 - "jason-guru/laravel-make-repository": Facilita la implementación del patrón de reppositorios.
 - "laravel/sanctum": Se emplea para la autenticación y gestión.

## Indicaciones 
- Mantener los controladores lo mas limpio posible.
- Trabajar por módulos.
- Desarrollar la lógica en servicios por módulo.
- Desarrollar la parte de acceso a datos en repositorios por módulos.




## Notas
- Para crear un Modelo (Model), Controlador (Controller) y Migración (Migration): 

    php artisan make:model Post -mcr

-m, --migration Create a new migration file for the model.

-c, --controller Create a new controller for the model.

-r, --resource Indicates if the generated controller should be a resource controller
