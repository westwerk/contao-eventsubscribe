<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */
 
class FormSelectEventMenu extends \Widget
{
	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;
	
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';
	
	/** #
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();
	
	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'EventOptions':
				$this->import('EventSubscribe');	//Import der Klasse				
				$arrOptions = $this->EventSubscribe->prepareOptions();
				$this->arrOptions = $arrOptions;
				break;
			
			case 'mSize':
				if ($this->multiple)
				{
					$this->arrAttributes['size'] = $varValue;
				}
				break;

			case 'multiple':
				if (strlen($varValue))
				{
					$this->arrAttributes[$strKey] = 'multiple';
				}
				break;

			case 'rgxp':
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}
	
	/**
	 * Return a parameter
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if ($strKey == 'options')
		{
			return $this->arrOptions;
		}

		return parent::__get($strKey);
	}
	
	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
	
		$strOptions = '';
		$strClass = 'select';

		if ($this->multiple)
		{
			$this->strName .= '[]';
			$strClass = 'multiselect';
		}
		
		//Optionen einfügen
		$this->import('EventSubscribe');	//Import der Klasse
		$arrOptions = $this->EventSubscribe->prepareOptions();
		$this->arrOptions = $arrOptions;
		
		// Add empty option (XHTML) if there are none
		if (empty($this->arrOptions))
		{
			$this->arrOptions = array(array('value'=>'', 'label'=>'-'));
		}
		
		foreach ($this->arrOptions as $arrOption)
		{
			$selected = '';
			if ((is_array($this->varValue) && in_array($arrOption['value'] , $this->varValue) || $this->varValue == $arrOption['value']) || $arrOption['value'] == \Input::get('eventId'))
			{
				$selected = ' selected="selected"';
			}
			
			$strOptions .= sprintf('<option value="%s"%s>%s</option>',
				$arrOption['value'],
				$selected,
				$arrOption['label']);
		}
		
		return sprintf('<select name="%s" id="ctrl_%s" class="%s%s"%s onChange="javascript:doAjSel(this.value);">%s</select>',
						$this->strName,
						$this->strId,
						$strClass,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$strOptions) . $this->addSubmit();
	
	}
}