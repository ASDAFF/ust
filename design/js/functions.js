$(document).ready(function(){
   // checkUplinkPosition();

    /*$(".rent-item th").hover(function(){
      var that = $(this);
      var rentItem = that.closest('.rent-item');
      var col = 3 + that.parent().children().index($(this));
      //var row = $(this).parent().parent().children().index($(this).parent());
      that.addClass('hovered');
      rentItem.find("tbody td:nth-child("+col+")").addClass('hovered');
    }, function(){
      $(".rent-item .hovered").removeClass('hovered');
    });


    $(".rent-item tr.odd").hover(function(){
      var that = $(this);
      that.next().find('.line-cell').addClass('hovered');
      // $(".rent-item tbody td:nth-child("+col+")").addClass('hovered');
    }, function(){
      $(".rent-item .hovered").removeClass('hovered');
    });
	
    $(".faq .question a").click(function(){
      $(this).closest('.item').toggleClass('active').siblings().removeClass('active');
      return false;
    });


    $(".more-videos .more-videos-link").click(function(){
      $(".more-videos .tooltip").toggle();
      return false;
    });

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
    var starttimer;
    $(".catalog-table").hover(function() {
        var element = $(this);
        element.addClass('hover');
        clearTimeout(starttimer);
      }, function() {
        var element = $(".catalog-table");
        element.removeClass('hover');
        starttimer = setTimeout(function(){
          if(!element.hasClass("hover")){
            //element.removeClass('hover');
            $(".flying-plate").hide();
          };
        },200);
    });*/
   

   /* $('.contacts-map-link').click(function(){
        $(".overlay").show();
        $(".contacts-map-popup").show();
        bodyLock();
        //setTimeout(reloadWindow, 500);
        return false;
    });*/

   /* function reloadWindow() {
      $('.overlay .contacts-map-popup').css('position','relative');
    }*/

   /* $(document).click(function(e) {
        var target = $(e.target);
        if (!target.is('.custom-select') &&
            !target.closest('.custom-select').length &&
            !target.closest('.custom-select .select-list').length) {
            $('.custom-select').removeClass('active');
        }
    });*/

  /*  $(".custom-select .selected").click(function(){
        var that = $(this);
        var parent = that.parent();
        if (parent.hasClass('active')) {
          parent.removeClass('active');
          $(".custom-select").removeClass('active');
        } else {
          $(".custom-select").removeClass('active');
          parent.addClass('active');
        }

    });
    $(".custom-select .select-list li").click(function(){
        var that = $(this);
        var container = that.closest('.custom-select');
        that.addClass('active').siblings().removeClass('active');
        var activeItem = container.children('.select-list').find('.active');
        container.children('.selected').html(activeItem.html());
        container.toggleClass('active');

    });*/

   /* $('.popup').click(function(){
      $(".custom-select").removeClass('active');
    });*/
  
  /*  $(document).on("click", ".logos .next", function(){
        var list = $(this).parent().parent().find(".list-pages");
        var listSize = list.children(".page").size();
        var itemWidth = list.children(".page").outerWidth(true);
        var slideNum = list.data("page-num");
        //var slidePages = Math.ceil(listSize/7);
        slideNum = parseInt(slideNum+1,10);
        if (slideNum < listSize) {
            list.parent().addClass('lock');
            list.data("page-num", slideNum);
            list.children('.page').css('visibility', 'visible');
            list.animate({"margin-left":-(itemWidth) * slideNum}, function() {
              list.children('.page[data-page='+slideNum+']').css('visibility', 'visible').siblings().css('visibility', 'hidden');
              list.parent().removeClass('lock');
            });
        }
        return false;
    });*/

    /* $(".logo-item .hover-place").click(function(){
      $(this).next('.logo-link').trigger('click');
    });*/
   
	/*$(document).on("click", ".logos .prev", function(){
        var list = $(this).parent().parent().find(".list-pages");
        var listSize = list.children(".page").size();
        var itemWidth = list.children(".page").outerWidth(true);
        var slideNum = list.data("page-num");
        //var slidePages = Math.ceil(listSize/7);
        slideNum = slideNum - 1;
        if (slideNum >= 0) {
            list.parent().addClass('lock');
            list.data("page-num", slideNum);
            list.children('.page').css('visibility', 'visible');
            list.animate({"margin-left":-(itemWidth) * slideNum}, function() {
              list.children('.page[data-page='+slideNum+']').css('visibility', 'visible').siblings().css('visibility', 'hidden');
              list.parent().removeClass('lock');
            });
        }
        return false;
    });

    $('.logos').on('mousewheel', function(event) {
      if ($(this).find('.list').hasClass('lock')) {
        return false;
      }
      if (event.deltaY === -1) {
        $( ".logos .next").trigger('click');
      } else {
        $( ".logos .prev").trigger('click');
      }
      return false;
	});

    $('input, textarea').placeholder();

    

    $(".cat-sections .item").hover(function(){
      var that = $(this);
        if(!that.hasClass("active") && !that.hasClass("close-item")) {
          that.addClass("active").siblings().removeClass("active");
          var catPage = that.data('cat-page');
          $(".cat-page-"+catPage).addClass("active").siblings().removeClass("active");
        }
    });  

    $('nav li').hover(function() {
      var catPopupHeigth = $('.nav-hover-popup').height();
      var outerHeight = 0;
      $('.cat-sections .item').each(function() {
        outerHeight += $(this).outerHeight();
      });
      $('.cat-sections').find('.empty-item').height(catPopupHeigth-outerHeight);
    });

    $(".test-thx-popup").click(function() {
        $(".callback-popup").hide();
        $(".callback-thx-popup").show();
    });*/
});

/*$(window).resize(function(){
    checkUplinkPosition();
});*/



/*function checkUplinkPosition(){
    $(".up-button a").css('bottom', $('footer').height() + 42);
    if ($(".up-button").length > 0) {
        var consultDiv = $(".up-button");
        var clientWidth = getClientWidth();
        if (clientWidth < 1360) {
            consultDiv.addClass("min");
        } else {
            consultDiv.removeClass("min");
        }
    }
}*/

/*function scrollbarWidth() {
      "use strict";
      var parent = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body'),
          child  = parent.children(),
          width  = child.innerWidth() - child.height(99).innerWidth();
      parent.remove();
      return width;
  }*/

 /* function get_scroll(a) {
      "use strict";
      var d = document,
          b = d.body,
          e = d.documentElement,
          c = "client" + a;
      a = "scroll" + a;
      return /CSS/.test(d.compatMode) ? (e[c] < e[a]) : (b[c] < b[a]);
  }*/

  /*function bodyLock() {
      "use strict";
      var bodySelector = $("body");
      if (!bodySelector.hasClass('lock')) {
          if ((bodySelector.css('margin-right') == '0px') && get_scroll('Height')) {
              bodySelector.css('margin-right', scrollbarWidth);
          }
          bodySelector.addClass('lock');
      }
  }

  function bodyUnLock() {
      "use strict";
      var bodySelector = $("body");
      if (bodySelector.hasClass('lock') && ($("#overlay").length == '0')) {
          if (bodySelector.css('margin-right') != '0px') {
              bodySelector.css('margin-right', '0px');
          }
          bodySelector.removeClass('lock');
      }
  }*/

/*function tabClick(tabId) {
    if (tabId !== $('.tabs a.active').attr('id') ) {
        var tabNum = tabId.replace("tab-", "");
        $('.tabs a.active').removeClass('active');
        $('#tab-' + tabNum).addClass('active');
        $('#page-' + tabNum).addClass('active').siblings().removeClass("active");
    }
}*/
//function getClientWidth(){return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;}