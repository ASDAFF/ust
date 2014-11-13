jQuery(function($){
	var hoverClass = 'hover',
		activeClass = 'active',
		hiddenClass = 'hide',
		mapHolder = '[data-attr="map-holder"]',
		officesMap = $('.offices-map'),
		dealersMap = $('.dealers-map'),
		areasList = '',
		regionsList = '',
		citiesList = '',
		dotsList = '';

	buildMap = function (list, map){
		var areasList = '',
			regionsList = '',
			citiesList = '',
			dotsList = '';
		$(list).each(function(index, item){
			areasList += '<area data-index="'+ index +'" class="'+ (item.key && item.area ? item.key : hiddenClass) +'" coords="'+ (item.area ? item.area : '') +'" alt="'+ (item.name ? item.name : '') +'" href="'+item.href+'" shape="poly">'
			regionsList += '<li data-index="'+ index +'" class="'+ (item.key ? item.key : hiddenClass) +'"></li>';
			citiesList += '<li data-index="'+ index +'" class="'+ (item.key ? item.key : hiddenClass) +'">'+ (item.name ? item.name : 'Ошибка: Проверьте наличие имени области') +'</li>';
			dotsList += '<li data-index="'+ index +'" class="'+ (item.key ? item.key : hiddenClass) +'"><i class="dot"></i></li>'
		});
		map.find('.areas-list map').append(areasList);
		map.find('.regions-list ul').append(regionsList);
		map.find('.cities-list ul').append(citiesList);
		map.find('.dots-list ul').append(dotsList);
	}
	
	if(typeof officesCities !== "undefined")
		buildMap(officesCities, officesMap);
	if(typeof dealersCities !== "undefined")
		buildMap(dealersCities, dealersMap);
	
	$('#map').find('.areas-list area').each(function(){
		var cur = $(this),
			targetRegion = cur.closest(mapHolder).find('.' + cur.attr('class') + '');
		cur.on({
			'mouseenter': function(){
				targetRegion.addClass(hoverClass);
			},
			'mouseleave': function(){
				targetRegion.removeClass(hoverClass);
			},
			'click': function(e){
				e.preventDefault();
				var town =  targetRegion.attr("class").replace(hoverClass, "").replace(" ", "");
				if($(this).closest("#map").find("ul").hasClass("offices-map") == true) {
					if($('.filialy .right .element.element_'+town).size() > 0)
						window.location.href = "/filialy/"+town+"/";
				}
				 else {
					$('.filialy .right a.open').removeClass("active");
					$('.filialy .right .element').slideUp("slow");			
					$('.filialy .right a.open#element_'+town).addClass("active");
					$('.filialy .right .element.element_'+town).slideDown("slow");
                                        
                                      
					api.scrollToElement($('.filialy .right a.open.active'));
				}/**/
				targetRegion.hasClass(activeClass)
					? targetRegion.removeClass(activeClass)
					: targetRegion.addClass(activeClass).siblings('.' + activeClass).removeClass(activeClass);
			}
		})
	});
	
	/* Инициализация скролла */
	if($('#pane').size() > 0) {
		var pane = $('#pane');
		pane.jScrollPane({animateScroll: true, mouseWheelSpeed: 100, hideFocus: true});
		var api = pane.data('jsp');
		api.scrollToElement($('.filialy .right a.open.active'), true);

		$('.filialy a.open.active').each(function() {
			var town = $(this).attr("id").substr(8);
			$('#map').find('.areas-list area').closest(mapHolder).find('.' + town + '').addClass(activeClass);
		});
	}
	
	/* Переключение городов */
	$('.filialy .right a.open').on("click", function(e) {
		e.preventDefault();
		var id = $(this).attr("id");
		var town = $(this).attr("id").substr(8);
		if($(this).hasClass("active") == true) {
			$('.filialy .right a.open').removeClass("active");
			$('.filialy .right .element').slideUp("slow");
		}
		else {
			$('.filialy .right a.open').removeClass("active");
			$('.filialy .right .element').slideUp("slow");			
			$('.filialy .right .element.'+$(this).attr("id")).slideDown("slow");
			$(this).addClass("active");
			api.scrollToElement($('.filialy .right a.open.active'));
		}
		$('#map').find('.areas-list area').closest(mapHolder).find('.' + town + '').addClass(activeClass).siblings('.' + activeClass).removeClass(activeClass);
	});
				
	$(".filialy .left form [type='submit']").click(function() {
		$(this).closest("form").find("p.errortext").empty();
		if($(this).closest('form').find('input[name="TOWN"]').size() > 0) {
			var town = $(this).closest('form').find('input[name="TOWN"]').val();
			if($('.filialy .right a.open[title="'+town+'"]').size() > 0) {
				var town_code = $('.filialy .right a.open[title="'+town+'"]').attr("id").substr(8);
				if($(this).closest("form").attr("name") == "dealers") {
					$('.filialy .right a.open').removeClass("active");
					$('.filialy .right .element').slideUp("slow");			
					$('.filialy .right a.open#element_'+town_code).addClass("active");
					$('.filialy .right .element.element_'+town_code).slideDown("slow");
					api.scrollToElement($('.filialy .right a.open.active'), true);
					$('#map').find('.areas-list area').closest(mapHolder).find('.' + town_code + '').addClass(activeClass).siblings('.' + activeClass).removeClass(activeClass);
				}
				else if($(this).closest("form").attr("name") == "filials") {
					window.location.href = "/filialy/"+town_code+"/";
				}
				
			}
			else {
				$(this).closest("form").find("p.errortext").text('Информации о представителях в городе "'+town+'" не найдено.');
			}
		}
		return false;
	});
	var TimeoutID;
	$(".filialy .right .element .services a").hover(function() {
		var span = $(this).find("span");
		$(this).find("span").css('display', 'block');
		var coords = span[0].getBoundingClientRect();		
		var name = span.text();
		TimeoutID = setTimeout(function() {
			$(".filialy .right span.description").html("<i></i>"+name).css("visibility", "visible").css("position", "fixed").css("left", coords.left).css("top", coords.top).show();
		}, 1);
	}, function() {
		clearTimeout(TimeoutID);
		$(".filialy .right span.description").html("").hide();
	});
});