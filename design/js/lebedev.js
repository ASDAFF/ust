jQuery(function($){
	$('a.about-link').click(function(){
		var target = $(this).attr('href');
		$('html, body').animate({scrollTop: $(target).offset().top-15}, 1500);
		$('.small-title-box').removeClass('active');
		$(target).addClass('active');
		return false; 
		
	}); 
	$('.question a').click(function(e){
		e.preventDefault();
		quest = $(this).attr('class');
		
		$('.faq .answer:not(.'+quest).removeClass('active').slideUp();
		$('.faq .question').not($(this).parent('.question')).removeClass('active');
		
		$('.faq').find('div.'+quest).slideToggle().toggleClass('active');
		$(this).parent('.question').toggleClass('active'); 
	});
	
	$('.more-videos-link').click(function(e){
		e.preventDefault();
		$(this).next('.tooltip').slideToggle();
		
	});
	
	$('.sbOptions a[rel="/catalog/navesnoe-burovoe-i-svaeboynoe-oborudovanie-1/"]').click(function(e){
		e.preventDefault();
		url = $(this).attr('rel').replace('-1','');
		console.log(url);
		window.location = url;
		return false;
	});
	if($('.catalog-detail').hasClass('vert_design')) {
		if ($('.add_block').length!=0) {
			$('.a-side .news-left').after($('.add_block')).siblings('.add_block').after($('.aside-block'));
		} else {
			$('.a-side .news-left').after($('.aside-block'));
		}
		
		
		$(".a-side #related-propducts").jCarouselLite({
	    btnNext: ".related-propducts-button.next",
	    btnPrev: ".related-propducts-button.prev",
		mouseWheel: true,
		visible: 2,
		circular: false,
		vertical:  false,
		});
		
		$(".a-side #attachments").jCarouselLite({
	    btnNext: ".attachments-button.next",
	    btnPrev: ".attachments-button.prev",
		mouseWheel: true,
		visible: 1,
		circular: false,
		vertical:  false,
		});
		
		$('.inner_link').click(function(e){
			e.preventDefault();
			link_to = $(this).attr('href');
			$('html, body').animate({
				scrollTop: $(link_to).offset().top
			}, 1500);
		});
		
		$('#characteristics .group:gt(1)').hide();
		$('#characteristics .show_chars').clickToggle(function(){
			$('#characteristics .group:gt(1)').slideDown();
			$(this).html('Скрыть характеристики').addClass('open');
		}, function(){
			$('#characteristics .group:gt(1)').slideUp();
			$(this).html('Показать все характеристики').removeClass('open');	
		});
		
		$('#benefits .show_benefit').clickToggle(function(){
			$(this).prev('.to_show').slideDown();
			$(this).html('Спрятать').addClass('open');
		}, function(){
			$(this).prev('.to_show').slideUp();
			$(this).html('Подробнее').removeClass('open');	
		});
		
	}
	
	$('.catalog_quick .service-icon').each(function(){
		if($(this).attr('link')==$(this).attr('host')) {
			$(this).click();
			return false;
		}
	});
	
	
	$('a[href="#"]').click(function(){
		yaCounter24904661.hit('#' + get_custom_link_name($(this)));
		return true;
	
	});
	/*onsubmit="yaCounter24904661.reachGoal('CallBack'); return true;"*/
	/* обратный звонок */
	$('form[name="callback"]').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_callback-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' callback', document.domain +' callback']);
		return true;	
	});
	/* форма заказа */
	$('button[name="ordering"], button.order, .order-btn.button').closest('form').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_order-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' ordering', document.domain +' ordering']);
		return true;	
	});
	/* вопрос */
	$('button.question, form[name="order_form_question"] button[type="submit"]').closest('form').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_question-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' question', document.domain +' question']);
		return true;	
	});
	/* обратная связь */
	$('.contacts-page button[type="submit"], .bu-page button[type="submit"]').closest('form').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_feedback-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' feedback', document.domain +' feedback']);
		return true;	
	});
	/* опрос */
	$('.survey form').closest('form').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_survey-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' survey', document.domain +' survey']);
		return true;	
	});
	/* работа */
	$('form[name="career"]').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_career-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' career', document.domain +' career']);
		return true;	
	});
	/* доставка */
	$('form[name="dostavka"]').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_dostavka-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' dostavka', document.domain +' dostavka']);
		return true;	
	});
	/* сервис */
	$('form[name="service_form"]').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_service-form-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' service', document.domain +' service']);
		return true;	
	});
	/* John Deere */
	$('.john_deere form').submit(function(){
		yaCounter24904661.reachGoal(document.domain +'_mfeedback-form-button');
		_gaq.push(['_trackEvent', 'Forms', document.domain +' mfeedback', document.domain +' mfeedback']);
		return true;	
	});
	
	
	
	
	
	
	$('a[href=#]').each(function(){
		$(this).attr('href','#' + get_custom_link_name($(this)));
	});
	
	
	

	
	var pathname = window.location.pathname;
    if(pathname.indexOf('bu-skladskaya-tehnika') > -1){
		 $('.subscribe-plate').hide();
	}
	
	


















	var th_height = $('th.name:not(.fixedgorizontal)').height();
	$('th .fixedgorizontal').height(th_height);
	
	var ie = msieversion();
	if (ie && ie>0){
		$('.navesnoe-menu .bx_catalog_line_title a').css('padding','0 9px');
		/*$('#badie').dialog({
			dialogClass: 'dialog',
			autoOpen: true,
			width: 562,
			resizable: false,
			draggable: false,		
			position: "center",
			modal: true,
			closeText: "Закрыть"
		});
		console.log(ie);*/
	}
	var pathname = window.location.pathname;
	$('.filials .col a.diler').click(function(e){
	   e.preventDefault();
	   var url = $(this).attr('href');
	   if (pathname.indexOf('/dilery-skladskoj-tehniki/') == -1) {
			window.location =url;
	   } else {
			$(url +':not(.active)').click();
			$('html, body').animate({scrollTop: $('.filialy').offset().top-15}, 1000);   
	   }
	});/**/
	$('.offices-map li').click(function(e){
	   e.preventDefault();
	   url = $(this).attr('class');
	   
	   //$('.' + url).find('p:first-child a').click();
	   $('#' + url).click();
	});
	if (pathname.indexOf('navesnoe-oborudovanie-dlya-skladskoy-tekhniki-') != -1) {
		 $('.items_wrapper a.title, .items_wrapper a.image').click(function(e){
		 	e.preventDefault();
		 });
	}
   /*$('.catalog.catalog-plate .items .characteristics').each(function(){
	   if ($(this).children('p').length <=4){
			$(this).parents('.info').find('button[name=ordering]').addClass('pos_rel');  
			$(this).parents('.description').addClass('small');
	   }
   });*/
   /*$('.properties .action a,.properties .action button').each(function(){
		$(this).clone().addClass('hovered').insertBefore(this);   
   });
   $('.properties .action a,.properties .action button').not('.hovered').hover(function(){
	   $(this).fadeOut().prev('.hovered').fadeIn();
	   
   },function(){
	   $(this).fadeIn().prev('.hovered').fadeOut();
   });*/
});
window.onload = function() {
	var pathname = window.location.pathname;
   var path_hash = window.location.hash;
	if (window.location.hash.length >2 && pathname.indexOf('/dilery-skladskoj-tehniki/') != -1){
		if(path_hash != '#element_moscow'){
	   		$(path_hash).click();	
		}
	   	$('html, body').animate({scrollTop: $('.filialy').offset().top-15}, 1000); 
   }/* */
   
}
function msieversion() {

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer, return version number
            return msie;
        else                 // If another browser, return 0
            return false;

   
}
function get_custom_link_name(j_item){
		parent_class = j_item.parents()
						     .map(function(){return this.className}).get().join(' ').split(' ');
		parent_id_temp = j_item.parents()
						       .map(function(){return this.id}).get();
		parent_id = Array();
		for (var i = 0; i < parent_id_temp.length; i++) {
			if(parent_id_temp[i] != '') {
				parent_id.push(parent_id_temp[i]);	
			}
		}
		if (parent_id.length==0){
			parent_id[0]='';	
		} else {
			parent_id[0]+='-id_';	
		}
		link_name = document.domain + '_' + parent_id[0]+ '_' + parent_class[1]+'_'+parent_class[0];
		if (typeof j_item.attr('id') !== typeof undefined && j_item.attr('id') !== false){
			link_name += '_' + j_item.attr('id')+'-id';
		}else if(typeof j_item.attr('class') !== typeof undefined && j_item.attr('class') !== false){
			temp_link = j_item.attr('class').split(' ');
			link_name += '_' +temp_link[0];
		}
		return link_name;
}
jQuery(document).ready(function($){
$('.about-btn').hover(function(){
    $('#drop-bg').stop().fadeIn("slow");
    }, function () {
        $('#drop-bg').stop().fadeOut("slow");
   });
   $('.about-btn2').hover(function(){
    $('#drop-bg2').stop().fadeIn("slow");
    }, function () {
        $('#drop-bg2').stop().fadeOut("slow");
   });
   $('.about-btn3').hover(function(){
    $('#drop-bg3').stop().fadeIn("slow");
    }, function () {
        $('#drop-bg3').stop().fadeOut("slow");
   });
   $('.about-btn4').hover(function(){
    $('#drop-bg4').stop().fadeIn("slow");
    }, function () {
        $('#drop-bg4').stop().fadeOut("slow");
   });
   
   $('.compare.header:not(.right)').hide();
 
  
  var jspleft;
  $(".ui-tabs-panel").mousemove(function(){
     
      jspleft=$(".jspDrag").css("left");
      //console.log(jspleft);
       
       $(".fixedgorizontal").css("left",jspleft);
       
      
  });
 
 
 $(".contact.link").click(function(){
     window.location="/o-kompanii/kontakty/karera/";
 })
   
});


(function($) {
    $.fn.clickToggle = function(func1, func2) {
        var funcs = [func1, func2];
        this.data('toggleclicked', 0);
        this.click(function() {
            var data = $(this).data();
            var tc = data.toggleclicked;
            $.proxy(funcs[tc], this)();
            data.toggleclicked = (tc + 1) % 2;
        });
        return this;
    };
}(jQuery));
if (!Array.prototype.filter) {
    Array.prototype.filter = function(fun /*, thisp*/) {
        var len = this.length >>> 0;
        if (typeof fun != "function") {
            throw new TypeError();
        }

        var res = [];
        var thisp = arguments[1];
        for (var i = 0; i < len; i++) {
            if (i in this) {
                var val = this[i]; // in case fun mutates this
                if (fun.call(thisp, val, i, this)) {
                    res.push(val);
                }
            }
        }

        return res;
    };
}
/*
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
}*/