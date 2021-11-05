/*
	App
*/
function loadApp() {

 	$('#canvas').fadeIn(1000)
	var flipbook = $('.magazine')

 	// Проверяем, загрузились ли CSS стили
	
	if (flipbook.width()==0 || flipbook.height()==0) {
		setTimeout(loadApp, 10)
		return
	}
	
	var page_count = flipbook.data().page_count + 4
	
	// Создаем книгу

	flipbook.turn({
			
		// Magazine width

		width: 922,

		// Magazine height

		height: 600,

		// Duration in millisecond

		duration: 1000,

		// Hardware acceleration

		acceleration: !isChrome(),

		// Enables gradients

		gradients: true,
		
		// Auto center this flipbook

		autoCenter: true,

		// Elevation from the edge of the flipbook when turning a page

		elevation: 50,

		// The number of pages

		pages: page_count,

		// Events

		when: {
			turning: function(event, page, view) {
				var book = $(this),
				currentPage = book.turn('page'),
				pages = book.turn('pages')
		
				setTimeout(function(){
					if (zvuk.readyState == 4){
						zvuk.play()
					}
				}, 10)
		
				// Show and hide navigation buttons

				disableControls(page)
			},

			turned: function(event, page, view) {
				disableControls(page)

				$(this).turn('center')

				if (page==1) { 
					$(this).turn('peel', 'br')
				}

			},

			missing: function (event, pages) {
				// Добавление страниц, если они еще не загружены
				for (var page of pages) 
					addPage(page, $(this))
			}
		}

	})

	// Zoom.js

	$('.magazine-viewport').zoom({
		flipbook: $('.magazine'),

		max: function() { 
			
			return 2214 / $('.magazine').width();

		}, 

		when: {

			swipeLeft: function() {

				$(this).zoom('flipbook').turn('next');

			},

			swipeRight: function() {
				
				$(this).zoom('flipbook').turn('previous');

			},
			zoomIn: function () {

				$('.made').hide();
				$('.magazine').removeClass('animated').addClass('zoom-in');
				$('.zoom-icon').removeClass('zoom-icon-in').addClass('zoom-icon-out');
				
				if (!window.escTip && !$.isTouch) {
					escTip = true;

					$('<div />', {'class': 'exit-message'}).
						html('<div>Press ESC to exit</div>').
							appendTo($('body')).
							delay(2000).
							animate({opacity:0}, 500, function() {
								$(this).remove();
							});
				}
			},

			zoomOut: function () {

				$('.exit-message').hide();
				$('.made').fadeIn();
				$('.zoom-icon').removeClass('zoom-icon-out').addClass('zoom-icon-in');

				setTimeout(function(){
					$('.magazine').addClass('animated').removeClass('zoom-in');
					resizeViewport();
				}, 0);

			}
		}
	});


	// Используем кнопки со стрелками для листания страниц

	$(document).keydown(function(e){
		var previous = 37, next = 39, find = 49

		switch (e.keyCode) {
			case previous:

				// left arrow
				$('.magazine').turn('previous')
				e.preventDefault()

			break
			case next:

				//right arrow
				$('.magazine').turn('next')
				e.preventDefault()

			break
			case find:
				$('.magazine').turn('page', 2)
				e.preventDefault()
			break
		}
	})

	// Перерисовка книги при изменении размеров страницы
	
	$(window).resize(function() {
		resizeViewport()
	}).bind('orientationchange', function() {
		resizeViewport()
	})

	// События для кнопки следующая страница

	$('.next-button').bind($.mouseEvents.over, function() {
		
		$(this).addClass('next-button-hover')

	}).bind($.mouseEvents.out, function() {
		
		$(this).removeClass('next-button-hover')

	}).bind($.mouseEvents.down, function() {
		
		$(this).addClass('next-button-down')

	}).bind($.mouseEvents.up, function() {
		
		$(this).removeClass('next-button-down')

	}).click(function() {
		
		$('.magazine').turn('next')

	})

	// События для кнопки предыдущая страница
	
	$('.previous-button').bind($.mouseEvents.over, function() {
		
		$(this).addClass('previous-button-hover')

	}).bind($.mouseEvents.out, function() {
		
		$(this).removeClass('previous-button-hover')

	}).bind($.mouseEvents.down, function() {
		
		$(this).addClass('previous-button-down')

	}).bind($.mouseEvents.up, function() {
		
		$(this).removeClass('previous-button-down')

	}).click(function() {
		
		$('.magazine').turn('previous')

	})

	
	resizeViewport()

	$('.magazine').addClass('animated')

	$(function(){
		$("#find").keydown(function(e){
			e.stopPropagation()	
		})
		
		// Переход к нужной странице, если она задана
		if (flipbook.data().finded_page) {
			goToPage(1*flipbook.data().finded_page + 2)
		}
		
		$(".search-icon").click(function(){
			goToPage(2)
			setTimeout(function(){
				$("#find").focus()
			}, 1300)
		})
		
		$(".ae").click(function(){
			if ($.ztimeout) {
				clearTimeout($.ztimeout)
			}
			var find = $("#find")
			var text = find.val()
			var selStart = find[0].selectionStart
			var ae = $(this).find('span').text()
			if (selStart == 0) {
				ae = ae.toUpperCase()
			}
			find.val( text.slice(0, selStart) + ae + text.slice(selStart) )
			find.focus().keyup();
			return false
		})
	})
	
	// Подключаем звук перелистывания страницы
	
	var zvuk = new Audio('/s/zvuk.mp3')
	zvuk.volume = 0.2
	$('body').append(zvuk)
}

