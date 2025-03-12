<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//load classes
JLoader::registerPrefix('Food', JPATH_COMPONENT_ADMINISTRATOR);
//application
$app = JFactory::getApplication();
// Require specific controller if requested
$controller = $app->input->get('controller', 'display');
// Create the controller
$classname  = 'FoodControllers'.ucwords($controller);
$controller = new $classname();
// Perform the Request task
$controller->execute();