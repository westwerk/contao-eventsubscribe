<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */

$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][] = array('tl_calendar_events_ext','isEventSubscribe');
 
//BackEnd
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace('endDate','endDate;{title_location},location_name,location_street,location_zip,location_city;{title_price},price_whole,price_fraction,price_info;', $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);
 
/*
 * Anmeldeschluss
 * Falls leer, wird einfach das Startdatum (exklusiv) des Events gewählt
 */

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['subscribe_endDate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['subscribe_endDate'],
			'default'                 => null,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'save_callback' 		  => array
			(
				array('tl_calendar_events_ext', 'setEmptyEndDate')
			),
			'sql'                     => "int(10) unsigned NULL"
); 
 
/**
 * Location
 */
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_name'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location']['name'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'tl_class'=>'w50'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_street'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location']['street'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'tl_class'=>'w50'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_zip'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location']['zip'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('rgxp'=>'digit', 'maxlength'=>6, 'tl_class'=>'w50'),
    'sql'               => "varchar(6) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_city'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location']['city'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'tl_class'=>'w50', 'mandatory'=>true),
    'sql'               => "varchar(255) NOT NULL default ''"
);

/**
 * Kosten
 */
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['price_whole'] = array
(
	'label'				=>	$GLOBALS['TL_LANG']['tl_calendar_events']['price_whole'],
	'exclude'			=>	true,
	'inputType'			=>	'text',
	'eval'				=>	array('maxlength'=>8, 'tl_class' => 'w50'),
	'sql'				=>	"int(8) NOT NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['price_fraction'] = array
(
	'label'				=>	$GLOBALS['TL_LANG']['tl_calendar_events']['price_fraction'],
	'exclude'			=>	true,
	'inputType'			=>	'text',
	'eval'				=>	array('maxlength'=>2, 'tl_class' => 'w50'),
	'sql'				=>	"int(2) NOT NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['price_info'] = array
(
    'label'             => $GLOBALS['TL_LANG']['tl_calendar_events']['price_info'],
    'exclude'           => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>128, 'tl_class' =>	'long'),
    'sql'               => "varchar(128) NOT NULL default ''"
);

/**
 * bestehende, störende Felder löschen (sollte sauberer gelöst werden...)
 */
 
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location'] = false;

class tl_calendar_events_ext extends \Backend
{

	/**
	 * Set the end date to null if empty
	 * @param mixed
	 * @return string
	 */
	public function setEmptyEndDate($varValue, DataContainer $dc)
	{
		if ($varValue === '')
		{
			$varValue = $dc->activeRecord->startDate;
		}

		return $varValue;
	}
	
	/* Prüfen, ob EventSubscribe im übergeordneten Kalendar aktiviert wurde */
	public function isEventSubscribe(){
		
		$this->import('Database');
		$isUsed = $this->Database
						->prepare('SELECT useEventSubscribe FROM tl_calendar AS tlc LEFT JOIN tl_calendar_events AS tlce ON (tlc.id = tlce.pid) WHERE tlce.id = ?')
						->limit(1)
						->execute($_GET['id']);
		
		if((int)$isUsed->useEventSubscribe){			
			$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace('endDate','endDate,subscribe_endDate',$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);
			return true;
		}else{
			return false;
		}
		
	}
}