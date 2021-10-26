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
	.starter-template {
	  padding: 40px 15px;
	  text-align: center;
	}
	.input-group-addon {
		cursor: pointer;
	}
	.input-lg+.form-control-feedback {
		width: 60px;
		top: 6px;
		right: 6px;
		line-height: 22px;
		height: 34px;
		pointer-events: all;
	}
	.translate {
		border: 1px solid #aaa;
		border-radius: 4px;
		box-shadow: 0 0 20px inset #aaa;
		padding: 20px;
		background: #f5f5f5;
		position: relative;
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
	input.translate-item, 
	input.example {
		padding-right: 70px;
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
        <h1>Ныхас бафтауын</h1>
		<p>Ацы сыфыл ис ног ныхас дзырдуатмӕ бахӕссын</p>
		<hr>
		<div class="form-group">
			<label>Ныхас</label>
			<div class="input-group">
				<div class="input-group-addon">ӕ</div>
				<input type="text" class="form-control input-lg" id="word" placeholder="Ныхас" autocomplete='off'>
			</div>
			<input type="hidden" id="translit" style="display: none;">
			<input type="hidden" id="userId" style="display: none;" value="<?=$user['id']?>">
		</div>
		<div class="form-group">
			<label>Ныхасы бын фыст</label>
			<div class="input-group">
				<div class="input-group-addon">ӕ</div>
				<input type="text" class="form-control input-lg" id="desc" placeholder="Ныхасы бын фыст" autocomplete='off'>
			</div>
		</div>
		
		<div class='translates'>
			<div class='translate'>
				<div class="form-group">
					<label>Тӕлмац</label>
					<input type="text" class="form-control input-lg translate-item" placeholder="Тӕлмац">
				</div>
				<button class='btn btn-primary add-trans-item'>Бафтауын ӕмнысаниуӕг ныхас</button>
				<br>
				<br>
				<button class='btn btn-primary add-example'>Бафтауын дӕнцӕг</button>
			</div>
			<hr>
		</div>
		<button class='btn btn-primary btn-lg add-trans-block'>Тӕлмац бафтауын</button>
		
		<hr>
		
		<button class='btn btn-success btn-lg' id='save'>Ныхас дзырдуатмӕ бахӕссын</button>
	  </div>

    </div>

    <script src="/js/extras/jquery.min.1.7.js"></script>
    <script src="/js/extras/jquery.tmpl.js"></script>
	<script>
	$(function(){
		$('body').on('click', '.input-group-addon', function(){
			var input = $(this).next();
			var text = input.val();
			if (text == '' && !input.hasClass('example')) {
				text = $(this).text().toUpperCase();
			} else {
				text = text + $(this).text();
			}
			input.val(text);
			input.keyup();
			input.focus();
		})
	})
	$("button.navbar-toggle").click(function(){
		$("#navbar").toggleClass("collapse")
	})
	$("#word").keyup(function(){
		$("#translit").val( translit($(this).val()) )
	})
	$('.add-trans-block').click(function(){
		$("#transTmpl").tmpl({}).appendTo('.translates');
	})
	$('body').on('click', '.add-trans-item', function(){
		$("#transItemTmpl").tmpl({}).insertBefore($(this))
	})
	$("body").on('click', '.remove-translate', function(){
		$(this).parent().remove();
	})
	$('body').on('click', '.add-example', function(){
		$("#exampleItemTmpl").tmpl({}).insertBefore($(this))
	})
	$("body").on('click', '.remove-example', function(){
		$(this).parent().parent().remove();
	})
	
	$("#save").click(function(){
		var data = {}
		data.word = $("#word").val().trim()
		if (data.word == '') {
			alert('Ныхас ныффыссын хъӕуы')
			return false
		}
		var inBase = false;
		$.ajax({
			url: '/find/' + data.word,
			async: false,
			success: function(words) {
				for (var w of words)
				{
					if (w['word'].toLowerCase() == data.word.toLowerCase()) {
						inBase = true
						break
					}
				}
			}
		})
		if (inBase) {
			alert("Ахӕм ныхас дзырдуаты ис")
			return false;
		}
		data.translit = $("#translit").val()
		data.user_id = $("#userId").val()
		data.description = $("#desc").val()
		data.translates = []
		var emptyTranslate = false;
		$translates = $(".translates .translate")
		$translates.each(function(index){
			var t_items = []
			$items = $($translates[index]).find(".translate-item")
			$items.each(function(t_index){
				if ($items[t_index].value == '') {
					emptyTranslate = true
				}
				t_items.push($items[t_index].value);
			})
			
			
			var e_items = []
			$examples = $($translates[index]).find(".example")
			$examples.each(function(e_index){
				e_items.push($examples[e_index].value)
			})
			
			data.translates.push({
				'words': t_items, 
				'examples': e_items
			})
		})
		
		if (data.translates.length == 0 || emptyTranslate) {
			alert('Тӕлмац ныффыссын хъӕуы')
			return false
		}
		
		$.ajax({
			url: '/admin/add/',
			type: 'POST', 
			data: {'data': data},
			success: function(r) {
				if (r == 'ok') {
					alert('Ныхас дзырдуатмӕ бафтыдис!')
					location.reload()
				}
			}
		})
	})
	function translit(text) {
		return text .toLowerCase()
					.replaceAll('а', 'а1')
					.replaceAll('ӕ', 'а2')
					.replaceAll('г', 'г1')
					.replaceAll('г1ъ', 'г2')
					.replaceAll('к', 'к1')
					.replaceAll('д', 'д1')
					.replaceAll('д1ж', 'д2')
					.replaceAll('д1з', 'д3')
					.replaceAll('к1ъ', 'к2')
					.replaceAll('п', 'п1')
					.replaceAll('п1ъ', 'п2')
					.replaceAll('т', 'т1')
					.replaceAll('т1ъ', 'т2')
					.replaceAll('х', 'х1')
					.replaceAll('х1ъ', 'х2')
					.replaceAll('ц', 'ц1')
					.replaceAll('ц1ъ', 'ц2')
					.replaceAll('ч', 'ч1')
					.replaceAll('ч1ъ', 'ч2')				
	}
	</script>
	
	<script id="transTmpl" type="text/x-jquery-tmpl">
		<div class='translate'>
			<img src="/p/remove.png" class="remove-translate" title='Тӕлмац айсын'>
			<div class="form-group">
				<label>Тӕлмац</label>
				<input type="text" class="form-control input-lg translate-item" placeholder="Тӕлмац">
			</div>
			<button class='btn btn-primary add-trans-item'>Бафтауын ӕмнысаниуӕг ныхас</button>
			<br>
			<br>
			<button class='btn btn-primary add-example'>Бафтауын дӕнцӕг</button>
		</div>
		<hr>
	</script>
	
	<script id="transItemTmpl" type="text/x-jquery-tmpl">
		<div class="form-group" style='position:relative;'>
			<input type="text" class="form-control input-lg translate-item" placeholder="Тӕлмац">
			<span class="form-control-feedback btn btn-sm btn-danger remove-translate">айсын</span>
		</div>
	</script>
	
	<script id="exampleItemTmpl" type="text/x-jquery-tmpl">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-addon">ӕ</div>
				<input type="text" class="form-control input-lg example" placeholder="Дӕнцӕг">
				<span class="form-control-feedback btn btn-sm btn-danger remove-example">айсын</span>
			</div>
		</div>
	</script>
</body></html>