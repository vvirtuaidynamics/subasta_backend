# Subasta Backend
Api URL: //localhost/api

Api Documentation URL: //localhost/api-doc

## Descripción 
Subasta Backend 

## Librerias empleadas
Descripción de las librerias empleadas: 
 - "barryvdh/laravel-dompdf": Generación de reportes pdf.
   https://github.com/barryvdh/laravel-dompdf
 - "flowframe/laravel-trend": Generación de datos estadísticos de un modelo.
   https://github.com/Flowframe/laravel-trend
 - "jason-guru/laravel-make-repository": Facilita la implementación del patrón de reppositorios.
   https://github.com/jason-guru/laravel-make-repository
 - "santigarcor/laratrust": Gestión de roles y permisos.

    https://laratrust.santigarcor.me/
 - "laravel/sanctum": Se emplea para la autenticación y gestión.
 - "maatwebsite/excel" : Se emplea para importar y exportar datos desde o hacia excel o csv.
 https://docs.laravel-excel.com/3.1/getting-started/installation.html
 - "darkaonline/l5-swagger": Paquete para documentar la api.
 https://github.com/DarkaOnLine/L5-Swagger


## Indicaciones 
- Mantener los controladores lo mas limpio posible.
- Trabajar por módulos.
- Desarrollar la lógica en servicios por módulo.
- Desarrollar la parte de acceso a datos en repositorios por módulos.

## Implementación de patrón repositorio.
###  

## Notas
- Para crear un Modelo (Model), Controlador (Controller) y Migración (Migration): 

    php artisan make:model Post -mcr

-m, --migration Create a new migration file for the model.

-c, --controller Create a new controller for the model.

-r, --resource Indicates if the generated controller should be a resource controller


## Patrón Service-Repository.
### Implementación del patrón service-repository en laravel

Route > Controller::action > Service > Repository

View  < Controller::action < Service < Repository

En esté patrón se divide la de la lógica de negocio hacia el 
servicio y el acceso a datos hacia el repositorio.

Ejemplo si tenemos un modelo Post que tiene su repositorio asociado
y su servicio. La implementación de crear un Post sería:
PostController -> PostService -> PostRepository

Desde el controlador se llamaría al servicio y al metodo requerido. 
$this->postService->savePostData($data)

En el servicio se validarian los datos y si no hay error se llamaria metodo
correspondiente en el repositorio que seria el encargado de guardar los datos en la db.
$this->postRepository->save($data);

## Swagger && API Doc
Articulo: 
https://medium.com/@nelsonisioma1/how-to-document-your-laravel-api-with-swagger-and-php-attributes-1564fc11c305

Podemos publicar la configuración de L5-Swagger's 

$ php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
Podemos generar la documentación ejecutando los siguientes comandos.

$ php artisan l5-swagger:generate

Podemos ver la documentación generada en: 

{hosname}/api/documentation


## Localization
Emplearemos para la gestión de la localización o idioma la siguiente librería.
composer require --dev laravel-lang/common

php artisan lang:add en

php artisan lang:add es

## Roles y permisos con santigarcor/laratrust
You can install the package using composer:

composer require santigarcor/laratrust

Publish the configuration file:

php artisan vendor:publish --tag="laratrust"

