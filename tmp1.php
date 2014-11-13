<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	$file = $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/.default/components/bitrix/catalog/new_design/areal/catalog.element/.default/test.txt";
	//file_put_contents($file, print_r($arResult, true));
	function nth_strpos($str, $substr, $n, $stri = false)
{
    if ($stri) {
        $str = strtolower($str);
        $substr = strtolower($substr);
    }
    $ct = 0;
    $pos = 0;
    while (($pos = strpos($str, $substr, $pos)) !== false) {
        if (++$ct == $n) {
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
            $arResult["IBLOCK_ID"],
            $arResult["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );
        $arResult["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
$this->AddEditAction($arResult['ID'], $arResult['EDIT_LINK'], $strElementEdit);
?>


<? $naves = FALSE; if($arResult['SECTION']['PATH'][0]['ID']=='243') {$naves = TRUE;} ?>
<div class="catalog-detail catalog <?if($naves){echo 'naves';}?> hproduct vert_design"  id="<? echo $this->GetEditAreaId($arResult['ID']); ?>">
<span style="display:none" class="fn"><? $APPLICATION->ShowTitle() ?></span>
<div class="on_top">
<? if($CATALOG_question == 1):?>
	<div><button class="question silver left">Задать вопрос</button></div>
<?endif;?>
<? if($arParams["TYPE"] == "ELEMENT" && count($arResult["ITEMS"])):?>
	<div class="comparison visible"></div>
<?endif;?>
	<div class="clear"></div>
</div>
<? $i=0;
foreach ($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"] as $sections) {
	foreach ($sections as $items) {
		$item[$i] = $items;	
		$i++;
	}
}

?>

<? global $USER;
if ($USER->IsAdmin()) : ?>
<pre>
<? // print_r($arResult);?>
</pre>
<? endif;?>
<? //print_r($item);["NATURE"] ?>
	<div class="ppic detail">
		<div class="image-main image">
			<?if(count($arResult["PHOTOS"])>0):?>
				
			<?endif;?>
					
						<?if(!empty($arResult["PHOTOS"][0]["STANDART"]["src"])):?>
							<img class="photo" src="<?=$arResult["PHOTOS"][0]["NATURE"]?>"  alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
						<?else:?>
							<img class="photo" src="<?=getImageNoPhoto(338, 278)?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
						<?endif;?>
					
			<?if(count($arResult["PHOTOS"])>0):?>
					<span class="full"></span>
					<?if(!empty($arResult["PROPERTIES"]["ACTIONS"]["VALUE"])):?>
						<span class="action popup"><?=$arResult["PROPERTIES"]["ACTIONS"]["NAME"]?></span>
					<?endif;?>
					<?if(!empty($arResult["PROPERTIES"]["NEW"]["VALUE"])):?>
						<span class="new popup"><?=$arResult["PROPERTIES"]["NEW"]["NAME"]?></span>
					<?endif;?>
					<?if(!empty($arResult["PROPERTIES"]["SEASONAL"]["VALUE"])):?>
						<span class="season popup"><?=$arResult["PROPERTIES"]["SEASONAL"]["NAME"]?></span>
					<?endif;?>	
					<?if(!empty($arResult["PROPERTIES"]["SALE"]["VALUE"])):?>
						<span class="sale popup"><?=$arResult["PROPERTIES"]["SALE"]["NAME"]?></span>
					<?endif;?>	
					<?if(!empty($arResult["PROPERTIES"]["CREDIT"]["VALUE"])):?>
						<span class="credit popup"><?=$arResult["PROPERTIES"]["CREDIT"]["NAME"]?></span>
					<?endif;?>		
				
			<?endif;?>
		</div>
		
		<div class="cl"></div>
	</div>
	<div class="properties">
    	<h2 class="title">Основные данные</h2>
		<div class="characteristics<?if($arParams["TYPE"] == "ELEMENT" && !empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])):?> with-price<?endif;?>">
			<div class="key_prop list">
			<? if(strpos($arResult["PROPERTIES"]['KEY_PROP']['~VALUE']['TEXT'],'<ul>')==false):?>
            	<ul>
				<?=$arResult["PROPERTIES"]['KEY_PROP']['~VALUE']['TEXT'];?>
                </ul>
            <? else:?>
            	<?=$arResult["PROPERTIES"]['KEY_PROP']['~VALUE']['TEXT'];?>
			<? endif;?></div>
			<div class="chars">
                                <? //print $arParams["TYPE"]; //SERIES
                                ?>
				<?if(count($arResult["ITEMS"]) > 1 or $arParams["TYPE"]=="SERIES"):?>
					<?if(isset($arResult["CHARACTERISTICS"]["TEXT"]) && isset($arResult["CHARACTERISTICS"]["TYPE"])):?>
						<?if($arResult["CHARACTERISTICS"]["TYPE"] == "text"):?>
							<p><?=$arResult["CHARACTERISTICS"]["TEXT"]?></p>
						<?else:?>
							<?=$arResult["CHARACTERISTICS"]["TEXT"]?>
						<?endif;?>
					<?else:?>
						<p>К сожалению, общие характеристики не указаны.</p>
					<?endif;?>
				<?else:?>
					<?$char = 0; $i=0?>
                    <table>
					<?foreach($arResult["ITEMS"][0]["PROPERTIES"] as $val):?>
						<?if(!empty($val["VALUE"])):?>
							<?$char = 1;?>
							<tr class="identifier">
								<td class="name type"><?=$val["NAME"]?></td>
								<td class="value"><?//echo $val["VALUE"];
								 if (str_replace(" ","",$val["NAME"]) == "Типпривода" || 
									 str_replace(" ","",$val["NAME"]) == "Типмашины" || 
									 str_replace(" ","",$val["NAME"]) == "Исполнение") {
								 $cur_dir = $APPLICATION->GetCurDir();
								 
									$arPrms = array("replace_space"=>"_","replace_other"=>"_");
									$trans = ToLower(Cutil::translit($val["VALUE"],"ru",$arPrms));
									
									
									$nth = nth_strpos($cur_dir, "/", 3, true);
									$to_cut = substr( $cur_dir, 0, $nth);
									
									echo '<a class="no-mar" href="'.$to_cut.'/filter/pto_tip_privoda-'.$trans.'">'.$val["VALUE"].'</a>';
									//$pos = strpos($cur_dir, $to_cut);
									//$cutted = substr($APPLICATION->GetCurDir(), $to_cut);
									//file_put_contents($file, print_r($val["VALUE"], true), FILE_APPEND);
									$res = CIBlockElement::GetProperty($arResult["IBLOCK_ID"], $arResult["IBLOCK_SECTION_ID"], "asc", array("ID"=>"261"));
									while ($ob = $res->GetNext())
									{
										$VALUE = $ob['NAME'];
										file_put_contents($file, print_r($VALUE, true), FILE_APPEND);
									}
									/* $VALUES = array();
									$db_props = CIBlockElement::GetProperty($arResult["ID"], $arResult["IBLOCK_SECTION_ID"], array("sort" => "asc"), Array());
									while ($ob = $res->GetNext())
									{
										$VALUES[] = $ob['VALUE'];
									}
									file_put_contents($file, print_r($VALUES, true), FILE_APPEND); */
									/* $res = CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"]);
									while ($obRes = $res->GetNextElement())
									{
									  $ar_res = $obRes->GetProperty();
									  file_put_contents($file, print_r($obRes, true), FILE_APPEND);
									} */
								} 
								else echo $val["VALUE"];
								/* $res = CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"]);
								 while ($obRes = $res->GetNextElement())
								{
								  $ar_res = $obRes->GetProperty();
								  file_put_contents($file, print_r($ar_res, true), FILE_APPEND);
								}  */
								?>
								</td>
								
							</tr>
                            <?$i++;?>
                            <?if($i>=6) break;?>
						<?endif;?>
                        
					<?endforeach;?>
                    </table>
					<?if($char == 0):?>
						<p>К сожалению, общие характеристики не указаны</p>
					<?endif;?>
				<?endif;?>
			</div>
			<div class="cost">
				<?if($arParams["TYPE"] == "ELEMENT" && !empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])):?>
					<?if($arResult["PROPERTIES"]["PRICE"]["VALUE"] > 0):?>
						<span class="value"><?=CurrencyFormat($arResult["PROPERTIES"]["PRICE"]["VALUE"], "RUB");?></span>
						<div class="clear"></div>
					<?endif;?>
				<?endif;?>
			</div>
		</div>
		<input type="hidden" name="iblock_id" value="<?=$arResult["ITEMS"][0]["IBLOCK_ID"]?>" />
		<?if($arParams["TYPE"] == "ELEMENT"):?>
			<input type="hidden" name="type_element" value="model" />
		<?else:?>
			<input type="hidden" name="type_element" value="seriya" />
		<?endif;?>
		<?foreach($arResult["ITEMS"] as $val):?>
			<input type="hidden" class="element" name="element_<?=$val["ID"]?>" value="<?=$val["NAME"]?>">
		<?endforeach;?>
		
		
		<?if($CATALOG_order == 1 || $CATALOG_question == 1):?>
			<div class="form-button clearfix">
				
				
				<?if($CATALOG_order == 1):?>
                                        <?if($arResult["PROPERTIES"]["PRICE"]["VALUE"]!=""):?>
					<button class="order left">Узнать стоимость</button>
                                        <!--?else:?>
                                        <-?=$arResult["PROPERTIES"]["PRICE"]["VALUE"];?>
                                        <-?endif;?-->
				<?endif;?>
			</div>
		<?endif;?>
		<div class="action">
			<?if($CATALOG_kredit == 1):?>
				<button class="left credit">Купить в кредит</button>
			<?endif;?>
			<?if($CATALOG_where_buy == false):?>
				<a class="right where_buy" href="/filialy/" title="Филиалы">Где купить</a>
			<?endif;?>
			<div class="clear"></div>
			
			<?if($CATALOG_used == 1):?>
				<button class="left used">Купить Б/У</button>
			<?endif;?>
			<?if($CATALOG_service_centers == false):?>
				<a class="right service_centers" href="#">Сервисные центры</a>
			<?endif;?>
			<div class="clear"></div>
			
			<?if($CATALOG_arenda == 1):?>
				<button class="left arenda">Взять в аренду</button>
			<?endif;?>
			
			<div class="clear"></div>
		</div>
        <a href="#characteristics" class="to_chars inner_link">Посмотреть все технические характеристики</a>
	</div>
	<? if(count($arResult["PHOTOS"])>1):?>
        <h4>Техника в работе:</h4>
			<div class="jcarousel small_photos" id="carousel-detail">
				<ul>
					<?foreach($arResult["PHOTOS"] as $key => $arPhoto):?>
                    <?if($key>0):?>
						<li>
							<a rel="gal1" href="<?=$arPhoto["NATURE"]?>" width-pic="<?=$arPhoto["RESIZE"]["width"]?>" height-pic="<?=$arPhoto["RESIZE"]["height"]?>" class="image <?if($key==0):?>active<?endif;?> fancybox-thumb">
								
									<img big-pic="<?=$arPhoto["NATURE"]?>" src="<?=$arPhoto["RESIZE"]["src"]?>" width="<?=$arPhoto["RESIZE"]["width"]?>" height="<?=$arPhoto["RESIZE"]["height"]?>" />
								
							</a>
						</li>
                        <?endif;?>
					<?endforeach;?>
					<?if(count($arResult["PHOTOS"]) < 3):?>
						<?for($i = 0; $i < (3-count($arResult["PHOTOS"])); $i++) {?>
							<li class="image">
									<img src="<?=getImageNoPhoto(125, 90)?>" />
							</li>
						<?}?>
					<?endif;?>
				</ul>
				<?if(count($arResult["PHOTOS"]) > 13):?>
					<button class="jcarousel-prev"></button>
					<button class="jcarousel-next"></button>
				<?endif;?>
			</div>
            <?if(!empty($arResult["VIDEO"])):?>
            <a href="#videos" class="with_arrow inner_link">Посмотреть видео</a>
            <?endif;?>
	<?endif;?>
	<?if(!empty($arResult["RELATED_PRODUCTS"]) && !$naves):?>
		<div class="related-propducts no-list clearfix aside-block">
        
			<span class="title<?if(count($arResult["RELATED_PRODUCTS"]) <= 2):?> marginBot<?endif;?> title-block">Сопутствующие товары:</span>
			
			<div id="related-propducts">
				<ul>
				<?foreach($arResult["RELATED_PRODUCTS"] as $product):?>
					<li class="image">
						<?if(!empty($product["URL"])):?>
							<a href="<?=$product["URL"]?>" title="<?=$product["NAME"]?>"> 
						<?endif;?>
								
									<?if(!empty($product["PREVIEW_PICTURE"]["src"])):?>
										<img src="<?=$product["PREVIEW_PICTURE"]["src"]?>" width="<?=$product["PREVIEW_PICTURE"]["width"]?>" height="<?=$product["PREVIEW_PICTURE"]["height"]?>" alt="<?=$product["NAME"]?>" title="<?=$product["NAME"]?>">
									<?else:?>
										<img src="<?=getImageNoPhoto(120, 90)?>" alt="<?=$product["NAME"]?>" title="<?=$product["NAME"]?>">
									<?endif;?>
								
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
			<?if(count($arResult["RELATED_PRODUCTS"]) > 2):?>
				<button class="related-propducts-button prev disabled"></button><button class="related-propducts-button next"></button>
			<?endif?>
		</div>
	<?endif?>
	
	<div class="clear"></div>
    <?if(!empty($arResult["PREVIEW_TEXT"])):?>
			<div class="page list" id="description">
					<?if($arResult["PREVIEW_TEXT_TYPE"] == "text"):?>
						<p><?=$arResult["~PREVIEW_TEXT"]?></p>
					<?else:?>
						<?=$arResult["PREVIEW_TEXT"]?>
					<?endif;?>
			</div>
	<?endif;?>
    <pre>
    <? //print_r($arResult); 
//  if(!empty($arResult["PREVIEW_TEXT"]))print "|".trim($arResult["PREVIEW_TEXT"])."|";
    
        
    
    ?>
    <? //if($arResult["PREVIEW_TEXT"] == "") {echo 1;} elseif (empty($arResult["PREVIEW_TEXT"])) {echo 2;} else {echo $arResult["PREVIEW_TEXT"];} ?>
    </pre>
    
    <? if(!empty($arResult["PROPERTIES"]['SERTIFICATE'])):?>
    	<div class="sertificates no-list inline clearfix">
        	<ul>
        	<? foreach($arResult["PROPERTIES"]['SERTIFICATE']['VALUE'] as $key=>$sertificate): ?>
            	<li>Скачать <?=$arResult["PROPERTIES"]['SERTIFICATE']['DESCRIPTION'][$key] ?>: <a class="pdf_ico" href="<? echo CFile::GetPath($sertificate); ?>">&nbsp;</a></li>
        	<? endforeach;?>
            </ul>
        </div>
    <? endif;?>
    
    <?if(true):?>
	<div class="item_props">    
    <?if(!empty($arResult["PROPERTIES"]["preimushestva"]["VALUE"])):?> 
    	<!-- BENEFITS -->  
        <div class="page" id="benefits">
        
        
        	<h3>Преимущества</h3>
            <ul class="items no-list">
            <? foreach($arResult["PROPERTIES"]["preimushestva"]["VALUE"] as $key => $benefit): ?>
            	<li>
                	<h4><?=$benefit['desc'];?></h4>
                    <? foreach ($benefit['items'] as $benefit_val): ?>
                    	<span class="sub_title"><?=$benefit_val['title'];?></span>
<? if(!empty($benefit_val['text'])):?>
                        <div class="to_show">
                        	<p><?=$benefit_val['text'];?></p>
                        </div>
                         <div class="hided show_benefit">Подробнее</div>
<? endif;?>
                    <? endforeach;?>
                </li>
            
            <? endforeach;?>
            
            </ul>
        </div>
    <?endif;?>   
    
     
	
		
		<?if(!empty($arResult["OPTIONS"])):?>
			<div class="page" id="options">
				<?if(!empty($arResult["OPTIONS"])):?>
					<?if($arResult["OPTIONS"]["TYPE"] == "text"):?>
						<p><?=$arResult["OPTIONS"]["TEXT"]?></p>
					<?else:?>
						<?=$arResult["OPTIONS"]["TEXT"]?>
					<?endif;?>
				<?endif;?>
			</div>
		<?endif;?>
        <?if(!empty($arResult["GROUPING_CHARS"])):?>
		<div class="page" id="characteristics">
        <h3>Характеристики</h3>
<? if($arParams["TYPE"] != "ELEMENT"):?>
                                         <div class="compare header right"></div>
                     <? endif;?> 					
                     
						<? $APPLICATION->IncludeComponent(
	"customwl:propgroup", 
	"template1", 
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "6",
		"IBLOCK_ELEMENT" => $arResult["ID"],
		"FREE_PROPS" => "n",
		"SECTION_CONTROL" => "N",
		"GROUP_SORT_FIELD" => "sort",
		"GROUP_SORT_ORDER" => "asc",
		"PROPERTY_SORT_FIELD" => "sort",
		"PROPERTY_SORT_ORDER" => "asc"
	),
	false
);?>
		
		
	
			<div class="clear"></div>
		</div>
        <? endif;?>
		<?if(!empty($arResult["ATTACHMENTS"])):?>
			<div class="page no-list inline  aside-block" id="attachments">
            <span class="title title-block">Навесное оборудование</span>
            <ul>
				<?foreach($arResult["ATTACHMENTS"] as $key => $attachment):?>
                	<li>
					<?if(!empty($attachment["URL"])):?>
						<a class="attachment image<?if(($key+1)%6 == 0):?> last<?endif;?>" title="<?=$attachment["NAME"]?>" href="<?=$attachment["URL"]?>" >
					<?else:?>
						<div class="attachment image<?if(($key+1)%6 == 0):?> last<?endif;?>">
					<?endif;?>
							<?if(!empty($attachment["PREVIEW_PICTURE"]["src"])):?>
								<img src="<?=$attachment["PREVIEW_PICTURE"]["src"]?>" width="<?=$attachment["PREVIEW_PICTURE"]["width"]?>" height="<?=$attachment["PREVIEW_PICTURE"]["height"]?>" alt="<?=$attachment["NAME"]?>" title="<?=$attachment["NAME"]?>" />
							<?else:?>
								<img src="<?=getImageNoPhoto(100, 100)?>" alt="<?=$attachment["NAME"]?>" title="<?=$attachment["NAME"]?>" />
							<?endif;?>
							<span><?=$attachment["NAME"]?></span>
					<?if(!empty($attachment["URL"])):?>
						</a>
					<?else:?>
						</div>
					<?endif;?>
                    </li>
				<?endforeach;?>
                </ul>
                <?if(count($arResult["ATTACHMENTS"]) > 1):?>
					<button class="attachments-button prev disabled"></button><button class="attachments-button next"></button>
				<?endif?>
				<div class="clear"></div>
                
			</div>
		<?endif;?>
		<?if(!empty($arResult["VIDEO"])):?>
			<div class="page clearfix" id="videos">
            <h3>Видео</h3>
				<?foreach($arResult["VIDEO"] as $key => $arVideo):?>
					<div class="item-video <?if(($key+1)%3 == 0):?> last <?endif;?><?if(($key)%3 == 0):?> first <?endif;?>">
						<div class="video">
							<a class="video_show image" href="#" id="video_<?=$arVideo["ID"]?>" title="Посмотреть видео">
								<?if(!empty($arVideo["PREVIEW_PICTURE"]["src"])):?>
									<img src="<?=$arVideo["PREVIEW_PICTURE"]["src"]?>" width="<?=$arVideo["PREVIEW_PICTURE"]["width"]?>" height="<?=$arVideo["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arVideo["NAME"]?>" title="<?=$arVideo["NAME"]?>" />
								<?endif;?>
								<span class="frame"></span>
								<span class="icon"></span>
								<span class="video-overlay"></span>
							</a>
						</div>
						<div class="name"><a class="video_show" href="#" id="video_<?=$arVideo["ID"]?>" title="Посмотреть видео"><?=$arVideo["NAME"]?></a></div>
					</div>
				<?endforeach;?>
			</div>
		<?endif;?>
		<div class="clear"></div>
        <? if(!empty($arResult['SCHEMES'])):?>
        <div class="schemes page no-list inline clearfix">
            <h3>Схемы</h3>
            <div class="jcarousel small_photos" id="carousel-detail-scheme">
				<ul>
					<?foreach($arResult["SCHEMES"] as $key => $arScheme):?>
						<li>
							<a rel="gal2" href="<?=$arScheme["NATURE"]?>" width-pic="<?=$arScheme["STANDART"]["width"]?>" height-pic="<?=$arScheme["STANDART"]["height"]?>" class="image <?if($key==0):?>active<?endif;?> fancybox-thumb">
								
									<img big-pic="<?=$arScheme["NATURE"]?>" src="<?=$arScheme["STANDART"]["src"]?>" width="<?=$arScheme["STANDART"]["width"]?>" height="<?=$arScheme["STANDART"]["height"]?>" />
								
							</a>
						</li>
					<?endforeach;?>
					
				</ul>
			</div>
        </div>
		<? endif;?>
	</div> 
    <?endif;?>
	<div class="clear"></div>	
	<div class="looked interested-propducts"></div>
	<?if(COption::GetOptionInt("ust", "catalog_detail_spare_parts") > 0 && strlen(COption::GetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT")) > 0):?>
		<div class="text-banner"><?=COption::GetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT");?></div>
	<?endif;?>
	<? /*if(COption::GetOptionInt("ust", "catalog_detail_you_interested") == 1 && !empty($arResult["INTERESTED_PRODUCTS"])):?>
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
	<?endif; */?>
    <? if (!empty($arResult["PROPERTIES"]['add_photo']['VALUE']) || 
	!empty($arResult["PROPERTIES"]['add_name']['VALUE']) || 
	!empty($arResult["PROPERTIES"]['add_descr']['VALUE'])): ?>
    	<div class="add_block">	
   			<div class="add_photo"><img src=<?=CFile::GetPath($arResult["PROPERTIES"]['add_photo']['VALUE']);?> alt="<?=$arResult["PROPERTIES"]['add_name']['VALUE'];?>" /></div>
            <div class="add_name"><span><?=$arResult["PROPERTIES"]['add_name']['VALUE'];?></span></div>
            <div class="add_descr"><?=$arResult["PROPERTIES"]['add_descr']['~VALUE']['TEXT'];?></div>
            
    	</div>
    <? endif;?> 
    
    <? 
    //echo "<!--";
    //print_r($arResult["PROPERTIES"]);
    //echo "-->";
    
    ?>
</div>
<div class="dialog" id="video_player"></div>