$(function(){
	/* input file */
	$('.input-files input[type=file]').change(function(e){
		$(this).prevAll('input[type="text"]').val($(this).val());
	});
	
	//Защита email-ов от спама
	setMailBoxes();
	
	//красивые селекты
	$(".main-wrapper select").selectbox();
	
	//маска для телефона
	$('.main-wrapper input.phone').mask("+7(999) 999-99-99");
	
	//защита форм от спама
	$(".main-wrapper form.protection").each(function() {
		if($(this).find("input[name='sessid']").val())
			$(this).append("<input type='hidden' name='jssid' value='"+$(this).find("input[name='sessid']").val()+"'>");
	});
	
	//таблицы
	$(".main-wrapper table tr:odd").addClass("even");
	
	//скорость перелистывания баннеров
	var speed = 0;
	if($('input[name="ust_banners_speed"]').val() > 0)
		speed = $('input[name="ust_banners_speed"]').val();
	
	//время перехода между баннерами
	var timeout = 0;
	if($('input[name="ust_banners_timeout"]').val() > 0)
		timeout = $('input[name="ust_banners_timeout"]').val();
	
	$("a.catalog_more").on('click', function(e) {
		e.preventDefault();
		if($(this).hasClass("less") == true) {
			$(this).removeClass("less").html("Подробнее &raquo;");
		}
		else {
			$(this).addClass("less").html("&laquo; Свернуть");
		}
		$(this).closest(".catalog-section-descr").find("p.more_description").slideToggle("slow");
	});
	
	$("select[name='quantity']").change(function() {
		$(this).closest("form[name='sort']").submit();
	});
	
	//при клике вне окна, веб-форма закрывается
	$(document).on("click", ".ui-widget-overlay", function() {
		$('#callback').dialog( "close" );
	});
	
	$(".bu-products .descr .more").on('click', function(e){
		e.preventDefault();
		if($(this).hasClass("less") == true) {
			$(this).closest(".descr").find(".full-text").hide();
			$(this).removeClass("less").html("Подробнее");
		}
		else {
			$(this).closest(".descr").find(".full-text").show();
			$(this).addClass("less").html("Свернуть");
		}
    });
	
	//смена города
	$("a#change_sity").on('click', function(e) {
		e.preventDefault();
		$('#change_town').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 700,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		$('#change_town').dialog("open");
	});
	
	$(document).on("click", ".cities .town a.select_town, .cities .autocomplete a.select_town", function(e) {
		e.preventDefault();
		if($(this).attr("id").substr(5) > 0) {
			$(".cities .town a.select_town").removeClass("active");
			$(this).addClass("active");
			$.post('/ajax/ajax.php', {
					FormType: 'setTown',
					ID: parseInt($(this).attr("id").substr(5))
				}, 
				function(data) {
					if(data.STATUS == 1)
						window.location.reload();
				},
				'json'
			);
		}
	});
	
	$('input.autocomplete.town').keyup(function() {
		var el = $(this);
		$.post(
			'/ajax/ajax.php', 
			{
				FormType: 'getTownArray', 
				SearchText: $(this).val()
			}, 
			function(data) {
				if(data.STATUS > 0) {
					if(data.TOWNS.length > 0) {
						el.closest("div").find(".b-places").empty();
						for(var i = 0; i < data.TOWNS.length; i++) {
							el.closest("div").find(".b-places").append("<a class='select_town' id='town_"+data.TOWNS[i].ID+"' href='#'>"+data.TOWNS[i].NAME+"</a>");
						}
						var width = el[0].offsetWidth-2;
						var left = el.position().left;						
						if(width > 0)
							el.closest("div").find(".b-places").css("width", width+'px').css("margin-left", left+"px").slideDown("slow");
					}
					else 
						el.closest("div").find(".b-places").slideUp("slow");
				}
				else {
					el.closest("div").find(".b-places").slideUp("slow");
				}
			},
			'json'
		);
	});
	$(document).on("click", ".b-places a.select_town", function(e) {
		e.preventDefault();
		$(this).closest("form").find("input.autocomplete.town").val($(this).text());
		$(this).closest(".b-places").slideUp("slow");
	});
	
	$(".callback-link").on("click", function(e) {
		e.preventDefault();
		$('#callback').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 562,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		$('#callback').dialog("open");
		$('input.phone').mask("+7(999) 999-99-99");
		$("select").selectbox();
	})
	
	//быстрый выбор машины
	$(".quick_select_techn").on("click", function(e) {
		e.preventDefault();
		$('#quick_select_techn').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 870,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		$('#quick_select_techn').dialog("open");
	});
	
	$(document).on("click", ".catalog_quick a.open_section", function(e) {
		e.preventDefault();
		var id = $(this).attr("id").substr(2);
		$("#line_"+id).slideToggle("slow");
	});
	
	//подсказки в форме заказа обратного звонка	
	$(document).on("click", "p.hints a.callback_hint", function(e) {
		e.preventDefault();
		var text = $(this).text();
		$(this).closest('form').find('textarea').val("Интересует "+text);
	});
	
	//Проверка форм на ошибки
	$(document).on("focus", ".main-wrapper form input, .main-wrapper form textarea", function(e) {
		if($(this).hasClass("error")) {
			$(this).val("");
			$(this).removeClass("error");
		}
	});
	
	//сабмит формы - проверка заполненности полей
	$(document).on("click", ".main-wrapper form [type='submit']", function(e) {
		e.preventDefault();
		var el = $(this);
		error = CheckForm($(this).closest("form"));		
		if(error == false) {
			if($(this).closest("form").attr("name") == "order_catalog") {
				$(this).closest("form").find("input[name='URL']").val(window.location.href);
				var data = $(this).closest("form").serialize();
				$.post('/ajax/ajax.php', 
					data, 
					function(data) {
						if(data.SUCCESS_MESSAGE) {
							$('#order_catalog').dialog("close");
							$('#callback').dialog({
								dialogClass: 'dialog',
								autoOpen: false,
								width: 562,
								resizable: false,
								draggable: false,		
								position: "center",
								modal: true,
								closeText: "Закрыть",
								close: function( event, ui ) {
									window.location.reload();
								}
							});
							$("#callback").html('<div class="title">Спасибо за Ваше обращение!</div><h2>Мы свяжемся с Вами в ближайшее время!</h2><p>С уважением, Универсал-Спецтехника</p>');
							$('#callback').dialog("open");
						}
					},
					'json'
				);
			}
			else {
				$(this).closest("form").submit();
			}
		}
	});
	
	$(document).on("click", "form[name='callback'] [type='submit']", function(e) {
		e.preventDefault();
		var el = $(this);
		var error = CheckForm($("form[name='callback']"));
		if(error == true) {
			$.post('/ajax/ajax.php', {
					FormType: 'CallbackSend',
					NAME: $("[name='NAME']").val(),
					PHONE: $("[name='PHONE']").val(),
					TOWN: $("[name='TOWN']").val(),
					TIME: $("[name='TIME']").val(),
					COMMENT: $("[name='COMMENT']").val()
				}, 
				function(data) {
					if(data.STATUS == 1) {
						$("#callback").dialog({
							close: function( event, ui ) {
								window.location.reload();
							}
						});
						$("#callback").html('<div class="title">Спасибо за Ваше обращение!</div><h2>Мы свяжемся с Вами в ближайшее время!</h2><p>С уважением, Универсал-Спецтехника</p>');
					}
				},
				'json'
			);
		}
	});
	
	//открытие формы для заказа Б/У техники
	$(document).on("click", ".order-btn button", function(e) {
		e.preventDefault();
		$('#order_catalog').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 562,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		var lot = $(this).closest(".order-btn").find("input[name='lot']").val();
		var section_name = $(this).closest(".bu-products").find('input[name="section_name"]').val();
		$('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", section_name+', лот '+lot);
		$('input.phone').mask("+7(999) 999-99-99");
		$('#order_catalog').dialog("open");
	});	
	
	//переключение вкладок в полезной информации
	$(".useful-info .tags a").on("click", function(e) {
		e.preventDefault();
		showDescriptioFromUseful($(this).attr("id").substr(8));
	});
	$(".useful-info .tags a").each(function() {
		if($(this).hasClass("active") == true)
			showDescriptioFromUseful($(this).attr("id").substr(8));
	});

	//подгрузка видео
	$(document).on("click", "a.video_show", function(e) {
		e.preventDefault();
		var ID = $(this).attr("id").substr(6);
		$.post('/player.php', {id:ID}, 
			function(data) {
				$('#video_player').dialog({
					dialogClass: 'dialog',
					autoOpen: false,
					width: 400,
					resizable: false,
					draggable: false,		
					position: "center",
					modal: true,
					closeText: "Закрыть",
					close: function( event, ui ) {
						$("#video_player").dialog( "destroy" );
					}
				});
				$('#video_player').html(data);
				$('#video_player').dialog("open");
			},
			'html'
		);
	});	
	
	//заказ акции
	$(".action-timer button.order").on("click", function(e) {
		e.preventDefault();
		$('#order_catalog').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 562,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});	
		var name = $(this).closest("div").find("input[name='action_name']").val();
		$('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", name);
		$('input.phone').mask("+7(999) 999-99-99");
		$('#order_catalog').dialog("open");
	});
	
	/* Заказ техники в аренду */
	$(".rent-item button.silver.order").on("click", function(e) {
		e.preventDefault();
		$('#order_catalog').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 562,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});	
		var name = $(this).closest("td").find("input[name='name']").val();
		$('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", name);
		$('input.phone').mask("+7(999) 999-99-99");
		$('#order_catalog').dialog("open");
	});
	
	
	/* Карусель в акциях */
	$('#action_banner').cycle({
		fx: 'scrollHorz',
		timeout: 0,
		speed: speed,
		prev: '.prev',
		next: '.next',
		pager: '.pagging',
		pagerAnchorBuilder: pagerFactory
	});
	function pagerFactory(idx, slide) {
		return '<li></li>';
	};
	
	/* Карусель в филиалах */
	$('#filial_banner').cycle({
		fx: 'scrollHorz',
		timeout: timeout,
		speed: speed,
		prev: '.prev',
		next: '.next',
		pager: '.pagging',
		pagerAnchorBuilder: pagerFactory
	});
	function pagerFactory(idx, slide) {
		return '<li></li>';
	};
	
	// карьера в УСТ
	$('.slider-career').each(function() {
		var prev = "#prev_"+$(this).attr("id");
		var next = "#next_"+$(this).attr("id");
		$(this).cycle({
			fx: 'scrollHorz',
			timeout: timeout,
			speed: speed,
			prev: prev,
			next: next
		});
	});
	
	// верстка поля типа file
	$('#imulated').change(function(){    
		$('.im_input input').val($(this).val());
	});
	$('.im_input input').click(function(){    
		$('#imulated').trigger('click');
	});
	
	//всплывающее окно на странице "Сервис" - должно всплывать только 2 раза
	if($('input[name="count_service_up"]').size() > 0) {
		$('#service').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 730,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть",
			close: function( event, ui ) {
				$.post(
					'/ajax/ajax.php', 
					{
						FormType:"setServiceSession"
					}, 
					function(data) {
						$("#service").dialog("close");
					},
					'json'
				);
			}
		});		
		if($('input[name="count_service_up"]').val() < 2)
			$("#service").dialog("open");
	}
	
	$('#service_centers').dialog({
		dialogClass: 'dialog',
		autoOpen: false,
		width: 650,
		resizable: false,
		draggable: false,		
		position: "center",
		modal: true,
		closeText: "Закрыть",
		open: function( event, ui ) {
			$('#pane').jScrollPane({
				contentWidth: 650
			});
		}
	});
	
	$('#service_phone').dialog({
		dialogClass: 'dialog',
		autoOpen: false,
		width: 650,
		resizable: false,
		draggable: false,		
		position: "center",
		modal: true,
		closeText: "Закрыть",
		open: function( event, ui ) {
			$('#pane_phone').jScrollPane({
				contentWidth: 650
			});
		}
	});
	
	$('a#service_address').on('click', function(e) {
		e.preventDefault();
		$('#service_centers').dialog("open");		
	});
	
	$('a#service_phones').on('click', function(e) {
		e.preventDefault();
		$('#service_phone').dialog("open");		
	});
	
	$(".service form[name='town'] [type='submit']").on('click', function() {
		var town = $(this).closest("form").find("input[name='select_town']").val();
		$.post(
			'/ajax/ajax.php', 
			{
				FormType:"getServiceCenters",
				Town: town
			}, 
			function(data) {				
				$("#pane").html(data);
				setMailBoxes();
				$('#pane').reinitialise();
			},
			'html'
		);
		return false;
	});
	
	//баннер на второстепенных страницах
	if ($("#secondary_banner").length) {
		if($('input[name="count_banner"]').val() == 1)
			timeout = 0;
		
		$('#secondary_banner').cycle({
			fx: 'scrollHorz',
			timeout: timeout,
			speed: speed,
			prev: '.prev_secondary_banner',
			next: '.next_secondary_banner',
			pager: '#nav_secondary_banner',
			pagerAnchorBuilder: pagerFactory
		});
		function pagerFactory(idx, slide) {
			return '<li></li>';
		};
    }
	
	/* Баннер на главной странице */
	if ($("#slider").length) {
		if(timeout == 0)
			timeout = 300;
        $('#slider').nivoSlider({
            effect: 'fade',
            controlNavThumbs: false,
            prevText: '',
            nextText: '',
			//manualAdvance: true
            animSpeed: 100,
            pauseTime: timeout
        });
    }
	
	/* Выпадающий каталог на главной странице при наведении на бренды */
	//карусель брендов в футере
	$(".inside-logos").hide();
	setTimeout(function() {
		$(".inside-logos").show();
		$("#carousel_bottom").jCarouselLiteCustom({
			btnNext: ".next",
			btnPrev: ".prev",
			mouseWheel: true
		});
	}, 2000);
	var intervalID;
	$(".inside-logos li").hover(
		function () {
			$(".inside-logos li").removeClass("active");
			ChangePicture($(this).find("img"));
			if($(this).attr("id") && $('.hidden_catalog.brand_'+$(this).attr("id").substr(6)).size() > 0) {
				$(this).addClass("active");
				var popup = $('.hidden_catalog.brand_'+$(this).attr("id").substr(6));
				if($('.hidden_catalog.brand_'+$(this).attr("id").substr(6)).size() > 0)	
					intervalID=setTimeout(function() { $('.hidden_catalog').fadeOut(); popup.fadeIn(); }, 300);
				else 
					$('.hidden_catalog').fadeOut();
			}
		},
		function () {
			var li_element = $(this);
						
			
			if($(this).is(':hover') == false) {
				ChangePicture(li_element.find("img"));				
				$('.hidden_catalog.'+li_element.attr("id")).mouseenter(function(){
					li_element.addClass("active");
					ChangePicture(li_element.find("img"));
				}).mouseleave(function(){
					li_element.removeClass("active");
					ChangePicture(li_element.find("img"));
					$('.hidden_catalog').fadeOut();
					clearInterval(intervalID);
				});
			}
			/*else {
				$('.hidden_catalog.'+li_element.attr("id")).mousemove(function(){
					li_element.addClass("active");
				}).mouseleave(function(){
					li_element.removeClass("active");
					ChangePicture(li_element.find("img"));
					$('.hidden_catalog').fadeOut();
					clearInterval(intervalID);
				});
			}*/
		}
	);
	
	/* Карусель акций на главной странице */
	$("#actions_main").jCarouselLite({
	    btnNext: ".jcarousel-next",
	    btnPrev: ".jcarousel-prev",
		mouseWheel: true,
		visible: 4,
		circular: false
	});
	
	/* Карусель на странице "О компании" */
	$("#about_company").jCarouselLite({
	    btnNext: ".jcarousel-next",
	    btnPrev: ".jcarousel-prev",
		mouseWheel: true,
		visible: 4,
		circular: false
	});
	
	//всплывающие окна с фотографиями
	$("a.fancybox").fancybox({
		padding: 42
	});
	
	/* Сворачивание/Разворачивание текста на главной странице */
	$(".site-descr .show-more a").click(function(e) {
		e.preventDefault();
		var moreText = $(this).closest(".site-descr").find(".more-text");
		var overlate = $(this).closest(".site-descr").find(".overlay-grad");
		if($(this).hasClass("collapse")) {
			//если свернут
			$(this).text("Развернуть").removeClass("collapse");
			moreText.slideToggle("slow", function() {
				overlate.show();
			});
		}
		else {
			//если развернут
			$(this).text("Свернуть").addClass("collapse");
			moreText.slideToggle("slow", function() {
				overlate.hide();
			});
		}
    });
	
	$("#main_page_information, #tabs").tabs({ 
		event: "mouseover", 
		activate: function( event, ui ) {
			if(ui.newTab.find('a').attr("link")) {
				var href = ui.newTab.find('a').attr("link");
				var text = ui.newTab.find('a').text();
				ui.newTab.closest("ul").find('a#selected').attr("href", href);
				ui.newTab.closest("ul").find('a#selected').attr("title", text);
			}
			ui.newTab.closest("ul").find('a#selected').animate(
				{
					width : ui.newTab.width(),
					left: ui.newTab.position().left
				}, {
					duration : 300,
					easing : "swing",
					queue : false
				}
			);
		},
		create: function( event, ui ) {
			if(ui.tab.find('a').attr("link")) {
				var href = ui.tab.find('a').attr("link");
				var text = ui.tab.find('a').text();
				ui.tab.closest("ul").find('a#selected').attr("href", href);
				ui.tab.closest("ul").find('a#selected').attr("title", text);
			}
			ui.tab.closest("ul").find('a#selected').css("width", ui.tab.width());
			ui.tab.closest("ul").find('a#selected').css("left", ui.tab.position().left);
		}
	});
	$("#tabs-used-detail, #tabs-detail").tabs();
	
	/*Переключение вкладок в Б/У технике и Аренде*/
	if($("#tab_link").size() > 0) {
		if($("#tab_link").find("li.active_li a").attr("href")) {
			var href = $("#tab_link").find("li.active_li a").attr("href");
			var text = $("#tab_link").find("li.active_li a").text();
			$("#tab_link").find('a#selected').attr("href", href);
			$("#tab_link").find('a#selected').attr("title", text);		
		}
		$("#tab_link").find('a#selected').css("width", $("#tab_link").find("li.active_li").width());
		$("#tab_link").find('a#selected').css("left", $("#tab_link").find("li.active_li").position().left);
	}
	$("#tab_link li").hover(function() {
		if($("#tab_link").find("li.active_li a").attr("href")) {
			var href = $(this).find("a").attr("href");
			var text = $(this).find("a").text();			
			$("#tab_link").find('a#selected').attr("href", href);
			$("#tab_link").find('a#selected').attr("title", text);
		}
		$("#tab_link").find('a#selected').animate(
			{
				width : $(this).width(),
				left: $(this).position().left
			}, {
				duration : 300,
				easing : "swing",
				queue : false
			}
		);
	}, function() {
		if($("#tab_link").find("li.active_li a").attr("href")) {
			var href = $("#tab_link li.active_li a").attr("href");
			var text = $("#tab_link li.active_li a").text();			
			$("#tab_link").find('a#selected').attr("href", href);
			$("#tab_link").find('a#selected').attr("title", text);
		}
		$("#tab_link").find('a#selected').animate(
			{
				width : $("#tab_link li.active_li").width(),
				left: $("#tab_link li.active_li").position().left
			}, {
				duration : 300,
				easing : "swing",
				queue : false
			}
		);
	});
	
	/* Сворачивание меню в футере */
	$(".navs .title a").click(function(e){
		e.preventDefault();
		$(this).closest(".nav-collapsed").toggleClass("active");
		var cur = $(this).closest(".nav-collapsed").find(".collapsed");
		var expanded = $(this).closest(".nav-collapsed").find(".expanded");
		if(cur.hasClass("active") == true) {
			cur.hide().removeClass("active");
			expanded.slideToggle("slow");
		}
		else {
			expanded.slideToggle("slow", function() {
				cur.show().addClass("active");
			});
		}
		
	});
	
	/* Карусель в акциях */
	$('#carousel').cycle({
		fx: 'scrollHorz',
		timeout: timeout,
		speed: speed,
		prev: '.prev',
		next: '.next',
		pager: '.pagging',
		pagerAnchorBuilder: pagerFactory
	});
	
	$(".contacts-page .contacts-map-link").on("click", function(e) {
		e.preventDefault();
		$('#contacts-map').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 770,
			resizable: false,
			draggable: false,		
			position: "top",
			modal: true,
			closeText: "Закрыть"
		});
		$('#contacts-map').dialog("open");		
		$('.flashmap .map').flash({
			swf: '/design/images/flashmap4.swf',
			width: 770,
			height: 520,
			wmode: 'transparent'
		});
		$('.show-directs .toggler').click(function(){
			$(this).toggleClass('opened');
			$('#contacts-map .directs').slideToggle();
		});

		$('.hide-directs .toggler').click(function(){
			$('.show-directs .toggler').toggleClass('opened');
			$('#contacts-map .directs').slideToggle();
		});
	});
	
	$(".clients-page a.show-more").on("click", function(e) {
		e.preventDefault();
		var element = $(this);
		if(element.hasClass("opened") == true)
			element.removeClass("opened").html("Показать все<span></span>");
		else
			element.addClass("opened").html("Свернуть<span></span>");
		$(".clients-page .more").slideToggle("slow");
	});
	$(".text a.show-more").on("click", function(e) {
		e.preventDefault();	
		if($(this).hasClass("opened") == true)
			$(this).removeClass("opened");
		else
			$(this).addClass("opened");
		$(".text .more").slideToggle("slow");
	});
	
	//история
	$('.history-page .pages .page.active .history_carousel').each(function() {		
		var prev = "#prev_"+$(this).attr("id");
		var next = "#next_"+$(this).attr("id");
		$(this).jCarouselLite({
			btnPrev: prev,
			btnNext: next,
			mouseWheel: true,
			visible: 4,
			circular: false
		});
	});
	
	/* История */
	$('.years-tabs .year.active').each(function() {
		yearClick($(this).data("tab"));
		$('.history-page .pages .page.active .history_carousel').each(function() {		
			var prev = "#prev_"+$(this).attr("id");
			var next = "#next_"+$(this).attr("id");
			$(this).jCarouselLite({
				btnPrev: prev,
				btnNext: next,
				mouseWheel: true,
				visible: 4,
				circular: false
			});
		});
	});
	$('.years-tabs .year').click(function() {
		yearClick($(this).data("tab"));
		$('.history-page .pages .page.active .history_carousel').each(function() {		
			var prev = "#prev_"+$(this).attr("id");
			var next = "#next_"+$(this).attr("id");
			$(this).jCarouselLite({
				btnPrev: prev,
				btnNext: next,
				mouseWheel: true,
				visible: 4,
				circular: false
			});
		});
        return false;
    });
	
	/* Пройти опрос */
	$('#take-survey').on('click', function(e) {
		e.preventDefault();
		$('#survey').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 670,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		$('#survey').dialog("open");
		/* Установка ответов на вопросы */
		$(".survey .line span.regular").on("click", function() {
			$(".survey .line span.regular").removeClass("active");
			$(this).addClass("active");
			$(this).closest(".line").find('input[type="hidden"]').val($(this).text());
		});
		$(".survey .line a.answer").on("click", function(e) {
			e.preventDefault();
			$(this).closest(".line").find("a.answer").removeClass("active");
			$(this).addClass("active");
			$(this).closest(".line").find('input[type="hidden"]').val($(this).text());
			
		});
	});
	
	$(".survey form [type='submit']").on("click", function(e) {
		e.preventDefault();
		var error = CheckForm($(this).closest("form"));
		if(error == false) {
			var data = $(this).closest("form").serialize();
			$.post('/ajax/ajax.php', 
				data, 
				function(data) {
					if(data.GRATITUDE) {
						$('#survey').dialog("close");
						$('#survey_ok').dialog({
							dialogClass: 'dialog',
							autoOpen: false,
							width: 670,
							resizable: false,
							draggable: false,		
							position: "center",
							modal: true,
							closeText: "Закрыть",
							close: function( event, ui ) {
								window.location.reload();
							}
						});
						$("#survey_ok").html(data.GRATITUDE);
						$('#survey_ok').dialog("open");
					}
				},
				'json'
			);
		}
		return false;
	});
	
	/* Переключение городов в диллерах */
	$('.dealers a.open').on("click", function(e) {
		e.preventDefault();
		var id = $(this).attr("id");
		$('div.dealer.'+id).slideToggle("slow");
		$(this).toggleClass("active");
	});
	
	$(".dealers form[name='dealers'] [type='submit']").click(function() {
		$(this).closest("form").find("p.errortext").empty();
		if($(this).closest('form').find('input[name="TOWN"]').size() > 0) {
			var town = $(this).closest('form').find('input[name="TOWN"]').val();
			if($('.dealers a[title="'+town+'"]').size() > 0) {
				$('.dealers a[title="'+town+'"]').addClass("active");
				var id = $('.dealers a[title="'+town+'"]').attr("id");
				$('div.dealer.'+id).show("slow");
			}
			else {
				$(this).closest("form").find("p.errortext").text('Информации о диллерах в городе "'+town+'" не найдено.');
			}
		}
		return false;
	});
		
	$("a.service-icon").hover(
		function() {
			var detail = $(this).find('img').attr("detail-pic");
			var src = $(this).find('img').attr("src");
			$(this).find('img').attr("src", detail);
			$(this).find('img').attr("detail-pic", src);
		},
		function() {
			var detail = $(this).find('img').attr("detail-pic");
			var src = $(this).find('img').attr("src");
			$(this).find('img').attr("src", detail);
			$(this).find('img').attr("detail-pic", src);
		}
	);
	
	/* Карточка Б/У товара */
	$('#carousel-detail').jCarouselLite({
		btnNext: ".jcarousel-next",
	    btnPrev: ".jcarousel-prev",
		mouseWheel: true,
		scroll: 1,
		circular: false,
		speed: 500,
		visible: 2
	});
	
	$(".catalog-detail .jcarousel li a").on("click", function(e) {
		e.preventDefault();
		$(".catalog-detail .jcarousel li a").removeClass("active");
		$(this).addClass("active");
		var src_std = $(this).attr("href");
		var width_std = $(this).attr("width-pic");
		var height_std = $(this).attr("height-pic");
		var src_big = $(this).find("img").attr("big-pic");
		$(this).closest('.ppic').find(".image-main a").attr("href", src_big);
		$(this).closest('.ppic').find(".image-main a img").attr("src", src_std);
		$(this).closest('.ppic').find(".image-main a img").attr("width", width_std);
		$(this).closest('.ppic').find(".image-main a img").attr("height", height_std);
	});
	
	$(".catalog-detail.used button.question, .catalog-detail.used button.order, .catalog-detail.used a.rassrochka, .catalog-detail.used a.arenda").on("click", function(e) {
		e.preventDefault();
		$('#order_catalog').dialog({
			dialogClass: 'dialog',
			autoOpen: false,
			width: 562,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		var lot = $(this).closest(".properties").find("input[name='lot']").val();
		var section_name = $(this).closest(".properties").find('input[name="section_name"]').val();
		$('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", section_name + ', лот '+lot);
		var theme;
		if($(this).hasClass("question") == true)
			theme = "Вопрос";
		if($(this).hasClass("order") == true)
			theme = "Заказ";
		if($(this).hasClass("rassrochka") == true)
			theme = "Лизинг/Рассрочка";
		if($(this).hasClass("arenda") == true)
			theme = "Аренда";
		if(theme) {
			$('#order_catalog').find("select[name='THEME'] [value='"+theme+"']").attr("selected", "selected");
			$('#order_catalog').find('select[name="THEME"]').selectbox("detach");
			$('#order_catalog').find('select[name="THEME"]').selectbox();
		}
		$('input.phone').mask("+7(999) 999-99-99");
		$('#order_catalog').dialog("open");
	});	
	
});

function ChangePicture(element) {
	if(element.attr("alt")) {
		var src = element.attr("src");
		var alt = element.attr("alt");
		element.attr("src", alt);
		element.attr("alt", src);
	}
}

function CheckForm(form) {
	var error = false;
	form.find('input, textarea').removeClass("error");
	form.find('input, textarea').each(function() {
		if($(this).hasClass("required") == true) {
			if(!$(this).val() || $(this).val() == "Не заполнено!") {
				error = true;
				$(this).addClass('error').val("Не заполнено!");
			}
		}
		if($(this).hasClass("email") == true) {
			var email = $(this).val();
			var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
			if(email && email != "Не заполнено!" && !pattern.test(email)) {
				error = true;
				$(this).addClass('error').val("Email введен неверно!");
			}
		}
	});
	return error;
}

function showDescriptioFromUseful(id) {
	$(".useful-info .tags a").removeClass("active");
	$(".useful-info .tags a#tooltip_"+id).addClass("active");
	$(".useful-info .text").hide();
	$(".useful-info .text.descr_"+id).show();
}

function setMailBoxes() {
	var as=document.getElementsByTagName('a'), dmn, nm;
	for(var i=0;i<as.length;i++) {
		if(as[i].className=='e-mail') {
			dmn=as[i].href.substr(as[i].href.search('#')+1);
			nm=as[i].title;					
			as[i].href='mailto:'+nm+'@'+dmn;
			as[i].title='Написать письмо';					
			if(!as[i].innerHTML) as[i].innerHTML=nm+'@'+dmn;
		}
	}
}
function yearClick(tabId) {
    if (/MSIE\s([\d.]+)/.test(navigator.userAgent)) {
        //Get the IE version.  This will be 6 for IE6, 7 for IE7, etc...
        var version = new Number(RegExp.$1);
    }

    if (tabId !== $('.years-tabs .year.active').data('tab') ) {
        if (version == 8) {
			$('.year .rotate-f').rotate({
				angle: 0,
				//animateTo: 360,
				animateTo: 100,
				easing: $.easing.easeOutQuad,
				duration: 1500
			});
			$('.year .rotate-b').rotate({
				angle: 0,
				animateTo: -100,
				easing: $.easing.easeOutQuad,
				duration: 1500
			});
        }
		else {
			$('.year .rotate-f').rotate({
				angle: 0,
				animateTo: 100,
				easing: $.easing.easeOutQuad,
				duration: 2500
			});
			$('.year .rotate-b').rotate({
				angle: 0,
				animateTo: -100,
				easing: $.easing.easeOutQuad,
				duration: 2500
			});
        }
        $('.years-tabs .year.active').removeClass('active');
        $('.years-tabs .year-' + tabId).addClass('active');
        $('.years-tabs .page').removeClass('active');
		$('.years-tabs .page-'+tabId).addClass('active');
    }
}

$.fn.animateRotate = function(angle, duration, easing, complete) {
    var args = $.speed(duration, easing, complete);
    var step = args.step;
    return this.each(function(i, e) {
        args.step = function(now) {
            $.style(e, 'transform', 'rotate(' + now + 'deg)');
            if (step) return step.apply(this, arguments);
        };

        $({deg: 0}).animate({deg: angle}, args);
    });
};