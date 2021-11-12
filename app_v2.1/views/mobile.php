<?php
	if (!defined("APP")) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
?><!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<title><?= $title?></title>
	<? if ($description != ''): ?>
	<meta name="description" content="<?=$description?>" />
	<? endif ?>
	<? if (isset($finded)): ?>
	<meta property="og:type" content="article"/>
	<? else: ?>
	<meta property="og:type" content="website"/>
	<? endif ?>
	<meta property="og:title" content="<?=$title?>"/>
	<meta property="og:description" content="<?=$description?>"/>
	<meta property="og:url" content="http://дзырдуат.рф/<?
		if (isset($finded)): 
	?><?=$finded['word']?><? endif ?>"/>
	<? if (isset($finded) && isset($finded['image']) && $finded['image'] != ''):?>
	<meta property="og:image" content="http://дзырдуат.рф/u/<?=$finded['image']?>" />
	<? else: ?>
	<meta property="og:image" content="http://дзырдуат.рф/p/og-first.jpg" />
	<? endif ?>
	<? if (isset($finded) && isset($finded['sound']) && $finded['sound'] != ''):?>
	<meta property="og:audio" content="http://дзырдуат.рф/s/<?=$finded['sound']?>" />
	<? endif ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" href="/css/book-mobile.css?v=2.2" />
	<meta name="theme-color" content="#704f26" />
	<script type="text/javascript" src="/js/extras/jquery.min.1.7.js"></script>
</head>
	<body>
		<? if (isset($finded)): ?>
			<input type='hidden' id='finded' value="<?=$finded['word']?>" />
		<? endif ?>
		<div class='wrapper'>
			<div class='finder'>
				<div class='finder-bg'>
					<input id='find' class='transition' autocomplete='off' placeholder='Ныхас ам фыссут' spellcheck='false'>
					<div class='lupa'></div>
					<a id='ae' class='transition'><span>ӕ</span></a>
				</div>
			</div>
			<div class='finder-panel transition'>
				<ul class='finded-words'></ul>
			</div>
			<div id='firts-layer'>
				<img id='olen' src='/p/olen.png' alt='саг'/>
				<h1 id='title'>дзырдуат</h1>
			</div>
			<div id='pages' style='display:none;' data-current_page='0' data-page_count='<?=$page_count + $page_count % 2?>'></div>
		</div>
		<script src='/js/book-mobile.js?v=4'></script>
	</body>
</html>