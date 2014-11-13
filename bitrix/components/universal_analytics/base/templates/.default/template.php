<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<style>
    .whiteField {
        height: 28px;
        width: 261px;
        background-color: #FFF;
        position: fixed;
        margin-top: -38px;
        margin-left: 0px;
        top: 100%;
        text-align: justify;
        padding-top: 10px;
        padding-right: 15px;
        padding-left: 15px;
        color: #353535;
        font-size: 14px;
        font-weight: normal;
        font-family: Verdana, Geneva, sans-serif;
        -webkit-border-top-right-radius: 10px;
        -moz-border-radius-topright: 10px;
        border-top-right-radius: 10px;
        z-index: 999;
        -webkit-box-shadow: 0px 0px 2px 0px rgba(50, 50, 50, 0.5);
        -moz-box-shadow:    0px 0px 2px 0px rgba(50, 50, 50, 0.5);
        box-shadow:         0px 0px 2px 0px rgba(50, 50, 50, 0.5);
        display: none;
    }
    .whiteField #id_user_web {
        font-size: 18px;
        color: #ec2b32;
        font-weight: bold;
        float: right;
        margin-top: -2px;
    }
</style>


<div class="whiteField">
    Ваш уникальный номер:
    <span id="id_user_web"></span>
</div>

<script type="text/javascript">
    if (!window.jQuery) {
        //document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js">')
    }
</script>
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>-->
<script type="text/javascript" src="/bitrix/components/universal_analytics/base/templates/.default/js/evercookie/js/swfobject-2.2.min.js"></script>
<!--script type="text/javascript" src="http://www.java.com/js/dtjava.js"></script-->
<script type="text/javascript" src="/bitrix/components/universal_analytics/base/templates/.default/js/evercookie/js/evercookie.js"></script>
<!--script type="text/javascript" src="/bitrix/components/universal_analytics/base/templates/.default/js/scripts.js"></script-->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    $(document).ready(function(){



        if(window.location.href.indexOf('?utm_')!==-1){
            var url = window.location.href.substr(0,window.location.href.indexOf('?'));
            var getParams = window.location.search.substr(1).split("&");
            var source='';
            var campaign='';
            var content='';
            var banner_id=''; // must be empty

            if(getParams.length>0){

                function getUTM(value, index, ar) {

                    if(value.indexOf('utm_source')!==-1){
                        var campaignArr=value.split('=');
                        source=campaignArr['1'];
                    }

                    if(value.indexOf('utm_campaign')!==-1){
                        var campaignArr=value.split('=');
                        campaign=campaignArr['1'];
                    }

                    if(value.indexOf('utm_content')!==-1){
                        var contentArr=value.split('=');
                        content=contentArr['1'];
                    }

                    if(value.indexOf('banner_id')!==-1){
                        var contentArr=value.split('=');
                        banner_id=contentArr['1'];
                    }
                }

                getParams.forEach(getUTM);

                if(banner_id!=='' && url!==''){
                    $.ajax({
                        type: "GET",
                        url: "/universal_analytics/api.php?action=create_user_point_by_utm",
                        data: {
                            source: source,
                            campaign: campaign,
                            content: content,
                            banner_id: banner_id,
                            REQUEST_URI: url
                        },
                        success:function(result){
                            console.log(result);
                        }
                    });
                }
            }
        }


            $.ajax({
                type: "GET",
                url: "/universal_analytics/api.php?action=save_user_history",
                data: {'REQUEST_URI': document.URL },
                success:function(result){
                    console.log(result);
                }
            });


    });

    var ec = new evercookie({
        baseurl: "/bitrix/components/universal_analytics/base/templates/.default/js/evercookie",
        phpuri: "/php",
        pngPath: "/evercookie_png.php",
        java: false
    });
    <?

        if(isset($_SESSION["id_user_web"]) && $_SESSION["id_user_web"]!==''){
            $id_user_web = $_SESSION["id_user_web"];
        }
        else{
            unset($id_user_web);
        }

        if (isset($id_user_web)){
            $id_user_web=(string)$id_user_web;

            echo 'ec.set("id_user_web", '.$id_user_web.');'."\n\n";
            echo '    get_visitor_info('.$id_user_web.');'."\n\n";
            echo '    var yaParams = {'.$id_user_web.' : '.$id_user_web.', unique: '.time().'};'."\n\n";

        }
        else
        {
    ?>

    $(document).ready(function()
    {
        var id_user_web;

        ec.get("id_user_web", function(id_user_web) {
            if (id_user_web == undefined)
            {
                $.get("/universal_analytics/api.php?action=create_user", function(data) {
                    var id_user_web=data.toString();
                    if(id_user_web.length>0){
                        ec.set("id_user_web", id_user_web);
                    }
                });
                console.log('create_user');
            }
            else
            {
                id_user_web=id_user_web.toString();
                if(id_user_web.length>0){
                    $.get("/universal_analytics/api.php",{ action: "set_session_id_user_web", id_user_web: id_user_web });
                    console.log('Seting a session userID from evercookie '+ id_user_web);
                }
            }
            if(id_user_web!==NaN){
                get_visitor_info(id_user_web);
            }
        });

    });
    <?
    }
    ?>

    function get_visitor_info(id_user_web){
        var id_user_web=id_user_web.toString();

        if(id_user_web.length>0 && $(".whiteField #id_user_web").length>0){
            $(".whiteField #id_user_web").html(id_user_web);
            $(".whiteField").show('slow');
        }

        $.get('/universal_analytics/api.php',{ action: 'test', 'REQUEST_URI': document.URL }, function(data){
            if(data!=='true'){
                setTimeout(function(){
                    get_visitor_info(id_user_web);
                }, 120000);
            }

            ga('create', 'UA-50892414-1', 'auto');
            ga('set', 'dimension1',id_user_web.toString());
            ga('require', 'displayfeatures');
            ga('send', 'pageview');

            var yaParams = {id_user_web : id_user_web, unique: <?=time();?>};
        });
    }

</script>

<!-- Yandex.Metrika counter-->
<script type="text/javascript">
    (function(d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter24904661 = new Ya.Metrika({id: 24904661, webvisor: true, clickmap: true, trackLinks: true, accurateTrackBounce: true, trackHash:true, params: window.yaParams || {}});
            } catch (e) {
            }
        });
        var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function() {
            n.parentNode.insertBefore(s, n);
        };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
    <div>
        <img src="//mc.yandex.ru/watch/24904661" style="position:absolute; left:-9999px;" alt="" />
    </div>
</noscript>
<!-- /Yandex.Metrika counter-->

<span style="display: none;">
<?$APPLICATION->IncludeComponent("unispec:phones",".default",Array(

    )
);?>
</span>
