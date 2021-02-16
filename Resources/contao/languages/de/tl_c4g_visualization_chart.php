<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */

$GLOBALS['TL_LANG']['tl_c4g_visualization_chart'] =
[
    'new'               => ['Neue Grafik hinzufügen', 'Neue Grafik hinzufügen'],
    'edit'              => ['Bearbeiten','Grafk bearbeiten'],
    'copy'              => ['Kopieren','Grafik kopieren'],
    'delete'            => ['Löschen','Grafik löschen'],
    'show'              => ['Anzeigen','Daten anzeigen'],
    'toggle'            => ['Veröffentlichen/verbergen','Grafik veröffentlichen/verbergen'],
    'all'               => ['Mehrere bearbeiten','Mehrere Grafiken bearbeiten'],
    'id'                => ['ID', ''],
    'backendtitle'      => ['Bezeichnung (nur Backend)', 'Dient zur eindeutigen Indentifikation im Backend.'],
    'frontendtitle'     => ['Titel (Frontend)', 'Dies wird im Frontend über der Grafik angezeigt, falls gesetzt.'],
    'published'         => ['Grafik veröffentlichen', 'Die Grafik auf der Webseite anzeigen.'],
    'zoom'              => ['Zoom aktivieren', 'Falls gesetzt, kann der Nutzer in die Grafik zoomen und den Ausschnitt verschieben.'],
    'points'            => ['Punkte darstellen', 'In einigen Diagrammen werden die X-Koordinaten als Punkte dargestellt. Diese Punkte lassen sich hier abschalten.'],
    'legend'            => ['Legende darstellen', 'Im Normalfall wird unterhalb der Grafik die Legende dargestellt. Diese kann hier deaktiviert werden.'],
    'labels'            => ['Label darstellen', 'Im Normalfall werden an den Grafikelementen selbst keine Label dargestellt. Das kann hiermit geändert werden.'],
    'swapAxes'          => ['Achsen vertauschen', 'Falls gesetzt, tauschen die X- und Y-Achsen die Positionen.'],
    'xType'             => ['X-Achsentyp', 'Definiert die Beschriftung der X-Werte.'],
    'xValueCharacter'   => ['Charakter der X-Werte', 'Gibt den Charakter der X-Werte an. Diese Auswahl beeinflusst das Formular und das Laden der Daten.'],
    'xTimeFormat'       => ['Zeitformat', 'Formatierungsstring für den Zeitstempel.'],
    'xLabelCount'       => ['Jedes X. Label anzeigen', 'Das wievielte Label soll unterhalb der X-Achse angezeigt werden. Standard: 1'],
    'xRotate'           => ['X-Achsenrotation', 'Rotiert die Beschriftung der X-Werte. Das Vorzeichen gibt die Rotationsrichtung an.'],
    'xshow'             => ['X-Achse anzeigen', 'Zeigt die X-Achse an oder nicht.'],
    'xLabelText'        => ['Bezeichnung der X-Achse', 'Definiert die Bezeichnung der X-Achse.'],
    'xLabelPosition'    => ['Position der Bezeichnung', 'Definiert die Position der Bezeichnung relativ zur Achse.'],
    'yLabelText'        => ['Bezeichnung der Y-Achse', 'Definiert die Bezeichnung der Y-Achse.'],
    'yLabelPosition'    => ['Position der Bezeichnung', 'Definiert die Position der Bezeichnung relativ zur Achse.'],
    'yshow'             => ['Y-Achse anzeigen', 'Zeigt die Y-Achse an oder nicht.'],
    'yInverted'         => ['Y-Achse invertieren', 'Falls gesetzt, beginnt die Y-Achse am oberen Rand statt am unteren.'],
    'y2show'            => ['Zweite Y-Achse anzeigen', 'Zeigt eine zweite Y-Achse an oder nicht.'],
    'y2LabelText'       => ['Bezeichnung der zweiten Y-Achse', 'Definiert die Bezeichnung der zweiten Y-Achse.'],
    'y2LabelPosition'   => ['Position der Bezeichnung', 'Definiert die Position der Bezeichnung relativ zur Achse.'],
    'y2Inverted'        => ['Zweite Y-Achse invertieren', 'Falls gesetzt, beginnt die zweite Y-Achse am oberen Rand statt am unteren.'],
    'elementWizard'     => ['Elemente', 'Die Grafikelemente, die in dieser Grafik angezeigt werden.'],
    'elementId'         => ['Element'],
    'image'             => ['Bilddatei', 'Die Bilddatei, auf dem das Wasserzeichen basiert.'],
    'imageMaxHeight'    => ['Maximale Bildhöhe (Pixel)', 'Das Seitenverhältnis wird beibehalten.'],
    'imageMaxWidth'     => ['Maximale Bildbreite (Pixel)', 'Das Seitenverhältnis wird beibehalten.'],
    'imageMarginTop'    => ['Oberer Abstand (Pixel)', 'Oberer Abstand relativ zum oberen Rand der Grafik.'],
    'imageMarginLeft'   => ['Linker Abstand (Pixel)', 'Linker Abstand relativ zum linken Rand der Grafik.'],
    'imageOpacity'      => ['Bildtransparenz (Prozent)', 'Bildtransparenz in Prozent.'],
    'rangeWizardNominal'=> ['Wertebereiche definieren', 'Jeder Wertebereich wird eigenständig angezeigt. Über Buttons kann zwischen den Wertebereichen gewechselt werden.'],
    'rangeWizardTime'   => ['Wertebereiche definieren', 'Jeder Wertebereich wird eigenständig angezeigt. Über Buttons kann zwischen den Wertebereichen gewechselt werden.'],
    'name'              => ['Bezeichnung', 'Der Text des Buttons.'],
    'fromX'             => ['Von', 'Ab diesem X-Wert (inklusiv) werden die Werte angezeigt.'],
    'toX'               => ['Bis', 'Bis zu diesem X-Wert (inklusiv) werden die Werte angezeigt.'],
    'defaultRange'      => ['Standard', 'Der Standardwertebereich. Sind mehr als einer angegeben, gilt der erste als Standard.'],
    'buttonAllCaption'  => ['Button für alle Werte', 'Falls dieses Feld nicht leer ist, wird ein Button eingeblendet, mit dem alle Werte gezeichnet werden, so als wären keine Wertebereiche definiert. Der Button hat den eingegebenen Text.'],
    'buttonAllPosition' => ['Position des Buttons für alle Werte', 'Definiert, ob der Button für alle Wertebereiche als erster oder letzter Button eingefügt wird.'],
    'buttonPosition'    => ['Position der Buttons', 'Definiert die Position der Buttons relativ zur Grafik.'],
    'loadOutOfRangeData'=> ['Daten laden, die außerhalb definierter Wertebereiche liegen', 'Bei großen Datenbeständen oder wenn nur die definierten Wertebereiche gezeigt werden sollen, ist es ggf. sinnvoll, das Laden der Daten zu beschränken.'],
    'general_legend'    => 'Allgemeine Einstellungen',
    'element_legend'    => 'Elemente',
    'color_legend'      => 'Farben',
    'watermark_legend'  => 'Wasserzeichen',
    'ranges_legend'     => 'Wertebereiche',
    'coordinate_system_legend'          => 'Koordinatensystem',
    'expert_legend'     => 'Experten-Einstellungen',
    'publish_legend'    => 'Veröffentlichung',
    'option_as_first'   => 'als erster',
    'option_as_last'    => 'als letzter',
    'option_top_left'   => 'Oben Links',
    'option_top_middle' => 'Oben Mittig',
    'option_top_right'  => 'Oben Rechts',
    'option_bottom_left' => 'Unten Links',
    'option_bottom_middle' => 'Unten Mittig',
    'option_bottom_right' => 'Unten Rechts',
    'option_inner_right_up' => 'innen rechts/oben',
    'option_inner_middle' => 'innen mittig',
    'option_inner_left_down' => 'innen links/unten',
    'option_outer_right_up' => 'außen rechts/oben',
    'option_outer_middle' => 'außen mittig',
    'option_outer_left_down' => 'außen links/unten',
    'option_nominal_values' => 'Nominalwerte',
    'option_temporal_values' => 'Zeitwerte'
];
