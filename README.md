> ## Si llego buscando el archivo de instalación para su tienda [Descargue la última versión dando click Aquí] [compropago-3-0-x]


# Plugin para OpenCart 2.1.x

## Descripción
Este modulo provee el servicio de ComproPago para poder generar intenciones de pago dentro de la plataforma Opencart.

Con ComproPago puede recibir pagos en OXXO, 7Eleven y muchas tiendas más en todo México.

[Registrarse en ComproPago ](https://compropago.com)


## Ayuda y Soporte de ComproPago

- [Centro de ayuda y soporte](https://compropago.com/ayuda-y-soporte)
- [Solicitar Integración](https://compropago.com/integracion)
- [Guía para Empezar a usar ComproPago](https://compropago.com/ayuda-y-soporte/como-comenzar-a-usar-compropago)
- [Información de Contacto](https://compropago.com/contacto)

Este modulo provee el servicio de ComproPago para poder generar intensiones de pago dentro de la plataforma Magento.

* [Instalación](#install)
* [¿Cómo trabaja el modulo?](#howto)
* [Configuración](#setup)
* [Sincronización con los webhooks](#webhook)

## Requerimientos
* [Opencart 2.1.x +](https://www.woothemes.com/woocommerce/)
* [PHP >= 5.4](http://www.php.net/)
* [PHP JSON extension](http://php.net/manual/en/book.json.php)
* [PHP cURL extension](http://php.net/manual/en/book.curl.php)

<a name="install"></a>
## Instalación:

1. Copiar los directorios **admin** y **catalog** en el mismo orden, en directorio raiz de OpenCart. Asegurate de mantener la estructura en los directorios.

2. Copiar la carpeta vendor dentro de la raiz de opencart al nivel de las carpetas **admin** y **catalog** junto los archivos composer.json y composer.lock al mismo nivel.

3. Ingresar en el panel de admistración a **Extensions > Payments** y dar click en el boton install de **Compropago Payment Method**.

---
<a name="setup"></a>
## Configurar ComproPago

1. Para iniciar la configuración ir a **Extensions > Payments**. Dar click en el boton editar de **Compropago Payment Method**.

2. Dentro de la pestaña **Plugin Configurations** cambiar **Status** a 'Enabled', ingresar las **Claves Publica y Privada** ( Si no conoce sus claves puede verificarlas dentro del panel de administracion de su cuenta en Compropago [https://compropago.com/panel/configuracion](https://compropago.com/panel/configuracion) ), Seleccionar el modo correspondiente a Pruebas o activo. El campo **Sort Order** indicara el lugar en el cual se mostrara Compropago como metodo de pago al realizar una compra, si desa que Compropago sea su metodo de pago por defecto indique **Sort Order** = 1.

3. Dentro de la pestaña **Display Configurations** puede indicar la manera en la cual se mostrara la seleccion de proveedores para realización del pago.
Para mostrar u ocultar los logos de proveedores modifique el campo **Show Logo**, puede tambien agragar una pequeña descripción del apartado con el campo **Description Service**, y por ultimo puede tambien agregar las instrucciones que desee pertinentes para la selección del proveedor en el apartado **Instructions**

4. Dentro de la pestaña **Estatus Configurations** establecer **New Order status** = Processing y **Approve Order Status** = Processed.

---
<a name="howto"></a>
## ¿Cómo trabaja el modulo?
Una vez que el cliente sabe que comprar y continua con el proceso de compra entrará a la opción de elegir metodo de pago justo aqui aparece la opción de pagar con ComproPago<br /><br />

Una vez que el cliente completa su orden de compra iniciara el proceso para generar su intensión de pago, el cliente selecciona el establecimiento y recibe las instrucciones para realizar el pago.

Una vez que el cliente genero su intención de pago, dentro del panel de control de ComproPago la orden se muestra como "PENDIENTE" esto significa que el usuario esta por ir a hacer el deposito.


---

<a name="webhook"></a>
## Sincronización con la notificación Webhook

1. Ir al area de **Webhooks** en ComproPago [https://compropago.com/panel/webhooks](https://compropago.com/panel/webhooks)

2. Introducir la dirección: ***[direcciondetusitio.com]***/index.php?route=payment/compropago/webhook y dar click en el boton **Agregar URL**

3. Dar click en el botón "Probar" y verificamos que el servidor de la tienda esta respondiendo, debera aparecer el mensaje de "Probando el WebHook?, Ruta correcta."

---

Una vez completado estos pasos el proceso de instalación queda completado.

## Documentación
### Documentación ComproPago Plugin WooCommerce

### Documentación de ComproPago
**[API de ComproPago] (https://compropago.com/documentacion/api)**

ComproPago te ofrece un API tipo REST para integrar pagos en efectivo en tu comercio electrónico o tus aplicaciones.


**[General] (https://compropago.com/documentacion)**

Información de Comisiones y Horarios, como Transferir tu dinero y la Seguridad que proporciona ComproPAgo


**[Herramientas] (https://compropago.com/documentacion/boton-pago)**
* Botón de pago
* Modo de pruebas/activo
* WebHooks
* Librerías y Plugins
* Shopify

[compropago-3-0-x]: https://s3.amazonaws.com/compropago/plugins/opencart/opencart-oc-2-0-0.zip
