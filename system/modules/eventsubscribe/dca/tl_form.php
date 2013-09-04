<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */
 
//Palettes
$GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'eventsubscribe_use';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['eventsubscribe_use_no'] = '';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['eventsubscribe_use_yes'] = 'eventsubscribe_mode';

//BackEnd
$GLOBALS['TL_DCA']['tl_form']['palettes']['default'] = str_replace('allowTags','allowTags,eventsubscribe_use', $GLOBALS['TL_DCA']['tl_form']['palettes']['default']);

//Abfragen, ob es ein EvenSubscribe Formular sein soll
$GLOBALS['TL_DCA']['tl_form']['fields']['eventsubscribe_use'] = array
(
	'label'		=>	$GLOBALS['TL_LANG']['tl_form']['eventsubscribe_use'],
	'inputType'	=>	'select',
	'options'	=>	array('no','yes'),
	'reference' =>	$GLOBALS['TL_LANG']['tl_form'],
	'eval'		=>	array(
		'submitOnChange'		=> true,
		'tl_class'				=> 'w50'
	),
	'sql'		=>	"varchar(12) NOT NULL default ''"
);

//Falls ja, genauen Modus wählen
$GLOBALS['TL_DCA']['tl_form']['fields']['eventsubscribe_mode'] = array
(
	'label'		=>	$GLOBALS['TL_LANG']['tl_form']['eventsubscribe_mode'],
	'inputType'	=>	'select',
	'default'	=>	0,
	'options'	=>	array(0,1,2,3),
	'reference' =>	$GLOBALS['TL_LANG']['tl_form'],
	'eval'		=>	array(
		'tl_class'				=> 'w50'
	),
	'sql'		=>	"int(8) NOT NULL default '0'"
);	