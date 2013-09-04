<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */

// Table tl_calendar_events_subscribe
$GLOBALS['TL_DCA']['tl_calendar_events_subscribe'] = array
(

	// Konfiguration
	'config' => array
	(
		'dataContainer'               => 'Table',
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',		
			)
		)
	),

	// List
	'list'		=> array
	(
		'sorting'	=> array
		(
			'mode'		=> 2,
			'fields'	=> array('eventId', 'name'),
			'flag'		=> 11,
			'panelLayout' => 'filter;sort,search,limit'
		),
		'label'		=> array
		(
			'fields' 	=> array('eventId','name'),
			'format'	=>	'%s - %s',
		),
		'global_operations'	=> array
		(
			'all'	=> array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_screencast']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_screencast']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show'   => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_screencast']['show'],
				'href'       => 'act=show',
				'icon'       => 'show.gif',
				'attributes' => 'style="margin-right:3px"'
			),
		)
	),
		
	// Palettes
	'palettes'	=> array
	(
		'default'	=>	'{title_legend}, name, street, zip, city, fon, email; {eventsubcribe_legend}, eventId'
	),
	
	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(11) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql' 					  => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'label'		=> $GLOBALS['TL_LANG']['tl_calendar_events_subscribe']['name'],
			'inputType'	=> 'text',
			'sorting'   => true,
			'flag'      => 1,
            'search'    => true,
			'sql'                     => "varchar(64) NOT NULL"
		),
		'street' => array
		(
			'label'		=> $GLOBALS['TL_LANG']['tl_calendar_events_subscribe']['street'],
			'inputType'	=> 'text',
			'sql'                     => "varchar(64) NOT NULL"
		),
		'zip' => array
		(
			'label'		=> $GLOBALS['TL_LANG']['tl_calendar_events_subscribe']['zip'],
			'inputType'	=> 'text',
			'sql'					  => "int(6) unsigned NOT NULL"
		),
		'city' => array
		(
			'label'		=> $GLOBALS['TL_LANG']['tl_calendar_events_subscribe']['city'],
			'inputType'	=> 'text',
			'sql'					  => "varchar(64) NOT NULL"
		),
		'fon' => array
		(
			'label'		=> $GLOBALS['TL_LANG']['tl_calendar_events_subscribe']['fon'],
			'inputType'	=> 'text',
			'sql'					  => "varchar(64) NOT NULL"
		),
		'email' => array
		(
			'label'		=> $GLOBALS['TL_LANG']['tl_calendar_events_subscribe']['email'],
			'inputType'	=> 'text',
			'sql' 					  => "varchar(64) NOT NULL"
		),
		'eventId' => array
		(
			'label'					=> $GLOBALS['TL_LANG']['tl_calendar_events_subscribe']['eventId'],
			'inputType'				=> 'select',
			'options_callback'		=> array('EventSubscribeDCA','eventlist_callback'),
			'sorting'   			=> true,
			'flag'      			=> 11,
			'sql'					=> "int(11) NOT NULL"
		)
	)
);

//Klassen und Methoden, die für den DCA relevant sind
class EventSubscribeDCA extends \Backend{

	public function eventlist_callback()
	{
		
		//Array als Rückgabe
		$arrReturn = array();
		
		//Auswählen aller Events
		$qry = "SELECT * FROM tl_calendar_events";
		$objRow = $this->Database->prepare($qry)->execute();
		
		while($objRow->next())
		{
			$arrReturn[$objRow->id] = $objRow->title;
		}
		
		return $arrReturn;
		
	}

}
