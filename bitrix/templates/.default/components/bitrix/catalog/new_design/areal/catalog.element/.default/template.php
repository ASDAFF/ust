<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$file = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/templates/.default/components/bitrix/catalog/new_design/areal/catalog.element/.default/test.txt";

function getParent($id){
		$tt = CIBlockSection::GetList(array(), array('ID'=>$id));
		$as=$tt->GetNext();
		static $a;
		if($as['DEPTH_LEVEL']==1) $a = $as['ID'];
		else{
		getParent($as['IBLOCK_SECTION_ID']);
		}
		return $a;
		}


function nth_strpos($str, $substr, $n, $stri = false)
{
    if ($stri)
    {
        $str = strtolower($str);
        $substr = strtolower($substr);
    }
    $ct = 0;
    $pos = 0;
    while (($pos = strpos($str, $substr, $pos)) !== false)
    {
        if (++$ct == $n)
        {
            return $pos;
        }
        $pos++;
    }
    return false;
}
?>

<?
$CATALOG_order = COption::GetOptionInt("ust", "CATALOG_order");
$CATALOG_question = COption::GetOptionInt("ust", "CATALOG_question");
$CATALOG_kredit = COption::GetOptionInt("ust", "CATALOG_kredit");
$CATALOG_arenda = COption::GetOptionInt("ust", "CATALOG_arenda");
$CATALOG_used = COption::GetOptionInt("ust", "CATALOG_used");
$CATALOG_where_buy = COption::GetOptionInt("ust", "CATALOG_where_buy");
$CATALOG_service_centers = COption::GetOptionInt("ust", "CATALOG_service_centers");

$strElementEdit = CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_EDIT");


