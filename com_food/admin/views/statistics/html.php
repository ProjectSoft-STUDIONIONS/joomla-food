<?php

defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class FoodViewsStatisticsHtml extends JViewHtml
{
	function render()
	{
		$app = JFactory::getApplication();
		$model = new FoodModelsStatistics();
		$this->stats = $model->getStats();
		$this->addToolbar();
		return parent::render();
	} 

	protected function addToolbar()
	{
		$canDo  = FoodHelpersFood::getActions();
		$bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('COM_FOOD_TITLE'), 'folder-open food');
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_food');
		}
	}
}