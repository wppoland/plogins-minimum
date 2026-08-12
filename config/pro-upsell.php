<?php
/**
 * PRO upsell content, generated from the plogins.com registry by
 * scripts/gen-pro-upsell.mjs. The admin upsell renders this; curate the
 * feature list to fit this plugin's settings screen (do not invent features).
 *
 * @package plogins-minimum-pro
 */

defined('ABSPATH') || exit;

return [
    'name'       => 'Minimum Pro',
    'url'        => 'https://plogins.com/plogins-minimum-pro/pricing/',
    'sellable'   => true,
    'price_from' => 29,
    'currency'   => 'EUR',
    'price_pln'  => 129,
    'lead'       => [
        'en' => 'All PRO features run on the free rules engine.',
        'pl' => 'Wszystkie funkcje PRO działają na darmowym silniku reguł.',
    ],
    'features'   => [
        [
            'en' => ['title' => 'Rules on the product editor', 'desc' => 'Set a product\'s min, max and step on its own Inventory tab instead of adding a product-scoped row to the rules table. The free plugin already supports per-product rules; this changes where you set them.'],
            'pl' => ['title' => 'Reguły w edytorze produktu', 'desc' => 'Ustawiasz min, max i krok na karcie produktu w zakładce Magazyn, zamiast dodawać regułę o zasięgu produktu w tabeli reguł. Darmowa wtyczka obsługuje już reguły per produkt; to zmienia miejsce, w którym je ustawiasz.'],
        ],
        [
            'en' => ['title' => 'Per-role rules', 'desc' => 'Different quantity rules and order totals for wholesale, trade and retail roles from one screen.'],
            'pl' => ['title' => 'Reguły per rola', 'desc' => 'Różne reguły ilości i minimalna wartość zamówienia dla ról hurt, handel i detal z jednego ekranu.'],
        ],
        [
            'en' => ['title' => 'Scheduled rules', 'desc' => 'Activate or relax quantity rules and minimum order totals over a date range on WooCommerce → Minimum Schedules.'],
            'pl' => ['title' => 'Reguły harmonogramowane', 'desc' => 'Włączaj lub luzuj reguły ilości i minimalną wartość zamówienia w zakresie dat na ekranie WooCommerce → Minimum Schedules.'],
        ],
        [
            'en' => ['title' => 'Bulk import', 'desc' => 'Import per-product min, max and step overrides from CSV on WooCommerce → Minimum Bulk Import.'],
            'pl' => ['title' => 'Import zbiorczy', 'desc' => 'Importuj nadpisania min, max i krok per produkt z CSV na ekranie WooCommerce → Minimum Bulk Import.'],
        ],
        [
            'en' => ['title' => 'Conditional rules', 'desc' => 'Apply rules only when the cart matches a subtotal, item-count, product-ID or category-ID condition on WooCommerce → Minimum Conditions.'],
            'pl' => ['title' => 'Reguły warunkowe', 'desc' => 'Stosuj reguły tylko gdy koszyk pasuje do warunku subtotalu, liczby sztuk, ID produktu lub ID kategorii - ekran WooCommerce → Minimum Conditions.'],
        ],
    ],
];
