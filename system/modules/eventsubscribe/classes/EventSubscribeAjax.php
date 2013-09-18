<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */
 
//Klasse, um mit Ajax umzugehen
class EventSubscribeAjax extends \System{
	
	public function ajaxSelect()
	{
		if($this->Input->post('type') == 'ajaxsimple')
		{
			$this->import('Database');
			$res = $this->Database
						->prepare("SELECT * FROM tl_calendar_events WHERE id = ?")
						->limit(1)
						->execute((int)$this->Input->post('key'));
						
			if($res->count())
			{
				$result['title'] = $res->title;
				$result['startDate'] = date('d.m.Y', $res->startDate);
				$result['field_location_city'] = $res->location_city;
				$result['price'] = "";
				$result['price'] .= $res->price_whole.",";
				if($res->price_fraction <= 9){$result['price'] .= "0".$res->price_fraction;}else{$result['price'] .= $res->price_fraction;}
				$result['price_info'] = $res->price_info;
				$result['registrations'] = $res->registrations;
				if($res->maximum_number > 0){$result['maximum_number'] = $res->maximum_number;}else{$result['maximum_number'] = 'Unbeschränkt';}
			}else{
				$result['error'] = "Das angeforderte Event wurde nicht gefunden.";
			}			
			echo json_encode($result);
			exit;
		}
	}
}

?>