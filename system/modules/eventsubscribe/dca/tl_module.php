<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */
 
/**
 * Add palettes to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['module_eventsubscribe']    = '{title_legend},name,headline,type;{config_legend},eventsubscribe_form;';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['eventsubscribe_form'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['eventsubscribe_form'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'		  => array('EventSubscribeForms','eventforms_callback'),
	'eval'                    => array('mandatory'=>true),
	'sql'                     => "int(11) NULL"
);

//Klassen und Methoden, die für den DCA relevant sind
class EventSubscribeForms extends \Backend{

	public function eventforms_callback()
	{		
		//Array als Rückgabe
		$arrReturn = array();
		
		//Auswählen aller Events
		$objRow = $this->Database->prepare("SELECT * FROM tl_form WHERE eventsubscribe_use = 'yes'")->execute();
		
		while($objRow->next())
		{
			$arrReturn[$objRow->id] = $objRow->title;
		}
		
		return $arrReturn;
		
	}
}