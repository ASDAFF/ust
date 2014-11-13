<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
 
<?
//фиксированный набор полей в том же порядке что в дизайне. индекс сортировки употребил для фильтра
$arProps = array(
	"YEAR" => "Год выпуска",
	"KPP" => "КПП",
	"WORKED" => "Наработка, мч",
	"HEIGHT" => "Высота подъема, м",
	"MAX_WEIGHT" => "Грузопод- ъемность, кг",
	"CITY" => "Город"
	);
?>
<?if(count($arResult["ITEMS"]) > 0):?>
	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
	<div class="clear"></div>
	<div class="catalog-list-view catalog">
		<div class="catalog-table">
			<div class="table-body table-items">
				<div class="row head-row">
					<div class="cell photo"><div class="cell-inr">Фото</div></div>
					<div class="cell"><div class="cell-inr">Название</div></div>
					<div class="cell"><div class="cell-inr">Бренд</div></div>
					<?$price_flag = 0;
					foreach($arResult["ITEMS"] as $key => $arItem):
						if(!empty($arItem["PROPERTIES"]["PRICE"]["VALUE"])):
							$price_flag = 1;
						endif;
					endforeach;?>

					

					<?foreach($arResult["ITEMS"] as $key => $arItem):
						foreach($arItem["PROPERTIES"] as $prop_key => $arProp):
							if(!in_array($prop_key, $arParams["PERMANENT_PROPERTY"]) && !empty($arProp["VALUE"])):
								$arProperties[$prop_key] = $arProp["NAME"];									
							endif;
						endforeach;
					endforeach;
					?>

					<?foreach($arProps as $pro_key => $pro):?>	
						<div class="cell"><div class="cell-inr"><?=$pro?></div></div>
					<?endforeach;?>
					<?if($price_flag == 1):?><div class="cell"><div class="cell-inr">Цена</div></div><?endif;?>
				</div>
				<?foreach($arResult["ITEMS"] as $key => $arItem):?>
					<div class="row<?if(($key+1)%2 == 0):?> even<?endif;?>" id="item_<?=$arItem["ID"]?>">
						<div class="cell photo">
							<div class="cell-inr">
								<?if(!empty($arItem["PROPERTIES"]["ACTIONS"]["VALUE"])):?>
									<div class="tick action"><?=$arItem["PROPERTIES"]["ACTIONS"]["NAME"]?></div>
								<?endif;?>
								<?if(!empty($arItem["PROPERTIES"]["NEW"]["VALUE"])):?>
									<div class="tick new"><?=$arItem["PROPERTIES"]["NEW"]["NAME"]?></div>
								<?endif;?>
								<?if(!empty($arItem["PROPERTIES"]["SEASONAL"]["VALUE"])):?>
									<div class="tick season"><?=$arItem["PROPERTIES"]["SEASONAL"]["NAME"]?></div>
								<?endif;?>	
								<?if(!empty($arItem["PROPERTIES"]["SALE"]["VALUE"])):?>
									<div class="tick sale"><?=$arItem["PROPERTIES"]["SALE"]["NAME"]?></div>
								<?endif;?>	
								<?if(!empty($arItem["PROPERTIES"]["CREDIT"]["VALUE"])):?>
									<div class="tick credit"><?=$arItem["PROPERTIES"]["CREDIT"]["NAME"]?></div>
								<?endif;?>	
								<?if($arItem["PROPERTIES"]["ENABLE_DETAIL_PAGE"]["VALUE"] == "N"):?>
									<a class="title no-link"><table><tr><td>
										<?if(!empty($arItem["PICTURE"]["src"])):?>
											<img src="<?=$arItem["PICTURE"]["src"]?>" width="<?=$arItem["PICTURE"]["width"]?>" height="<?=$arItem["PICTURE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
										<?else:?>
											<img src="<?=getImageNoPhoto(100, 50)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
										<?endif;?>
										</td></tr></table>
									</a>	
								<?else:?>					
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" class="image"><table><tr><td>
										<?if(!empty($arItem["PICTURE"]["src"])):?>
											<img src="<?=$arItem["PICTURE"]["src"]?>" width="<?=$arItem["PICTURE"]["width"]?>" height="<?=$arItem["PICTURE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
										<?else:?>
											<img src="<?=getImageNoPhoto(100, 50)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
										<?endif;?>
										</td></tr></table>
									</a>
								<?endif;?>
								<div class="comparison"></div>
							</div>
						</div>
						<div class="cell">
							<div class="cell-inr">
								<?if($arItem["PROPERTIES"]["ENABLE_DETAIL_PAGE"]["VALUE"] == "N"):?>
									<a class="title no-link" ><?=$arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"] ? $arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"] : $arItem["NAME"]?></a>
								<?else:?>
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="title" title="<?=$arItem["NAME"]?>"><?=$arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"] ? $arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"] : $arItem["NAME"]?></a>
								<?endif;?>
							</div>
						</div>
						<div class="cell">
							<div class="cell-inr"><?=$arItem["PROPERTIES"]["BRAND"]["VALUE"]?></div>
						</div>
						
						<?foreach($arProps as $key_pro => $pro):?>	
							<div class="cell"><div class="cell-inr">
								<?if(!empty($arItem["PROPERTIES"][$key_pro]["VALUE"])):?>
									<?=$arItem["PROPERTIES"][$key_pro]["VALUE"]?>
								<?else:?>
									&mdash;
								<?endif;?>
							</div></div>
						<?endforeach;?>
						<?if($price_flag == 1):?>
							<div class="cell"><div class="cell-inr">
								<?if(!empty($arItem["PROPERTIES"]["PRICE"]["VALUE"])):?>
									<?if(!empty($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])):?>
										<span class="old_price"><?=number_format($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"], 0, ".", " ")?></span>
									<?endif;?>
									<span class="price"><?=number_format($arItem["PROPERTIES"]["PRICE"]["VALUE"], 0, ".", " ")?></span>
								<?endif;?>
							</div></div>
						<?endif;?>
					</div>
				<?endforeach;?>
			</div>
		</div>
	</div>
	<div class="hr"></div>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
	 
<?else:?>
	<div class="clear"></div>
	<p class="errortext">К сожалению, в данном разделе нет доступных товаров.</p>
	<div class="clear"></div>
<?endif;?>