$arButtons = CIBlock::GetPanelButtons(
                $arResult["IBLOCK_ID"], $arResult["ID"], 0, array("SECTION_BUTTONS" => false, "SESSID" => false)
);
$arResult["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
$this->AddEditAction($arResult['ID'], $arResult['EDIT_LINK'], $strElementEdit);
?>


<? $naves = FALSE;
if ($arResult['SECTION']['PATH'][0]['ID'] == '243')
{
    $naves = TRUE;
} ?>
<div class="catalog-detail catalog <? if ($naves)
{
    echo 'naves';
} ?> hproduct vert_design"  id="<? echo $this->GetEditAreaId($arResult['ID']); ?>">
    <span style="display:none" class="fn"><? $APPLICATION->ShowTitle() ?></span>
    <div class="on_top">
    <? if ($CATALOG_question == 1): ?>
            <div><button class="question silver left">Задать вопрос</button></div>
    <? endif; ?>
    <? if ($arParams["TYPE"] == "ELEMENT" && count($arResult["ITEMS"])): ?>
            <div class="comparison visible"></div>
    <? endif; ?>
        <div class="clear"></div>
    </div>
    <?
    $i = 0;
    foreach ($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"] as $sections)
    {
        foreach ($sections as $items)
        {
            $item[$i] = $items;
            $i++;
        }
    }
    ?>

            <? global $USER;
            if ($USER->IsAdmin()) :
                ?>
        
                <?  
                echo "<!--";
                echo '<div style="display:none" class="fff"><pre>';
                //print_r($arResult);
                echo '</pre></div>';
                echo "-->";
                ?> 
        
            <? endif; ?>
            <? //print_r($item);["NATURE"] ?>
    <div class="ppic detail">
        <div class="image-main image">
            <? if (count($arResult["PHOTOS"]) > 0): ?>

            <? endif; ?>

            <? if (!empty($arResult["PHOTOS"][0]["STANDART"]["src"])): ?>
<?
if ($USER->IsAdmin()){

$arWaterMark = Array(
            array(
                "name" => "watermark",
                "position" => "bottomright", // Положение
                "type" => "image",
                "size" => "real",
                "file" => $_SERVER["DOCUMENT_ROOT"].'/images/logo_water.png', // Путь к картинке
                "fill" => "exact",
            )
        );
        $arFileTmp = CFile::ResizeImageGet(
            $arResult["PHOTOS"][0]["STANDART"],
            array("width" => 250, "height" => 127),
            BX_RESIZE_IMAGE_EXACT,
            true,
            $arWaterMark
        );
//var_dump($arResult["PHOTOS"][0]);
echo $arResult["PHOTOS"][0]["NATURE"];
	?><img src="<?=$arResult["PHOTOS"][0]["NATURE"]?>"/><?
} else {    
?>
                <img class="photo" src="<?= $arResult["PHOTOS"][0]["NATURE"] ?>"  alt="<?= $arResult["NAME"] ?>" title="<?= $arResult["NAME"] ?>" /><?}?>
            <? else: ?>
                <img class="photo" src="<?= getImageNoPhoto(338, 278) ?>" alt="<?= $arResult["NAME"] ?>" title="<?= $arResult["NAME"] ?>" />
            <? endif; ?>

            <? if (count($arResult["PHOTOS"]) > 0): ?>
                <span class="full"></span>
                <? if (!empty($arResult["PROPERTIES"]["ACTIONS"]["VALUE"])): ?>
                    <span class="action popup"><?= $arResult["PROPERTIES"]["ACTIONS"]["NAME"] ?></span>
                <? endif; ?>
                <? if (!empty($arResult["PROPERTIES"]["NEW"]["VALUE"])): ?>
                    <span class="new popup"><?= $arResult["PROPERTIES"]["NEW"]["NAME"] ?></span>
                <? endif; ?>
    <? if (!empty($arResult["PROPERTIES"]["SEASONAL"]["VALUE"])): ?>
                    <span class="season popup"><?= $arResult["PROPERTIES"]["SEASONAL"]["NAME"] ?></span>
    <? endif; ?>	
    <? if (!empty($arResult["PROPERTIES"]["SALE"]["VALUE"])): ?>
                    <span class="sale popup"><?= $arResult["PROPERTIES"]["SALE"]["NAME"] ?></span>
    <? endif; ?>	
    <? if (!empty($arResult["PROPERTIES"]["CREDIT"]["VALUE"])): ?>
                    <span class="credit popup"><?= $arResult["PROPERTIES"]["CREDIT"]["NAME"] ?></span>
                    <? endif; ?>		

                    <? endif; ?>
        </div>

        <div class="cl"></div>
        
        
        <div class="preimusshestva">
           <div class="red"></div>
           <div class="pr dost"><span class="upper"><a href="http://ust-co.ru/dostavka/">Доставка</a> по всей России</span></div>
           <div class="pr podd"><span class="upper">Круглосуточная поддержка</span></div>
           <div class="pr zap"><span class="upper">Более 23000 запчастей в наличии</span></div>
           <div class="pr fil"><span><a href="http://ust-co.ru/filialy/">Филиалы</a> и <a href="http://ust-co.ru/servis/">сервис</a> в 35 городах</span></div>
           <div class="pr gar"><span>Расширенная гарантия</span></div>
         </div>
         <script>
         
         
         $(window).load(function() {
            var mainfotoH =  $("img.photo").height();
            var propertiesH =  $("div.properties").height();
            //alert(mainfotoH);
            //alert(propertiesH);
            if((mainfotoH-propertiesH)<100) {
             $("div.preimusshestva").css({"width": "585", "height": "100", left: 265});
             $("div.preimusshestva>div.pr").css({"width": "240"});
             $("div.preimusshestva>div.gar").css({"margin-top": "8px"});
             $("div.preimusshestva>div.red").css({"height": "100"});
            }
            
         });
         
         </script>
        
    </div>
    <div class="properties">
        <h2 class="title">Основные данные</h2>
        <div class="characteristics<? if ($arParams["TYPE"] == "ELEMENT" && !empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])): ?> with-price<? endif; ?>">
            <div class="key_prop list">
                <? if (strpos($arResult["PROPERTIES"]['KEY_PROP']['~VALUE']['TEXT'], '<ul>') == false): ?>
                    <ul>
                    <?= $arResult["PROPERTIES"]['KEY_PROP']['~VALUE']['TEXT']; ?>
                    </ul>
                <? else: ?>
                    <?= $arResult["PROPERTIES"]['KEY_PROP']['~VALUE']['TEXT']; ?>
                <? endif; ?></div>
            <div class="chars">
                <? //print $arParams["TYPE"]; //SERIES
                ?>
                <? if (count($arResult["ITEMS"]) > 1 or $arParams["TYPE"] == "SERIES"): ?>
                        <? if (isset($arResult["CHARACTERISTICS"]["TEXT"]) && isset($arResult["CHARACTERISTICS"]["TYPE"])): ?>
                            <? if ($arResult["CHARACTERISTICS"]["TYPE"] == "text"): ?>
                            <p><?= $arResult["CHARACTERISTICS"]["TEXT"] ?></p>
                            <? else: ?>
            <?= $arResult["CHARACTERISTICS"]["TEXT"] ?>
        <? endif; ?>
                                <? else: ?>
                        <p>К сожалению, общие характеристики не указаны.</p>
                                <? endif; ?>
                            <? else: ?>
                                <? $char = 0;
                                $i = 0 ?>
                    <table>
                                <? foreach ($arResult["ITEMS"][0]["PROPERTIES"] as $val): ?>
                                    <? if (!empty($val["VALUE"])): ?>
                                        <? $char = 1; ?>
                                <tr class="identifier">
                                    <td class="name type"><?= $val["NAME"] ?></td>
                                    <td class="value"><?
                                        //echo $val["VALUE"];
										
										//file_put_contents($file, print_r($res, true), FILE_APPEND);
										$is_with_friend = true;
										//проверка названия свойства и получение значения "Показывать в умном фильтре"
										$res = CIBlockSectionPropertyLink::GetArray($arResult["IBLOCK_ID"], $arResult["IBLOCK_SECTION_ID"]); //"Показывать в умном фильтре"
										$trans_name = "";
										
												   if (str_replace(" ", "", $val["NAME"]) == "Типпривода")  {
												    $trans_name = "pto_tip_privoda";
													$is_in_filter = $res["1212"]["SMART_FILTER"]; // тип привода
													
													$is_with_friend = cache_property_request("1212",$arResult["IBLOCK_ID"],$arResult["IBLOCK_SECTION_ID"]);
													/* $obCache = new CPHPCache();
													$cacheLifetime = 86400*7; $cacheID = 'is_with_friend'.$arResult["IBLOCK_SECTION_ID"]; $cachePath = '/'.$cacheID;
													if( $obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
													{
														$vars = $obCache->GetVars();
														$is_with_friend = $vars['is_with_friend'.$arResult["IBLOCK_SECTION_ID"]]; 
														//file_put_contents($file, "1", FILE_APPEND);
														
													}
													elseif( $obCache->StartDataCache()  )
													{ 
													 //file_put_contents($file, "333", FILE_APPEND);
													
													$proh = 0;
													$arSelect = Array("ID");
													$arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "SECTION_ID"=>$arResult["IBLOCK_SECTION_ID"]);
													$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
														while($ob = $res->GetNextElement()){ 
														$arFields = $ob->GetFields();  
														$db_props = CIBlockElement::GetProperty($arResult["IBLOCK_ID"], $arFields["ID"], array("sort" => "asc"), Array("ID"=>"1212"));
															if($ar_props = $db_props->Fetch()) {
																if ($proh == 0) {
																	$cur_val = $ar_props["VALUE"];
																	$proh++;
																}
																else if ($cur_val != $ar_props["VALUE"]) {
																	$is_with_friend = false;
																	break;
																}
															}
														}
														   $obCache->EndDataCache(array('is_with_friend'.$arResult["IBLOCK_SECTION_ID"] => $is_with_friend));
													} */  
													//$obCache->CleanDir();
													}
													
												   
												   
											else if (str_replace(" ", "", $val["NAME"]) == "Типмашины")  {
													$trans_name = "tip_mashiny";
													$is_in_filter = $res["1350"]["SMART_FILTER"]; // тип машины
													
													$is_with_friend = cache_property_request("1350",$arResult["IBLOCK_ID"],$arResult["IBLOCK_SECTION_ID"]);
													/* $obCache = new CPHPCache();
													$cacheLifetime = 86400*7; $cacheID = 'is_with_friend'.$arResult["IBLOCK_SECTION_ID"]; $cachePath = '/'.$cacheID;
													if( $obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
													{
														$vars = $obCache->GetVars();
														$is_with_friend = $vars['is_with_friend'.$arResult["IBLOCK_SECTION_ID"]]; 
														//file_put_contents($file, "1", FILE_APPEND);
														
													}
													elseif( $obCache->StartDataCache()  )
													{ 
													 //file_put_contents($file, "333", FILE_APPEND);
													 
													$proh = 0;
													$arSelect = Array("ID");
													$arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "SECTION_ID"=>$arResult["IBLOCK_SECTION_ID"]);
													$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
														while($ob = $res->GetNextElement()){ 
														$arFields = $ob->GetFields();  
														$db_props = CIBlockElement::GetProperty($arResult["IBLOCK_ID"], $arFields["ID"], array("sort" => "asc"), Array("ID"=>"1350"));
															if($ar_props = $db_props->Fetch()) {
																if ($proh == 0) {
																	$cur_val = $ar_props["VALUE"];
																	$proh++;
																}
																else if ($cur_val != $ar_props["VALUE"]) {
																	$is_with_friend = false;
																	break;
																}
															}
														}
														   $obCache->EndDataCache(array('is_with_friend'.$arResult["IBLOCK_SECTION_ID"] => $is_with_friend));
													} */  
													//$obCache->CleanDir();
													
												}
											else if (str_replace(" ", "", $val["NAME"]) == "Исполнение")  {
													$trans_name = "isposlenenie_generatory";
													$is_in_filter = $res["669"]["SMART_FILTER"]; // исполнение
													
													$is_with_friend = cache_property_request("669",$arResult["IBLOCK_ID"],$arResult["IBLOCK_SECTION_ID"]);
													/* $obCache = new CPHPCache();
													$cacheLifetime = 86400*7; $cacheID = 'is_with_friend'.$arResult["IBLOCK_SECTION_ID"]; $cachePath = '/'.$cacheID;
													if( $obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
													{
														$vars = $obCache->GetVars();
														$is_with_friend = $vars['is_with_friend'.$arResult["IBLOCK_SECTION_ID"]]; 
														//file_put_contents($file, "1", FILE_APPEND);
														
													}
													elseif( $obCache->StartDataCache()  )
													{ 
													// file_put_contents($file, "333", FILE_APPEND);
													 
													$proh = 0;
													$arSelect = Array("ID");
													$arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "SECTION_ID"=>$arResult["IBLOCK_SECTION_ID"]);
													$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
														while($ob = $res->GetNextElement()){ 
														$arFields = $ob->GetFields();  
														$db_props = CIBlockElement::GetProperty($arResult["IBLOCK_ID"], $arFields["ID"], array("sort" => "asc"), Array("ID"=>"669"));
															if($ar_props = $db_props->Fetch()) {
																if ($proh == 0) {
																	$cur_val = $ar_props["VALUE"];
																	$proh++;
																}
																else if ($cur_val != $ar_props["VALUE"]) {
																	$is_with_friend = false;
																	break;
																}
															}
														}
														   $obCache->EndDataCache(array('is_with_friend'.$arResult["IBLOCK_SECTION_ID"] => $is_with_friend));
													} */  
													//$obCache->CleanDir();
												}
										
                                        if ((str_replace(" ", "", $val["NAME"]) == "Типпривода" ||
                                                str_replace(" ", "", $val["NAME"]) == "Типмашины" ||
                                                str_replace(" ", "", $val["NAME"]) == "Исполнение") && $is_in_filter == "Y" && !$is_with_friend)
                                        {
                                            $cur_dir = $APPLICATION->GetCurDir();

											$nth = nth_strpos($cur_dir, "/", 3, true);// кусок ссылки каталога
                                            $catalog = substr($cur_dir, 0, $nth);
											
                                            $arPrms = array("replace_space" => "_", "replace_other" => "_");// транслитерация
                                            $trans_value = ToLower(Cutil::translit($val["VALUE"], "ru", $arPrms));// типа машины/исполения....

                                            echo '<a class="no-mar" href="' . $catalog . '/filter/'.$trans_name.'-' . $trans_value . '">' . $val["VALUE"] . '</a>';
                                        }
                                        else
                                            echo $val["VALUE"];
                                        ?>
                                    </td>

                                </tr>
                            <? $i++; ?>
                            <? if ($i >= 6) break; ?>
                        <? endif; ?>

    <? endforeach; ?>
                    </table>
            <? if ($char == 0): ?>
                        <p>К сожалению, общие характеристики не указаны</p>
            <? endif; ?>
        <? endif; ?>
            </div>
            <div class="cost">
			
		<? //вывод старой цены
			//file_put_contents($file, print_r($arResult, true));
			$base_price = GetCatalogProductPriceList($arResult["ID"],"ID","ASC");		
			if ($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] != "" && $base_price[0]["PRICE"] != "")  {
				echo '<span class="value" style="font-size: 14px; text-decoration: line-through;">'.$arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"].' '.$base_price[0]["CURRENCY"].'</span>';
				echo '<div class="clear"></div>';
			}
			else if ($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] != "" && $base_price[0]["PRICE"] == "")  {
				echo '<span class="value" style="font-size: 14px; text-decoration: line-through;">'.CurrencyFormat($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"], "RUB").'</span>';
				echo '<div class="clear"></div>';
			}
		?>
		<?if ($base_price[0]["PRICE"] != ""):?>
			<? //вывод базовой цены
				//$base_price = GetCatalogProductPriceList($arResult["ID"],"ID","ASC");
					echo '<span class="value">'.$base_price[0]["PRICE"].' '.$base_price[0]["CURRENCY"].'</span>';
					echo '<div class="clear"></div>';
					echo '<div class="form-button clearfix"><button class="order left">Купить</button></div>';
			?>
		<?else:?>	
			<? if ($arParams["TYPE"] == "ELEMENT" && !empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])): ?>
				<? if ($arResult["PROPERTIES"]["PRICE"]["VALUE"] > 0): ?>
							<span class="value"><?= CurrencyFormat($arResult["PROPERTIES"]["PRICE"]["VALUE"], "RUB"); ?></span>
							<div class="clear"></div>
							<div class="form-button clearfix"><button class="order left">Купить</button></div>
				<? endif; ?>
			<? endif; ?>
		<? endif; ?>


            </div>
        </div>
        <input type="hidden" name="iblock_id" value="<?= $arResult["ITEMS"][0]["IBLOCK_ID"] ?>" />
            <? if ($arParams["TYPE"] == "ELEMENT"): ?>
            <input type="hidden" name="type_element" value="model" />
            <? else: ?>
            <input type="hidden" name="type_element" value="seriya" />
        <? endif; ?>
        <? foreach ($arResult["ITEMS"] as $val): ?>
            <input type="hidden" class="element" name="element_<?= $val["ID"] ?>" value="<?= $val["NAME"] ?>">
            <? endforeach; ?>


            <? if ($CATALOG_order == 1 || $CATALOG_question == 1): ?>
            <div class="form-button clearfix">
                <? if ($CATALOG_order == 1): ?>
                    <? if ($arResult["PROPERTIES"]["PRICE"]["VALUE"] == "" && $base_price[0]["PRICE"] == ""): ?>
					<button class="order left">Запрос цены</button>   
                    <? endif; ?>    
                <? endif; ?>
            </div>
            <? endif; ?>
        <div class="action">
            <? if ($CATALOG_kredit == 1): ?>
                <button class="left credit">Купить в кредит</button>
            <? endif; ?>
            <? if ($CATALOG_where_buy == false): ?>
                <a class="right where_buy" href="/filialy/" title="Филиалы">Где купить</a>
