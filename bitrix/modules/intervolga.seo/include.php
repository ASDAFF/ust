<?

IncludeModuleLangFile(__FILE__);

require_once(__DIR__.'/classes/general/CIvSeo.php');

Class CIntervolgaSeo
{
	function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
	{
		/*if($GLOBALS['APPLICATION']->GetGroupRight("main") < "R")
			return;*/

		$MODULE_ID = basename(dirname(__FILE__));

        if($GLOBALS['APPLICATION']->GetGroupRight($MODULE_ID)!="D")
        {
            $aMenu = array(
                "parent_menu" => "global_menu_services",
                "section" => $MODULE_ID,
                "sort" => 50,
                "text" => GetMessage("intervolga.seo_MODULE_NAME"),
                "title" => GetMessage("intervolga.seo_MODULE_NAME"),
    //			"url" => "partner_modules.php?module=".$MODULE_ID,
                "icon" => "",
                "page_icon" => "",
                "items_id" => $MODULE_ID."_items",
                "more_url" => array(),
                "items" => array(
                    array(
                        'text' => GetMessage("intervolga.seo_LIST"),
                        'url' => $MODULE_ID.'_'.'list.php',
                        'module_id' => $MODULE_ID,
                        "title" => GetMessage("intervolga.seo_LIST"),
                        "more_url" => array($MODULE_ID.'_'.'list.php',$MODULE_ID.'_'.'edit.php'),
                    ),
                )
            );
        }
        //Раскоментировать, деинсталлировать, унсталлировать для отладки меню админки
		/*if (file_exists($path = dirname(__FILE__).'/admin'))
		{
			if ($dir = opendir($path))
			{
				$arFiles = array();

				while(false !== $item = readdir($dir))
				{
					if (in_array($item,array('.','..','menu.php')))
						continue;

					if (!file_exists($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$MODULE_ID.'_'.$item))
						file_put_contents($file,'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.$MODULE_ID.'/admin/'.$item.'");?'.'>');

					$arFiles[] = $item;
				}

				sort($arFiles);

				foreach($arFiles as $item)
					$aMenu['items'][] = array(
						'text' => $item,
						'url' => $MODULE_ID.'_'.$item,
						'module_id' => $MODULE_ID,
						"title" => "",
					);
			}
		}*/
        if(is_array($aMenu))
		    $aModuleMenu[] = $aMenu;
	}

    function SeoOnPanelCreate()
    {
        global $APPLICATION, $USER;

        if (!$USER->CanDoOperation('seo_tools'))
            return false;


            $currentFilePathParam = $APPLICATION->GetCurPageParam();
            $currentFilePathParam = CIvSeo::UrlReplacer( $currentFilePathParam, self::ExcludeOptions(), '');


        $enccurrentFilePathParam = urlencode($currentFilePathParam);
        $encRequestUri = urlencode($_SERVER["REQUEST_URI"]);


        $arOrder=array();
        $arFilter = array(
            "URL" => $currentFilePathParam,
            "LID" => SITE_ID
        );
        $rsSeo = CIvSeo::GetList($arOrder,$arFilter);
        if($el = $rsSeo->Fetch()) {
            $id = $el["ID"];
        }

        $arMenu = array(); // подпункты меню
        /*$arMenu[] = array(
            "ACTION" =>$APPLICATION->GetPopupLink(
                    array(
                        "URL"=>"/bitrix/admin/intervolga.seo_analysis.php?&lang=".LANGUAGE_ID."&bxpublic=Y&from_module=intervolga.seo&site=".SITE_ID
                        ."&path=".$enccurrentFilePathParam
                        ."&".bitrix_sessid_get()
                        ."&back_url=".$encRequestUri,
                        "PARAMS"=> Array("width"=>920, "height" => 400, 'resize' => false)
                    )),
            "ALT"=>GetMessage("intervolga.seo_ANALYSIS_PAGE_ALT"),
            "TEXT"=>GetMessage("intervolga.seo_ANALYSIS_PAGE"),
            "SORT"=> 5,
        );*/
        if($id)
        {
            $arMenu[] = array(
                "SEPARATOR" => 1
            );
            $arMenu[] = array(
                "TEXT"  => GetMessage("ivseo_delete"),
                "TITLE"  => GetMessage("ivseo_delete_text"),
                "SORT" => 10, //индекс сортировки пункта
                "ACTION" => "if(confirm('".GetMessageJS('ivseo_del_conf')."'))window.location='".CUtil::JSEscape('/bitrix/admin/intervolga.seo_list.php?action=delete&ID[]='.$id.'&lang='.LANGUAGE_ID.'&backurl='.urlencode($APPLICATION->GetCurPageParam()).'&'.bitrix_sessid_get())."'",
                "DEFAULT" => true, //пункт по умолчанию?
                "MENU" => Array() //массив подменю
            );

            $APPLICATION->AddPanelButton(array(
                "HREF"=> 'javascript:'.$APPLICATION->GetPopupLink(
                    array(
                        "URL"=>"/bitrix/admin/intervolga.seo_edit.php?ID=".intval($id)."&lang=".LANGUAGE_ID."&bxpublic=Y&from_module=intervolga.seo",
                        "PARAMS"=> Array("width"=>920, "height" => 400, 'resize' => false)
                    )),
                "ID"=>"seointervolga",
                "ICON" => "bx-panel-seo-icon",
                "ALT"=>GetMessage("INTERVOLGA_SEO_IZMENITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY"),
                "TEXT"=>GetMessage("INTERVOLGA_SEO_IZMENITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY"),
                "MAIN_SORT"=>"300",
                "SORT"=> 50,
                "HINT" => array(
                    "TITLE" => GetMessage("INTERVOLGA_SEO_IZMENITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY"),
                    "TEXT" => GetMessage("INTERVOLGA_SEO_IZMENITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY")
                ),
                "MENU" => $arMenu,
            ));
        }
        else
        {
            $APPLICATION->AddPanelButton(array(
                "HREF"=> 'javascript:'.$APPLICATION->GetPopupLink(
                    array(
                        "URL"=>"/bitrix/admin/intervolga.seo_edit.php?lang=".LANGUAGE_ID."&bxpublic=Y&from_module=intervolga.seo&page=".$enccurrentFilePathParam."&pagenotget=".$encCurrentFilePath."&SITE_ID=".SITE_ID,
                        "PARAMS"=> Array("width"=>920, "height" => 400, 'resize' => false)
                    )),
                "ID"=>"seointervolga",
                "ICON" => "bx-panel-seo-icon",
                "ALT"=>GetMessage("INTERVOLGA_SEO_DOBAVITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY"),
                "TEXT"=>GetMessage("INTERVOLGA_SEO_DOBAVITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY"),
                "MAIN_SORT"=>"300",
                "SORT"=> 50,
                "HINT" => array(
                    "TITLE" => GetMessage("INTERVOLGA_SEO_DOBAVITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY"),
                    "TEXT" => GetMessage("INTERVOLGA_SEO_DOBAVITQ")." SEO-".GetMessage("INTERVOLGA_SEO_PROPERTY")
                ),
                "MENU" => $arMenu,
            ));
        }

    }

    function SeoOnEpilog()
    {
        if(CModule::IncludeModule("intervolga.seo"))
        {
            global $APPLICATION;
            $currentFilePathParam = $APPLICATION->GetCurPageParam();
            $page = CIvSeo::UrlReplacer( $currentFilePathParam, self::ExcludeOptions(), '');

            //TODO: Подумать над автокешированием

            $rsSeo = CIvSeo::GetForURL($page);

            if($ob = $rsSeo->Fetch())
            {
                $arResult = $ob;
            }

            if (strlen( $arResult["TITLE"] ) ) {
                $APPLICATION->SetPageProperty("title", $arResult["TITLE"]);
            }

            if (strlen( $arResult["KEYWORDS"] ) ) {
                $APPLICATION->SetPageProperty("keywords", $arResult["KEYWORDS"]);
            }

            if (strlen( $arResult["DESCRIPTION"]) ) {
                $APPLICATION->SetPageProperty("description", $arResult["DESCRIPTION"]);
            }

            if (strlen( $arResult["H1"]) ) {
                $APPLICATION->SetTitle($arResult["H1"]);
            }

            if ( $arResult["CANONICAL"]=="Y" ) {
                $protocol = ''; $hostname = '';
                $hostname = $_SERVER['SERVER_NAME'];
                $protocol = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';

                $APPLICATION->AddHeadString('<link rel="canonical" href="'.$protocol.$hostname.$arResult["URL"].'" />',true);
            }

            if(strlen( $arResult["TEXT1"] ) || strlen( $arResult["TEXT2"] ) || strlen( $arResult["TEXT3"] )){
                global $IvSeoText;
                $IvSeoText["TEXT1"]=$arResult["TEXT1"];
                $IvSeoText["TEXT2"]=$arResult["TEXT2"];
                $IvSeoText["TEXT3"]=$arResult["TEXT3"];
            }

			if(self::IsSmartPagenavEnabled() && $number=self::HasPagenavOnPage() && self::IsSmartPagenavAllowedByExcludeList())
			{
				self::SmartPagenav($number); 
			}
        }
    }

    function SeoOnEndBufferContent(&$content)
    {
        if(CModule::IncludeModule("intervolga.seo") && !CSite::InDir('/bitrix/')) //Подключаем инфоблоки и исключаем работу кода для админки
        {
            global $IvSeoText;
            if ( $IvSeoText ){
                $search=array();
                $replace=array();
                if($IvSeoText["TEXT1"])
                {
                    $search[]='<!--seo_text1-->';
                    $replace[]=$IvSeoText["TEXT1"];
                }
                if($IvSeoText["TEXT2"])
                {
                    $search[]='<!--seo_text2-->';
                    $replace[]=$IvSeoText["TEXT2"];
                }
                if($IvSeoText["TEXT3"])
                {
                    $search[]='<!--seo_text3-->';
                    $replace[]=$IvSeoText["TEXT3"];
                }
                $content = str_replace($search, $replace, $content);
            }
        }
    }

	function SmartPagenav($number=1)
	{
		/**
		 * @global CMain
		 */
		global $APPLICATION;

		$pageNumber = intval($_GET['PAGEN_'.$number]);
        $showAll = intval($_GET['SHOWALL_'.$number]);
        $navString=false;


        $template = $showAll>0 ? COption::GetOptionString('intervolga.seo','smart_pagenav_all_template') : COption::GetOptionString('intervolga.seo','smart_pagenav_template');

        if($template)
        {
            if($pageNumber)
            {
                $navString=CComponentEngine::makePathFromTemplate($template, array("PAGE" => $pageNumber));
            }
            elseif($showAll==0)
            {
                $navString=CComponentEngine::makePathFromTemplate($template, array("PAGE" => 1));
            }
            elseif($showAll>0)
            {
                $navString=$template;
            }

            if($navString)
                $APPLICATION->SetPageProperty("title",  $APPLICATION->GetPageProperty("title") . $navString );
        }
	}

    protected function ExcludeOptions()
    {
        return array('login', 'back_url_admin', 'clear_cache', 'logout_butt', 'bitrix_include_areas'); //Опции для исключения; NOTE: Перенесено из класса CIvSeo из-за проблем в демо-версии
    }

	protected function IsSmartPagenavEnabled()
	{
		return COption::GetOptionString('intervolga.seo','smart_pagenav') == "Y";
	}

	protected function HasPagenavOnPage()
	{
        $get=$_GET;
        asort($get); //сотрируем массив $_GET, чтобы у нас всегда отрабатывала самая младшая постраничность
        foreach($_GET as $cell=>$value)
        {
            if(stripos($cell,'PAGEN_')!==false)
            {
                return substr($cell,6);
            }
            elseif(stripos($cell,'SHOWALL_')!==false)
            {
                return substr($cell,8);
            }
        }
        return false;
		//return (isset($_GET['PAGEN_1']) && intval($_GET['PAGEN_1']) > 1) || (isset($_GET['SHOWALL_1']) && intval($_GET['SHOWALL_1']) > 0);
	}

	/**
	 * Страница не содержится в списке исключений для умной постраничности
	 *
	 * @return bool
	 */
	protected function IsSmartPagenavAllowedByExcludeList()
	{
		/**
		 * @global CMain
		 */
		global $APPLICATION;

		$excludes = explode("\n", COption::GetOptionString('intervolga.seo','smart_pagenav_excludes'));

        $currentFilePathParam = $APPLICATION->GetCurPageParam('','',true);
        $curUri = CIvSeo::UrlReplacer( $currentFilePathParam, self::ExcludeOptions(), '');
		foreach ($excludes as $exclude)
		{
			if($exclude && stripos($curUri, $exclude) === 0)
				return false;
		}

		return true;
	}
    public function wwwRedirect()
    {
        global $APPLICATION;

        if(COption::GetOptionString('intervolga.seo','www_redirect') == 'Y') {
            $_url = $_SERVER["SERVER_NAME"];
            $_protocol = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
            $_params = $APPLICATION->GetCurPageParam();

            if(strpos($_url.$_params, $_url."/bitrix") === false){
                if( (strpos($_protocol.$_url, $_protocol."www") === false) && (COption::GetOptionString('intervolga.seo','www_redirect_opt') == 'w') )
                    LocalRedirect($_protocol."www.".$_url.$_params, false, "301 Moved Permanently");
                elseif( (strpos($_protocol.$_url, $_protocol."www") !== false) && (COption::GetOptionString('intervolga.seo','www_redirect_opt') == 'wo') )
                    LocalRedirect(strtr($_protocol.$_url, array('www.'=>'')).$_params, false, "301 Moved Permanently");
            }
        }
    }
}
?>