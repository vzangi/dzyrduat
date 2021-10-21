<?php
	if (!defined("APP")) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
?><!doctype html>
<html lang="ru">
<head>
<title><?= $title?></title>
<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body><?=$title?></body>
</html>