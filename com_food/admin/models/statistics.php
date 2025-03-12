<?php

defined( '_JEXEC' ) or die( 'Restricted access' ); 

class FoodModelsStatistics extends JModelBase
{
	
	public function getStats()
	{
		$stats = array();
		$ver  = (string) JVERSION;
		$vers = explode(".", $ver);
		$stats["jversion"] = $vers[0];
		$stats["com_food_path"] = str_replace("\\", "/", str_replace(JPATH_ROOT, "", JPATH_COMPONENT_ADMINISTRATOR)) . "/";
		return $stats;
	}

}
