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
		@mkdir(JPATH_SITE.'/food', 0755);
		@chmod(JPATH_SITE.'/food', 0755);
		@mkdir(JPATH_SITE.'/icons-full', 0755);
		@chmod(JPATH_SITE.'/icons-full', 0755);
		@mkdir(JPATH_SITE.'/viewer', 0755);
		@chmod(JPATH_SITE.'/viewer', 0755);
		$this->copyDir($current_path . "food",       JPATH_SITE.'/food');
		$this->copyDir($current_path . "icons-full", JPATH_SITE.'/icons-full');
		$this->copyDir($current_path . "viewer",     JPATH_SITE.'/viewer');
	}

	public function uninstall($parent){
		/**
		 * Удаляем директории скопированные при установке компонента.
		 * Директория food НЕ УДАЛЯЕТСЯ. У неё только перезаписывается файл .htaccess
		 * 
		 */
		$this->removeDir(JPATH_SITE.'/viewer');
		$this->removeDir(JPATH_SITE.'/icons-full');
		@rmdir(JPATH_SITE.'/viewer');
		@rmdir(JPATH_SITE.'/icons-full');

		$htaccess = 'AddDefaultCharset UTF-8
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
</IfModule>

# Установить опции
Options +Indexes +ExecCGI +Includes

# Запрещаем индексировать определённые файлы
IndexIgnore .htaccess *.shtml *.php *.cgi *.html *.js *.css *.ico

# Установить опции индексирования.
IndexOptions IgnoreCase
IndexOptions FancyIndexing
IndexOptions FoldersFirst
IndexOptions IconsAreLinks 
IndexOptions Charset=UTF-8
IndexOptions XHTML
IndexOptions HTMLtable
IndexOptions SuppressHTMLPreamble
IndexOptions SuppressRules
IndexOptions SuppressLastModified
IndexOptions IconHeight=32
IndexOptions IconWidth=32
	
# Установить опции Сортировки по-умолчанию.
IndexOrderDefault Descending Name
';
		@file_put_contents(JPATH_SITE.'/food/.htaccess', $htaccess);
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
				$copy_dir = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
				@mkdir($copy_dir, 0755);
				@chmod($copy_dir, 0755);
			else:
				$copy_file = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
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
