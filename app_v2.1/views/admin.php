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
	
    <!-- Bootstrap core CSS -->
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
	.table td {
		text-align: left;
	}
	.table tr {
		cursor: pointer;
	}
	</style>
  </head>

  <body data-role='<?=$user['role']?>'>

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
        <h1>Ныхӕсттӕ</h1>
		<p>Ацы сыфыл ис ссарын ныхӕсттӕ, кӕцытӕ бафтыдтой дызрдуатмӕ</p>
		<hr>
		
		<div class="form-group">
			<div class="input-group">
			  <div class="input-group-addon">ӕ</div>
			  <input type="text" class="form-control input-lg" id="find" placeholder="Ныхас 'ссарын" autocomplete='off'>
			</div>
		  </div>
		<table class='table table-striped table-hover'></table>
	  </div>

    </div>

    <script src="/js/extras/jquery.min.1.7.js"></script>
    <script>
	$(function(){
		$(".input-group-addon").click(function(){
			var input = $(this).next();
			var text = input.val();
			if (text == '') {
				text = text + $(this).text().toUpperCase();
			} else {
				text = text + $(this).text();
			}
			input.val(text);
			$(input).keyup().focus();
		})
	})
	$("button.navbar-toggle").click(function(){
		$("#navbar").toggleClass("collapse")
	})
	$("#find").keyup(function(){
		var find = $(this).val().trim()
		if ($.ztimeout) {
			clearTimeout($.ztimeout)
		}
		$(".table").empty()
		if (find != '')
		$.ztimeout = setTimeout(function(){
			$.ajax({
				url: '/find/' + find,
				success: function(words) {
					for (word of words) {
						var text = "<b>" + word['word'] + "</b>"
						if (word['translate']) {
							text += ' - ' + word['translate']
						}
						var btns = "";
						if ($('body').data().role == 0) {
							btns = "<td width='50'><a href='/admin/changeImage/" + word['word'] +"'>къам</a></td>" +
									"<td width='50'><a href='/admin/changeSound/" + word['word'] +"'>зӕл</a></td>";
						}
						$("<tr><td class='item'>" + text + "</td>" + btns + "</tr>").appendTo('table.table')
					}
				}
			})
		}, 300)
	});
	$("body").on('click', '.table .item', function(){
		var word = $(this).find("b").text();
		location.href = "/admin/change/" + word;
	});
	</script>
</body></html>