<? endif; ?>
            <div class="clear"></div>

    <? if ($CATALOG_used == 1): ?>
                <button class="left used">Купить Б/У</button>
<? endif; ?>
<? if ($CATALOG_service_centers == false): ?>
                <a class="right service_centers" href="#">Сервисные центры</a>
            <? endif; ?>
            <div class="clear"></div>

<? if ($CATALOG_arenda == 1): ?>
                <button class="left arenda">Взять в аренду</button>
<? endif; ?>

            <div class="clear"></div>
        </div>
        <a href="#characteristics" class="to_chars inner_link">Посмотреть все технические характеристики</a>
    </div>
            <? if (count($arResult["PHOTOS"]) > 1): ?>
        <h4>Техника в работе:</h4>
        <div class="jcarousel small_photos" id="carousel-detail">
            <ul>
                <? foreach ($arResult["PHOTOS"] as $key => $arPhoto): ?>
                    <? if ($key > 0): ?>
                        <li>
                            <a rel="gal1" href="<?= $arPhoto["NATURE"] ?>" width-pic="<?= $arPhoto["RESIZE"]["width"] ?>" height-pic="<?= $arPhoto["RESIZE"]["height"] ?>" class="image <? if ($key == 0): ?>active<? endif; ?> fancybox-thumb">

                                <img big-pic="<?= $arPhoto["NATURE"] ?>" src="<?= $arPhoto["RESIZE"]["src"] ?>" width="<?= $arPhoto["RESIZE"]["width"] ?>" height="<?= $arPhoto["RESIZE"]["height"] ?>" />

                            </a>
                        </li>
            <? endif; ?>
        <? endforeach; ?>
        <? if (count($arResult["PHOTOS"]) < 3): ?>
            <? for ($i = 0; $i < (3 - count($arResult["PHOTOS"])); $i++)
            { ?>
                        <li class="image">
                            <img src="<?= getImageNoPhoto(125, 90) ?>" />
                        </li>
        <? } ?>
    <? endif; ?>
            </ul>
                    <? if (count($arResult["PHOTOS"]) > 13): ?>
                <button class="jcarousel-prev"></button>
                <button class="jcarousel-next"></button>
                            <? endif; ?>
        </div>
                            <? if (!empty($arResult["VIDEO"])): ?>
            <a href="#videos" class="with_arrow inner_link">Посмотреть видео</a>
                            <? endif; ?>
                        <? endif; ?>
                        <? if (!empty($arResult["RELATED_PRODUCTS"]) && !$naves): ?>
        <div class="related-propducts no-list clearfix aside-block">

            <span class="title<? if (count($arResult["RELATED_PRODUCTS"]) <= 2): ?> marginBot<? endif; ?> title-block">Сопутствующие товары:</span>

            <div id="related-propducts">
                <ul>
                            <? foreach ($arResult["RELATED_PRODUCTS"] as $product): ?>
                        <li class="image">
                            <? if (!empty($product["URL"])): ?>
                                <a href="<?= $product["URL"] ?>" title="<?= $product["NAME"] ?>"> 
                        <? endif; ?>

                <? if (!empty($product["PREVIEW_PICTURE"]["src"])): ?>
                                    <img src="<?= $product["PREVIEW_PICTURE"]["src"] ?>" width="<?= $product["PREVIEW_PICTURE"]["width"] ?>" height="<?= $product["PREVIEW_PICTURE"]["height"] ?>" alt="<?= $product["NAME"] ?>" title="<?= $product["NAME"] ?>">
                <? else: ?>
                                    <img src="<?= getImageNoPhoto(120, 90) ?>" alt="<?= $product["NAME"] ?>" title="<?= $product["NAME"] ?>">
            <? endif; ?>

                                <span class="name"><?= $product["NAME"] ?></span>
            <? if (!empty($product["PRICE"])): ?>
                                    <span class="price"><?= $product["PRICE"] ?></span>
                <? endif; ?>
                <? if (!empty($product["URL"])): ?>
                                </a> 
                <? endif; ?>
                        </li>
            <? endforeach; ?>
                </ul>
            </div>
            <? if (count($arResult["RELATED_PRODUCTS"]) > 2): ?>
                <button class="related-propducts-button prev disabled"></button><button class="related-propducts-button next"></button>
            <? endif ?>
        </div>
        <? endif ?>

    <div class="clear"></div>
        <? if (!empty($arResult["PREVIEW_TEXT"])): ?>
        <div class="page list" id="description">
        <? if ($arResult["PREVIEW_TEXT_TYPE"] == "text"): ?>
                <p><?= $arResult["~PREVIEW_TEXT"] ?></p>
    <? else: ?>
                    <?= $arResult["PREVIEW_TEXT"] ?>
                <? endif; ?>
        </div>
            <? endif; ?>
    <pre>
    <?
    //print_r($arResult); 
