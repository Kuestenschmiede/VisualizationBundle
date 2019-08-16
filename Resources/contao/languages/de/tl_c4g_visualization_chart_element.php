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

$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element'] = array
(
    'new'               => array('Neues Grafikelement hinzufügen', 'Neues Grafikelement hinzufügen'),
    'edit'              => array('Bearbeiten','Grafikelement bearbeiten'),
    'copy'              => array('Kopieren','Grafikelement kopieren'),
    'delete'            => array('Löschen','Grafikelement löschen'),
    'show'              => array('Anzeigen','Grafikelement anzeigen'),
    'toggle'            => array('Veröffentlichen/verbergen','Grafikelement veröffentlichen/verbergen'),
    'all'               => array('Mehrere bearbeiten','Mehrere Grafikelemente bearbeiten'),
    'backendtitle'      => array('Bezeichnung (nur Backend)', 'Dient zur eindeutigen Indentifikation im Backend.'),
    'frontendtitle'     => array('Titel (Frontend)', 'Anzeige in der Legende.'),
    'published'         => array('Grafikelement veröffentlichen', 'Das Grafikelement auf der Webseite anzeigen.'),
    'origin'            => array('Herkunft', 'Wie die Daten, die diesem Element zugeordnet sind, geladen werden sollen.'),
    'color'             => array('Farbe', 'Die Farbe, in der das Element dargestellt wird.'),
    'type'              => array('Elementtyp', 'Die Darstellungsform der Daten. Bachten Sie, dass manche Typen nicht miteinander kompatibel sind.'),
    'inputWizard'       => array('Eingaben', 'X- und Y-Wertepaare, die geladen werden. Beliebig viele Paare sind zulässig.'),
    'xinput'            => array('X-Wert', 'Bis zu zehn Stellen jeweils vor und hinter dem Dezimaltrennzeichen. Sowohl Komma als auch Punkt sind als Trennzeichen erlaubt. Im Falle eines Kuchendiagramms werden die X-Werte ignoriert.'),
    'yinput'            => array('Y-Wert', 'Bis zu zehn Stellen jeweils vor und hinter dem Dezimaltrennzeichen. Sowohl Komma als auch Punkt sind als Trennzeichen erlaubt. Im Falle eines Kuchendiagramms bestimmt sich die Größe des Kuchenstücks aus der Summe der Y-Werte.'),
    'yinputPie'         => array('Wert', 'Relativer Wert. Die Größe des Stücks brechnet sich anteilig aus der Summer der Werte aller Stücke. Bis zu zehn Stellen jeweils vor und hinter dem Dezimaltrennzeichen. Sowohl Komma als auch Punkt sind als Trennzeichen erlaubt.'),
    'table'             => array('Tabelle', 'Die Tabelle, aus der die Daten geladen werden.'),
    'tablex'            => array('Spalte für X-Werte', 'Die Spalte, deren Werte als X-Werte interpretiert werden.'),
    'tabley'            => array('Spalte für Y-Werte', 'Die Spalte, deren Werte als Y-Werte interpretiert werden.'),
    'general_legend'    => 'Allgemine Einstellungen',
    'type_origin_legend'     => 'Typ & Datenherkunft',
    'publish_legend'     => 'Veröffentlichung'
);
