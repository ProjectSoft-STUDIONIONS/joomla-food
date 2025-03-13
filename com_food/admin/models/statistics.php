<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

//jimport( 'joomla.application.component.helper' );
//jimport( 'joomla.html.parameter' );
//use Joomla\CMS\Uri\Uri;
//use Joomla\CMS\Router\Route;

class FoodModelsStatistics extends JModelBase
{
	
	public function getStats()
	{
		$stats = array();
		$ver  = (string) JVERSION;
		$vers = explode(".", $ver);
		$stats["jversion"] = $vers[0];
		$glob_path = str_replace("\\", "/", JPATH_ROOT) . "/";
		$application = JFactory::getApplication();

		$stats["com_food_path"] = str_replace("\\", "/", str_replace(JPATH_ROOT, "", JPATH_COMPONENT_ADMINISTRATOR)) . "/";

		// Параметры URL
		$option = $application->input->get('option');
		$dir = $application->input->get('dir');
		$mode = $application->input->get('mode');

		// Удалим лишние символы из директории
		$dir = trim((string) $dir, " \n\r\t\v\x00\\/|\"'`!@#$%^&*()_-+={}[]|<>?.,");
		
		$stats["option"] = $option;
		$stats["dir"] = $dir;
		$stats["mode"] = $mode;

		$component = JComponentHelper::getComponent($option);
		$folders = $component->params->get('food_folders');
		$folders = preg_split('/[\s,;]+/', $folders);
		$food = array("food");
		$array = array_filter(array_unique(array_merge($food, $folders)));
		// Сортируем директории
		sort($array);
		// Директории
		foreach ($array as $key => $value):
			$path = $glob_path . $value;
			// Если директория не существует
			if(!is_dir($path)):
				// Создаём директорию
				@mkdir($path);
				@chmod($path, 0755);
			endif;
			// Записываем .htacces
			$htaccess = "";
			include(__DIR__ . "/.htaccess.old.php");
			@file_put_contents($path . "/.htaccess", $htaccess);
			@chmod($path . "/.htaccess", 0644);
		endforeach;
		$stats["com_food_params"] = $array;
		// Определяем методы (загрузка, переименование, удаление)
		// Определяем запрос, выполняем поиск директорий или файлов из директории
		if(in_array($dir, $array)):
			$stats["food_title"] = $dir ? $dir : false;
		else:
			$stats["food_title"] = false;
			$dir = "";
		endif;
		$stats["dir"] = $dir;
		$stats["files"] = array();
		if($dir):
			// Поиск файлов в директории
			$files_path = $glob_path . $dir . "/";
			// Разрешённые файлы
			$exts = ["xlsx", "pdf"];
			// Установка локали
			setlocale(LC_NUMERIC, 'C');
			$iterators = new DirectoryIterator($files_path);
			foreach ($iterators as $fileinfo):
				// Если это файл
				if($fileinfo->isFile()):
					$ext = strtolower($fileinfo->getExtension());
					if(in_array($ext, $exts)):
						// Проверить дату (год) в имени файла
						$name = $fileinfo->getFilename();
						$re = '/^(?:[\w]+)?(\d{4})/';
						preg_match($re, $name, $matches, PREG_UNMATCHED_AS_NULL);
						// Если есть 4 цифры в имени файла
						if($matches):
							// Год сейчас
							$year = intval(date("Y", time()));
							// Год в имени файла
							$file_year = intval($matches[1]);
							// Если разница лет больше/равно 5 лет.
							if($year - $file_year > 4):
								// Удаляем файл
								$file_absolute = path_join($startpath, $name);
								@unlink($file_absolute);
							else:
								// Добавляем файл в отображение
								$stats["files"][] = $name;
							endif;
						else:
							// Добавляем файл в отображение
							$stats["files"][] = $name;
						endif;
					endif;
				endif;
			endforeach;
		endif;
		$stats["uri"] = JURI::getInstance()->toString();
		return $stats;
	}

}
