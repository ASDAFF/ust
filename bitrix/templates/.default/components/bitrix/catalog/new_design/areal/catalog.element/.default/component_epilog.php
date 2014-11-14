<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?

  if(CModule::IncludeModule("alexkova.megametatags")){

    $arKeys = array();
	$brand_id = $arResult["PROPERTIES"]["BRAND"]["~VALUE"];
	

	
	$brand_res = CIBlockElement::GetByID($brand_id);
	
		$db_props = CIBlockElement::GetProperty(9, $brand_id, array("sort" => "asc"), Array());
	while ($ar_props = $db_props->Fetch()) {
		if ($ar_props["NAME"] == "Рус. название")
			$brand_rus = $ar_props["VALUE"];
		
				//$file = $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/.default/components/bitrix/catalog/new_design/areal/catalog.element/.default/test.txt";
				//file_put_contents($file, print_r($brand_rus, true));
		}

	
	if($ar_res = $brand_res->GetNext())
		$brand_name = $ar_res['NAME'];
	//  $file = $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/.default/components/bitrix/catalog/new_design/areal/catalog.element/.default/test.txt";
	//file_put_contents($file, print_r($ar_res, true));
	if (strlen($arResult["PREVIEW_TEXT"])>180)
				  {
						$cleared = strip_tags($arResult["PREVIEW_TEXT"]);
						$detail_text = substr($cleared, 0,strpos ($cleared, " ", 180)); // удалили лишнее
						$detail_text = substr($detail_text, 0, strrpos($detail_text, " ")); // удалили лишнее слово
						$detail_text=preg_replace('/\s+/',' ',$detail_text);
				  }  
      $arKeys[] = array("KEY"=>"ELEMENT_DETAIL_TEXT","VALUE"=>$detail_text,"WHERE_SET"=>"");   
      $arKeys[] = array("KEY"=>"ELEMENT_BRAND","VALUE"=>$brand_name,"WHERE_SET"=>"");   
      $arKeys[] = array("KEY"=>"ELEMENT_BRAND_RUS","VALUE"=>$brand_rus,"WHERE_SET"=>"");   
   if ($arKeys){
      CMegaMetaKeys::setKeys($arKeys);      
   } 
}  

?>

<?if(count($arResult["ITEMS"]) == 1 && $arResult["ID"] > 0):?>
	<script type="text/javascript">
		var id = "<?=$arResult["ID"]?>";
		<?if(in_array($arResult["ID"], $_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"][$arItem["IBLOCK_SECTION_ID"]])):?>
			$(".catalog-detail div.comparison").html('<a class="compare del" href="#" id="item_'+id+'" title="Сравнение">Убрать из сравнения</a>');
		<?else:?>
			$(".catalog-detail div.comparison").html('<a class="compare add" href="#" id="item_'+id+'" title="Сравнение">Сравнить</a>');
		<?endif;?>		
	</script>
<?elseif(count($arResult["ITEMS"]) > 1):?>
	<?foreach($arResult["ITEMS"] as $item):?>
		<?$arID[] = $item["ID"];?>
	<?endforeach;?>
	<script type="text/javascript">
		<?foreach($arID as $id):?>
			<?if(in_array($id, $_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"])):?>
				$("label.checkbox input[name='compare_<?=$id?>']").attr("checked", "checked");
			<?endif;?>
		<?endforeach;?>
		<?if(count(array_intersect($_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"], $arID)) > 0):?>
			$(".catalog-detail div.comparison").html('<a class="compare" href="/catalog/compare/" title="Перейти в список сравнения">Перейти в список сравнения</a>');
		<?else:?>
			$(".catalog-detail div.comparison").html('');
		<?endif;?>
	</script>
<?endif;?>
<?
if(!in_array($arResult["ITEMS"][0]["ID"], $_SESSION["LOOKED"]))
	$_SESSION["LOOKED"][] = $arResult["ITEMS"][0]["ID"];
	
if(!empty($_SESSION["LOOKED"]) && COption::GetOptionInt("ust", "catalog_detail_you_looked") == 1):
	$looks = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $_SESSION["LOOKED"]), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "IBLOCK_SECTION_ID", "PROPERTY_PRICE", "DETAIL_PAGE_URL", "PROPERTY_GROUP_PAGE", "PROPERTY_DISPLAY_AS_SERIES"));
	while($look = $looks->GetNext()) {
		if($look["ID"] != $arResult["ITEMS"][0]["ID"]) {
			unset($url);
			if(!empty($look["PROPERTY_GROUP_PAGE_VALUE"]) && !empty($look["PROPERTY_DISPLAY_AS_SERIES_VALUE"])) {	
				unset($sections);
				unset($section);
				$sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $look["IBLOCK_ID"], "ID" => $look["IBLOCK_SECTION_ID"]), false);
				if($section = $sections->GetNext())
					$url = $section["SECTION_PAGE_URL"].$look["PROPERTY_GROUP_PAGE_VALUE"]."/";
			}
			else $url = $look["DETAIL_PAGE_URL"];
			
			$looked_items[] = array(
				"NAME" => $look["NAME"],
				"URL" => $url,
				"PRICE" => ($look["PROPERTY_PRICE_VALUE"] > 0) ? FormatCurrency($look["PROPERTY_PRICE_VALUE"], "RUB") : "",
				"PREVIEW_PICTURE" => CFile::ResizeImageGet(
					$look["PREVIEW_PICTURE"], 
					array("width" => 119, "height" => 100), 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true 
				)
			);
		}
	}?>
	<?if(!empty($looked_items) && count($looked_items) > 0):?>
		<div id="looked_items">
			<span class="title">Вы смотрели:</span>
			<?if(count($looked_items) > 6):?>
				<button class="looked-propducts-button prev"></button>
			<?endif;?>
			<div id="looked-propducts">
				<ul>
				<?foreach($looked_items as $product):?>
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
			<?if(count($looked_items) > 6):?>
				<button class="looked-propducts-button next"></button>
			<?endif?>
			<div class="clear"></div>
		</div>
		<script type="text/javascript">	
			var content = $("#looked_items").html();
			$("#looked_items").remove();
			$(".catalog-detail .looked.interested-propducts").empty().html(content);
			$(".catalog-detail #looked-propducts").jCarouselLite({
				btnNext: ".looked-propducts-button.next",
				btnPrev: ".looked-propducts-button.prev",
				mouseWheel: true,
				visible: 6,
				circular: false
			});
		</script>
	<?endif;?>
<?endif;?>