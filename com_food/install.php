<?php
defined('_JEXEC') or die();

@mkdir(JPATH_SITE.'/food', 0755);
@chmod(JPATH_SITE.'/food', 0755);
@mkdir(JPATH_SITE.'/icons-full', 0755);
@chmod(JPATH_SITE.'/icons-full', 0755);
@mkdir(JPATH_SITE.'/viewer', 0755);
@chmod(JPATH_SITE.'/viewer', 0755);

function copyDir($source, $dest) {
	foreach ($iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::SELF_FIRST) as $item) {
		if ($item->isDir()) {
			$copy_dir = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
			@mkdir($copy_dir, 0755);
			@chmod($copy_dir, 0755);
		} else {
			$copy_file = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
			@copy($item, $copy_file);
			@chmod($dest . $copy_file, 0644);
		}
	}
}
$current_path = dirname(__FILE__) . "/";

copyDir($current_path . "food",       JPATH_SITE.'/food');
copyDir($current_path . "icons-full", JPATH_SITE.'/icons-full');
copyDir($current_path . "viewer",     JPATH_SITE.'/viewer');
