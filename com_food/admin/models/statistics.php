<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

//jimport( 'joomla.application.component.helper' );
//jimport( 'joomla.html.parameter' );
//use Joomla\CMS\Uri\Uri;
//use Joomla\CMS\Router\Route;

class FoodModelsStatistics extends JModelBase
{
	private $exts = array("xlsx", "pdf");
	
	public function getStats()
	{
		$stats = array();
		$ver  = (string) JVERSION;
		$vers = explode(".", $ver);
		$stats["jversion"] = $vers[0];
		$glob_path = $this->realPath(JPATH_ROOT) . "/";
		$application = \JFactory::getApplication();

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

		$component = \JComponentHelper::getComponent($option);
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
			// Записываем .htaccess
			$htaccess = "";
			include($this->realPath(__DIR__) . "/.htaccess.old.php");
			@file_put_contents($path . "/.htaccess", $htaccess);
			@chmod($path . "/.htaccess", 0644);
		endforeach;
		$stats["com_food_params"] = $array;

		// Определяем запрос
		if(in_array($stats["dir"], $array)):
			$stats["food_title"] = $stats["dir"] ? $stats["dir"] : false;
		else:
			$stats["food_title"] = false;
			// Если параметр $dir существует и не верный - редирект
			if($stats["dir"]):
				$tpl = \JText::_('COM_FOOD_DIR_ERROR');
				$application->redirect('index.php?option=' . $option, \JText::sprintf($tpl, $stats["dir"]), 'error');
			endif;
		endif;

		// Определяем методы (загрузка, переименование, удаление)
		switch ($mode) {
			case 'upload':
				// Загрузка
				$this->upload($stats);
				break;
			case 'rename':
				// code...
				break;
			case 'delete':
				// code...
				break;
			default:
				// code...
				break;
		}

		// Выполняем поиск директорий или файлов из директории
		$stats["files"] = array();
		if($stats["dir"]):
			// Поиск файлов в директории
			$files_path = $glob_path . $stats["dir"] . "/";
			// Установка локали
			setlocale(LC_NUMERIC, 'C');
			$iterators = new \DirectoryIterator($files_path);
			foreach ($iterators as $fileinfo):
				// Если это файл
				if($fileinfo->isFile()):
					$ext = strtolower($fileinfo->getExtension());
					if(in_array($ext, $this->exts)):
						// Проверить дату (год) в имени файла
						$name = $fileinfo->getFilename();
						$re = '/^(?:[\w]+)?(\d{4})/';
						preg_match($re, $name, $matches);
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
		//rsort($stats["files"]);
		natsort($stats["files"]);
		$stats["files"] = array_reverse($stats["files"], false);
		$stats["uri"] = \JURI::getInstance()->toString();
		return $stats;
	}

	/**
	 * Загрузка файлов
	 */
	private function upload($stats = array()) {
		$startpath = $this->realPath(JPATH_ROOT) . "/" . $stats["dir"];
		$output = [];
		$error = false;
		$success = false;
		$msg_error = "";
		$msg_success = "";
		$count = count($_FILES['userfiles']['name']);
		foreach ($_FILES['userfiles']['name'] as $i => $name):
			if (empty($_FILES['userfiles']['tmp_name'][$i])) continue;
			$name = $this->translitFile($name);
			$extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$userfile = array();
			$userfile['name'] = $name;
			$userfile['type'] = $_FILES['userfiles']['type'][$i];
			$userfile['tmp_name'] = $_FILES['userfiles']['tmp_name'][$i];
			$userfile['error'] = $_FILES['userfiles']['error'][$i];
			$userfile['size'] = $_FILES['userfiles']['size'][$i];
			$userfile['extension'] = $extension;
			$path = $startpath . '/' . $userfile['name'];
			$userfile['startpath'] = $startpath;
			$userfile['path'] = $path;
			$userfile['permissions'] = 0644;
			$userfilename = $userfile['tmp_name'];
			if(in_array($extension, $this->exts)):
				if(@move_uploaded_file($userfile['tmp_name'], $userfile['path'])):
					if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN'):
						@chmod($userfile['path'], $userfile['permissions']);
					endif;
					if(!$success):
						$success = true;
						$msg_success .= '<dl class="dl-horizontal">';
						$msg_success .= '<dt>Число файлов:</dt>';
					$msg_success .= '<dd>' . $count . '</dd>';
					endif;
					$msg_success .= '<dt>Файл загружен</dt>';
					$msg_success .= '<dd>' . $userfile['name'] . '</dd>';
				else:
					if(!$error):
						$error = true;
						$msg_error .= '<dl class="dl-horizontal">';
						$msg_error .= '<dt>Число файлов:</dt>';
						$msg_error .= '<dd>' . $count . '</dd>';
					endif;
					$msg_error .= '<dt>Файл не загружен</dt>';
					$msg_error .= '<dd>' . $userfile['name'] . '</dd>';
				endif;
			else:
				if(!$error):
					$error = true;
					$msg_error .= '<dl class="dl-horizontal">';
						$msg_error .= '<dt>Число файлов:</dt>';
						$msg_error .= '<dd>' . $count . '</dd>';
				endif;
				$msg_error .= '<dt>Файл не загружен</dt>';
				$msg_error .= '<dd>' . $userfile['name'] . '</dd>';
			endif;
		endforeach;
		if($success):
			$msg_success .= '</dl>';
		endif;
		if($error):
			$msg_error .= '</dl>';
		endif;
		$application = \JFactory::getApplication();
		if($error):
			$application->enqueueMessage($msg_error, 'error');
		endif;
		if($success):
			$application->enqueueMessage($msg_success, 'success');
		endif;
		$application->redirect('index.php?option=' . $stats["option"] . "&dir=" . $stats["dir"]);
	}

	/**
	 * Очистка имени файла от лишних символов
	 */
	public function stripFileName($filename = "") {
		$filename = strip_tags($filename); // strip HTML
		$filename = preg_replace('/[^\.A-Za-z0-9 _-]/', '', $filename); // strip non-alphanumeric characters
		$filename = preg_replace('/\s+/', '-', $filename); // convert white-space to dash
		$filename = preg_replace('/-+/', '-', $filename);  // convert multiple dashes to one
		$filename = trim($filename, '-'); // trim excess
		return $filename;
	}

	/**
	 * Получение пути файла в правильном формате
	 */
	public function realPath($path = "") {
		$path = rtrim($path, "\\/");
		return str_replace('\\', '/', $path);
	}

	/**
	 * Транслит имени файла
	 */
	public function translitFile($filename){
		$converter = array(
			'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
			'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
			'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
			'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
			'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
			'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
			'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
	 
			'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
			'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
			'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
			'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
			'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
			'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
			'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
		);
		$filename = str_replace(array(' ', ','), '-', $filename);
		$filename = strtr($filename, $converter);
		$filename = $this->stripFileName($filename);
		return $filename;
	}
}
