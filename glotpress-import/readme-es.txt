=== Plogins Minimum - Minimum Order for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, minimum order, quantity, order rules, minimum quantity
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requires Plugins: woocommerce
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Define reglas de cantidad mínima, máxima y por pasos, además de un total mínimo de pedido, aplicadas en el carrito y en el pago con avisos que bloquean la finalización de la compra.

== Description ==

Minimum añade reglas de cantidad y de gasto a tu tienda WooCommerce. Tú decides cuántas unidades de un producto tiene que comprar un cliente, limitas cuántas puede comprar, vendes en tamaños de paquete fijos y exiges un total mínimo de pedido antes de permitir el pago. Las reglas se pueden definir para un solo producto, una categoría completa o todos los productos a la vez.

El plugin comprueba el carrito cuando se añade un artículo y de nuevo en el pago. Si una regla no se cumple, el cliente ve un aviso que explica qué cambiar y el pago permanece bloqueado hasta que lo corrija.

El código fuente y los informes de errores están en GitHub: https://github.com/wppoland/plogins-minimum

Qué puedes configurar:

* Cantidad mínima: exige al menos N unidades de un producto antes del pago.
* Cantidad máxima: limita cuántas unidades puede comprar un cliente.
* Cantidad por pasos: solo permite múltiplos, para productos vendidos en paquetes (por ejemplo, paquetes de 6).
* Total mínimo del pedido: exige un subtotal del carrito antes de permitir el pago.
* Texto del aviso: edita el mensaje mostrado para cada regla incumplida, usando tokens como {min}, {max}, {step}, {product} y {total}.

Cuando más de una regla puede aplicarse a un producto, gana la más específica para cada valor: una regla de producto supera a una de categoría, y una de categoría supera a la regla global. Mínimo, máximo y paso se resuelven por separado, así que puedes mezclarlos entre ámbitos.

Otras cosas que conviene saber:

* Funciona con HPOS (tablas de pedidos personalizadas) y con los bloques de carrito y pago, además del carrito y pago clásicos. La validación lee el contenido del carrito, así que ambos diseños quedan cubiertos.
* La pantalla de ajustes sigue el estilo del escritorio de WordPress, respeta el esquema de color oscuro y etiqueta cada campo para teclado y lectores de pantalla.
* Sin tablas de base de datos propias. Al eliminar el plugin se borran sus dos opciones y el resto de tu base de datos queda intacto.
* Sin bibliotecas incluidas ni jQuery en la pantalla de ajustes.

== Installation ==

1. Instala y activa WooCommerce 8.0 o posterior.
2. Sube la carpeta `plogins-minimum` a `/wp-content/plugins/` o instala el zip desde la pantalla <strong>Plugins → Añadir nuevo → Subir plugin</strong>.
3. Activa el plugin desde la pantalla <strong>Plugins</strong>.
4. Ve a <strong>WooCommerce → Minimum</strong> y añade una regla (por ejemplo, un producto con cantidad mínima 3) o define un total mínimo de pedido.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-minimum/docs/
* <strong>Página del plugin</strong> - https://plogins.com/es/plogins-minimum/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-minimum
* <strong>Informes de errores y peticiones de funciones</strong> - https://github.com/wppoland/plogins-minimum/issues


= Does it need WooCommerce? =
Sí. Minimum es una extensión de WooCommerce y necesita WooCommerce 8.0 o posterior activo. Si falta WooCommerce, el plugin permanece inactivo y muestra un aviso en el escritorio.

= What happens if two rules cover the same product? =
Cada valor se resuelve por sí solo. Para mínimo, máximo y paso por separado, una regla de producto anula una de categoría, que anula la regla global. Un campo dejado en 0 significa que ese valor no se aplica.

= Does it work with the block-based cart and checkout? =
Sí. Las reglas se comprueban contra el contenido del carrito, no contra una plantilla concreta, así que aparecen los mismos avisos y el pago se bloquea tanto si usas las páginas clásicas como los bloques de carrito y pago.

= Can I change the wording shown to customers? =
Sí. Cada aviso tiene su propio campo en la pantalla de ajustes. Los tokens como {min}, {max}, {step}, {product} y {total} se sustituyen por los valores correspondientes.

= Can I turn rules off without deleting them? =
Sí. El interruptor Aplicación en la pantalla de ajustes deja de aplicar las reglas en el carrito y en el pago, pero las mantiene guardadas.

= What is left behind when I delete the plugin? =
El paso de desinstalación elimina las opciones `minimum_settings` y `minimum_db_version`. No hay tablas propias, así que no se añade ni queda nada más en tu base de datos.


= Does this plugin work on WordPress Multisite? =

Sí. Este plugin es compatible con WordPress Multisite. Actívalo para toda la red o en sitios concretos; cada sitio conserva sus propios ajustes y datos.

== Screenshots ==

1. En la tienda.
2. Ajustes en el escritorio de WordPress.
3. En un dispositivo móvil.
== External Services ==

Minimum no se conecta a ningún servicio externo. Las reglas se evalúan contra el carrito en tu propio servidor, y los únicos datos almacenados son dos opciones de WordPress: `minimum_settings` (tus reglas y textos de aviso) y `minimum_db_version`. El plugin no envía correos ni crea tablas de base de datos propias.

== Translations ==

Plogins Minimum incluye traducciones al polaco, al alemán y al español para la interfaz del plugin. El dominio de texto es `plogins-minimum`, por lo que los paquetes de idioma de WordPress.org también pueden sustituir o ampliar estas traducciones incluidas.

== Changelog ==

= 1.0.2 =
* Se añadieron traducciones incluidas al polaco, al alemán y al español para la interfaz del plugin.

= 1.0.1 =
* Primera versión estable.

= 0.1.2 =
* Renombrado a Plogins Minimum for WooCommerce para un nombre de plugin más distintivo.

= 0.1.1 =
* Filtros de extensión `minimum/rules` y `minimum/min_order_total` para complementos como las reglas por rol de Minimum Pro.
* Soporte de ámbito por rol en el motor de reglas (slug `role` en filas de reglas).

= 0.1.0 =
* Lanzamiento inicial. Reglas de cantidad por producto, por categoría y globales (mínimo, máximo, paso), un total mínimo de pedido y mensajes de aviso editables. Aplicado al añadir al carrito y en el carrito y el pago. Compatible con HPOS y con los bloques de carrito y pago. Sin jQuery en la pantalla de ajustes.
