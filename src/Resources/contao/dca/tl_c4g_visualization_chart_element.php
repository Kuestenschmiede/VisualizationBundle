<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\CheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\ColorPickerField;
use con4gis\CoreBundle\Classes\DCA\Fields\DatePickerField;
use con4gis\CoreBundle\Classes\DCA\Fields\DigitField;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\LabelField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiColumnField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SelectField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\VisualizationBundle\Classes\Charts\ChartElement;

$cbClass = \con4gis\VisualizationBundle\Classes\Callback\VisualizationChartElementCallback::class;

$dca = new DCA('tl_c4g_visualization_chart_element');
$dca->list()->sorting()->panelLayout('filter;sort,search,limit')
    ->headerFields(['id', 'backendtitle', 'frontendtitle', 'chartTitles']);
$dca->list()->label()->fields(['id', 'backendtitle', 'frontendtitle', 'chartTitles'])
    ->labelCallback($cbClass, 'getLabel');
$dca->list()->addRegularOperations($dca);
$dca->palette()->selector(['origin'])
    ->default('{general_legend},backendtitle,frontendtitle,color,redirectSite;'.
        '{type_origin_legend},type,xValueCharacter,origin;'.
        '{transform_legend},groupIdenticalX,minCountIdenticalX,yAxisSelection;'.
        '{expert_legend},tooltipExtension;'.
        '{publish_legend},published;')
    ->subPalette('origin', '1', 'inputWizard')
    ->subPalette('origin', '2', 'table,tablex,tablex2,tabley,whereWizard')
    ->subPalette('origin', '3', 'periodWizard');

$id = new IdField('id', $dca);
$tStamp = new NaturalField('tstamp', $dca);
$backendTitle = new TextField('backendtitle', $dca);
$backendTitle->search()->sorting();
$frontendTitle = new TextField('frontendtitle', $dca);
$frontendTitle->search()->sorting();
$chartTitles = new LabelField('chartTitles', $dca);
$chartTitles->exclude(false);
$color = new ColorPickerField('color', $dca);
$type = new SelectField('type', $dca);
$type->sql("varchar(10) NOT NULL default ''")->default("")
    ->optionsCallback($cbClass, 'loadTypeOptions')
    ->eval()->submitOnChange();
$origin = new SelectField('origin', $dca);
$origin->sql("char(1) NOT NULL default '2'")
    ->optionsCallback($cbClass, 'loadOriginOptions')
    ->default('2')
    ->eval()->submitOnChange();
$inputWizard = new MultiColumnField('inputWizard', $dca);
$inputWizard->saveCallback($cbClass, 'saveInput')
    ->loadCallback($cbClass, 'loadInput')
    ->eval()->doNotSaveEmpty();
$xInput = new TextField('xinput', $dca, $inputWizard);
$xInput->eval()->maxlength(21);
$yInput = new DigitField('yinput', $dca, $inputWizard);
$yInput->eval()->maxlength(21);
$table = new SelectField('table', $dca);
$table->optionsCallback($cbClass, 'loadTableNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
    ->doNotSaveEmpty()
    ->submitOnChange()
    ->includeBlankOption();
$tableX = new SelectField('tablex', $dca);
$tableX->optionsCallback($cbClass, 'loadColumnNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
        ->class('w50')
        ->doNotSaveEmpty();
$tableX2 = new SelectField('tablex2', $dca);
$tableX2->optionsCallback($cbClass, 'loadColumnNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
    ->class('w50')
    ->doNotSaveEmpty()
    ->includeBlankOption(true);
$tableY = new SelectField('tabley', $dca);
$tableY->optionsCallback($cbClass, 'loadColumnNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
        ->class('w50')
        ->doNotSaveEmpty();
$whereWizard = new MultiColumnField('whereWizard', $dca);
$whereWizard->saveCallback($cbClass, 'saveWhere')
    ->loadCallback($cbClass, 'loadWhere')
    ->eval()
    ->doNotSaveEmpty()
    ->class('clr');
$whereColumn = new SelectField('whereColumn', $dca, $whereWizard);
$whereColumn->optionsCallback($cbClass, 'loadColumnNames')
    ->default('');
$whereComparison = new SelectField('whereComparison', $dca, $whereWizard);
$whereComparison->optionsCallback($cbClass, 'loadComparisonOptions');
$whereValue = new TextField('whereValue', $dca, $whereWizard);
$whereValue->eval()->regEx('alnum');
$groupIdenticalX = new CheckboxField('groupIdenticalX', $dca);
$minCountIdenticalX = new DigitField('minCountIdenticalX', $dca);
$minCountIdenticalX->default('1');
$minCountIdenticalX->sql("int(10) NOT NULL default '1'");

$periodWizard = new MultiColumnField('periodWizard', $dca);
$periodWizard->saveCallback($cbClass, 'savePeriod')
    ->loadCallback($cbClass, 'loadPeriod');
$fromX = new DatePickerField('fromX', $dca, $periodWizard);
$toX = new DatePickerField('toX', $dca, $periodWizard);
$yinput = new TextField('yinput', $dca, $periodWizard);


$published = new CheckboxField('published', $dca);
$published->default(true);

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");
$importId->eval()->doNotCopy(true);

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart_element']['fields']['redirectSite'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['redirectSite'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('mandatory'=>false, 'fieldType'=>'radio', 'tl_class'=>'long clr'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'eager')
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart_element']['fields']['yAxisSelection'] = [
    'inputType' => "select",
    'default' => 'y1',
    'options' => ['y1', 'y2'],
    'eval' => ['tl_class' => "clr"],
    'sql' => "varchar(20) NOT NULL DEFAULT 'y1'"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart_element']['fields']['tooltipExtension'] = [
    'inputType' => "textarea",
    'default' => '',
    'eval' => ['tl_class' => "clr", 'rte' => 'tinyMCE', 'allowHtml' => true, 'preserveTags' => true],
    'sql' => 'text NULL'
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart_element']['list']['sorting']['fields'] = ['id', 'backendtitle'];

$xValueCharacter = new SelectField('xValueCharacter', $dca);
$xValueCharacter->optionsCallback($cbClass, 'loadXValueCharacterOptions')
    ->eval()->submitOnChange();
