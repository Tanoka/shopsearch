# Instalación y uso
Para poder activar el proyecto hace falta tener __Docker__ instalado y activado. Este proyecto se puede ejecutar tanto en Windows como en Linux.

Una vez descargado el proyecto de github podemos ejecutar los siguientes scripts:
* Activar el endpoint: para ello ejecutamos ```./start_server.sh``` en Linux o ```.\start_server.ps1``` en una terminal PowerShell de Windows
* Lanzar los tests:  ```./run_tests.sh``` en Linux o ```.\run_tests.ps1``` en una terminal PowerShell de Windows

Cualquiera de estos script crea una imagen de Docker en la que instala la aplicación y todas sus dependencias, además
de una pequeña base de datos SQlLite para poder hacer las pruebas.

Hay test unitarios, de integracion y de aplicación, el script de test los lanza todos.

### Llamadas al endpoint
* obtener todos los productos: http://localhost:8000/products

* Obtener los productos de la categoría _boots_: http://localhost:8000/products?category=boots

* Obtener los productos con un precio inferior a 90 (en base de datos: 90000): http://localhost:8000/products?priceLessThan=90

todos los endpoint retornan como máximo 5 resultados

## Diseño e implementación

## Capa de infraestructura
El endpoint lo he desarrollado sobre el **framework Symfony** el cual se ocupa de la mayoría de las tareas relacionadas con la infraestructura, como enrutamiento, la inyecccion de dependencias, sistema de logging, serialización de la respuesta, gestión de exceptiones y de proporcionar un servidor de desarrollo.

Para la gestión de la persistencia usamos el ORM Doctrine.

Decisiones como sistema de caché y otras optimizaciones para mejorar el rendimiento no se han tenido en cuenta.

## Persistencia de datos
Para la persistencia utilizamos una base de datos relacional, **_SQLite_**. No es una base de datos para un entorno de producción, pero en este caso nos proporciona las funcionadades necesarias para realizar la prueba.
Para hacer pruebas con grandes volumenes se podrían usar postgreSQL o MySQL, en cuanto al código de la aplicación no haría falta cambiar nada.
### tablas:
* Products
* Category
* Discount

#### Sobre Discount table
Para guardar los descuentos se ha optado por crear una única tabla, la cual distingue el tipo de descuento por los campos, __discount_type__, que puede ser "sku" o "category" y __discount_type_id__ para guardar el identificador del producto o de la categoria indicada. De esta forma es sencillo añadir nuevos tipo.
Otra opción es crear una tabla por cada tipo, _discount_category_ y _discount_product_, de esta forma podríamos crear
_constraints_ con la tabla _Products_, que nos garantizan la integridad de la relación, pero por contra crear nuevo tipos de descuento es mucho más complicado, pues obliga a crear nuevas tablas y nuevos repositorios.  

## Capa de Aplicación
El core del código del proyecto está en el directorio __Service__. Ahí podemos encontrar los servicios de dominio y los interfaces que deben ser implementados desde la capa de infraestructura.

El directorio __Repository__ (infraestructura) contiene la implementación de los interfaces de los repositorios indicados desde los servicios de dominio.

El directorio __Entity__ contiene las entidades. Son entidades generadas por el ORM, por lo tanto relacionadas con la infraestructura, pero en este caso tambien las usamos como entidades de dominio, debido a su poca complejidad no vale la pena desacoplarlas en entidades de dominio propias. 

### Servicio Discount
El valor del porcentaje de los descuentos se guarda en la base de datos de la misma forma que los importes, un valor entero. 15 se corresponde con 1500, y 15.5 con 1550.

Para la gestión de los descuentos he optado por crear un nuevo servicio de dominio en lugar de hacerlo desde la entidad _Product_, aunque en un principio un producto podría ser responsable de aplicar sus descuentos, en la práctica estos descuentos hacen uso de otras entidades y pueden llegar tener lógicas complejas, por lo que creo que es más adecuado crear un nuevo servicio donde encapsular esa funcionalidad.



