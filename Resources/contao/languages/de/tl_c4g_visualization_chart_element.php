<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    6
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
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
    'backendtitle'      => ['Bezeichnung (nur Backend)', 'Dient zur eindeutigen Indentifikation im Backend.'],
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
    'tabley'            => ['Spalte für Y-Werte', 'Die Spalte, deren Werte als Y-Werte interpretiert werden.'],
    'general_legend'    => 'Allgemine Einstellungen',
    'type_origin_legend'     => 'Typ & Datenherkunft',
    'publish_legend'     => 'Veröffentlichung'
];
