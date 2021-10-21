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
	<input type='hidden' id='nyhas_id' value='<?=$finded['id']?>'>
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
        <h1>Ныхас аивын</h1>
		<p>Ацы сыфыл ис ныхас дзырдуаты ивын</p>
		<hr>
		<div class="form-group">
			<label>Ныхас</label>
			<div class="input-group">
				<div class="input-group-addon">ӕ</div>
				<input type="text" class="form-control input-lg" id="word" placeholder="Ныхас" autocomplete='off'
					value='<?=$finded['word']?>'>
			</div>
			<input type="hidden" id="translit" style="display: none;" 
				value='<?=$finded['translit']?>'>
			<input type="hidden" id="userId" style="display: none;" value="<?=$user['id']?>">
		</div>
		<div class="form-group">
			<label>Ныхасы бын фыст</label>
			<div class="input-group">
				<div class="input-group-addon">ӕ</div>
				<input type="text" class="form-control input-lg" id="desc" placeholder="Ныхасы бын фыст" autocomplete='off'
					value='<?=$finded['description']?>'>
			</div>
		</div>
		
		<div class='translates-removed'></div>
		<div class='translates'>
			<?php
				$sort = 0;
				foreach ($finded['translates'] as $translate) {
					$sort += 10;
					?>
					<div class='translate' data-id='<?=$translate['id']?>' data-sort='<?=$sort?>'>
						<img src="/p/remove.png" class="remove-translate" title='Тӕлмац x'>
						<label>Тӕлмац</label>
						<div class='translate-box-removed'></div>
						<div class='translate-box'>
						<? foreach ($translate['words'] as $words) { ?>
							<div class="form-group" style='position:relative;'>
								<input type="text" class="form-control input-lg translate-item" placeholder="Тӕлмац"
									value='<?=$words['translate']?>' data-id='<?=$words['id']?>' data-sort='<?=$words['sort']?>'>
								<span class="form-control-feedback btn btn-sm btn-danger remove-translate" title='Айсын'>x</span>
								<div class='form-control-feedback w_mover'>
									<a class="btn btn-sm btn-warning move-up" title='Уӕлӕмӕ'>/\</a>
									<a class="btn btn-sm btn-warning move-down" title='Дӕлӕмӕ'>\/</a>
								</div>
							</div>
						<? } ?>
						</div>
						<button class='btn btn-primary add-trans-item'>Бафтауын ӕмнысаниуӕг ныхас</button>
						<br>
						<br>
						<div class='example-box-removed'></div>
						<div class='example-box'>
						<? foreach ($translate['examples'] as $example) { ?>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">ӕ</div>
									<input type="text" class="form-control input-lg example" placeholder="Дӕнцӕг"
										value='<?=$example['example']?>' data-id='<?=$example['id']?>' data-sort='<?=$example['sort']?>'>
									<span class="form-control-feedback btn btn-sm btn-danger remove-example" title='Айсын'>x</span>
									<div class='form-control-feedback w_mover'>
										<a class="btn btn-sm btn-warning move-up" title='Уӕлӕмӕ'>/\</a>
										<a class="btn btn-sm btn-warning move-down" title='Дӕлӕмӕ'>\/</a>
									</div>
								</div>
							</div>
						<? } ?>
						</div>
						<button class='btn btn-primary add-example'>Бафтауын дӕнцӕг</button>
						<br>
						<br>
						<div class='t_mover'>
							<button class='btn btn-warning move-up' title='Уӕлӕмӕ'>/\</button>
							<button class='btn btn-warning move-down' title='Дӕлӕмӕ'>\/</button>
						</div>
					</div>
					<?
				}
			?>
		</div>
		
		<button class='btn btn-primary btn-lg add-trans-block'>Тӕлмац бафтауын</button>
		<hr>
		<button class='btn btn-success btn-lg' id='save'>Ныхас дзырдуаты баивын</button>
	  </div>

    </div>

    <script src="/js/extras/jquery.min.1.7.js"></script>
    <script src="/js/extras/jquery.tmpl.js"></script>
	<script>
	$(function(){
		$('body').on('click', '.input-group-addon', function(){
			var input = $(this).next();
			var text = input.val();
			text = text + $(this).text();
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
		$("#transItemTmpl").tmpl({}).appendTo($(this).prev())
	})
	$("body").on('click', '.remove-translate', function(){
		var trash = $(this).parent().parent().prev();
		trash.append( $(this).parent().remove() );
	})
	$('body').on('click', '.add-example', function(){
		$("#exampleItemTmpl").tmpl({}).appendTo($(this).prev())
	})
	$("body").on('click', '.remove-example', function(){
		var trash = $(this).parent().parent().parent().prev();
		trash.append( $(this).parent().parent().remove() );
	})
	
	function baivyn(a, b) {
		$(b).remove().insertBefore($(a));
	}
	
	$("body").on('click', '.translate-box .w_mover .move-down', function(){
		var a = $(this).parent().parent();
		var b = $(this).parent().parent().next();
		baivyn(a, b);
	})
	
	$("body").on('click', '.translate-box .w_mover .move-up', function(){
		var a = $(this).parent().parent();
		var b = $(this).parent().parent().prev();
		baivyn(b, a);
	})
	
	$("body").on('click', '.example-box .w_mover .move-down', function(){
		var a = $(this).parent().parent().parent();
		var b = $(this).parent().parent().parent().next();
		baivyn(a, b);
	})
	
	$("body").on('click', '.example-box .w_mover .move-up', function(){
		var a = $(this).parent().parent().parent();
		var b = $(this).parent().parent().parent().prev();
		baivyn(b, a);
	})
	
	$("body").on('click', '.t_mover .move-down', function(){
		var a = $(this).parent().parent();
		var b = $(this).parent().parent().next();
		baivyn(a, b);
	})
	
	$("body").on('click', '.t_mover .move-up', function(){
		var a = $(this).parent().parent();
		var b = $(this).parent().parent().prev();
		baivyn(b, a);
	})
	
	$("#save").click(function(){
		
		var data = {
			id: $("#nyhas_id").val()
		}
		data.word = $("#word").val().trim()
		if (data.word == '') {
			alert('Ныхас ныффыссын хъӕуы')
			return false
		}
		data.translit = $("#translit").val()
		data.description = $("#desc").val()
		
		// Переводы, которые нужно удалить
		var $rt = $(".translates-removed .translate");
		var removedTranslates = [];	
		$rt.each(function(){
			if ($(this).data().id)
				removedTranslates.push($(this).data().id)
		});
		
		data.translates = []
		var removedExamples = [];
		var removedWords = [];
		var emptyTranslate = false;
		$translates = $(".translates .translate")
		var sort = 0, t_sort = 0;
		$translates.each(function(index){
			t_sort += 10
			$r_words = $($translates[index]).find(".translate-box-removed .translate-item");
			$r_words.each(function(){
				if ($(this).data().id) {
					removedWords.push($(this).data().id)
				}
			})
			
			$r_examples = $($translates[index]).find(".example-box-removed .example");
			$r_examples.each(function(){
				if ($(this).data().id) {
					removedExamples.push($(this).data().id)
				}
			})
			
			var t_items = []
			$items = $($translates[index]).find(".translate-box .translate-item")
			sort = 0;
			$items.each(function(t_index){
				sort += 10;
				if ($items[t_index].value == '') {
					emptyTranslate = true
				}
				if ($($items[t_index]).data().id) {
					t_items.push({
						"value": $items[t_index].value, 
						"id": $($items[t_index]).data().id,
						"sort": sort
					});
				} else {
					t_items.push({
						"value": $items[t_index].value,
						"sort": sort
					});
				}
			})
			
			var e_items = []
			$examples = $($translates[index]).find(".example-box .example")
			sort = 0;
			$examples.each(function(e_index){
				sort += 10;
				if ($($examples[e_index]).data().id) {
					e_items.push({
						"value": $examples[e_index].value, 
						"id": $($examples[e_index]).data().id,
						"sort": sort
					});
				} else {
					e_items.push({
						"value": $examples[e_index].value,
						"sort": sort
					});
				}
			})
			
			data.translates.push({
				'words': t_items, 
				'examples': e_items,
				'sort': t_sort
			})
			if ($($translates[index]).data().id) {
				data.translates[data.translates.length-1]['id'] = $($translates[index]).data().id;
			}
		})
		//console.log(data);
		//console.log(removedWords, removedExamples)
		if (data.translates.length == 0 || emptyTranslate) {
			alert('Тӕлмац ныффыссын хъӕуы')
			return false
		}
		
		$.ajax({
			url: '/admin/tchange/',
			type: 'POST', 
			data: {
				'data': data, 
				'r_translates': removedTranslates,
				'r_words': removedWords,
				'r_examples': removedExamples
			},
			success: function(r) {
				if (r == 'ok') {
					alert('Ныхас дзырдуаты аивта!')
					location.href = '/admin/';
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
		<div class='translate' data-new='1'>
			<img src="/p/remove.png" class="remove-translate" title='Тӕлмац x'>
			<label>Тӕлмац</label>
			<div class='translate-box-removed'></div>
			<div class='translate-box'>
				<div class="form-group"  style='position:relative;'>
					<input type="text" class="form-control input-lg translate-item" placeholder="Тӕлмац" data-new='1'>
					<span class="form-control-feedback btn btn-sm btn-danger remove-translate" title='Айсын'>x</span>
					<div class='form-control-feedback w_mover'>
						<a class="btn btn-sm btn-warning move-up" title='Уӕлӕмӕ'>/\</a>
						<a class="btn btn-sm btn-warning move-down" title='Дӕлӕмӕ'>\/</a>
					</div>
				</div>
			</div>
			<button class='btn btn-primary add-trans-item'>Бафтауын ӕмнысаниуӕг ныхас</button>
			<br>
			<br>
			<div class='example-box-removed'></div>
			<div class='example-box'>
			</div>
			<button class='btn btn-primary add-example'>Бафтауын дӕнцӕг</button>
			<br>
			<br>
			<div class='t_mover'>
				<button class='btn btn-warning move-up' title='Уӕлӕмӕ'>/\</button>
				<button class='btn btn-warning move-down' title='Дӕлӕмӕ'>\/</button>
			</div>
		</div>
	</script>
	
	<script id="transItemTmpl" type="text/x-jquery-tmpl">
		<div class="form-group" style='position:relative;'>
			<input type="text" class="form-control input-lg translate-item" placeholder="Тӕлмац" data-new='1'>
			<span class="form-control-feedback btn btn-sm btn-danger remove-translate" title='Айсын'>x</span>
			<div class='form-control-feedback w_mover'>
				<a class="btn btn-sm btn-warning move-up" title='Уӕлӕмӕ'>/\</a>
				<a class="btn btn-sm btn-warning move-down" title='Дӕлӕмӕ'>\/</a>
			</div>
		</div>
	</script>
	
	<script id="exampleItemTmpl" type="text/x-jquery-tmpl">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-addon">ӕ</div>
				<input type="text" class="form-control input-lg example" placeholder="Дӕнцӕг" data-new='1'>
				<span class="form-control-feedback btn btn-sm btn-danger remove-example" title='Айсын'>x</span>
				<div class='form-control-feedback w_mover'>
					<a class="btn btn-sm btn-warning move-up" title='Уӕлӕмӕ'>/\</a>
					<a class="btn btn-sm btn-warning move-down" title='Дӕлӕмӕ'>\/</a>
				</div>
			</div>
		</div>
	</script>
</body></html>