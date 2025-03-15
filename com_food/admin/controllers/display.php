<?php
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class FoodControllersDisplay extends JControllerBase
{
	public function execute()
	{
		// Application
		$app = $this->getApplication();

		// Document
		$document     = \JFactory::getDocument();
		$viewName     = $app->input->getWord('view', 'statistics');
		$viewFormat   = $document->getType();
		$layoutName   = $app->input->getWord('layout', 'default');
		$app->input->set('view', $viewName);

		// Register the layout paths for the view
		$paths = new \SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/views/' . $viewName . '/tmpl', 'normal');
		$viewClass  = 'FoodViews' . ucfirst($viewName) . ucfirst($viewFormat);

		// Практически все действия в модели
		$modelClass = 'FoodModels' . ucfirst($viewName);
		$view = new $viewClass(new $modelClass, $paths);
		$view->setLayout($layoutName);

		// Render our view.
		echo $view->render();
		return true;
	}

}
