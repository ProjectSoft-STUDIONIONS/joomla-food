<?php
/**
* @version      1.0.0 11.03.2025
* @author       ProjectSoft
* @package      food
* @copyright    Copyright (C) 2008 - All rights reserved.
* @license      MIT
*/
defined('_JEXEC') or die();

class com_foodInstallerScript {

	public function preflight($type, $parent=null){
		if ($type=='update'){
			return true;
		}
	}
	
	public function update(){}
	
	public function install($parent){
		$joomla_path = $this->realPath(JPATH_SITE);
		$dir_path = $this->realPath(__DIR__);
		/**
		 * Создаём нужные директории для отображения индексации директории
		 * Копируем содержимое
		 * Директория food создаётся автоматически и никогда не удаляется.
		 */
		@mkdir($joomla_path . '/food', 0755);
		@chmod($joomla_path . '/food', 0755);
		@mkdir($joomla_path . '/icons-full', 0755);
		@chmod($joomla_path . '/icons-full', 0755);
		@mkdir($joomla_path . '/viewer', 0755);
		@chmod($joomla_path . '/viewer', 0755);
		/**
		 * Копирование директорий
		 */
		$this->copyDir($dir_path . "/icons-full", $joomla_path . '/icons-full');
		$this->copyDir($dir_path . "/viewer",     $joomla_path . '/viewer');
		/**
		 * Перезаписываем .htaccess
		 */
		$htaccess = "";
		include($dir_path . "/admin/models/.htaccess.old.php");
		@file_put_contents($joomla_path.'/food/.htaccess', $htaccess);
		@chmod($joomla_path.'/food/.htaccess', 0644);
	}

	public function uninstall($parent){
		$joomla_path = $this->realPath(JPATH_SITE);
		$dir_path = $this->realPath(__DIR__);
		/**
		 * Удаляем директории скопированные при установке компонента.
		 * Директория food и прочие указанные в настройках НЕ УДАЛЯЮТСЯ.
		 * У них только перезаписывается файл .htaccess,
		 * чтобы небыло ощибок при дальнейшем использовании.
		 */
		$this->removeDir($joomla_path.'/viewer');
		$this->removeDir($joomla_path.'/icons-full');
		@rmdir($joomla_path.'/viewer');
		@rmdir($joomla_path.'/icons-full');
		/**
		 * Получаем настройки компонента
		 */
		$component = JComponentHelper::getComponent('com_food');
		$folders = $component->params->get('food_folders');
		/**
		 * Получаем директории
		 */
		$folders = preg_split('/[\s,;]+/', $folders);
		$food = array("food");
		$array = array_filter(array_unique(array_merge($food, $folders)));
		// Пробегаемся по директориям указанных в настройках
		foreach($array as $key => $value):
			/**
			 * Получаем путь .htaccess
			 */
			$path = array($joomla_path, $value, ".htaccess");
			$path = implode("/", $path);
			if(is_file($path)):
				// Перезаписываем .htacces
				$htaccess = "";
				include($dir_path . "/models/.htaccess.dev.php");
				@file_put_contents($path, $htaccess);
			endif;
		endforeach;
	}

	private function copyDir($source, $dest) {
		$source = rtrim($source, "\\/");
		$dest   = rtrim($dest, "\\/");
		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				$source,
				\RecursiveDirectoryIterator::SKIP_DOTS
			),
			\RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ($files as $item):
			if ($item->isDir()):
				$copy_dir = $dest . "/" . trim(str_replace($source, "", $this->realPath($item->getRealPath())), "\\/");
				@mkdir($copy_dir, 0755);
				@chmod($copy_dir, 0755);
			else:
				$copy_file = $dest . "/" . trim(str_replace($source, "", $this->realPath($item->getRealPath())), "\\/");
				@copy($this->realPath($item->getRealPath()), $copy_file);
				@chmod($copy_file, 0644);
			endif;
		endforeach;
	}

	private function removeDir($source) {
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$source,
				RecursiveDirectoryIterator::SKIP_DOTS
			),
			RecursiveIteratorIterator::CHILD_FIRST
		);
		foreach ($files as $item):
			$function = $item->isDir() ? 'rmdir' : 'unlink';
			@$function($this->realPath($item->getRealPath()));
		endforeach;
	}

	private function realPath($path) {
		$path = rtrim($path, "\\/");
		return str_replace('\\', '/', $path);
	}
}
