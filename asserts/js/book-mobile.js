(function($){
	$(function(){
		$("#find").focus(function(){
			$(".wrapper").addClass('find-focused')
		}).focusout(function(){
			$.zfocus_to = setTimeout(function(){
				$(".wrapper").removeClass('find-focused')
			},50)
		}).keyup(function(){
			if ($.ztimeout) {
				clearTimeout($.ztimeout)
			}
			var word = $(this).val().trim()
			$.ztimeout = setTimeout(function(){
				getWords(word);
			}, 350)
		})
		
		function getWords(word) {
			if (word == '')	{
				$(".finded-words").empty()
				return
			}
			$.ajax({
				url: '/find/' + word,
				success: function(words) {
					$(".finded-words").empty()
					words.forEach(function(word, number){
						var translate = ''
						if (word['translate']) {
							translate = ' - ' + word['translate']
						}
						$("<li><a onclick='goToPage("+(1*word['page']) +")'>"+word['word']+"</a>" + translate
							+"<span onclick='goToPage("+(1*word['page']) +")'>"+word['page']+" сыф</span></li>").appendTo(".finded-words")
					})
				}
			})
		}
		
		$("#ae").click(function() {
			if ($.zfocus_to) {
				clearTimeout($.zfocus_to);
			}
			var find = $("#find")
			var text = find.val()
			var selStart = find[0].selectionStart
			var ae = $(this).find('span').text()
			if (selStart == 0) {
				ae = ae.toUpperCase()
			}
			find.val( text.slice(0, selStart) + ae + text.slice(selStart) )
			getWords(find.val())
			find.focus()
			return false;
		})
	
		function loadPage(number, asunc=true) {
			$.ajax({
				url: '/page/' + (1*number+2),
				async: asunc,
				success: function(page) {
					page = $(page).addClass('page').addClass('page-'+number);
					page.data().number = number
					$("#pages").append( page )
				}
			})
		}
		
		loadPage(1, false)
		loadPage(2)
		loadPage(3)
		
		function nextPage() {
			var cur_page = $("#pages").data().current_page*1
			var page_count = $("#pages").data().page_count*1
			if (cur_page == page_count + 1) return
			goToPage(cur_page + 1)
		}
		
		function prevPage() {
			var cur_page = $("#pages").data().current_page*1
			if (cur_page == 0) return
			if (cur_page == 1) {
				playZvuk();
				var w = $(document).width()
				$(".viewer").addClass('old').css("left", w)
				setTimeout(function(){
					$("#pages").data().current_page = 0;
					$(".viewer.old").remove()
				}, 400)
				return
			}
			goToPage(cur_page - 1)
		}
		
		window.goToPage = function(page) {
			if ($.zmutex) return
			var cur_page = $("#pages").data().current_page*1
			if (cur_page == page) return;
			$.zmutex = true;
			$(".viewer").addClass('old')
			var viewer = $("<div />")
			viewer.addClass('viewer').addClass('transition')
			var $page = $("#pages .page-" + page)
			if ($page.length == 0) {
				loadPage(page, false)
			}
			$page = $("#pages .page-" + page)
			
			viewer.append( $page.clone() )
			
			var w = $(document).width()
			
			if (cur_page > page) {
				w = -w;
			}
			
			viewer.css("left", w);
			
			$(".wrapper").append( viewer )
			
			setTimeout(function(){
				playZvuk();
				viewer.css("left", 0);
				$("#pages").data().current_page = page;
				setTimeout(function(){
					$(".viewer.old").remove();
					$.zmutex = false;
				}, 400);
			}, 100)
		}
		
		function playZvuk(){
			setTimeout(function(){
				if (zvuk.readyState == 4){
					zvuk.play()
				}
			}, 10)
		}
		
		$('body').on('click', 'ol li a', function(){
			var word = $(this).attr('href')
			goToWord( word )
			return false;
		})
		
		function goToWord(word) {
			$.ajax({
				url: 'find/' + word,
				async: false,
				success: function(words) {
					if (words.length > 0) {
						goToPage(1 * words[0]['page'])
					}
				}
			})
			return false
		}
		
		$('body').on('click', 'h2.sounded', function(){
			if ($.sounded) return
			$.sounded = true
			
			var sound = new Audio('/s/' + $(this).data().sound)
			sound.addEventListener('pause', function(e){
				$.sounded = false
			})
			sound.volume = 0.7
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
		
		function swipedetect(el, callback){
  
			var touchsurface = el,
			swipedir,
			startX,
			startY,
			distX,
			distY,
			threshold = 100, //требуемое минимальное пройденное расстояние, чтобы считаться свайпом
			restraint = 100, // максимальное расстояние, разрешенное одновременно в перпендикулярном направлении
			allowedTime = 300, // максимальное время, разрешенное для преодоления этого расстояния
			elapsedTime,
			startTime,
			handleswipe = callback || function(swipedir){}
		  
			touchsurface.addEventListener('touchstart', function(e){
				var touchobj = e.changedTouches[0]
				swipedir = 'none'
				dist = 0
				startX = touchobj.pageX
				startY = touchobj.pageY
				startTime = new Date().getTime() // record time when finger first makes contact with surface
			}, false)
		  
			touchsurface.addEventListener('touchend', function(e){
				var touchobj = e.changedTouches[0]
				distX = touchobj.pageX - startX // get horizontal dist traveled by finger while in contact with surface
				distY = touchobj.pageY - startY // get vertical dist traveled by finger while in contact with surface
				elapsedTime = new Date().getTime() - startTime // get time elapsed
				if (elapsedTime <= allowedTime){ // first condition for awipe met
					if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint){ // 2nd condition for horizontal swipe met
						swipedir = (distX < 0)? 'left' : 'right' // if dist traveled is negative, it indicates left swipe
					}
					else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint){ // 2nd condition for vertical swipe met
						swipedir = (distY < 0)? 'up' : 'down' // if dist traveled is negative, it indicates up swipe
					}
				}
				handleswipe(swipedir)
			}, false)
		}
	
		swipedetect($("body")[0], function(swipedir){
			if ($(".wrapper").hasClass('find-focused')) return
			if (swipedir =='left') nextPage()
			if (swipedir =='right') prevPage()
		})
	
		var zvuk = new Audio('/s/zvuk.mp3')
		zvuk.volume = 0.2
		$('body').append(zvuk)
		
		var finded = $("#finded");
		if (finded.length != 0) {
			goToWord( finded.val() )
		}
	})
})(jQuery)