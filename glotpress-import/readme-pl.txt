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

Ustaw reguły minimalnej, maksymalnej i krokowej ilości oraz minimalnej sumy zamówienia, egzekwowane w koszyku i przy kasie za pomocą powiadomień blokujących finalizację zakupu.

== Description ==

Minimum dodaje do Twojego sklepu WooCommerce reguły ilości i wydatków. Ty decydujesz, ile jednostek produktu musi kupić klient, ograniczasz, ile może kupić, sprzedajesz w stałych wielkościach opakowań i wymagasz minimalnej sumy zamówienia, zanim dozwolona będzie płatność. Reguły można ustawić dla pojedynczego produktu, całej kategorii lub wszystkich produktów naraz.

Wtyczka sprawdza koszyk po dodaniu pozycji i ponownie przy kasie. Jeśli reguła nie jest spełniona, klient widzi powiadomienie wyjaśniające, co zmienić, a finalizacja zakupu pozostaje zablokowana, dopóki tego nie poprawi.

Kod źródłowy i zgłoszenia błędów znajdziesz na GitHubie: https://github.com/wppoland/plogins-minimum

Co możesz skonfigurować:

* Minimalna ilość: wymagaj co najmniej N jednostek produktu przed finalizacją zakupu.
* Maksymalna ilość: ogranicz, ile jednostek klient może kupić.
* Ilość krokowa: dopuszczaj tylko wielokrotności, dla produktów sprzedawanych w opakowaniach (na przykład paczki po 6).
* Minimalna suma zamówienia: wymagaj sumy częściowej koszyka przed finalizacją zakupu.
* Treść powiadomień: edytuj komunikat pokazywany dla każdej niespełnionej reguły, używając tokenów takich jak {min}, {max}, {step}, {product} i {total}.

Gdy do produktu może pasować więcej niż jedna reguła, dla każdej wartości wygrywa ta bardziej szczegółowa: reguła produktu przeważa nad regułą kategorii, a reguła kategorii nad regułą globalną. Min, max i krok są rozstrzygane osobno, więc możesz je mieszać między zakresami.

Inne rzeczy, o których warto wiedzieć:

* Działa z HPOS (niestandardowe tabele zamówień) oraz blokami Koszyk i Kasa, a także z klasycznym koszykiem i kasą. Walidacja odczytuje zawartość koszyka, więc oba układy są objęte.
* Ekran ustawień trzyma się stylu kokpitu WordPressa, respektuje ciemny schemat kolorów i opisuje każde pole pod klawiaturę i czytniki ekranu.
* Brak własnych tabel bazy danych. Usunięcie wtyczki kasuje jej dwie opcje i nie dotyka reszty bazy danych.
* Brak dołączonych bibliotek i jQuery na ekranie ustawień.

== Installation ==

1. Zainstaluj i włącz WooCommerce 8.0 lub nowszy.
2. Prześlij folder `plogins-minimum` do `/wp-content/plugins/` lub zainstaluj archiwum zip z ekranu <strong>Wtyczki → Dodaj nową → Prześlij wtyczkę</strong>.
3. Włącz wtyczkę na ekranie <strong>Wtyczki</strong>.
4. Przejdź do <strong>WooCommerce → Minimum</strong> i dodaj regułę (na przykład produkt z minimalną ilością 3) albo ustaw minimalną sumę zamówienia.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-minimum/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-minimum/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-minimum
* <strong>Zgłoszenia błędów i propozycje funkcji</strong> - https://github.com/wppoland/plogins-minimum/issues


= Does it need WooCommerce? =
Tak. Minimum to rozszerzenie WooCommerce i wymaga aktywnego WooCommerce 8.0 lub nowszego. Jeśli WooCommerce nie jest dostępne, wtyczka pozostaje nieaktywna i pokazuje powiadomienie w kokpicie.

= What happens if two rules cover the same product? =
Każda wartość jest rozstrzygana osobno. Dla min, max i step z osobna reguła produktu zastępuje regułę kategorii, która zastępuje regułę globalną. Pole pozostawione na 0 oznacza, że ta wartość nie jest egzekwowana.

= Does it work with the block-based cart and checkout? =
Tak. Reguły są sprawdzane względem zawartości koszyka, a nie konkretnego szablonu, więc te same powiadomienia się pojawiają, a finalizacja zakupu jest blokowana niezależnie od tego, czy używasz klasycznych stron, czy bloków Koszyk i Kasa.

= Can I change the wording shown to customers? =
Tak. Każde powiadomienie ma własne pole na ekranie ustawień. Tokeny takie jak {min}, {max}, {step}, {product} i {total} są podstawiane odpowiednimi wartościami.

= Can I turn rules off without deleting them? =
Tak. Przełącznik Egzekwowanie na ekranie ustawień zatrzymuje stosowanie reguł w koszyku i przy kasie, zachowując je w zapisanych ustawieniach.

= What is left behind when I delete the plugin? =
Krok dezinstalacji usuwa opcje `minimum_settings` i `minimum_db_version`. Nie ma własnych tabel, więc nic więcej nie jest dodawane ani pozostawiane w Twojej bazie danych.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest zgodna z WordPress Multisite. Włącz ją dla całej sieci lub w pojedynczych witrynach; każda witryna zachowuje własne ustawienia i dane.

== Screenshots ==

1. W sklepie.
2. Ustawienia w kokpicie WordPressa.
3. Na urządzeniu mobilnym.
== External Services ==

Minimum nie łączy się z żadnymi usługami zewnętrznymi. Reguły są oceniane względem koszyka na Twoim własnym serwerze, a jedyne przechowywane dane to dwie opcje WordPress: `minimum_settings` (Twoje reguły i treść powiadomień) oraz `minimum_db_version`. Wtyczka nie wysyła e-maili i nie tworzy własnych tabel bazy danych.

== Translations ==

Plogins Minimum zawiera polskie, niemieckie i hiszpańskie tłumaczenie interfejsu wtyczki. Domena tekstowa to `plogins-minimum`, dzięki czemu paczki językowe z WordPress.org mogą również nadpisywać lub rozszerzać dołączone tłumaczenia.

== Changelog ==

= 1.0.2 =
* Dodano dołączone polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki.

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.2 =
* Zmieniono nazwę na Plogins Minimum for WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.1 =
* Filtry rozszerzeń `minimum/rules` i `minimum/min_order_total` na potrzeby dodatków, takich jak reguły ról w Minimum Pro.
* Obsługa zakresu ról w silniku reguł (slug `role` w wierszach reguł).

= 0.1.0 =
* Pierwsze wydanie. Reguły ilości na poziomie produktu, kategorii i globalnie (min, max, krok), minimalna suma zamówienia oraz edytowalne komunikaty powiadomień. Egzekwowane przy dodawaniu do koszyka oraz w koszyku i przy kasie. Zgodne z HPOS oraz blokami Koszyk i Kasa. Bez jQuery na ekranie ustawień.
