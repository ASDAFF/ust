<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"]) > 0):?>
	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
	<div class="clear"></div>
		<div class="catalog-plate catalog">
			<input type="hidden" name="iblock_id" value="<?=$arParams["IBLOCK_ID"]?>" />
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<div class="items <?if(($key+1)%2 == 0):?>last<?endif;?>" id="item_<?=$arItem["ID"]?>">
					<?if(!empty($arItem["PROPERTIES"]["ACTIONS"]["VALUE"])):?>
						<span class="action popup"><?=$arItem["PROPERTIES"]["ACTIONS"]["NAME"]?></span>
					<?endif;?>
					<?if(!empty($arItem["PROPERTIES"]["NEW"]["VALUE"])):?>
						<span class="new popup"><?=$arItem["PROPERTIES"]["NEW"]["NAME"]?></span>
					<?endif;?>
					<?if(!empty($arItem["PROPERTIES"]["SEASONAL"]["VALUE"])):?>
						<span class="season popup"><?=$arItem["PROPERTIES"]["SEASONAL"]["NAME"]?></span>
					<?endif;?>	
					<?if(!empty($arItem["PROPERTIES"]["SALE"]["VALUE"])):?>
						<span class="sale popup"><?=$arItem["PROPERTIES"]["SALE"]["NAME"]?></span>
					<?endif;?>	
					<?if(!empty($arItem["PROPERTIES"]["CREDIT"]["VALUE"])):?>
						<span class="credit popup"><?=$arItem["PROPERTIES"]["CREDIT"]["NAME"]?></span>
					<?endif;?>					
					<a class="image" href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>"><table><tr><td>
						<?if(!empty($arItem["PICTURE"]["src"])):?>
							<img src="<?=$arItem["PICTURE"]["src"]?>" width="<?=$arItem["PICTURE"]["width"]?>" height="<?=$arItem["PICTURE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?else:?>
							<img src="<?=getImageNoPhoto(160, 224)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?endif;?>
					</td></tr></table></a>
					<div class="info">
						<div class="description">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="title" title="<?=$arItem["NAME"]?>"><span><?=$arItem["NAME"]?></span></a>
							<span class="brand"><?=$arItem["BRAND"]?></span>
							<?if(!empty($arItem["PROPERTIES"]["PRICE"]["VALUE"])):?>
								<?if(!empty($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])):?>
									<span class="old_price"><?=CurrencyFormat($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"], "RUB")?></span>
								<?endif;?>
								<span class="price"><?=CurrencyFormat($arItem["PROPERTIES"]["PRICE"]["VALUE"], "RUB")?></span>
							<?endif;?>
							<div class="characteristics">
								<?$no_prop = array("BRAND", "PRICE", "OLD_PRICE", "ACTIONS", "NEW", "SALE", "CREDIT", "SEASONAL", "ATTACHMENTS", "OPTIONS", "VIDEO", "PHOTO", "SERIES", "GROUP_PAGE", "RELATED_PRODUCTS", "INTERESTED_PRODUCTS", "DISPLAY_AS_SERIES", "SHORT_NAME");?>
								<?foreach($arItem["PROPERTIES"] as $prop_key => $arProp):?>
									<?if(!in_array($prop_key, $no_prop) && !empty($arProp["VALUE"])):?>
										<p><?=$arProp["NAME"]?> <span><?=$arProp["VALUE"]?></span></p>
									<?endif;?>
								<?endforeach;?>
							</div>
						</div>
						<input type="hidden" name="item_name" value="<?=$arItem["NAME"]?>" />
						<button class="silver" name="ordering">Заказать</button>
					</div>
					<div class="clear"></div>
					<div class="comparison"></div>
				</div>
			<?endforeach;?>	
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