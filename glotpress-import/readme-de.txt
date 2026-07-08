=== Plogins Minimum - Minimum Order for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, minimum order, quantity, order rules, minimum quantity
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Erfordert Plugins: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lege Mindest-, Höchst- und Schrittmengenregeln sowie eine Mindestbestellsumme fest, die im Warenkorb und an der Kasse mit Hinweisen durchgesetzt wird, die die Kasse blockieren.

== Description ==

Minimum fügt deinem WooCommerce-Shop Mengen- und Ausgabenregeln hinzu. Sie entscheiden, wie viele Einheiten eines Produkts ein Kunde kaufen muss, begrenzen die Menge, die er kaufen darf, verkaufen in festen Packungsgrößen und verlangen eine Mindestbestellmenge, bevor der Kauf möglich ist. Regeln können für ein einzelnes Produkt, eine ganze Kategorie oder jedes Produkt gleichzeitig festgelegt werden.

Das Plugin überprüft den Warenkorb, wenn ein Artikel hinzugefügt wird, und erneut an der Kasse. Wenn eine Regel nicht erfüllt ist, sieht der Kunde einen Hinweis, der erklärt, was er ändern muss, und der Checkout bleibt gesperrt, bis das Problem behoben ist.

Quellcode und Fehlerberichte live auf GitHub: https://github.com/wppoland/plogins-minimum

Was du einrichten können:

* Mindestmenge: Vor dem Bezahlen sind mindestens N Einheiten eines Produkts erforderlich.
* Maximale Menge: Begrenzt die Anzahl der Einheiten, die ein Kunde kaufen kann.
* Schrittanzahl: Für Produkte, die in Packungen verkauft werden (z. B. 6er-Packungen), sind nur Vielfache zulässig.
* Mindestbestellsumme: Du musst eine Zwischensumme im Warenkorb angeben, bevor du zur Kasse gehen können.
* Hinweistext: Bearbeite die für jede nicht erfüllte Regel angezeigte Meldung, indem du Token wie {min}, {max}, {step}, {product} und {total} verwenden.

Wenn für ein Produkt mehr als eine Regel gelten könnte, gewinnt für jeden Wert die spezifischere Regel: Eine Produktregel übertrifft eine Kategorieregel und eine Kategorieregel übertrifft die globale Regel. Min., Max. und Schritt werden separat aufgelöst, sodass du sie bereichsübergreifend mischen können.

Weitere wissenswerte Dinge:

* Funktioniert mit HPOS (benutzerdefinierte Bestelltabellen) und den Warenkorb- und Checkout-Blöcken sowie dem klassischen Warenkorb und Checkout. Die Validierung liest den Warenkorbinhalt, sodass beide Layouts abgedeckt sind.
* Der Einstellungsbildschirm folgt dem WordPress-Admin-Stil, respektiert das dunkle Farbschema und beschriftet jedes Feld für die Verwendung per Tastatur und Bildschirmlesegerät.
* Keine benutzerdefinierten Datenbanktabellen. Durch das Löschen des Plugins werden seine beiden Optionen entfernt und der Rest deiner Datenbank bleibt unberührt.
* Keine gebündelten Bibliotheken oder jQuery auf dem Einstellungsbildschirm.

== Installation ==

1. Installieren und aktiviere WooCommerce 8.0 oder höher.
2. Lade den Ordner „plogins-minimum“ nach „/wp-content/plugins/“ hoch oder installiere die ZIP-Datei über den Bildschirm <strong>Plugins → Neu hinzufügen → Plugin hochladen<strong>. 3. Aktiviere das Plugin über den Bildschirm </strong>Plugins<strong>. 4. Gehe zu </strong>WooCommerce → Minimum</strong> und füge eine Regel hinzu (z. B. ein Produkt mit einer Mindestmenge von 3) oder lege eine Mindestbestellmenge fest.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-minimum/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-minimum/
* <strong>Quellcode</strong> – https://github.com/wppoland/plogins-minimum
* <strong>Fehlerberichte und Funktionsanfragen</strong> – https://github.com/wppoland/plogins-minimum/issues


= Does it need WooCommerce? =
Ja. Minimum ist eine WooCommerce-Erweiterung und erfordert WooCommerce 8.0 oder höher. Wenn WooCommerce fehlt, bleibt das Plugin inaktiv und zeigt einen Administratorhinweis an.

= What happens if two rules cover the same product? =
Jeder Wert wird einzeln aufgelöst. Für Min., Max. und Schritt separat hat eine Produktregel Vorrang vor einer Kategorieregel, die wiederum Vorrang vor der globalen Regel hat. Ein Feld, das auf 0 belassen wird, bedeutet, dass der Wert nicht erzwungen wird.

= Does it work with the block-based cart and checkout? =
Ja. Die Regeln werden anhand des Warenkorbinhalts und nicht anhand einer bestimmten Vorlage überprüft, sodass dieselben Hinweise angezeigt und der Checkout blockiert wird, unabhängig davon, ob du die klassischen Seiten oder die Warenkorb- und Checkout-Blöcke verwenden.

= Can I change the wording shown to customers? =
Ja. Jede Benachrichtigung verfügt über ein eigenes Feld auf dem Einstellungsbildschirm. Token wie {min}, {max}, {step}, {product} und {total} werden gegen die passenden Werte ausgetauscht.

= Can I turn rules off without deleting them? =
Ja. Der Durchsetzungsschalter auf dem Einstellungsbildschirm verhindert, dass Regeln im Warenkorb und an der Kasse angewendet werden, während sie gespeichert bleiben.

= What is left behind when I delete the plugin? =
Der Deinstallationsschritt löscht die Optionen „minimum_settings“ und „minimum_db_version“. Es gibt keine benutzerdefinierten Tabellen, sodass nichts anderes zu deiner Datenbank hinzugefügt oder in dieser verbleibt.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es im Netzwerk oder auf einzelnen Websites. Jede Site behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Im Schaufenster.
2. Einstellungen im WordPress-Admin.
3. Auf einem mobilen Gerät.
== External Services ==

Minimum stellt keine Verbindung zu externen Diensten her. Die Regeln werden anhand des Warenkorbs auf deinem eigenen Server ausgewertet und die einzigen gespeicherten Daten sind zwei WordPress-Optionen: „minimum_settings“ (deine Regeln und dein Hinweistext) und „minimum_db_version“. Das Plugin sendet keine E-Mails und erstellt keine benutzerdefinierten Datenbanktabellen.

== Changelog ==

= 1.0.1 =
* Erste stabile Version.

= 0.1.2 =
* Für einen eindeutigeren Plugin-Namen in Plogins Minimum für WooCommerce umbenannt.

= 0.1.1 =
* Erweiterungsfilter „minimum/rules“ und „minimum/min_order_total“ für Add-ons wie Minimum Pro-Rollenregeln.
* Unterstützung des Rollenbereichs in der Regel-Engine („role“-Slug in Regelzeilen).

= 0.1.0 =
* Erstveröffentlichung. Pro Produkt, pro Kategorie und globale Mengenregeln (Min., Max., Schritt), eine Mindestbestellmenge und bearbeitbare Benachrichtigungen. Wird beim Hinzufügen zum Warenkorb sowie beim Warenkorb und an der Kasse durchgesetzt. Kompatibel mit HPOS und den Cart- und Checkout-Blöcken. Kein jQuery auf dem Einstellungsbildschirm.
