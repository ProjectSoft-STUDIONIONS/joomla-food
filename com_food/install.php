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
		/**
		 * Создаём нужные директории для отображения индексации директории
		 * Копируем содержимое
		 * Директория food создаётся автоматически и никогда не удаляется.
		 */
		$current_path = dirname(__FILE__) . "/";
		@mkdir(JPATH_SITE.'/food');
		@chmod(JPATH_SITE.'/food', 0755);
		@mkdir(JPATH_SITE.'/icons-full');
		@chmod(JPATH_SITE.'/icons-full', 0755);
		@mkdir(JPATH_SITE.'/viewer');
		@chmod(JPATH_SITE.'/viewer', 0755);

		$htaccess = "";
		include($current_path . "admin/models/.htaccess.old.php");
		@file_put_contents(JPATH_SITE.'/food/.htaccess', $htaccess);

		$this->copyDir($current_path . "icons-full", JPATH_SITE.'/icons-full');
		$this->copyDir($current_path . "viewer",     JPATH_SITE.'/viewer');
	}

	public function uninstall($parent){
		/**
		 * Удаляем директории скопированные при установке компонента.
		 * Директория food и прочие указанные в настройках НЕ УДАЛЯЮТСЯ.
		 * У них только перезаписывается файл .htaccess,
		 * чтобы небыло ощибок при дальнейшем использовании.
		 */
		$this->removeDir(JPATH_SITE.'/viewer');
		$this->removeDir(JPATH_SITE.'/icons-full');
		@rmdir(JPATH_SITE.'/viewer');
		@rmdir(JPATH_SITE.'/icons-full');
		$component = JComponentHelper::getComponent('com_food');
		$folders = $component->params->get('food_folders');
		$folders = preg_split('/[\s,;]+/', $folders);
		$food = array("food");
		$array = array_filter(array_unique(array_merge($food, $folders)));
		$glob_path = str_replace("\\", "/", JPATH_SITE) . "/";
		// Пробегаемся по директориям указанных в настройках
		foreach($array as $key => $value):
			$path = array($glob_path, $value, ".htaccess");
			$path = implode("/", $path);
			if(is_file($path)):
				// Перезаписываем .htacces
				$htaccess = "";
				include(__DIR__ . "/models/.htaccess.dev.php");
				@file_put_contents($path, $htaccess);
			endif;
		endforeach;
	}

	private function copyDir($source, $dest) {
		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				$source,
				\RecursiveDirectoryIterator::SKIP_DOTS
			),
			\RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ($files as $item):
			if ($item->isDir()):
				$copy_dir = $dest . DIRECTORY_SEPARATOR . $item->getRealPath();
				@mkdir($copy_dir);
				@chmod($copy_dir, 0755);
			else:
				$copy_file = $dest . DIRECTORY_SEPARATOR . $item->getRealPath();
				@copy($item, $copy_file);
				@chmod($dest . $copy_file, 0644);
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
			@$function($item->getRealPath());
		endforeach;
	}
}
