<?php
	if (!defined("APP")) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico">
    
    <title><?=$title?></title>
	
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<style>
	body {
	  padding-top: 50px;
	}
	label {
		display: block;
	}
	.starter-template {
	  padding: 40px 15px;
	  text-align: center;
	}
	.input-group-addon {
		cursor: pointer;
	}
	.input-lg+.form-control-feedback {
		width: 33px;
		top: 6px;
		right: 6px;
		line-height: 22px;
		height: 34px;
		pointer-events: all;
		z-index: 33;
	}
	.translate {
		border: 1px solid #aaa;
		border-radius: 4px;
		box-shadow: 0 0 20px inset #aaa;
		padding: 20px;
		background: #f5f5f5;
		position: relative;
		margin-bottom: 40px;
	}
	.translate:after {
		content: ' ';
		display: block;
		border-bottom: 1px solid #ddd;
		position: relative;
		top: 42px;
		z-index: 111;
	}
	img.remove-translate {
		position: absolute;
		right: -10px;
		top: -10px;
		width: 40px;
		opacity: 0.5;
		cursor: pointer;
		display: none;
	}
	img.remove-translate:hover {
		opacity: 0.77;
	}
	.translate:hover .remove-translate {
		display: block;
	}
	.w_mover {
		width: 105px;
		z-index: 22;
		pointer-events: all;
		top: 5px;
		text-align: left;
	}
	.translate:first-child img.remove-translate {
		display: none;
	}
	.translate-box .form-group:first-child .move-up,
	.translate-box .form-group:last-child .move-down,
	.example-box .form-group:last-child .move-down,
	.example-box .form-group:first-child .move-up,
	.translate-box .form-group:first-child .remove-translate {
		display: none;
	}
	input.form-control.input-lg {
		padding-right: 110px;
	}
	.translates-removed, .translate-box-removed, .example-box-removed {
		display: none;
	}
	.translates .translate:last-child .t_mover .move-down,
	.translates .translate:first-child .t_mover .move-up {
		display: none;
	}
	</style>
</head>

  <body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href='/admin'><?=$user['name']?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/admin/add">Бафтауын ныхас</a></li>
            <li><a href="/admin/logout">Рахизын</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">

		<div class="starter-template">
			<h1>Зӕл аивын</h1>
			<p>Ацы сыфыл ис ныхасы зӕл баивын</p>
			<hr>
			<div class="form-group">
				<label>Ныхас</label>
				<input type="text" class="form-control input-lg" id="word" placeholder="Ныхас" autocomplete='off'
						value='<?=$finded['word']?>' disabled='disabled'>
				<input type="hidden" id="userId" style="display: none;" value="<?=$user['id']?>">
			</div>
			
			<div>
			<? if ($finded['sound'] != ''): ?>
				<audio controls>
				  <source src="/s/<?=$finded['sound']?>" type="audio/mpeg">
				  <p>Ваш браузер не поддерживает HTML5 аудио. Вот взамен <a href="/s/<?=$finded['sound']?>">ссылка на аудио</a></p>
				</audio>
			<? endif ?>
			</div>
			
			<form method="post" enctype="multipart/form-data" action="/admin/uploadSound">
				<input type='hidden' id='nyhas_id' name='nyhas_id' value='<?=$finded['id']?>'>
				<input type='hidden' name='nyhas_word' value='<?=$finded['word']?>'>
				<div class="form-group">
					<label>Зӕл равзарын</label>
					<input type='file' name='zal' />
				</div>
				
				<button type='submit' name='submit' class='btn btn-success btn-lg' id='save'>Зӕл баивын</button>
			</form>
			
			<? if ($finded['sound'] != ''): ?>
			<br>
			<br>
			<form method="post" action="/admin/removeSound">
				<input type='hidden' id='nyhas_id' name='nyhas_id' value='<?=$finded['id']?>'>
				<input type='hidden' name='nyhas_word' value='<?=$finded['word']?>'>
				<button type='submit' name='submit' class='btn btn-danger btn-lg' id='save'>Зӕл айсын</button>
			</form>
			<? endif ?>
		</div>
    </div>

    <script src="/js/extras/jquery.min.1.7.js"></script>
    <script>
	$(function(){
		
	})
	$("button.navbar-toggle").click(function(){
		$("#navbar").toggleClass("collapse")
	})
	
	
	
	$("#save").click(function(){
		
		var data = {
			id: $("#nyhas_id").val()
		}
		
	})
	
	</script>
</body></html>