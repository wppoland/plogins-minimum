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

Lege Mindest-, Höchst- und Schrittmengenregeln sowie eine Mindestbestellsumme fest, die im Warenkorb und an der Kasse mit Hinweisen durchgesetzt werden, die den Checkout blockieren.

== Description ==

Minimum fügt deinem WooCommerce-Shop Mengen- und Ausgabenregeln hinzu. Du entscheidest, wie viele Einheiten eines Produkts ein Kunde kaufen muss, begrenzt, wie viele er kaufen darf, verkaufst in festen Packungsgrößen und verlangst eine Mindestbestellsumme, bevor der Checkout erlaubt ist. Regeln können für ein einzelnes Produkt, eine ganze Kategorie oder alle Produkte gleichzeitig festgelegt werden.

Das Plugin prüft den Warenkorb, wenn ein Artikel hinzugefügt wird, und erneut an der Kasse. Wenn eine Regel nicht erfüllt ist, sieht der Kunde einen Hinweis, der erklärt, was geändert werden muss, und der Checkout bleibt gesperrt, bis das behoben ist.

Quellcode und Fehlerberichte liegen auf GitHub: https://github.com/wppoland/plogins-minimum

Was du einrichten kannst:

* Mindestmenge: verlange mindestens N Einheiten eines Produkts vor dem Checkout.
* Höchstmenge: begrenze, wie viele Einheiten ein Kunde kaufen darf.
* Schrittmenge: erlaube nur Vielfache, für Produkte, die in Packungen verkauft werden (zum Beispiel 6er-Packungen).
* Mindestbestellsumme: verlange eine Warenkorb-Zwischensumme, bevor der Checkout erlaubt ist.
* Hinweistext: bearbeite die Meldung für jede nicht erfüllte Regel mit Token wie {min}, {max}, {step}, {product} und {total}.

Wenn für ein Produkt mehr als eine Regel gelten könnte, gewinnt für jeden Wert die spezifischere Regel: eine Produktregel schlägt eine Kategorieregel, und eine Kategorieregel schlägt die globale Regel. Min, Max und Schritt werden getrennt aufgelöst, sodass du sie über Bereiche hinweg mischen kannst.

Weitere Dinge, die du wissen solltest:

* Funktioniert mit HPOS (eigenen Bestelltabelle) sowie den Warenkorb- und Kassenblöcken und auch mit dem klassischen Warenkorb und Checkout. Die Validierung liest den Warenkorbinhalt, sodass beide Layouts abgedeckt sind.
* Der Einstellungsbildschirm folgt dem WordPress-Admin-Stil, respektiert das dunkle Farbschema und beschriftet jedes Feld für Tastatur und Screenreader.
* Keine eigenen Datenbanktabellen. Beim Löschen des Plugins werden seine zwei Optionen entfernt, der Rest deiner Datenbank bleibt unberührt.
* Keine mitgelieferten Bibliotheken und kein jQuery auf dem Einstellungsbildschirm.

== Installation ==

1. Installiere und aktiviere WooCommerce 8.0 oder höher.
2. Lade den Ordner `plogins-minimum` nach `/wp-content/plugins/` hoch oder installiere das Zip über den Bildschirm <strong>Plugins → Installieren → Plugin hochladen</strong>.
3. Aktiviere das Plugin über den Bildschirm <strong>Plugins</strong>.
4. Gehe zu <strong>WooCommerce → Minimum</strong> und füge eine Regel hinzu (zum Beispiel ein Produkt mit einer Mindestmenge von 3) oder lege eine Mindestbestellsumme fest.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-minimum/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-minimum/
* <strong>Quellcode</strong> - https://github.com/wppoland/plogins-minimum
* <strong>Fehlerberichte und Funktionswünsche</strong> - https://github.com/wppoland/plogins-minimum/issues


= Does it need WooCommerce? =
Ja. Minimum ist eine WooCommerce-Erweiterung und benötigt WooCommerce 8.0 oder höher aktiv. Fehlt WooCommerce, bleibt das Plugin inaktiv und zeigt einen Admin-Hinweis.

= What happens if two rules cover the same product? =
Jeder Wert wird einzeln aufgelöst. Für Min, Max und Schritt getrennt hat eine Produktregel Vorrang vor einer Kategorieregel, die wiederum Vorrang vor der globalen Regel hat. Ein Feld, das auf 0 bleibt, bedeutet, dass dieser Wert nicht erzwungen wird.

= Does it work with the block-based cart and checkout? =
Ja. Regeln werden am Warenkorbinhalt geprüft, nicht an einem bestimmten Template, sodass dieselben Hinweise erscheinen und der Checkout blockiert wird, egal ob du die klassischen Seiten oder die Warenkorb- und Kassenblöcke nutzt.

= Can I change the wording shown to customers? =
Ja. Jeder Hinweis hat ein eigenes Feld auf dem Einstellungsbildschirm. Token wie {min}, {max}, {step}, {product} und {total} werden durch die passenden Werte ersetzt.

= Can I turn rules off without deleting them? =
Ja. Der Durchsetzungs-Schalter auf dem Einstellungsbildschirm stoppt die Anwendung der Regeln im Warenkorb und an der Kasse, während sie gespeichert bleiben.

= What is left behind when I delete the plugin? =
Der Deinstallationsschritt löscht die Optionen `minimum_settings` und `minimum_db_version`. Es gibt keine eigenen Tabellen, also wird nichts weiteres in deiner Datenbank hinzugefügt oder zurückgelassen.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es netzwerkweit oder auf einzelnen Websites; jede Website behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Im Shop.
2. Einstellungen im WordPress-Adminbereich.
3. Auf einem mobilen Gerät.
== External Services ==

Minimum stellt keine Verbindung zu externen Diensten her. Regeln werden am Warenkorb auf deinem eigenen Server ausgewertet, und die einzigen gespeicherten Daten sind zwei WordPress-Optionen: `minimum_settings` (deine Regeln und Hinweistexte) und `minimum_db_version`. Das Plugin versendet keine E-Mails und erstellt keine eigenen Datenbanktabellen.

== Translations ==

Plogins Minimum enthält polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche. Die Textdomain ist `plogins-minimum`, sodass Sprachpakete von WordPress.org diese mitgelieferten Übersetzungen ebenfalls überschreiben oder erweitern können.

== Changelog ==

= 1.0.2 =
* Mitgelieferte polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche hinzugefügt.

= 1.0.1 =
* Erste stabile Version.

= 0.1.2 =
* Umbenannt in Plogins Minimum for WooCommerce für einen unverwechselbaren Plugin-Namen.

= 0.1.1 =
* Erweiterungsfilter `minimum/rules` und `minimum/min_order_total` für Add-ons wie Minimum Pro-Rollenregeln.
* Rollenbereich-Unterstützung in der Regel-Engine (`role`-Slug in Regelzeilen).

= 0.1.0 =
* Erstveröffentlichung. Mengenregeln pro Produkt, pro Kategorie und global (Min, Max, Schritt), eine Mindestbestellsumme und bearbeitbare Hinweismeldungen. Durchgesetzt beim Hinzufügen zum Warenkorb sowie im Warenkorb und an der Kasse. Kompatibel mit HPOS sowie den Warenkorb- und Kassenblöcken. Kein jQuery auf dem Einstellungsbildschirm.
