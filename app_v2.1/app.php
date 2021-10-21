<?php

if (!defined("APP")) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

// Регистрация автозагрузчика классов
spl_autoload_register(function ($class) {
	$class_folders = ['models', 'controllers'];
	foreach($class_folders AS $folder) {
		$class_file_name = INC_FOLDER . "$folder/$class.php";
		if (file_exists($class_file_name)) {
			include($class_file_name);
		}
	}	
});

// Создем объект приложения и запускаем обработку запросов
Application::getInstance()->start();
