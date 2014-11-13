$(window).scroll(function() {
    if ($(document).scrollTop() <= "500"){
        $(".up-button").hide();
    } else {
        $(".up-button").show();
    }
});

jQuery(function($){
	/* input file */
	$('.input-files input[type=file]').change(function(e){
		$(this).prevAll('input[type="text"]').val($(this).val());
	});
	
	$(".up-button").click(function(){
        $("html, body").animate({ scrollTop: "0" });
        return false;
    });
	
	//Защита email-ов от спама
	setMailBoxes();
	
	//красивые чекбоксы
	setCheckbox();
	
	$(document).on("click", "label.label_check", function(e) {
		e.preventDefault();
		var inp = this.getElementsByTagName('input')[0];
		var input = $(this).find('input');
		if(inp.disabled == false) {
			if ($(this).hasClass("c_off") == true) {				
				$(this).removeClass("c_off").addClass("c_on");
				inp.setAttribute("checked", "checked");
			}
			else {
				$(this).removeClass("c_on").addClass("c_off");
				inp.removeAttribute("checked");
			};
		}
		add3Compare(input, input.attr("id").substr(5));
		checkCatalogCount($(this).find("input[type='checkbox']").first());
		checkCatalogDetail($(this).find("input[type='checkbox']").first());
	});
	
	//красивые селекты
	var detect_ipad = navigator.userAgent.match(/iPad/i) != null; // для iPad
	var detect_ipod = navigator.userAgent.match(/iPod/i) != null; // для iPod
	var detect_iphone = navigator.userAgent.match(/iPhone/i) != null; // для iPhone
	if(detect_ipad == false && detect_ipod == false && detect_iphone == false) {
		$(".main-wrapper select").selectbox();
	}
	
	//маска для телефона
	$('.main-wrapper input.phone').mask("+7(999) 999-99-99");
	
	//защита форм от спама
	$(".main-wrapper form.protection").each(function() {
		if($(this).find("input[name='sessid']").val())
			$(this).append("<input type='hidden' name='jssid' value='"+$(this).find("input[name='sessid']").val()+"'>");
	});
	
	//таблицы
	if($('.catalog.catalog-list > table').size() > 0)
		updateTable($('.catalog.catalog-list > table'));
	defineTable();
	
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
		$(this).closest(".catalog-section-descr").find(".more_description").slideToggle("slow");
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
	
	$("a#file_popup_link").on("click", function(e){
		e.preventDefault();
		$("#file_popup").dialog({
			dialogClass: "dialog",
			autoOpen: false,
			width: 350,
			resizable: false,
			draggable: false,
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		var kod = $("#id_user_web").html();
		var file = $(this).attr("href");
		$("input[name=PROPS_personal_number]").val(kod);
		$("input[name=PROPS_FILE_LINK]").val(file);
		$("#file_popup").dialog("open");
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
		$(this).toggleClass("active");
		$("#line_"+id).slideToggle("slow");
	});
	
	//подсказки в форме заказа обратного звонка	
	$(document).on("click", "p.hints a.callback_hint", function(e) {
		e.preventDefault();
		var text = $(this).text();
		$(this).closest('form').find('textarea').val("Интересует "+text);
	});
	
	//Проверка форм на ошибки
	/*$(document).on("focus", ".main-wrapper form input, .main-wrapper form textarea", function(e) {
		if($(this).hasClass("error")) {
			$(this).val("");
			$(this).removeClass("error");
		}
	});*/
	
	
	$(".main-wrapper form input, .main-wrapper form textarea").on("focus", function(e) {
		if($(this).hasClass("error")) {
			$(this).val("");
			$(this).removeClass("error");
		}
	});
	
	//сабмит формы - проверка заполненности полей
	$(".main-wrapper form [type='submit']").on("click", function(e) {
		e.preventDefault();
		var el = $(this);
		error = CheckForm($(this).closest("form"));		
		if(error == false) {
			if($(this).closest("form").hasClass("ajax") == true) {
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
				return false;
			}
			else {
				$(this).closest("form").submit();
			}			
		}
	});
	$("form[name='callback'] [type='submit']").on("click", function(e) {
		e.preventDefault();
		var el = $(this);
		var error = CheckForm($("form[name='callback']"));
		if(error == false) {
            var data = $(this).closest("form").serialize();
			$.post('/ajax/ajax.php', 
					/*FormType: 'CallbackSend',
					NAME: $("[name='NAME']").val(),
					PHONE: $("[name='PHONE']").val(),
					TOWN: $("[name='TOWN']").val(),
					TIME: $("[name='TIME']").val(),
					COMMENT: $("[name='COMMENT']").val() */
                    data, 
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

	$("form[name='filesend'] [type='submit']").on("click", function(e) {
		e.preventDefault();
		var el = $(this);
		var error = CheckForm($("form[name='filesend']"));
		if(error == false) {
            var data = $(this).closest("form").serialize();
			$.post('/ajax/ajax.php', 
					/*FormType: 'CallbackSend',
					NAME: $("[name='NAME']").val(),
					PHONE: $("[name='PHONE']").val(),
					TOWN: $("[name='TOWN']").val(),
					TIME: $("[name='TIME']").val(),
					COMMENT: $("[name='COMMENT']").val() */
                    data, 
				function(data) {
					if(data.STATUS == 1) {
						$("#file_popup").dialog({
							close: function( event, ui ) {
								window.location.reload();
							}
						});
						$("#file_popup").html('<h2>Файл выслан на укзанный email</h2><p>С уважением, Универсал-Спецтехника</p>');
					}
				},
				'json'
			);
		}
	});
 
        $("form[name='john-deere-topcon'] [type='submit']").on("click", function(e) {
		 $(this).closest("form").submit();
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
		$('.sbOptions a[rel=Рассрочка]').html('Взять в лизинг').attr('href','#Лизинг');
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
					width: 800,
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
		$('.sbOptions a[rel=Рассрочка]').html('Взять в лизинг').attr('href','#Лизинг'); 
	});
	
	/* Заказ техники в аренду */
	$(".rent-item button.silver_button.order").on("click", function(e) {
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
		$('.sbOptions a[rel=Рассрочка]').html('Взять в лизинг').attr('href','#Лизинг'); 
	});
	
	
	/* Карусель в акциях */
	$('#action_banner').cycle({
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
	
	$(".archive-link").click(function(){
      $(this).toggleClass('opened');
      $(".archive-actions .actions-list").slideToggle();
      return false;
    });
	
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
	
	$("#phone_support").on("click", function(e) {
		e.preventDefault();
		$("#service").dialog("close");
		$('#service_phone').dialog("open");
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
			btnNext: ".next_brands",
			btnPrev: ".prev_brands",
			mouseWheel: true
		});
	}, 2000);
	var intervalID;
	
	/*Карусель на главной странице*/
	$(".inside-logos.catalog #carousel_bottom > ul > li").hover(function() {
		clearTimeout(intervalID);
		var element = $(this);
		if(element.hasClass("active") == false) {
			ChangePicture($(".inside-logos.catalog #carousel_bottom > ul > li.active").find("img"));
			$(".inside-logos.catalog #carousel_bottom > ul > li.active").removeClass("active");
			$('.hidden_catalog').fadeOut();
			ChangePicture(element.find("img"));
		}
		if(element.attr("id") && $('.hidden_catalog.brand_'+element.attr("id").substr(6)).size() > 0 && element.hasClass("active") == false) {
			element.addClass("active");
			$('.hidden_catalog.brand_'+element.attr("id").substr(6)).fadeIn();
		}
	}, function() {
		var element = $(this);
		if(element.attr("id") && $('.hidden_catalog.brand_'+element.attr("id").substr(6)).size() > 0) {
			intervalID = setTimeout(function() {
				ChangePicture(element.find("img"));
					element.removeClass("active");
					$('.hidden_catalog.brand_'+element.attr("id").substr(6)).fadeOut();
			}, 500);
		}
		else ChangePicture(element.find("img"));		
	});
	$('.hidden_catalog').hover(function() {
		clearTimeout(intervalID);
	}, function() {
		intervalID = setTimeout(function() {
			ChangePicture($(".inside-logos.catalog #carousel_bottom > ul > li.active").find("img"));
			$(".inside-logos.catalog #carousel_bottom > ul > li.active").removeClass("active");
			$('.hidden_catalog').fadeOut();
		}, 500);
	});
	
	$(".inside-logos.bottom li").hover(function() {
		ChangePicture($(this).find("img"));
	}, function() {
		ChangePicture($(this).find("img"));
	});
	
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
	$("a.fancybox, .jcarousel a").fancybox({
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
	$("#tabs-used-detail").tabs();
	
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
	});
		$('.show-directs .toggler').stop(true,true).click(function(){
			$(this).toggleClass('opened');
			$('#contacts-map .directs').slideToggle();
		});

		$('.hide-directs .toggler').click(function(){
			$('.show-directs .toggler').toggleClass('opened');
			$('#contacts-map .directs').slideToggle();
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
		$(".survey .line a.answer").hover(function() {
			$(this).addClass("hover");
		}, function() {
			$(this).removeClass("hover");
		});
		$(".survey .line a.answer").on("click", function(e) {
			e.preventDefault();
			//$(this).css("border", "1px solid red");
			$(this).closest(".line").find("a.answer").removeClass("active");
			$(this).addClass("active");
			$(this).closest(".line").find('input[type="hidden"]').val($(this).text());
			
		});
		
		var mobileHover = function () {
			$('*').on('touchstart', function () {
				$(this).trigger('hover');
			}).on('touchend', function () {
				$(this).trigger('hover');
			});
		};
		mobileHover();
		
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
	$('.vert_design #carousel-detail').jCarouselLite({
		btnNext: ".jcarousel-next",
	    btnPrev: ".jcarousel-prev",
		mouseWheel: true,
		scroll: 1,
		circular: false,
		speed: 500,
		visible: 13
	});
	if($('.vert_design #carousel-detail button').length = 2 && ($('.vert_design #carousel-detail button.disabled').length = 2)) {
		$(this).hide();	
	}
	
	$(".catalog-detail .jcarousel li a").on("click", function(e) {
		e.preventDefault();
		$(".catalog-detail .jcarousel li a").removeClass("active");
		$(this).addClass("active");
		var src_std = $(this).attr("href");
		var width_std = $(this).attr("width-pic");
		var height_std = $(this).attr("height-pic");
		var src_big = $(this).find("img").attr("big-pic");
		/*$(this).closest('.ppic').find(".image-main a").attr("href", src_big);
		$(this).closest('.ppic').find(".image-main a img").attr("src", src_std);
		$(this).closest('.ppic').find(".image-main a img").attr("width", width_std);
		$(this).closest('.ppic').find(".image-main a img").attr("height", height_std);*/
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
		$('.sbOptions a[rel=Рассрочка]').html('Заказать в лизинг').attr('href','#Лизинг'); 
	});
	
	/* "Заказать" на странице секции */
	$(".catalog .items button[name='ordering']").on("click", function(e) {
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
		var name = $(this).closest(".items").find("input[name='item_name']").val();
		$('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", name);
		$('input.phone').mask("+7(999) 999-99-99");
		$('#order_catalog').dialog("open");
		
	});
	
	/* Добавление в список сравнения */
	$(".catalog .comparison a.compare").on('click', function(e) {
		e.preventDefault();
		add2Compare($(this), $(this).attr("id").substr(5));
	});
	if(!$('.catalog-detail').hasClass('vert_design')) {
		$(".catalog-detail #related-propducts").jCarouselLite({
			btnNext: ".related-propducts-button.next",
			btnPrev: ".related-propducts-button.prev",
			mouseWheel: true,
			visible: 2,
			circular: false,
			vertical:  true
		});
		$('#carousel-detail, #carousel-detail-scheme').jCarouselLite({
			btnNext: ".jcarousel-next",
			btnPrev: ".jcarousel-prev",
			mouseWheel: true,
			scroll: 1,
			circular: false,
			speed: 500,
			visible: 3
		});
	}
	$(".catalog-detail #interested-propducts").jCarouselLite({
	    btnNext: ".interested-propducts-button.next",
	    btnPrev: ".interested-propducts-button.prev",
		mouseWheel: true,
		visible: 6,
		circular: false
	});
	
	/* Фильтр в каталоге */
	$(document).on("change", ".catalog-filter select[name='type']", function() {
		$(".catalog-filter select[name='view'] option").removeAttr("selected");
		$(".catalog-filter select[name='view'] option").first().attr("selected", "selected");
		$('.catalog-filter .brands input[type="checkbox"]').removeAttr("checked");
		checkCatalogCount($(this));
	});
	
	$(document).on("change", ".catalog-filter select[name='view']", function() {
		$('.catalog-filter .brands input[type="checkbox"]').removeAttr("checked");
		checkCatalogCount($(this));
	});
	
	$(document).on("change", ".catalog-filter select[name='brand']", function() {
		checkCatalogCount($(this));
	});
	
	$(".catalog-filter [type='submit']").on("click", function() {
		var url;
		if($(".catalog-filter select[name='view']").val().length > 1) 
			url = $(".catalog-filter select[name='view']").val();
		else
			url = $(".catalog-filter select[name='type']").val();
			
		var array = new Array();
		if($('.brands input[type="checkbox"]:checked').size() > 0) {
			$('.brands input[type="checkbox"]:checked').each(function() {
				array[array.length] = $(this).val();
			});
		}
		else if($(".catalog-filter select[name='brand']").size() > 0 && $(".catalog-filter select[name='brand']").val() != 0) {
			array[array.length] = $(".catalog-filter select[name='brand']").val();
		}
		if(array.length > 0) {
			var str = implode_js(',', array);
			new_url = setGetParameter(url, "brand", str);
		}
		else new_url = removeParam("brand", url);
		window.location.href = new_url;
	});
	
	/* Заказать в карточке товара в каталоге товаров */
	$(".catalog-detail.catalog button.question, .catalog-detail.catalog button.order, .catalog-detail.catalog button.credit, .catalog-detail.catalog button.used, .catalog-detail.catalog button.arenda").on("click", function(e) {
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
		var type = $(this).closest(".catalog-detail.catalog").find("input[name='type_element']").val();
		var element_array = new Array(); 
		$(this).closest(".catalog-detail.catalog").find("input.element").each(function() {	
			element_array[element_array.length] = {"ID" : $(this).attr("name").substr(8), "NAME" : $(this).val()};
		});
		if(element_array.length > 1 && type == "seriya") {
			$('#order_catalog').find('input[name="DESCRIPTION"]').closest(".line").find("label").html("Выберите модель<span class='required'>*</span>");
			$('#order_catalog').find('input[name="DESCRIPTION"]').after("<select name='DESCRIPTION' class='required'></select>");
			$('#order_catalog').find('input[name="DESCRIPTION"]').remove();
			for(var i = 0; i < element_array.length; i++) { 
				$('select[name="DESCRIPTION"]').append($("<option value='"+element_array[i]["NAME"]+"'>"+element_array[i]["NAME"]+"</option>")); 
			}
			$('#order_catalog').find('select[name="DESCRIPTION"]').selectbox();
		}
		else {
			$('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", element_array[0]["NAME"]);
		}
		var theme;
		if($(this).hasClass("question") == true)
			theme = "Вопрос";
		if($(this).hasClass("order") == true)
			theme = "Заказ";
		if($(this).hasClass("credit") == true)
			theme = "Кредит";
		if($(this).hasClass("used") == true)
			theme = "Купить Б/У";
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
	$(".catalog-detail.catalog a.compare").on('click', function(e) {
		e.preventDefault();
		add2Compare($(this), $(this).closest('.catalog-detail.catalog').find('input.element').first().attr("name").substr(8));
	});
	$(".catalog-detail.catalog a.service_centers").on("click", function(e) {
		e.preventDefault();
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
					contentWidth: 650,
					horizontalGutter: 30
				});
			}
		});
		$('#service_centers').dialog("open");
	});
	
	$(".catalog_description a.to_useful").on("click", function(e) {
		e.preventDefault();
		var desctination = $('.catalog-information-left-col .useful-info').offset().top-10;
		$("html, body").animate({ scrollTop: desctination}, 1000);
	});
	var api;
	if($(".catalog-detail .tabs-detail .page#characteristics").size() > 0) {
		$('.scroll-pane').jScrollPane();
		api = $('.catalog-detail.catalog .scroll-pane').jScrollPane().data('jsp');
		$("#tabs-detail").tabs();
		$('li[aria-controls="characteristics"] a').click();
	}
	
	$(".catalog-detail .tabs-detail .page#characteristics a.group_name").on("click", function(e) {
		e.preventDefault();
		$(".catalog-detail .tabs-detail .page#characteristics a.group_name").removeClass("active");
		$(".catalog-detail .tabs-detail .page#characteristics .group").removeClass("active");
		$(this).addClass("active");
		$(".catalog-detail .tabs-detail .page#characteristics .group."+$(this).attr("id")).addClass("active");
		api.destroy();
		$('.scroll-pane').jScrollPane();
		api = $('.catalog-detail.catalog .scroll-pane').jScrollPane().data('jsp');
	});
	
	if($(".catalog-table .table-body").length) {
		updateTable($(".catalog-table .table-body"));
		resetSize($(".catalog-table .table-body"));
	}
	
	$(".contact-short").click(function(){
        $(".contacts-right-col .office-details").slideToggle();
        $(".contact-detailed").show();
        return false;
    });
    $(".contact-detailed").click(function(){
      $(".contact-detailed").hide();
        $(".contacts-right-col .office-details").slideToggle();
        return false;
    });
	updateComparisonList();
});

function resetSize(elem) {
	elem.find(".row").each(function() {
		var that = $(this);
		var position = that.position();
		that.data('top-offset', position.top);
	});
	elem.find(".row").each(function() {
		var that = $(this);
		that.children(".cell").each(function(i) {
			var cell = $(this);
			var itemNum = 1 + i;
			var itemElem = $('.catalog-table .table-items .head-row .cell:nth-child('+itemNum+')');
			cell.css('width', itemElem.outerWidth() - 1);
			//cell.css('height', that.height() - 1);
		});
	});
}

function getUnvisibleDimensions(obj) {
    if ($(obj).length == 0) {
        return false;
    }

    var clone = obj.clone();
    clone.css({
        visibility:'hidden',
        width : '',
        height: '',
        maxWidth : '',
        maxHeight: ''
    });
    $('body').append(clone);
    var width = clone.outerWidth(),
        height = clone.outerHeight();
    clone.remove();
    return {w:width, h:height};
}

function defineTable() {
	$(".main-wrapper table").each(function() {
		if($(this).hasClass("vertical") == true) {
			$(this).find("tr").each(function() {
				$(this).find("td:nth-child(odd), th:nth-child(odd)").addClass("even");
			});		
		}
		else if(parseInt($(this).closest(".image").length) > 0 || $(this).hasClass("no-hover") == true) {
			
		}
		else {
			$(this).find("thead tr").addClass("even");
			$(this).find("tbody tr:odd").addClass("even");
		}
	});
}

function add2Compare(el, id) {
	el.addClass("disable");
	if(el.hasClass("add") == true) {
		$.post('/ajax/ajax.php', 
			{
				FormType: 'addCompare', 
				ID: id
			}, 
			function(data) {
				if(data.STATUS == 1) {
					el.removeClass("disable");
					el.closest(".comparison").find("a.compare").removeClass("add").addClass("del").text("Убрать из сравнения");
					if(data.COUNT >= 1) {
						el.closest(".comparison").addClass("full");
						if (el.closest(".comparison").find("a.go").length < 1){
							if(el.hasClass('used')){
								el.closest(".comparison").find("a.compare").after('<a class="go" href="/catalog/compare_used/" title="Перейти в список сравнения">Сравнение('+data.COUNT+')</a>');
							} else {
								el.closest(".comparison").find("a.compare").after('<a class="go" href="/catalog/compare/" title="Перейти в список сравнения">Сравнение('+data.COUNT+')</a>');
							}
						}
						if(el.hasClass('used')){
							$('a.go').replaceWith('<a class="go" href="/catalog/compare_used/" title="Перейти в список сравнения">Сравнение('+data.COUNT+')</a>');
						} else {
							$('a.go').replaceWith('<a class="go" href="/catalog/compare/" title="Перейти в список сравнения">Сравнение('+data.COUNT+')</a>');
						}
					}
					else {
						el.closest(".comparison").removeClass("full");
					}
					updateComparisonList();
				}
			},
			'json'
		);
	}
	else if(el.hasClass("del") == true) {
		$.post('/ajax/ajax.php', 
			{
				FormType: 'delCompare', 
				ID: id
			}, 
			function(data) {
				el.removeClass("disable");
				if(data.STATUS == 1) {
					el.closest(".comparison").find("a.compare").removeClass("del").addClass("add").text("Сравнить");
					el.closest(".comparison").find("a.go").remove();
					updateComparisonList();
				}
				if(data.COUNT >= 1) {
					if(el.hasClass('used')){
						$('a.go').replaceWith('<a class="go" href="/catalog/compare_used/" title="Перейти в список сравнения">Сравнение('+data.COUNT+')</a>');
					} else {
						$('a.go').replaceWith('<a class="go" href="/catalog/compare/" title="Перейти в список сравнения">Сравнение('+data.COUNT+')</a>');
					}
				}
				if (data.COUNT == 0) {
					$('a.go').remove();	
				}
			},
			'json'
		);
	}
}
function updateComparisonList() {
	$.post('/ajax/ajax.php', 
		{
			FormType: 'updateComparisonList'
		}, 
		function(data) {
			if(data.COUNT > 0) {
				var items = data.items;
				/*if($('.compare.header a').size() > 0) {
					$('.compare.header a > span').text(data.COUNT);
					$('.compare.header').html('<a href="/catalog/compare/" title="Перейти в список сравнения">Сравнение (<span>'+data.COUNT+'</span>)</a>');
				}
				else {*/
					$('.compare.header').html('<a href="/catalog/compare/" title="Перейти в список сравнения">Сравнение (<span>'+data.COUNT+'</span>)</a>');
				//}
				for (var i=0;i<items.length;i++){
					var val = items[i];
					$('body').find('#item_'+val).attr('checked','checked').parent('label').removeClass('c_off').addClass('c_on');
					
				}
				
			}
			else {
				$('.compare.header').html('');
			}
		},
		'json'
	);
}

function checkCatalogDetail(element) {
	if(parseInt(element.closest("#characteristics").size()) > 0) {
		var id = element.attr("name").substr(8);
		if(element.attr("checked") == "checked") {
			element.closest(".scroll-pane").find("input[name='"+element.attr("name")+"']").attr("checked", "checked");
			$.post('/ajax/ajax.php', 
				{
					FormType: 'addCompare', 
					ID: id
				}, 
				function(data) {
					if(data.STATUS == 1) {
						updateSelectingModel();
						updateComparisonList();
					}
				},
				'json'
			);
		}
		else {
			element.closest(".scroll-pane").find("input[name='"+element.attr("name")+"']").removeAttr("checked");
			$.post('/ajax/ajax.php', 
				{
					FormType: 'delCompare', 
					ID: id
				}, 
				function(data) {
					if(data.STATUS == 1) {
						updateSelectingModel();
						updateComparisonList();
					}
				},
				'json'
			);
		}
		setCheckbox();
		
	}
}

function updateSelectingModel() {
	if($(".catalog-detail .tabs-detail .page#characteristics .checkbox input[type='checkbox']").size() > 0) {
		var array_checked = new Array();
		$(".catalog-detail .tabs-detail .page#characteristics .checkbox input[type='checkbox']").each(function() {
			if(in_array($(this).val(), array_checked) == false && $(this).attr("checked") == "checked")
				array_checked[array_checked.length] = $(this).val();
		});
		if(array_checked.length > 0)
			$(".catalog-detail div.comparison").html('<a class="compare" href="/catalog/compare/" title="Перейти в список сравнения">Перейти в список сравнения</a>');
		else 
			$(".catalog-detail div.comparison").html('');
	}
} 


function in_array(needle, haystack) { 
    var found = false, key; 
    for (key in haystack) {
        if (haystack[key] === needle){
            found = true;
            break;
        }
    } 
    return found;
}

function checkCatalogCount(element) {
	if(element.closest(".catalog-filter").find(".field.brands").size() > 0) {
		var brands = new Array();

		if(element.closest(".catalog-filter").find(".field.brands input[type='checkbox']:checked").size() > 0) {
			element.closest(".catalog-filter").find(".field.brands input[type='checkbox']:checked").each(function() {
				brands[brands.length] = $(this).val();
			});
		}
		else if(element.closest(".catalog-filter").find("select[name='brand']").size() > 0) {
			brands[brands.length] = element.closest(".catalog-filter").find("select[name='brand']").val();
		}
	}
	$('#chosen').html("");
	if(typeof $('#chosen').html()!= 'undefined') {
	if($('#chosen').html().length < 100) {
		$(".catalog-params").addClass('opacity');
		setTimeout(function() {
			$.post('/ajax/ajax.php', {
					FormType: 'checkCatalogFilter',
					Type: element.closest(".catalog-filter").find('select[name="type"] option:selected').attr("id"),
					View: element.closest(".catalog-filter").find('select[name="view"] option:selected').attr("id"),
					Brands: brands
				}, 
				function(data) {
					if(data) {
						$(".catalog-params").removeClass('opacity').html("").html(data);
						setCheckbox();
						$(".catalog-params select").selectbox();
					}
				},
				'html'
			);
			$.post('/ajax/ajax.php', {
					FormType: 'checkCatalogCount',
					Type: element.closest(".catalog-filter").find('select[name="type"] option:selected').attr("id"),
					View: element.closest(".catalog-filter").find('select[name="view"] option:selected').attr("id"),
					Brands: brands
				}, 
				function(data) {
					if(data.STATUS == 1) {
						$(".catalog-filter #chosen").empty().html("Подобрано товаров: "+data.COUNT);
					}
				},
				'json'
			);
		}, 500);
	}
	}
}
function setCheckbox() {
	$('label.label_check').each(function() {
		var inp = $(this).find('input[type="checkbox"]').first();
		if(inp.attr("checked") == "checked") {
			$(this).removeClass("c_off").addClass("c_on");
		}
		else 
			$(this).removeClass("c_on").addClass("c_off");		
	});
}

function implode_js(separator,array){
   var temp = '';
   for(var i=0;i<array.length;i++){
	   temp +=  array[i] 
	   if(i!=array.length-1){
			temp += separator  ; 
	   }
   }
   return temp;
}

function setGetParameter(url, paramName, paramValue)
{
	if (url.indexOf(paramName + "=") >= 0) {
		var prefix = url.substring(0, url.indexOf(paramName));
		var suffix = url.substring(url.indexOf(paramName)).substring(url.indexOf("=") + 1);
		suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
		url = prefix + paramName + "=" + paramValue + suffix;
	}
	else {
		if (url.indexOf("?") < 0)
			url += "?" + paramName + "=" + paramValue;
		else
			url += "&" + paramName + "=" + paramValue;
	}
	return url;
}

function removeParam(key, sourceURL) {
	var rtn = sourceURL.split("?")[0],
		param,
		params_arr = [],
		queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
	if (queryString !== "") {
		params_arr = queryString.split("&");
		for (var i = params_arr.length - 1; i >= 0; i -= 1) {
			param = params_arr[i].split("=")[0];
			if (param === key) {
				params_arr.splice(i, 1);
			}
		}
		rtn = rtn + "?" + params_arr.join("&");
	}
	return rtn;
}

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

function updateTable(elem) {
	var full_width = elem.outerWidth(true);
	var div_width = elem.closest(".catalog-list-view").outerWidth(true);
	
	if(full_width > div_width) {
		var cur_length = 0, cur_point = 0;			
		elem.find(".row").each(function(index) {
			var td_array_width = new Array();
			$(this).find(".cell").each(function(index) {
				td_array_width[td_array_width.length] = {
					"num" : index,
					"width" : $(this).outerWidth(true)
				};
			});			
			for(var i = 0; i < td_array_width.length; i++) {
				if(cur_length < div_width) {
					cur_length = cur_length + td_array_width[i]["width"];
					cur_point = td_array_width[i]["num"];
				}
			}			
		});
		
		elem.find(".row").each(function(index) {
			$(this).find(".cell").each(function(index) {
				if(index > cur_point-1)
					$(this).remove();
			});
		});
	}
}
function add3Compare(el, id) {
	el.addClass("disable");
	if(el.hasClass("add") == true) {
		$.post('/ajax/ajax.php', 
			{
				FormType: 'addCompare', 
				ID: id
			}, 
			function(data) {
				if(data.STATUS == 1) {
					el.removeClass("disable");
					el.closest(".label_check").find("input.compare").removeClass("add").addClass("del");
					if(data.COUNT >= 2) {
						el.closest(".label_check").addClass("full");
					}
					else {
						el.closest(".comparison").removeClass("full");
					}
					updateComparisonList();
				}
			},
			'json'
		);
	}
	else if(el.hasClass("del") == true) {
		$.post('/ajax/ajax.php', 
			{
				FormType: 'delCompare', 
				ID: id
			}, 
			function(data) {
				el.removeClass("disable");
				if(data.STATUS == 1) {
					el.closest(".label_check").find("input.compare").removeClass("del").addClass("add");
					updateComparisonList();
				}
			},
			'json'
		);
	}
}