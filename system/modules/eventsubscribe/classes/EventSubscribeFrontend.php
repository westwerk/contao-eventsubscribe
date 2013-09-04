<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */

class EventSubscribeFrontend extends \Module
{
	protected $strTemplate = 'ext_subscribe';
 
	protected function compile()
	{
		// Die FormId ermitteln, welche in dem Template eingebunden werden soll	 
		$this->import('Database');
		$row = $this->Database
					->prepare("SELECT tl_form.id FROM tl_module LEFT JOIN tl_form ON (tl_form.id = tl_module.eventsubscribe_form) WHERE tl_module.id = ?")
					->execute($this->Template->id);
		
		$this->Template->formId = $row->id;
		
		if(TL_MODE == 'FE')
		{
			$GLOBALS['TL_JAVASCRIPT']['ajaxselect'] = 'system/modules/eventsubscribe/assets/ajaxselect.js';
		}
	}
}

?>