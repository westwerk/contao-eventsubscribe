<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Eventsubscribe
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'EventSubscribe'         => 'system/modules/eventsubscribe/classes/EventSubscribe.php',
	'EventSubscribeAjax'     => 'system/modules/eventsubscribe/classes/EventSubscribeAjax.php',
	'EventSubscribeFrontend' => 'system/modules/eventsubscribe/classes/EventSubscribeFrontend.php',

	// Forms
	'FormSelectEventMenu'    => 'system/modules/eventsubscribe/forms/FormSelectEventMenu.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'event_fullextended' => 'system/modules/eventsubscribe/templates',
	'ext_subscribe'      => 'system/modules/eventsubscribe/templates',
));
