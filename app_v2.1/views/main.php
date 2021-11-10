<?php
	if (!defined("APP")) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
?><!doctype html>
<!--[if lt IE 7 ]><html lang="ru" class="ie6"><![endif]-->
<!--[if IE 7 ]>   <html lang="ru" class="ie7"><![endif]-->
<!--[if IE 8 ]>   <html lang="ru" class="ie8"><![endif]-->
<!--[if IE 9 ]>   <html lang="ru" class="ie9"><![endif]-->
<!--[if !IE]><!--><html lang="ru"><!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title><?=$title?></title>
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
	<meta name="viewport" content="width = 1050, user-scalable = yes" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<meta name="theme-color" content="#704f26" />
	<script type="text/javascript" src="/js/extras/jquery.min.1.7.js"></script>
	<script type="text/javascript" src="/js/extras/modernizr.2.5.3.min.js"></script>
</head>
<body>
<div id="canvas">
	<div class="magazine-viewport">
		<div class="container">
			<div class="magazine" data-page_count='<?=$page_count + $page_count % 2?>' 
				<? if (isset($finded)): ?>
					data-finded_page='<?=$finded['page']?>'
				<? endif ?>>
				<div>
					<div class="gradient"></div>
					<img src="/p/first.jpg" style="width: 100%; height: 100%;">
				</div>
				<div>
					<img src="/p/page-bg-2.jpg" style="width: 100%; height: 100%;">
					<div class='content'>
						<form>
							<div class='input-box'>
								<input id='find' autocomplete='off' placeholder='Ныхас ам фыссут' spellcheck='false'>
								<a class='ae'><span>ӕ</span></a>
							</div>
							<p class='p1'>Дзырдуаты ис ныхӕсттӕ агурын. Уӕ ныхас ам ныффыссут ӕмӕ фендзыстут ныхӕстты ранымад. Дамгъӕ 'ӕ'-йы бӕстӕ, фыссут 'ае'.</p>
							<p class='p2' style='display:none;'>Ахӕм ныхас дзырдуаты нӕ разынд.</p>
						</form>
						<ul class='words-list'></ul>
					</div>
				</div>
				<div ignore="1" class="next-button"></div>
				<div ignore="1" class="previous-button"></div>
			</div>
		</div>
	</div>
</div>

<div class='search-icon'>
	<div class='pic' title='Ныхас агурын'></div>
</div>
<script type="text/javascript" src="js/app.js?v=4"></script>
</body>
</html>