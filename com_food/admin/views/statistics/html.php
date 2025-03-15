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

	public function getSize($file) {

		$sizes = array('Tb' => 1099511627776, 'Gb' => 1073741824, 'Mb' => 1048576, 'Kb' => 1024, 'b' => 1);
		$precisions = count($sizes) - 1;
		$size = filesize($file);
		foreach ($sizes as $unit => $bytes) {
			if ($size >= $bytes) {
				return number_format($size / $bytes, $precisions) . ' ' . $unit;
			}
			$precisions--;
		}
		return '0 b';
	}

	/**
	 * Вывод времени в определённом формате
	 */
	public function toDateFormat( $timestamp = 0 )
	{
		$dateFormat = 'd-m-Y H:i:s';
		$strTime = date($dateFormat, $timestamp);
		return $strTime;
	}

	/**
	 * Получение пути файла в правильном формате
	 */
	public function realPath($path = "") {
		$path = rtrim($path, "\\/");
		return str_replace('\\', '/', $path);
	}
}