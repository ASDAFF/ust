$(function () {
    $(document).ready(function(){

        var compareTables = $('div.compare > div.compare-table'); // получаем массив контейнеров
        compareTables.hide().filter(':first').show(); // прячем все, кроме первого;
        // далее обрабатывается клик по вкладке
        $('div.compare ul.navigation a').click(function (e) {
            e.preventDefault();
            compareTables.hide(); // прячем все табы
            compareTables.filter(this.hash).show().children('.data-form').jScrollPane(); // показываем содержимое текущего
            $('div.compare ul.navigation a').removeClass('selected'); // у всех убираем класс 'selected'
            $('div.compare ul.navigation a').parent('li').removeClass('selected'); // у всех убираем класс 'selected'
            $(this).addClass('selected'); // текушей вкладке добавляем класс 'selected'
            $(this).parent('li').addClass('selected'); // текушей вкладке добавляем класс 'selected'   

        }).filter(':first').click();
        
         
        if (browser1 = 'Google Chrome'){
            /*var h = $('.data-table').height();
            console.log(h);*/
            var h1 = $('.jspContainer').height();
            h1 = h1+40;
            $('.jspContainer').height(h1);
        }
        
        $('td, th').not('.first-col').mouseenter(function(){
            var thissClass = $(this).attr('class');
            $('.comparison_'+thissClass).addClass('active');
            $('.order_'+thissClass).addClass('active');
            
        }).mouseleave(function(){
            $('.comparison').removeClass('active');
            $('.order').removeClass('active');
        });
        
        $(".comparison a.comparis").click( function() {
            /*e.preventDefault();*/
            delCompare($(this), $(this).attr("id"));
        });
        
       function delCompare(el, id) {
        el.addClass("disable");
             if(el.hasClass("del") == true) {
                $.post('/ajax/ajax.php', 
                    {
                        FormType: 'delCompare', 
                        ID: id
                    }, 
                    function(data) {
                        el.removeClass("disable");
                        if(data.STATUS == 1) {
                            updateComparison();
                        }
                    },
                    'json'
                );
            }
        }

        function updateComparison() {
            $.post('/ajax/ajax.php', 
                {
                    FormType: 'updateComparisonList'
                }, 
                function(data) {
                    if(data.COUNT > 0) {
                        /*if($('.compare.header a').size() > 0) {
                            $('.compare.header a > span').text(data.COUNT);
                        }
                        else {
                            $('.compare.header').html('<a href="/catalog/compare/" title="Перейти в список сравнения">Сравнение (<span>'+data.COUNT+'</span>)</a>');
                        }*/
                        window.location.reload()
                    }
                    else {
                        /*$('.compare.header').html('');*/
                        window.location.reload()
                    }
                },
                'json'
            );
        }
    });
    
    $(".data-table button.order").on("click", function(e) {
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
        var type = $(this).closest("button.order").find("input[name='type_element']").val();
        var element_array = new Array(); 
        $(this).closest("td").find("input.element").each(function() {    
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
    
    $("button.order-choosed").on("click", function(e) {
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
        var type = $(this).closest("button.order-choosed").find("input[name='type_element']").val();
        var checked = $('.checkbox :checked');
        var result = new Array();
        result [result.length] = $('.checkbox').filter(':checked').val();
        console.log(result);
        var element_array = new Array(); 
       $('.checkbox').filter(':checked').each(function() {    
            element_array[element_array.length] = $(this).val();
            console.log(element_array);
        });
        console.log(element_array);
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
            /*$('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", element_array[0]["NAME"]);*/
                $('#order_catalog').find('input[name="DESCRIPTION"]').attr("value", element_array); 
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
   
    
});