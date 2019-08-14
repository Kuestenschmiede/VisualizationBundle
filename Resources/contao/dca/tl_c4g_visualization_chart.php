<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');
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

/**
 * Table tl_c4g_visualization_chart
 */
$GLOBALS['TL_DCA']['tl_c4g_visualization_chart'] = array
(

	// Config
	'config' => array
	(
	    'label'                       => $GLOBALS['TL_CONFIG']['websiteTitle'],
	    'dataContainer'               => 'Table',
		'enableVersioning'            => true,
//	    'onload_callback'			  => array(array('tl_c4g_visualization_chart', 'updateDCA')),
//	    'onsubmit_callback'           => array(array('tl_c4g_visualization_chart', 'onSubmit')),
//		'ondelete_callback'			  => array(array('tl_c4g_visualization_chart', 'onDeleteForum')),
        'sql'                         => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )

	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'fields'                  => array('name'),
			'flag'                    => 1
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
            'label_callback'          => array('tl_c4g_visualization_chart','get_label')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			),
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_c4g_visualization_chart', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('define_groups','define_rights','enable_maps','map_type'),
		'default'                     => '{general_legend},name,headline,description,published;'.
                                         '{language_legend:hide},optional_names,optional_headlines,optional_descriptions;'.
										 '{comfort_legend},box_imagesrc;'.
										 '{intropage_legend:hide},use_intropage;'.
										 '{infotext_legend:hide},pretext,posttext;'.
										 '{additional_legend:hide},tags;'.
										 '{groups_legend:hide},define_groups;'.
										 '{rights_legend:hide},define_rights;'.
										 '{expert_legend:hide},linkurl,link_newwindow,sitemap_exclude;mail_subscription_text',
	),

	// Fields
	'fields' => array
	(
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'threads' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'posts' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'last_thread_id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'last_post_id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'enable_maps_inherited' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['name'],
			'exclude'                 => true,
			'inputType'               => 'text',
            'search'                  => 'true',
			'sorting'                 => 'true',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'optional_names' => array
        (
            'label'			=> &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_names'],
            'exclude' 		=> true,
            'inputType'     => 'multiColumnWizard',
            'eval' 			=> array
            (
                'columnFields' => array
                (
                    'optional_name' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_name'],
                        'exclude'               => true,
                        'inputType'             => 'text',
                        'eval' 			        => array('tl_class'=>'w50')
                    ),
                    'optional_language' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_language'],
                        'exclude'               => true,
                        'inputType'             => 'select',
                        'options'               => \System::getLanguages(),
                        'eval'                  => array('chosen' => true, 'style'=>'width: 200px')
                    )
                )
            ),

            'sql' => "blob NULL"
        ),
        'headline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['headline'],
			'exclude'                 => true,
			'search'                  => true,
            'default'                 => array('value'=>'', 'unit'=>'h1'),
			'inputType'               => 'inputUnit',
			'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
			'eval'                    => array('maxlength'=>200, 'tl_class'=>'long clr'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'optional_headlines' => array
        (
            'label'			=> &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_headlines'],
            'exclude' 		=> true,
            'inputType'     => 'multiColumnWizard',
            'eval' 			=> array
            (
                'columnFields' => array
                (
                    'optional_headline' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_headline'],
                        'exclude'               => true,
                        'default'               => array('value'=>'', 'unit'=>'h1'),
                        'inputType'             => 'inputUnit',
                        'options'               => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
                        'eval'                  => array('maxlength'=>200, 'style'=>'width: 250px'),
                    ),
                    'optional_headline_language' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_language'],
                        'exclude'               => true,
                        'inputType'             => 'select',
                        'options'               => \System::getLanguages(),
                        'eval'                  => array('chosen' => true, 'style'=>'width: 200px')
                    )
                )
            ),

            'sql' => "blob NULL"
        ),
		'description' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['description'],
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'                  => array('style' => 'height:60px', 'tl_class'=>'long clr'),
            'sql'                   => "blob NULL"
		),
        'optional_descriptions' => array
        (
            'label'			=> &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_descriptions'],
            'exclude' 		=> true,
            'inputType'     => 'multiColumnWizard',
            'eval' 			=> array
            (
                'columnFields' => array
                (
                    'optional_description' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_description'],
                        'exclude'               => true,
                        'inputType'             => 'textarea',
                        'eval' 			        => array('tl_class'=>'w50')
                    ),
                    'optional_description_language' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['optional_language'],
                        'exclude'               => true,
                        'inputType'             => 'select',
                        'options'               => \System::getLanguages(),
                        'eval'                  => array('chosen' => true, 'tl_class'=>'w50', 'style'=>'width: 200px')
                    )
                )
            ),

            'sql' => "blob NULL"
        ),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['published'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'box_imagesrc' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['box_imagesrc'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'extensions'=>'gif,jpg,jpeg,png,svg', 'tl_class'=>'clr', 'mandatory'=>false),
            'sql'                     => "binary(16) NULL"
		),


		'use_intropage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['use_intropage'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'intropage' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['intropage'],
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
		),

		'intropage_forumbtn' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['intropage_forumbtn'],
			'exclude'               => true,
			'default'               => '',
			'inputType'             => 'text',
			'eval'                  => array('maxlength'=>100 ),
            'sql'                   => "varchar(100) NOT NULL default ''"
		),

		'intropage_forumbtn_jqui' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['intropage_forumbtn_jqui'],
			'exclude'               => true,
			'default'               => true,
			'inputType'             => 'checkbox',
            'sql'                   => "char(1) NOT NULL default '1'"
		),

		'pretext' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['pretext'],
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
		),

		'posttext' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['posttext'],
            'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
		),

		'define_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['define_groups'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'member_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['member_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'admin_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['admin_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'define_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['define_rights'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),


		'guest_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['guest_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('tl_c4g_visualization_chart','getGuestRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'member_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['member_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('tl_c4g_visualization_chart','getRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'admin_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['admin_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('tl_c4g_visualization_chart','getRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'enable_maps' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['enable_maps'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'eval'					  => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'map_type' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_type'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('EDIT','PICK','OSMID'),
            'default'                 => 'EDIT',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['references'],
            'eval'					  => array('submitOnChange'=>true),
            'sql'                     => "char(5) NOT NULL default 'PICK'"
		),

		'map_override_locationstyle' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_override_locationstyle'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'eval'					  => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'map_override_locstyles' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_override_locstyles'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'options_callback'        => array('tl_c4g_visualization_chart','getAllLocStyles'),
            'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'map_id' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_id'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('tl_c4g_visualization_chart', 'get_maps'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		'map_location_label' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_location_label'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>20 ),
            'sql'                     => "char(20) NOT NULL default ''"
		),

		'map_label' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_label'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','SUBJ','LINK','CUST'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
        ),

		'map_tooltip' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_tooltip'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','SUBJ','LINK','CUST'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
		),

		'map_popup' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_popup'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','SUBJ','POST','SUPO'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
		),

		'map_link' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['map_link'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','POST','THREA','PLINK'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
		),

		'linkurl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['linkurl'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'wizard'),
			'wizard' 				  => array(array('tl_c4g_visualization_chart', 'pickLinkUrl')),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),

		'link_newwindow' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['link_newwindow'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'sitemap_exclude' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['sitemap_exclude'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
		),
		'tags' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['tags'],
            'search'				  => true,
            'inputType'               => 'text',
            'load_callback'           => array("tl_c4g_visualization_chart" => "decodeTags"),
            'eval'                    => array(),
            'sql'                     => "blob NULL"
		),
        'default_author' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['default_author'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.username',
            'sql'                     => "int(10) default '0'"
		),
        'member_id' => array
        (
            'sql'                     =>'int(10) default "0"'
        ),
        'concerning' => array
        (
            'sql'                     =>'int(10) default "0"'
        )
	)
);

/**
 * Class tl_c4g_visualization_chart
 */
class tl_c4g_visualization_chart extends \Backend
{



}
