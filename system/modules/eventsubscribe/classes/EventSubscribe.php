<?php

/**	
 * 
 * Copyright (C) 2013 Thomas Belkowski / WESTWERK GmbH & Co. KG
 * 
 * @package eventssubsribe
 * @author  Thomas Belkowski / WESTWERK GmbH & Co. KG
 * @license LGPL
 */

class EventSubscribe extends \System
{	
	/*
	 *	Hook: myProcessFormData, speichert die eingegebenen Daten und verschickt E-Mails an Absender und an den Besitzer
	 *	Vorausgesetzt wird, dass über den Formulargenerator ein Formular mit folgenden Feldern erstellt wurden:
	 *	Feldnamen: name, street, zip, city, fon, email, eventId
	 *	
	 *	Da wir den integrierten Formulargenerator nutzen, kann selber bestimmt werden, bei welchen Feldern es sich um Pflichtfelder handelt.
	 *	Zusätzlich ist die Eingabevalidierung damit bereits integriert.
	 */	
	public function myProcessFormData($arrPost, $arrForm, $arrFiles)
	{
		$this->import('Database');
		
		$form = $this->Database
					->prepare("SELECT * FROM tl_form WHERE eventsubscribe_use = 'yes' AND id = ?")
					->limit(1)
					->execute($arrForm['id']);
			
		if($form->count())
		{	
			//Daten in die DB speichern
			$this->Database
				->prepare("INSERT INTO tl_calendar_events_subscribe %s")
				->set(array(
					'tstamp' => time(),
					'name' => $arrPost['name'],
					'street' => $arrPost['street'],
					'zip' => $arrPost['zip'],
					'city' => $arrPost['city'],
					'fon' => $arrPost['fon'],
					'email' => $arrPost['email'],
					'eventId' => $arrPost['eventId']
				))
				->execute();				
			
			//Aus der eventId das richtige Event ermitteln (für benutzerfreundlichere Ausgaben)
			$event = $this->Database
						->prepare("SELECT * FROM tl_calendar_events WHERE id = ?")
						->limit(1)
						->execute($arrPost['eventId']);
			
			//E-Mail Meta-Daten aus der DB lesen.
			$metamail = $this->Database
						->prepare("SELECT mailsystem, mailsystemname, mailowner, praemailtext, postmailtext FROM tl_calendar_events LEFT JOIN tl_calendar ON (tl_calendar.id = tl_calendar_events.pid) WHERE tl_calendar_events.id = ?")
						->limit(1)
						->execute($arrPost['eventId']);
					
			//Versenden einer E-Mail mit allen Daten an den Besitzer
			$newMailOwner = new Email();
			$newMailOwner->fromName = $metamail->mailsystemname;		
			$newMailOwner->from = $metamail->mailsystem;			
			$newMailOwner->subject = 'Anmeldung für das Event: '.$event->title;
			$newMailOwner->text = "Sie haben eine neue Anmeldung für das folgende Event:\r\r".$event->title."\r\rName: ".$arrPost['name']."\rStraße: ".$arrPost['street']."\rPLZ: ".$arrPost['zip']."\rStadt: ".$arrPost['city']."\rTelefon: ".$arrPost['fon']."\rE-Mail: ".$arrPost['email'];
			$newMailOwner->sendTo($metamail->mailowner);							
			// wir räumen im Speicher vom Server auf und löschen die Ressourcen 
			unset($newMailOwner); 			
			
			//Versenden einer Bestätigungs-E-Mail an den Absender
			$confirmation = new Email();
			$confirmation->fromName = $metamail->mailsystemname;
			$confirmation->from = $metamail->mailsystem;			
			$confirmation->subject = 'Erfolgreiche Anmeldung für: '.$event->title.'';
			$confirmation->text = $metamail->praemailtext;
			$confirmation->text .= "\r\rDie wichtigsten Informationen im Überblick:\r".$event->title."\rStart: ".date('d.m.Y', $event->startDate)."\rOrt: ".$event->location_city."\r";
			$confirmation->text .= "\rIhre angegebenen Daten lauten:\rName: ".$arrPost['name']."\rStraße: ".$arrPost['street']."\rPLZ: ".$arrPost['zip']."\rStadt: ".$arrPost['city']."\rTelefon: ".$arrPost['fon']."\r\r";
			$confirmation->text .= $metamail->postmailtext;
			$confirmation->sendTo($arrPost['email']);
			// wir räumen im Speicher vom Server auf und löschen die Ressourcen 
			unset($confirmation);			
		}else{
			//Dieses Formular nutzt nicht EventSubscribe. Tschüss!
			return;
		}
	}
	
