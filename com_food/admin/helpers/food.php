<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_food
 *
 * @copyright   Copyright (C) 2008 - All rights reserved.
 * @license     MIT
 */

defined('_JEXEC') or die;

/**
 * Food component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_food
 * @since       1.6
 */
class FoodHelpersFood
{
	public static $extension = 'com_food';

	/**
	 * @return  JObject
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		$assetName = 'com_food';
		$level = 'component';
		$actions = JAccess::getActions('com_food', $level);
		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}
		return $result;
	}
}