<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */

//BackEnd
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['__selector__'][] = 'useEventSubscribe';
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default'] = str_replace('jumpTo;','jumpTo; {title_EventSubscribe}, useEventSubscribe;', $GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_calendar']['subpalettes']['useEventSubscribe'] = 'subscribejumpTo, mailowner, mailsystem, mailsystemname, praemailtext, postmailtext';

/**
 * Jump zur Page mit dem Anmelde-Formular
 */

$GLOBALS['TL_DCA']['tl_calendar']['fields']['useEventSubscribe'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['useEventSubscribe'],
	'inputType'               => 'checkbox',
	'eval'                    => array('mandatory'=>false, 'isBoolean' => true, 'submitOnChange' => true),
	'sql'                     => "char(1) NOT NULL default ''"
); 
 
$GLOBALS['TL_DCA']['tl_calendar']['fields']['subscribejumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['subscribejumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'foreignKey'              => 'tl_page.title',
	'eval'                    => array('fieldType'=>'radio'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'",
	'relation'                => array('type'=>'hasOne', 'load'=>'eager')
);

/**
 * Bestätigungs-E-Mails
 */
 
$GLOBALS['TL_DCA']['tl_calendar']['fields']['mailowner'] = array
(
        'label'					=> $GLOBALS['TL_LANG']['tl_calendar']['mailowner'],
		'inputType'				=> 'text',
		'eval'					=>	array('maxlength'=>64, 'mandatory'=>true, 'rgxp'=>'email'),
		'sql' 					=> "varchar(64) NOT NULL"
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['mailsystem'] = array
(
        'label'					=> $GLOBALS['TL_LANG']['tl_calendar']['mailsystem'],
		'inputType'				=> 'text',
		'eval'					=>	array('maxlength'=>64, 'tl_class'=>'w50', 'mandatory'=>true, 'rgxp'=>'email'),
		'sql' 					=> "varchar(64) NOT NULL"
);

 $GLOBALS['TL_DCA']['tl_calendar']['fields']['mailsystemname'] = array
(
        'label'					=> $GLOBALS['TL_LANG']['tl_calendar']['mailsystemname'],
		'inputType'				=> 'text',
		'eval'					=>	array('maxlength'=>64, 'tl_class'=>'w50', 'mandatory'=>true),
		'sql' 					=> "varchar(64) NOT NULL"
);

 $GLOBALS['TL_DCA']['tl_calendar']['fields']['praemailtext'] = array
(
        'label'					=> $GLOBALS['TL_LANG']['tl_calendar']['praemailtext'],
		'inputType'				=> 'textarea',
		'sql' 					=> "text NOT NULL"
);

 $GLOBALS['TL_DCA']['tl_calendar']['fields']['postmailtext'] = array
(
        'label'					=> $GLOBALS['TL_LANG']['tl_calendar']['postmailtext'],
		'inputType'				=> 'textarea',
		'sql' 					=> "text NOT NULL"
);