	public function myValidateFormField(Widget $objWidget, $intId){
		 
		$this->import('Database');
		$form = $this->Database
					->prepare("SELECT * FROM tl_form WHERE eventsubscribe_use = 'yes' AND id = ?")
					->limit(1)
					->execute(substr($intId,10)); //die ersten 10 Zeichen sind: auto_form_
					
		if($form->count())		
		{			
			switch($form->eventsubscribe_mode){		
				case 1:
				if($objWidget->name == 'name')
				{
					//Prüfen, ob dieser Name bereits mit der entsprechenden Id vorhanden ist 
					$objTest = $this->Database->prepare("SELECT * FROM tl_calendar_events_subscribe WHERE name=? AND eventId=?")
								->limit(1)                        
								->execute(\Input::post('name'), \Input::post('eventId'));
					if ($objTest->count())
					{
						$objWidget->addError('Dieser Nutzer ist bereits angemeldet.');							
					} 																		
				}
				break;
					
				case 2:
				if($objWidget->name == 'email')
				{
					//Prüfen, ob diese Email bereits mit der entsprechenden Id vorhanden ist 
					$objTest = $this->Database->prepare("SELECT * FROM tl_calendar_events_subscribe WHERE email=? AND eventId=?")
								->limit(1)                        
								->execute(\Input::post('email'), \Input::post('eventId'));
					if ($objTest->count())
					{
						$objWidget->addError('Diese E-Mail wurde bereits angemeldet.');
					} 																		
				}
				break;
					
				case 3:
				if(($objWidget->name == 'name') || ($objWidget->name == 'email'))
				{
					//Prüfen, ob dieser Name + diese Email bereits mit der entsprechenden Id vorhanden ist 
					$objTest = $this->Database->prepare("SELECT * FROM tl_calendar_events_subscribe WHERE name=? AND email=? AND eventId=?")
								->limit(1)                        
								->execute(\Input::post('name'), \Input::post('email'), \Input::post('eventId'));
					if ($objTest->count())
					{
						$objWidget->addError('Diese Kombination aus Nutzer & E-Mail ist bereits angemeldet.');							
					} 																		
				}
				break;
			}
		}
		return $objWidget;		 
	}
	
	public function myReplaceInsertTags($strTag)
    {		
		$arrSplit = explode('::', $strTag);
		if($arrSplit[0] == 'subscribe')
		{				
			switch($arrSplit[1])
			{			
				case 'event':
				//ermittel den Event-Titel aus der DB
				$this->import('Database');
				$row = $this->Database
							->prepare("SELECT * FROM tl_calendar_events WHERE id = ?")
							->limit(1)
							->execute($_SESSION['FORM_DATA']['eventId']);
				return $row->title;
				break;
				
				default:
				//Felder auslesen, die im Formular ausgefüllt wurden
				if(isset($_SESSION['FORM_DATA'][$arrSplit[1]])) {
					return $_SESSION['FORM_DATA'][$arrSplit[1]];
				}
				break;
			}
		}else if($arrSplit[0] == 'subscribejumpTo'){
			//Die eventId steht damit in $arrSplit[1]
			$this->import('Database');
			$row = $this->Database
						->prepare("SELECT subscribejumpTo FROM tl_calendar_events LEFT JOIN tl_calendar ON (tl_calendar.id = tl_calendar_events.pid) LEFT JOIN tl_page ON (tl_page.id = tl_calendar.subscribejumpTo) WHERE tl_calendar_events.id = ?")
						->limit(1)
						->execute($arrSplit[1]);
			
			return $row->subscribejumpTo;
		}
		return false;
	}

	public function prepareOptions()
	{
		$arrOptions = array();
		
		//DB abfrage mit allen (aktuellen und buchbaren) Terminen. Wobei Termine als buchbar definiert sind, wenn sie in dem übergeordneten Kalender useEventSubscribe eingestellt haben. 
		$this->import('Database');
		$allevents = $this->Database
						->prepare("SELECT tlce.id, tlce.title FROM tl_calendar_events AS tlce LEFT JOIN tl_calendar AS tlc ON (tlce.pid = tlc.id) WHERE (UNIX_TIMESTAMP(NOW()) < subscribe_endDate) AND (useEventSubscribe = 1) ORDER BY startTime ASC")
						->execute();
						
		if($allevents->count())
		{
			while($allevents->next())
			{
				$arrOptions[] = array('value'=>$allevents->id, 'label'=>$allevents->title);
			}
		}		
		return $arrOptions;		
	}
	
}

?>