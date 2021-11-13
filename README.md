#Prueba API T2C

Requisitos para el funcionamiento

    PHP +7.2.31
    Base de datos MariaDB +10.3.28 en localhost:3306
    PHP Composer
    PHP Symfony

##Instalación
    1. Instalación de composer
    https://getcomposer.org/download/

    2. Instalación de Symfony Framework
    https://symfony.com/download

    3. Instalación de servidor MYSQL
    https://www.apachefriends.org/es/download.html

    4. Clonar el Repositorio
    https://github.com/Fgarciaher/vehicle_t2c.git

    5. Ejecutar la consola en la carpeta del proyecto y ejecutar "composer update" y "composer install"

    6. Crear nueva base de datos con el nombre vehicle_t2c

    7. Importar el archivo .sql en la base de datos vehcicle_t2c

    8. Ejecutar el servidor con el comando "symfony serve", iniciará un servidor local escuchado en el puerto 8000.

##Uso
Rutas accesibles de la API

    
POST http://127.0.0.1:8000/api/vehicle/getAll

Muestra todos los vehículos en la base de datos, acepta dos parámetros POST para odenar los resultados.

Campo field: Fecha de registro del vehículo (entry_date) o Última fecha de venta (last_sale_date)
Campo order: Orden de los resultados Ascendente (ASC) o Descendente (DESC)    
Ejemplo 
{ 
    "field":"entry_date",
    "order":"ASC"
}

#
GET http://127.0.0.1:8000/api/vehicle/{id}

Muestra los detalles de un vehículo.

Campo {id}: Debe ser la id de un vehículo.

Ejempo http://127.0.0.1:8000/api/vehicle/2

#
POST http://127.0.0.1:8000/api/vehicle

Crea un nuevo vehículo a partir de los siguientes parámetros;


    Matrícula del vehículo (license), si no se indica matrícula se registra como null.

    Modelo del vehículo (model), debe ser una id de un modelo de la base de datos.

    Fecha de ingreso (entry_date), debe ser una fecha con formato datetime

    Coste del Vehículo (cost), acepta numeros con decimales.

    Concesionario (store_id), debe ser una id de una tienda dentro de la base de datos

    Ejemplo
    {
        "license":"2356TRD",
        "model": 4,
        "entry_date": "2021-11-13T15:04:01.01",
        "cost": 2000,
        "store_id": 1
    }

#
DELETE http://127.0.0.1:8000/api/vehicle/{id}

Borra el vehículo con la id indicada en la ruta. Un vehículo vendido no se puede borrar.

Ejemplo: http://127.0.0.1:8000/api/vehicle/1


#
PUT http://127.0.0.1:8000/api/vehicle/{id}

Actualiza los datos del vehículo indicado, un vehículo vendido no se puede actualizar.

    Matrícula del vehículo (license), si no se indica matrícula se registra como null.

    Modelo del vehículo (model), debe ser una id de un modelo de la base de datos.

    Fecha de ingreso (entry_date), debe ser una fecha con formato datetime

    Coste del Vehículo (cost), acepta números con decimales.

    Concesionario (store_id), debe ser una id de una tienda dentro de la base de datos

    Ejemplo
    {
        "license":"2942ITM",
        "model": 5,
        "entry_date": "2021-11-13T15:04:01.01",
        "cost": 2000,
        "store_id": 2
    }  


#
GET http://127.0.0.1:8000/api/profit

Muestra los beneficios totales, suma todas las ventas de vehículos y resta todos los costes.

#
GET http://127.0.0.1:8000/api/profit/{id}

Muestra los beneficios por concesionario, suma todas las ventas de un concesionario y resta todos los costes de ese concesionario.

El campo {id} debe ser la id de una tienda en la base de datos.

Ejemplo: http://127.0.0.1:8000/api/profit/2


##Uso con POSTMAN
Importar el archivo Vehicle.postman_collection.json para usar las rutas con el programa Postman.