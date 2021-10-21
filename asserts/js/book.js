function addPage(page, book) {
	$.ajax({
		url:'/page/'+page,
		async: false,
		success: function(data) {
			var element = $('<div />', {})

			// Add the page to the flipbook
			if (book.turn('addPage', element, page)) {
				element.html(data)
			}
		}
	})
}

function isChrome() {
	return navigator.userAgent.indexOf('Chrome')!=-1
}

function disableControls(page) {
		if (page==1)
			$('.previous-button').hide()
		else
			$('.previous-button').show()
					
		if (page==$('.magazine').turn('pages'))
			$('.next-button').hide()
		else
			$('.next-button').show()
}

// Set the width and height for the viewport

function resizeViewport() {
	var width = $(window).width(),
		height = $(window).height(),
		options = $('.magazine').turn('options')

	$('.magazine').removeClass('animated')

	$('.magazine-viewport').css({
		width: width,
		height: height
	})
	
	if ($('.magazine').turn('zoom')==1) {
		var bound = calculateBound({
			width: options.width,
			height: options.height,
			boundWidth: Math.min(options.width, width),
			boundHeight: Math.min(options.height, height)
		})

		if (bound.width%2!==0)
			bound.width-=1

			
		if (bound.width!=$('.magazine').width() || bound.height!=$('.magazine').height()) {

			$('.magazine').turn('size', bound.width, bound.height)

			if ($('.magazine').turn('page')==1)
				$('.magazine').turn('peel', 'br')

			$('.next-button').css({height: bound.height, backgroundPosition: '-38px '+(bound.height/2-32/2)+'px'})
			$('.previous-button').css({height: bound.height, backgroundPosition: '-4px '+(bound.height/2-32/2)+'px'})
		}

		$('.magazine').css({top: -bound.height/2, left: -bound.width/2})
	}

	var magazineOffset = $('.magazine').offset(),
		boundH = height - magazineOffset.top - $('.magazine').height(),
		marginTop = (boundH - $('.thumbnails > div').height()) / 2

	if (marginTop<0) {
		$('.thumbnails').css({height:1})
	} else {
		$('.thumbnails').css({height: boundH})
		$('.thumbnails > div').css({marginTop: marginTop})
	}

	if (magazineOffset.top<$('.made').height())
		$('.made').hide()
	else
		$('.made').show()

	$('.magazine').addClass('animated')
	
}

// Calculate the width and height of a square within another square

function calculateBound(d) {
	
	var bound = {width: d.width, height: d.height}

	if (bound.width>d.boundWidth || bound.height>d.boundHeight) {
		
		var rel = bound.width/bound.height
		
		if (d.boundWidth/rel>d.boundHeight && d.boundHeight*rel<=d.boundWidth) {
			
			bound.width = Math.round(d.boundHeight*rel)
			bound.height = d.boundHeight

		} else {
			
			bound.width = d.boundWidth
			bound.height = Math.round(d.boundWidth/rel)
		
		}
	}
	setFontSize(bound)
	
	return bound
}

// Подстройка размеров шрифтов в зависимости от площади листа

function setFontSize(bound) {
	for (var i = 10; i < 17; i++) 
		$('body').removeClass('set' + i)
	var plochad = bound.width * bound.height
	//console.log(plochad)
	if (plochad > 500000) return
	if (plochad > 400000) {
		$('body').addClass('set16');
		return
	}
	if (plochad > 300000) {
		$('body').addClass('set14');
		return
	}
	if (plochad > 230000) {
		$('body').addClass('set13');
		return
	}
	if (plochad > 190000) {
		$('body').addClass('set12');
		return
	}
	if (plochad > 130000) {
		$('body').addClass('set11');
		return
	}
	$('body').addClass('set10');
}

// Переход на страницу по номеру

function goToPage(page) {
	var book = $('.magazine'), secondTurn = false
	if ($(".page.p" + page).length == 0) {
		secondTurn = true
	}
	book.turn('page', page)
	if (secondTurn) {
		setTimeout(function(){
			goToPage(page)
		}, 100)
	}
}