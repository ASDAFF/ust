<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<script type="text/javascript">
    if (!window.jQuery) {
        //document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js">')
    }
</script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="/bitrix/components/universal_analytics/base/templates/.default/js/evercookie/js/swfobject-2.2.min.js"></script>
<!--script type="text/javascript" src="http://www.java.com/js/dtjava.js"></script-->
<!--<script type="text/javascript" src="/bitrix/components/universal_analytics/base/templates/.default/js/evercookie/js/evercookie.js"></script>-->
<script type="text/javascript" src="/bitrix/components/universal_analytics/base/templates/.default/js/evercookie_0.1/evercookie.js"></script>
<!--script type="text/javascript" src="/bitrix/components/universal_analytics/base/templates/.default/js/scripts.js"></script-->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');


    //    var userId = (new Date()).getTime().toString();
    //
    //    ga('create', 'UA-50892414-1', {userId: userId});
    //    ga('require', 'displayfeatures');
    //    ga('set', 'dimension1', userId);
    //    ga('send', 'pageview',{
    //        'dimension5':  userId
    //    });


    //    function readCookie(name) {
    //        name += '=';
    //        for (var ca = document.cookie.split(/;\s*/), i = ca.length - 1; i >= 0; i--)
    //            if (!ca[i].indexOf(name))
    //                return ca[i].replace(name, '');
    //    }
    //
    //    var gaUserCookie = readCookie("_ga");
    //
    //    if (gaUserCookie != undefined) {
    //        var cookieValues = gaUserCookie.split('.');
    //        if (cookieValues.length > 2 )
    //        {
    //            var userId = cookieValues[2];
    //            try {
    //                ga('create', 'UA-50892414-3', {userId: userId});
    //                ga('set', 'dimension1', userId);
    //                ga('send', 'event', 'Custom Variables', 'Set UserId', {'nonInteraction': 1});
    //            } catch(e) {}
    //        }
    //    }

    <?php
//$id_user_web = $_session["id_user_web"];
//
//if (isset($id_user_web)){
//    $gacode ="ga('create', 'ua-50892414-3', 'auto'); \n"
//            . "ga('set', 'dimension1', '$id_user_web'); \n"
//            . "ga('require', 'displayfeatures'); \n"
//            . "ga('send', 'pageview'); \n";
//    echo $gacode;
//    echo 'update_user_properties();';
//}
//else
//{
        ?>

    $(document).ready(function()
    {
        var id_user_web;
        var ec = new evercookie({
            baseurl: "/bitrix/components/universal_analytics/base/templates/.default/js/evercookie_0.1",
            phpuri: "/php",
            pngPath: "/evercookie_png.php",
            java: false
        });


//    var ec = new evercookie();

    ec.get("id_user_web", function(value) {
        id_user_web=value;
        alert(id_user_web);
    });


        //var id_user_web = ec.get("id_user_web");

        if (id_user_web == undefined)
        {
            $.get("/universal_analytics/api.php?action=create_user", function(data) {
                var id_user_web=data.toString();
                if(id_user_web.length>0){
                    ec.set("id_user_web", id_user_web);
                    ga('create', 'UA-50892414-3', 'auto');
                    ga('set', 'dimension1',id_user_web);
                    ga('require', 'displayfeatures');
                    ga('send', 'pageview');
                }
            });
            console.log('create_user');
        }
        else
        {
            id_user_web=id_user_web.toString();
            if(id_user_web.length>0){
                $.get("/universal_analytics/api.php",{ action: "set_session_id_user_web", id_user_web: id_user_web });
                update_user_properties();
                ga('create', 'UA-50892414-3', 'auto');
                ga('set', 'dimension1',id_user_web);
                ga('require', 'displayfeatures');
                ga('send', 'pageview');
                get_visitor_info();
                console.log('evercookie');
            }

        }

    });
    <?
//    }
    ?>

    function get_visitor_info(){
        $.get('/universal_analytics/api.php',{ action: 'test'}, function(data){
            if(data=='false'){
                setTimeout(get_visitor_info, 60000);
            }else{
                $('body').append(data);
            }
        });
    }

    function update_user_properties(){
        $.get("/universal_analytics/api.php",{ action: "update_user_properties"},function(data){
            if(data=='false'){
                setTimeout(update_user_properties, 60000);
            }else{
                $('body').append(data);
            }
        });
    }

</script>