// Событие нажатия кнопки на поле поиска слова

$('body').on('keydown', '#find', function(e){
	e.stopPropagation()
})

$('body').on('keyup', '#find', function(e){
	if ($.ztimeout) {
		clearTimeout($.ztimeout)
	}
	
	var word = $(this).val()
	if (word.indexOf('АЕ') >= 0 || word.indexOf('Ае') >= 0) {
		$(this).val(word.replace('АЕ', 'Ӕ').replace('Ае','Ӕ'))
	}
	if (word.indexOf('ае') >= 0) {
		$(this).val(word.replace('ае', 'ӕ'))
	}
	word = $(this).val()
	
	if (word == '') {
		clearTimeout($.ztimeout)
		$(".words-list").html('')
		$("form .p2").hide()
		$("form .p1").show()
		return
	}
	
	$("form .p1").hide()
	
	$.ztimeout = setTimeout(function(){
		$(".words-list").html('')
		$.ajax({
			url: 'find/' + word,
			success: function(words) {
				if (words.length == 0) {
					$("form .p2").show()
				} else {
					$("form .p2").hide()
					words.forEach(function(word, number){
						var translate = ''
						if (word['translate']) {
							translate = ' - ' + word['translate']
						}
						$("<li><a onclick='goToPage("+(1*word['page'] + 2) +")'>"+word['word']+"</a>" + translate
							+"<span>"+word['page']+" сыф</span></li>").appendTo(".words-list")
					})
				}
			}
		})
	}, 300)
})

$('body').on('click', 'ol li a', function(){
	var word = $(this).attr('href')
	$.ajax({
		url: 'find/' + word,
		async: false,
		success: function(words) {
			if (words.length > 0) {
				goToPage(1 * words[0]['page'] + 2)
			}
		}
	})
	return false
})


$('body').on('click', 'h2.sounded', function(){
	if ($.sounded) return
	$.sounded = true
	
	var sound = new Audio('/s/' + $(this).data().sound)
	sound.addEventListener('pause', function(e){
		$.sounded = false
	})
	sound.volume = 0.9
	playSound(sound)
})

function playSound(sound) {
	setTimeout(function(){
		if (sound.readyState == 4){
			sound.play()
		} else {
			playSound(sound)
		}
	}, 10)
}

$('#canvas').hide()

// Загружаем HTML4 версию, если CSS transform не поддерживается браузером

yepnope({
	test: Modernizr.csstransforms,
	yep:  ['js/lib/turn.min.js'],
	nope: ['js/lib/turn.html4.min.js'],
	both: ['js/lib/zoom.min.js', 'js/book.js', 'css/book.css'],
	complete: loadApp
})
