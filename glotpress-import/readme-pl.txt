=== Plogins Minimum - Minimum Order for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, minimum order, quantity, order rules, minimum quantity
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Wymaga wtyczek: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ustaw reguły dotyczące minimalnej, maksymalnej i stopniowej ilości oraz minimalnej sumy zamówienia, egzekwowane przy koszyku i kasie z powiadomieniami blokującymi realizację transakcji.

== Description ==

Minimum dodaje zasady dotyczące ilości i wydatków do Twojego sklepu WooCommerce. Ty decydujesz, ile jednostek produktu musi kupić klient, ograniczasz liczbę, jaką może kupić, sprzedajesz w opakowaniach o stałych rozmiarach i wymagasz minimalnej sumy zamówienia, zanim będzie można dokonać płatności. Reguły można ustawić dla pojedynczego produktu, całej kategorii lub każdego produktu na raz.

Wtyczka sprawdza koszyk po dodaniu przedmiotu i ponownie przy kasie. Jeśli reguła nie jest spełniona, klient widzi powiadomienie wyjaśniające, co należy zmienić, a płatność pozostaje zablokowana do czasu naprawienia problemu.

Kod źródłowy i raporty o błędach dostępne na GitHubie: https://github.com/wppoland/plogins-minimum

Co możesz skonfigurować:

* Minimalna ilość: przed realizacją transakcji wymagane jest co najmniej N jednostek produktu.
* Maksymalna ilość: ograniczona liczba jednostek, które klient może kupić.
* Ilość kroków: dopuszczaj tylko wielokrotności w przypadku produktów sprzedawanych w opakowaniach (na przykład opakowania po 6 sztuk).
* Minimalna suma zamówienia: przed realizacją transakcji wymagana jest suma częściowa koszyka.
* Treść powiadomienia: zmodyfikuj komunikat wyświetlany dla każdej niespełnionej reguły, używając tokenów takich jak {min}, {max}, {step}, {product} i {total}.

Jeśli do produktu można zastosować więcej niż jedną regułę, w przypadku każdej wartości wygrywa ta bardziej szczegółowa: reguła produktu przewyższa regułę kategorii, a reguła kategorii przewyższa regułę globalną. Min., maks. i krok są ustalane osobno, więc można je mieszać w różnych zakresach.

Inne rzeczy, o których warto wiedzieć:

* Współpracuje z HPOS (tabele zamówień niestandardowych) oraz blokami Koszyk i Kasa, a także klasycznym koszykiem i kasą. Walidacja odczytuje zawartość koszyka, więc oba układy są uwzględnione.
* Ekran ustawień jest zgodny ze stylem administratora WordPressa, uwzględnia ciemną kolorystykę i oznacza każde pole do użycia za pomocą klawiatury i czytnika ekranu.
* Brak niestandardowych tabel bazy danych. Usunięcie wtyczki usuwa jej dwie opcje i pozostawia resztę bazy danych nietkniętą.
* Brak dołączonych bibliotek i jQuery na ekranie ustawień.

== Installation ==

1. Zainstaluj i aktywuj WooCommerce 8.0 lub nowszy.
2. Prześlij folder `plogins-minimum` do `/wp-content/plugins/` lub zainstaluj plik zip z ekranu <strong>Wtyczki → Dodaj nową → Prześlij wtyczkę<strong>. 3. Aktywuj wtyczkę na ekranie </strong>Wtyczki<strong>. 4. Przejdź do </strong>WooCommerce → Minimalne</strong> i dodaj regułę (na przykład produkt z minimalną ilością 3) lub ustaw minimalną sumę zamówienia.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-minimum/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-minimum/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-minimum
* <strong>Raporty o błędach i prośby o nowe funkcje</strong> - https://github.com/wppoland/plogins-minimum/issues


= Does it need WooCommerce? =
Tak. Minimum to rozszerzenie WooCommerce i wymaga aktywnego WooCommerce 8.0 lub nowszego. Jeśli brakuje WooCommerce, wtyczka pozostaje uśpiona i wyświetla powiadomienie administratora.

= What happens if two rules cover the same product? =
Każda wartość jest rozstrzygana samodzielnie. Dla wartości min, max i step oddzielnie reguła produktu zastępuje regułę kategorii, która zastępuje regułę globalną. Pole pozostawione na poziomie 0 oznacza, że ​​wartość nie jest wymuszana.

= Does it work with the block-based cart and checkout? =
Tak. Reguły są sprawdzane pod kątem zawartości koszyka, a nie konkretnego szablonu, więc pojawiają się te same powiadomienia, a realizacja transakcji jest blokowana niezależnie od tego, czy korzystasz z klasycznych stron, czy z bloków Koszyk i Kasa.

= Can I change the wording shown to customers? =
Tak. Każde powiadomienie ma swoje własne pole na ekranie ustawień. Tokeny takie jak {min}, {max}, {step}, {product} i {total} są zamieniane na pasujące wartości.

= Can I turn rules off without deleting them? =
Tak. Przełącznik Egzekwowanie na ekranie ustawień zatrzymuje stosowanie reguł przy koszyku i kasie, jednocześnie zachowując je.

= What is left behind when I delete the plugin? =
Krok dezinstalacji usuwa opcje `minimum_settings` i `minimum_db_version`. Nie ma żadnych niestandardowych tabel, więc nic więcej nie jest dodawane ani pozostawiane w Twojej bazie danych.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest kompatybilna z WordPress Multisite. Aktywuj go w sieci lub aktywuj na poszczególnych stronach; każda witryna przechowuje własne ustawienia i dane.

== Screenshots ==

1. Na wystawie sklepowej.
2. Ustawienia w panelu administracyjnym WordPress.
3. Na urządzeniu mobilnym.
== External Services ==

Minimum nie łączy się z żadnymi usługami zewnętrznymi. Reguły są porównywane z koszykiem na Twoim własnym serwerze, a jedyne przechowywane dane to dwie opcje WordPress, „minimum_settings” (twoje reguły i treść powiadomienia) oraz „minimum_db_version”. Wtyczka nie wysyła wiadomości e-mail i nie tworzy niestandardowych tabel bazy danych.

== Changelog ==

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.2 =
* Zmieniono nazwę na Plogins Minimum dla WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.1 =
* Rozszerzenia filtrują „minimum/rules” i „minimum/min_order_total” dla dodatków, takich jak zasady minimalnej roli Pro.
* Obsługa zakresu ról w silniku reguł (błąd „roli” w wierszach reguł).

= 0.1.0 =
* Pierwsze wydanie. Reguły dotyczące poszczególnych produktów, kategorii i globalnych ilości (min., maks., krok), minimalna suma zamówienia oraz edytowalne powiadomienia. Obowiązuje przy dodawaniu do koszyka, koszyku i kasie. Kompatybilny z HPOS oraz blokami Cart i Checkout. Brak jQuery na ekranie ustawień.
