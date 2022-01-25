<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element'] = [
    'new'               => ['Neues Grafikelement hinzufügen', 'Neues Grafikelement hinzufügen'],
    'edit'              => ['Bearbeiten','Grafikelement bearbeiten'],
    'copy'              => ['Kopieren','Grafikelement kopieren'],
    'delete'            => ['Löschen','Grafikelement löschen'],
    'show'              => ['Anzeigen','Grafikelement anzeigen'],
    'toggle'            => ['Veröffentlichen/verbergen','Grafikelement veröffentlichen/verbergen'],
    'all'               => ['Mehrere bearbeiten','Mehrere Grafikelemente bearbeiten'],
    'id'                => ['ID', ''],
    'backendtitle'      => ['Bezeichnung (nur Backend)', 'Dient zur eindeutigen Identifikation im Backend.'],
    'frontendtitle'     => ['Titel (Frontend)', 'Anzeige in der Legende.'],
    'chartTitles'       => ['Grafiken', ''],
    'published'         => ['Grafikelement veröffentlichen', 'Das Grafikelement auf der Webseite anzeigen.'],
    'origin'            => ['Herkunft', 'Wie die Daten, die diesem Element zugeordnet sind, geladen werden sollen.'],
    'color'             => ['Farbe', 'Die Farbe, in der das Element dargestellt wird.'],
    'type'              => ['Elementtyp', 'Die Darstellungsform der Daten. Bachten Sie, dass manche Typen nicht miteinander kompatibel sind.'],
    'inputWizard'       => ['Eingaben', 'X- und Y-Wertepaare, die geladen werden. Beliebig viele Paare sind zulässig. Werden die Daten regelmäßig erweitert, sollten sie in einer eigenen Tabelle in der Datenbank gespeichert werden.'],
    'xinput'            => ['X-Wert', 'Bis zu zehn Stellen jeweils vor und hinter dem Dezimaltrennzeichen. Sowohl Komma als auch Punkt sind als Trennzeichen erlaubt. Im Falle eines Kuchendiagramms werden die X-Werte ignoriert.'],
    'yinput'            => ['Y-Wert', 'Bis zu zehn Stellen jeweils vor und hinter dem Dezimaltrennzeichen. Sowohl Komma als auch Punkt sind als Trennzeichen erlaubt. Im Falle eines Kuchendiagramms bestimmt sich die Größe des Kuchenstücks aus der Summe der Y-Werte.'],
    'yinputPie'         => ['Wert', 'Relativer Wert. Die Größe des Stücks brechnet sich anteilig aus der Summer der Werte aller Stücke. Bis zu zehn Stellen jeweils vor und hinter dem Dezimaltrennzeichen. Sowohl Komma als auch Punkt sind als Trennzeichen erlaubt.'],
    'table'             => ['Tabelle', 'Die Tabelle, aus der die Daten geladen werden.'],
    'tablex'            => ['Spalte für X-Werte', 'Die Spalte, deren Werte als X-Werte interpretiert werden.'],
    'tablex2'           => ['Spalte für Bereichsende (X-Werte)', 'Die Spalte, deren Wert als Endwert für einen Wertebereich interpretiert wird (X-Achse).'],
    'tabley'            => ['Spalte für Y-Werte', 'Die Spalte, deren Werte als Y-Werte interpretiert werden.'],
    'whereWizard'       => ['Bedingungen', 'Bedingungen für das Laden der Daten.'],
    'whereColumn'       => ['Spalte', 'Die Spalte, dessen Wert abgefragt wird.'],
    'whereComparison'   => ['Vergleich', 'Der Vergleich, der durchgeführt wird.'],
    'whereValue'        => ['Wert', 'Der Wert, mit dem verglichen wird.'],
    'periodWizard'      => ['Zeiträume', 'Hier können die Zeiträume festgelegt werden (gantt).'],
    'fromX'             => ['Zeitraum Beginn', 'Ab diesem X-Wert (inklusiv) werden die Werte angezeigt.'],
    'toX'               => ['Zeitraum Ende', 'Bis zu diesem X-Wert (inklusiv) werden die Werte angezeigt.'],
    'groupIdenticalX'   => ['Identische X-Werte gruppieren', 'Liegen mehrere Y-Werte für den gleichen X-Wert vor, werden die Werte addiert und die Summe statt der individuellen Werte angezeigt.'],
    'minCountIdenticalX' => ['Min. Anzahl für Gruppierung', 'Identische Werte werden erst ab der angegebenen Anzahl dargestellt.'],
    'redirectSite'      => ['Weiterleitungsseite', 'Beim Klick auf das Element soll auf die ausgewählte Seite weitergeleitet werden.'],
    'yAxisSelection'      => ['Y-Achsen-Auswahl', 'Auf welcher Y-Achse sollen die Werte dieses Elements dargestellt werden?'],
    'tooltipExtension'      => ['Eigener Tooltip-Inhalt', 'Zusätzlicher Inhalt für den Tooltip dieses Elements (unterstützt Insert-Tags).'],
    
    'general_legend'    => 'Allgemeine Einstellungen',
    'expert_legend'    => 'Experten-Einstellungen',
    'type_origin_legend'     => 'Typ & Datenherkunft',
    'transform_legend'    => 'Datentransformation',
    'publish_legend'    => 'Veröffentlichung',
    
    'option_equal'      => 'gleich',
    'option_greater_or_equal' => 'größer oder gleich',
    'option_lesser_or_equal' => 'kleiner oder gleich',
    'option_not_equal' => 'ungleich',
    'option_greater' => 'größer als',
    'option_lesser' => 'kleiner als',
    'option_input' => 'Eingabe',
    'option_load_from_table' => 'Aus Tabelle laden',
    'option_period' => 'Zeiträume (gantt)',
    'option_line' => 'Linie',
    'option_area_line' => 'Linie (Bereich)',
    'option_spline' => 'Kurve',
    'option_area_spline' => 'Kurve (Bereich)',
    'option_pie' => 'Kuchen / Torte',
    'option_donut' => 'Ring / Donut',
    'option_gauge' => 'Manometer',
    'option_bar' => 'Balken / Säulen',
    'option_gantt' => 'Gantt (Zeitlinie)'
];