//  if(!empty($arResult["PREVIEW_TEXT"]))print "|".trim($arResult["PREVIEW_TEXT"])."|";
    ?>
        <? //if($arResult["PREVIEW_TEXT"] == "") {echo 1;} elseif (empty($arResult["PREVIEW_TEXT"])) {echo 2;} else {echo $arResult["PREVIEW_TEXT"];}  ?>
    </pre>

<? if (!empty($arResult["PROPERTIES"]['SERTIFICATE'])): ?>
        <div class="sertificates no-list inline clearfix">
            <ul>
    <? foreach ($arResult["PROPERTIES"]['SERTIFICATE']['VALUE'] as $key => $sertificate): ?>
                    <li>Скачать <?= $arResult["PROPERTIES"]['SERTIFICATE']['DESCRIPTION'][$key] ?>: <a class="pdf_ico" href="<? echo CFile::GetPath($sertificate); ?>">&nbsp;</a></li>
                    <? endforeach; ?>
            </ul>
        </div>
                    <? endif; ?>

                    <? if (true): ?>
        <div class="item_props">    
    <? if (!empty($arResult["PROPERTIES"]["preimushestva"]["VALUE"])): ?> 
                <!-- BENEFITS -->  
                <div class="page" id="benefits">


                    <h3>Преимущества</h3>
                    <ul class="items no-list">
                        <? foreach ($arResult["PROPERTIES"]["preimushestva"]["VALUE"] as $key => $benefit): ?>
                            <li>
                                <h4><?= $benefit['desc']; ?></h4>
                    <? foreach ($benefit['items'] as $benefit_val): ?>
                                    <span class="sub_title"><?= $benefit_val['title']; ?></span>
                <? if (!empty($benefit_val['text'])): ?>
                                        <div class="to_show">
                                            <p><?= $benefit_val['text']; ?></p>
                                        </div>
                                        <div class="hided show_benefit">Подробнее</div>
                            <? endif; ?>
                        <? endforeach; ?>
                            </li>

                    <? endforeach; ?>

                    </ul>
                </div>
            <? endif; ?>   




                <? if (!empty($arResult["OPTIONS"])): ?>
                <div class="page" id="options">
                    <? if (!empty($arResult["OPTIONS"])): ?>
                        <? if ($arResult["OPTIONS"]["TYPE"] == "text"): ?>
                            <p><?= $arResult["OPTIONS"]["TEXT"] ?></p>
                        <? else: ?>
                            <?= $arResult["OPTIONS"]["TEXT"] ?>
                        <? endif; ?>
                    <? endif; ?>
                </div>
                <? endif; ?>
                <? if (!empty($arResult["GROUPING_CHARS"])): ?>
                <div class="page" id="characteristics">
                    <h3>Характеристики - <?=$arResult["NAME"]?></h3>
                    <? if ($arParams["TYPE"] != "ELEMENT"): ?>
                        <div class="compare header right"></div>
                    <? endif; ?> 					

                    <?
                    $APPLICATION->IncludeComponent(
                            "customwl:propgroup", "template1", array(
                        "IBLOCK_TYPE" => "catalog",
                        "IBLOCK_ID" => "6",
                        "IBLOCK_ELEMENT" => $arResult["ID"],
                        "FREE_PROPS" => "n",
                        "SECTION_CONTROL" => "N",
                        "GROUP_SORT_FIELD" => "sort",
                        "GROUP_SORT_ORDER" => "asc",
                        "PROPERTY_SORT_FIELD" => "sort",
                        "PROPERTY_SORT_ORDER" => "asc"
                            ), false
                    );
                    ?>



                    <div class="clear"></div>
                </div>
                                <? endif; ?>
                                <? if (!empty($arResult["ATTACHMENTS"])): ?>
                <div class="page no-list inline  aside-block" id="attachments">
                    <span class="title title-block">Навесное оборудование</span>
                    <ul>
                                    <? foreach ($arResult["ATTACHMENTS"] as $key => $attachment): ?>
                            <li>
                                <? if (!empty($attachment["URL"])): ?>
                                    <a class="attachment image<? if (($key + 1) % 6 == 0): ?> last<? endif; ?>" title="<?= $attachment["NAME"] ?>" href="<?= $attachment["URL"] ?>" >
                                <? else: ?>
                                        <div class="attachment image<? if (($key + 1) % 6 == 0): ?> last<? endif; ?>">
                            <? endif; ?>
                        <? if (!empty($attachment["PREVIEW_PICTURE"]["src"])): ?>
                                            <img src="<?= $attachment["PREVIEW_PICTURE"]["src"] ?>" width="<?= $attachment["PREVIEW_PICTURE"]["width"] ?>" height="<?= $attachment["PREVIEW_PICTURE"]["height"] ?>" alt="<?= $attachment["NAME"] ?>" title="<?= $attachment["NAME"] ?>" />
                        <? else: ?>
                                            <img src="<?= getImageNoPhoto(100, 100) ?>" alt="<?= $attachment["NAME"] ?>" title="<?= $attachment["NAME"] ?>" />
            <? endif; ?>
                                        <span><?= $attachment["NAME"] ?></span>
                    <? if (!empty($attachment["URL"])): ?>
                                    </a>
                    <? else: ?>
                                    </div>
                        <? endif; ?>
                            </li>
        <? endforeach; ?>
                    </ul>
                                <? if (count($arResult["ATTACHMENTS"]) > 1): ?>
                        <button class="attachments-button prev disabled"></button><button class="attachments-button next"></button>
                                <? endif ?>
                    <div class="clear"></div>

                </div>
    <? endif; ?>
    <? if (!empty($arResult["VIDEO"])): ?>
                <div class="page clearfix" id="videos">
                    <h3>Видео</h3>
                    <? foreach ($arResult["VIDEO"] as $key => $arVideo): ?>
                        <div class="item-video <? if (($key + 1) % 3 == 0): ?> last <? endif; ?><? if (($key) % 3 == 0): ?> first <? endif; ?>">
                            <div class="video">
                                <a class="video_show image" href="#" id="video_<?= $arVideo["ID"] ?>" title="Посмотреть видео">
                    <? if (!empty($arVideo["PREVIEW_PICTURE"]["src"])): ?>
                                        <img src="<?= $arVideo["PREVIEW_PICTURE"]["src"] ?>" width="<?= $arVideo["PREVIEW_PICTURE"]["width"] ?>" height="<?= $arVideo["PREVIEW_PICTURE"]["height"] ?>" alt="<?= $arVideo["NAME"] ?>" title="<?= $arVideo["NAME"] ?>" />
            <? endif; ?>
                                    <span class="frame"></span>
                                    <span class="icon"></span>
                                    <span class="video-overlay"></span>
                                </a>
                            </div>
                            <div class="name"><a class="video_show" href="#" id="video_<?= $arVideo["ID"] ?>" title="Посмотреть видео"><?= $arVideo["NAME"] ?></a></div>
                        </div>
        <? endforeach; ?>
                </div>
    <? endif; ?>
            <div class="clear"></div>
                        <? if (!empty($arResult['SCHEMES'])): ?>
                <div class="schemes page no-list inline clearfix">
                    <h3>Схемы</h3>
                    <div class="jcarousel small_photos" id="carousel-detail-scheme">
                        <ul>
                <? foreach ($arResult["SCHEMES"] as $key => $arScheme): ?>
                                <li>
                                    <a rel="gal2" href="<?= $arScheme["NATURE"] ?>" width-pic="<?= $arScheme["STANDART"]["width"] ?>" height-pic="<?= $arScheme["STANDART"]["height"] ?>" class="image <? if ($key == 0): ?>active<? endif; ?> fancybox-thumb">

                                        <img big-pic="<?= $arScheme["NATURE"] ?>" src="<?= $arScheme["STANDART"]["src"] ?>" width="<?= $arScheme["STANDART"]["width"] ?>" height="<?= $arScheme["STANDART"]["height"] ?>" />

                                    </a>
                                </li>
            <? endforeach; ?>

                        </ul>
                    </div>
                </div>
        <? endif; ?>
        </div> 
    <? endif; ?>
    <div class="clear"></div>	
    <div class="looked interested-propducts"></div>
    <? if (COption::GetOptionInt("ust", "catalog_detail_spare_parts") > 0 && strlen(COption::GetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT")) > 0): ?>
        <div class="text-banner"><?= COption::GetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT"); ?></div>
    <? endif; ?>
    <? /* if(COption::GetOptionInt("ust", "catalog_detail_you_interested") == 1 && !empty($arResult["INTERESTED_PRODUCTS"])):?>
      <div class="interested-propducts">
      <span class="title">Вам могут быть интересны:</span>
      <?if(count($arResult["INTERESTED_PRODUCTS"]) > 6):?>
      <button class="interested-propducts-button prev"></button>
      <?endif;?>
      <div id="interested-propducts">
      <ul>
      <?foreach($arResult["INTERESTED_PRODUCTS"] as $product):?>
      <li class="image">
      <?if(!empty($product["URL"])):?>
      <a href="<?=$product["URL"]?>" title="<?=$product["NAME"]?>">
      <?endif;?>
      <table><tr><td>
      <?if(!empty($product["PREVIEW_PICTURE"]["src"])):?>
      <img src="<?=$product["PREVIEW_PICTURE"]["src"]?>" width="<?=$product["PREVIEW_PICTURE"]["width"]?>" height="<?=$product["PREVIEW_PICTURE"]["height"]?>" alt="<?=$product["NAME"]?>" title="<?=$product["NAME"]?>">
      <?else:?>
      <img src="<?=getImageNoPhoto(120, 90)?>" alt="<?=$product["NAME"]?>" title="<?=$product["NAME"]?>">
      <?endif;?>
      </td></tr></table>
      <span class="name"><?=$product["NAME"]?></span>
      <?if(!empty($product["PRICE"])):?>
      <span class="price"><?=$product["PRICE"]?></span>
      <?endif;?>
      <?if(!empty($product["URL"])):?>
      </a>
      <?endif;?>
      </li>
      <?endforeach;?>
      </ul>
      </div>
      <?if(count($arResult["INTERESTED_PRODUCTS"]) > 6):?>
      <button class="interested-propducts-button next"></button>
      <?endif?>
      <div class="clear"></div>
      </div>
      <?endif; */ ?>
    <?
    if (!empty($arResult["PROPERTIES"]['add_photo']['VALUE']) ||
            !empty($arResult["PROPERTIES"]['add_name']['VALUE']) ||
            !empty($arResult["PROPERTIES"]['add_descr']['VALUE'])):
        ?>
        <div class="add_block">	
            <div class="add_photo"><img src=<?= CFile::GetPath($arResult["PROPERTIES"]['add_photo']['VALUE']); ?> alt="<?= $arResult["PROPERTIES"]['add_name']['VALUE']; ?>" /></div>
            <div class="add_name"><span><?= $arResult["PROPERTIES"]['add_name']['VALUE']; ?></span></div>
            <div class="add_descr"><?= $arResult["PROPERTIES"]['add_descr']['~VALUE']['TEXT']; ?></div>

        </div>
<? endif; ?> 

<?
// Проверка есть ли еще бренды в этой секции
$proh = 0;
$is_two_brands = true;
$arSelect = Array("ID");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
$arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "SECTION_ID"=>$arResult["IBLOCK_SECTION_ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){ 
 $arFields = $ob->GetFields();  
//print_r($arFields);
 //$arProps = $ob->GetProperties();
	$db_props = CIBlockElement::GetProperty($arResult["IBLOCK_ID"], $arFields["ID"], array("sort" => "asc"), Array("ID"=>212));
	if($ar_props = $db_props->Fetch()) {
	$cur_val = $ar_props["VALUE"];
	}
	$resz = CIBlockElement::GetByID($cur_val);
	if($ar_res = $resz->GetNext()) {
		if ($proh == 0) {
		  $name = $ar_res['NAME'];
		  $proh++;
		  }
		else if ($name != $ar_res['NAME']) {
			$is_two_brands = false;
			break;
		}

	}

}
// получение названия бренда
		$db_props = CIBlockElement::GetProperty($arResult["IBLOCK_ID"], $arResult["ID"], array("sort" => "asc"), Array("ID"=>212));
		if($ar_props = $db_props->Fetch())
			$brand_id = $ar_props["VALUE"];
		$resx = CIBlockElement::GetByID($brand_id);
		if($ar_res = $resx->GetNext()) {
			$brand_name = $ar_res['NAME'];
			$brand_code = $ar_res['CODE'];
			}
			$trans_brand_name = ToLower(Cutil::translit($brand_name, "ru", $arPrms));// Транслитерация названия бренда


// проверка стоит ли галочка "Показывать в ссылке"			
			$db_props2 = CIBlockElement::GetProperty(9, $brand_id, array("sort" => "asc"), Array("ID" => 2253));
			if($ar_props2 = $db_props2->Fetch())
				$show_in_href = $ar_props2["VALUE_ENUM"];
			//file_put_contents($file, print_r($show_in_href, true), FILE_APPEND);
		


?>
<div class="useful_hrefs">Смотрите также: 
		<a href="<?=$arResult["SECTION"]["SECTION_PAGE_URL"]?>">все <?=strtolower($arResult["SECTION"]["NAME"])?></a>,
	<?if (!$is_two_brands):?>
		<a href="<?echo $arResult["SECTION"]["SECTION_PAGE_URL"].'filter/brand-'.$trans_brand_name;?>"><?echo strtolower($arResult["SECTION"]["NAME"]).' '.$brand_name;?></a>,
	<?endif;?>
	<?if ($show_in_href == "Y"):?>
		<?
		$parent_id = getParent($arResult["IBLOCK_SECTION_ID"]);
		$resq = CIBlockSection::GetByID($parent_id);
		if($ar_resq = $resq->GetNext())
			$fisrt_level_href = $ar_resq['SECTION_PAGE_URL'];
		//file_put_contents($file, print_r($fisrt_level_href, true));
		?>
		<a href="<?echo $fisrt_level_href.'filter/brand-'.$trans_brand_name;?>">вся техника <?=$brand_name?></a>,
	<?endif;?>
		<a href="<?echo '/partnery/'.$brand_code.'/'?>">информация о бренде <?=$brand_name?></a>
</div>

</div>
<div class="dialog" id="video_player"></div>