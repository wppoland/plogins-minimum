=== Plogins Minimum - Minimum Order for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, minimum order, quantity, order rules, minimum quantity
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requiere complementos: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Establezca reglas de cantidad mínima, máxima y de pasos más un total mínimo de pedido, aplicado en el carrito y en el momento del pago con avisos que bloquean el proceso de pago.

== Description ==

El mínimo añade cantidad y reglas de gasto a su tienda WooCommerce. Usted decide cuántas unidades de un producto tiene que comprar un cliente, limita cuántas puede comprar, vende en tamaños de paquetes fijos y exige un pedido total mínimo antes de que se permita el pago. Se pueden establecer reglas para un solo producto, una categoría completa o todos los productos a la vez.

El complemento verifica el carrito cuando se añade un artículo y nuevamente al finalizar la compra. Si no se cumple una regla, el cliente ve un aviso que explica qué cambiar y el proceso de pago permanece bloqueado hasta que se solucione.

El código fuente y los informes de errores están disponibles en GitHub: https://github.com/wppoland/plogins-minimum

Qué puedes configurar:

*Cantidad mínima: requiere al menos N unidades de un producto antes de realizar el pago.
* Cantidad máxima: limita cuántas unidades puede comprar un cliente.
* Cantidad de pasos: solo permite múltiplos, para productos vendidos en paquetes (por ejemplo, paquetes de 6).
* Total mínimo del pedido: requiere un subtotal del carrito antes de permitir el pago.
* Redacción del aviso: edite el mensaje que se muestra para cada regla no cumplida, usando tokens como {min}, {max}, {step}, {product} y {total}.

Cuando se puede aplicar más de una regla a un producto, la más específica gana para cada valor: una regla de producto supera a una regla de categoría y una regla de categoría supera a la regla global. El mínimo, el máximo y el paso se resuelven por separado, por lo que puede combinarlos en distintos ámbitos.

Otras cosas que vale la pena saber:

* Funciona con HPOS (tablas de pedidos personalizadas) y los bloques Carrito y Pago, así como el clásico carrito y pago. La validación lee el contenido del carrito, por lo que ambos diseños están cubiertos.
* La pantalla de configuración sigue el estilo de administración de WordPress, respeta la combinación de colores oscuros y etiqueta cada campo para el uso del teclado y del lector de pantalla.
* No hay tablas de bases de datos personalizadas. Eliminar el complemento elimina sus dos opciones y deja intacto el resto de tu base de datos.
* No hay bibliotecas incluidas ni jQuery en la pantalla de configuración.

== Installation ==

1. Instale y active WooCommerce 8.0 o posterior.
2. Cargue la carpeta `plogins-minimum` en `/wp-content/plugins/`, o instale el zip desde la pantalla <strong>Complementos → Añadir nuevo → Cargar complemento<strong>. 3. Active el complemento a través de la pantalla </strong>Complementos<strong>. 4. Vaya a </strong>WooCommerce → Mínimo</strong> y añade una regla (por ejemplo, un producto con una cantidad mínima de 3) o establezca un total mínimo de pedido.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-minimum/docs/
* <strong>Página de complementos</strong> - https://plogins.com/es/plogins-minimum/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-minimum
* <strong>Informes de errores y solicitudes de funciones</strong> - https://github.com/wppoland/plogins-minimum/issues


= Does it need WooCommerce? =
Sí. Mínimo es una extensión de WooCommerce y necesita WooCommerce 8.0 o posterior activo. Si falta WooCommerce, el complemento permanece inactivo y muestra un aviso de administrador.

= What happens if two rules cover the same product? =
Cada valor se resuelve por sí solo. Para mínimo, máximo y paso por separado, una regla de producto anula una regla de categoría, que anula la regla global. Un campo dejado en 0 significa que ese valor no se aplica.

= Does it work with the block-based cart and checkout? =
Sí. Las reglas se comparan con el contenido del carrito en lugar de con una plantilla específica, por lo que aparecen los mismos avisos y el pago se bloquea ya sea que use las páginas clásicas o los bloques Carrito y Pago.

= Can I change the wording shown to customers? =
Sí. Cada aviso tiene su propio campo en la pantalla de configuración. Los tokens como {min}, {max}, {step}, {product} y {total} se intercambian por los valores coincidentes.

= Can I turn rules off without deleting them? =
Sí. El interruptor Aplicación en la pantalla de configuración detiene la aplicación de reglas en el carrito y en la caja mientras las mantiene guardadas.

= What is left behind when I delete the plugin? =
El paso de desinstalación elimina las opciones `minimum_settings` y `minimum_db_version`. No hay tablas personalizadas, por lo que no se añade ni se deja nada más en tu base de datos.


= Does this plugin work on WordPress Multisite? =

Sí. Este complemento es compatible con WordPress Multisite. Activarlo en red o activarlo en sitios individuales; Cada sitio mantiene su propia configuración y datos.

== Screenshots ==

1. En el escaparate.
2. Configuración en el administrador de WordPress.
3. En un dispositivo móvil.
== External Services ==

Mínimo no se conecta a ningún servicio externo. Las reglas se evalúan con respecto al carrito en su propio servidor, y los únicos datos almacenados son dos opciones de WordPress, `minimum_settings` (sus reglas y texto de aviso) y `minimum_db_version`. El complemento no envía correo electrónico ni crea tablas de bases de datos personalizadas.

== Changelog ==

= 1.0.1 =
* Primera versión estable.

= 0.1.2 =
* Renombrado a Plogins Mínimo para WooCommerce para un nombre de complemento más distintivo.

= 0.1.1 =
* La extensión filtra `minimum/rules` y `minimum/min_order_total` para complementos como reglas de rol de Maximum Pro.
* Soporte de alcance de rol en el motor de reglas (slug `role` en filas de reglas).

= 0.1.0 =
* Lanzamiento inicial. Reglas de cantidad global por producto, por categoría y (mínimo, máximo, paso), un pedido mínimo total y mensajes de aviso editables. Se aplica al añadir al carrito, al carrito y al finalizar la compra. Compatible con HPOS y los bloques Cart y Checkout. No hay jQuery en la pantalla de configuración.
