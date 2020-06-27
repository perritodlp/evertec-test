### Prueba técnica para desarrollador Evertec

Se lleva a cabo las actividades para la evalución técnica para ingreso a la compañía. Se verifica en entorno local, el cumplimiento de los criterios de evaluación.

La prueba se realiza usando el framework de PHP Symfony versión 5.1, MySql 5.7 para persistencia de datos, Nginx como servidor web y PhpMyAdmin para gestionar la base de datos.

Se crearon 5 productos que se cargan aleatoriamente en la página de inicio de la prueba. También se manejó aleatoreamente, el identificador del cliente. Algunos datos del cliente, como el documento de identidad, dirección, etc., fueron manejados dejándolos "fijos" en el código que realiza la petición de pago a la pasarela de pagos.

### :cloud: Clonando el proyecto desde Github, para ejecutarlo con Docker

```bash
# Desde la terminal
cd ~/ruta-deseada/
git clone --recurse-submodules https://github.com/perritodlp/symfony-docker.git
```